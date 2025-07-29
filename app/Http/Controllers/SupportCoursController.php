<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\StreamedResponse;

class SupportCoursController extends Controller
{
    public function support()
    {
        return view('formateurs.support.index');
    }

    public function supportCfp()
    {
        return view('CFP.supportCours.index');
    }

    public function supportEmp()
    {
        $userId = Auth::user()->id;

        // Récupérer les projets de l'employé
        $projects = DB::table('v_projet_emps')
            ->select(
                'idProjet',
                'project_reference',
                'dateDebut',
                'dateFin',
                'module_name',
                'module_image',
                'ville',
                'salle_name',
                'salle_quartier',
                'salle_code_postal',
                'idModule'
            )
            ->where('idEmploye', $userId)
            // ->where('headYear', Carbon::now()->format('Y'))
            ->orderBy('dateDebut', 'asc')
            ->get();

        $moduleIds = $projects->pluck('idModule')->unique()->toArray();

        $moduleRessources = DB::table('module_ressources')
            ->select('idModuleRessource', 'taille', 'module_ressource_name', 'module_ressource_extension', 'idModule')
            ->whereIn('idModule', $moduleIds)
            ->get()
            ->groupBy('idModule');

        $projets = [];
        foreach ($projects as $project) {
            $projets[] = [
                'idProjet' => $project->idProjet,
                'project_reference' => $project->project_reference,
                'dateDebut' => $project->dateDebut,
                'dateFin' => $project->dateFin,
                'module_name' => $project->module_name,
                'module_image' => $project->module_image,
                'ville' => $project->ville,
                'salle_name' => $project->salle_name,
                'salle_quartier' => $project->salle_quartier,
                'salle_code_postal' => $project->salle_code_postal,
                'idModule' => $project->idModule,
                'module_ressources' => $moduleRessources->get($project->idModule, [])
            ];
        }
        // dd($projets);

        // Retourner la vue avec les projets et leurs ressources
        return view('employes.supports.index', [
            'projets' => $projets,
        ]);
    }

    public function getModuleRessource($idModule)
    {
        return DB::table('module_ressources')
            ->select('idModuleRessource', 'taille', 'module_ressource_name', 'module_ressource_extension', 'idModule')
            ->where('idModule', $idModule)
            ->get();
    }

    public function download(int $idModuleRessource)
    {
        $file = DB::table('module_ressources')
            ->select('file_path', 'module_ressource_name')
            ->where('idModuleRessource', $idModuleRessource)
            ->first();

        if ($file) {
            $disk = Storage::disk('do');

            if ($disk->exists($file->file_path)) {
                return new StreamedResponse(function () use ($disk, $file) {
                    echo $disk->get($file->file_path);
                }, 200, [
                    'Content-Type' => $disk->mimeType($file->file_path),
                    'Content-Disposition' => 'attachment; filename="' . $file->module_ressource_name . '"',
                ]);
            }
        }

        abort(404);
    }
}
