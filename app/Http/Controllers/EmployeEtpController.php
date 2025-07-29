<?php

namespace App\Http\Controllers;

use App\Mail\PasswordChange;
use App\Models\Customer;
use Exception;
use App\Models\User;
use App\Models\Employe;
use App\Models\RoleUser;
use App\Services\EmployeService;
use App\Services\UserService;
use App\Traits\GetQuery;
use App\Traits\StoreQuery;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Intervention\Image\Drivers\Gd\Driver;
use Intervention\Image\ImageManager;
use Laravelcm\Subscriptions\Models\Feature;
use Laravelcm\Subscriptions\Models\Subscription;

class EmployeEtpController extends Controller
{
    use GetQuery, StoreQuery;

    public function idEtp()
    {
        $customer = DB::select("SELECT employes.idEmploye, employes.idCustomer, customers.idTypeCustomer FROM employes INNER JOIN customers ON employes.idCustomer = customers.idCustomer WHERE idEmploye = ?", [Auth::user()->id]);
        return $customer[0]->idCustomer;
    }

    public function index()
    {
        $referentAll = DB::table('v_employe_alls')
            ->select('idEmploye', 'idCustomer', 'role_id', 'matricule as ref_matricule', 'initialName as ref_initial_name', 'name as ref_name', 'firstName as ref_firstname', 'phone as ref_phone', 'email as ref_email', 'cin as ref_cin', 'adresse as ref_adresse', 'sexe as ref_sexe', 'fonction as ref_fonction', 'photo as ref_photo', 'idSexe', 'isActive', 'hasRole', 'phone as ref_phone')
            ->where('idCustomer', $this->idEtp())
            ->where('role_id', 9)
            ->orderBy('isActive', 'desc')
            ->paginate(10);

        $referentActifs = DB::table('v_employe_alls')
            ->select('idEmploye', 'idCustomer', 'role_id', 'matricule as ref_matricule', 'initialName as ref_initial_name', 'name as ref_name', 'firstName as ref_firstname', 'phone as ref_phone', 'email as ref_email', 'cin as ref_cin', 'adresse as ref_adresse', 'sexe as ref_sexe', 'fonction as ref_fonction', 'photo as ref_photo', 'idSexe', 'isActive', 'hasRole', 'phone as ref_phone')
            ->where('idCustomer', $this->idEtp())
            ->where('role_id', 9)
            ->where('isActive', 1)
            ->orderBy('name', 'asc')
            ->paginate(10);


        $id = Auth::user()->id;

        $refConnected = DB::table('users')
            ->select('role_users.role_id')
            ->join('role_users', 'users.id', '=', 'role_users.user_id')
            ->where('users.id',  $id)
            ->where('role_users.role_id', 6)
            ->first();

        // dd($refConnected);

        $referentInactifs = DB::table('v_employe_alls')
            ->select('idEmploye', 'idCustomer', 'role_id', 'matricule as ref_matricule', 'initialName as ref_initial_name', 'name as ref_name', 'firstName as ref_firstname', 'phone as ref_phone', 'email as ref_email', 'cin as ref_cin', 'adresse as ref_adresse', 'sexe as ref_sexe', 'fonction as ref_fonction', 'photo as ref_photo', 'idSexe', 'isActive', 'hasRole', 'phone as ref_phone')
            ->where('idCustomer', $this->idEtp())
            ->where('role_id', 9)
            ->where('isActive', 0)
            ->orderBy('name', 'asc')
            ->paginate(10);

        $countReferentAll = DB::table('v_employe_alls')->where('idCustomer', $this->idEtp())->where('role_id', 9)->count();
        $countReferentActifs = DB::table('v_employe_alls')->where('idCustomer', $this->idEtp())->where('role_id', 9)->where('isActive', 1)->count();
        $countReferentInactifs = DB::table('v_employe_alls')->where('idCustomer', $this->idEtp())->where('role_id', 9)->where('isActive', 0)->count();

        return view('ETP.employeEtps.index', compact(['referentAll', 'referentActifs', 'referentInactifs', 'countReferentAll', 'countReferentActifs', 'countReferentInactifs', 'refConnected']));
    }

    public function store(Request $req, UserService $usr, EmployeService $employe)
    {
        $validation = Validator::make($req->all(), [
            'emp_name' => 'required|max:240|min:2',
            'emp_email' => 'required|max:150|unique:users,email'
        ]);

        if ($validation->fails()) {
            return response([
                'status' => 422,
                'message' => $validation->messages()
            ]);
        }

        try {
            DB::transaction(function() use($req, $usr, $employe){
                $user = $usr->store($req->emp_matricule, $req->emp_name, $req->emp_firstname, $req->emp_email, $req->emp_phone, Hash::make('0000@#'));
                $employe->store($user->id, 6, Customer::idCustomer(), 1, $this->getIdFonction(Customer::idCustomer()));
                $this->roleUser(9, $user->id, 1, 1, 1);
            });

            return response([
                'status' => 200,
                'message' => 'Réferent ajouté avec succès'
            ]);
        } catch (Exception $e) {
            return response([
                'status' => 411,
                'message' => $e->getMessage()
            ]);
        }
    }

    public function edit($idEmploye)
    {
        $emp = DB::table('v_employe_alls')
            ->select('idEmploye', 'idCustomer', 'matricule', 'initialName', 'name', 'firstName', 'phone', 'email', 'cin', 'fonction', 'photo', 'isActive', 'hasRole', 'user_addr_quartier', 'user_addr_lot', 'user_addr_rue', 'user_addr_code_postal')
            ->where('idEmploye', $idEmploye)
            ->first();

        $villes = DB::table('villes')->select('idVille', 'ville')->get();

        return response()->json([
            'emp' => $emp,
            'villes' => $villes
        ]);
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
                return response()->json(['error' => 'Opération echouée !']);
            }
        }
    }

    public function show($idEmploye)
    {
        $emp = DB::table('v_employe_alls')
            ->select('idEmploye', 'idCustomer', 'role_id', 'matricule', 'initialName', 'name', 'firstName', 'phone as phoneEmp', 'email as mailEmp', 'cin', 'adresse', 'sexe', 'fonction', 'photo as photoEmp', 'idSexe', 'isActive', 'hasRole')
            ->where('idEmploye', $idEmploye)
            ->first();

        return response()->json(['emp' => $emp]);
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

        return view('ETP.employeEtps.editPhoto', compact('emp'));
    }

    public function updatePhoto(Request $req, $empId)
    {
        $referent = DB::table('users')->select('photo')->where('id', $empId)->first();

        $driver = new Driver();

        $manager = new ImageManager($driver);

        if ($referent != null) {

            if (!empty($module->module_image)) {
                Storage::disk('do')->delete('img/referents/' . $referent->photo);
            }

            $image_parts = explode(";base64,", $req->image);
            $image_type_aux = explode("image/", $image_parts[0]);
            $image_type = $image_type_aux[1];
            $image_base64 = base64_decode($image_parts[1]);
            $image = $manager->read($image_base64)->toWebp(25);

            $imageName = uniqid() . '.webp';

            $filePath = 'img/referents/' . $imageName;

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


    public function activate(Request $req, $idEmploye)
    {
        $abn = DB::table('v_abonnement_cfps')
            ->select('idAbn', 'idCustomer', 'nbReferent', 'isInfinity', 'isActive')
            ->where('idCustomer', $this->idEtp())
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
