<?php

namespace App\Http\Controllers;

use App\Http\Requests\SalleStoreRequest;
use App\Models\Customer;
use App\Models\Salle;
use Exception;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Intervention\Image\Drivers\Gd\Driver;
use Intervention\Image\ImageManager;

class SalleController extends Controller
{
    public function allSalles()
    {
        $sls = DB::table('v_list_salles')
            ->select('idSalle', 'salle_name', 'salle_quartier', 'salle_rue', 'salle_image', 'ville', 'ville_name_coded', 'vi_code_postal', 'lt_name', 'idLieuType', 'lieu_name')
            ->where(function ($q) {
                $q->where('idLieuType', 1)
                    ->orWhere('idCustomer', Customer::idCustomer());
            })
            ->orderBy('salle_name', 'asc');

        return $sls;
    }

    public function allLieux()
    {
        $ls = DB::table('v_liste_lieux')
            ->select('idLieu', 'li_name', 'idVille', 'idCustomer', 'idLieuType', 'vi_code_postal', 'ville')
            ->where(function ($q) {
                $q->where('idLieuType', 1)
                    ->orWhere('idCustomer', Customer::idCustomer());
            });

        return $ls;
    }

    public function index()
    {
        $salles = $this->allSalles()->get();
        $lieux = $this->allLieux()->get();

        switch (Customer::typeCustomer()) {
            case 1:
                return view('CFP.salles.index', compact('salles', 'lieux'));
                break;
            case 2:
                return view('ETP.salles.index', 'salles', 'lieux');
                break;
            default:
                return abort(404);
                break;
        }
    }

    public function storeImageDigitalOcean(Request $req)
    {
        try {
            $driver = new Driver();
            $manager = new ImageManager($driver);

            $file = $req->file('salle_image');
            if (!$file) {
                throw new Exception("Aucune image n'a été téléchargée ou le fichier est invalide.");
            }

            // Lire le fichier et vérifier sa validité
            $image = $manager->read($file)->toWebp(25);
            $imageName = $file->getClientOriginalName();
            $filePath = 'img/salles/' . $imageName;

            if (!$imageName) {
                throw new Exception("Le nom du fichier est vide ou invalide.");
            }

            if (empty($image)) {
                throw new Exception("Le fichier d'image est vide ou invalide.");
            }

            // Ajout de logging avant d'écrire dans le stockage
            Log::info("Tentative d'écriture de l'image dans DigitalOcean", [
                'filePath' => $filePath,
                'imageName' => $imageName,
            ]);

            Storage::disk('do')->put($filePath, $image, 'public');

            return $imageName;
        } catch (Exception $e) {
            // Log l'erreur
            Log::error("Erreur dans storeImageDigitalOcean : " . $e->getMessage(), ['stack' => $e->getTraceAsString()]);
            throw $e; // Laisser l'exception remonter
        }
    }

    public function storeSalle(Request $req)
    {
        try {
            $validated = $req->validated();

            $image_name = isset($validated['salle_image'])
                ? $this->storeImageDigitalOcean($req)
                : null;

            DB::table('salles')->insert([
                'salle_name' => $validated['salle_name'],
                'idLieu' => $validated['idLieu'],
                'salle_image' => $image_name
            ]);
        } catch (Exception $e) {
            // Consigner l'erreur dans les logs
            Log::error("Erreur dans storeSalle : " . $e->getMessage(), ['stack' => $e->getTraceAsString()]);
            throw $e; // Laisser l'erreur remonter
        }
    }

    public function store(SalleStoreRequest $request)
    {
        try {
            $this->storeSalle($request);
            return response()->json(['success' => 'Succès']);
        } catch (Exception $e) {
            // Consigner l'erreur dans les logs
            Log::error("Erreur dans store : " . $e->getMessage(), ['stack' => $e->getTraceAsString()]);
            return response()->json(['error' => 'Une erreur s\'est produite. Veuillez réessayer.'], 500);
        }
    }

    public function destroy($id)
    {
        try {
            $salle = Salle::find($id);
            $salle_projet = DB::table('projets')
                ->where('idSalle', $id)
                ->get();
            if (count($salle_projet) > 0) {
                return response([
                    'message' => 'Impossible de supprimer cette salle car il est déja rattaché à un projet!',
                    'status' => 401
                ]);
            } else {
                if ($salle) {
                    $salle->delete();
                    return response([
                        'message' => 'Supprimée avec succès',
                        'status' => 200
                    ]);
                } else {
                    return response([
                        'message' => 'Salle introuvable',
                        'status' => 404
                    ]);
                }
            }
        } catch (Exception $e) {
            return response([
                'message' => 'Impossible de supprimer cette salle !' . $e->getMessage(),
                'status' => 401
            ]);
        }
    }

    public function getSalleDetails(Request $request)
    {
        $idLieu = $request->query('idLieu');
        $idSalle = $request->query('idSalle');

        if ($idSalle == null) {
            $lieuSalle = DB::table('lieux')
                ->select(
                    'lieux.*',
                    DB::raw('NULL as idSalle'),
                    DB::raw('NULL as salle_name'),
                    DB::raw('NULL as salle_image'),
                    'ville_codeds.ville_name',
                    'ville_codeds.vi_code_postal',
                    'lieu_types.idLieuType',
                    'lieu_types.lt_name',
                    'villes.idVille',
                )
                ->join('ville_codeds', 'ville_codeds.id', 'lieux.idVilleCoded')
                ->join('lieu_types', 'lieu_types.idLieuType', 'lieux.idLieuType')
                ->join('villes', 'villes.idVille', 'ville_codeds.idVille')
                ->where('lieux.idLieu', $idLieu)
                ->first();
        } else if ($idSalle != null) {
            $lieuSalle = DB::table('lieux')
                ->select('lieux.*', 'salles.idSalle', 'salles.salle_name', 'salles.salle_image', 'ville_codeds.ville_name', 'ville_codeds.vi_code_postal', 'lieu_types.idLieuType', 'lieu_types.lt_name', 'villes.idVille')
                ->join('salles', 'salles.idLieu', 'lieux.idLieu')
                ->join('ville_codeds', 'ville_codeds.id', 'lieux.idVilleCoded')
                ->join('lieu_types', 'lieu_types.idLieuType', 'lieux.idLieuType')
                ->join('villes', 'villes.idVille', 'ville_codeds.idVille')
                ->where('lieux.idLieu', $idLieu)
                ->where('salles.idSalle', $idSalle)
                ->first();
        }

        if (!$lieuSalle) {
            return response()->json(['error' => 'Données non trouvées'], 404);
        }

        return response()->json([
            'idLieu' => $lieuSalle->idLieu,
            'idSalle' => $lieuSalle->idSalle,
            'li_name' => $lieuSalle->li_name,
            'salle_name' => $lieuSalle->salle_name,
            'idVilleCoded' => $lieuSalle->idVilleCoded,
            'li_quartier' => $lieuSalle->li_quartier,
            'vi_code_postal' => $lieuSalle->vi_code_postal,
            'idVille' => $lieuSalle->idVille,
            'ville_name' => $lieuSalle->ville_name,
            'idLieuType' => $lieuSalle->idLieuType,
            'lt_name' => $lieuSalle->lt_name,
            'salle_image' => $lieuSalle->salle_image,
        ]);
    }

    public function updateSalleLieu(Request $request)
    {

        try {

            $validate = Validator::make($request->all(), [
                'salle_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:3000',
            ]);

            $idLieu = $request->input('idLieu');
            $idSalle = $request->input('idSalle');

            if ($request->input("idVilleUpdate") != null &&  $request->input("idVilleCodedUpdate") != null) {
                $lieuData = [
                    'li_name' => $request->input("li_name"),
                    'li_quartier' => $request->input("li_quartier"),
                    'idVille' => $request->input("idVilleUpdate"),
                    'idVilleCoded' => $request->input("idVilleCodedUpdate"),
                ];
            } else {
                $lieuData = [
                    'li_name' => $request->input("li_name"),
                    'li_quartier' => $request->input("li_quartier"),
                ];
            }

            DB::table('lieux')
                ->where('idLieu', $idLieu)
                ->update($lieuData);


            if ($request->salle_image == null) {
                $salleData = [
                    'salle_name' => $request->input("salle_name"),
                    'idLieu' => $idLieu,
                ];
            } else {
                if ($validate->fails()) {
                    return back()->with('error', 'Image invalide ou taille supérieure à 3 Mo.');
                } else {
                    $salle = DB::table('salles')->where('idSalle', $idSalle)->first();
                    if (!empty($salle->salle_image)) {
                        Storage::disk('do')->delete('img/salles/' . $salle->salle_image);
                    }

                    $file = $request->file('salle_image');
                    if ($file != null) {
                        $driver = new Driver();
                        $manager = new ImageManager($driver);

                        $image = $manager->read($file);

                        $compressedImage = $image->toWebp(25);
                        $quality = 25;
                        while (strlen($compressedImage) > 204800 && $quality > 1) {
                            $quality -= 1;
                            $compressedImage = $image->toWebp($quality);
                        }

                        $imageName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME) . '-' . uniqid() . '.webp';
                        $filePath = 'img/salles/' . $imageName;

                        Storage::disk('do')->put($filePath, $compressedImage, 'public');

                        $salleData = [
                            'salle_name' => $request->input("salle_name"),
                            'idLieu' => $idLieu,
                            'salle_image' => $imageName,
                        ];
                    } else {
                        return back()->withErrors(['error' => 'Impossible pour le moment de modifier cet image']);
                    }
                }
            }

            DB::table('salles')
                ->where('idSalle', $idSalle)
                ->update($salleData);

            return back()->with('success', 'Succès');
        } catch (QueryException $e) {
            return back()->withErrors(['error' => 'Erreur de la base de données: ' . $e->getMessage()]);
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Erreur inconnue: ' . $e->getMessage()]);
        }
    }
}
