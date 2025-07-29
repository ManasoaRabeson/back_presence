<?php

namespace App\Http\Controllers;

use App\Http\Requests\LieuxStoreRequest;
use App\Http\Requests\PlaceEtpFromCfpStore;
use App\Models\Customer;
use App\Models\Lieux;
use App\Models\PlaceEtpFromCfp;
use App\Models\Salle;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use function PHPUnit\Framework\returnSelf;

class LieuxController extends Controller
{
    public function allVilles()
    {
        $vs = DB::table('villes')->select('idVille', 'ville')->orderBy('ville', 'asc');
        return $vs;
    }

    public function allVilleCodeds($idVille)
    {
        $villeCodeds = DB::table('ville_codeds')
            ->select('id', 'ville_name', 'vi_code_postal')
            ->where('idVille', $idVille);

        if ($villeCodeds->count() <= 0)
            return response([
                'message' => 'Aucun résultat trouvé !',
                'status' => 404
            ]);

        return response([
            'villeCodeds' => $villeCodeds->get(),
            'status' => 200
        ]);
    }

    public function allLieux()
    {
        $ls = DB::table('v_liste_lieux')
            ->select('idLieu', 'li_name', 'lt_name', 'ville_name_coded as ville_name', 'vi_code_postal', 'ville', 'idLieuType')
            ->where(function ($q) {
                $q->where('idLieuType', 1)
                    ->orWhere('idCustomer', Customer::idCustomer())
                    ->orWhere('idCfp', Customer::idCustomer());
            });

        return $ls;
    }

    public function searchJson(Request $request)
    {
        $search = $request->input('search');
        $idCfp = Customer::idCustomer();

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
                'customers.customerName'
            )
            ->leftJoin('lieu_publics as lp', 'lp.idLieu', '=', 'vll.idLieu')
            ->leftJoin('place_etp_from_cfps as pefc', 'pefc.idLieu', '=', 'vll.idLieu')
            ->leftJoin('customers', 'customers.idCustomer', 'pefc.idEntreprise')
            ->join('lieux', 'lieux.idLieu', 'vll.idLieu')
            ->leftJoin('ville_codeds', 'ville_codeds.id', 'lieux.idVilleCoded')
            ->leftJoin('salles', 'salles.idLieu', 'vll.idLieu')
            ->leftJoin('lieu_privates as lpv', 'lpv.idLieu', '=', 'vll.idLieu')
            ->where(function ($query) use ($idCfp) {
                $query->where('lpv.idCustomer', $idCfp)
                    ->orWhereNull('lpv.idCustomer');
            })
            ->where(function ($query) use ($idCfp) {
                $query->where('pefc.idCfp', $idCfp)
                    ->orWhereNull('pefc.idCfp');
            });

        // Appliquer le filtre de recherche
        if ($search) {
            $lieux_salles->where(function ($query) use ($search) {
                $query->where('vll.li_name', 'like', '%' . $search . '%')
                    ->orWhere('salles.salle_name', 'like', '%' . $search . '%')
                    ->orWhere('lieux.li_quartier', 'like', '%' . $search . '%')
                    ->orWhere('vll.ville', 'like', '%' . $search . '%');
            });
        }

        $lieux_salles = $lieux_salles->orderBy('vll.li_name', 'asc')->paginate(10);

        // Retourner la vue de la table avec les résultats sous forme de HTML
        $view = view('CFP.lieux.table_body', compact('lieux_salles'))->render();

        return response()->json($view);
    }


    public function index()
    {
        $ville_addSalle = $this->allVilles()->get();
        $lieux_addSalle = DB::table('lieux')
            ->select('idLieu', 'li_name', 'ville_name', 'vi_code_postal')
            ->join('ville_codeds', 'ville_codeds.id', 'lieux.idVilleCoded')
            ->orderBy('li_name', 'asc')
            ->get();

        $idCfp = Customer::idCustomer();
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
                'customers.customerName',
                'cc.customerName as cfpName'
            )
            ->leftJoin('lieu_publics as lp', 'lp.idLieu', '=', 'vll.idLieu')
            ->leftJoin('place_etp_from_cfps as pefc', 'pefc.idLieu', '=', 'vll.idLieu')
            ->leftJoin('customers', 'customers.idCustomer', 'pefc.idEntreprise')
            ->join('lieux', 'lieux.idLieu', 'vll.idLieu')
            ->leftJoin('ville_codeds', 'ville_codeds.id', 'lieux.idVilleCoded')
            ->leftJoin('salles', 'salles.idLieu', 'vll.idLieu')
            ->leftJoin('lieu_privates as lpv', 'lpv.idLieu', '=', 'vll.idLieu')
            ->leftJoin('customers as cc', 'cc.idCustomer', 'lpv.idCustomer')
            ->where(function ($query) use ($idCfp) {
                $query->where('lpv.idCustomer', $idCfp)
                    ->orWhereNull('lpv.idCustomer');
            })
            ->where(function ($query) use ($idCfp) {
                $query->where('pefc.idCfp', $idCfp)
                    ->orWhereNull('pefc.idCfp');
            })
            ->where(function ($query) {
                $query->where('salles.salle_name', '!=', 'In situ')
                    ->orWhereNull('salles.salle_name');
            })
            ->orderBy('vll.li_name', 'asc')
            ->get();

        // dd($lieux_salles);
        // ->paginate(10);
        $lieu_types = DB::table('lieu_types')
            ->get();

        switch (Customer::typeCustomer()) {
            case 1:
                return view('CFP.lieux.index', compact('ville_addSalle', 'lieux_addSalle', 'lieux_salles', 'lieu_types'));
                break;
            case 2:
                // return view('ETP.agences.index', compact('agences', 'villeCodeds'));
                return view('ETP.salles.index', compact('ville_addSalle', 'lieux_addSalle', 'lieux_salles', 'lieu_types'));
                break;
            default:
                return abort(404);
                break;
        }
    }

    public function search($id)
    {
        $lieux = DB::table('lieux')
            ->select('idLieu', 'li_name')
            ->where('idLieuType', '=', $id)
            ->get();
        return response()->json([
            'lieux' => $lieux
        ]);
    }

    public function searchNoId()
    {
        $lieux = DB::table('lieux')
            ->select('idLieu', 'li_name')
            ->get();
        return response()->json([
            'lieux' => $lieux
        ]);
    }

    public function storeLieu(Request $request)
    {
        if ($request->idLieuType == 3) {
            $request->validate([
                'li_name' => 'required|min:3|max:150',
                'idVille' => 'required|exists:villes,idVille',
                'idLieuType' => 'required|exists:lieu_types,idLieuType',
                'idVilleCoded' => 'required|exists:ville_codeds,id',
                'idEntreprise' => 'required'
            ]);
        } else {
            $request->validate([
                'li_name' => 'required|min:3|max:150',
                'idVille' => 'required|exists:villes,idVille',
                'idLieuType' => 'required|exists:lieu_types,idLieuType',
                'idVilleCoded' => 'required|exists:ville_codeds,id'
            ]);
        }

        $idLieu = DB::table('lieux')->insertGetId([
            'li_name' => $request->li_name,
            'li_quartier' => $request->main_salle_quartier,
            'idVille' => $request->idVille,
            'idLieuType' => $request->idLieuType,
            'idVilleCoded' => $request->idVilleCoded
        ]);

        return $idLieu;
    }

    public function storePlaceEtpFromCfp(Request $req, $idLieu)
    {
        PlaceEtpFromCfp::create([
            'idLieu' => $idLieu,
            'date_added' => Carbon::now(),
            'idEntreprise' => $req->idEntreprise,
            'idCfp' => Customer::idCustomer()
        ]);
    }

    public function getAllEtps()
    {
        $etps = DB::table('v_collaboration_cfp_etps')
            ->select('idEtp', 'etp_initial_name', 'etp_name', 'etp_logo')
            ->where('idCfp', Customer::idCustomer())
            ->groupBy('idEtp', 'etp_initial_name', 'etp_name', 'etp_logo')
            ->orderBy('etp_name', 'asc')
            ->get();

        if (count($etps) <= 0) {
            return response([
                "messages" => "Aucun résultat trouvé !",
                "status" => 404
            ]);
        }

        return response([
            "etps" => $etps,
            "status" => 200
        ]);
    }

    public function store(Request $request)
    {
        $type_lieux = $request->idLieuType;

        try {
            DB::transaction(function () use ($request, $type_lieux) {
                $idLieu = $this->storeLieu($request);

                switch ($type_lieux) {
                    case 1:
                        DB::table('lieu_publics')->insert(['idLieu' => $idLieu]);
                        break;
                    case 2:
                        DB::table('lieu_privates')->insert([
                            'idLieu' => $idLieu,
                            'idCustomer' => Customer::idCustomer()
                        ]);
                        break;
                    case 3:
                        $this->storePlaceEtpFromCfp($request, $idLieu);
                        break;
                    default:
                        return null;
                        break;
                }
            });

            return back()->with('success', 'Succès');
        } catch (Exception $e) {
            return back()->with('error', 'Erreur inconnue !' . $e->getMessage());
        }
    }


    public function deleteLieu($id)
    {
        try {
            $lieux = Lieux::find($id);

            if ($lieux) {
                $salle = Salle::where('idLieu', $lieux->idLieu)->first();

                if ($salle) {
                    return response([
                        'message' => 'Veuillez supprimer la salle d\'abord avant de supprimer le lieu',
                        'status' => 400
                    ]);
                }

                $lieuPublicExist = DB::table('lieu_publics')->where('idLieu', $id)->exists();

                $lieuPrivateExist = DB::table('lieu_privates')->where('idLieu', $id)->exists();

                $lieuEtpExist = DB::table('place_etp_from_cfps')->where('idLieu', $id)->exists();

                if ($lieuPublicExist) {
                    DB::table('lieu_publics')->where('idLieu', $id)->delete();
                }

                if ($lieuPrivateExist) {
                    DB::table('lieu_privates')->where('idLieu', $id)->delete();
                }

                if ($lieuEtpExist) {
                    DB::table('place_etp_from_cfps')->where('idLieu', $id)->delete();
                }

                $lieux->delete();

                return response([
                    'message' => 'Lieu supprimé avec succès',
                    'status' => 200
                ]);
            } else {
                return response([
                    'message' => 'Lieu introuvable',
                    'status' => 404
                ]);
            }
        } catch (Exception $e) {
            return response([
                'message' => 'Impossible de supprimer ce lieu! ' . $e->getMessage(),
                'status' => 500
            ]);
        }
    }

    public function propositionQuartier()
    {
        $quartiers = DB::table('lieux')
            ->select('li_quartier')
            ->where('li_quartier', '!=', '')
            ->distinct()
            ->get();

        return response()->json($quartiers);
    }
}
