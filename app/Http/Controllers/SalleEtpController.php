<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class SalleEtpController extends Controller
{
    public function idEtp()
    {
        $customer = DB::select("SELECT employes.idEmploye, employes.idCustomer, customers.idTypeCustomer FROM employes INNER JOIN customers ON employes.idCustomer = customers.idCustomer WHERE idEmploye = ?", [Auth::user()->id]);
        return $customer[0]->idCustomer;
    }

    public function list()
    {
        $salles = DB::table('villes')
            ->join('salles', 'salles.idVille', 'villes.idVille')
            ->select('idSalle', 'salles.idCustomer', 'salle_name', 'salle_quartier AS quartier', 'ville')
            ->where('salles.idCustomer', $this->idEtp())
            ->get();

        $events = [];
        foreach ($salles as $salle) {
            $events[] =  [
                'id' => $salle->idSalle,
                'name' => $salle->salle_name
            ];
        }
        return response()->json([
            'salles' => $events
        ]);
    }

    public function getVillesByPostalCode(Request $request)
    {
        $codePostal = $request->input('cp');

        if (!$codePostal) {
            return response()->json([]);
        }

        $villes = DB::table('ville_codeds')
            ->join('villes', 'villes.idVille', '=', 'ville_codeds.idVille')
            ->where('ville_codeds.vi_code_postal', 'LIKE', $codePostal . '%')
            ->get(['ville_codeds.id', 'ville_codeds.ville_name']);

        return response()->json($villes);
    }

    public function index()
    {
        $villes = DB::table('villes')->select('idVille', 'ville')->get();

        // $salles = DB::table('villes')
        //     ->join('salles', 'salles.idVille', 'villes.idVille')
        //     ->select('idSalle', 'salles.idCustomer', 'salle_name', 'salle_quartier', 'salle_rue', 'salle_code_postal', 'ville')
        //     ->where(function ($query) {
        //         $query->where('salles.idCustomer', $this->idEtp())
        //             ->where('salles.salle_name', '!=', 'null');
        //     })
        //     ->orderBy('salle_name', 'asc')
        //     ->get();
        $salles = [];
        // jordy
        $ville_addSalle = DB::table('villes')->select('idVille', 'ville')->orderBy('ville', 'asc')->get();
        $lieux_addSalle = DB::table('lieux')
            ->select('idLieu', 'li_name')
            ->get();

        $idEtp =  $this->idEtp();
        $lieux_salles = DB::table('v_liste_lieux as vll')
            ->select(
                'vll.idLieu',
                'vll.li_name',
                'lieux.li_quartier',
                'lieux.li_rue',
                'vll.idVille',
                'vll.idLieuType',
                'vll.idVilleCoded',
                'vll.ville',
                'vll.lt_name',
                'vll.ville_name_coded',
                'vll.vi_code_postal',
                'vll.idCustomer',
                'vll.date_added',
                'vll.idCfp',
                'vll.idEntreprise',
                'salles.idSalle',
                'salles.salle_name',
                'salles.salle_image',
                'ville_codeds.ville_name',
                'pefc.idCfp',
                'customers.customerName',
            )
            ->leftJoin('lieu_publics as lp', 'lp.idLieu', '=', 'vll.idLieu')
            ->leftJoin('place_etp_from_cfps as pefc', 'pefc.idLieu', '=', 'vll.idLieu')
            ->leftJoin('customers', 'customers.idCustomer', 'pefc.idCfp')
            ->join('lieux', 'lieux.idLieu', 'vll.idLieu')
            ->leftJoin('ville_codeds', 'ville_codeds.id', 'lieux.idVilleCoded')
            ->leftJoin('salles', 'salles.idLieu', 'vll.idLieu')
            ->leftJoin('lieu_privates as lpv', 'lpv.idLieu', '=', 'vll.idLieu')
            ->where(function ($query) use ($idEtp) {
                $query->where('pefc.idEntreprise', $idEtp)
                    ->orWhereNull('pefc.idEntreprise');
            })
            ->whereNull('lpv.idLieu')
            ->orderBy('vll.li_name', 'asc')
            ->get();

        $lieu_types = DB::table('lieu_types')
            ->get();
        return view('ETP.salles.index', compact('ville_addSalle', 'lieux_addSalle', 'lieux_salles', 'lieu_types'));

        // return view('ETP.salles.index', compact(['villes', 'salles']));
    }

    public function loadVille()
    {
        $villes = DB::table('villes')->select('idVille', 'ville')->get();

        return response()->json(['villes' => $villes]);
    }

    public function store(Request $req)
    {
        $validation = Validator::make($req->all(), [
            'salle_name' => 'required|max:45|min:3|max:250',
            'idVille' => 'required|exists:villes,idVille'
        ]);

        if ($validation->fails()) {
            return response()->json($validation->messages());
        } else {
            try {
                $insert = DB::table('salles')->insert([
                    'salle_name' => $req->salle_name,
                    'salle_quartier' => $req->salle_quartier,
                    'salle_rue' => $req->salle_rue,
                    'salle_code_postal' => $req->salle_code_postal,
                    'idVille' => $req->idVille,
                    'idCustomer' => $this->idEtp(),
                ]);

                if ($insert) {
                    return response()->json(["success" => "Succès"]);
                } else {
                    return response()->json(["error" => "Erreur inconnue !"]);
                }
            } catch (Exception $e) {
                return response()->json(["error" => "Erreur inconnue !", $e]);
            }
        }
    }

    // public function getAllSalle()
    // {
    //     $salles = DB::table('lieux AS li')
    //         ->join('salles As sll', 'sll.idLieu', 'li.idLieu')
    //         ->join('ville_codeds AS vcd', 'vcd.idLieu', 'li.idLieu')
    //         //->join('salles', 'salles.idLieu', 'vcd.idLieu')
    //         ->select('sll.idSalle', 'sll.salle_name', 'li.li_quartier', 'li.li_quartier', 'li.li_rue', 'vcd.vi_code_postal', 'vcd.ville_name')
    //         // ->where('salles.idCustomer', $this->idEtp())
    //         ->where('sll.salle_name', '!=', 'null')
    //         ->get();

    //     return response()->json(['salles' => $salles]);
    // }

    public function edit($idSalle)
    {
        $villes = DB::table('villes')->select('idVille', 'ville')->get();

        $salle = DB::table('villes')
            ->join('salles', 'salles.idVille', 'villes.idVille')
            ->select('idSalle', 'salle_name', 'salle_quartier', 'salle_rue', 'salle_code_postal', 'villes.ville')
            ->where('idSalle', $idSalle)
            ->first();

        return response()->json([
            'villes' => $villes,
            'salle' => $salle
        ]);
    }

    public function update(Request $req, $idSalle)
    {
        $validate = Validator::make($req->all(), [
            'salle_name' => 'required|max:100|min:3',
            'idVille' => 'required|exists:villes,idVille',
        ]);

        if ($validate->fails()) {
            return response()->json([['error' => $validate->messages()]]);
        } else {
            $update = DB::table('salles')->where('idSalle', $idSalle)->update([
                'salle_name' => $req->salle_name,
                'salle_quartier' => $req->salle_quartier,
                'salle_rue' => $req->salle_rue,
                'salle_code_postal' => $req->salle_code_postal,
                'idVille' => $req->idVille
            ]);

            if ($update) {
                return response()->json([
                    "success" => "Succès"
                ]);
            } else {
                return response()->json([
                    "error" => "Erreur inconnue !"
                ]);
            }
        }
    }

    public function destroy($idSalle)
    {
        try {
            $delete = DB::table('salles')->where('idSalle', $idSalle)->delete();

            if ($delete) {
                return response()->json(["success" => "Supprimée avec succès"]);
            } else {
                return response()->json(['error' => 'Impossible de supprimer cette salle !']);
            }
        } catch (Exception $e) {
            return response()->json(['error' => 'Impossible de supprimer cette salle !']);
        }
    }
}
