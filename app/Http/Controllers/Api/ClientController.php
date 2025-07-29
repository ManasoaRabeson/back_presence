<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Traits\GetQuery;
use Illuminate\Http\Request;
use App\Models\Customer;
use App\Services\CfpService;
use App\Services\CustomerService;
use App\Services\EntrepriseService;
use App\Traits\HasEnterprise;
use Exception;
use Illuminate\Support\Facades\DB;

class ClientController extends Controller
{
    use GetQuery, HasEnterprise;

    public function index(EntrepriseService $etp, $idTypeEtp)
    {
        if(in_array($idTypeEtp, [1, 2, 4, 5, 6, 7])){
            switch($idTypeEtp){
                case 1:
                    $allEtps = $etp->getAllEnterprises(Customer::idCustomer(), 1);
                    break;
                case 2:
                    $allEtps = $etp->getAllEnterprises(Customer::idCustomer(), 2);
                    break;
                case 4:
                    $allEtps = $etp->getAllEnterprises(Customer::idCustomer(), 4);
                    break;
                case 5:
                    $allEtps = $etp->getAllEnterprises(Customer::idCustomer(), 5);
                    break;
                case 6:
                    $allEtps = $etp->getAllEnterprises(Customer::idCustomer(), 6);
                    break;
                case 7:
                    $allEtps = $etp->getAllEnterprises(Customer::idCustomer(), 7);
                    break;
            }
        }else{
            return response()->json([
                'status' => 404,
                'message' => 'introuvable !'
            ], 404);
        }

        // filtre par lettre
        $filters = $etp->letterFilterEnterprises($allEtps);
        $filteredEtps = $filters['filteredEtps'];
        $firstLetter = $filters['firstLetter'];
        $enabledLetters = $filters['enabledLetters'];

        $villeCodeds = $this->getVilleCodeds();
        $typeEntreprises = $this->getTypeEntreprise()->whereIn('idTypeEtp', [1, 4, 5, 6, 7])->get();

        if(count($allEtps) <= 0){
            return response()->json([
                'status' => 404,
                'message' => 'Aucun élement trouvé !'
            ], 404);
        }

        return response()->json([
            'status' => 200,
            'allEtps' => $allEtps,
            'filteredEtps' => $filteredEtps,
            'firstLetter' => $firstLetter,
            'enabledLetters' => $enabledLetters,
            'ville_codeds' => $villeCodeds,
            'typeEntreprises' => $typeEntreprises
        ]);
    }

    public function searchName(string $name, EntrepriseService $etp)
    {
        $etps = $etp->index(Customer::idCustomer())->where('etp_name', 'like', '%' . $name . '%')->get();

        if(count($etps) <= 0){
            return response()->json([
                'status' => 404,
                'message' => 'Aucun élement trouvé !'
            ], 404);
        }

        return response()->json([
            'status' => 200,
            'etps' => $etps
        ]);
    }

    public function getAllEtps(EntrepriseService $etp)
    {
        $etps = $etp->index(Customer::idCustomer())->get();

        if(count($etps) <= 0){
            return response()->json([
                'status' => 404,
                'message' => 'Aucun élement trouvé !'
            ], 404);
        }

        return response()->json([
            'status' => 200,
            'etps' => $etps
        ]);
    }

    public function getAllFrais()
    {
        $frais = DB::table('frais')
            ->select('idFrais', 'Frais', 'exemple')
            ->get();

        return response()->json(['frais' => $frais]);
    }

    public function edit($id, EntrepriseService $etp)
    {
        $etp = $etp->edit(Customer::idCustomer(), $id);

        if($etp->exists()){
            return response()->json([
                'status' => 200,
                'entreprise' => $etp->first(),
                'villeCodeds' => $this->getVilleCodeds()
            ]);
        }else{
            return response()->json([
                'status' => 404,
                'message' => 'Entreprise introuvable !'
            ], 404);
        }
    }


    public function update(Request $req, $id, EntrepriseService $etp, CustomerService $cst)
    {
        if(in_array($req->idTypeEtp, [1, 4, 5, 6, 7])){
            $req->validate([
                'etp_name' => 'required|min:2|max:200',
                'etp_email' => 'required|email',
                'idTypeEtp' => 'required|exists:type_entreprises,idTypeEtp'
            ]);
        }else{
            $req->validate([
                'etp_name' => 'required|min:2|max:200',
                'etp_email' => 'required|email'
            ]);
        }

        $entreprise = $etp->edit(Customer::idCustomer(), $id);

        if($entreprise->exists()){
            try {
                DB::transaction(function() use($id, $req, $cst){
                    $cst->update(
                        Customer::idCustomer(), 
                        $id, 
                        $req->etp_nif, 
                        $req->etp_stat, 
                        $req->etp_rcs, 
                        $req->etp_name, 
                        $req->etp_phone, 
                        $req->etp_email, 
                        $req->etp_addr_lot, 
                        $req->etp_addr_quartier, 
                        $req->etp_ville_id, 
                        $req->etp_referent_name, 
                        $req->etp_referent_firstname
                    );
    
                    if(in_array($req->idTypeEtp, [1, 4, 5, 6, 7])){
                        $etp->updateEntreprise(Customer::idCustomer(), $id, $req->idTypeEtp);
                    }
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
                'message' => 'Entreprise introuvable !'
            ], 404);
        }
    }

    public function updateLogo(Request $req, $id, EntrepriseService $etp, CustomerService $cst)
    {
        $query = $etp->edit(Customer::idCustomer(), $id);

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

    public function destroy($id, EntrepriseService $etp)
    {
        $entreprise = $etp->edit(Customer::idCustomer(), $id);

        if($entreprise->exists()){
            if($this->isCollaboratedIntra($id) || $this->isCollaboratedInter($id)){
                return response()->json([
                    'status' => 401,
                    'message' => 'Suppression impossible, Ce client est déjà associé à un projet !'
                ]);
            }

            $etp->destroy(Customer::idCustomer(), $id);

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
