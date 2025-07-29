<?php

namespace App\Http\Controllers;

use App\Mail\InvitationReferent;
use App\Mail\PasswordChange;
use App\Models\Customer;
use Exception;
use App\Models\User;
use App\Models\Employe;
use App\Models\RoleUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Intervention\Image\Drivers\Gd\Driver;
use Intervention\Image\ImageManager;
use Laravelcm\Subscriptions\Models\Feature;
use Laravelcm\Subscriptions\Models\Subscription;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Mail;

class EmployecfpController extends Controller
{
    public function idCfp()
    {
        $customer = DB::select("SELECT employes.idEmploye, employes.idCustomer, customers.idTypeCustomer FROM employes INNER JOIN customers ON employes.idCustomer = customers.idCustomer WHERE idEmploye = ?", [Auth::user()->id]);
        return $customer[0]->idCustomer;
    }

    public function index()
    {
        $referentAll = DB::table('v_employe_alls')
            ->select('idEmploye', 'idCustomer', 'role_id', 'matricule as ref_matricule', 'initialName as ref_initial_name', 'name as ref_name', 'firstName as ref_firstname', 'phone as ref_phone', 'email as ref_email', 'cin as ref_cin', 'adresse as ref_adresse', 'sexe as ref_sexe', 'fonction as ref_fonction', 'photo as ref_photo', 'idSexe', 'isActive', 'hasRole', 'phone as ref_phone')
            ->where('idCustomer', $this->idCfp())
            ->where('role_id', 8)
            ->orderBy('isActive', 'desc')
            ->paginate(10);

        $id = Auth::user()->id;

        $refConnected = DB::table('users')
            ->select('role_users.role_id')
            ->join('role_users', 'users.id', '=', 'role_users.user_id')
            ->where('users.id',  $id)
            ->where('role_users.role_id', 3)
            ->first();

        $referentActifs = DB::table('v_employe_alls')
            ->select('idEmploye', 'idCustomer', 'role_id', 'matricule as ref_matricule', 'initialName as ref_initial_name', 'name as ref_name', 'firstName as ref_firstname', 'phone as ref_phone', 'email as ref_email', 'cin as ref_cin', 'adresse as ref_adresse', 'sexe as ref_sexe', 'fonction as ref_fonction', 'photo as ref_photo', 'idSexe', 'isActive', 'hasRole', 'phone as ref_phone')
            ->where('idCustomer', $this->idCfp())
            ->where('role_id', 8)
            ->where('isActive', 1)
            ->orderBy('name', 'asc')
            ->paginate(10);

        $referentInactifs = DB::table('v_employe_alls')
            ->select('idEmploye', 'idCustomer', 'role_id', 'matricule as ref_matricule', 'initialName as ref_initial_name', 'name as ref_name', 'firstName as ref_firstname', 'phone as ref_phone', 'email as ref_email', 'cin as ref_cin', 'adresse as ref_adresse', 'sexe as ref_sexe', 'fonction as ref_fonction', 'photo as ref_photo', 'idSexe', 'isActive', 'hasRole', 'phone as ref_phone')
            ->where('idCustomer', $this->idCfp())
            ->where('role_id', 8)
            ->where('isActive', 0)
            ->orderBy('name', 'asc')
            ->paginate(10);

        $countReferentAll = DB::table('v_employe_alls')->where('idCustomer', $this->idCfp())->where('role_id', 8)->count();
        $countReferentActifs = DB::table('v_employe_alls')->where('idCustomer', $this->idCfp())->where('role_id', 8)->where('isActive', 1)->count();
        $countReferentInactifs = DB::table('v_employe_alls')->where('idCustomer', $this->idCfp())->where('role_id', 8)->where('isActive', 0)->count();

        return view('CFP.employeCfps.index', compact(['referentAll', 'referentActifs', 'referentInactifs', 'countReferentAll', 'countReferentActifs', 'countReferentInactifs', 'refConnected']));
    }

    public function updatePassword($idEmploye, Request $req)
    {
        $validated = $req->validate([
            'password' => 'required|min:6',
        ]);

        $user = User::find($idEmploye);

        if ($user) {
            $user->password = Hash::make($validated['password']);
            $password = $validated['password'];
            $user->save();

            $cfp = DB::table('customers')->select('idCustomer', 'customerName', 'customer_addr_lot AS customerAdress')->where('idCustomer', $this->idCfp())->first();
            $ref = $req->emp_email;
            Mail::to($req->emp_email)->send(new PasswordChange($cfp, $ref, $password));
            return response()->json(['success' => 'Mot de passe modifié avec succès.'], 200);
        }

        return response()->json(['error' => 'Utilisateur non trouvé.'], 404);
    }


    public function store(Request $req)
    {
        // // LIMITEUR PAR RAPPORT AU ABONNEMENT
        // $authenticatedUser = Auth::user();
        // $user = User::findOrFail($authenticatedUser->id);

        // //Check raha efa nanao abonnement
        // $sub = Subscription::where('subscriber_id', Auth::user()->id)->first();
        // if (!$sub) {
        //     return response()->json(['error' => 'Vous devriez vous abonner']);
        // }
        // //Maka subscriptionSlug
        // $subscriptionSlug = Subscription::where('subscriber_id', Auth::user()->id)->first()->slug;
        // //Maka idPlan sy featureSlug
        // $idplan = $user->planSubscriptions()->first()->plan_id;
        // $featureSlug = Feature::where('plan_id', $idplan)->where('name', '{"fr":"Référents"}')->first()->slug;

        // $subscription = $user->planSubscription($subscriptionSlug);
        // $usage = $subscription->usage()->byFeatureSlug($featureSlug)->first();

        // //Initialisation du premier usage 0
        // if (!$usage) {
        //     $subscription->recordFeatureUsage($featureSlug, 0, false);
        // }

        // if (!$subscription->canUseFeature($featureSlug)) {
        //     return response()->json(['error' => 'Vous avez atteint le nombre maximum de Référents autorisés.']);
        // }
        // // FIN LIMITEUR PAR RAPPORT AU ABONNEMENT

        $validation = Validator::make($req->all(), [
            // 'emp_matricule' => 'required|max:50|unique:users,matricule',
            'emp_name' => 'required|max:240',
            'emp_email' => 'required|max:150|unique:users,email'
        ]);

        if ($validation->fails()) {
            return response()->json(['error' => $validation->messages()]);
        } else {
            $cfp = DB::table('customers')
                ->select('idCustomer')
                ->where('idCustomer', $this->idCfp())
                ->first();

            try {

                DB::beginTransaction();

                $fonction = DB::table('fonctions')->select('idFonction')->where('idCustomer', $this->idCfp())->first();

                $user = new User();
                $user->matricule = $req->emp_matricule;
                $user->name = $req->emp_name;
                $user->firstName = $req->emp_firstname;
                $user->email = $req->emp_email;
                $user->phone = $req->emp_phone;
                $password = Hash::make('0000@#');
                $user->password = $password;
                $user->save();

                $emp = new Employe();
                $emp->idEmploye = $user->id;
                $emp->idSexe = 1;
                $emp->idNiveau = 6;
                $emp->idCustomer = $cfp->idCustomer;
                $emp->idFonction = $fonction->idFonction;
                $emp->save();

                RoleUser::create([
                    'role_id'  => 8,
                    'user_id'  => $user->id,
                    'isActive' => 1,
                    'hasRole' => 1
                ]);

                $cfp = DB::table('customers')->select('idCustomer', 'customerName', 'customer_addr_lot AS customerAdress')->where('idCustomer', $this->idCfp())->first();
                $ref = $req->emp_email;
                Mail::to($req->emp_email)->send(new InvitationReferent($cfp, $ref));

                DB::commit();
                // $subscription->recordFeatureUsage($featureSlug);
                return response()->json(["success" => "Succès"]);
            } catch (Exception $e) {
                DB::rollBack();
                return response()->json(["error" => "Erreur inconnue !"]);
            }
        }
    }

    public function edit($idEmploye)
    {
        $query = DB::table('v_employe_alls')
            ->select('idEmploye', 'idCustomer', 'matricule', 'initialName', 'name', 'firstName', 'phone', 'email', 'cin', 'fonction', 'photo', 'isActive', 'hasRole', 'user_addr_quartier', 'user_addr_lot', 'user_addr_rue', 'user_addr_code_postal')
            ->where('idEmploye', $idEmploye);

        if ($query->first()) {
            $emp = $query->first();
            $villes = DB::table('villes')->select('idVille', 'ville')->get();

            return response()->json([
                'emp' => $emp,
                'villes' => $villes
            ]);
        } else {
            return response()->json(['error' => "Employé introuvable !"]);
        }
    }

    public function update(Request $req, $idEmploye)
    {
        $validate = Validator::make($req->all(), [
            'emp_name' => 'required|min:3|max:240',
            'emp_email' => 'required|min:3|max:150|email'
        ]);

        if ($validate->fails()) {
            return response()->json(['error' => $validate->messages()]);
        } else {
            $query = DB::table('users')->where('id', $idEmploye);

            if ($query->first()) {
                $query->update([
                    'matricule' => $req->emp_matricule,
                    'name' => $req->emp_name,
                    'firstName' => $req->emp_firstname,
                    //'photo' => $req->emp_photo,
                    'phone' => $req->emp_phone,
                    'email' => $req->emp_email,
                    'user_addr_lot' => $req->emp_lot,
                    'user_addr_quartier' => $req->emp_quartier,
                    'user_addr_code_postal' => $req->emp_code_postal
                ]);

                return response()->json(['success' => 'Succès']);
            } else {
                return response()->json(['error' => "Employes introuvable !"]);
            }
        }
    }

    // suppression referent
    public function destroy($id)
    {
        $query = DB::table('users')->join('role_users', 'users.id', 'role_users.user_id')->where('users.id', $id)->where('role_users.role_id', '!=', 3);

        if ($query->first()) {
            $chekProject = DB::table('detail_apprenants')->where('idEmploye', $id)->count();
            $chekProjectInter = DB::table('detail_apprenant_inters')->where('idEmploye', $id)->count();

            if ($chekProject <= 0 || $chekProjectInter <= 0) {
                DB::transaction(function () use ($query, $id) {
                    DB::table('employes')->where('idEmploye', $id)->delete();
                    $query->delete();
                });
                return back()->with('success', 'Référent supprimé avec succès !');
            } else
                return back()->with('error', 'Suppression impossible !');
        } else
            return back()->with('error', 'Référent introuvable !');
    }





















    public function editPhoto($empCfpId)
    {
        $emp = Employe::where('idEmploye', '=', $empCfpId)->firstOrFail();

        return view('CFP.employeCfps.editPhoto', compact('emp'));
    }

    public function updatePhoto(Request $req, $empId)
    {
        $driver = new Driver();

        $manager = new ImageManager($driver);
        $referent = DB::table('users')->select('photo')->where('id', $empId)->first();

        if ($referent != null) {
            if (!empty($referent->photo)) {
                Storage::disk('do')->delete('img/referents/' . $referent->photo);
            }

            $folderPath = public_path('img/referents/');

            $image_parts = explode(";base64,", $req->image);
            $image_type_aux = explode("image/", $image_parts[0]);
            $image_type = $image_type_aux[1];
            $image_base64 = base64_decode($image_parts[1]);
            $image = $manager->read($image_base64)->toWebp(25);

            $imageName = uniqid() . '.webp';
            $filePath = 'img/referents/' . $imageName;

            // Upload the image to DigitalOcean Space
            Storage::disk('do')->put($filePath, $image, 'public');

            DB::table('users')->where('id', $empId)->update([
                'photo' => $imageName,
            ]);
            return response()->json([
                'success' => 'Image Uploaded Successfully',
                'imageName' =>  $imageName
            ]);
        }
    }

    public function activate(Request $req, $idEmploye)
    {
        $abn = DB::table('v_abonnement_cfps')
            ->select('idAbn', 'idCustomer', 'nbReferent', 'isInfinity', 'isActive')
            ->where('idCustomer', $this->idCfp())
            ->where('isActive', 1)
            ->first();

        $check = DB::select("SELECT COUNT(idEmploye) AS nbrEmp FROM v_employe_alls WHERE idCustomer = ? AND isActive = ? AND role_id = ? OR role_id = ?", [Auth::user()->id, 1, 3, 8]);

        if ($abn->isInfinity == 1) {
            DB::table('employes')
                ->join('users', 'users.id', 'employes.idEmploye')
                ->join('role_users', 'role_users.user_id', 'users.id')
                ->where('idEmploye', $idEmploye)
                ->update([
                    'role_users.isActive' => 1
                ]);

            return response()->json(["success" => "Succès"]);
        } elseif ($abn->idAbn == $req->idAbn) {
            if ($check[0]->nbrEmp < intval($req->nbRef)) {
                DB::table('employes')
                    ->join('users', 'users.id', 'employes.idEmploye')
                    ->join('role_users', 'role_users.user_id', 'users.id')
                    ->where('idEmploye', $idEmploye)
                    ->update([
                        'role_users.isActive' => 1
                    ]);

                return response()->json(["success" => "Succès"]);
            } else {
                return response()->json(["error" => "Vous avez atteint le nombre maximale de réferent, Veuillez mettre à niveau votre abonnement !"]);
            }
        }
    }

    public function disableEmp($idEmploye)
    {
        $emp = DB::table('employes')
            ->join('users', 'users.id', 'employes.idEmploye')
            ->join('role_users', 'role_users.user_id', 'users.id')
            ->where('idEmploye', $idEmploye)
            ->update([
                'role_users.isActive' => 0
            ]);

        if ($emp) {
            return response()->json([
                "success" => "Succès"
            ]);
        } else {
            return response()->json([
                "error" => "Erreur Inconnue"
            ]);
        }
    }
}
