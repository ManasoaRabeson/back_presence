<?php

namespace App\Http\Controllers;

use App\Mail\RequestCustomer;
use App\Models\Salle;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;

class SubContractorController extends Controller
{
    public function idCfp()
    {
        $customer = DB::select("SELECT employes.idEmploye, employes.idCustomer, customers.idTypeCustomer FROM employes INNER JOIN customers ON employes.idCustomer = customers.idCustomer WHERE idEmploye = ?", [Auth::user()->id]);
        return $customer[0]->idCustomer;
    }

    // get all subContractor inside "project detail"
    public function getAll()
    {
        $query = DB::table('v_list_sub_contractors')
            ->select('idSubContractor', 'sub_name', 'sub_email', 'sub_logo', 'sub_initial_name')
            ->where('id_cfp', $this->idCfp());

        if ($query->count() > 0) {
            return response(['subContractors' => $query->get()], 200);
        } else {
            return response(['message' => 'Aucun résultat trouvé']);
        }
    }

    public function getNif($nif)
    {
        $nifs = DB::table('customers')
            ->select('idCustomer', 'idTypeCustomer', 'customerName AS customer_name', 'nif AS customer_nif')
            ->where('idTypeCustomer', 1)
            ->whereNot('idCustomer', $this->idCfp())
            ->where('nif', 'like', $nif . '%')
            ->get();

        return response()->json(['nif' => $nifs]);
    }

    public function getCfpDetail($idCfp)
    {
        $cfp = DB::table('customers')
            ->select('idCustomer', 'nif AS customer_nif', 'customerName AS customer_name', 'customerEmail AS customer_email')
            ->where('idCustomer', $idCfp)
            ->first();

        return response()->json(['cfp' => $cfp]);
    }

    public function storeSalle($idCustomer): void
    {
        DB::transaction(function () use ($idCustomer) {
            $idLieu = DB::table('lieux')->insertGetId([
                'li_name' => "Default",
                'idVille' => 1,
                'idLieuType' => 2,
                'idVilleCoded' => 1
            ]);
            DB::table('lieu_privates')->insert([
                'idLieu' => $idLieu,
                'idCustomer' => $idCustomer
            ]);

            Salle::insert([
                'salle_name' => 'In situ',
                'idLieu' => $idLieu
            ]);
        });
    }

    public function sendInvitation(Request $req)
    {
        $validation = Validator::make($req->all(), [
            'etp_rcs'           => 'required|min:2',
            'etp_name'          => 'required|min:2',
            'etp_email'         => 'required'
        ]);

        if ($validation->fails()) {
            return response()->json(['error' => $validation->messages()]);
        } else {
            $checkRcs = DB::table('customers')->where('nif', $req->etp_rcs)->count();
            $checkMail = DB::table('users')->select('email')->where('email', $req->etp_email)->count();

            $checkCfp = DB::table('customers')
                ->select('customerEmail AS email')
                ->where('idCustomer', $this->idCfp())
                ->first();

            $checkEtp = DB::table('customers')
                ->select('customerEmail AS email')
                ->where('nif', $req->etp_rcs)
                ->where('customerEmail', $req->etp_email)
                ->first();

            $cfp = DB::table('customers')->select('idCustomer', 'customerName', 'customer_addr_lot AS customerAdress')->where('idCustomer', $this->idCfp())->first();

            if ($checkRcs <= 0 && $checkMail <= 0 && $checkCfp->email != $req->etp_email) {
                try {
                    DB::beginTransaction();
                    $user = new User();
                    $user->name = $req->etp_referent_name;
                    $user->firstName = $req->etp_referent_firstname;
                    $user->email = $req->etp_email;
                    $user->password = Hash::make('1234@#');
                    $user->save();

                    DB::table('customers')->insert([
                        'idCustomer'    => $user->id,
                        'customerName'  => $req->etp_name,
                        'nif'           => $req->etp_rcs,
                        'customerEmail' => $req->etp_email,
                        'idSecteur'     => 7,
                        'idTypeCustomer' => 1
                    ]);

                    DB::table('cfps')->insert([
                        'idCustomer' => $user->id,
                    ]);

                    DB::table('sub_contractors')->insert([
                        'idSubContractor' => $user->id,
                        'idCfp' => Auth::user()->id
                    ]);

                    $customer = DB::table('customers')->select('idCustomer')->orderBy('idCustomer', 'desc')->first();

                    $this->storeSalle($user->id);

                    $idFonction = DB::table('fonctions')->insertGetId([
                        'fonction' => "default_fonction",
                        'idCustomer' => $user->id
                    ]);

                    $idModule = DB::table('mdls')->insertGetId([
                        'moduleName' => "Default module",
                        'idDomaine' => 1,
                        'idCustomer' => $user->id,
                        'idTypeModule' => 1
                    ]);

                    DB::table('modules')->insert(['idModule' => $idModule]);

                    DB::table('employes')->insert([
                        'idEmploye'     => $user->id,
                        'idCustomer'    => $customer->idCustomer,
                        'idSexe'        => 1,
                        'idNiveau'      => 1,
                        'idFonction'    => $idFonction
                    ]);

                    DB::table('role_users')->insert([
                        'role_id'   => 3,
                        'user_id'   => $user->id,
                        'hasRole'   => 1,
                        'isActive'  => 1
                    ]);

                    Mail::to($req->etp_email)->send(new RequestCustomer($cfp));
                    DB::commit();

                    return response()->json(['success' => 'Invitation envoyée avec succès']);
                } catch (Exception $e) {
                    DB::rollBack();
                    return response()->json(['error' => $e->getMessage()]);
                }
            } elseif ($checkRcs >= 1 && $req->etp_email == $checkEtp->email) {
                $req->validate(['idCfp' => 'required|exists:customers,idCustomer']);

                $isCfp = DB::table('users')
                    ->join('customers', 'customers.idCustomer', 'users.id')
                    ->select('email', 'idTypeCustomer')
                    ->where('email', $req->etp_email)
                    ->first();

                $isCollaborated = DB::table('sub_contractors')
                    ->select('idSubContractor', 'idCfp')
                    ->where('idCfp', Auth::user()->id)
                    ->where('idSubContractor', $req->idCfp)
                    ->count('idSubContractor', 'idCfp');

                if ($isCfp->idTypeCustomer == 1 && $isCollaborated <= 0) {
                    try {
                        DB::beginTransaction();

                        DB::table('sub_contractors')->insert([
                            'idSubContractor' => $req->idCfp,
                            'idCfp' => Auth::user()->id
                        ]);

                        Mail::to($req->etp_email)->send(new RequestCustomer($cfp));
                        DB::commit();

                        return response()->json(['success' => 'Invitation envoyée avec succès']);
                    } catch (Exception $e) {
                        DB::rollBack();
                        return response()->json(['error' => $e->getMessage()]);
                    }
                } else {
                    return response()->json(['error' => 'Erreur inconnue, Veuillez verifier votre invitation !']);
                }
            } else {
                return response()->json(['error' => "Mail existant, veuillez vérifier vos données !"]);
            }
        }
    }

    public function assign($idProjet, $idSubContractor)
    {
        $query = DB::table('project_sub_contracts')
            ->select('idProjet', 'idSubContractor')
            ->where('idProjet', $idProjet);

        $checkForm = DB::table('project_forms')->select('idProjet', 'idFormateur')->where('idProjet', $idProjet)->count();

        if ($query->count() <= 0) {
            DB::transaction(function () use ($query, $idProjet, $idSubContractor, $checkForm) {
                $query->insert([
                    'idProjet' => $idProjet,
                    'idSubContractor' => $idSubContractor
                ]);

                if ($checkForm > 0) {
                    DB::table('project_forms')->where('idProjet', $idProjet)->delete();
                }
            });

            return response()->json(['success' => 'Succès']);
        } else {
            return response()->json(['error' => 'Sous-traitant déjas inscrit au projet !']);
        }
    }

    public function getAssign($idProjet)
    {
        $query = DB::table('v_list_sub_contractor_addeds')
            ->where('idProjet', $idProjet);

        if (isset($query->first()->idCfp) && $this->idCfp() == $query->first()->idCfp) {
            $query->select('idSubContractor', 'sub_name as cfp_name', 'sub_initial_name as cfp_initial_name', 'sub_logo as cfp_logo', 'sub_email as cfp_email');
        } else {
            $query->select('idCfp as idSubContractor', 'cfp_name', 'cfp_initial_name', 'cfp_logo', 'cfp_email');
        }

        if ($query->first()) {
            return response()->json(['cfp' => $query->first()]);
        } else {
            return response(['error' => 'Introuvable !']);
        }
    }

    public function removeAssign($idSubContractor)
    {
        $query = DB::table('project_sub_contracts')->where('idSubContractor', $idSubContractor);

        if ($query->first()) {
            $query->delete();

            return response(['success' => 'Opération éffectuée avec succès'], 200);
        } else {
            return response(['error' => 'Introuvable !'], 404);
        }
    }

    public function getSubContractorList()
    {
        $query = DB::table('v_list_sub_contractors')
            ->select('idSubContractor as idSub', 'sub_name', 'sub_email', 'sub_initial_name', 'sub_logo', 'name', 'firstName')
            ->join('users', 'idSubContractor', 'id')
            ->where('idCfp', $this->idCfp())
            ->get();

        if (isset($query)) {
            $subContractors = $query;
        } else {
            $subContractors = null;
        }


        return view('CFP.subContractor.index', compact(['subContractors']));
    }
}
