<?php

namespace App\Http\Controllers;

use App\Models\Module;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ModuleRessourceController extends Controller
{
    public function store(Request $req, int $idModule)
    {
        $validate = Validator::make($req->all(), [
            'module_ressource_file' => 'required|max:50000|not_in:exe,bat,sh,msi,cmd',
        ]);

        if ($validate->fails()) {
            return back()->with('error', $validate->messages());
        } else {
            if ($req->hasFile('module_ressource_file')) {
                $module = DB::table('mdls')->select('idModule')->where('idModule', $idModule)->first();

                $files = $req->module_ressource_file;
                $all_files = [];

                foreach ($files as $key => $file) {
                    $fileSize[$key] = $file->getSize();
                    $fileSizeInMb[$key] = round($fileSize[$key] / (1024 * 1024), 2);
                    $fileName[$key] = $file->getClientOriginalName();
                    $fileExtension[$key] = $file->getClientOriginalExtension();

                    $disk = Storage::disk('do');
                    $path = $disk->putFile('ressource/projet/' . $idModule, $file);

                    $all_files[] = [
                        'module_ressource_name' => $fileName[$key],
                        'module_ressource_extension' => $fileExtension[$key],
                        'taille' => $fileSizeInMb[$key],
                        'idModule' => $module->idModule,
                        'file_path' => $path
                    ];
                }

                DB::table('module_ressources')->insert($all_files);

                return back()->with('success', 'Fichier Importé(s) avec succès');
            }
        }
    }

    public function destroy(int $idModuleRessource)
    {
        try {
            $file = DB::table('module_ressources')
                ->select('file_path', 'module_ressource_name')
                ->where('idModuleRessource', $idModuleRessource)
                ->first();

            if ($file) {
                $disk = Storage::disk('do');

                if ($disk->exists($file->file_path)) {
                    $disk->delete($file->file_path);
                }

                DB::table('module_ressources')->where('idModuleRessource', $idModuleRessource)->delete();

                return back()->with('success', "Fichier '" . $file->module_ressource_name . "' supprimé avec succès");
            } else {
                return back()->with('error', "Fichier introuvable dans la base de données");
            }
        } catch (Exception $e) {
            return back()->with('error', "Erreur inconnue lors de la suppression");
        }
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
