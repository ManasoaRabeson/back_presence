<?php

namespace App\Http\Controllers;

use App\Models\Projet;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


class AssiduiteSuperAdminController extends Controller
{
    //SuperAdmin 
    public function assiduite()
    {
        return view('superAdmin.assiduite.index');
    }
 
    public function test()
    {
        return view('superAdmin.testSA.index');
    }

    public function projetlist()
    {
        $projects = DB::table('v_projet_all')
            ->select(
                'v_projet_all.idProjet',
                DB::raw('coalesce(v_projet_all.project_reference, "Non défini") as project_reference'),
                DB::raw('coalesce(v_projet_all.project_title, "Non défini") as project_title'),
                DB::raw('coalesce(v_projet_all.project_name, "Non défini") as project_name'),
                DB::raw('coalesce(v_projet_all.domaine_name, "Non défini") as domaine_name'),
                'v_projet_all.project_type',
                'v_projet_all.idTypeprojet',
                DB::raw('coalesce(v_projet_all.dateDebut, "Non défini") as dateDebut'),
                DB::raw('coalesce(v_projet_all.dateFin, "Non défini") as dateFin'),
                'v_projet_all.module_name',
                'v_projet_all.ville',
                DB::raw('coalesce(v_projet_all.etp_name, "Non défini") as etp_name'),
                DB::raw('coalesce(customers.customerName, "- - -") as customerName'),
                'v_projet_all.modalite',
                DB::raw('coalesce(v_projet_all.salle_name, "Non défini") as salle_name'),
                DB::raw('coalesce(v_projet_all.project_description, "Non défini") as project_description'),
                DB::raw('coalesce(v_projet_all.paiement, "- - -") as paiement'),
                DB::raw('coalesce(REPLACE(FORMAT(projets.total_ht, 0), ",", " "), "- - -") as total_ht'),
                DB::raw('coalesce(v_list_sub_contractor_addeds.sub_name, "- - -") as sous_traitant'),
                'v_projet_all.project_status'
            )
            ->join('projets','projets.idProjet','v_projet_all.idProjet')
            ->join('customers','customers.idCustomer','v_projet_all.idCustomer')
            ->leftJoin('v_list_sub_contractor_addeds','v_list_sub_contractor_addeds.idProjet','v_projet_all.idProjet')
            ->get();
        // dd($projects);

        return view('superAdmin.projets.index', compact('projects'));
    }

    public function organismelist()
    {
        $customers = DB::table('customers as C')
                        ->join('cfps as CF', 'CF.idCustomer', '=', 'C.idCustomer')
                        ->join('role_users','role_users.user_id','CF.idCustomer')
                        ->join('users','users.id','C.idCustomer')
                        ->where('users.user_is_deleted',0)
                        ->where('role_users.isActive',1)
                        ->select('C.*')
                        ->get();
        $selectedCustomers = DB::table('cfp_selected_by_admin')->pluck('idCfp')->toArray();
        return view('superAdmin.organisme.index', compact('customers', 'selectedCustomers'));
    }    

    public function organismevalidate(Request $request)
    {
        $selectedIds = explode(',', $request->input('ids'));
        $idSuperAdmin = 1; 

        DB::table('cfp_selected_by_admin')
            ->where('idSuperAdmin', $idSuperAdmin)
            ->delete();

        $insertData = [];
        foreach ($selectedIds as $idCfp) {
            $insertData[] = [
                'idSuperAdmin' => $idSuperAdmin,
                'idCfp' => $idCfp,
                'date_added' => Carbon::now(),
            ];
        }

        if (!empty($insertData)) {

            DB::table('cfp_selected_by_admin')->insert($insertData);

            return response()->json([
                'success' => true,
                'message' => 'Selected IDs were reinserted successfully!',
                'inserted_ids' => array_column($insertData, 'idCfp'),
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'No IDs provided for insertion.',
            ]);
        }
    }

    public function domaineList()
    {
        $domaines = DB::table('domaine_formations as d')
                        ->leftJoin('mdls as m', 'd.idDomaine', '=', 'm.idDomaine')
                        ->select('d.idDomaine','d.nomDomaine', DB::raw('COUNT(m.idModule) as nbrModules'))
                        ->groupBy('d.idDomaine', 'd.nomDomaine')
                        ->orderBy('nomDomaine','asc')
                        ->get();
        return view('superAdmin.domaine.index',compact('domaines'));
    } 

    public function domaineInsert(Request $request)
    {
        try {
            $validated = $request->validate([
                '_token' => 'required|string',
                'domaineName' => 'required|string|max:255'
            ]);
            
            if ($validated['_token'] !== csrf_token()) {
                return response()->json(['message' => 'Token CSRF invalide.'], 403);
            }
            
            $domaineName = $validated['domaineName'];
            $existingDomain = DB::table('domaine_formations')->where('nomDomaine', $domaineName)->exists();
            try {
                if(!$existingDomain) {
                    DB::insert('INSERT INTO domaine_formations (nomDomaine) VALUES (?)', [$domaineName]);
                    return response()->json([
                        "message" => "Domaine ajouté avec succès!",
                        "domaineName" => $domaineName
                    ]); 
                } else {
                    return response()->json([
                        "message" => "Le nom de domaine existe déja dans la table!",
                        "domaineName" => $domaineName
                    ]);
                }
            } catch (\Exception $e) {
                return response()->json(['message' => 'Erreur lors de l\'insertion : ' . $e->getMessage()], 500);
            }

            return response()->json([
                "message" => "Domaine ajouté avec succès.",
                "domaineName" => $domaineName
            ]);
            
        } catch (\Exception $e) {
            return response()->json(['message' => 'Une erreur est survenue : ' . $e->getMessage()], 500);
        }
    }
    
    public function domaineUpdate(Request $request)
    {
        try {
            $validated = $request->validate([
                '_token' => 'required|string',
                'idDomaine' => 'required|integer|exists:domaine_formations,idDomaine', 
                'domaineName' => 'required|string|max:255'
            ]);

            if ($validated['_token'] !== csrf_token()) {
                return response()->json(['message' => 'Token CSRF invalide.'], 403);
            }

            $domaineId = $validated['idDomaine'];
            $domaineName = $validated['domaineName'];

            $existingDomain = DB::table('domaine_formations')->where('idDomaine', '=', $domaineId)->exists();

            if ($existingDomain) {
                try {
                    DB::table('domaine_formations')
                        ->where('idDomaine', $domaineId)
                        ->update(['nomDomaine' => $domaineName]);

                    return response()->json([
                        'message' => "Nom de domaine mis à jour avec succès!",
                        'domaineName' => $domaineName
                    ]);
                } catch (\Exception $e) {
                    return response()->json(['message' => 'Erreur lors de la mise à jour : ' . $e->getMessage()], 500);
                }
            }

        } catch (\Exception $e) {
            return response()->json(['message' => 'Une erreur est survenue : ' . $e->getMessage()], 500);
        }
    }


    public function deleteDomaine($id)
    {
        $moduleCount = DB::table('mdls')->where('idDomaine', $id)->count();
    
        if ($moduleCount > 0) {
            return response()->json([
                'success' => false,
                'message' => 'Ce domaine de formation ne peut pas Ãªtre supprimé car il est lié un module.'
            ], 400);
        }
    
        DB::table('domaine_formations')->where('idDomaine', $id)->delete();
    
        return response()->json([
            'success' => true,
            'message' => 'Domaine de formation supprimé avec succès.'
        ]);
    }

    public function deleteProject($id)
    {
        try {

            DB::beginTransaction();

            $tables = [
                'seances',
                'project_sub_contracts',
                'project_restaurations',
                'project_materials',
                'project_forms', 
                'particulier_projet', 
                'intras',
                'inters',
                'internes',
                'images',
                'ignoredConflitLieu', 
                'ignoredConflitFormateur', 
                'fraisprojet',
                'eval_froids',
                'eval_chauds',
                'detail_apprenants',
                'detail_apprenant_inters',
                'projets'
            ];
            
            $tablesWithDeletions = [];
            $errorOccurred = false;
            $errorMessage = '';
            
            foreach ($tables as $table) {
                try {
                    $exists = DB::table($table)->where('idProjet', $id)->exists();
            
                    if ($exists) {
                        DB::table($table)->where('idProjet', $id)->delete();
                        $tablesWithDeletions[] = $table;
                    }
            
                } catch (\Exception $e) {
                    $errorOccurred = true;
                    $errorMessage = $e->getMessage(); 
                    break; 
                }
            }

            if ($errorOccurred) {
                return response()->json([
                    'success' => false,
                    'message' => 'Une erreur est survenue lors de la suppression.',
                    'error' => $errorMessage,
                ], 500);
            }

            
            DB::commit();
            
            return response()->json([
                'success' => true,
                'message' => 'Les suppressions ont été effectuées.',
                'tables_deleted' => $tablesWithDeletions,  
            ]);            
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with("error", "Une erreur s'est produite, veuillez réessayer plus tard !" . $e);
        }
    }

    public function publicityModule()
    {
        $customers = DB::table('customers as C')
                        ->join('cfps as CF', 'CF.idCustomer', '=', 'C.idCustomer')
                        ->join('role_users','role_users.user_id','CF.idCustomer')
                        ->join('users','users.id','C.idCustomer')
                        ->where('users.user_is_deleted',0)
                        ->where('role_users.isActive',1)
                        ->select('C.idCustomer','C.customerName','C.logo')
                        ->get();
        return view('superAdmin.publicite.index', compact('customers'));
    } 

    public function moduleCfp( $id )
    {
        $moduleCfp = DB::table('mdls')
                        ->select(
                            'mdls.idModule',
                            'mdls.moduleName',
                            'mdls.description',
                            DB::raw('COALESCE(publicites.is_active, 0) as is_active')
                        )
                        ->leftJoin('publicites', 'mdls.idModule', '=', 'publicites.idModule') 
                        ->where('mdls.idCustomer', $id)
                        ->where('mdls.moduleName', '!=', 'Default module')
                        ->get()
                        ->map(function ($module) {
                            $module->is_active = $module->is_active == 1 ? 'Promu' : 'Non Promu';
                            return $module;
                        });
        return view('superAdmin.publicite.moduleCfp',compact('moduleCfp'));
    } 

    public function modulePromu($id)
    {
        try {
            DB::beginTransaction();
            
            $publiciteId = DB::table('publicites')->insertGetId([
                'idModule' => $id, 
                'date_ajout' => now(), 
                'is_active' => 1,
                'idType' => 1
            ]);
        
            DB::table('pub_simples')->insert([
                'id' => $publiciteId
            ]);
        
            DB::commit();

            return response()->json(['message' => 'Module promu avec succès!']);
            
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with("error", "Une erreur s'est produite, veuillez réessayer plus tard !" . $e);
        }
    }    

    public function listeModulePromu()
    {
        $promu = DB::table('publicites')
                    ->join('pub_simples','pub_simples.id','publicites.id')
                    ->join('mdls','mdls.idModule','publicites.idModule')
                    ->where('publicites.is_active',1)
                    ->where('idType',1)
                    ->get();
        return view('superAdmin.publicite.listePromu',compact('promu'));
    } 

    public function updateRang( Request $request) 
    {
        $request->validate([
            'ids' => 'required|array',
            'rang_apparition' => 'required|array',
            'rang_apparition.*' => 'integer|min:1',
        ]);

        $ids = $request->input('ids');
        $rangs = $request->input('rang_apparition');

        if (count($rangs) !== count(array_unique($rangs))) {
            return redirect()->back()->withErrors(['Les rangs d\'apparition doivent être uniques.']);
        }

        foreach ($ids as $index => $id) {
            DB::table('publicites')
                ->where('id', $id)
                ->update(['rang_affichage' => $rangs[$index]]);
        }

        return redirect()->back()->with('success', 'Rangs d\'affichage mis à jour avec succès.');
        
    }

    public function detache ( $id ) {
        try {
            DB::table('publicites')
                ->where('id', $id)
                ->update(['is_active' => 0]);

            return redirect()->back()->with('success', 'Publicité désactivée avec succès.');
        } catch (\Exception $e) {
            return back()->with("error", "Une erreur s'est produite, veuillez réessayer plus tard !" . $e);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $request->validate([
                'nif' => 'nullable|string',
                'stat' => 'nullable|string'
            ]);
        
            $customerExists = DB::table('customers')->where('idCustomer', $id)->exists();

            if (!$customerExists) {
                return redirect()->back()->with('error', 'Enregistrement non trouvé.');
            }

            $updateData = array_filter([
                'nif' => $request->nif,
                'stat' => $request->stat,
                'updated_at' => now(),
            ], fn($value) => !is_null($value));

            DB::table('customers')->where('idCustomer', $id)->update($updateData);

            return redirect()->back()->with('success', 'Les informations ont été mises à jour avec succès.');
            
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Une erreur est survenue : ' . $e->getMessage());
        }

    }


}
