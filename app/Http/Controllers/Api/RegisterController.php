<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Services\CustomerService;
use App\Services\EmployeService;
use App\Services\CreditWalletService;
use App\Services\FormateurService;
use App\Services\LieuService;
use App\Services\UserService;
use App\Traits\GetQuery;
use App\Traits\StoreQuery;
use App\Traits\UpdateQuery;
use Exception;
use Illuminate\Support\Facades\Auth;

class RegisterController extends Controller
{
    use StoreQuery, GetQuery, UpdateQuery;

    public function register(Request $req,  UserService $usr, CustomerService $cst, LieuService $lieu, EmployeService $emp, FormateurService $form, CreditWalletService $creditService){
        // Validation
        $req->validate([
            'account_type' => 'required'
        ]);

        if ($req->account_type == 8) {
            $req->validate([
                'customer_name' => 'required|min:2|max:200',
                'referent_name' => 'required|min:2|max:250',
                'referent_firstname' => 'required|min:2|max:250',
                'customer_email' => 'required|unique:users,email',
                'password' => 'required|min:8|confirmed'
            ]);
        } elseif (in_array($req->account_type, [1, 2, 4, 5, 6, 7, 9])) {
            $req->validate([
                'customer_name' => 'required|min:2|max:200',
                'referent_name' => 'required|min:2|max:250',
                'referent_firstname' => 'required|min:2|max:250',
                'customer_email' => 'required|unique:users,email',
                'password' => 'required|min:8|confirmed'
            ]);
        }

        // Save data
        if ($req->account_type == 9) {
            // compte CFP
            try {
                DB::beginTransaction();

                $user = $usr->store(NULL, $req->referent_name, NULL, $req->customer_email, NULL, Hash::make($req->password));
                $cst->store($user->id, $req->customer_name, $req->customer_email, 7, 1, 1);
                $module = $this->mdls("Default module", 1, $user->id, 1);
                $this->module($module);
                $lieu->store($user->id);
                $fonction = $this->fonctions("default_fonction", $user->id);
                $this->cfp($user->id);
                $emp->store($user->id, 6, $user->id, 1, $fonction);
                $this->roleUser(3, $user->id, 1, 1, 1);

                // make Admin Trainer "Formateur" by default
                $form->storeFormateur($user->id, $user->id, 1);
                $form->storeCfpFormateur($user->id, $user->id, 1, 1);
                $this->roleUser(5, $user->id, 0, 1, 1);

                DB::commit();

                return response()->json([
                    'status' => 200,
                    'message' => "Votre compte a été créer avec succès"
                ]);

                // Auth::login($user);

                // return redirect(RouteServiceProvider::HOME);
            } catch (Exception $e) {
                return response()->json([
                    "status" => 400,
                    "message" => $e->getMessage()
                ]);
            }
        } elseif ($req->account_type == 8) {
            // Compte PARTICULIER
            try {
                DB::beginTransaction();
                $particulier = $usr->store(NULL, $req->referent_name, $req->referent_firstname, $req->customer_email, NULL, Hash::make($req->password));
                $this->particulier($particulier->id);
                $this->roleUser(10, $particulier->id, 1, 1, 1);

                DB::commit();

                return response()->json([
                    'status' => 200,
                    'message' => "Votre compte a été créer avec succès"
                ]);

                // Auth::login($particulier);

                // return redirect(RouteServiceProvider::HOMEPARTICULIER);
            } catch (Exception $e) {
                return response()->json([
                    "status" => 400,
                    "message" => $e->getMessage()
                ]);
            }
        } elseif (in_array($req->account_type, [1, 2, 4, 5, 6, 7])) {
            // Compte ETP(etp privé na etp_single mitovy ihany) et ETP_GROUPE
            try {
                DB::beginTransaction();

                $user = $usr->store(NULL, $req->referent_name, NULL, $req->customer_email, NULL, Hash::make($req->password));
                $cst->store($user->id, $req->customer_name, $req->customer_email, 7, 2, 1);
                $module = $this->mdls("Default module", 1, $user->id, 2);
                $this->moduleInterne($module);
                $lieu->store($user->id);
                $fonction = $this->fonctions("default_fonction", $user->id);
                $this->entreprise($user->id, $req->account_type);

                if ($req->account_type == 1) {
                    $this->etpPrivate($user->id);
                } elseif ($req->account_type == 2) {
                    $this->etpGroupe($user->id);
                }

                $emp->store($user->id, 6, $user->id, 1, $fonction);
                $this->roleUser(6, $user->id, 1, 1, 1);

                // Testing Center -> Crediter les comptes de 1000 crédits des Entreprises d'id : 1, 2, 4, 5, 6, 7 dans la table 'type_entreprises' (v2) (litakelykill)
                // Créditer le compte si éligible
                $creditService->creditNewAccount($user, $req->account_type);
                // Testing Center -> Crediter les comptes de 1000 crédits des Entreprises d'id : 1, 2, 4, 5, 6, 7 dans la table 'type_entreprises' (v2) (litakelykill)

                DB::commit();

                return response()->json([
                    'status' => 200,
                    'message' => "Votre compte a été créer avec succès"
                ]);

                // Auth::login($user);

                // return redirect(RouteServiceProvider::HOMEETP);
            } catch (Exception $e) {
                return response()->json([
                    "status" => 400,
                    "message" => $e->getMessage()
                ]);
            }
        }
    }
}
