<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Employe;
use App\Models\RoleUser;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Intervention\Image\Drivers\Gd\Driver;
use Intervention\Image\ImageManager;
use Laravelcm\Subscriptions\Models\Feature;
use Laravelcm\Subscriptions\Models\Subscription;

class FormateurInterneController extends Controller
{

    public function idEtp()
    {
        $customer = DB::select("SELECT employes.idEmploye, employes.idCustomer, customers.idTypeCustomer FROM employes INNER JOIN customers ON employes.idCustomer = customers.idCustomer WHERE idEmploye = ?", [Auth::user()->id]);
        return $customer[0]->idCustomer;
    }

    public function randomPassword()
    {
        $alphabet = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890';
        $pass = [];
        $alphaLength = strlen($alphabet) - 1;

        for ($i = 0; $i < 8; $i++) {
            $n = rand(0, $alphaLength);
            $pass[] = $alphabet[$n];
        }

        return implode($pass);
    }

    public function index()
    {
        $formInternes = DB::table('formateur_internes')
            ->join('forms', 'formateur_internes.idFormateur', 'forms.idFormateur')
            ->join('employes', 'formateur_internes.idEmploye', 'employes.idEmploye')
            ->join('sexes', 'employes.idSexe', 'sexes.idSexe')
            ->join('type_formateurs', 'forms.idTypeFormateur', 'type_formateurs.idTypeFormateur')
            ->join('users', 'forms.idFormateur', 'users.id')
            //->select('forms.idFormateur', 'forms.name', 'forms.firstName', 'forms.photo', 'forms.cin', 'users.email', 'type_formateurs.type', 'sexes.sexe')
            ->where('formateur_internes.idEntreprise', '=', Auth::user()->id)
            ->get();
        return view('ETP.formateurInternes.index', compact('formInternes'));
    }

    public function store(Request $req)
    {
        $req->validate([
            'idForm' => 'required|integer',
            'mailForm' => 'required'
        ]);

        $typeForm = DB::table('type_formateurs')->select('idTypeFormateur')->get();

        $check = DB::table('formateur_internes')
            ->select('idFormateur')
            ->where('idFormateur', '=', $req->idForm)
            ->get();

        // if(count($check) < 1){
        //     try{
        //         DB::beginTransaction();
        //         $form = DB::table('employes')
        //             //->join('users','id')
        //             ->select('idEmploye', 'name', 'firstName', 'mailEmp', 'adress', 'cin', 'phoneEmp', 'idSexe', 'idNiveau', 'photoEmp')
        //             ->where('idEmploye', '=', $req->idForm)
        //             ->where('mailEmp', '=', $req->mailForm)
        //             ->first();

        //         DB::table('forms')->insert([
        //             'idFormateur' => $req->idForm,
        //             'name' => $form->name,
        //             'firstName' => $form->firstName,
        //             'adress' => $form->adress,
        //             'cin' => $form->cin,
        //             'phone' => '032333333333',
        //             'idTypeFormateur' => $typeForm[1]->idTypeFormateur,
        //             'photo' => $form->photoEmp
        //         ]);

        //         $user = DB::table('users')->select('id', 'email')->where('email', '=', $req->mailForm)->first();

        //         DB::table('users')
        //             ->join('role_users', 'role_users.user_id', 'users.id')
        //             ->select('users.id')
        //             ->where('users.email', '=', $req->mailForm)
        //             ->update([
        //                 'role_users.isActive' => 1,
        //                 'role_users.hasRole' => 0
        //             ]);

        //         RoleUser::create([
        //             'role_id'  => 7,
        //             'user_id'  => $user->id,
        //             'isActive'  => 1,
        //             'hasRole' => 1
        //         ]);

        //         DB::table('formateur_internes')->insert([
        //             'idFormateur' => $user->id,
        //             'idEmploye' => $user->id,
        //             'idEntreprise' => Auth::user()->id,
        //         ]);

        //         DB::commit();

        //         return back()->with('successForm', 'Succès');
        //     }catch(Exception $e){
        //         DB::rollBack();
        //         return $e->getMessage();
        //     }
        // }else{
        //     return back()->with('errorForm', 'L\employé est déjas formateurs interne');
        // }
    }

    public function sendInvitation(Request $req)
    {
        // LIMITEUR PAR RAPPORT AU ABONNEMENT
        $authenticatedUser = Customer::idCustomer();
        $user = Customer::findOrFail($authenticatedUser);

        //Check raha efa nanao abonnement
        $sub = Subscription::where('subscriber_id', $authenticatedUser)->first();
        if (!$sub) {
            return response()->json(['error' => 'Vous devriez vous abonner']);
        }
        //Maka subscriptionSlug
        $subscriptionSlug = Subscription::where('subscriber_id', $authenticatedUser)->first()->slug;
        //Maka idPlan sy featureSlug
        $idplan = $user->planSubscriptions()->first()->plan_id;
        $featureSlug = Feature::where('plan_id', $idplan)->where('name', '{"fr":"Formateurs"}')->first()->slug;

        $subscription = $user->planSubscription($subscriptionSlug);
        $usage = $subscription->usage()->byFeatureSlug($featureSlug)->first();

        //Initialisation du premier usage 0
        if (!$usage) {
            $subscription->recordFeatureUsage($featureSlug, 0, false);
        }

        if (!$subscription->canUseFeature($featureSlug)) {
            return response()->json(['error' => 'Vous avez atteint le nombre maximum de formateurs autorisés.']);
        }
        // FIN LIMITEUR PAR RAPPORT AU ABONNEMENT

        $validate = Validator::make($req->all(), [
            'form_name' => 'required|min:2|max:200',
            'form_email' => 'required|email'

        ]);
        if ($validate->fails()) {
            return response()->json(['error' => $validate->messages()]);
        } else {
            $checkUser = DB::table('users')->select('id', 'email')->where('email', $req->form_email)->get();
            // $password = $this->randomPassword();
            $password = '0000@#';
            /** Logique envoie Mail */

            /***Fin envoie mail***/
            try {
                DB::beginTransaction();

                DB::table('users')->insert([
                    'name' => $req->form_name,
                    'firstName' => $req->form_first_name,
                    'email' => $req->form_email,
                    'password' => Hash::make($password),
                    'phone' => $req->form_phone
                ]);

                $user = DB::table('users')->select('id')->orderBy('id', 'desc')->first();
                $fonction = DB::table('fonctions')->select('idFonction')->where('idCustomer', $this->idEtp())->first();

                DB::table('forms')->insert([
                    'idFormateur' => $user->id,
                    'idTypeFormateur' => 1,
                    'idSexe' => 1
                ]);
                $emp = new Employe();
                $emp->idEmploye = $user->id;
                $emp->idSexe = 1;
                $emp->idNiveau = 6;
                $emp->idCustomer =  $this->idEtp(); //<------------
                //$emp->idFonction = $fonction->idFonction;
                $emp->idFonction = $fonction->idFonction;
                $emp->save();

                DB::table('formateur_internes')->insert([
                    'idFormateur'    => $user->id,
                    'idEmploye'      => $user->id,
                    'idEntreprise'   => $this->idEtp(),
                ]);
                RoleUser::create([
                    'role_id'  => 7,
                    'user_id'  => $user->id,
                    'isActive'  => 1,
                    'hasRole' => 1
                ]);
                DB::commit();
                $subscription->recordFeatureUsage($featureSlug);
                return response()->json(['success' => 'Formateur interne ajouté avec succès !']);
            } catch (Exception $e) {
                DB::rollBack();

                return response()->json(['error' => $e->getMessage()]);
            }
        }
    }

    public function updateImageForm(Request $req, $idFormateur)
    {
        // $validate = Validator::make($req->all(), [
        //     'photo' => 'required|image|mimes:png,jpg,webp,gif|max:6144'
        // ]);
        // if ($validate->fails()) {
        //     return back()->with('error', $validate->messages());
        // } else {

        $form = DB::table('users')->select('photo')->where('id', $idFormateur)->first();

        $driver = new Driver();

        $manager = new ImageManager($driver);

        if ($form != null) {
            if (!empty($module->module_image)) {
                Storage::disk('do')->delete('img/formateurs/' . $form->photo);
            }

            $folderPath = public_path('img/formateurs/');

            $image_parts = explode(";base64,", $req->image);
            $image_type_aux = explode("image/", $image_parts[0]);
            $image_type = $image_type_aux[1];
            $image_base64 = base64_decode($image_parts[1]);
            $image = $manager->read($image_base64)->toWebp(25);

            $imageName = uniqid() . '.webp';
            $filePath = 'img/formateurs/' . $imageName;

            Storage::disk('do')->put($filePath, $image, 'public');

            DB::table('users')->where('id', $idFormateur)->update([
                'photo' => $imageName,
            ]);
            return response()->json([
                'success' => 'Image Uploaded Successfully',
                'imageName' =>  $imageName
            ]);
        }
    }
    public function edit($idFormateur)
    {
        $formInterne = DB::table('v_formateur_internes')
            ->select('idEmploye', 'isActive AS form_is_active', 'initialNameForm AS form_initial_name', 'photoForm AS form_photo', 'name AS form_name', 'firstName AS form_firstname', 'email AS form_email', 'isActive AS user_is_active', 'form_phone', 'form_addr_lot', 'form_addr_qrt', 'form_addr_cp')
            ->where('idEmploye', $idFormateur)
            ->groupBy('idEmploye', 'initialNameForm', 'photoForm', 'name', 'firstName', 'email', 'isActive', 'form_phone', 'form_addr_lot', 'form_addr_qrt', 'form_addr_cp')
            ->first();

        return response()->json(['formInterne' => $formInterne]);
    }


    public function update(Request $req, $idFormateur)
    {
        $validate = Validator::make($req->all(), [
            'form_name' => 'required|min:2|max:200',
            'form_email' => 'required|email'
        ]);

        if ($validate->fails()) {
            return response()->json(['error' => $validate->messages()]);
        } else {
            try {
                DB::table('users')->where('id', $idFormateur)->update([
                    'name' => $req->form_name,
                    'firstName' => $req->form_firstname,
                    'email' => $req->form_email,
                    'phone' => $req->form_phone,
                    'user_addr_lot' => $req->form_lot,
                    'user_addr_quartier' => $req->form_qrt,
                    'user_addr_code_postal' => $req->form_cp
                ]);

                return response()->json(['success' => 'Modifié avec succès']);
            } catch (Exception $e) {
                return response()->json(['error' => 'Erreur inconnue !']);
            }
        }
    }
    /** A modifier */
    public function removeFormateur($id)
    {
        try {
            DB::beginTransaction();
            // DB::table('role_users')->select('user_id')->where('user_id', $idFormateur)->where('hasRole', 1)->where('isActive', 1)->where('role_id', 7)->update([
            //     'hasRole' => 0
            // ]);

            // DB::table('role_users')->select('user_id')->where('user_id', $idFormateur)->where('hasRole', 0)->where('role_id', 4)->update([
            //     'hasRole' => 1
            // ]);

            $assigned_project = DB::table('v_formateur_internes')->where('idEmploye', $id)->exists();
            if (!$assigned_project) {
                DB::table('formateur_internes')->select('idFormateur')->where('idFormateur', $id)->delete();
            }
            $status = !$assigned_project ? 'success' : 'erreur';
            $message = !$assigned_project ? 'Suppression de formateur avec succès' : 'Suppression impossible';
            // DB::table('forms')->select('idFormateur')->where('idFormateur', $idFormateur)->delete();

            DB::commit();
            return back()->with($status, $message);
        } catch (Exception $e) {
            DB::rollBack();
            return $e->getMessage();
        }
    }

    public function getAllForms()
    {
        $forms = DB::table('v_formateur_internes')
            ->select('idEmploye', 'photoForm AS form_photo', 'name AS form_name', 'firstName AS form_first_name', 'email AS form_email', 'initialNameForm AS form_initial_name')
            ->where('idEntreprise', $this->idEtp())
            ->groupBy('idEmploye', 'photoForm', 'name', 'firstName', 'email', 'initialNameForm')
            ->orderBy('name', 'asc')
            ->get();
        // dd($forms);
        return response()->json(['forms'  => $forms]);
    }
}
