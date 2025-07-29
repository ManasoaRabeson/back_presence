<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Customer;
use App\Services\CfpService;
use App\Services\CustomerService;
use App\Services\EntrepriseService;
use App\Traits\GetQuery;
use Exception;
use Illuminate\Support\Facades\DB;

class ClientCfpController extends Controller
{
    use GetQuery;

    public function index(CfpService $cfp){
        $cfps = $cfp->index(Customer::idCustomer())->get();

        if(count($cfps) <= 0){
            return response()->json([
                'status' => 404,
                'message' => 'Aucun élément trouvé !'
            ], 404);
        }

        return response()->json([
            'status' => 200,
            'cfps' => $cfps
        ]);
    }

    public function edit($id, CfpService $cfp)
    {
        $cfp = $cfp->edit(Customer::idCustomer(), $id);

        if($cfp->exists()){
            return response()->json([
                'status' => 200,
                'entreprise' => $cfp->first(),
                'villeCodeds' => $this->getVilleCodeds()
            ]);
        }else{
            return response()->json([
                'status' => 404,
                'message' => 'CFP introuvable !'
            ], 404);
        }
    }

    public function update(Request $req, $id, CfpService $cfp, CustomerService $cst)
    {
        $req->validate([
            'cfp_name' => 'required|min:2|max:200',
            'cfp_email' => 'required|email'
        ]);

        $cf = $cfp->edit(Customer::idCustomer(), $id);

        if($cf->exists()){
            try {
                DB::transaction(function() use($id, $req, $cst){
                    $cst->update(
                        Customer::idCustomer(), 
                        $id, 
                        $req->cfp_nif, 
                        $req->cfp_stat, 
                        $req->cfp_rcs, 
                        $req->cfp_name, 
                        $req->cfp_phone, 
                        $req->cfp_email, 
                        $req->cfp_addr_lot, 
                        $req->cfp_addr_quartier, 
                        $req->cfp_ville_id, 
                        $req->cfp_referent_name, 
                        $req->cfp_referent_firstname
                    );
                });
    
                return response()->json([
                    'status' => 200,
                    'message' => 'Modifiée avec succès'
                ]);
            } catch (Exception $e) {
                return response()->json([
                    'status' => 400,
                    'message' => 'Erreur inconnue !'
                ]);
            }
        }else{
            return response()->json([
                'status' => 404,
                'message' => 'CFP introuvable !'
            ], 404);
        }
    }

    public function updateLogo(Request $req, $id, CfpService $cf, CustomerService $cst)
    {
        $query = $cf->edit(Customer::idCustomer(), $id);

        if($query->exists()){
            $cst->updateLogo(Customer::idCustomer(), $id, $query, $req->image);

            return response()->json([
                'status' => 200,
                'message' => 'Logo ajouté avec succès'
            ]);
        }{
            return response()->json([
                'status' => 404,
                'message' => 'Entreprise introuvable !'
            ], 404);
        }
    }

    public function destroy($id, CfpService $cf)
    {
        $cfp = $cf->edit(Customer::idCustomer(), $id);

        if($cfp->exists()){
            if($this->isCollaboratedIntra($id) || $this->isCollaboratedInter($id)){
                return response()->json([
                    'status' => 401,
                    'message' => 'Suppression impossible, Ce client est déjà associé à un projet !'
                ]);
            }

            $cf->destroy(Customer::idCustomer(), $id);

            return response()->json([
                'status' => 200,
                'message' => "Client supprimé avec succès",
            ]);
        }else{
            return response()->json([
                'status' => 404,
                'message' => 'Entreprise introuvable !'
            ], 404);
        }
    }
}
