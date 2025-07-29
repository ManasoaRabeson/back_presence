<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class EmargementController extends Controller
{
    public function index()
    {
        $emgs = DB::table('detail_emargements')
            ->join('emargements', 'detail_emargements.idEmargement', 'emargements.idEmargement')
            ->select('emargements.notes', 'detail_emargements.heureDebut', 'detail_emargements.heureFin', 'present')
            ->get();
    }

    public function getSeance($idProjet)
    {
        $seances = DB::table('v_emargement_appr')
            ->select('idProjet', 'idEmploye', 'idSeance')
            ->where('idProjet', $idProjet)
            ->get();

        return response()->json([
            'seances' => $seances
        ]);
    }

    public function checkEmg($idSeance, $idEmploye)
    {
        $checkEmg = DB::table('emargements')
            ->select('idSeance', 'idEmploye')
            ->where('idSeance', $idSeance)
            ->where('idEmploye', $idEmploye)
            ->count();

        return response()->json([
            'checkEmg' => $checkEmg
        ]);
    }

    public function edit($idProjet)
    {
        $emargements = DB::table('emargements')
            ->select('idProjet', 'idEmploye', 'idSeance', 'isPresent')
            ->where('idProjet', $idProjet)
            ->get();

        return response()->json(['emargements' => $emargements]);
    }

    public function show($idProjet, $idEmploye, $idSession, $idSeance)
    {
        $appr = DB::table('v_list_apprenants')
            ->select('idProjet', 'idEmploye', 'idSession', 'idSeance', 'idCustomer', 'photoEmp', 'matricule', 'name', 'firstName')
            ->where('idProjet', '=', $idProjet)
            ->where('idEmploye', '=', $idEmploye)
            ->where('idSession', '=', $idSession)
            ->where('idSeance', '=', $idSeance)
            ->get();

        return response()->json($appr);
    }

    // public function store(Request $req){
    //     $req->validate([
    //         'idEmploye' => 'required|exists:apprenants,idEmploye',
    //         'idSeance' => 'required|exists:seances,idSeance'
    //     ]);
    //     try {
    //         DB::table('emargements')->insert([
    //             'idSeance' => $req->idSeance,
    //             'idEmploye' => $req->idEmploye,
    //             'isPresent' => $req->isPresent,
    //             'idProjet' => $req->idProjet
    //         ]);

    //         return response()->json(["success" => "Succès"]);
    //     } catch (Exception $e) {
    //         return response()->json(["error" => "Emargement déjà éffectuée !"]);
    //     }
    // }
    public function store(Request $req)
    {
        $presences = $req->all();

        try {
            foreach ($presences as $presence) {
                $validator = Validator::make($presence, [
                    'idEmploye' => 'required|exists:apprenants,idEmploye',
                    'idSeance' => 'required|exists:seances,idSeance',
                    'isPresent' => 'required|integer',
                    'idProjet' => 'required|integer',
                ]);

                if ($validator->fails()) {
                    return response()->json([
                        'error' => 'Validation échouée',
                        'details' => $validator->errors()
                    ], 422);
                }

                DB::table('emargements')->updateOrInsert(
                    [
                        'idSeance' => $presence['idSeance'],
                        'idEmploye' => $presence['idEmploye']
                    ],
                    [
                        'isPresent' => $presence['isPresent'],
                        'idProjet' => $presence['idProjet']
                    ]
                );
            }

            return response()->json(["success" => "Succès"]);
        } catch (Exception $e) {
            return response()->json(["error" => "Erreur : " . $e->getMessage()], 500);
        }
    }


    public function update(Request $request, $idProjet, $isPresent)
    {
        try {
            $data = $request->all();

            // ✅ Récupération unique du projet
            $projet = DB::table('v_projet_cfps')->where('idProjet', $idProjet)->first();

            foreach ($data as $entry) {
                $id_employe = DB::table('emargements')->where('idEmploye', $entry['idEmploye'])->where('idSeance', $entry['idSeance'])->where('idProjet', $idProjet)->exists();
                if ($id_employe) {
                    DB::table('emargements')
                        ->where('idSeance', $entry['idSeance'])
                        ->where('idProjet', $idProjet)
                        ->where('idEmploye', $entry['idEmploye'])
                        ->update([
                            'isPresent' => $isPresent,
                        ]);
                } else {
                    DB::table('emargements')
                        ->where('idSeance', $entry['idSeance'])
                        ->where('idProjet', $idProjet)
                        ->where('idEmploye', $entry['idEmploye'])
                        ->insert([
                            'isPresent' => $isPresent,
                            'idProjet' => $idProjet,
                            'idSeance' => $entry['idSeance'],
                            'idEmploye' => $entry['idEmploye']
                        ]);
                }
            }

            return response()->json(['success' => 'Modification avec succes.', 'projet' => $projet]);
        } catch (\Exception $e) {
            return response()->json(["error" => $e->getMessage()]);
        }
    }

    public function countGlobalEmg($idSeance)
    {
        $countPresence = DB::select("SELECT isPresent, COUNT(idEmploye) AS countPresent FROM emargements WHERE idSeance = ? GROUP BY isPresent", [$idSeance]);

        return response()->json(['countPresence' => $countPresence]);
    }
    public function destroy($idProjet, $idSeance, $idEmploye)
    {
        $emargement = DB::table('emargements')
            ->where('idProjet', $idProjet)
            ->where('idSeance', $idSeance)
            ->where('idEmploye', $idEmploye);

        if ($emargement->exists()) {
            $emargement->delete();

            return response()->json([
                'status' => 200,
                'message' => 'Succes'
            ]);
        } else
            return response()->json([
                'status' => 404,
                'message' => 'Introuvable !'
            ], 404);
    }
}
