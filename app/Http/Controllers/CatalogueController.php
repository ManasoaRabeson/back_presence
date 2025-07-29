<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CatalogueController extends Controller
{
    public function idCustomer()
    {
        $customer = DB::select("SELECT employes.idEmploye, employes.idCustomer, customers.idTypeCustomer FROM employes INNER JOIN customers ON employes.idCustomer = customers.idCustomer WHERE idEmploye = ?", [Auth::user()->id]);
        return $customer[0]->idCustomer;
    }

    // NEW
    public function getDomaines()
    {
        $allDomaineFormations = DB::table('domaine_formations')->select('idDomaine', 'nomDomaine')->get();
        return response()->json([
            'allDomaineFormations' => $allDomaineFormations
        ]);
    }

    public function getModules()
    {
        $modules = DB::table('v_catalogues')
            ->select('cfpLogo', 'cfpInitialName', 'cfpName', 'idModule', 'moduleReference', 'moduleName', 'moduleDescription', 'prix', 'prixGroupe', 'dureeH', 'dureeJ')
            ->where('idEtp', $this->idCustomer())
            ->orderBy('idModule', 'desc')
            ->limit(3)
            ->get();

        return response()->json([
            'modules' => $modules
        ]);
    }

    public function index()
    {
        return view('ETP.catalogues.index');
    }

    public function result()
    {
        $allmodules = DB::table('v_module_cfps')
            ->select('logo', 'moduleName', 'reference', 'cfpName', 'idModule', 'module_image', 'dureeJ', 'dureeH', 'prixGroupe', 'description', 'nomDomaine')
            ->orderBy('idModule', 'desc')
            ->get();

        return view('ETP.catalogues.marketplace.result', compact('allmodules'));
    }

    public function avis()
    {
        return view('ETP.catalogues.marketplace.avis');
    }

    public function devis()
    {
        return view('ETP.catalogues.marketplace.devis');
    }

    public function detailsCatalogueEtp()
    {
        return view('ETP.catalogues.marketplace.detail');
    }

    public function show($idProjet, $idModule)
    {
        $formation = DB::table('v_catalogue_inters')
            ->select('idCfp', 'idModule', 'idProjet', 'moduleName', 'description', 'customerName', 'logoCustomer', 'moduleName')
            ->groupBy('idCfp', 'idModule', 'idProjet', 'moduleName', 'description', 'customerName', 'logoCustomer', 'moduleName')
            ->where('idEtp', Auth::user()->id)
            ->where('idProjet', $idProjet)
            ->where('idModule', $idModule)
            ->first();

        $sessions = DB::table('v_union_sessions')
            ->select('idSession', 'idProjet', 'sessionName', 'dateDebut', 'dateFin', 'moduleName', 'idModule')
            ->where('idTypeProjet', 2)
            ->where('idProjet', $idProjet)
            ->where('idModule', $idModule)
            ->where('isActiveSession', 1)
            ->get();

        return view('ETP.catalogues.show', compact(['formation', 'sessions']));
    }

    public function store(Request $req)
    {
        $req->validate([
            'idSession' => 'required|integer|exists:sessions,idSession',
            'projetId' => 'required|integer'
        ]);

        $check = DB::table('inter_entreprises')
            ->select('idProjet', 'idEtp')
            ->where('idProjet', '=', $req->projetId)
            ->where('idSession', '=', $req->idSession)
            ->where('idEtp', '=', Auth::user()->id)
            ->count('idSession');

        if ($check < 1) {
            DB::table('inter_entreprises')->insert([
                'idSession' => $req->idSession,
                'idProjet' => $req->projetId,
                'idEtp' => Auth::user()->id,
                'isActiveInter' => 0
            ]);

            $last = DB::select('SELECT idProjet, idSession, idEtp FROM inter_entreprises WHERE idProjet = ? AND idSession = ?', [$req->projetId, $req->idSession]);

            return redirect('projetInters/' . $last[0]->idProjet . '/' . $last[0]->idSession);
        } else {
            return back()->with('erreur', 'Inscription déjas effectuée');
        }
    }
}
