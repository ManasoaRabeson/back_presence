<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\ModuleCibleRequest;
use App\Http\Requests\ModuleObjectifRequest;
use App\Http\Requests\ModulePrerequisRequest;
use App\Http\Requests\ModulePrestationRequest;
use App\Http\Requests\ModuleRequest;
use App\Models\Customer;
use App\Models\Module;
use App\Services\ModuleService;
use App\Traits\HasModule;
use App\Traits\LearnerQuery;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Intervention\Image\Drivers\Gd\Driver;
use Intervention\Image\ImageManager;
use Illuminate\Support\Facades\Storage;

class CatalogueController extends Controller
{
    use HasModule, LearnerQuery;

    private function getModuleGrouped($module_statut)
    {
        $query = DB::table('v_modules')
            ->select('nomDomaine as domaine', DB::raw('GROUP_CONCAT(CONCAT(moduleName, "``" , COALESCE(prix, "null"), "``" , 
                                        idModule, "``" , COALESCE(dureeH, "null"), "``" , COALESCE(dureeJ, "null"), "``", moduleStatut, "``", 
                                        COALESCE(module_image, "null"), "``",module_is_complete, "``", module_level_name )SEPARATOR "~&") as modules'))
            ->where('idCustomer', Customer::idCustomer())
            ->where('moduleName', '!=', 'Default module')
            ->where('moduleStatut', $module_statut)
            ->groupBy('idDomaine')
            ->orderBy('nomDomaine');

        $typeCustomer = Customer::typeCustomer();

        if($typeCustomer == 1){
            $getModules = $query->where('id_type_module', 1)->get();
        }elseif($typeCustomer == 2){
            $getModules = $query->where('id_type_module', 2)->get();
        }
        
        $get_module_grouped = [];
        foreach ($getModules as $mdl) {
            $modules = [];
            $get_module_name = explode('~&', $mdl->modules);
            foreach ($get_module_name as $name) {
                $get_info_module = explode('``', $name);

                $testObjectif = (!empty($this->listObjectifs($get_info_module[2])) ? 1 : 0);
                $testPrestation = (!empty($this->listPrestations($get_info_module[2])) ? 1 : 0);
                $testProgramme = (!empty($this->listProgrammes($get_info_module[2])) ? 1 : 0);
                $testCible = (!empty($this->listCibles($get_info_module[2])) ? 1 : 0);
                $testPrerequis = (!empty($this->listPrerequis($get_info_module[2])) ? 1 : 0);
                $testImgMdl = (($get_info_module[6] != 'null') ? 1 : 0);

                // Calcul de la somme des indicateurs
                $testSumQuality = $testObjectif + $testPrestation + $testProgramme + $testCible + $testPrerequis + $testImgMdl;

                $modules[] = [
                    'module_name' => $get_info_module[0],
                    'prix' => $get_info_module[1],
                    'idModule' => $get_info_module[2],
                    'dureeJ' => $get_info_module[4],
                    'dureeH' => $get_info_module[3],
                    'moduleStatut' => $get_info_module[5],
                    'module_image' => $get_info_module[6],
                    'module_is_complete' => $get_info_module[7],
                    'module_level_name' => $get_info_module[8],
                    'objectifs' => $this->listObjectifs($get_info_module[2]),
                    'prestations' => $this->listPrestations($get_info_module[2]),
                    'programmes' => $this->listProgrammes($get_info_module[2]),
                    'cibles' => $this->listCibles($get_info_module[2]),
                    'prerequis' => $this->listPrerequis($get_info_module[2]),
                    'testSumQuality' => $testSumQuality,
                    'totalFormed' => $this->countLearnerByModule($get_info_module[2])
                ];
            }

            $get_module_grouped[] = [
                'domaine' => $mdl->domaine,
                'modules' => $modules,
                'count_module' => count($modules),

            ];
        }

        return $get_module_grouped;
    }

    // return all catalogues
    public function index(){
        $queries = DB::table('v_modules')
            ->select('idDomaine', 'nomDomaine AS domain_name', 'moduleStatut AS module_status')
            ->groupBy('idDomaine', 'nomDomaine', 'moduleStatut')
            ->where('idCustomer', Customer::idCustomer())
            ->where('moduleName', '!=', 'Default module')
            ->orderBy('nomDomaine', 'asc');

        $typeCustomer = Customer::typeCustomer();

        if($typeCustomer == 1){
            $query = $queries->where('id_type_module', 1);
        }elseif($typeCustomer == 2){
            $query = $queries->where('id_type_module', 2);
        }

        $domaines = $query->get();

        $queryCount = DB::table('mdls')
            ->where('idCustomer', Customer::idCustomer())
            ->where('idTypeModule', 1)
            ->where('moduleName', '!=', "Default module");

        $countTrashline = $queryCount->where('moduleStatut', 2)->count();
        $countOnline = $queryCount->where('moduleStatut', 1)->count();
        $countOffline = $queryCount->where('moduleStatut', 0)->count();

        $badgeOnline = $queryCount->where('moduleStatut', 1)->where('module_is_complete', '!=', 1)->exists();
        $badgeOffline = $queryCount->where('moduleStatut', 0)->where('module_is_complete', '!=', 1)->exists();

        $onlineModules = $this->getModuleGrouped(1);
        $offlineModules = $this->getModuleGrouped(0);
        $trashedModules = $this->getModuleGrouped(2);

        $customer = DB::table('v_detail_customers')->select('idCustomer', 'initialName', 'customerName as name', 'customer_addr_lot as adress', 'customerPhone as phone', 'description', 'siteWeb', 'logo', 'customerEmail as email')->where('idCustomer', Customer::idCustomer())->first();

        return response()->json([
            'domaines' => $domaines,
            'countTrashline' => $countTrashline,
            'module_trashed' => $countOnline,
            'countOffline' => $countOffline,
            'badgeOnline' => $badgeOnline,
            'badgeOffline' => $badgeOffline,
            'onlineModules' => $onlineModules, 
            'offlineModules' => $offlineModules, 
            'trashedModules' => $trashedModules, 
            'customer' => $customer
        ]);
    }

    public function store(Request $req, ModuleRequest $reqModule, ModuleService $mdls)
    {
        try {
            DB::transaction(function() use($req, $mdls){
                $typeCustomer = Customer::typeCustomer();
                
                
                if($typeCustomer == 1){
                    $module = $mdls->storeMdls($req->module_reference, $req->module_tag, $req->name, $req->subtitle, $req->module_dureeH, $req->module_dureeJ, $req->module_min_appr, $req->module_max_appr, 1, Customer::idCustomer(), $req->id_domaine_formation, $req->idLevel);
                    $mdls->storeModule($module, $req->module_price, $req->module_prix_groupe);
                }else if($typeCustomer == 2){
                    $module = $mdls->storeMdls($req->module_reference, $req->module_tag, $req->name, $req->subtitle, $req->module_dureeH, $req->module_dureeJ, $req->module_min_appr, $req->module_max_appr, 2, Customer::idCustomer(), $req->id_domaine_formation, $req->idLevel);
                    $mdls->storeModuleInterne($module);
                }
            });

            return response()->json([
                "status" => 200,
                "message" => "Module ajouté avec succès."
            ]);
        } catch (Exception $e) {
            return response()->json([
                "status" => 400,
                "message" => "Ajout impossible"
            ]);
        }
    }

    public function makeOnline($idModule, ModuleService $mdls)
    {
        $changed = $mdls->changeStatus($idModule, Customer::idCustomer(), 1, 0);

        if($changed){
            return response()->json([
                "status" => 200,
                "message" => "Succès"
            ]);
        }else{
            return response()->json([
                "status" => 404,
                "message" => "Module introuvable !"
            ]);
        }
    }


    public function makeOffline($idModule, ModuleService $mdls)
    {
        $changed = $mdls->changeStatus($idModule, Customer::idCustomer(), 0, 0);

        if($changed){
            return response()->json([
                "status" => 200,
                "message" => "Succès"
            ]);
        }else{
            return response()->json([
                "status" => 404,
                "message" => "Module introuvable !"
            ]);
        }
    }

    public function makeTrashed($idModule, ModuleService $mdls)
    {
        $changed = $mdls->changeStatus($idModule, Customer::idCustomer(), 2, 0);

        if($changed){
            return response()->json([
                "status" => 200,
                "message" => "Succès"
            ]);
        }else{
            return response()->json([
                "status" => 404,
                "message" => "Module introuvable !"
            ]);
        }
    }

    public function restore($idModule,  ModuleService $mdls)
    {
        $changed = $mdls->changeStatus($idModule, Customer::idCustomer(), 0, 0);

        if($changed){
            return response()->json([
                "status" => 200,
                "message" => "Succès"
            ]);
        }else{
            return response()->json([
                "status" => 404,
                "message" => "Module introuvable !"
            ]);
        }
    }

    public function destroy($id)
    {
        try {
            $module = DB::table('mdls')->where('idModule', $id)->where('idCustomer', Customer::idCustomer());

            if($module->exists()){
                DB::transaction(function() use($id, $module){
                    DB::table('prerequis_modules')->where('idModule', $id)->delete();
                    DB::table('cible_modules')->where('idModule', $id)->delete();
                    DB::table('programmes')->where('idModule', $id)->delete();
                    DB::table('prestation_modules')->where('idModule', $id)->delete();
                    DB::table('objectif_modules')->where('idModule', $id)->delete();

                    if(Customer::typeCustomer() == 1){
                        DB::table('modules')->where('idModule', $id)->delete();
                    }elseif(Customer::typeCustomer() == 2){
                        DB::table('module_internes')->where('idModule', $id)->delete();
                    }
                    $module->delete();
                });

                return response()->json([
                    "status" => 200,
                    "message" => "Module supprimé avec succès"
                ]);
            }else{
                return response()->json([
                    "status" => 404,
                    "message" => "Module introuvable !"
                ]);
            }
        } catch (Exception $e) {
            return response()->json([
                "status" => 400,
                "message" => "suppression impossible"
            ]);
        }
    }

    public function edit($id, ModuleService $mdl)
    {
        $module = $mdl->getModule($id, Customer::idCustomer());
        
        if($module->exists()){
            $domaines = $this->domaines();
            $levels = $this->levels();

            return response()->json([
                'status' => 200,
                'module' => $module->first(),
                'domaines' => $domaines,
                'levels' => $levels
            ]);
        }else{
            return response()->json([
                "status" => 404,
                "message" => "Module introuvable !"
            ]);  
        }
    }

    public function update(Request $req, ModuleRequest $reqModule, $id, ModuleService $mdls)
    {
        try {
            $module = $mdls->getModule($id, Customer::idCustomer());

            if($module->exists()){
                DB::transaction(function() use($req, $id, $mdls){
                    $mdls->updateMdls(
                        $id, 
                        $req->module_reference, 
                        $req->module_tag, 
                        $req->name,
                        $req->subtitle, 
                        $req->module_description, 
                        $req->module_dureeH, 
                        $req->module_dureeJ, 
                        $req->module_min_appr, 
                        $req->module_max_appr, 
                        Customer::idCustomer(), 
                        $req->id_domaine_formation, 
                        $req->idLevel);
    
                    if(Customer::typeCustomer() == 1){
                        $mdls->updateModule($id, $req->module_price, $req->module_prix_groupe);
                    }
                });
                
                return response()->json([
                    "status" => 200,
                    "message" => "Module modifié avec succès"
                ]);
            }else{
                return response()->json([
                    "status" => 404,
                    "message" => "Module introuvable !"
                ]);  
            }
        } catch (Exception $e) {
            return response()->json([
                "status" => 400,
                "message" => "Modification impossible".$e->getMessage()
            ]);
        }
    }

    public function updateImage(Request $req, $id, ModuleService $mdl)
    {
        $query = $mdl->getModule($id, Customer::idCustomer());

        if($query->exists()){
            $mdl->updateModuleImage($id, Customer::idCustomer(), $query, $req->image);

            return response()->json([
                'status' => 200,
                'message' => 'Image ajoutée avec succès'
            ]);
        }else{
            return response()->json([
                "status" => 404,
                "message" => "Module introuvable !"
            ]); 
        }
    }

    // Objectifs
    public function addObjectif($id, ModuleObjectifRequest $req, ModuleService $mdl)
    {
        $module = $mdl->getModule($id, Customer::idCustomer());

        if($module->exists()){
            $mdl->storeObjectif($id, $req->validated()['name']);

            return response()->json([
                "status" => 200,
                "message" => "Objectif ajoutée avec succès"
            ]);
        }else{
            return response()->json([
                "status" => 404,
                "message" => "Module introuvable !"
            ]);  
        }
    }

    public function getObjectif($id, ModuleService $mdl)
    {
        $module = $mdl->getModule($id, Customer::idCustomer());

        if($module->exists()){
            $objectifs = $this->objectifs($id);

            return response()->json([
                "status" => 200,
                "objectifs" => $objectifs
            ]);
        }else{
            return response()->json([
                "status" => 404,
                "message" => "Module introuvable !"
            ]);  
        }
    }

    public function deleteObjectif($idModule, $idObjectif, ModuleService $mdl)
    {
        $module = $mdl->getModule($idModule, Customer::idCustomer());

        if($module->exists()){
            $objectif = DB::table('objectif_modules')->where('idObjectif', $idObjectif);

            if($objectif->exists()){
                $mdl->destroyObjectif($idModule, $idObjectif);

                return response()->json([
                    "status" => 200,
                    "message" => "Objectif supprimée avec succès"
                ]);
            }else{
                return response()->json([
                    "status" => 404,
                    "message" => "Objectif introuvable !"
                ]);  
            }
        }else{
            return response()->json([
                "status" => 404,
                "message" => "Module introuvable !"
            ]);  
        }
    }

    // Prestations
    public function addPrestation($id, ModulePrestationRequest $req, ModuleService $mdl)
    {
        $module = $mdl->getModule($id, Customer::idCustomer());

        if($module->exists()){
            $mdl->storePrestation($id, $req->validated()['prestation_name']);

            return response()->json([
                "status" => 200,
                "message" => "Préstation ajoutée avec succès"
            ]);
        }else{
            return response()->json([
                "status" => 404,
                "message" => "Module introuvable !"
            ]);  
        }
    }

    public function getPrestation($id, ModuleService $mdl)
    {
        $module = $mdl->getModule($id, Customer::idCustomer());

        if($module->exists()){
            $prestations = $this->prestations($id);

            return response()->json([
                "status" => 200,
                "prestations" => $prestations
            ]);
        }else{
            return response()->json([
                "status" => 404,
                "message" => "Module introuvable !"
            ]);  
        }
    }
    
    public function deletePrestation($idModule, $idPrestation, ModuleService $mdl)
    {
        $module = $mdl->getModule($idModule, Customer::idCustomer());

        if($module->exists()){
            $prestation = DB::table('prestation_modules')->where('idPrestation', $idPrestation);

            if($prestation->exists()){
                $mdl->destroyPrestation($idModule, $idPrestation);

                return response()->json([
                    "status" => 200,
                    "message" => "Préstation supprimée avec succès"
                ]);
            }else{
                return response()->json([
                    "status" => 404,
                    "message" => "Préstation introuvable !"
                ]);  
            }
        }else{
            return response()->json([
                "status" => 404,
                "message" => "Module introuvable !"
            ]);  
        }
    }

    // Prerequis
    public function addPrerequis($id, ModulePrerequisRequest $req, ModuleService $mdl)
    {
        $module = $mdl->getModule($id, Customer::idCustomer());

        if($module->exists()){
            $mdl->storePrerequis($id, $req->validated()['prerequis_name']);

            return response()->json([
                "status" => 200,
                "message" => "Prérequis ajoutée avec succès"
            ]);
        }else{
            return response()->json([
                "status" => 404,
                "message" => "Module introuvable !"
            ]);  
        }
    }

    public function getPrerequis($id, ModuleService $mdl)
    {
        $module = $mdl->getModule($id, Customer::idCustomer());

        if($module->exists()){
            $prerequis = $this->prerequis($id);

            return response()->json([
                "status" => 200,
                "prerequis" => $prerequis
            ]);
        }else{
            return response()->json([
                "status" => 404,
                "message" => "Module introuvable !"
            ]);  
        }
    }

    public function deletePrerequis($idModule, $idPrerequis, ModuleService $mdl)
    {
        $module = $mdl->getModule($idModule, Customer::idCustomer());

        if($module->exists()){
            $prerequis = DB::table('prerequis_modules')->where('idPrerequis', $idPrerequis);

            if($prerequis->exists()){
                $mdl->destroyPrerequis($idModule, $idPrerequis);

                return response()->json([
                    "status" => 200,
                    "message" => "Prérequis supprimée avec succès"
                ]);
            }else{
                return response()->json([
                    "status" => 404,
                    "message" => "Prérequis introuvable !"
                ]);  
            }
        }else{
            return response()->json([
                "status" => 404,
                "message" => "Module introuvable !"
            ]);  
        }
    }

    // Cibles
    public function addCible($idModule, ModuleCibleRequest $req, ModuleService $mdl)
    {
        $module = $mdl->getModule($idModule, Customer::idCustomer());

        if($module->exists()){
            $mdl->storeCible($idModule, $req->validated()['cible_name']);

            return response()->json([
                "status" => 200,
                "message" => "Cible ajoutée avec succès"
            ]);
        }else{
            return response()->json([
                "status" => 404,
                "message" => "Module introuvable !"
            ]);  
        }
    }

    public function getCible($idModule, ModuleService $mdl)
    {
        $module = $mdl->getModule($idModule, Customer::idCustomer());

        if($module->exists()){
            $cibles = $this->cibles($idModule);

            return response()->json([
                "status" => 200,
                "cibles" => $cibles
            ]);
        }else{
            return response()->json([
                "status" => 404,
                "message" => "Module introuvable !"
            ]);  
        }
    }

    public function deleteCible($idModule, $idCible, ModuleService $mdl)
    {
        $module = $mdl->getModule($idModule, Customer::idCustomer());

        if($module->exists()){
            $cibles = DB::table('cible_modules')->where('idCible', $idCible);

            if($cibles->exists()){
                $mdl->destroyCible($idModule, $idCible);

                return response()->json([
                    "status" => 200,
                    "message" => "Cible supprimée avec succès"
                ]);
            }else{
                return response()->json([
                    "status" => 404,
                    "message" => "Cible introuvable !"
                ]);  
            }
        }else{
            return response()->json([
                "status" => 404,
                "message" => "Module introuvable !"
            ]);  
        }
    }

    // Qualités
    public function getSumQuality($idModule, ModuleService $mdl){
        $module = $mdl->getModule($idModule, Customer::idCustomer());

        if($module->exists()){
            return response()->json([
                'sumQuality' => $mdl->getSumQuality($idModule, Customer::idCustomer())
            ]);
        }else{
            return response()->json([
                'status' => 404,
                'message' => 'Module introuvable !'
            ]);
        }
    }
}