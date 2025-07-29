<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Laravelcm\Subscriptions\Models\Plan;
use Laravelcm\Subscriptions\Models\Subscription;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }



    public function getIdCustomer()
    {
        return response()->json(['idCustomer' => Customer::idCustomer()]);
    }

    public function getIdEmploye()
    {
        return response()->json(['idCustomer' => Auth::id()]);
    }


    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        return view('home');
    }

    public function indexEmpCfp()
    {
        return view('homeEmpCfp');
    }

    public function indexEtp()
    {
        return view('homeEtp');
    }

    public function indexAdmin()
    {

        $typeprojets = DB::table('type_projets')
            ->select('idTypeProjet', 'type')
            ->get();

        $paiements = DB::table('paiements')
            ->select('idPaiement', 'paiement')
            ->get();

        $nombreprojetsintra = DB::table('v_nombre_projets_intra')
            ->select('nombre_projets_intra')
            ->first();

        $nombreprojetsinter = DB::table('v_nombre_projets_inter')
            ->select('nombre_projets_inter')
            ->first();

        $nombreprojetsinterne = DB::table('v_nombre_projets_interne')
            ->select('nombre_projets_interne')
            ->first();

        $nombreprojetsmodulescfp = DB::table('v_nombre_projets_modules_cfp')
            ->select('nombre_projets_modules_cfp')
            ->first();

        $nombreprojetsmodulesentreprise = DB::table('v_nombre_projets_modules_entreprise')
            ->select('nombre_projets_modules_entreprise')
            ->first();

        $nombrecfp = DB::table('v_nombre_cfp')
            ->select('nombre_cfp')
            ->first();

        $nombreentreprise = DB::table('v_nombre_entreprise')
            ->select('nombre_entreprise')
            ->first();

        $nombreformateur = DB::table('v_nombre_formateur')
            ->select('nombre_formateur')
            ->first();

        $nombreapprenant = DB::table('v_nombre_apprenant')
            ->select('nombre_apprenant')
            ->first();

        $nombreprojetfondpropre = DB::table('v_nombre_projet_fond_propre')
            ->select('nombre_projet_fond_propre')
            ->first();

        $nombreprojetfmpt = DB::table('v_nombre_projet_fmtp')
            ->select('nombre_projet_fmtp')
            ->first();

        $nombreprojetautres = DB::table('v_nombre_projet_autres')
            ->select('nombre_projet_autres')
            ->first();

        $nombreformateurcfp = DB::table('v_nombre_formateur_cfp')
            ->select('nombre_formateur_cfp')
            ->first();

        /* $nombrereferentcfp = DB::table('v_nombre_referent_cfp')
            ->select('nombre_referent_cfp')
            ->first(); */

        $nombrereferententreprise = DB::table('v_nombre_referent_entreprise')
            ->select('nombre_referent_entreprise')
            ->first();

        $nombreformateurentreprise = DB::table('v_nombre_formateur_entreprise')
            ->select('nombre_formateur_entreprise')
            ->first();

        $nombreapprenantsentreprise = DB::table('apprenants')
            ->join('users', 'users.id', 'apprenants.idEmploye')
            ->count();

        $planCfp = Plan::where('user_type', 'centre de formation')
            ->with('features')
            ->withCount('subscriptions')
            ->get();

        $planEtp = Plan::where('user_type', 'entreprise')
            ->with('features')
            ->withCount('subscriptions')
            ->get();

        $nombrereferentcfp = DB::table('v_referent_cfp_all')
            ->join('employes', 'employes.idEmploye', 'v_referent_cfp_all.id')
            ->join('customers', 'customers.idCustomer', 'employes.idCustomer')
            ->join('users', 'users.id', 'customers.idCustomer')
            ->join('role_users', 'role_users.id', 'customers.idCustomer')
            ->where('users.user_is_deleted', 0)
            ->where('role_users.isActive', 1)
            ->count();

        return view('homeAdmin', compact('planCfp', 'planEtp', 'paiements', 'typeprojets', 'nombreprojetsintra', 'nombreprojetsinter', 'nombreprojetsinterne', 'nombreprojetsmodulescfp', 'nombreprojetsmodulesentreprise', 'nombrecfp', 'nombreentreprise', 'nombreformateur', 'nombreapprenant', 'nombreprojetfondpropre', 'nombreprojetfmpt', 'nombreprojetautres', 'nombreformateurcfp', 'nombrereferentcfp', 'nombrereferententreprise', 'nombreformateurentreprise', 'nombreapprenantsentreprise'));
    }

    public function indexEmp()
    {
        // $projet = DB::table('v_projet_cfps')
        //     ->select(
        //         'idProjet'
        //     )
        //     ->where('idProjet', 98)
        //     ->first();

        // $imagesMomentums = DB::table('images')
        //     ->select('nomImage', 'idImages')
        //     ->where('idProjet', 98)
        //     ->where('idTypeImage', 1)
        //     ->get();

        // modifier

        $userId = Auth::user()->id;

        $projetAvecImages = DB::table('v_projet_emps')
            ->join('images', 'v_projet_emps.idProjet', '=', 'images.idProjet')
            ->select('v_projet_emps.idProjet')
            ->where('idEmploye', $userId)
            ->where('v_projet_emps.project_status', 'Terminé')
            ->orderBy('v_projet_emps.dateFin', 'desc')
            ->limit(1)
            ->first();

        $images = [];
        $idProjet = null;

        if ($projetAvecImages) {
            $idProjet = $projetAvecImages->idProjet;

            $images = DB::table('images')
                ->select('nomImage', 'idProjet', 'idImages')
                ->where('idProjet', $idProjet)
                ->get();
        }

        return view('homeEmp', compact('images', 'idProjet'));
    }

    public function indexFor()
    {
        return view('formateurs.projets.index');
    }

    public function indexFormInterne()
    {
        return view('homeFormInterne');
    }
    public function search()
    {
        return view('layouts.search');
    }

    public function indexParticulier()
    {
        return view('homeParticulier');
    }

    public function confidentialite()
    {
        return view('confidentialites.politique');
    }
    public function condition()
    {
        return view('confidentialites.condition');
    }

    public function searchGenerality()
    {
        return view('searchGenerality');
    }

    public function searchIndexReferent()
    {
        return view('recherche.searchIndexReferent');
    }

    public function searchIndexFormateur()
    {
        return view('recherche.searchIndexFormateur');
    }

    public function searchIndexApprenant()
    {
        return view('recherche.searchIndexApprenant');
    }

    public function searchIndexClient()
    {
        return view('recherche.searchIndexClient');
    }

    public function searchIndexCfp()
    {
        return view('recherche.searchIndexCfp');
    }

    public function searchIndexLieu()
    {
        return view('recherche.searchIndexLieu');
    }

    public function searchIndexProjet()
    {
        return view('recherche.searchIndexProjet');
    }
}
