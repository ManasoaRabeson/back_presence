<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Drivers\Gd\Driver;
use Intervention\Image\ImageManager;

class BadgeController extends Controller
{
    public function index()
    {
        $countBadge = DB::table('v_badge_projet')
            ->where('idCfp', auth()->user()->id)
            ->where('project_status', 'Terminé')
            ->count();

        // $countBadge = 1;

        $badgeProjects = DB::table('v_badge_projet')
            ->select(
                'idBadge',
                'idModule',
                DB::raw("DATE_FORMAT(dateDebut, '%d %b. %Y') as dateDebut"),
                DB::raw("DATE_FORMAT(dateFin, '%d %b. %Y') as dateFin"),
                'etp_name',
                'idCfp',
                'file_path',
                'file_name',
                'module_name',
                'module_image'
            )
            ->where('idCfp', auth()->user()->id)
            ->where('project_status', 'Terminé')
            ->orderBy('module_name', 'asc')
            ->get();

        return view('CFP.badge.index', compact('countBadge', 'badgeProjects'));
    }

    public function store(Request $request, $idModule)
    {
        try {
            $request->validate([
                'fichier' => 'required|file|mimes:jpeg,jpg,png,webp,avif|max:2048',
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json(['error' => $e->errors()]);
        }
        $file = $request->file('fichier');

        if ($this->badgeExists($idModule)) {
            return response()->json(['error' => 'Cette module a déjà un badge']);
        }

        $driver = new Driver();
        $manager = new ImageManager($driver);

        try {
            $image = $manager->read($file)->toWebp(25);

            $disk = Storage::disk('do');
            $filename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME) . '.webp';
            $path = 'badge/idModule_' . $idModule . '/' . $filename;
            $disk->put($path, $image->__toString());

            DB::table('badges')->insert([
                'idModule' => $idModule,
                'idCfp' => auth()->user()->id,
                'file_path' => $path,
                'file_name' => $filename,
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Erreur lors de l\'enregistrement du badge : ' . $e->getMessage()]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Cet badge a été enregistré avec succès.'
        ]);
    }

    public function edit($idBadge)
    {
        $badge = DB::table('v_badge_projet')
            ->where('idCfp', auth()->user()->id)
            ->where('idBadge', $idBadge)
            ->where('moduleStatut', 1)
            ->orderBy('idBadge', 'desc')
            ->first();

        // dd($badge);

        return response()->json(['badge' => $badge]);
    }

    public function update(Request $request, $idBadge)
    {
        $request->validate([
            'fichier' => 'nullable|file|mimes:jpeg,jpg,png,webp,avif|max:2048',
        ]);

        try {
            return DB::transaction(function () use ($request, $idBadge) {
                $badge = DB::table('badges')
                    ->where('idCfp', auth()->user()->id)
                    ->where('idBadge', $idBadge)
                    ->first();

                if (!$badge) {
                    return response()->json(['error' => 'Badge non trouvé'], 404);
                }

                $data = [];

                if ($request->hasFile('fichier')) {
                    if ($badge->file_path && Storage::exists($badge->file_path)) {
                        Storage::disk('do')->delete($badge->file_path);
                    }

                    $file = $request->file('fichier');
                    $driver = new Driver();
                    $manager = new ImageManager($driver);
                    $image = $manager->read($file)->toWebp(25);

                    $disk = Storage::disk('do');
                    $filename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME) . '.webp';
                    $filePath = 'badge/idModule_' . $badge->idModule . '/' . $filename;
                    $disk->put($filePath, $image->__toString());

                    $data['file_path'] = $filePath;
                    $data['file_name'] = $filename;
                }

                if (!empty($data)) {
                    DB::table('badges')
                        ->where('idBadge', $idBadge)
                        ->update($data);

                    return response()->json(['success' => 'Badge mis à jour avec succès']);
                } else {
                    return response()->json(['error' => 'Aucune donnée à mettre à jour'], 400);
                }
            });
        } catch (Exception $e) {
            return response()->json(['error' => 'Une erreur s\'est produite lors de la modification du badge : ' . $e->getMessage()]);
        }
    }

    public function destroy($idBadge)
    {
        return DB::transaction(function () use ($idBadge) {

            try {
                $filePath = DB::table('badges')
                    ->where('idBadge', $idBadge)
                    ->pluck('file_path')
                    ->first();

                if ($filePath && Storage::disk('do')->exists($filePath)) {
                    Storage::disk('do')->delete($filePath);
                }

                $delete = DB::table('badges')->where('idBadge', $idBadge)->delete();

                if ($delete) {
                    return response()->json(['success' => 'Le badge a bien été supprimée']);
                } else {
                    return response()->json(['error' => 'Une erreur s\'est produite lors de la suppression de cet badge']);
                }
            } catch (\Exception $e) {
                return response()->json(['error' => 'Erreur inattendue']);
            }
        });
    }

    public function getCatalogue()
    {
        $modules = DB::table('v_module_cfps')
            ->select('idModule', 'moduleName')
            ->where('idCustomer', Customer::idCustomer())
            ->where('moduleName', '!=', 'Default module')
            ->where('moduleStatut', 1)
            ->orderBy('nomDomaine', 'asc')
            ->get();

        // dd($modules);

        return response()->json($modules);
    }

    public function getApprenant($idProjet)
    {
        $apprenants = DB::table('v_apprenant_etp_alls')
            ->where('idProjet', $idProjet)
            ->get();
        dd($apprenants);

        return response()->json(['apprenants', $apprenants]);
    }

    function badgeExists($idModule)
    {
        return DB::table('badges')
            ->where('idModule', $idModule)
            ->exists();
    }

    private function formatDate($date, $type = 'j M Y')
    {
        return Carbon::parse($date)->locale('fr')->translatedFormat($type);
    }
}
