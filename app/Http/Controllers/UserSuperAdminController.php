<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class UserSuperAdminController extends Controller
{
    public function formateurs()
    {
        $users = DB::table('v_employe_alls')
            ->select(
                'idEmploye',
                'isActive',
                'fonction',
                'photo',
                'phone',
                'adresse',
                'name',
                'firstName',
                'matricule',
                'email'
            )
            ->get();
        // dd($users);
        return view('superAdmin.utilisateurs.formateurs.utilisateurs', compact(['users']));
    }

    public function formateurscfp()
    {
        $formateurs = DB::table('v_formateur_cfps')
            ->select(
                DB::raw('distinct idFormateur'),
                DB::raw('coalesce(name, "Non défini") as name'),
                DB::raw('coalesce(firstName, "Non défini") as firstName'),
                DB::raw('coalesce(form_phone, "Non défini") as form_phone'),
                DB::raw('coalesce(email, "Non défini") as email'),
                DB::raw('coalesce(form_addr_qrt, "Non défini") as form_addr_qr'),
                'customers.customerName'
            )
            ->join('customers','customers.idCustomer','v_formateur_cfps.idCfp')
            ->get();
        return view('superAdmin.utilisateurs.formateurs.formateursCfp', compact('formateurs'));
    }

    public function deleteFormateursCfp( $idFormateur )
    {
        DB::beginTransaction();
        try {
            $deleted1 = DB::table('cfp_formateurs')->where('idFormateur', $idFormateur)->delete();
            $deleted2 = DB::table('formateurs')->where('idFormateur', $idFormateur)->delete();
            $deleted3 = DB::table('project_forms')->where('idFormateur', $idFormateur)->delete();
            $deleted4 = DB::table('forms')->where('idFormateur', $idFormateur)->delete();
            $deleted5 = DB::table('users')->where('id', $idFormateur)->delete();

            if($deleted1 && $deleted2 && $deleted5 && $deleted4) {
                DB::commit(); 
                session()->flash('success', 'Formateur supprimé avec succès');
            } else {
                DB::rollBack();
                session()->flash('error', 'Formateur non trouvé');
            }
            $formateurs = DB::table('v_formateur_cfps')
                ->select(
                    DB::raw('distinct idFormateur'),
                    DB::raw('coalesce(name, "Non défini") as name'),
                    DB::raw('coalesce(firstName, "Non défini") as firstName'),
                    DB::raw('coalesce(form_phone, "Non défini") as form_phone'),
                    DB::raw('coalesce(email, "Non défini") as email'),
                    DB::raw('coalesce(form_addr_qrt, "Non défini") as form_addr_qr'),
                )
                ->get();
            return view('superAdmin.utilisateurs.formateurs.formateursCfp', compact('formateurs'));
        } catch (\Exception $e) {
            DB::rollBack();
            session()->flash('error', 'Une erreur est survenue : ' . $e->getMessage());
            $formateurs = DB::table('v_formateur_cfps')
                ->select(
                    DB::raw('distinct idFormateur'),
                    DB::raw('coalesce(name, "Non défini") as name'),
                    DB::raw('coalesce(firstName, "Non défini") as firstName'),
                    DB::raw('coalesce(form_phone, "Non défini") as form_phone'),
                    DB::raw('coalesce(email, "Non défini") as email'),
                    DB::raw('coalesce(form_addr_qrt, "Non défini") as form_addr_qr'),
                )
                ->get();
            return view('superAdmin.utilisateurs.formateurs.formateursCfp', compact('formateurs'));
        }
    }

    public function formateursetp()
    {
        $formateurs = DB::table('v_formateur_internes')
            ->select(
                DB::raw('distinct idEmploye'),
                DB::raw('coalesce(name, "Non défini") as name'),
                DB::raw('coalesce(firstName, "Non défini") as firstName'),
                DB::raw('coalesce(form_phone, "Non défini") as form_phone'),
                DB::raw('coalesce(email, "Non défini") as email'),
                DB::raw('coalesce(customerName, "Non défini") as customerName'),
                DB::raw('coalesce(customer_addr_quartier, "Non défini") as customer_addr_quartier'),
            )
            ->get();
        return view('superAdmin.utilisateurs.formateurs.formateursEtp', compact('formateurs'));
    }

    public function deleteFormateursEtp( $idEmploye )
    {
        DB::beginTransaction();
        try {
            $deleted = DB::table('c_emps')->where('idEmploye', $idEmploye)->delete();
            $deleted1 = DB::table('formateur_internes')->where('idEmploye', $idEmploye)->delete();
            $deleted0 = DB::table('employes')->where('idEmploye', $idEmploye)->delete();
            $deleted2 = DB::table('formateurs')->where('idFormateur', $idEmploye)->delete();
            $deleted3 = DB::table('project_forms')->where('idFormateur', $idEmploye)->delete();
            $deleted4 = DB::table('forms')->where('idFormateur', $idEmploye)->delete();
            $deleted5 = DB::table('users')->where('id', $idEmploye)->delete();

            if($deleted0 && $deleted && $deleted1) {
                DB::commit(); 
                session()->flash('success', 'Formateur supprimé avec succès');
            } else {
                DB::rollBack();
                session()->flash('error', 'Formateur non trouvé');
            }
            $formateurs = DB::table('v_formateur_internes')
                ->select(
                    DB::raw('distinct idEmploye'),
                    DB::raw('coalesce(name, "Non défini") as name'),
                    DB::raw('coalesce(firstName, "Non défini") as firstName'),
                    DB::raw('coalesce(form_phone, "Non défini") as form_phone'),
                    DB::raw('coalesce(email, "Non défini") as email'),
                    DB::raw('coalesce(customerName, "Non défini") as customerName'),
                    DB::raw('coalesce(customer_addr_quartier, "Non défini") as customer_addr_quartier'),
                )
                ->get();
            return view('superAdmin.utilisateurs.formateurs.formateursEtp', compact('formateurs'));
        } catch (\Exception $e) {
            DB::rollBack();
            session()->flash('error', 'Une erreur est survenue : ' . $e->getMessage());
            $formateurs = DB::table('v_formateur_internes')
                ->select(
                    DB::raw('distinct idEmploye'),
                    DB::raw('coalesce(name, "Non défini") as name'),
                    DB::raw('coalesce(firstName, "Non défini") as firstName'),
                    DB::raw('coalesce(form_phone, "Non défini") as form_phone'),
                    DB::raw('coalesce(email, "Non défini") as email'),
                    DB::raw('coalesce(customerName, "Non défini") as customerName'),
                    DB::raw('coalesce(customer_addr_quartier, "Non défini") as customer_addr_quartier'),
                )
                ->get();
            return view('superAdmin.utilisateurs.formateurs.formateursEtp', compact('formateurs'));
        }
    }

    public function referentscfp()
    {
        $referents = DB::table('v_referent_cfp_all')
            ->select(
                'v_referent_cfp_all.id',
                DB::raw('coalesce(v_referent_cfp_all.name, "Non défini") as name'),
                DB::raw('coalesce(v_referent_cfp_all.firstName, "Non défini") as firstName'),
                DB::raw('coalesce(v_referent_cfp_all.phone, "Non défini") as phone'),
                DB::raw('coalesce(v_referent_cfp_all.email, "Non défini") as email'),
                DB::raw('coalesce(v_referent_cfp_all.user_addr_quartier, "Non défini") as user_addr_quartier'),
                'customers.idCustomer',
                'customers.customerName' 
            )
            ->join('employes','employes.idEmploye','v_referent_cfp_all.id')
            ->join('customers','customers.idCustomer','employes.idCustomer')
            ->join('users','users.id','customers.idCustomer')
            ->join('role_users','role_users.id','customers.idCustomer')
            ->where('users.user_is_deleted',0)
            ->where('role_users.isActive',1)
            ->get();
        return view('superAdmin.utilisateurs.refferents.cfp', compact('referents'));
    }

    public function deleteReferentsCfp( $idReferent )
    {
        DB::beginTransaction();

        try {

            $deleted = DB::table('role_users')->where('user_id', $idReferent)->where('role_id', 3)->delete();
    
            if ($deleted) {
                DB::commit(); 
                session()->flash('success', 'Référent supprimé avec succès');
            } else {
                DB::rollBack(); 
                session()->flash('error', 'Référent non trouvé');
            }
            $referents = DB::table('v_referent_cfp_all')
                ->select(
                    'id',
                    DB::raw('coalesce(name, "Non défini") as name'),
                    DB::raw('coalesce(firstName, "Non défini") as firstName'),
                    DB::raw('coalesce(phone, "Non défini") as phone'),
                    DB::raw('coalesce(email, "Non défini") as email'),
                    DB::raw('coalesce(user_addr_quartier, "Non défini") as user_addr_quartier'),
                )
                ->get();
            return view('superAdmin.utilisateurs.refferents.cfp', compact('referents'));
        } catch (\Exception $e) {
            DB::rollBack();
            session()->flash('error', 'Une erreur est survenue : ' . $e->getMessage());
            $referents = DB::table('v_referent_cfp_all')
                ->select(
                    'id',
                    DB::raw('coalesce(name, "Non défini") as name'),
                    DB::raw('coalesce(firstName, "Non défini") as firstName'),
                    DB::raw('coalesce(phone, "Non défini") as phone'),
                    DB::raw('coalesce(email, "Non défini") as email'),
                    DB::raw('coalesce(user_addr_quartier, "Non défini") as user_addr_quartier'),
                )
                ->get();
            return view('superAdmin.utilisateurs.refferents.cfp', compact('referents'));
        }
    }

    public function referentsetp()
    {
        $referents = DB::table('v_referent_etp_all')
            ->select(
                'id',
                DB::raw('coalesce(name, "Non défini") as name'),
                DB::raw('coalesce(firstName, "Non défini") as firstName'),
                DB::raw('coalesce(phone, "Non défini") as phone'),
                DB::raw('coalesce(email, "Non défini") as email'),
                DB::raw('coalesce(user_addr_quartier, "Non défini") as user_addr_quartier'),
                'customers.customerName'
            )
            ->join('employes','employes.idEmploye','v_referent_etp_all.id')
            ->join('customers','customers.idCustomer','employes.idCustomer')
            ->get();
        return view('superAdmin.utilisateurs.refferents.etp', compact('referents'));
    }

    public function deleteReferentsEtp( $idReferent )
    {
        DB::beginTransaction();

        try {

            $deleted = DB::table('role_users')->where('user_id', $idReferent)->where('role_id', 6)->delete();
    
            if ($deleted) {
                DB::commit(); 
                session()->flash('success', 'Référent supprimé avec succès');
            } else {
                DB::rollBack(); 
                session()->flash('error', 'Référent non trouvé');
            }
            $referents = DB::table('v_referent_etp_all')
                ->select(
                    'id',
                    DB::raw('coalesce(name, "Non défini") as name'),
                    DB::raw('coalesce(firstName, "Non défini") as firstName'),
                    DB::raw('coalesce(phone, "Non défini") as phone'),
                    DB::raw('coalesce(email, "Non défini") as email'),
                    DB::raw('coalesce(user_addr_quartier, "Non défini") as user_addr_quartier'),
                )
                ->get();
            return view('superAdmin.utilisateurs.refferents.etp', compact('referents'));
        } catch (\Exception $e) {
            DB::rollBack();
            session()->flash('error', 'Une erreur est survenue : ' . $e->getMessage());
            $referents = DB::table('v_referent_etp_all')
                ->select(
                    'id',
                    DB::raw('coalesce(name, "Non défini") as name'),
                    DB::raw('coalesce(firstName, "Non défini") as firstName'),
                    DB::raw('coalesce(phone, "Non défini") as phone'),
                    DB::raw('coalesce(email, "Non défini") as email'),
                    DB::raw('coalesce(user_addr_quartier, "Non défini") as user_addr_quartier'),
                )
                ->get();
            return view('superAdmin.utilisateurs.refferents.etp', compact('referents'));
        }
    }

    public function cfpmodule()
    {
        $modules = DB::table('v_module_cfp_all')
            ->select(
                'v_module_cfp_all.idModule',
                DB::raw('coalesce(moduleName, "Non défini") as moduleName'),
                DB::raw('coalesce(description, "Non défini") as description'),
                DB::raw('coalesce(nomDomaine, "Non défini") as nomDomaine'),
                DB::raw('coalesce(customerName, "Non défini") as customerName'),
                DB::raw('(SELECT COUNT(*) FROM projets WHERE projets.idModule = v_module_cfp_all.idModule) as nombreProjets')
            )
            ->get();
        return view('superAdmin.catalogues.listeModuleCfp', compact('modules'));
    }

    public function deleteCfpModule( $idModule )
    {
        DB::beginTransaction();

        try {

            DB::table('modules')->where('idModule', $idModule)->delete();

            $deleted = DB::table('mdls')->where('idModule', $idModule)->where('idTypeModule',1)->delete();
    
            if ($deleted) {
                DB::commit(); 
                session()->flash('success', 'Module supprimé avec succès');
            } else {
                DB::rollBack(); 
                session()->flash('error', 'Module non trouvé');
            }
            $modules = DB::table('v_module_cfp_all')
                ->select(
                    'v_module_cfp_all.idModule',
                    DB::raw('coalesce(moduleName, "Non défini") as moduleName'),
                    DB::raw('coalesce(description, "Non défini") as description'),
                    DB::raw('coalesce(nomDomaine, "Non défini") as nomDomaine'),
                    DB::raw('coalesce(customerName, "Non défini") as customerName'),
                )
                ->get();
            return view('superAdmin.catalogues.listeModuleCfp', compact('modules'));
        } catch (\Exception $e) {
            DB::rollBack();
            session()->flash('error', 'Une erreur est survenue : ' . $e->getMessage());
            $modules = DB::table('v_module_cfp_all')
                ->select(
                    'v_module_cfp_all.idModule',
                    DB::raw('coalesce(moduleName, "Non défini") as moduleName'),
                    DB::raw('coalesce(description, "Non défini") as description'),
                    DB::raw('coalesce(nomDomaine, "Non défini") as nomDomaine'),
                    DB::raw('coalesce(customerName, "Non défini") as customerName'),
                )
                ->get();
            return view('superAdmin.catalogues.listeModuleCfp', compact('modules'));
        }
    }

    public function etpmodule()
    {
        $modules = DB::table('v_module_etp_all')
            ->select(
                'idModule',
                DB::raw('coalesce(moduleName, "Non défini") as moduleName'),
                DB::raw('coalesce(description, "Non défini") as description'),
                DB::raw('coalesce(nomDomaine, "Non défini") as nomDomaine'),
                DB::raw('coalesce(customerName, "Non défini") as customerName'),
                DB::raw('(SELECT COUNT(*) FROM projets WHERE projets.idModule = v_module_etp_all.idModule) as nombreProjets')
            )
            ->get();
        return view('superAdmin.catalogues.listeModuleEtp', compact('modules'));
    }

    public function etpapprenants()
    {
        $apprenants = DB::table('apprenants')
                        ->select(
                            'apprenants.idEmploye', 
                            'users.name', 
                            'users.firstName', 
                            'users.phone', 
                            'users.email', 
                            'customers.customerName',
                            DB::raw('COALESCE((
                                SELECT COUNT(DISTINCT projets.idProjet)
                                FROM emargements 
                                JOIN seances ON seances.idSeance = emargements.idSeance
                                JOIN projets ON projets.idProjet = seances.idProjet
                                WHERE emargements.idEmploye = apprenants.idEmploye
                            ), 0) AS nbr_projets')
                        )
                        ->join('users', 'users.id', '=', 'apprenants.idEmploye')
                        ->join('employes', 'employes.idEmploye', '=', 'apprenants.idEmploye')
                        ->join('customers', 'customers.idCustomer', '=', 'employes.idCustomer')
                        ->get();
        return view('superAdmin.apprenants.listeApprenantsEtp', compact('apprenants'));
    }

    public function deleteEtpApprenants( $idEmploye )
    {
        DB::beginTransaction();

        try {

            $deleted = DB::table('apprenants')->where('idEmploye', $idEmploye)->delete();
    
            if ($deleted) {
                DB::commit(); 
                session()->flash('success', 'Module supprimé avec succès');
            } else {
                DB::rollBack(); 
                session()->flash('error', 'Module non trouvé');
            }
            return redirect()->route('superAdmins.apprenants');
        } catch (\Exception $e) {
            DB::rollBack();
            session()->flash('error', 'Une erreur est survenue : ' . $e->getMessage());
            return redirect()->route('superAdmins.apprenants');
        }
    }

    public function deleteEtpModule( $idModule )
    {
        DB::beginTransaction();

        try {
            DB::table('module_internes')->where('idModule', $idModule)->delete();

            $deleted = DB::table('mdls')->where('idModule', $idModule)->where('idTypeModule',2)->delete();
    
            if ($deleted) {
                DB::commit(); 
                session()->flash('success', 'Module supprimé avec succès');
            } else {
                DB::rollBack(); 
                session()->flash('error', 'Module non trouvé');
            }
            $modules = DB::table('v_module_etp_all')
                ->select(
                    'idModule',
                    DB::raw('coalesce(moduleName, "Non défini") as moduleName'),
                    DB::raw('coalesce(description, "Non défini") as description'),
                    DB::raw('coalesce(nomDomaine, "Non défini") as nomDomaine'),
                    DB::raw('coalesce(customerName, "Non défini") as customerName'),
                )
                ->get();
            return view('superAdmin.catalogues.listeModuleEtp', compact('modules'));
        } catch (\Exception $e) {
            DB::rollBack();
            session()->flash('error', 'Une erreur est survenue : ' . $e->getMessage());
            $modules = DB::table('v_module_etp_all')
                ->select(
                    'idModule',
                    DB::raw('coalesce(moduleName, "Non défini") as moduleName'),
                    DB::raw('coalesce(description, "Non défini") as description'),
                    DB::raw('coalesce(nomDomaine, "Non défini") as nomDomaine'),
                    DB::raw('coalesce(customerName, "Non défini") as customerName'),
                )
                ->get();
            return view('superAdmin.catalogues.listeModuleEtp', compact('modules'));
        }
    }

    public function show($idModule)
    {
        if ('module_is_complete' !== 1) {
            DB::table('mdls')->where('idModule', $idModule)->update([
                'module_is_complete' => 1
            ]);
        }
        $module = DB::table('v_module_cfps')
            ->select('idModule', 'module_image', 'reference AS module_reference', 'moduleName AS module_name', 'nomDomaine AS domaine_name', 'idDomaine', 'description AS module_description', 'minApprenant', 'dureeH', 'dureeJ', 'maxApprenant', 'prix AS module_price', 'prixGroupe', 'idCustomer', 'cfpName as nameCfp', 'logo as cfpLogo', 'idLevel', 'module_level_name', 'module_subtitle')
            ->where('idModule', $idModule)
            ->first();

        $module_ressources = DB::table('module_ressources')->select('idModuleRessource', 'taille', 'module_ressource_name', 'module_ressource_extension', 'idModule')->where('idModule', $idModule)->get();
        
        return view('superAdmin.catalogues.detailModuleCfp', compact(["module", "module_ressources"]));
    }

    public function showEtp($idModule)
    {
        if ('module_is_complete' !== 1) {
            DB::table('mdls')->where('idModule', $idModule)->update([
                'module_is_complete' => 1
            ]);
        }
        $module = DB::table('v_module_etps')
            ->select('idModule', 'module_image', 'reference AS module_reference', 'moduleName AS module_name', 'nomDomaine AS domaine_name', 'description AS module_description', 'minApprenant', 'dureeH', 'dureeJ', 'maxApprenant', 'idCustomer', 'cfpName as nameCfp', 'logo as cfpLogo')
            ->where('idModule', $idModule)
            ->first();

        $module_ressources = DB::table('module_ressources')->select('idModuleRessource', 'module_ressource_name', 'module_ressource_extension', 'idModule')->where('idModule', $idModule)->get();  
        return view('superAdmin.catalogues.detailModuleEtp', compact(["module", "module_ressources"]));
    }
}
