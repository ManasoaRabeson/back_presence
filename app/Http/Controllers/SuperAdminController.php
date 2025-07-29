<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SuperAdminController extends Controller
{
    public function about()
    {
        $superAdm = DB::table('users')
            ->select('name', 'email')
            ->where('email', '=', 'contact@formation.mg')
            ->get();

        return view('superAdmin.about', compact('superAdm'));
    }

    //Projet SuperAdmin
    public function projets()
    {
        $pSuperAdm = DB::table('v_projet_cfps')
            ->select(
                'idProjet',
                'referenceEtp as reference',
                'projectName as name',
                'dateDebut',
                'dateFin',
                'statut',
                'isActiveProjet as isActive',
                'moduleName',
                'logoEtp',
                'modalite',
                'etpName',
            )
            ->get();

        $pEtp = DB::table('v_projet_internes')
            ->select(
                'idProjet',
                'projectName as name',
                'isActiveProjet as isActive',
                'dateDebut',
                'dateFin',
                'modalite',
                'idEtp',
                'cfp_inter',
                'statut',
                'idModule',
                'moduleName',
                'dureeH',
                'dureeJ',
            )
            ->get();

        $countP = DB::table('v_projet_cfps')
            ->select('idProjet')
            ->count();

        $countPEtp = DB::table('v_projet_internes')
            ->select('idProjet')
            ->count();
        return view('superAdmin.projets.projets', compact(["pSuperAdm", "pEtp", "countP", "countPEtp"]));
    }

    public function projetstype($idTypeProjet)
    {
        $projetstypes = DB::table('v_projet_all')
            ->select(
                DB::raw('coalesce(project_reference, "Non défini") as project_reference'),
                DB::raw('coalesce(project_title, "Non défini") as project_title'),
                DB::raw('coalesce(project_name, "Non défini") as project_name'),
                DB::raw('coalesce(domaine_name, "Non défini") as domaine_name'),
                DB::raw('coalesce(dateDebut, "Non défini") as dateDebut'),
                DB::raw('coalesce(dateFin, "Non défini") as dateFin'),
                'module_name',
                'ville',
                DB::raw('coalesce(etp_name, "Non défini") as etp_name'),
                'modalite',
                DB::raw('coalesce(salle_name, "Non défini") as salle_name'),
                DB::raw('coalesce(project_description, "Non défini") as project_description'),
                'project_status'
            )
            ->where('idTypeProjet', $idTypeProjet)
            ->get();
        $type = null;
        if ($idTypeProjet == 1) {
            $type = 'intra-entreprise';
        }
        if ($idTypeProjet == 2) {
            $type = 'inter-entreprise';
        }
        if ($idTypeProjet == 3) {
            $type = 'interne';
        }

        $idTypeProjet = $idTypeProjet;
        return view('superAdmin.projets.intra.index', compact('projetstypes', 'type'));
    }

    public function projetsInterne()
    {
        $internes = DB::table('projets')
            ->select(
                DB::raw('coalesce(project_reference, "Non défini") as project_reference'),
                'project_title',
                DB::raw('coalesce(projectName, "Non défini") as projectName')
            )
            ->where('idTypeProjet', 3)
            ->get();
        return view('superAdmin.projets.interne.index', compact('internes'));
    }

    public function projetsInter()
    {
        $inters = DB::table('projets')
            ->select('project_reference', 'project_title', 'projectName')
            ->where('idTypeProjet', 2)
            ->get();
        return view('superAdmin.projets.inter.index', compact('inters'));
    }

    //Projet par financement
    public function projetsFmfp()
    {
        $fmtps = DB::table('v_union_projets2')
            ->select('project_reference', 'project_title', 'project_status')
            ->where('idPaiement', 2)
            ->get();
        return view('superAdmin.projets.fmfp.index', compact('fmtps'));
    }

    public function projetsFondPropre()
    {
        $propres = DB::table('v_union_projets2')
            ->select('project_reference', 'project_title', 'project_status')
            ->where('idPaiement', 1)
            ->get();
        return view('superAdmin.projets.fondpropre.index', compact('propres'));
    }

    public function projetsFondAutres($idPaiement)
    {
        $autres = DB::table('v_union_projets2')
            ->select('project_reference', 'project_title', 'project_status', 'idPaiement')
            ->where('idPaiement', $idPaiement)
            ->get();
        $paiement = null;
        if ($idPaiement == 1) {
            $paiement = 'fond propre';
        }
        if ($idPaiement == 2) {
            $paiement = 'FMFP';
        }
        if ($idPaiement == 3) {
            $paiement = 'autre financement';
        }

        return view('superAdmin.projets.fondpers.index', compact('autres', 'paiement'));
    }

    public function entreprises()
    {
        $etps = DB::table('v_entreprise_all')
                    ->select(
                        DB::raw('coalesce(v_entreprise_all.customerName, "Non défini") as customerName'),
                        DB::raw('coalesce(v_entreprise_all.description, "Non défini") as description'),
                        DB::raw('coalesce(v_entreprise_all.customer_addr_lot, "Non défini") as customer_addr_lot'),
                        DB::raw('coalesce(v_entreprise_all.customerPhone, "Non défini") as customerPhone'),
                        DB::raw('coalesce(v_entreprise_all.customerEmail, "Non défini") as customerEmail'),
                        'v_entreprise_all.idEtp',
                        'v_entreprise_all.isActive',
                        'customers.nif',
                        'customers.stat',
                        'customers.created_at',
                        DB::raw('COALESCE((
                            SELECT COUNT(*) 
                            FROM apprenants 
                            JOIN employes ON employes.idEmploye = apprenants.idEmploye
                            WHERE employes.idCustomer = customers.idCustomer
                        ), 0) as nbr_apprenants') ,
                        DB::raw('COALESCE((
                            SELECT COUNT(*) 
                            FROM projets 
                            WHERE projets.idCustomer = customers.idCustomer
                        ), 0) as nbr_projets')   
                    )
                    ->join('customers', 'customers.idCustomer', '=', 'v_entreprise_all.idEtp')
                    ->where('user_is_deleted', 0)
                    ->orderByRaw('customers.created_at IS NULL, customers.created_at ASC')
                    ->get();   
        return view('superAdmin.entreprise.entreprise', compact('etps'));
    }

    public function cfp()
    {
        $cfps = DB::table('v_cfp_all')
            ->select(
                DB::raw('coalesce(v_cfp_all.customerName, "Non défini") as customerName'),
                DB::raw('coalesce(v_cfp_all.description, "Non défini") as description'),
                DB::raw('coalesce(v_cfp_all.customer_addr_lot, "Non défini") as customer_addr_lot'),
                DB::raw('coalesce(v_cfp_all.customerPhone, "Non défini") as customerPhone'),
                DB::raw('coalesce(v_cfp_all.customerEmail, "Non défini") as customerEmail'),
                'v_cfp_all.idCfp', 'v_cfp_all.isActive', 'customers.nif' , 'customers.stat', 'customers.created_at',
                DB::raw('COALESCE((
                    SELECT COUNT(DISTINCT apprenants.idEmploye) 
                    FROM apprenants 
                    join emargements on emargements.idEmploye = apprenants.idEmploye
                    join seances on seances.idSeance = emargements.idSeance
                    join projets on projets.idProjet = seances.idProjet
                    WHERE projets.idCustomer = customers.idCustomer
                ), 0) as nbr_apprenants')  ,
                DB::raw('COALESCE((
                    SELECT COUNT(*) 
                    FROM projets 
                    WHERE projets.idCustomer = customers.idCustomer
                ), 0) as nbr_projets')   
            )
            ->join('customers','customers.idCustomer','=','v_cfp_all.idCfp')
            ->where('user_is_deleted',0)
            ->orderByRaw('customers.created_at IS NULL, customers.created_at ASC')
            ->get();
        return view('superAdmin.OF.cfpList', compact('cfps'));
    }

    public function blockCfp($idCfp){
        $query = DB::table('cfps')
            ->join('role_users', 'role_users.user_id', 'cfps.idCustomer')
            ->where('idCustomer', $idCfp);

        if($query->first()){
            $query->update(['role_users.isActive' => 0]);
            return response(['success' => 'Succès']);
        }else{
            return response(['error' => 'CFP introuvable !'], 404);
        }
    }

    public function unblockCfp($idCfp){
        $query = DB::table('cfps')
            ->join('role_users', 'role_users.user_id', 'cfps.idCustomer')
            ->where('idCustomer', $idCfp);

        if($query->first()){
            $query->update(['role_users.isActive' => 1]);
            return response(['success' => 'Succès']);
        }else{
            return response(['error' => 'CFP introuvable !'], 404);
        }
    }

    public function trashCfp($idCfp){
        $query = DB::table('users')
                    ->join('cfps','cfps.idCustomer','users.id')
                    ->where('idCustomer',$idCfp);

        if($query->first()){
            $query2 = DB::table('employes')
                        ->join('users','users.id','employes.idEmploye')
                        ->where('employes.idEmploye',$idCfp);

            if($query2->count()>0) {
                $query2->update(['users.user_is_deleted' => 1]);
            }
            $query->update(['users.user_is_deleted' => 1]);
            return response(['success' => 'Succès']);
        }else{
            return response(['error' => 'CFP introuvable !'], 404);
        }
    }

    public function blockEtp($idEtp){
        $query = DB::table('entreprises')
            ->join('role_users', 'role_users.user_id', 'entreprises.idCustomer')
            ->where('idCustomer', $idEtp);

        if($query->first()){
            $query->update(['role_users.isActive' => 0]);
            return response(['success' => 'Succès']);
        }else{
            return response(['error' => 'CFP introuvable !'], 404);
        }
    }

    public function unblockEtp($idEtp){
        $query = DB::table('entreprises')
            ->join('role_users', 'role_users.user_id', 'entreprises.idCustomer')
            ->where('idCustomer', $idEtp);

        if($query->first()){
            $query->update(['role_users.isActive' => 1]);
            return response(['success' => 'Succès']);
        }else{
            return response(['error' => 'CFP introuvable !'], 404);
        }
    }

    public function trashEtp($idEtp){
        $query = DB::table('users')
                    ->join('entreprises','entreprises.idCustomer','users.id')
                    ->where('idCustomer',$idEtp);

        if($query->first()){
            $query2 = DB::table('employes')
                        ->join('users','users.id','employes.idEmploye')
                        ->where('employes.idEmploye',$idEtp);

            if($query2->count()>0) {
                $query2->update(['users.user_is_deleted' => 1]);
            }
            $query->update(['users.user_is_deleted' => 1]);
            return response(['success' => 'Succès']);
        }else{
            return response(['error' => 'CFP introuvable !'], 404);
        }
    }
}
