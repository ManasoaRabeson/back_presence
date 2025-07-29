<?php

namespace App\Http\Controllers;

use App\Http\Requests\VilleStoreRequest;
use App\Models\Ville;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use PhpOffice\PhpSpreadsheet\IOFactory;

class VilleController extends Controller
{
    public function index()
    {
        $vls = DB::table('villes')->select('idVille', 'ville')->orderBy('ville', 'asc')->get();

        $villes = DB::table('ville_codeds as vc')
            ->join('villes', 'vc.idVille', 'villes.idVille')
            ->select('vc.idVille', 'vc.ville_name', 'villes.ville', 'vc.vi_code_postal')
            ->orderBy('vc.vi_code_postal', 'asc')->get();

        return view('superAdmin.villes.index', compact('villes', 'vls'));
    }

    public function store(VilleStoreRequest $request)
    {
        Ville::create($request->validated());
        return back()->with('success', 'Succès');
    }

    public function destroy($id)
    {
        $ville = Ville::where('idVille', $id);

        if ($ville->first()) {
            try {
                $ville->delete();
                return back()->with('success', 'Succès');
            } catch (Exception $e) {
                return back()->with('error', 'Suppression impossible !');
            }
        } else
            return back()->with('error', 'ville introuvable !');
    }

    public function importer(Request $request)
    {

        $request->validate([
            'region' => 'required', // La région doit être l'un des IDs valides
            'excel_file' => 'required|file|mimes:xlsx,xls', // Le fichier doit être un Excel valide
        ]);

        // Récupérer la région sélectionnée
        $regionId = $request->input('region');

        // Récupérer le fichier Excel téléchargé
        $file = $request->file('excel_file');

        try {
            $spreadsheet = IOFactory::load($file);

            $sheet = $spreadsheet->getActiveSheet();
            $rows = $sheet->toArray(); 
            
            $data = array_slice($rows, 1);  

            foreach ($data as $row) {
                $villeName = $row[0];
                $codePostal = $row[1]; 
                
                $existingVille = Ville::where('ville_name', $villeName)
                                    ->where('vi_code_postal', $codePostal)
                                    ->exists(); 

                if (!$existingVille) {
                    Ville::create([
                        'ville_name' => $villeName,
                        'vi_code_postal' => $codePostal,
                        'idVille' => $regionId, 
                    ]);
                }
            }

            return back()->with('success', 'Données importées avec succès !');
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Erreur lors du traitement du fichier Excel : ' . $e->getMessage()]);
        }
    }
}
