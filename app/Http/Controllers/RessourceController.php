<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Validator;

class RessourceController extends Controller
{
    public function idCustomer()
    {
        $customer = DB::select("SELECT employes.idEmploye, employes.idCustomer, customers.idTypeCustomer FROM employes INNER JOIN customers ON employes.idCustomer = customers.idCustomer WHERE idEmploye = ?", [Auth::user()->id]);
        return $customer[0]->idCustomer;
    }

    public function addRessource(){
        $typeMateriels = DB::table('type_materiels')->select('idTypeMateriel', 'typeMateriel')->get();
        $fournisseurs = DB::table('fournisseurs')->select('idFournisseur', 'nomFournisseur')->get();
        $materiels = DB::table('v_materiel_cfps')
            ->select('idMateriel', 'codeMateriel', 'nomMateriel', 'typeMateriel', 'nomFournisseur', 'descriptionMateriel')
            ->where('idCfp', $this->idCustomer())
            ->paginate(8);

        return view("CFP.ressources.index", compact(['typeMateriels', 'fournisseurs', 'materiels']));
    }

    public function addRessourceEtp(){
        $customer = DB::select("SELECT employes.idEmploye, employes.idCustomer, customers.idTypeCustomer FROM employes INNER JOIN customers ON employes.idCustomer = customers.idCustomer WHERE idEmploye = ?", [Auth::user()->id]);
        $ressources = DB::select("SELECT idRessource, nomRessource FROM ressource_etps WHERE idCustomer = ?", [$customer[0]->idCustomer]);

        return view("ETP.ressources.index", compact("ressources"));
    }

    public function storeMateriel(Request $req){
        $customer = DB::select("SELECT employes.idEmploye, employes.idCustomer, customers.idTypeCustomer FROM employes INNER JOIN customers ON employes.idCustomer = customers.idCustomer WHERE idEmploye = ?", [Auth::user()->id]);
        
        //interne
        $validation = Validator::make($req->all(), [
            'codeMateriel' => 'required|min:2',
            'nomMateriel' => 'required|min:2',
            'descriptionMateriel' => 'required|min:2',
            'idTypeMateriel' => 'required',
        ], [
            'codeMateriel.required' => 'Ce champ est obligatoire',
            'codeMateriel.min' => 'Veuillez saisir au moins 2 caractères',
            'nomMateriel.required' => 'Ce champ est obligatoire',
            'nomMateriel.min' => 'Veuillez saisir au moins 2 caractères',
            'descriptionMateriel.required' => 'Ce champ est obligatoire',
            'descriptionMateriel.min' => 'Veuillez saisir au moins 2 caractères',
            'idTypeMateriel.required' => 'Ce champ est obligatoire', 
        ]);

        if ($validation->fails()) {
            return response()->json($validation->messages());
        } else {
            if((int)$req->idTypeMateriel === 1 && $customer[0]->idTypeCustomer == 1){
                try {
                    DB::beginTransaction();
    
                    DB::table('materiel_cfps')->insert([
                        'codeMateriel' => $req->codeMateriel,
                        'nomMateriel' => $req->nomMateriel,
                        'descriptionMateriel' => $req->descriptionMateriel,
                        'idCfp' => $this->idCustomer(),
                        'idTypeMateriel' => $req->idTypeMateriel
                    ]);
    
                    $idMateriel = DB::table('materiel_cfps')->select('idMateriel')->orderBy('idMateriel', 'desc')->limit(1)->get();
    
                    DB::table('materiel_internes')->insert([
                        'idMateriel' => $idMateriel[0]->idMateriel
                    ]);
    
                    DB::commit();
    
                    return response()->json(["success" => "Succès"]);
                } catch (Exception $e) {
                    DB::rollBack();
                    return $e->getMessage();
                }
            }elseif((int)$req->idTypeMateriel === 2){
                $validation = Validator::make($req->all(), [
                    'codeMateriel' => 'required|min:2',
                    'nomMateriel' => 'required|min:2',
                    'descriptionMateriel' => 'required|min:2',
                    'idTypeMateriel' => 'required',
                    'idFournisseur' => 'required',
                ], [
                    'codeMateriel.required' => 'Ce champ est obligatoire',
                    'codeMateriel.min' => 'Veuillez saisir au moins 2 caractères',
                    'nomMateriel.required' => 'Ce champ est obligatoire',
                    'nomMateriel.min' => 'Veuillez saisir au moins 2 caractères',
                    'descriptionMateriel.required' => 'Ce champ est obligatoire',
                    'descriptionMateriel.min' => 'Veuillez saisir au moins 2 caractères',
                    'idTypeMateriel.required' => 'Ce champ est obligatoire', 
                    'idFournisseur.required' => 'Ce champ est obligatoire', 
                ]);
                //externe
    
                if ($validation->fails()) {
                    return response()->json($validation->messages());
                } else {
                    try {
                        DB::beginTransaction();
        
                        DB::table('materiel_cfps')->insert([
                            'codeMateriel' => $req->codeMateriel,
                            'nomMateriel' => $req->nomMateriel,
                            'descriptionMateriel' => $req->descriptionMateriel,
                            'idCfp' => $this->idCustomer(),
                            'idTypeMateriel' => $req->idTypeMateriel
                        ]);
        
                        $idMateriel = DB::table('materiel_cfps')->select('idMateriel')->orderBy('idMateriel', 'desc')->limit(1)->get();
        
                        DB::table('materiel_externes')->insert([
                            'idMateriel' => $idMateriel[0]->idMateriel,
                            'idFournisseur' => $req->idFournisseur
                        ]);
        
                        DB::commit();
        
                        return response()->json(["success" => "Succès"]);
                    } catch (Exception $e) {
                        DB::rollBack();
                        return response()->json(["error" => "Erreur inconnue !"]);
                    }
                }
            }
        };
    }

    public function editMateriel($idMateriel){
        $typeMateriels = DB::table('type_materiels')->select('idTypeMateriel', 'typeMateriel')->get();
        $fournisseurs = DB::table('fournisseurs')->select('idFournisseur', 'nomFournisseur')->get();
        $materiels = DB::table('materiel_cfps')
            ->select('idMateriel', 'codeMateriel', 'nomMateriel', 'descriptionMateriel', 'idTypeMateriel')
            ->where('idMateriel', $idMateriel)
            ->first();

        return response()->json([
            'typeMateriels' => $typeMateriels, 
            'fournisseurs' => $fournisseurs, 
            'materiels' => $materiels
        ]);
    }

    public function updateMateriel(Request $req, $idMateriel){
        if((int)$req->idTypeMaterielEdit === 1){
            //interne
            $req->validate([
                'codeMaterielEdit' => 'required|min:2',
                'nomMaterielEdit' => 'required|min:2',
                'descriptionMaterielEdit' => 'required|min:2',
                'idTypeMaterielEdit' => 'required',
            ]);

            try {
                $update = DB::table('materiel_cfps')->where('idMateriel', $idMateriel)->update([
                    'codeMateriel' => $req->codeMaterielEdit,
                    'nomMateriel' => $req->nomMaterielEdit,
                    'descriptionMateriel' => $req->descriptionMaterielEdit,
                    'idTypeMateriel' => $req->idTypeMaterielEdit,
                ]);

                if($update){
                    return response()->json([
                        "success" => "Succès"
                    ]);
                }else{
                    return response()->json([
                        "error" => "Erreur inconnue 1 !"
                    ]);
                }
            } catch (Exception $e) {
                return response()->json([
                    "error" => "Erreur inconnue 2 !"
                ]);
            }
        }elseif((int)$req->idTypeMaterielEdit === 2){
            //externe
            $req->validate([
                'codeMaterielEdit' => 'required|min:2',
                'nomMaterielEdit' => 'required|min:2',
                'descriptionMaterielEdit' => 'required|min:2',
                'idTypeMaterielEdit' => 'required',
                'idFournisseurEdit' => 'required'
            ]);

            try {
                DB::beginTransaction();

                DB::table('materiel_cfps')->where('idMateriel', $idMateriel)->update([
                    'codeMateriel' => $req->codeMaterielEdit,
                    'nomMateriel' => $req->nomMaterielEdit,
                    'descriptionMateriel' => $req->descriptionMaterielEdit,
                    'idTypeMateriel' => $req->idTypeMaterielEdit,
                ]);

                DB::table('materiel_externes')->where('idMateriel', $idMateriel)->update([
                    'idFournisseur' => $req->idFournisseurEdit
                ]);
                
                DB::commit();

                return response()->json([
                    "success" => "Succès"
                ]);
            } catch (Exception $e) {
                DB::rollBack();
                return response()->json([
                    "error" => "Erreur inconnue !"
                ]);
            }
        }
    }

    public function deleteMateriel($idMateriel){
        $customer = DB::select("SELECT employes.idEmploye, employes.idCustomer, customers.idTypeCustomer FROM employes INNER JOIN customers ON employes.idCustomer = customers.idCustomer WHERE idEmploye = ?", [Auth::user()->id]);
        $check = DB::table('materiel_cfps')->select('idTypeMateriel')->where('idMateriel', $idMateriel)->first();

        //delete pour cfps idTypeCustomer 1: cfp
        if($customer[0]->idTypeCustomer == 1 && $check->idTypeMateriel === 1){
            //interne
            try{
                DB::beginTransaction();

                DB::table('materiel_internes')->where('idMateriel', $idMateriel)->delete();
                DB::table('materiel_cfps')->where('idMateriel', $idMateriel)->delete();
                DB::commit();
                
                return back()->with("success", "Succès");
            }catch(Exception $e){
                DB::rollBack();
                return $e->getMessage();
                return back()->with("error", "Erreur inconnue");
            }
        }elseif($check->idTypeMateriel === 2){
            //externe
            try{
                DB::beginTransaction();

                DB::table('materiel_externes')->where('idMateriel', $idMateriel)->delete();
                DB::table('materiel_cfps')->where('idMateriel', $idMateriel)->delete();
                DB::commit();
                
                return back()->with("success", "Succès");
            }catch(Exception $e){
                DB::rollBack();
                return back()->with("error", "Erreur inconnue");
            }
        }
    }
    
    public function index($idSession){
        $customer = DB::select("SELECT employes.idEmploye, employes.idCustomer, customers.idTypeCustomer FROM employes INNER JOIN customers ON employes.idCustomer = customers.idCustomer WHERE idEmploye = ?", [Auth::user()->id]);

        if($customer[0]->idTypeCustomer == 1){
            $ressources = DB::select("SELECT idRessource, idTypeCustomer, nomRessource, responsable AS type, cfpName as name, etpName, number FROM v_union_ressources WHERE idSession = ?", [$idSession]);
            $ressCount = DB::select("SELECT COUNT(idRessource) AS nbRess FROM v_union_ressources WHERE idSession = ?", [$idSession]);
        }else{
            $ressources = DB::select("SELECT idRessource, idTypeCustomer, nomRessource, responsable AS type, etpName as name, cfpName, number FROM v_union_ressources WHERE idSession = ?", [$idSession]);
            $ressCount = DB::select("SELECT COUNT(idRessource) AS nbRess FROM v_union_ressources WHERE idSession = ?", [$idSession]);
        }

        return response()->json(['ressources' => $ressources, 'ressCount' => $ressCount]);
    }

    public function indexForm($idSession){
        $customer = DB::select("SELECT cfp_formateurs.idFormateur, cfp_formateurs.idCfp, customers.idTypeCustomer
            FROM cfp_formateurs
            INNER JOIN customers ON cfp_formateurs.idCfp = customers.idCustomer WHERE cfp_formateurs.idFormateur = ?", [Auth::user()->id]);

        if($customer[0]->idTypeCustomer == 1){
            $ressources = DB::select("SELECT idRessource, idTypeCustomer, nomRessource, responsable AS type, cfpName as name, etpName, number FROM v_union_ressources WHERE idSession = ?", [$idSession]);
            $ressCount = DB::select("SELECT COUNT(idRessource) AS nbRess FROM v_union_ressources WHERE idSession = ?", [$idSession]);
        }else{
            $ressources = DB::select("SELECT idRessource, idTypeCustomer, nomRessource, responsable AS type, etpName as name, cfpName, number FROM v_union_ressources WHERE idSession = ?", [$idSession]);
            $ressCount = DB::select("SELECT COUNT(idRessource) AS nbRess FROM v_union_ressources WHERE idSession = ?", [$idSession]);
        }

        return response()->json(['ressources' => $ressources, 'ressCount' => $ressCount]);
    }

    public function indexFormInterne($idSession){
        $ressources = DB::select("SELECT idRessource, idTypeCustomer, nomRessource, responsable AS type, etpName as name, cfpName, number FROM v_union_ressources WHERE idSession = ?", [$idSession]);
        $ressCount = DB::select("SELECT COUNT(idRessource) AS nbRess FROM v_union_ressources WHERE idSession = ?", [$idSession]);

        return response()->json(['ressources' => $ressources, 'ressCount' => $ressCount]);
    }

    // CFP et ETP (insert from referent)
    public function store(Request $req){
        $customer = DB::select("SELECT employes.idEmploye, employes.idCustomer, customers.idTypeCustomer FROM employes INNER JOIN customers ON employes.idCustomer = customers.idCustomer WHERE idEmploye = ?", [Auth::user()->id]);
        
        if($customer[0]->idTypeCustomer == 1){
            DB::table('ressource_session_cfps')->insert([
                'idRessource' => $req->idRessource,
                'idType' => $req->idType,
                'idSession' => $req->idSession,
                'number' => $req->number
            ]);
        }else{
            DB::table('ressource_session_etps')->insert([
                'idRessource' => $req->idRessource,
                'idType' => $req->idType,
                'idSession' => $req->idSession,
                'number' => $req->number
            ]);
        };

        return response()->json(['success' => 'Succès']);
    }

    public function destroy($idRessource){
        $customer = DB::select("SELECT employes.idEmploye, employes.idCustomer, customers.idTypeCustomer FROM employes INNER JOIN customers ON employes.idCustomer = customers.idCustomer WHERE idEmploye = ?", [Auth::user()->id]);
        
        if($customer[0]->idTypeCustomer == 1){
            DB::table('ressource_session_cfps')->where('idRessource', $idRessource)->delete();
        }else{
            DB::table('ressource_session_etps')->where('idRessource', $idRessource)->delete();
        }

        return response()->json(['success' => 'Suppression avec succès']);
    }

    public function storeFormInterne(Request $req){
        $formId = DB::select("SELECT idFormateur FROM formateur_internes WHERE idFormateur = ?", [Auth::user()->id]);
        $etpId = DB::select("SELECT idEntreprise FROM formateur_internes WHERE idFormateur = ?", [$formId[0]->idFormateur]);

        DB::table('ressource_etps')->insert([
            'nomRessource' => $req->nomRessource,
            'idType' => $req->idType,
            'idSession' => $req->idSession,
            'idCustomer' => $etpId[0]->idEntreprise
        ]);

        return response()->json(['success' => 'Succès']);
    }

    public function dashboardRessource(){
        return view("CFP.DashboardRessources.index");
    }

    public function dashboardRessourceEtp(){
        return view("ETP.DashboardRessources.index");
    }
}
