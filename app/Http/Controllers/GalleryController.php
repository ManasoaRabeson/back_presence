<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator;
use Intervention\Image\Drivers\Gd\Driver;
use Intervention\Image\ImageManager;

class GalleryController extends Controller
{

    public function addImageGallery(Request $req, $idProjet)
    {
        $gallery = DB::table('gallery')->select('photo')->where('idProjet', $idProjet)->first();

        if ($gallery != null) {
            $folder = 'img/gallery/' . $gallery->photo;

            // if (File::exists($folder)) {
            //     File::delete($folder);
            // }

            $folderPath = public_path('img/gallery/');

            $image_parts = explode(";base64,", $req->image);
            $image_type_aux = explode("image/", $image_parts[0]);
            $image_type = $image_type_aux[1];
            $image_base64 = base64_decode($image_parts[1]);

            $imageName = uniqid() . '.webp';
            $imageFullPath = $folderPath . $imageName;

            file_put_contents($imageFullPath, $image_base64);

            DB::table('gallery')->where('idProjet', $idProjet)->update([
                'photo' => $imageName,
            ]);
            return response()->json([
                'success' => 'Image Uploaded Successfully',
                'imageName' =>  $imageName
            ]);
        }
    }

    public function getAllFolder(Request $request)
    {
        $folders = DB::table('dossiers')
            ->select('idDossier', 'nomDossier')
            ->where('idCfp', Customer::idCustomer())
            ->whereYear('created_at', $request->year)
            ->orderBy('nomDossier')
            ->get();

        $data = [];

        foreach ($folders as $folder) {
            $idDossier = $folder->idDossier;
            $data[] = [
                'idDossier' => $idDossier,
                'nomDossier' => $folder->nomDossier,
                'image' => $this->getFirstImage($idDossier),
                'countImage' => $this->countImageByFolder($idDossier)
            ];
        }

        $allFolder = view('CFP.gallery.folderList', [
            'data' => $data,
            'year' => $request->year
        ])->render();

        return response()->json($allFolder);
    }

    public function getAllFolderOrder(Request $request)
    {
        $folders = DB::table('dossiers')
            ->select('idDossier', 'nomDossier')
            ->where('idCfp', Customer::idCustomer())
            ->whereYear('created_at', $request->year)
            ->orderByDesc('created_at')
            ->get();

        $data = [];

        foreach ($folders as $folder) {
            $idDossier = $folder->idDossier;
            $data[] = [
                'idDossier' => $idDossier,
                'nomDossier' => $folder->nomDossier,
                'image' => $this->getFirstImage($idDossier),
                'countImage' => $this->countImageByFolder($idDossier)
            ];
        }

        $allFolder = view('CFP.gallery.folderList', [
            'data' => $data,
            'year' => $request->year
        ])->render();

        return response()->json($allFolder);
    }

    private function getFirstImage($id)
    {
        $projectIds = $this->getProjectByFolder($id);

        $image = DB::table('images')->select('url')->whereIn('idProjet', $projectIds)->first();

        return $image->url ?? null;
    }

    public function getAllGallery()
    {
        $data = DB::table('dossiers')
            ->select(DB::raw('YEAR(created_at) as year'))
            ->where('idCfp', Customer::idCustomer())
            ->groupBy(DB::raw('YEAR(created_at)'))
            ->get();

        return view('CFP.gallery.index', compact('data'));
    }

    private function countImageByFolder($id)
    {
        $projectIds = $this->getProjectByFolder($id);

        $imageCount = DB::table('images')->select('idImages')->whereIn('idProjet', $projectIds)->get();

        return count($imageCount);
    }

    public function getGalleryByFolder(Request $request)
    {
        $projectIds = $this->getProjectByFolder($request->id);

        $data = [];

        foreach ($projectIds as $projectId) {
            $data[] = [
                $this->getGaleryByProject($projectId)
            ];
        }

        $projectWithImage = view('CFP.gallery.imageList', [
            'data' => $data,
        ])->render();

        return response()->json([
            'data' => $projectWithImage
        ]);
    }

    private function getProjectByFolder($id)
    {
        $projects = DB::table('projets')
            ->where('idDossier', $id)
            ->pluck('idProjet');
        return $projects;
    }

    private function getGaleryByProject($id)
    {
        $project = $this->getProject($id);
        $images = $this->getImageByProject($id);
        $result = [
            'moduleName' => $project->moduleName,
            'dateDebut' => $project->dateDebut,
            'dateFin' => $project->dateFin,
            'ville' => $project->ville_name,
            'imageCount' => count($images),
            'images' => $images
        ];
        return $result;
    }

    private function getProject($id)
    {
        $project = DB::table('projets as P')
            ->select('P.dateDebut', 'P.dateFin', 'M.moduleName', 'V.ville_name')
            ->join('mdls as M', 'M.idModule', 'P.idModule')
            ->join('ville_codeds as V', 'V.id', 'P.idVilleCoded')
            ->where('P.idProjet', $id)
            ->first();
        return $project;
    }

    private function getImageByProject($id)
    {
        $images = DB::table('images')
            ->select('url')
            ->where('idProjet', $id)
            ->get();
        return $images;
    }

    public function allImage(Request $request)
    {
        $projectIds = $this->getProjectByFolder($request->id);

        $images = DB::table('images')
            ->select('url')
            ->whereIn('idProjet', $projectIds)
            ->get();

        $minAndMaxDate = DB::table('dossiers as D')
            ->join('projets as P', 'P.idDossier', 'D.idDossier')
            ->select(DB::raw('MIN(P.dateDebut) as minDate'), DB::raw('MAX(P.dateFin) as maxDate'))
            ->where('D.idDossier', $request->id)
            ->first();

        $dossier = DB::table('dossiers')
            ->select('nomDossier', DB::raw('YEAR(created_at) as year'))
            ->where('idDossier', $request->id)
            ->first();

        $images = view('CFP.gallery.imageList', [
            'data' => $images,
            'date' => $minAndMaxDate,
            'nomDossier' => $dossier->nomDossier,
            'year' => $dossier->year
        ])->render();

        return response()->json($images);
    }
}
