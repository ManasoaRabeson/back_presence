<?php

namespace App\Http\Controllers;

use App\Imports\ExcelApprenants;
use App\Models\Customer;
use App\Models\Employe;
use App\Models\RoleUser;
use App\Models\User;
use App\Services\EmployeService;
use App\Services\EntrepriseService;
use App\Services\UserService;
use App\Traits\CheckQuery;
use App\Traits\GetQuery;
use App\Traits\StoreQuery;
use Exception;
use Google\Service\Monitoring\Custom;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Facades\Excel;

class EmployeController extends Controller
{
    use CheckQuery, StoreQuery, GetQuery;

    public function idEtp()
    {
        $customer = DB::select("SELECT employes.idEmploye, employes.idCustomer, customers.idTypeCustomer FROM employes INNER JOIN customers ON employes.idCustomer = customers.idCustomer WHERE idEmploye = ?", [Auth::user()->id]);
        return $customer[0]->idCustomer;
    }

    public function getIdEtp()
    {
        $idEntreprise = DB::table('entreprises')
            ->select('idCustomer')
            ->where('idCustomer', $this->idEtp())
            ->get();
        return $idEntreprise;
    }

    public function emps($roleId)
    {
        $countEmpActive = DB::table('v_employe_alls')
            ->select('idEmploye', 'idCustomer', 'role_id', 'matricule', 'name', 'firstName', 'phoneEmp', 'mailEmp', 'cin', 'adress', 'sexe', 'fonction', 'photoEmp', 'idSexe', 'isActive', 'hasRole')
            ->where('idCustomer', $this->idEtp())
            ->where('isActive', 1)
            ->where('hasRole', 1)
            ->where('role_id', $roleId)
            ->count('idEmploye');

        return $countEmpActive;
    }

    public function index()
    {
        $checkEtpGrp = DB::table('etp_groupes')->where('idEntreprise', $this->idEtp())->exists();

        if ($checkEtpGrp) {
            $empGrps = DB::table('v_union_emp_grps')
                ->select('idEmploye', 'idEntreprise', 'idEntrepriseParent', 'etp_name_parent', 'etp_name', 'emp_initial_name', 'emp_name', 'emp_firstname', 'emp_email', 'emp_matricule', 'emp_phone', 'emp_photo', 'emp_cin', 'emp_sexe', 'emp_is_active', 'emp_has_role', 'user_is_in_service')
                ->where('idEntrepriseParent', Customer::idCustomer())
                ->orderBy('emp_name', 'asc')
                ->paginate(12);
            //->get();

            $countEmpls = DB::table('v_union_emp_grps')
                ->select('idEmploye')
                ->where('idEntrepriseParent', Customer::idCustomer())
                ->count();

            return view('ETP.employes.index', compact('empGrps', 'countEmpls', 'checkEtpGrp'));
        } else {
            $employes = DB::table('v_employe_alls')
                ->select('idEmploye', 'idCustomer as idEntreprise', 'role_id', 'customerName', 'matricule', 'initialName', 'name', 'firstName', 'phone', 'email', 'cin', 'adresse', 'sexe', 'fonction', 'photo', 'idSexe', 'isActive', 'hasRole', 'user_is_in_service')
                ->where(function ($query) {
                    $query->where('idCustomer', Customer::idCustomer())
                        ->where('role_id', 4);
                })
                ->orderBy('idEmploye', 'desc')
                ->paginate(12);


            $countEmpl = DB::table('v_employe_alls')
                ->select('idEmploye', 'idCustomer as idEntreprise', 'role_id', 'customerName', 'matricule', 'initialName', 'name', 'firstName', 'phone', 'email', 'cin', 'adresse', 'sexe', 'fonction', 'photo', 'idSexe', 'isActive', 'hasRole')
                ->where(function ($query) {
                    $query->where('idCustomer', Customer::idCustomer())
                        ->where('role_id', 4);
                })

                ->get();

            $countEmpls = count($countEmpl);
            //dd($countEmpls);
            return view('ETP.employes.index', compact('employes', 'countEmpls'));
        }
    }

    public function addEmp(Request $req, EntrepriseService $entreprise, UserService $usr, EmployeService $employe)
    {
        $etpType = $entreprise->getEnterpriseType(Customer::idCustomer());

        if (isset($etpType)) {
            if ($etpType->idTypeEtp == 2) {
                $validate = Validator::make($req->all(), [
                    'emp_matricule' => 'required|min:1|max:200',
                    'idEntrepriseGrp' => 'required|exists:customers,idCustomer',
                    'idEntreprise' => 'required|exists:customers,idCustomer',
                    'emp_name' => 'required|min:2|max:200',
                    'emp_email' => 'email|unique:users,email'
                ]);

                $idEtp = $req->idEntrepriseGrp;
            } else {
                $validate = Validator::make($req->all(), [
                    'emp_matricule' => 'required|min:1|max:200',
                    'idEntreprise' => 'required|exists:customers,idCustomer',
                    'emp_name' => 'required|min:2|max:200',
                    'emp_email' => 'email|unique:users,email'
                ]);

                $idEtp = $req->idEntreprise;
            }
        }

        if ($validate->fails()) {
            return response([
                'status' => 422,
                'message' => $validate->messages()
            ]);
        } 

        try {
            DB::transaction(function() use($req, $usr, $employe, $idEtp){
                $user = $usr->store($req->emp_matricule, $req->emp_name, $req->emp_firstname, $req->emp_email, $req->emp_phone, Hash::make('0000@#'));
                $employe->store($user->id, 6, $idEtp, 1, $this->getIdFonction($idEtp));
                $this->roleUser(4, $user->id, 1, 1, 1);
            });

            return response([
                'status' => 200,
                'message' => 'Employé ajoutée avec succès'
            ]);
        } catch (Exception $e) {
            return response([
                'status' => 411,
                'message' => 'Ajout impossible !'
            ]);
        }
    }


    public function searchName(string $name)
    {
        $checkEtpGrp = DB::table('etp_groupes')->where('idEntreprise', Customer::idCustomer())->exists();
        if ($checkEtpGrp) {
            $apprenants = DB::table('v_union_emp_grps') // v_list_emp_grps ?== v_union_emp_grps
                ->select('idEmploye', 'idEntreprise', 'idEntrepriseParent', 'etp_name_parent', 'etp_name', 'emp_initial_name', 'emp_name', 'emp_firstname', 'emp_email', 'emp_matricule', 'emp_phone', 'emp_photo', 'emp_cin', 'emp_sexe', 'emp_is_active', 'emp_has_role', 'user_is_in_service')
                ->where('idEntrepriseParent', Customer::idCustomer())
                ->where('role_id', '=', 4)
                ->where(function ($query) use ($name) {
                    $query->where('emp_name', 'LIKE', '%' . $name . '%')
                        ->orWhere('emp_firstname', 'LIKE', '%' . $name . '%');
                })
                // ->groupBy('idEmploye', 'emp_initial_name', 'emp_photo', 'emp_name', 'emp_firstname', 'emp_matricule', 'emp_email', 'etp_name', 'emp_phone', 'emp_is_active', 'user_is_in_service')
                ->get();
        } else {
            $apprenants = DB::table('v_employe_alls')
                ->select('idEmploye', 'customerName AS etp_name', 'initialName AS emp_initial_name', 'name AS emp_name', 'firstName AS emp_firstname', 'email AS emp_email', 'matricule AS emp_matricule', 'phone AS emp_phone', 'photo AS emp_photo', 'cin AS emp_cin', 'sexe AS emp_sexe', 'isActive AS emp_is_active', 'hasRole AS emp_has_role', 'user_is_in_service')
                ->where(function ($query) {
                    $query->where('idCustomer', Customer::idCustomer())
                        ->where('role_id', 4);
                })
                ->where(function ($query) use ($name) {
                    $query->where('name', 'LIKE', '%' . $name . '%')
                        ->orWhere('firstname', 'LIKE', '%' . $name . '%');
                })
                // ->groupBy('idEmploye', 'emp_initial_name', 'emp_photo', 'emp_name', 'emp_firstname', 'emp_matricule', 'emp_email', 'etp_name', 'emp_phone', 'emp_is_active', 'user_is_in_service')
                ->get();
        }
        return response()->json(['apprenants' => $apprenants]);
    }


    public function getEmpFiltered($idEtp)
    {
        $checkEtpGrp = DB::table('etp_groupes')->where('idEntreprise', $this->idEtp())->exists();
        if ($checkEtpGrp) {
            $apprenants = DB::table('v_apprenant_etp_all_groups')
                ->select('idEmploye', 'emp_initial_name', 'emp_photo', 'emp_name', 'emp_firstname', 'emp_matricule', 'emp_email', 'etp_name', 'emp_phone', 'emp_is_active', 'user_is_in_service')
                ->where('idEntrepriseParent', $this->idEtp())
                ->where('role_id', '=', 4)
                ->groupBy('idEmploye', 'emp_initial_name', 'emp_photo', 'emp_name', 'emp_firstname', 'emp_matricule', 'emp_email', 'etp_name', 'emp_phone', 'emp_is_active', 'user_is_in_service')
                ->get();
        } else {
            $apprenants = DB::table('v_apprenant_etp_alls')
                ->select('idEmploye', 'emp_initial_name', 'emp_photo', 'emp_name', 'emp_firstname', 'emp_matricule', 'emp_email', 'etp_name', 'emp_phone', 'emp_is_active', 'user_is_in_service')
                ->where('idEtp', $this->idEtp())
                ->where('role_id', 4)
                // ->where('idEtp', $idEtp)
                ->groupBy('idEmploye', 'emp_initial_name', 'emp_photo', 'emp_name', 'emp_firstname', 'emp_matricule', 'emp_email', 'etp_name', 'emp_phone', 'emp_is_active', 'user_is_in_service')
                ->get();
        }
        return response()->json(['apprenants' => $apprenants]);
    }

    public function update(Request $req, $idEmploye)
    {
        $validate = Validator::make($req->all(), [
            'idEntreprise' => 'required',
            // 'emp_matricule' => 'required|min:2|max:100',
            'emp_name' => 'required|min:2|max:100',
            'idVille' => 'required|exists:villes,idVille',
            //'emp_fonction' => 'required|min:2|max:200'
        ]);

        if ($validate->fails()) {
            return response()->json(['error' => $validate->messages()]);
        } else {
            try {
                DB::beginTransaction();

                $check = DB::table('fonctions')
                    ->select('fonction')
                    //->where('fonction', 'like', $req->emp_fonction)
                    ->where('idCustomer', $this->idEtp())
                    ->count();

                if ($check == 0) {
                    $idFonction = DB::table('fonctions')->insertGetId([
                        'fonction' => $req->emp_fonction,
                        'idCustomer' => $this->idEtp()
                    ]);

                    DB::table('users')->where('id', $idEmploye)->update([
                        'name' => $req->emp_name,
                        'firstName' => $req->emp_firstname,
                        'matricule' => $req->emp_matricule,
                        'email' => $req->emp_email,
                        'phone' => $req->emp_phone,
                        'idFonction' => $idFonction,
                        //'fonction' => $req->emp_fonction,
                        'user_addr_lot' => $req->emp_lot,
                        'user_addr_quartier' => $req->emp_qrt,
                        'user_addr_code_postal' => $req->emp_cp,
                        'idVille' => $req->idVille
                    ]);
                } else {
                    DB::table('users')->where('id', $idEmploye)->update([
                        'name' => $req->emp_name,
                        'firstName' => $req->emp_firstname,
                        'matricule' => $req->emp_matricule,
                        'email' => $req->emp_email,
                        'phone' => $req->emp_phone,
                        //'fonction' => $req->emp_fonction,
                        'user_addr_lot' => $req->emp_lot,
                        'user_addr_quartier' => $req->emp_qrt,
                        'user_addr_code_postal' => $req->emp_cp,
                        'idVille' => $req->idVille
                    ]);
                }

                Db::table('employes')->where('idEmploye', $idEmploye)->update([
                    'idCustomer' => $req->idEntreprise
                ]);

                DB::commit();
                return response()->json(['success' => 'Succès']);
            } catch (Exception $e) {
                DB::rollBack();
                return response()->json(['error' => 'Erreur inconnue !']);
                //return response()->json(['error' => $e->getMessage()]);
            }
        }
    }

    public function updateImageEmpl(Request $req, $idEmploye)
    {
        // $validate = Validator::make($req->all(), [
        //     'photo' => 'required|image|mimes:png,jpg,webp,gif|max:6144'
        // ]);
        // if ($validate->fails()) {
        //     return back()->with('error', $validate->messages());
        // } else {

        $empl = DB::table('users')->select('photo')->where('id', $idEmploye)->first();

        if ($empl != null) {
            $folder = 'img/employes/' . $empl->photo;

            if (File::exists($folder)) {
                File::delete($folder);
            }

            $folderPath = public_path('img/employes/');

            $image_parts = explode(";base64,", $req->image);
            $image_type_aux = explode("image/", $image_parts[0]);
            $image_type = $image_type_aux[1];
            $image_base64 = base64_decode($image_parts[1]);

            $imageName = uniqid() . '.webp';
            $imageFullPath = $folderPath . $imageName;

            file_put_contents($imageFullPath, $image_base64);

            DB::table('users')->where('id', $idEmploye)->update([
                'photo' => $imageName,
            ]);
            return response()->json([
                'success' => 'Image Uploaded Successfully',
                'imageName' =>  $imageName
            ]);
        }
    }










    public function store(Request $req)
    {
        $req->validate([
            'matricule' => 'required|max:90',
            'name' => 'required|max:240',
            'firstName' => 'required|max:240',
            'cin' => 'required|min:12|max:12|unique:users,cin',
            'mail' => 'required|max:150',
            'phoneEmp' => 'required',
            'adresse' => 'required|max:200',
            'idFonction' => 'required',
            'idSexe' => 'required',
            'photoEmp' => 'required | image | mimes: jpeg,jpg,png,gif,webp|max:1024'
        ]);

        $etp = DB::table('customers')
            ->select('idCustomer')
            ->where('idCustomer', $this->idEtp())
            ->first();

        $niveauEtude = DB::table('niveau_etudes')
            ->select('idNiveau')
            ->where('idNiveau', '=', 6)
            ->first();

        try {
            DB::beginTransaction();

            $user = new User();
            $user->name = $req->name;
            $user->email = $req->mail;
            $password = Hash::make('0000@#');
            $user->password = $password;
            $user->matricule = $req->matricule;
            $user->firstName = $req->firstName;
            $user->cin = $req->cin;
            $user->phone = $req->phoneEmp;
            $user->adresse = $req->adresse;
            if ($req->hasFile('photoEmp')) {
                $file = $req->file('photoEmp');
                $extension = $file->getClientOriginalExtension();
                $fileName = time() . '.' . $extension;
                $file->move('img/entreprises', $fileName);
                $user->photo = $fileName;
            }
            $user->save();

            $emp = new Employe();
            $emp->idEmploye = $user->id;
            $emp->idSexe = $req->idSexe;
            $emp->idNiveau = $niveauEtude->idNiveau;
            $emp->idFonction = $req->idFonction;
            $emp->idCustomer = $etp->idCustomer;
            $emp->save();

            RoleUser::create([
                'role_id'  => 4,
                'user_id'  => $user->id,
                'isActive' => 0,
                'hasRole' => 1
            ]);

            DB::commit();

            return back()->with('success', 'Succès');
        } catch (Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Erreur inconnue');
        }
    }

    public function edit($idEmploye)
    {
        $checkEtpGrp = DB::table('etp_groupes')->where('idEntreprise', $this->idEtp())->exists();
        if ($checkEtpGrp) {
            $emp = DB::table('v_union_emp_grps')
                //  ->select('idEmploye', 'idCustomer', 'role_id', 'emp_matricule', 'customer_initial_name', 'emp_name', 'emp_firstname', 'emp_photo', 'emp_phone', 'emp_email', 'emp_fonction', 'emp_cin', 'emp_is_active', 'emp_has_role')
                // ->where('idCustomer', $this->idEtp())
                ->select('*')
                ->where('idEmploye', $idEmploye)
                ->first();
        } else {
            $emp = DB::table('v_employe_alls')
                ->select('idEmploye', 'idCustomer', 'role_id', 'matricule AS emp_matricule', 'name AS emp_name', 'firstName AS emp_firstname', 'photo AS emp_photo', 'phone AS emp_phone', 'email AS emp_email', 'fonction AS emp_fonction', 'cin AS emp_cin', 'isActive AS emp_is_active', 'hasRole AS emp_has_role')
                ->where('idCustomer', $this->idEtp())
                //->select('*')
                ->where('idEmploye', $idEmploye)
                ->first();
        }
        $villes = DB::table('villes')
            ->select('idVille', 'ville')
            ->orderBy('ville', 'asc')
            ->get();

        return response()->json([
            'emp' => $emp,
            'villes' => $villes,
        ]);
    }

    public function activateEmp(Request $req, $idEmploye)
    {
        $abn = DB::table('v_abonnement_etps')
            ->select('idAbn', 'idCustomer', 'nbEmploye', 'isActive', 'isInfinity')
            ->where('idCustomer', $this->idEtp())
            ->where('isActive', 1)
            ->first();

        $checkEmp = DB::table('employes')
            ->join('users', 'users.id', 'employes.idEmploye')
            ->join('role_users', 'role_users.user_id', 'users.id')
            ->where('employes.idCustomer', $this->idEtp())
            ->where('role_users.isActive', 1)
            ->count('employes.idEmploye');

        if ($abn->isInfinity == 1) {
            DB::table('employes')
                ->join('users', 'users.id', 'employes.idEmploye')
                ->join('role_users', 'role_users.user_id', 'users.id')
                ->where('idEmploye', $idEmploye)
                ->update([
                    'role_users.isActive' => 1
                ]);

            return back();
        } elseif ($abn->idAbn == $req->idAbn && $abn->nbEmploye == $req->nbEmploye) {
            if ($checkEmp <= $req->nbEmploye) {
                DB::table('employes')
                    ->join('users', 'users.id', 'employes.idEmploye')
                    ->join('role_users', 'role_users.user_id', 'users.id')
                    ->where('idEmploye', $idEmploye)
                    ->update([
                        'role_users.isActive' => 1
                    ]);

                return back();
            } else {
                return back()->with('errorAbn', 'Vous avez atteint le nombre maximale d\'employes, Veuillez mettre à niveau votre abonnement !');
            }
        }
    }

    public function disableEmp($idEmploye)
    {
        DB::table('employes')
            ->join('users', 'users.id', 'employes.idEmploye')
            ->join('role_users', 'role_users.user_id', 'users.id')
            ->where('idEmploye', $idEmploye)
            ->update([
                'role_users.isActive' => 0
            ]);

        return back();
    }

    public function editPhoto($empId)
    {
        $emp = Employe::where('idEmploye', $empId)->firstOrFail();

        return view('ETP.employes.editPhoto', compact('emp'));
    }

    public function updatePhoto(Request $req, $empId)
    {
        $req->validate([
            'photoEmp' => 'required | image | mimes: jpeg,jpg,png,gif,webp|max:1024'
        ]);

        $emp = Employe::where('idEmploye', '=', $empId)->firstOrFail();

        try {
            if ($req->hasFile('photoEmp')) {
                $destination = 'img/' . $emp->photoEmp;
                if (File::exists($destination)) {
                    File::delete($destination);
                }

                $file = $req->file('photoEmp');
                $extension = $file->getClientOriginalExtension();
                $fileName = time() . '.' . $extension;
                $file->move('img/entreprises', $fileName);
            }


            DB::table('employes')->where('idEmploye', '=', $empId)->update([
                'photoEmp' => $fileName
            ]);

            return redirect('employes')->with('successMod', 'Modification avec succès');
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }

    // Filtres

    public function getDropdownItem()
    {
        $checkEtpGrp = DB::table('etp_groupes')->where('idEntreprise', $this->idEtp())->exists();
        // $etps = DB::table('v_apprenant_etp_all_filters')
        //     ->select('idEtp', 'etp_name', DB::raw('COUNT(idEmploye) AS emp_nb'))
        //     ->where('idEtp', $this->idEtp())
        //     ->orderBy('etp_name', 'asc')
        //     ->groupBy('idEtp', 'etp_name')
        //     ->get();

        // $fonctions = DB::table('v_apprenant_etp_all_filters')
        //     ->select('idFonction', 'emp_fonction', DB::raw('COUNT(idEmploye) AS emp_nb'))
        //     ->where('idEtp', $this->idEtp())
        //     ->orderBy('emp_fonction', 'asc')
        //     ->groupBy('idFonction', 'emp_fonction')
        //     ->get();
        // dd($fonctions);
        if ($checkEtpGrp) {
            $villes = DB::table('v_periode_groups')
                ->select('project_id_ville', 'project_ville as ville', DB::raw('COUNT(idEmploye) AS emp_nb'))
                ->where('idEntrepriseParent', $this->idEtp())
                ->orderBy('project_ville', 'asc')
                ->groupBy('project_id_ville', 'project_ville')
                ->get();

            $status = DB::table('v_apprenant_etp_all_groups')
                ->select('project_status', DB::raw('COUNT(idEmploye) AS emp_nb'))
                ->where('idEntrepriseParent', $this->idEtp())
                ->where('project_status', '!=', 'null')
                ->orderBy('project_status', 'asc')
                ->groupBy('project_status')
                ->get();

            $modalites = DB::table('v_apprenant_etp_all_groups')
                ->select('project_modality', DB::raw('COUNT(idEmploye) AS emp_nb'))
                ->where('idEntrepriseParent', $this->idEtp())
                ->where('project_modality', '!=', 'null')
                ->orderBy('project_modality', 'asc')
                ->groupBy('project_modality')
                ->get();

            $modules = DB::table('v_apprenant_etp_all_groups')
                ->select('idModule', 'module_name', DB::raw('COUNT(idEmploye) AS emp_nb'))
                ->where('idEntrepriseParent', $this->idEtp())
                ->where('idModule', '!=', 'null')
                ->orderBy('module_name', 'asc')
                ->groupBy('idModule', 'module_name')
                ->get();

            $periodePrev3 = DB::table('v_apprenant_etp_all_groups')
                ->select('idProjet', 'p_id_periode', DB::raw('COUNT(idEmploye) AS emp_nb'))
                ->where('idEntrepriseParent', $this->idEtp())
                //->where('id_cfp', $this->idEtp())
                ->where('dateDebut', '!=', 'null')
                ->where('p_id_periode', "prev_3_month")
                ->groupBy('p_id_periode')
                ->first();

            $periodePrev6 = DB::table('v_apprenant_etp_all_groups')
                ->select('idProjet', 'p_id_periode', DB::raw('COUNT(idEmploye) AS emp_nb'))
                ->where('idEntrepriseParent', $this->idEtp())
                //->where('id_cfp', $this->idEtp())
                ->where('dateDebut', '!=', 'null')
                ->where('p_id_periode', "prev_6_month")
                ->groupBy('p_id_periode')
                ->first();

            $periodePrev12 = DB::table('v_apprenant_etp_all_groups')
                ->select('idProjet', 'p_id_periode', DB::raw('COUNT(idEmploye) AS emp_nb'))
                ->where('idEntrepriseParent', $this->idEtp())
                //->where('id_cfp', $this->idEtp())
                ->where('dateDebut', '!=', 'null')
                ->where('p_id_periode', "prev_12_month")
                ->groupBy('p_id_periode')
                ->first();

            $periodeNext3 = DB::table('v_apprenant_etp_all_groups')
                ->select('p_id_periode', DB::raw('COUNT(idEmploye) AS emp_nb'))
                ->where('idEntrepriseParent', $this->idEtp())
                //->where('id_cfp', $this->idEtp())
                ->where('dateDebut', '!=', 'null')
                ->where('p_id_periode', "next_3_month")
                ->groupBy('p_id_periode')
                ->first();

            $periodeNext6 = DB::table('v_periode_groups')
                ->select('p_id_periode', DB::raw('COUNT(idEmploye) AS emp_nb'))
                ->where('idEntrepriseParent', $this->idEtp())
                ->where('p_id_periode', "next_6_month")
                ->groupBy('p_id_periode')
                ->first();

            $periodeNext12 = DB::table('v_apprenant_etp_all_groups')
                ->select('p_id_periode', DB::raw('COUNT(idEmploye) AS emp_nb'))
                ->where('idEntrepriseParent', $this->idEtp())
                //->where('id_cfp', $this->idEtp())
                ->where('dateDebut', '!=', 'null')
                ->where('p_id_periode', "next_12_month")
                ->groupBy('p_id_periode')
                ->first();

            // dd($modules);

        } else { //ETPS FILLES...
            $villes = DB::table('v_periodes')
                ->select('project_id_ville', 'project_ville as ville', DB::raw('COUNT(idEmploye) AS emp_nb'))
                ->where('idEtp', $this->idEtp())
                ->orderBy('project_ville', 'asc')
                ->groupBy('project_id_ville', 'project_ville')
                ->get();

            $status = DB::table('v_apprenant_etp_alls')
                ->select('project_status', DB::raw('COUNT(idEmploye) AS emp_nb'))
                ->where('idEtp', $this->idEtp())
                ->where('project_status', '!=', 'null')
                ->orderBy('project_status', 'asc')
                ->groupBy('project_status')
                ->get();

            $modalites = DB::table('v_apprenant_etp_alls')
                ->select('project_modality', DB::raw('COUNT(idEmploye) AS emp_nb'))
                ->where('idEtp', $this->idEtp())
                ->where('project_modality', '!=', 'null')
                ->orderBy('project_modality', 'asc')
                ->groupBy('project_modality')
                ->get();

            $modules = DB::table('v_apprenant_etp_alls')
                ->select('idModule', 'module_name', DB::raw('COUNT(idEmploye) AS emp_nb'))
                ->where('idEtp', $this->idEtp())
                ->where('idModule', '!=', 'null')
                ->orderBy('module_name', 'asc')
                ->groupBy('idModule', 'module_name')
                ->get();

            $periodePrev3 = DB::table('v_apprenant_etp_alls')
                ->select('idProjet', 'p_id_periode', DB::raw('COUNT(idEmploye) AS emp_nb'))
                ->where('idEtp', $this->idEtp())
                ->where('id_cfp', $this->idEtp())
                ->where('dateDebut', '!=', 'null')
                ->where('p_id_periode', "prev_3_month")
                ->groupBy('p_id_periode')
                ->first();

            $periodePrev6 = DB::table('v_apprenant_etp_alls')
                ->select('idProjet', 'p_id_periode', DB::raw('COUNT(idEmploye) AS emp_nb'))
                ->where('idEtp', $this->idEtp())
                ->where('id_cfp', $this->idEtp())
                ->where('dateDebut', '!=', 'null')
                ->where('p_id_periode', "prev_6_month")
                ->groupBy('p_id_periode')
                ->first();

            $periodePrev12 = DB::table('v_apprenant_etp_alls')
                ->select('idProjet', 'p_id_periode', DB::raw('COUNT(idEmploye) AS emp_nb'))
                ->where('idEtp', $this->idEtp())
                ->where('id_cfp', $this->idEtp())
                ->where('dateDebut', '!=', 'null')
                ->where('p_id_periode', "prev_12_month")
                ->groupBy('p_id_periode')
                ->first();

            $periodeNext3 = DB::table('v_apprenant_etp_alls')
                ->select('p_id_periode', DB::raw('COUNT(idEmploye) AS emp_nb'))
                ->where('idEtp', $this->idEtp())
                ->where('id_cfp', $this->idEtp())
                ->where('dateDebut', '!=', 'null')
                ->where('p_id_periode', "next_3_month")
                ->groupBy('p_id_periode')
                ->first();

            $periodeNext6 = DB::table('v_periodes')
                ->select('p_id_periode', DB::raw('COUNT(idEmploye) AS emp_nb'))
                ->where('idEtp', $this->idEtp())
                ->where('p_id_periode', "next_6_month")
                ->groupBy('p_id_periode')
                ->first();

            $periodeNext12 = DB::table('v_apprenant_etp_alls')
                ->select('p_id_periode', DB::raw('COUNT(idEmploye) AS emp_nb'))
                ->where('idEtp', $this->idEtp())
                ->where('id_cfp', $this->idEtp())
                ->where('dateDebut', '!=', 'null')
                ->where('p_id_periode', "next_12_month")
                ->groupBy('p_id_periode')
                ->first();
        }
        return response()->json([
            //'etps' => $etps,
            //'fonctions' => $fonctions,
            'villes' => $villes,
            'status' => $status,
            'modalites' => $modalites,
            'modules' => $modules,
            'periodePrev3' => $periodePrev3,
            'periodePrev6' => $periodePrev6,
            'periodePrev12' => $periodePrev12,
            'periodeNext3' => $periodeNext3,
            'periodeNext6' => $periodeNext6,
            'periodeNext12' => $periodeNext12
        ]);
    }

    public function filterItems(Request $req)
    {
        $idEtps = explode(',', $req->idEtp);
        $idFonctions = explode(',', $req->idFonction);
        $idModules = explode(',', $req->idModule);
        $idStatus = explode(',', $req->idStatut);
        $idModalites = explode(',', $req->idModalite);
        $idVilles = explode(',', $req->idVille);
        $idPeriodes = $req->idPeriode;

        $checkEtpGrp = DB::table('etp_groupes')->where('idEntreprise', $this->idEtp())->exists();

        if ($checkEtpGrp) {

            $query = DB::table('v_apprenant_etp_all_groups')
                ->select('idEmploye', 'emp_name', 'emp_firstname', 'emp_initial_name', 'emp_photo', 'emp_matricule', 'emp_phone', 'emp_email', 'emp_fonction', 'idEtp', 'etp_name')
                ->where('idEntrepriseParent', $this->idEtp());

            if ($idEtps[0] != null) {
                $query->whereIn('idEtp', $idEtps);

                if ($idFonctions[0] != null) {
                    $query->whereIn('idFonction', $idFonctions);
                }

                $fonctions = DB::table('v_periode_groups')
                    ->select('idFonction', 'emp_fonction', DB::raw('COUNT(idEmploye) AS emp_nb'))
                    ->where('idEntrepriseParent', $this->idEtp())
                    ->whereIn('idEtp', $idEtps)
                    ->orderBy('emp_fonction', 'asc')
                    ->groupBy('idFonction', 'emp_fonction')
                    ->get();

                $villes = DB::table('v_periode_groups')
                    ->select('project_id_ville', 'project_ville as ville', DB::raw('COUNT(idEmploye) AS emp_nb'))
                    ->where('idEntrepriseParent', $this->idEtp())
                    ->whereIn('idEtp', $idEtps)
                    ->orderBy('project_ville', 'asc')
                    ->groupBy('project_id_ville', 'project_ville')
                    ->get();

                $status = DB::table('v_apprenant_etp_all_groups')
                    ->select('project_status', DB::raw('COUNT(idEmploye) AS emp_nb'))
                    ->where('idEntrepriseParent', $this->idEtp())
                    ->where('project_status', '!=', 'null')
                    ->whereIn('idEtp', $idEtps)
                    ->orderBy('project_status', 'asc')
                    ->groupBy('project_status')
                    ->get();

                $modalites = DB::table('v_apprenant_etp_all_groups')
                    ->select('project_modality', DB::raw('COUNT(idEmploye) AS emp_nb'))
                    ->where('idEntrepriseParent', $this->idEtp())
                    ->where('project_modality', '!=', 'null')
                    ->whereIn('idEtp', $idEtps)
                    ->orderBy('project_modality', 'asc')
                    ->groupBy('project_modality')
                    ->get();

                $modules = DB::table('v_apprenant_etp_alls')
                    ->select('idModule', 'module_name', DB::raw('COUNT(idEmploye) AS emp_nb'))
                    ->where('idEntrepriseParent', $this->idEtp())
                    ->where('idModule', '!=', 'null')
                    ->whereIn('idEtp', $idEtps)
                    ->orderBy('module_name', 'asc')
                    ->groupBy('idModule', 'module_name')
                    ->get();

                $periodePrev3 = DB::table('v_apprenant_etp_all_groups')
                    ->select('idProjet', 'p_id_periode', DB::raw('COUNT(idEmploye) AS emp_nb'))
                    ->where('idEntrepriseParent', $this->idEtp())
                    ->where('id_cfp', $this->idEtp())
                    ->where('dateDebut', '!=', 'null')
                    ->where('p_id_periode', "prev_3_month")
                    ->whereIn('idEtp', $idEtps)
                    ->groupBy('p_id_periode')
                    ->first();

                $periodePrev6 = DB::table('v_apprenant_etp_all_groups')
                    ->select('idProjet', 'p_id_periode', DB::raw('COUNT(idEmploye) AS emp_nb'))
                    ->where('idEntrepriseParent', $this->idEtp())
                    ->where('id_cfp', $this->idEtp())
                    ->where('dateDebut', '!=', 'null')
                    ->where('p_id_periode', "prev_6_month")
                    ->whereIn('idEtp', $idEtps)
                    ->groupBy('p_id_periode')
                    ->first();

                $periodePrev12 = DB::table('v_apprenant_etp_all_groups')
                    ->select('idProjet', 'p_id_periode', DB::raw('COUNT(idEmploye) AS emp_nb'))
                    ->where('idEntrepriseParent', $this->idEtp())
                    ->where('id_cfp', $this->idEtp())
                    ->where('dateDebut', '!=', 'null')
                    ->where('p_id_periode', "prev_12_month")
                    ->whereIn('idEtp', $idEtps)
                    ->groupBy('p_id_periode')
                    ->first();

                $periodeNext3 = DB::table('v_apprenant_etp_all_groups')
                    ->select('idProjet', 'p_id_periode', DB::raw('COUNT(idEmploye) AS emp_nb'))
                    ->where('idEntrepriseParent', $this->idEtp())
                    ->where('id_cfp', $this->idEtp())
                    ->where('dateDebut', '!=', 'null')
                    ->where('p_id_periode', "next_3_month")
                    ->whereIn('idEtp', $idEtps)
                    ->groupBy('p_id_periode')
                    ->first();

                $periodeNext6 = DB::table('v_apprenant_etp_all_groups')
                    ->select('idProjet', 'p_id_periode', DB::raw('COUNT(idEmploye) AS emp_nb'))
                    ->where('idEntrepriseParent', $this->idEtp())
                    ->where('id_cfp', $this->idEtp())
                    ->where('dateDebut', '!=', 'null')
                    ->where('p_id_periode', "next_6_month")
                    ->whereIn('idEtp', $idEtps)
                    ->groupBy('p_id_periode')
                    ->first();

                $periodeNext12 = DB::table('v_apprenant_etp_all_groups')
                    ->select('idProjet', 'p_id_periode', DB::raw('COUNT(idEmploye) AS emp_nb'))
                    ->where('idEntrepriseParent', $this->idEtp())
                    ->where('id_cfp', $this->idEtp())
                    ->where('dateDebut', '!=', 'null')
                    ->where('p_id_periode', "next_12_month")
                    ->whereIn('idEtp', $idEtps)
                    ->groupBy('p_id_periode')
                    ->first();
            } elseif ($idFonctions[0] != null) {
                $query->whereIn('idFonction', $idFonctions);

                $etps = DB::table('v_periode_groups')
                    ->select('idEtp', 'etp_name', DB::raw('COUNT(idEmploye) AS emp_nb'))
                    ->where('idEntrepriseParent', $this->idEtp())
                    ->whereIn('idFonction', $idFonctions)
                    ->orderBy('etp_name', 'asc')
                    ->groupBy('idEtp', 'etp_name')
                    ->get();

                $villes = DB::table('v_periode_groups')
                    ->select('project_id_ville', 'project_ville as ville', DB::raw('COUNT(idEmploye) AS emp_nb'))
                    ->where('idEntrepriseParent', $this->idEtp())
                    ->whereIn('idFonction', $idFonctions)
                    ->orderBy('project_ville', 'asc')
                    ->groupBy('project_id_ville', 'project_ville')
                    ->get();

                $status = DB::table('v_apprenant_etp_all_groups')
                    ->select('project_status', DB::raw('COUNT(idEmploye) AS emp_nb'))
                    ->where('idEntrepriseParent', $this->idEtp())
                    ->where('project_status', '!=', 'null')
                    ->whereIn('idFonction', $idFonctions)
                    ->orderBy('project_status', 'asc')
                    ->groupBy('project_status')
                    ->get();

                $modalites = DB::table('v_apprenant_etp_all_groups')
                    ->select('project_modality', DB::raw('COUNT(idEmploye) AS emp_nb'))
                    ->where('idEntrepriseParent', $this->idEtp())
                    ->where('project_modality', '!=', 'null')
                    ->whereIn('idFonction', $idFonctions)
                    ->orderBy('project_modality', 'asc')
                    ->groupBy('project_modality')
                    ->get();

                $modules = DB::table('v_apprenant_etp_all_groups')
                    ->select('idModule', 'module_name', DB::raw('COUNT(idEmploye) AS emp_nb'))
                    ->where('idEntrepriseParent', $this->idEtp())
                    ->where('idModule', '!=', 'null')
                    ->whereIn('idFonction', $idFonctions)
                    ->orderBy('module_name', 'asc')
                    ->groupBy('idModule', 'module_name')
                    ->get();

                $periodePrev3 = DB::table('v_apprenant_etp_all_groups')
                    ->select('idProjet', 'p_id_periode', DB::raw('COUNT(idEmploye) AS emp_nb'))
                    ->where('idEntrepriseParent', $this->idEtp())
                    //->where('id_cfp', $this->idEtp())
                    ->where('dateDebut', '!=', 'null')
                    ->where('p_id_periode', "prev_3_month")
                    ->whereIn('idFonction', $idFonctions)
                    ->groupBy('p_id_periode')
                    ->first();

                $periodePrev6 = DB::table('v_apprenant_etp_all_groups')
                    ->select('idProjet', 'p_id_periode', DB::raw('COUNT(idEmploye) AS emp_nb'))
                    ->where('idEntrepriseParent', $this->idEtp())
                    //->where('id_cfp', $this->idEtp())
                    ->where('dateDebut', '!=', 'null')
                    ->where('p_id_periode', "prev_6_month")
                    ->whereIn('idFonction', $idFonctions)
                    ->groupBy('p_id_periode')
                    ->first();

                $periodePrev12 = DB::table('v_apprenant_etp_all_groups')
                    ->select('idProjet', 'p_id_periode', DB::raw('COUNT(idEmploye) AS emp_nb'))
                    ->where('idEntrepriseParent', $this->idEtp())
                    //->where('id_cfp', $this->idEtp())
                    ->where('dateDebut', '!=', 'null')
                    ->where('p_id_periode', "prev_12_month")
                    ->whereIn('idFonction', $idFonctions)
                    ->groupBy('p_id_periode')
                    ->first();

                $periodeNext3 = DB::table('v_apprenant_etp_all_groups')
                    ->select('idProjet', 'p_id_periode', DB::raw('COUNT(idEmploye) AS emp_nb'))
                    ->where('idEntrepriseParent', $this->idEtp())
                    //->where('id_cfp', $this->idEtp())
                    ->where('dateDebut', '!=', 'null')
                    ->where('p_id_periode', "next_3_month")
                    ->whereIn('idFonction', $idFonctions)
                    ->groupBy('p_id_periode')
                    ->first();

                $periodeNext6 = DB::table('v_apprenant_etp_all_groups')
                    ->select('idProjet', 'p_id_periode', DB::raw('COUNT(idEmploye) AS emp_nb'))
                    ->where('idEntrepriseParent', $this->idEtp())
                    //->where('id_cfp', $this->idEtp())
                    ->where('dateDebut', '!=', 'null')
                    ->where('p_id_periode', "next_6_month")
                    ->whereIn('idFonction', $idFonctions)
                    ->groupBy('p_id_periode')
                    ->first();

                $periodeNext12 = DB::table('v_apprenant_etp_all_groups')
                    ->select('idProjet', 'p_id_periode', DB::raw('COUNT(idEmploye) AS emp_nb'))
                    ->where('idEntrepriseParent', $this->idEtp())
                    //->where('id_cfp', $this->idEtp())
                    ->where('dateDebut', '!=', 'null')
                    ->where('p_id_periode', "next_12_month")
                    ->whereIn('idFonction', $idFonctions)
                    ->groupBy('p_id_periode')
                    ->first();
            } elseif ($idModules[0] != null) {
                $query->whereIn('idModule', $idModules);

                $etps = DB::table('v_apprenant_etp_all_groups')
                    ->select('idEtp', 'etp_name', DB::raw('COUNT(idEmploye) AS emp_nb'))
                    ->where('idEntrepriseParent', $this->idEtp())
                    ->whereIn('idModule', $idModules)
                    ->orderBy('etp_name', 'asc')
                    ->groupBy('idEtp', 'etp_name')
                    ->get();

                $fonctions = DB::table('v_apprenant_etp_all_groups')
                    ->select('idFonction', 'emp_fonction', DB::raw('COUNT(idEmploye) AS emp_nb'))
                    ->where('idEntrepriseParent', $this->idEtp())
                    ->whereIn('idModule', $idModules)
                    ->orderBy('emp_fonction', 'asc')
                    ->groupBy('idFonction', 'emp_fonction')
                    ->get();

                $villes = DB::table('v_apprenant_etp_all_groups')
                    ->select('project_id_ville', 'project_ville as ville', DB::raw('COUNT(idEmploye) AS emp_nb'))
                    ->where('idEntrepriseParent', $this->idEtp())
                    ->whereIn('idModule', $idModules)
                    ->orderBy('project_ville', 'asc')
                    ->groupBy('project_id_ville', 'project_ville')
                    ->get();

                $status = DB::table('v_apprenant_etp_all_groups')
                    ->select('project_status', DB::raw('COUNT(idEmploye) AS emp_nb'))
                    ->where('idEntrepriseParent', $this->idEtp())
                    ->where('project_status', '!=', 'null')
                    ->whereIn('idModule', $idModules)
                    ->orderBy('project_status', 'asc')
                    ->groupBy('project_status')
                    ->get();

                $modalites = DB::table('v_apprenant_etp_all_groups')
                    ->select('project_modality', DB::raw('COUNT(idEmploye) AS emp_nb'))
                    ->where('idEntrepriseParent', $this->idEtp())
                    ->where('project_modality', '!=', 'null')
                    ->whereIn('idModule', $idModules)
                    ->orderBy('project_modality', 'asc')
                    ->groupBy('project_modality')
                    ->get();

                $periodePrev3 = DB::table('v_apprenant_etp_all_groups')
                    ->select('idProjet', 'p_id_periode', DB::raw('COUNT(idEmploye) AS emp_nb'))
                    ->where('idEntrepriseParent', $this->idEtp())
                    //->where('id_cfp', $this->idEtp())
                    ->where('dateDebut', '!=', 'null')
                    ->where('p_id_periode', "prev_3_month")
                    ->whereIn('idModule', $idModules)
                    ->groupBy('p_id_periode')
                    ->first();

                $periodePrev6 = DB::table('v_apprenant_etp_all_groups')
                    ->select('idProjet', 'p_id_periode', DB::raw('COUNT(idEmploye) AS emp_nb'))
                    ->where('idEntrepriseParent', $this->idEtp())
                    //->where('id_cfp', $this->idEtp())
                    ->where('dateDebut', '!=', 'null')
                    ->where('p_id_periode', "prev_6_month")
                    ->whereIn('idModule', $idModules)
                    ->groupBy('p_id_periode')
                    ->first();

                $periodePrev12 = DB::table('v_apprenant_etp_all_groups')
                    ->select('idProjet', 'p_id_periode', DB::raw('COUNT(idEmploye) AS emp_nb'))
                    ->where('idEntrepriseParent', $this->idEtp())
                    //->where('id_cfp', $this->idEtp())
                    ->where('dateDebut', '!=', 'null')
                    ->where('p_id_periode', "prev_12_month")
                    ->whereIn('idModule', $idModules)
                    ->groupBy('p_id_periode')
                    ->first();

                $periodeNext3 = DB::table('v_apprenant_etp_all_groups')
                    ->select('idProjet', 'p_id_periode', DB::raw('COUNT(idEmploye) AS emp_nb'))
                    ->where('idEntrepriseParent', $this->idEtp())
                    // ->where('id_cfp', $this->idEtp())
                    ->where('dateDebut', '!=', 'null')
                    ->where('p_id_periode', "next_3_month")
                    ->whereIn('idModule', $idModules)
                    ->groupBy('p_id_periode')
                    ->first();

                $periodeNext6 = DB::table('v_apprenant_etp_all_groups')
                    ->select('idProjet', 'p_id_periode', DB::raw('COUNT(idEmploye) AS emp_nb'))
                    ->where('idEntrepriseParent', $this->idEtp())
                    // ->where('id_cfp', $this->idEtp())
                    ->where('dateDebut', '!=', 'null')
                    ->where('p_id_periode', "next_6_month")
                    ->whereIn('idModule', $idModules)
                    ->groupBy('p_id_periode')
                    ->first();

                $periodeNext12 = DB::table('v_apprenant_etp_all_groups')
                    ->select('idProjet', 'p_id_periode', DB::raw('COUNT(idEmploye) AS emp_nb'))
                    ->where('idEntrepriseParent', $this->idEtp())
                    //->where('id_cfp', $this->idEtp())
                    ->where('dateDebut', '!=', 'null')
                    ->where('p_id_periode', "next_12_month")
                    ->whereIn('idModule', $idModules)
                    ->groupBy('p_id_periode')
                    ->first();
            } elseif ($idStatus[0] != null) {
                $query->whereIn('project_status', $idStatus);

                $etps = DB::table('v_apprenant_etp_all_groups')
                    ->select('idEtp', 'etp_name', DB::raw('COUNT(idEmploye) AS emp_nb'))
                    ->where('idEntrepriseParent', $this->idEtp())
                    ->whereIn('project_status', $idStatus)
                    ->orderBy('etp_name', 'asc')
                    ->groupBy('idEtp', 'etp_name')
                    ->get();

                $fonctions = DB::table('v_apprenant_etp_all_groups')
                    ->select('idFonction', 'emp_fonction', DB::raw('COUNT(idEmploye) AS emp_nb'))
                    ->where('idEntrepriseParent', $this->idEtp())
                    ->whereIn('project_status', $idStatus)
                    ->orderBy('emp_fonction', 'asc')
                    ->groupBy('idFonction', 'emp_fonction')
                    ->get();

                $villes = DB::table('v_apprenant_etp_all_groups')
                    ->select('project_id_ville', 'project_ville as ville', DB::raw('COUNT(idEmploye) AS emp_nb'))
                    ->where('idEntrepriseParent', $this->idEtp())
                    ->whereIn('project_status', $idStatus)
                    ->orderBy('project_ville', 'asc')
                    ->groupBy('project_id_ville', 'project_ville')
                    ->get();

                $modalites = DB::table('v_apprenant_etp_all_groups')
                    ->select('project_modality', DB::raw('COUNT(idEmploye) AS emp_nb'))
                    ->where('idEntrepriseParent', $this->idEtp())
                    ->where('project_modality', '!=', 'null')
                    ->whereIn('project_status', $idStatus)
                    ->orderBy('project_modality', 'asc')
                    ->groupBy('project_modality')
                    ->get();

                $modules = DB::table('v_apprenant_etp_all_groups')
                    ->select('idModule', 'module_name', DB::raw('COUNT(idEmploye) AS emp_nb'))
                    ->where('idEntrepriseParent', $this->idEtp())
                    ->where('idModule', '!=', 'null')
                    ->whereIn('project_status', $idStatus)
                    ->orderBy('module_name', 'asc')
                    ->groupBy('idModule', 'module_name')
                    ->get();

                $periodePrev3 = DB::table('v_apprenant_etp_all_groups')
                    ->select('idProjet', 'p_id_periode', DB::raw('COUNT(idEmploye) AS emp_nb'))
                    ->where('idEntrepriseParent', $this->idEtp())
                    //->where('id_cfp', $this->idEtp())
                    ->where('dateDebut', '!=', 'null')
                    ->where('p_id_periode', "prev_3_month")
                    ->whereIn('project_status', $idStatus)
                    ->groupBy('p_id_periode')
                    ->first();

                $periodePrev6 = DB::table('v_apprenant_etp_all_groups')
                    ->select('idProjet', 'p_id_periode', DB::raw('COUNT(idEmploye) AS emp_nb'))
                    ->where('idEntrepriseParent', $this->idEtp())
                    //->where('id_cfp', $this->idEtp())
                    ->where('dateDebut', '!=', 'null')
                    ->where('p_id_periode', "prev_6_month")
                    ->whereIn('project_status', $idStatus)
                    ->groupBy('p_id_periode')
                    ->first();

                $periodePrev12 = DB::table('v_apprenant_etp_all_groups')
                    ->select('idProjet', 'p_id_periode', DB::raw('COUNT(idEmploye) AS emp_nb'))
                    ->where('idEntrepriseParent', $this->idEtp())
                    //->where('id_cfp', $this->idEtp())
                    ->where('dateDebut', '!=', 'null')
                    ->where('p_id_periode', "prev_12_month")
                    ->whereIn('project_status', $idStatus)
                    ->groupBy('p_id_periode')
                    ->first();

                $periodeNext3 = DB::table('v_apprenant_etp_all_groups')
                    ->select('idProjet', 'p_id_periode', DB::raw('COUNT(idEmploye) AS emp_nb'))
                    ->where('idEntrepriseParent', $this->idEtp())
                    //->where('id_cfp', $this->idEtp())
                    ->where('dateDebut', '!=', 'null')
                    ->where('p_id_periode', "next_3_month")
                    ->whereIn('project_status', $idStatus)
                    ->groupBy('p_id_periode')
                    ->first();

                $periodeNext6 = DB::table('v_apprenant_etp_all_groups')
                    ->select('idProjet', 'p_id_periode', DB::raw('COUNT(idEmploye) AS emp_nb'))
                    ->where('idEntrepriseParent', $this->idEtp())
                    //->where('id_cfp', $this->idEtp())
                    ->where('dateDebut', '!=', 'null')
                    ->where('p_id_periode', "next_6_month")
                    ->whereIn('project_status', $idStatus)
                    ->groupBy('p_id_periode')
                    ->first();

                $periodeNext12 = DB::table('v_apprenant_etp_all_groups')
                    ->select('idProjet', 'p_id_periode', DB::raw('COUNT(idEmploye) AS emp_nb'))
                    ->where('idEntrepriseParent', $this->idEtp())
                    //->where('id_cfp', $this->idEtp())
                    ->where('dateDebut', '!=', 'null')
                    ->where('p_id_periode', "next_12_month")
                    ->whereIn('project_status', $idStatus)
                    ->groupBy('p_id_periode')
                    ->first();
            } elseif ($idModalites[0] != null) {
                $query->whereIn('project_modality', $idModalites);

                $etps = DB::table('v_apprenant_etp_all_groups')
                    ->select('idEtp', 'etp_name', DB::raw('COUNT(idEmploye) AS emp_nb'))
                    ->where('idEntrepriseParent', $this->idEtp())
                    ->whereIn('project_modality', $idModalites)
                    ->orderBy('etp_name', 'asc')
                    ->groupBy('idEtp', 'etp_name')
                    ->get();

                $fonctions = DB::table('v_apprenant_etp_all_groups')
                    ->select('idFonction', 'emp_fonction', DB::raw('COUNT(idEmploye) AS emp_nb'))
                    ->where('idEntrepriseParent', $this->idEtp())
                    ->whereIn('project_modality', $idModalites)
                    ->orderBy('emp_fonction', 'asc')
                    ->groupBy('idFonction', 'emp_fonction')
                    ->get();

                $villes = DB::table('v_apprenant_etp_all_groups')
                    ->select('project_id_ville', 'project_ville as ville', DB::raw('COUNT(idEmploye) AS emp_nb'))
                    ->where('idEntrepriseParent', $this->idEtp())
                    ->whereIn('project_modality', $idModalites)
                    ->orderBy('project_ville', 'asc')
                    ->groupBy('project_id_ville', 'project_ville')
                    ->get();

                $status = DB::table('v_apprenant_etp_all_groups')
                    ->select('project_status', DB::raw('COUNT(idEmploye) AS emp_nb'))
                    ->where('idEntrepriseParent', $this->idEtp())
                    ->where('project_status', '!=', 'null')
                    ->whereIn('project_modality', $idModalites)
                    ->orderBy('project_status', 'asc')
                    ->groupBy('project_status')
                    ->get();

                $modules = DB::table('v_apprenant_etp_all_groups')
                    ->select('idModule', 'module_name', DB::raw('COUNT(idEmploye) AS emp_nb'))
                    ->where('idEntrepriseParent', $this->idEtp())
                    ->where('idModule', '!=', 'null')
                    ->whereIn('project_modality', $idModalites)
                    ->orderBy('module_name', 'asc')
                    ->groupBy('idModule', 'module_name')
                    ->get();

                $periodePrev3 = DB::table('v_apprenant_etp_all_groups')
                    ->select('idProjet', 'p_id_periode', DB::raw('COUNT(idEmploye) AS emp_nb'))
                    ->where('idEntrepriseParent', $this->idEtp())
                    //->where('id_cfp', $this->idEtp())
                    ->where('dateDebut', '!=', 'null')
                    ->where('p_id_periode', "prev_3_month")
                    ->whereIn('project_modality', $idModalites)
                    ->groupBy('p_id_periode')
                    ->first();

                $periodePrev6 = DB::table('v_apprenant_etp_all_groups')
                    ->select('idProjet', 'p_id_periode', DB::raw('COUNT(idEmploye) AS emp_nb'))
                    ->where('idEntrepriseParent', $this->idEtp())
                    //->where('id_cfp', $this->idEtp())
                    ->where('dateDebut', '!=', 'null')
                    ->where('p_id_periode', "prev_6_month")
                    ->whereIn('project_modality', $idModalites)
                    ->groupBy('p_id_periode')
                    ->first();

                $periodePrev12 = DB::table('v_apprenant_etp_all_groups')
                    ->select('idProjet', 'p_id_periode', DB::raw('COUNT(idEmploye) AS emp_nb'))
                    ->where('idEntrepriseParent', $this->idEtp())
                    //->where('id_cfp', $this->idEtp())
                    ->where('dateDebut', '!=', 'null')
                    ->where('p_id_periode', "prev_12_month")
                    ->whereIn('project_modality', $idModalites)
                    ->groupBy('p_id_periode')
                    ->first();

                $periodeNext3 = DB::table('v_apprenant_etp_all_groups')
                    ->select('idProjet', 'p_id_periode', DB::raw('COUNT(idEmploye) AS emp_nb'))
                    ->where('idEtp', $this->idEtp())
                    //->where('id_cfp', $this->idEtp())
                    ->where('dateDebut', '!=', 'null')
                    ->where('p_id_periode', "next_3_month")
                    ->whereIn('project_modality', $idModalites)
                    ->groupBy('p_id_periode')
                    ->first();

                $periodeNext6 = DB::table('v_apprenant_etp_all_groups')
                    ->select('idProjet', 'p_id_periode', DB::raw('COUNT(idEmploye) AS emp_nb'))
                    ->where('idEntrepriseParent', $this->idEtp())
                    //->where('id_cfp', $this->idEtp())
                    ->where('dateDebut', '!=', 'null')
                    ->where('p_id_periode', "next_6_month")
                    ->whereIn('project_modality', $idModalites)
                    ->groupBy('p_id_periode')
                    ->first();

                $periodeNext12 = DB::table('v_apprenant_etp_all_groups')
                    ->select('idProjet', 'p_id_periode', DB::raw('COUNT(idEmploye) AS emp_nb'))
                    ->where('idEntrepriseParent', $this->idEtp())
                    //->where('id_cfp', $this->idEtp())
                    ->where('dateDebut', '!=', 'null')
                    ->where('p_id_periode', "next_12_month")
                    ->whereIn('project_modality', $idModalites)
                    ->groupBy('p_id_periode')
                    ->first();
            } elseif ($idVilles[0] != null) {
                $query->whereIn('project_id_ville', $idVilles);

                $etps = DB::table('v_periode_groups')
                    ->select('idEtp', 'etp_name', DB::raw('COUNT(idEmploye) AS emp_nb'))
                    ->where('idEntrepriseParent', $this->idEtp())
                    ->whereIn('project_id_ville', $idVilles)
                    ->orderBy('etp_name', 'asc')
                    ->groupBy('idEtp', 'etp_name')
                    ->get();

                $fonctions = DB::table('v_periode_groups')
                    ->select('idFonction', 'emp_fonction', DB::raw('COUNT(idEmploye) AS emp_nb'))
                    ->where('idEntrepriseParent', $this->idEtp())
                    ->whereIn('project_id_ville', $idVilles)
                    ->orderBy('emp_fonction', 'asc')
                    ->groupBy('idFonction', 'emp_fonction')
                    ->get();

                $status = DB::table('v_apprenant_etp_all_groups')
                    ->select('project_status', DB::raw('COUNT(idEmploye) AS emp_nb'))
                    ->where('idEntrepriseParent', $this->idEtp())
                    ->where('project_status', '!=', 'null')
                    ->whereIn('project_id_ville', $idVilles)
                    ->orderBy('project_status', 'asc')
                    ->groupBy('project_status')
                    ->get();

                $modalites = DB::table('v_apprenant_etp_all_groups')
                    ->select('project_modality', DB::raw('COUNT(idEmploye) AS emp_nb'))
                    ->where('idEntrepriseParent', $this->idEtp())
                    ->where('project_modality', '!=', 'null')
                    ->whereIn('project_id_ville', $idVilles)
                    ->orderBy('project_modality', 'asc')
                    ->groupBy('project_modality')
                    ->get();

                $modules = DB::table('v_apprenant_etp_all_groups')
                    ->select('idModule', 'module_name', DB::raw('COUNT(idEmploye) AS emp_nb'))
                    ->where('idEntrepriseParent', $this->idEtp())
                    ->where('idModule', '!=', 'null')
                    ->whereIn('project_id_ville', $idVilles)
                    ->orderBy('module_name', 'asc')
                    ->groupBy('idModule', 'module_name')
                    ->get();

                $periodePrev3 = DB::table('v_apprenant_etp_all_groups')
                    ->select('idProjet', 'p_id_periode', DB::raw('COUNT(idEmploye) AS emp_nb'))
                    ->where('idEntrepriseParent', $this->idEtp())
                    ->where('id_cfp', $this->idEtp())
                    ->where('dateDebut', '!=', 'null')
                    ->where('p_id_periode', "prev_3_month")
                    ->whereIn('project_id_ville', $idVilles)
                    ->groupBy('p_id_periode')
                    ->first();

                $periodePrev6 = DB::table('v_apprenant_etp_all_groups')
                    ->select('idProjet', 'p_id_periode', DB::raw('COUNT(idEmploye) AS emp_nb'))
                    ->where('idEntrepriseParent', $this->idEtp())
                    ->where('id_cfp', $this->idEtp())
                    ->where('dateDebut', '!=', 'null')
                    ->where('p_id_periode', "prev_6_month")
                    ->whereIn('project_id_ville', $idVilles)
                    ->groupBy('p_id_periode')
                    ->first();

                $periodePrev12 = DB::table('v_apprenant_etp_all_groups')
                    ->select('idProjet', 'p_id_periode', DB::raw('COUNT(idEmploye) AS emp_nb'))
                    ->where('idEntrepriseParent', $this->idEtp())
                    ->where('id_cfp', $this->idEtp())
                    ->where('dateDebut', '!=', 'null')
                    ->where('p_id_periode', "prev_12_month")
                    ->whereIn('project_id_ville', $idVilles)
                    ->groupBy('p_id_periode')
                    ->first();

                $periodeNext3 = DB::table('v_apprenant_etp_all_groups')
                    ->select('idProjet', 'p_id_periode', DB::raw('COUNT(idEmploye) AS emp_nb'))
                    ->where('idEntrepriseParent', $this->idEtp())
                    ->where('id_cfp', $this->idEtp())
                    ->where('dateDebut', '!=', 'null')
                    ->where('p_id_periode', "next_3_month")
                    ->whereIn('project_id_ville', $idVilles)
                    ->groupBy('p_id_periode')
                    ->first();

                $periodeNext6 = DB::table('v_apprenant_etp_all_groups')
                    ->select('idProjet', 'p_id_periode', DB::raw('COUNT(idEmploye) AS emp_nb'))
                    ->where('idEntrepriseParent', $this->idEtp())
                    ->where('id_cfp', $this->idEtp())
                    ->where('dateDebut', '!=', 'null')
                    ->where('p_id_periode', "next_6_month")
                    ->whereIn('project_id_ville', $idVilles)
                    ->groupBy('p_id_periode')
                    ->first();

                $periodeNext12 = DB::table('v_apprenant_etp_all_groups')
                    ->select('idProjet', 'p_id_periode', DB::raw('COUNT(idEmploye) AS emp_nb'))
                    ->where('idEntrepriseParent', $this->idEtp())
                    ->where('id_cfp', $this->idEtp())
                    ->where('dateDebut', '!=', 'null')
                    ->where('p_id_periode', "next_12_month")
                    ->whereIn('project_id_ville', $idVilles)
                    ->groupBy('p_id_periode')
                    ->first();
            } elseif ($idPeriodes != null) {
                $query->where('p_id_periode', $idPeriodes);

                $etps = DB::table('v_periode_groups')
                    ->select('idEtp', 'etp_name', DB::raw('COUNT(idEmploye) AS emp_nb'))
                    ->where('idEntrepriseParent', $this->idEtp())
                    ->where('p_id_periode', $idPeriodes)
                    ->orderBy('etp_name', 'asc')
                    ->groupBy('idEtp', 'etp_name')
                    ->get();

                $fonctions = DB::table('v_periode_groups')
                    ->select('idFonction', 'emp_fonction', DB::raw('COUNT(idEmploye) AS emp_nb'))
                    ->where('idEntrepriseParent', $this->idEtp())
                    ->where('p_id_periode', $idPeriodes)
                    ->orderBy('emp_fonction', 'asc')
                    ->groupBy('idFonction', 'emp_fonction')
                    ->get();

                $villes = DB::table('v_periode_groups')
                    ->select('project_id_ville', 'project_ville as ville', DB::raw('COUNT(idEmploye) AS emp_nb'))
                    ->where('idEntrepriseParent', $this->idEtp())
                    ->where('p_id_periode', $idPeriodes)
                    ->orderBy('project_ville', 'asc')
                    ->groupBy('project_id_ville', 'project_ville')
                    ->get();

                $status = DB::table('v_apprenant_etp_all_groups')
                    ->select('project_status', DB::raw('COUNT(idEmploye) AS emp_nb'))
                    ->where('idEntrepriseParent', $this->idEtp())
                    ->where('project_status', '!=', 'null')
                    ->where('p_id_periode', $idPeriodes)
                    ->orderBy('project_status', 'asc')
                    ->groupBy('project_status')
                    ->get();

                $modalites = DB::table('v_apprenant_etp_all_groups')
                    ->select('project_modality', DB::raw('COUNT(idEmploye) AS emp_nb'))
                    ->where('idEntrepriseParent', $this->idEtp())
                    ->where('project_modality', '!=', 'null')
                    ->where('p_id_periode', $idPeriodes)
                    ->orderBy('project_modality', 'asc')
                    ->groupBy('project_modality')
                    ->get();

                $modules = DB::table('v_apprenant_etp_all_groups')
                    ->select('idModule', 'module_name', DB::raw('COUNT(idEmploye) AS emp_nb'))
                    ->where('idEntrepriseParent', $this->idEtp())
                    ->where('idModule', '!=', 'null')
                    ->where('p_id_periode', $idPeriodes)
                    ->orderBy('module_name', 'asc')
                    ->groupBy('idModule', 'module_name')
                    ->get();
            } else {
                $etps = DB::table('v_apprenant_etp_all_filter_groups')
                    ->select('idEtp', 'etp_name', DB::raw('COUNT(idEmploye) AS emp_nb'))
                    ->where('idEntrepriseParent', $this->idEtp())
                    ->orderBy('etp_name', 'asc')
                    ->groupBy('idEtp', 'etp_name')
                    ->get();

                $fonctions = DB::table('v_apprenant_etp_all_filter_groups')
                    ->select('idFonction', 'emp_fonction', DB::raw('COUNT(idEmploye) AS emp_nb'))
                    ->where('idEntrepriseParent', $this->idEtp())
                    ->orderBy('emp_fonction', 'asc')
                    ->groupBy('idFonction', 'emp_fonction')
                    ->get();

                $villes = DB::table('v_periode_groups')
                    ->select('project_id_ville', 'project_ville as ville', DB::raw('COUNT(idEmploye) AS emp_nb'))
                    ->where('idEntrepriseParent', $this->idEtp())
                    ->orderBy('project_ville', 'asc')
                    ->groupBy('project_id_ville', 'project_ville')
                    ->get();

                $status = DB::table('v_apprenant_etp_all_groups')
                    ->select('project_status', DB::raw('COUNT(idEmploye) AS emp_nb'))
                    ->where('idEntrepriseParent', $this->idEtp())
                    ->where('project_status', '!=', 'null')
                    ->orderBy('project_status', 'asc')
                    ->groupBy('project_status')
                    ->get();

                $modalites = DB::table('v_apprenant_etp_all_groups')
                    ->select('project_modality', DB::raw('COUNT(idEmploye) AS emp_nb'))
                    ->where('idEntrepriseParent', $this->idEtp())
                    ->where('project_modality', '!=', 'null')
                    ->orderBy('project_modality', 'asc')
                    ->groupBy('project_modality')
                    ->get();

                $modules = DB::table('v_apprenant_etp_all_groups')
                    ->select('idModule', 'module_name', DB::raw('COUNT(idEmploye) AS emp_nb'))
                    ->where('idEntrepriseParent', $this->idEtp())
                    ->where('idModule', '!=', 'null')
                    ->orderBy('module_name', 'asc')
                    ->groupBy('idModule', 'module_name')
                    ->get();

                $periodePrev3 = DB::table('v_apprenant_etp_all_groups')
                    ->select('idProjet', 'p_id_periode', DB::raw('COUNT(idEmploye) AS emp_nb'))
                    ->where('idEntrepriseParent', $this->idEtp())
                    ->where('id_cfp', $this->idEtp())
                    ->where('dateDebut', '!=', 'null')
                    ->where('p_id_periode', "prev_3_month")
                    ->groupBy('p_id_periode')
                    ->first();

                $periodePrev6 = DB::table('v_apprenant_etp_all_groups')
                    ->select('idProjet', 'p_id_periode', DB::raw('COUNT(idEmploye) AS emp_nb'))
                    ->where('idEntrepriseParent', $this->idEtp())
                    ->where('id_cfp', $this->idEtp())
                    ->where('dateDebut', '!=', 'null')
                    ->where('p_id_periode', "prev_6_month")
                    ->groupBy('p_id_periode')
                    ->first();

                $periodePrev12 = DB::table('v_apprenant_etp_all_groups')
                    ->select('idProjet', 'p_id_periode', DB::raw('COUNT(idEmploye) AS emp_nb'))
                    ->where('idEntrepriseParent', $this->idEtp())
                    ->where('id_cfp', $this->idEtp())
                    ->where('dateDebut', '!=', 'null')
                    ->where('p_id_periode', "prev_12_month")
                    ->groupBy('p_id_periode')
                    ->first();

                $periodeNext3 = DB::table('v_apprenant_etp_all_groups')
                    ->select('idProjet', 'p_id_periode', DB::raw('COUNT(idEmploye) AS emp_nb'))
                    ->where('idEntrepriseParent', $this->idEtp())
                    ->where('id_cfp', $this->idEtp())
                    ->where('dateDebut', '!=', 'null')
                    ->where('p_id_periode', "next_3_month")
                    ->groupBy('p_id_periode')
                    ->first();

                $periodeNext6 = DB::table('v_apprenant_etp_all_groups')
                    ->select('idProjet', 'p_id_periode', DB::raw('COUNT(idEmploye) AS emp_nb'))
                    ->where('idEntrepriseParent', $this->idEtp())
                    ->where('id_cfp', $this->idEtp())
                    ->where('dateDebut', '!=', 'null')
                    ->where('p_id_periode', "next_6_month")
                    ->groupBy('p_id_periode')
                    ->first();

                $periodeNext12 = DB::table('v_apprenant_etp_all_groups')
                    ->select('idProjet', 'p_id_periode', DB::raw('COUNT(idEmploye) AS emp_nb'))
                    ->where('idEntrepriseParent', $this->idEtp())
                    ->where('id_cfp', $this->idEtp())
                    ->where('dateDebut', '!=', 'null')
                    ->where('p_id_periode', "next_12_month")
                    ->groupBy('p_id_periode')
                    ->first();
            }

            $query->groupBy('idEmploye', 'emp_name', 'emp_firstname', 'emp_initial_name', 'emp_photo', 'emp_matricule', 'emp_phone', 'emp_email', 'emp_fonction', 'idEtp', 'etp_name');

            $apprs = $query->get();

            if ($idEtps[0] != null) {
                return response()->json([
                    'fonctions' => $fonctions,
                    'status' => $status,
                    'villes' => $villes,
                    'modalites' => $modalites,
                    'modules' => $modules,
                    'periodes' => $idPeriodes,
                    'apprs' => $apprs,
                    'periodePrev3' => $periodePrev3,
                    'periodePrev6' => $periodePrev6,
                    'periodePrev12' => $periodePrev12,
                    'periodeNext3' => $periodeNext3,
                    'periodeNext6' => $periodeNext6,
                    'periodeNext12' => $periodeNext12
                ]);
            } elseif ($idFonctions[0] != null) {
                return response()->json([
                    'etps' => $etps,
                    'status' => $status,
                    'villes' => $villes,
                    'modalites' => $modalites,
                    'modules' => $modules,
                    'periodes' => $idPeriodes,
                    'apprs' => $apprs,
                    'periodePrev3' => $periodePrev3,
                    'periodePrev6' => $periodePrev6,
                    'periodePrev12' => $periodePrev12,
                    'periodeNext3' => $periodeNext3,
                    'periodeNext6' => $periodeNext6,
                    'periodeNext12' => $periodeNext12
                ]);
            } elseif ($idModules[0] != null) {
                return response()->json([
                    'etps' => $etps,
                    'fonctions' => $fonctions,
                    'status' => $status,
                    'villes' => $villes,
                    'modalites' => $modalites,
                    'periodes' => $idPeriodes,
                    'apprs' => $apprs,
                    'periodePrev3' => $periodePrev3,
                    'periodePrev6' => $periodePrev6,
                    'periodePrev12' => $periodePrev12,
                    'periodeNext3' => $periodeNext3,
                    'periodeNext6' => $periodeNext6,
                    'periodeNext12' => $periodeNext12
                ]);
            } elseif ($idStatus[0] != null) {
                return response()->json([
                    'etps' => $etps,
                    'fonctions' => $fonctions,
                    'villes' => $villes,
                    'modalites' => $modalites,
                    'modules' => $modules,
                    'periodes' => $idPeriodes,
                    'apprs' => $apprs,
                    'periodePrev3' => $periodePrev3,
                    'periodePrev6' => $periodePrev6,
                    'periodePrev12' => $periodePrev12,
                    'periodeNext3' => $periodeNext3,
                    'periodeNext6' => $periodeNext6,
                    'periodeNext12' => $periodeNext12
                ]);
            } elseif ($idModalites[0] != null) {
                return response()->json([
                    'etps' => $etps,
                    'fonctions' => $fonctions,
                    'status' => $status,
                    'villes' => $villes,
                    'modules' => $modules,
                    'periodes' => $idPeriodes,
                    'apprs' => $apprs,
                    'periodePrev3' => $periodePrev3,
                    'periodePrev6' => $periodePrev6,
                    'periodePrev12' => $periodePrev12,
                    'periodeNext3' => $periodeNext3,
                    'periodeNext6' => $periodeNext6,
                    'periodeNext12' => $periodeNext12
                ]);
            } elseif ($idVilles[0] != null) {
                return response()->json([
                    'etps' => $etps,
                    'fonctions' => $fonctions,
                    'status' => $status,
                    'modalites' => $modalites,
                    'modules' => $modules,
                    'periodes' => $idPeriodes,
                    'apprs' => $apprs,
                    'periodePrev3' => $periodePrev3,
                    'periodePrev6' => $periodePrev6,
                    'periodePrev12' => $periodePrev12,
                    'periodeNext3' => $periodeNext3,
                    'periodeNext6' => $periodeNext6,
                    'periodeNext12' => $periodeNext12
                ]);
            } elseif ($idPeriodes != null) {
                return response()->json([
                    'etps' => $etps,
                    'fonctions' => $fonctions,
                    'status' => $status,
                    'villes' => $villes,
                    'modalites' => $modalites,
                    'modules' => $modules,
                    'apprs' => $apprs
                ]);
            } else {
                return response()->json([
                    'etps' => $etps,
                    'fonctions' => $fonctions,
                    'status' => $status,
                    'villes' => $villes,
                    'modalites' => $modalites,
                    'modules' => $modules,
                    'periodes' => $idPeriodes,
                    'apprs' => $apprs,
                    'periodePrev3' => $periodePrev3,
                    'periodePrev6' => $periodePrev6,
                    'periodePrev12' => $periodePrev12,
                    'periodeNext3' => $periodeNext3,
                    'periodeNext6' => $periodeNext6,
                    'periodeNext12' => $periodeNext12
                ]);
            }
        } else {

            //ETPS FILLES...
            $query = DB::table('v_apprenant_etp_alls')
                ->select('idEmploye', 'emp_name', 'emp_firstname', 'emp_initial_name', 'emp_photo', 'emp_matricule', 'emp_phone', 'emp_email', 'emp_fonction', 'idEtp', 'etp_name', 'user_is_in_service')
                ->where('idEtp', $this->idEtp());

            if ($idEtps[0] != null) {
                $query->whereIn('idEtp', $idEtps);

                if ($idFonctions[0] != null) {
                    $query->whereIn('idFonction', $idFonctions);
                }

                $fonctions = DB::table('v_periodes')
                    ->select('idFonction', 'emp_fonction', DB::raw('COUNT(idEmploye) AS emp_nb'))
                    ->where('idEtp', $this->idEtp())
                    ->whereIn('idEtp', $idEtps)
                    ->orderBy('emp_fonction', 'asc')
                    ->groupBy('idFonction', 'emp_fonction')
                    ->get();

                $villes = DB::table('v_periodes')
                    ->select('project_id_ville', 'project_ville as ville', DB::raw('COUNT(idEmploye) AS emp_nb'))
                    ->where('idEtp', $this->idEtp())
                    ->whereIn('idEtp', $idEtps)
                    ->orderBy('project_ville', 'asc')
                    ->groupBy('project_id_ville', 'project_ville')
                    ->get();

                $status = DB::table('v_apprenant_etp_alls')
                    ->select('project_status', DB::raw('COUNT(idEmploye) AS emp_nb'))
                    ->where('idEtp', $this->idEtp())
                    ->where('project_status', '!=', 'null')
                    ->whereIn('idEtp', $idEtps)
                    ->orderBy('project_status', 'asc')
                    ->groupBy('project_status')
                    ->get();

                $modalites = DB::table('v_apprenant_etp_alls')
                    ->select('project_modality', DB::raw('COUNT(idEmploye) AS emp_nb'))
                    ->where('idEtp', $this->idEtp())
                    ->where('project_modality', '!=', 'null')
                    ->whereIn('idEtp', $idEtps)
                    ->orderBy('project_modality', 'asc')
                    ->groupBy('project_modality')
                    ->get();

                $modules = DB::table('v_apprenant_etp_alls')
                    ->select('idModule', 'module_name', DB::raw('COUNT(idEmploye) AS emp_nb'))
                    ->where('idEtp', $this->idEtp())
                    ->where('idModule', '!=', 'null')
                    ->whereIn('idEtp', $idEtps)
                    ->orderBy('module_name', 'asc')
                    ->groupBy('idModule', 'module_name')
                    ->get();

                $periodePrev3 = DB::table('v_apprenant_etp_alls')
                    ->select('idProjet', 'p_id_periode', DB::raw('COUNT(idEmploye) AS emp_nb'))
                    ->where('idEtp', $this->idEtp())
                    ->where('id_cfp', $this->idEtp())
                    ->where('dateDebut', '!=', 'null')
                    ->where('p_id_periode', "prev_3_month")
                    ->whereIn('idEtp', $idEtps)
                    ->groupBy('p_id_periode')
                    ->first();

                $periodePrev6 = DB::table('v_apprenant_etp_alls')
                    ->select('idProjet', 'p_id_periode', DB::raw('COUNT(idEmploye) AS emp_nb'))
                    ->where('idEtp', $this->idEtp())
                    ->where('id_cfp', $this->idEtp())
                    ->where('dateDebut', '!=', 'null')
                    ->where('p_id_periode', "prev_6_month")
                    ->whereIn('idEtp', $idEtps)
                    ->groupBy('p_id_periode')
                    ->first();

                $periodePrev12 = DB::table('v_apprenant_etp_alls')
                    ->select('idProjet', 'p_id_periode', DB::raw('COUNT(idEmploye) AS emp_nb'))
                    ->where('idEtp', $this->idEtp())
                    ->where('id_cfp', $this->idEtp())
                    ->where('dateDebut', '!=', 'null')
                    ->where('p_id_periode', "prev_12_month")
                    ->whereIn('idEtp', $idEtps)
                    ->groupBy('p_id_periode')
                    ->first();

                $periodeNext3 = DB::table('v_apprenant_etp_alls')
                    ->select('idProjet', 'p_id_periode', DB::raw('COUNT(idEmploye) AS emp_nb'))
                    ->where('idEtp', $this->idEtp())
                    ->where('id_cfp', $this->idEtp())
                    ->where('dateDebut', '!=', 'null')
                    ->where('p_id_periode', "next_3_month")
                    ->whereIn('idEtp', $idEtps)
                    ->groupBy('p_id_periode')
                    ->first();

                $periodeNext6 = DB::table('v_apprenant_etp_alls')
                    ->select('idProjet', 'p_id_periode', DB::raw('COUNT(idEmploye) AS emp_nb'))
                    ->where('idEtp', $this->idEtp())
                    ->where('id_cfp', $this->idEtp())
                    ->where('dateDebut', '!=', 'null')
                    ->where('p_id_periode', "next_6_month")
                    ->whereIn('idEtp', $idEtps)
                    ->groupBy('p_id_periode')
                    ->first();

                $periodeNext12 = DB::table('v_apprenant_etp_alls')
                    ->select('idProjet', 'p_id_periode', DB::raw('COUNT(idEmploye) AS emp_nb'))
                    ->where('idEtp', $this->idEtp())
                    ->where('id_cfp', $this->idEtp())
                    ->where('dateDebut', '!=', 'null')
                    ->where('p_id_periode', "next_12_month")
                    ->whereIn('idEtp', $idEtps)
                    ->groupBy('p_id_periode')
                    ->first();
            } elseif ($idFonctions[0] != null) {
                $query->whereIn('idFonction', $idFonctions);

                $etps = DB::table('v_periodes')
                    ->select('idEtp', 'etp_name', DB::raw('COUNT(idEmploye) AS emp_nb'))
                    ->where('idEtp', $this->idEtp())
                    ->whereIn('idFonction', $idFonctions)
                    ->orderBy('etp_name', 'asc')
                    ->groupBy('idEtp', 'etp_name')
                    ->get();

                $villes = DB::table('v_periodes')
                    ->select('project_id_ville', 'project_ville as ville', DB::raw('COUNT(idEmploye) AS emp_nb'))
                    ->where('idEtp', $this->idEtp())
                    ->whereIn('idFonction', $idFonctions)
                    ->orderBy('project_ville', 'asc')
                    ->groupBy('project_id_ville', 'project_ville')
                    ->get();

                $status = DB::table('v_apprenant_etp_alls')
                    ->select('project_status', DB::raw('COUNT(idEmploye) AS emp_nb'))
                    ->where('idEtp', $this->idEtp())
                    ->where('project_status', '!=', 'null')
                    ->whereIn('idFonction', $idFonctions)
                    ->orderBy('project_status', 'asc')
                    ->groupBy('project_status')
                    ->get();

                $modalites = DB::table('v_apprenant_etp_alls')
                    ->select('project_modality', DB::raw('COUNT(idEmploye) AS emp_nb'))
                    ->where('idEtp', $this->idEtp())
                    ->where('project_modality', '!=', 'null')
                    ->whereIn('idFonction', $idFonctions)
                    ->orderBy('project_modality', 'asc')
                    ->groupBy('project_modality')
                    ->get();

                $modules = DB::table('v_apprenant_etp_alls')
                    ->select('idModule', 'module_name', DB::raw('COUNT(idEmploye) AS emp_nb'))
                    ->where('idEtp', $this->idEtp())
                    ->where('idModule', '!=', 'null')
                    ->whereIn('idFonction', $idFonctions)
                    ->orderBy('module_name', 'asc')
                    ->groupBy('idModule', 'module_name')
                    ->get();

                $periodePrev3 = DB::table('v_apprenant_etp_alls')
                    ->select('idProjet', 'p_id_periode', DB::raw('COUNT(idEmploye) AS emp_nb'))
                    ->where('idEtp', $this->idEtp())
                    ->where('id_cfp', $this->idEtp())
                    ->where('dateDebut', '!=', 'null')
                    ->where('p_id_periode', "prev_3_month")
                    ->whereIn('idFonction', $idFonctions)
                    ->groupBy('p_id_periode')
                    ->first();

                $periodePrev6 = DB::table('v_apprenant_etp_alls')
                    ->select('idProjet', 'p_id_periode', DB::raw('COUNT(idEmploye) AS emp_nb'))
                    ->where('idEtp', $this->idEtp())
                    ->where('id_cfp', $this->idEtp())
                    ->where('dateDebut', '!=', 'null')
                    ->where('p_id_periode', "prev_6_month")
                    ->whereIn('idFonction', $idFonctions)
                    ->groupBy('p_id_periode')
                    ->first();

                $periodePrev12 = DB::table('v_apprenant_etp_alls')
                    ->select('idProjet', 'p_id_periode', DB::raw('COUNT(idEmploye) AS emp_nb'))
                    ->where('idEtp', $this->idEtp())
                    ->where('id_cfp', $this->idEtp())
                    ->where('dateDebut', '!=', 'null')
                    ->where('p_id_periode', "prev_12_month")
                    ->whereIn('idFonction', $idFonctions)
                    ->groupBy('p_id_periode')
                    ->first();

                $periodeNext3 = DB::table('v_apprenant_etp_alls')
                    ->select('idProjet', 'p_id_periode', DB::raw('COUNT(idEmploye) AS emp_nb'))
                    ->where('idEtp', $this->idEtp())
                    ->where('id_cfp', $this->idEtp())
                    ->where('dateDebut', '!=', 'null')
                    ->where('p_id_periode', "next_3_month")
                    ->whereIn('idFonction', $idFonctions)
                    ->groupBy('p_id_periode')
                    ->first();

                $periodeNext6 = DB::table('v_apprenant_etp_alls')
                    ->select('idProjet', 'p_id_periode', DB::raw('COUNT(idEmploye) AS emp_nb'))
                    ->where('idEtp', $this->idEtp())
                    ->where('id_cfp', $this->idEtp())
                    ->where('dateDebut', '!=', 'null')
                    ->where('p_id_periode', "next_6_month")
                    ->whereIn('idFonction', $idFonctions)
                    ->groupBy('p_id_periode')
                    ->first();

                $periodeNext12 = DB::table('v_apprenant_etp_alls')
                    ->select('idProjet', 'p_id_periode', DB::raw('COUNT(idEmploye) AS emp_nb'))
                    ->where('idEtp', $this->idEtp())
                    ->where('id_cfp', $this->idEtp())
                    ->where('dateDebut', '!=', 'null')
                    ->where('p_id_periode', "next_12_month")
                    ->whereIn('idFonction', $idFonctions)
                    ->groupBy('p_id_periode')
                    ->first();
            } elseif ($idModules[0] != null) {
                $query->whereIn('idModule', $idModules);

                $etps = DB::table('v_apprenant_etp_alls')
                    ->select('idEtp', 'etp_name', DB::raw('COUNT(idEmploye) AS emp_nb'))
                    ->where('idEtp', $this->idEtp())
                    ->whereIn('idModule', $idModules)
                    ->orderBy('etp_name', 'asc')
                    ->groupBy('idEtp', 'etp_name')
                    ->get();

                $fonctions = DB::table('v_apprenant_etp_alls')
                    ->select('idFonction', 'emp_fonction', DB::raw('COUNT(idEmploye) AS emp_nb'))
                    ->where('idEtp', $this->idEtp())
                    ->whereIn('idModule', $idModules)
                    ->orderBy('emp_fonction', 'asc')
                    ->groupBy('idFonction', 'emp_fonction')
                    ->get();

                $villes = DB::table('v_apprenant_etp_alls')
                    ->select('project_id_ville', 'project_ville as ville', DB::raw('COUNT(idEmploye) AS emp_nb'))
                    ->where('idEtp', $this->idEtp())
                    ->whereIn('idModule', $idModules)
                    ->orderBy('project_ville', 'asc')
                    ->groupBy('project_id_ville', 'project_ville')
                    ->get();

                $status = DB::table('v_apprenant_etp_alls')
                    ->select('project_status', DB::raw('COUNT(idEmploye) AS emp_nb'))
                    ->where('idEtp', $this->idEtp())
                    ->where('project_status', '!=', 'null')
                    ->whereIn('idModule', $idModules)
                    ->orderBy('project_status', 'asc')
                    ->groupBy('project_status')
                    ->get();

                $modalites = DB::table('v_apprenant_etp_alls')
                    ->select('project_modality', DB::raw('COUNT(idEmploye) AS emp_nb'))
                    ->where('idEtp', $this->idEtp())
                    ->where('project_modality', '!=', 'null')
                    ->whereIn('idModule', $idModules)
                    ->orderBy('project_modality', 'asc')
                    ->groupBy('project_modality')
                    ->get();

                $periodePrev3 = DB::table('v_apprenant_etp_alls')
                    ->select('idProjet', 'p_id_periode', DB::raw('COUNT(idEmploye) AS emp_nb'))
                    ->where('idEtp', $this->idEtp())
                    ->where('id_cfp', $this->idEtp())
                    ->where('dateDebut', '!=', 'null')
                    ->where('p_id_periode', "prev_3_month")
                    ->whereIn('idModule', $idModules)
                    ->groupBy('p_id_periode')
                    ->first();

                $periodePrev6 = DB::table('v_apprenant_etp_alls')
                    ->select('idProjet', 'p_id_periode', DB::raw('COUNT(idEmploye) AS emp_nb'))
                    ->where('idEtp', $this->idEtp())
                    ->where('id_cfp', $this->idEtp())
                    ->where('dateDebut', '!=', 'null')
                    ->where('p_id_periode', "prev_6_month")
                    ->whereIn('idModule', $idModules)
                    ->groupBy('p_id_periode')
                    ->first();

                $periodePrev12 = DB::table('v_apprenant_etp_alls')
                    ->select('idProjet', 'p_id_periode', DB::raw('COUNT(idEmploye) AS emp_nb'))
                    ->where('idEtp', $this->idEtp())
                    ->where('id_cfp', $this->idEtp())
                    ->where('dateDebut', '!=', 'null')
                    ->where('p_id_periode', "prev_12_month")
                    ->whereIn('idModule', $idModules)
                    ->groupBy('p_id_periode')
                    ->first();

                $periodeNext3 = DB::table('v_apprenant_etp_alls')
                    ->select('idProjet', 'p_id_periode', DB::raw('COUNT(idEmploye) AS emp_nb'))
                    ->where('idEtp', $this->idEtp())
                    ->where('id_cfp', $this->idEtp())
                    ->where('dateDebut', '!=', 'null')
                    ->where('p_id_periode', "next_3_month")
                    ->whereIn('idModule', $idModules)
                    ->groupBy('p_id_periode')
                    ->first();

                $periodeNext6 = DB::table('v_apprenant_etp_alls')
                    ->select('idProjet', 'p_id_periode', DB::raw('COUNT(idEmploye) AS emp_nb'))
                    ->where('idEtp', $this->idEtp())
                    ->where('id_cfp', $this->idEtp())
                    ->where('dateDebut', '!=', 'null')
                    ->where('p_id_periode', "next_6_month")
                    ->whereIn('idModule', $idModules)
                    ->groupBy('p_id_periode')
                    ->first();

                $periodeNext12 = DB::table('v_apprenant_etp_alls')
                    ->select('idProjet', 'p_id_periode', DB::raw('COUNT(idEmploye) AS emp_nb'))
                    ->where('idEtp', $this->idEtp())
                    ->where('id_cfp', $this->idEtp())
                    ->where('dateDebut', '!=', 'null')
                    ->where('p_id_periode', "next_12_month")
                    ->whereIn('idModule', $idModules)
                    ->groupBy('p_id_periode')
                    ->first();
            } elseif ($idStatus[0] != null) {
                $query->whereIn('project_status', $idStatus);

                $etps = DB::table('v_apprenant_etp_alls')
                    ->select('idEtp', 'etp_name', DB::raw('COUNT(idEmploye) AS emp_nb'))
                    ->where('idEtp', $this->idEtp())
                    ->whereIn('project_status', $idStatus)
                    ->orderBy('etp_name', 'asc')
                    ->groupBy('idEtp', 'etp_name')
                    ->get();

                $fonctions = DB::table('v_apprenant_etp_alls')
                    ->select('idFonction', 'emp_fonction', DB::raw('COUNT(idEmploye) AS emp_nb'))
                    ->where('idEtp', $this->idEtp())
                    ->whereIn('project_status', $idStatus)
                    ->orderBy('emp_fonction', 'asc')
                    ->groupBy('idFonction', 'emp_fonction')
                    ->get();

                $villes = DB::table('v_apprenant_etp_alls')
                    ->select('project_id_ville', 'project_ville as ville', DB::raw('COUNT(idEmploye) AS emp_nb'))
                    ->where('idEtp', $this->idEtp())
                    ->whereIn('project_status', $idStatus)
                    ->orderBy('project_ville', 'asc')
                    ->groupBy('project_id_ville', 'project_ville')
                    ->get();

                $modalites = DB::table('v_apprenant_etp_alls')
                    ->select('project_modality', DB::raw('COUNT(idEmploye) AS emp_nb'))
                    ->where('idEtp', $this->idEtp())
                    ->where('project_modality', '!=', 'null')
                    ->whereIn('project_status', $idStatus)
                    ->orderBy('project_modality', 'asc')
                    ->groupBy('project_modality')
                    ->get();

                $modules = DB::table('v_apprenant_etp_alls')
                    ->select('idModule', 'module_name', DB::raw('COUNT(idEmploye) AS emp_nb'))
                    ->where('idEtp', $this->idEtp())
                    ->where('idModule', '!=', 'null')
                    ->whereIn('project_status', $idStatus)
                    ->orderBy('module_name', 'asc')
                    ->groupBy('idModule', 'module_name')
                    ->get();

                $periodePrev3 = DB::table('v_apprenant_etp_alls')
                    ->select('idProjet', 'p_id_periode', DB::raw('COUNT(idEmploye) AS emp_nb'))
                    ->where('idEtp', $this->idEtp())
                    ->where('id_cfp', $this->idEtp())
                    ->where('dateDebut', '!=', 'null')
                    ->where('p_id_periode', "prev_3_month")
                    ->whereIn('project_status', $idStatus)
                    ->groupBy('p_id_periode')
                    ->first();

                $periodePrev6 = DB::table('v_apprenant_etp_alls')
                    ->select('idProjet', 'p_id_periode', DB::raw('COUNT(idEmploye) AS emp_nb'))
                    ->where('idEtp', $this->idEtp())
                    ->where('id_cfp', $this->idEtp())
                    ->where('dateDebut', '!=', 'null')
                    ->where('p_id_periode', "prev_6_month")
                    ->whereIn('project_status', $idStatus)
                    ->groupBy('p_id_periode')
                    ->first();

                $periodePrev12 = DB::table('v_apprenant_etp_alls')
                    ->select('idProjet', 'p_id_periode', DB::raw('COUNT(idEmploye) AS emp_nb'))
                    ->where('idEtp', $this->idEtp())
                    ->where('id_cfp', $this->idEtp())
                    ->where('dateDebut', '!=', 'null')
                    ->where('p_id_periode', "prev_12_month")
                    ->whereIn('project_status', $idStatus)
                    ->groupBy('p_id_periode')
                    ->first();

                $periodeNext3 = DB::table('v_apprenant_etp_alls')
                    ->select('idProjet', 'p_id_periode', DB::raw('COUNT(idEmploye) AS emp_nb'))
                    ->where('idEtp', $this->idEtp())
                    ->where('id_cfp', $this->idEtp())
                    ->where('dateDebut', '!=', 'null')
                    ->where('p_id_periode', "next_3_month")
                    ->whereIn('project_status', $idStatus)
                    ->groupBy('p_id_periode')
                    ->first();

                $periodeNext6 = DB::table('v_apprenant_etp_alls')
                    ->select('idProjet', 'p_id_periode', DB::raw('COUNT(idEmploye) AS emp_nb'))
                    ->where('idEtp', $this->idEtp())
                    ->where('id_cfp', $this->idEtp())
                    ->where('dateDebut', '!=', 'null')
                    ->where('p_id_periode', "next_6_month")
                    ->whereIn('project_status', $idStatus)
                    ->groupBy('p_id_periode')
                    ->first();

                $periodeNext12 = DB::table('v_apprenant_etp_alls')
                    ->select('idProjet', 'p_id_periode', DB::raw('COUNT(idEmploye) AS emp_nb'))
                    ->where('idEtp', $this->idEtp())
                    ->where('id_cfp', $this->idEtp())
                    ->where('dateDebut', '!=', 'null')
                    ->where('p_id_periode', "next_12_month")
                    ->whereIn('project_status', $idStatus)
                    ->groupBy('p_id_periode')
                    ->first();
            } elseif ($idModalites[0] != null) {
                $query->whereIn('project_modality', $idModalites);

                $etps = DB::table('v_apprenant_etp_alls')
                    ->select('idEtp', 'etp_name', DB::raw('COUNT(idEmploye) AS emp_nb'))
                    ->where('idEtp', $this->idEtp())
                    ->whereIn('project_modality', $idModalites)
                    ->orderBy('etp_name', 'asc')
                    ->groupBy('idEtp', 'etp_name')
                    ->get();

                $fonctions = DB::table('v_apprenant_etp_alls')
                    ->select('idFonction', 'emp_fonction', DB::raw('COUNT(idEmploye) AS emp_nb'))
                    ->where('idEtp', $this->idEtp())
                    ->whereIn('project_modality', $idModalites)
                    ->orderBy('emp_fonction', 'asc')
                    ->groupBy('idFonction', 'emp_fonction')
                    ->get();

                $villes = DB::table('v_apprenant_etp_alls')
                    ->select('project_id_ville', 'project_ville as ville', DB::raw('COUNT(idEmploye) AS emp_nb'))
                    ->where('idEtp', $this->idEtp())
                    ->whereIn('project_modality', $idModalites)
                    ->orderBy('project_ville', 'asc')
                    ->groupBy('project_id_ville', 'project_ville')
                    ->get();

                $status = DB::table('v_apprenant_etp_alls')
                    ->select('project_status', DB::raw('COUNT(idEmploye) AS emp_nb'))
                    ->where('idEtp', $this->idEtp())
                    ->where('project_status', '!=', 'null')
                    ->whereIn('project_modality', $idModalites)
                    ->orderBy('project_status', 'asc')
                    ->groupBy('project_status')
                    ->get();

                $modules = DB::table('v_apprenant_etp_alls')
                    ->select('idModule', 'module_name', DB::raw('COUNT(idEmploye) AS emp_nb'))
                    ->where('idEtp', $this->idEtp())
                    ->where('idModule', '!=', 'null')
                    ->whereIn('project_modality', $idModalites)
                    ->orderBy('module_name', 'asc')
                    ->groupBy('idModule', 'module_name')
                    ->get();

                $periodePrev3 = DB::table('v_apprenant_etp_alls')
                    ->select('idProjet', 'p_id_periode', DB::raw('COUNT(idEmploye) AS emp_nb'))
                    ->where('idEtp', $this->idEtp())
                    ->where('id_cfp', $this->idEtp())
                    ->where('dateDebut', '!=', 'null')
                    ->where('p_id_periode', "prev_3_month")
                    ->whereIn('project_modality', $idModalites)
                    ->groupBy('p_id_periode')
                    ->first();

                $periodePrev6 = DB::table('v_apprenant_etp_alls')
                    ->select('idProjet', 'p_id_periode', DB::raw('COUNT(idEmploye) AS emp_nb'))
                    ->where('idEtp', $this->idEtp())
                    ->where('id_cfp', $this->idEtp())
                    ->where('dateDebut', '!=', 'null')
                    ->where('p_id_periode', "prev_6_month")
                    ->whereIn('project_modality', $idModalites)
                    ->groupBy('p_id_periode')
                    ->first();

                $periodePrev12 = DB::table('v_apprenant_etp_alls')
                    ->select('idProjet', 'p_id_periode', DB::raw('COUNT(idEmploye) AS emp_nb'))
                    ->where('idEtp', $this->idEtp())
                    ->where('id_cfp', $this->idEtp())
                    ->where('dateDebut', '!=', 'null')
                    ->where('p_id_periode', "prev_12_month")
                    ->whereIn('project_modality', $idModalites)
                    ->groupBy('p_id_periode')
                    ->first();

                $periodeNext3 = DB::table('v_apprenant_etp_alls')
                    ->select('idProjet', 'p_id_periode', DB::raw('COUNT(idEmploye) AS emp_nb'))
                    ->where('idEtp', $this->idEtp())
                    ->where('id_cfp', $this->idEtp())
                    ->where('dateDebut', '!=', 'null')
                    ->where('p_id_periode', "next_3_month")
                    ->whereIn('project_modality', $idModalites)
                    ->groupBy('p_id_periode')
                    ->first();

                $periodeNext6 = DB::table('v_apprenant_etp_alls')
                    ->select('idProjet', 'p_id_periode', DB::raw('COUNT(idEmploye) AS emp_nb'))
                    ->where('idEtp', $this->idEtp())
                    ->where('id_cfp', $this->idEtp())
                    ->where('dateDebut', '!=', 'null')
                    ->where('p_id_periode', "next_6_month")
                    ->whereIn('project_modality', $idModalites)
                    ->groupBy('p_id_periode')
                    ->first();

                $periodeNext12 = DB::table('v_apprenant_etp_alls')
                    ->select('idProjet', 'p_id_periode', DB::raw('COUNT(idEmploye) AS emp_nb'))
                    ->where('idEtp', $this->idEtp())
                    ->where('id_cfp', $this->idEtp())
                    ->where('dateDebut', '!=', 'null')
                    ->where('p_id_periode', "next_12_month")
                    ->whereIn('project_modality', $idModalites)
                    ->groupBy('p_id_periode')
                    ->first();
            } elseif ($idVilles[0] != null) {
                $query->whereIn('project_id_ville', $idVilles);

                $etps = DB::table('v_periodes')
                    ->select('idEtp', 'etp_name', DB::raw('COUNT(idEmploye) AS emp_nb'))
                    ->where('idEtp', $this->idEtp())
                    ->whereIn('project_id_ville', $idVilles)
                    ->orderBy('etp_name', 'asc')
                    ->groupBy('idEtp', 'etp_name')
                    ->get();

                $fonctions = DB::table('v_periodes')
                    ->select('idFonction', 'emp_fonction', DB::raw('COUNT(idEmploye) AS emp_nb'))
                    ->where('idEtp', $this->idEtp())
                    ->whereIn('project_id_ville', $idVilles)
                    ->orderBy('emp_fonction', 'asc')
                    ->groupBy('idFonction', 'emp_fonction')
                    ->get();

                $status = DB::table('v_apprenant_etp_alls')
                    ->select('project_status', DB::raw('COUNT(idEmploye) AS emp_nb'))
                    ->where('idEtp', $this->idEtp())
                    ->where('project_status', '!=', 'null')
                    ->whereIn('project_id_ville', $idVilles)
                    ->orderBy('project_status', 'asc')
                    ->groupBy('project_status')
                    ->get();

                $modalites = DB::table('v_apprenant_etp_alls')
                    ->select('project_modality', DB::raw('COUNT(idEmploye) AS emp_nb'))
                    ->where('idEtp', $this->idEtp())
                    ->where('project_modality', '!=', 'null')
                    ->whereIn('project_id_ville', $idVilles)
                    ->orderBy('project_modality', 'asc')
                    ->groupBy('project_modality')
                    ->get();

                $modules = DB::table('v_apprenant_etp_alls')
                    ->select('idModule', 'module_name', DB::raw('COUNT(idEmploye) AS emp_nb'))
                    ->where('idEtp', $this->idEtp())
                    ->where('idModule', '!=', 'null')
                    ->whereIn('project_id_ville', $idVilles)
                    ->orderBy('module_name', 'asc')
                    ->groupBy('idModule', 'module_name')
                    ->get();

                $periodePrev3 = DB::table('v_apprenant_etp_alls')
                    ->select('idProjet', 'p_id_periode', DB::raw('COUNT(idEmploye) AS emp_nb'))
                    ->where('idEtp', $this->idEtp())
                    ->where('id_cfp', $this->idEtp())
                    ->where('dateDebut', '!=', 'null')
                    ->where('p_id_periode', "prev_3_month")
                    ->whereIn('project_id_ville', $idVilles)
                    ->groupBy('p_id_periode')
                    ->first();

                $periodePrev6 = DB::table('v_apprenant_etp_alls')
                    ->select('idProjet', 'p_id_periode', DB::raw('COUNT(idEmploye) AS emp_nb'))
                    ->where('idEtp', $this->idEtp())
                    ->where('id_cfp', $this->idEtp())
                    ->where('dateDebut', '!=', 'null')
                    ->where('p_id_periode', "prev_6_month")
                    ->whereIn('project_id_ville', $idVilles)
                    ->groupBy('p_id_periode')
                    ->first();

                $periodePrev12 = DB::table('v_apprenant_etp_alls')
                    ->select('idProjet', 'p_id_periode', DB::raw('COUNT(idEmploye) AS emp_nb'))
                    ->where('idEtp', $this->idEtp())
                    ->where('id_cfp', $this->idEtp())
                    ->where('dateDebut', '!=', 'null')
                    ->where('p_id_periode', "prev_12_month")
                    ->whereIn('project_id_ville', $idVilles)
                    ->groupBy('p_id_periode')
                    ->first();

                $periodeNext3 = DB::table('v_apprenant_etp_alls')
                    ->select('idProjet', 'p_id_periode', DB::raw('COUNT(idEmploye) AS emp_nb'))
                    ->where('idEtp', $this->idEtp())
                    ->where('id_cfp', $this->idEtp())
                    ->where('dateDebut', '!=', 'null')
                    ->where('p_id_periode', "next_3_month")
                    ->whereIn('project_id_ville', $idVilles)
                    ->groupBy('p_id_periode')
                    ->first();

                $periodeNext6 = DB::table('v_apprenant_etp_alls')
                    ->select('idProjet', 'p_id_periode', DB::raw('COUNT(idEmploye) AS emp_nb'))
                    ->where('idEtp', $this->idEtp())
                    ->where('id_cfp', $this->idEtp())
                    ->where('dateDebut', '!=', 'null')
                    ->where('p_id_periode', "next_6_month")
                    ->whereIn('project_id_ville', $idVilles)
                    ->groupBy('p_id_periode')
                    ->first();

                $periodeNext12 = DB::table('v_apprenant_etp_alls')
                    ->select('idProjet', 'p_id_periode', DB::raw('COUNT(idEmploye) AS emp_nb'))
                    ->where('idEtp', $this->idEtp())
                    ->where('id_cfp', $this->idEtp())
                    ->where('dateDebut', '!=', 'null')
                    ->where('p_id_periode', "next_12_month")
                    ->whereIn('project_id_ville', $idVilles)
                    ->groupBy('p_id_periode')
                    ->first();
            } elseif ($idPeriodes != null) {
                $query->where('p_id_periode', $idPeriodes);

                $etps = DB::table('v_periodes')
                    ->select('idEtp', 'etp_name', DB::raw('COUNT(idEmploye) AS emp_nb'))
                    ->where('idEtp', $this->idEtp())
                    ->where('p_id_periode', $idPeriodes)
                    ->orderBy('etp_name', 'asc')
                    ->groupBy('idEtp', 'etp_name')
                    ->get();

                $fonctions = DB::table('v_periodes')
                    ->select('idFonction', 'emp_fonction', DB::raw('COUNT(idEmploye) AS emp_nb'))
                    ->where('idEtp', $this->idEtp())
                    ->where('p_id_periode', $idPeriodes)
                    ->orderBy('emp_fonction', 'asc')
                    ->groupBy('idFonction', 'emp_fonction')
                    ->get();

                $villes = DB::table('v_periodes')
                    ->select('project_id_ville', 'project_ville as ville', DB::raw('COUNT(idEmploye) AS emp_nb'))
                    ->where('idEtp', $this->idEtp())
                    ->where('p_id_periode', $idPeriodes)
                    ->orderBy('project_ville', 'asc')
                    ->groupBy('project_id_ville', 'project_ville')
                    ->get();

                $status = DB::table('v_apprenant_etp_alls')
                    ->select('project_status', DB::raw('COUNT(idEmploye) AS emp_nb'))
                    ->where('idEtp', $this->idEtp())
                    ->where('project_status', '!=', 'null')
                    ->where('p_id_periode', $idPeriodes)
                    ->orderBy('project_status', 'asc')
                    ->groupBy('project_status')
                    ->get();

                $modalites = DB::table('v_apprenant_etp_alls')
                    ->select('project_modality', DB::raw('COUNT(idEmploye) AS emp_nb'))
                    ->where('idEtp', $this->idEtp())
                    ->where('project_modality', '!=', 'null')
                    ->where('p_id_periode', $idPeriodes)
                    ->orderBy('project_modality', 'asc')
                    ->groupBy('project_modality')
                    ->get();

                $modules = DB::table('v_apprenant_etp_alls')
                    ->select('idModule', 'module_name', DB::raw('COUNT(idEmploye) AS emp_nb'))
                    ->where('idEtp', $this->idEtp())
                    ->where('idModule', '!=', 'null')
                    ->where('p_id_periode', $idPeriodes)
                    ->orderBy('module_name', 'asc')
                    ->groupBy('idModule', 'module_name')
                    ->get();
            } else {
                $etps = DB::table('v_apprenant_etp_all_filters')
                    ->select('idEtp', 'etp_name', DB::raw('COUNT(idEmploye) AS emp_nb'))
                    ->where('idEtp', $this->idEtp())
                    ->orderBy('etp_name', 'asc')
                    ->groupBy('idEtp', 'etp_name')
                    ->get();

                $fonctions = DB::table('v_apprenant_etp_all_filters')
                    ->select('idFonction', 'emp_fonction', DB::raw('COUNT(idEmploye) AS emp_nb'))
                    ->where('idEtp', $this->idEtp())
                    ->orderBy('emp_fonction', 'asc')
                    ->groupBy('idFonction', 'emp_fonction')
                    ->get();

                $villes = DB::table('v_periodes')
                    ->select('project_id_ville', 'project_ville as ville', DB::raw('COUNT(idEmploye) AS emp_nb'))
                    ->where('idEtp', $this->idEtp())
                    ->orderBy('project_ville', 'asc')
                    ->groupBy('project_id_ville', 'project_ville')
                    ->get();

                $status = DB::table('v_apprenant_etp_alls')
                    ->select('project_status', DB::raw('COUNT(idEmploye) AS emp_nb'))
                    ->where('idEtp', $this->idEtp())
                    ->where('project_status', '!=', 'null')
                    ->orderBy('project_status', 'asc')
                    ->groupBy('project_status')
                    ->get();

                $modalites = DB::table('v_apprenant_etp_alls')
                    ->select('project_modality', DB::raw('COUNT(idEmploye) AS emp_nb'))
                    ->where('idEtp', $this->idEtp())
                    ->where('project_modality', '!=', 'null')
                    ->orderBy('project_modality', 'asc')
                    ->groupBy('project_modality')
                    ->get();

                $modules = DB::table('v_apprenant_etp_alls')
                    ->select('idModule', 'module_name', DB::raw('COUNT(idEmploye) AS emp_nb'))
                    ->where('idEtp', $this->idEtp())
                    ->where('idModule', '!=', 'null')
                    ->orderBy('module_name', 'asc')
                    ->groupBy('idModule', 'module_name')
                    ->get();

                $periodePrev3 = DB::table('v_apprenant_etp_alls')
                    ->select('idProjet', 'p_id_periode', DB::raw('COUNT(idEmploye) AS emp_nb'))
                    ->where('idEtp', $this->idEtp())
                    ->where('id_cfp', $this->idEtp())
                    ->where('dateDebut', '!=', 'null')
                    ->where('p_id_periode', "prev_3_month")
                    ->groupBy('p_id_periode')
                    ->first();

                $periodePrev6 = DB::table('v_apprenant_etp_alls')
                    ->select('idProjet', 'p_id_periode', DB::raw('COUNT(idEmploye) AS emp_nb'))
                    ->where('idEtp', $this->idEtp())
                    ->where('id_cfp', $this->idEtp())
                    ->where('dateDebut', '!=', 'null')
                    ->where('p_id_periode', "prev_6_month")
                    ->groupBy('p_id_periode')
                    ->first();

                $periodePrev12 = DB::table('v_apprenant_etp_alls')
                    ->select('idProjet', 'p_id_periode', DB::raw('COUNT(idEmploye) AS emp_nb'))
                    ->where('idEtp', $this->idEtp())
                    ->where('id_cfp', $this->idEtp())
                    ->where('dateDebut', '!=', 'null')
                    ->where('p_id_periode', "prev_12_month")
                    ->groupBy('p_id_periode')
                    ->first();

                $periodeNext3 = DB::table('v_apprenant_etp_alls')
                    ->select('idProjet', 'p_id_periode', DB::raw('COUNT(idEmploye) AS emp_nb'))
                    ->where('idEtp', $this->idEtp())
                    ->where('id_cfp', $this->idEtp())
                    ->where('dateDebut', '!=', 'null')
                    ->where('p_id_periode', "next_3_month")
                    ->groupBy('p_id_periode')
                    ->first();

                $periodeNext6 = DB::table('v_apprenant_etp_alls')
                    ->select('idProjet', 'p_id_periode', DB::raw('COUNT(idEmploye) AS emp_nb'))
                    ->where('idEtp', $this->idEtp())
                    ->where('id_cfp', $this->idEtp())
                    ->where('dateDebut', '!=', 'null')
                    ->where('p_id_periode', "next_6_month")
                    ->groupBy('p_id_periode')
                    ->first();

                $periodeNext12 = DB::table('v_apprenant_etp_alls')
                    ->select('idProjet', 'p_id_periode', DB::raw('COUNT(idEmploye) AS emp_nb'))
                    ->where('idEtp', $this->idEtp())
                    ->where('id_cfp', $this->idEtp())
                    ->where('dateDebut', '!=', 'null')
                    ->where('p_id_periode', "next_12_month")
                    ->groupBy('p_id_periode')
                    ->first();
            }

            $query->groupBy('idEmploye', 'emp_name', 'emp_firstname', 'emp_initial_name', 'emp_photo', 'emp_matricule', 'emp_phone', 'emp_email', 'emp_fonction', 'idEtp', 'etp_name');

            $apprs = $query->get();

            if ($idEtps[0] != null) {
                return response()->json([
                    'fonctions' => $fonctions,
                    'status' => $status,
                    'villes' => $villes,
                    'modalites' => $modalites,
                    'modules' => $modules,
                    'periodes' => $idPeriodes,
                    'apprs' => $apprs,
                    'periodePrev3' => $periodePrev3,
                    'periodePrev6' => $periodePrev6,
                    'periodePrev12' => $periodePrev12,
                    'periodeNext3' => $periodeNext3,
                    'periodeNext6' => $periodeNext6,
                    'periodeNext12' => $periodeNext12
                ]);
            } elseif ($idFonctions[0] != null) {
                return response()->json([
                    'etps' => $etps,
                    'status' => $status,
                    'villes' => $villes,
                    'modalites' => $modalites,
                    'modules' => $modules,
                    'periodes' => $idPeriodes,
                    'apprs' => $apprs,
                    'periodePrev3' => $periodePrev3,
                    'periodePrev6' => $periodePrev6,
                    'periodePrev12' => $periodePrev12,
                    'periodeNext3' => $periodeNext3,
                    'periodeNext6' => $periodeNext6,
                    'periodeNext12' => $periodeNext12
                ]);
            } elseif ($idModules[0] != null) {
                return response()->json([
                    'etps' => $etps,
                    'fonctions' => $fonctions,
                    'status' => $status,
                    'villes' => $villes,
                    'modalites' => $modalites,
                    'periodes' => $idPeriodes,
                    'apprs' => $apprs,
                    'periodePrev3' => $periodePrev3,
                    'periodePrev6' => $periodePrev6,
                    'periodePrev12' => $periodePrev12,
                    'periodeNext3' => $periodeNext3,
                    'periodeNext6' => $periodeNext6,
                    'periodeNext12' => $periodeNext12
                ]);
            } elseif ($idStatus[0] != null) {
                return response()->json([
                    'etps' => $etps,
                    'fonctions' => $fonctions,
                    'villes' => $villes,
                    'modalites' => $modalites,
                    'modules' => $modules,
                    'periodes' => $idPeriodes,
                    'apprs' => $apprs,
                    'periodePrev3' => $periodePrev3,
                    'periodePrev6' => $periodePrev6,
                    'periodePrev12' => $periodePrev12,
                    'periodeNext3' => $periodeNext3,
                    'periodeNext6' => $periodeNext6,
                    'periodeNext12' => $periodeNext12
                ]);
            } elseif ($idModalites[0] != null) {
                return response()->json([
                    'etps' => $etps,
                    'fonctions' => $fonctions,
                    'status' => $status,
                    'villes' => $villes,
                    'modules' => $modules,
                    'periodes' => $idPeriodes,
                    'apprs' => $apprs,
                    'periodePrev3' => $periodePrev3,
                    'periodePrev6' => $periodePrev6,
                    'periodePrev12' => $periodePrev12,
                    'periodeNext3' => $periodeNext3,
                    'periodeNext6' => $periodeNext6,
                    'periodeNext12' => $periodeNext12
                ]);
            } elseif ($idVilles[0] != null) {
                return response()->json([
                    'etps' => $etps,
                    'fonctions' => $fonctions,
                    'status' => $status,
                    'modalites' => $modalites,
                    'modules' => $modules,
                    'periodes' => $idPeriodes,
                    'apprs' => $apprs,
                    'periodePrev3' => $periodePrev3,
                    'periodePrev6' => $periodePrev6,
                    'periodePrev12' => $periodePrev12,
                    'periodeNext3' => $periodeNext3,
                    'periodeNext6' => $periodeNext6,
                    'periodeNext12' => $periodeNext12
                ]);
            } elseif ($idPeriodes != null) {
                return response()->json([
                    'etps' => $etps,
                    'fonctions' => $fonctions,
                    'status' => $status,
                    'villes' => $villes,
                    'modalites' => $modalites,
                    'modules' => $modules,
                    'apprs' => $apprs
                ]);
            } else {
                return response()->json([
                    'etps' => $etps,
                    'fonctions' => $fonctions,
                    'status' => $status,
                    'villes' => $villes,
                    'modalites' => $modalites,
                    'modules' => $modules,
                    'periodes' => $idPeriodes,
                    'apprs' => $apprs,
                    'periodePrev3' => $periodePrev3,
                    'periodePrev6' => $periodePrev6,
                    'periodePrev12' => $periodePrev12,
                    'periodeNext3' => $periodeNext3,
                    'periodeNext6' => $periodeNext6,
                    'periodeNext12' => $periodeNext12
                ]);
            }
        }
    }

    public function filterItem(Request $req)
    {
        $idEtps = explode(',', $this->idEtp());
        $idFonctions = explode(',', $req->idFonction);
        $idPeriodes = $req->idPeriode;
        $idModules = explode(',', $req->idModule);
        $idVilles = explode(',', $req->idVille);
        $idModalites = explode(',', $req->idModalite);
        $idStatus = explode(',', $req->idStatut);

        $checkEtpGrp = DB::table('etp_groupes')->where('idEntreprise', $this->idEtp())->exists();

        //dd($checkEtpGrp);
        if ($checkEtpGrp) {

            $query = DB::table('v_union_emp_grps')
                ->select('idEmploye', 'emp_name', 'emp_firstname', 'emp_initial_name', 'emp_photo', 'emp_matricule', 'emp_phone', 'emp_email', 'emp_fonction', 'idEtp', 'etp_name', 'user_is_in_service')
                ->where('idEntrepriseParent', Customer::idCustomer());
        } else {
            $query = DB::table('v_apprenant_etp_alls')
                ->select('idEmploye', 'emp_name', 'emp_firstname', 'emp_initial_name', 'emp_photo', 'emp_matricule', 'emp_phone', 'emp_email', 'emp_fonction', 'idEtp', 'etp_name', 'user_is_in_service')
                ->where('idEtp', $this->idEtp());
            // if ($checkEtpGrp) {

            //     $query = DB::table('v_apprenant_etp_all_groups')
            //         ->select('idEmploye', 'emp_name', 'emp_firstname', 'emp_initial_name', 'emp_photo', 'emp_matricule', 'emp_phone', 'emp_email', 'emp_fonction', 'idEtp', 'etp_name')
            //         ->where('idEntrepriseParent', $this->idEtp());
            // } else { //ETPS FILLES...
            //     $query = DB::table('v_apprenant_etp_alls')
            //         ->select('idEmploye', 'emp_name', 'emp_firstname', 'emp_initial_name', 'emp_photo', 'emp_matricule', 'emp_phone', 'emp_email', 'emp_fonction', 'idEtp', 'etp_name')
            //         ->where('idEtp', $this->idEtp());
            // }
        }
        if ($idEtps[0] != null) {
            $query->whereIn('idEtp', $idEtps);

            if ($idFonctions[0] != null) {
                $query->whereIn('idFonction', $idFonctions);
            }
            if ($idPeriodes != null) {
                $query->where('p_id_periode', $idPeriodes);
            }
            if ($idModules[0] != null) {
                $query->whereIn('idModule', $idModules);
            }
            if ($idVilles[0] != null) {
                $query->whereIn('project_id_ville', $idVilles);
            }
            if ($idModalites[0] != null) {
                $query->whereIn('project_modality', $idModalites);
            }
            if ($idStatus[0] != null) {
                $query->whereIn('project_status', $idStatus);
            }
        }

        if ($idFonctions[0] != null) {
            $query->whereIn('idFonction', $idFonctions);

            if ($idEtps[0] != null) {
                $query->whereIn('idEtp', $idEtps);
            }
            if ($idPeriodes != null) {
                $query->where('p_id_periode', $idPeriodes);
            }
            if ($idModules[0] != null) {
                $query->whereIn('idModule', $idModules);
            }
            if ($idVilles[0] != null) {
                $query->whereIn('project_id_ville', $idVilles);
            }
            if ($idModalites[0] != null) {
                $query->whereIn('project_modality', $idModalites);
            }
            if ($idStatus[0] != null) {
                $query->whereIn('project_status', $idStatus);
            }
        }

        if ($idPeriodes != null) {
            $query->where('p_id_periode', $idPeriodes);

            if ($idEtps[0] != null) {
                $query->whereIn('idEtp', $idEtps);
            }
            if ($idFonctions[0] != null) {
                $query->whereIn('idFonction', $idFonctions);
            }
            if ($idModules[0] != null) {
                $query->whereIn('idModule', $idModules);
            }
            if ($idVilles[0] != null) {
                $query->whereIn('project_id_ville', $idVilles);
            }
            if ($idModalites[0] != null) {
                $query->whereIn('project_modality', $idModalites);
            }
            if ($idStatus[0] != null) {
                $query->whereIn('project_status', $idStatus);
            }
        }

        if ($idModules[0] != null) {
            $query->whereIn('idModule', $idModules);

            if ($idEtps[0] != null) {
                $query->whereIn('idEtp', $idEtps);
            }
            if ($idFonctions[0] != null) {
                $query->whereIn('idFonction', $idFonctions);
            }
            if ($idPeriodes != null) {
                $query->where('p_id_periode', $idPeriodes);
            }
            if ($idVilles[0] != null) {
                $query->whereIn('project_id_ville', $idVilles);
            }
            if ($idModalites[0] != null) {
                $query->whereIn('project_modality', $idModalites);
            }
            if ($idStatus[0] != null) {
                $query->whereIn('project_status', $idStatus);
            }
        }

        if ($idVilles[0] != null) {
            $query->whereIn('project_id_ville', $idVilles);

            if ($idEtps[0] != null) {
                $query->whereIn('idEtp', $idEtps);
            }
            if ($idFonctions[0] != null) {
                $query->whereIn('idFonction', $idFonctions);
            }
            if ($idPeriodes != null) {
                $query->where('p_id_periode', $idPeriodes);
            }
            if ($idModules[0] != null) {
                $query->whereIn('idModule', $idModules);
            }
            if ($idModalites[0] != null) {
                $query->whereIn('project_modality', $idModalites);
            }
            if ($idStatus[0] != null) {
                $query->whereIn('project_status', $idStatus);
            }
        }

        if ($idModalites[0] != null) {
            $query->whereIn('project_modality', $idModalites);

            if ($idEtps[0] != null) {
                $query->whereIn('idEtp', $idEtps);
            }
            if ($idFonctions[0] != null) {
                $query->whereIn('idFonction', $idFonctions);
            }
            if ($idPeriodes != null) {
                $query->where('p_id_periode', $idPeriodes);
            }
            if ($idModules[0] != null) {
                $query->whereIn('idModule', $idModules);
            }
            if ($idVilles[0] != null) {
                $query->whereIn('project_id_ville', $idVilles);
            }
            if ($idStatus[0] != null) {
                $query->whereIn('project_status', $idStatus);
            }
        }

        if ($idStatus[0] != null) {
            $query->whereIn('project_status', $idStatus);

            if ($idEtps[0] != null) {
                $query->whereIn('idEtp', $idEtps);
            }
            if ($idFonctions[0] != null) {
                $query->whereIn('idFonction', $idFonctions);
            }
            if ($idPeriodes != null) {
                $query->where('p_id_periode', $idPeriodes);
            }
            if ($idModules[0] != null) {
                $query->whereIn('idModule', $idModules);
            }
            if ($idVilles[0] != null) {
                $query->whereIn('project_id_ville', $idVilles);
            }
            if ($idModalites[0] != null) {
                $query->whereIn('project_modality', $idModalites);
            }
        }

        $query->groupBy('idEmploye', 'emp_name', 'emp_firstname', 'emp_initial_name', 'emp_photo', 'emp_matricule', 'emp_phone', 'emp_email', 'emp_fonction', 'idEtp', 'etp_name');

        $apprs = $query->orderBy('idEmploye', 'DESC')->limit(8)->get();

        return response()->json(['apprs' => $apprs]);
    }

    public function updateService(Request $req, $idEmploye)
    {
        $req->validate([
            'user_service' => 'required|integer'
        ]);

        $update = DB::table('role_users')->where('user_id', $idEmploye)->update(['user_is_in_service' => $req->user_service]);

        if ($update) {
            return response()->json(['success' => 'Modifié avec succès']);
        } else {
            return response()->json(['error' => 'Erreur inconnue !']);
        }
    }

    public function getEtpType()
    {
        $etp = DB::table('entreprises')->select('idCustomer', 'idTypeEtp')->where('idCustomer', $this->idEtp())->first();

        if ($etp) {
            $etpGrps = DB::table('v_list_etp_groupeds')
                ->select('idEntreprise', 'etp_name', 'etp_logo')
                ->where('idEntrepriseParent', Auth::user()->id)
                ->orderBy('etp_name', 'asc')
                ->get();

            return response([
                'etp' => $etp,
                'etpGrps' => $etpGrps
            ]);
        } else {
            return response(['error' => 'Entreprise introuvable']);
        }
    }

    public function addEmpExcel(Request $request)
    {
        $validate = Validator::make($request->all(), [
            'data' => 'required|file|mimes:xls,xlsx',
        ]);

        if ($validate->fails()) {
            return response()->json(['error' => $validate->messages()]);
        } else {

            $idEntreprise = Auth::user()->id;
            $file = $request->file('data');
            $data = Excel::toArray(new ExcelApprenants, $file);
            $fonction = DB::table('fonctions')->select('idFonction')->where('idCustomer', $this->idEtp())->first();

            try {
                foreach ($data[0] as $row) {
                    if (!empty($row['nom']) || !empty($row['matricule'])) {
                        DB::beginTransaction();

                        $allUser = DB::table('users')
                            ->select('email', 'matricule')
                            ->get();

                        $verification = true;

                        foreach ($allUser as $userVerif) {
                            if ($userVerif->email == $row['e_mail'] && $userVerif->email != null) {
                                $verification = false;
                            }
                            if ($userVerif->matricule == $row['matricule'] && $userVerif->matricule != null) {
                                $verification = false;
                            }
                        };
                        if ($verification == true) {
                            $user = new User();
                            $user->matricule = $row['matricule'];
                            $user->name = $row['nom'];
                            $user->firstName = $row['prenom'];
                            $user->email = $row['e_mail'];
                            $user->phone = $row['telephone'];
                            $user->password =  Hash::make('0000@#');
                            $user->save();


                            $emp = new Employe();
                            $emp->idEmploye = $user->id;
                            $emp->idSexe = 1;
                            $emp->idNiveau = 6;
                            $emp->idCustomer = $idEntreprise;
                            $emp->idFonction = $fonction->idFonction;
                            $emp->save();

                            // Log::info();

                            RoleUser::create([
                                'role_id'  => 4,
                                'user_id'  => $user->id,
                                'isActive' => 0,
                                'hasRole' => 1
                            ]);
                        }
                        DB::commit();
                    }
                }

                return response()->json(['success' => 'Employé ajouté avec succès !']);
            } catch (Exception $e) {
                DB::rollBack();
                return response()->json(['error' => 'Erreur inconnue']);
            }
        }
    }

    public function getApprenantEtp($idProjet)
    {
        $employesIds = DB::table('detail_apprenant_inters')->where('idEtp', auth()->user()->id)->where('idProjet', $idProjet)->pluck('idEmploye');
        $employes = DB::table('v_employe_alls')->select('*')->where('idCustomer', auth()->user()->id)->where('role_id', 4)->whereNotIn('idEmploye', $employesIds)->get();

        return response()->json(['employes' => $employes]);
    }

    public function getApprenantProjectInter($idProjet)
    {
        $employes = DB::table('v_employe_alls as VE')->join('detail_apprenant_inters as D', 'D.idEmploye', '=', 'VE.idEmploye')->select('VE.*')->where('VE.idCustomer', auth()->user()->id)->where('VE.role_id', 4)->where('D.idProjet', $idProjet)->get();
        return response()->json(['list_employes' => $employes]);
    }

    public function getNbPlaceApprenantAdded($idProjet)
    {
        $nb_place = DB::table('detail_apprenant_inters')->where('idEtp', auth()->user()->id)->where('idProjet', $idProjet)->count();
        return response()->json(['nb_place' => $nb_place]);
    }

    public function deleteEmployeEtp($id)
    {
        try {
            DB::beginTransaction();

            $assigned_project = DB::table('detail_apprenants')
                ->where('idEmploye', $id)
                ->exists();
            if (!$assigned_project) {
                DB::table('c_emps')->where('idEmploye', $id)->delete();
                DB::table('employes')->where('idEmploye', $id)->delete();
                DB::table('role_users')->where('user_id', $id)->delete();
            }

            $status = $assigned_project ? 'error_etp' : 'success_etp';
            $message = $assigned_project ? 'Suppression impossible' : 'Suppression succes';

            DB::commit();
            return back()->with($status, $message);
        } catch (Exception $e) {
            DB::rollBack();
            return $e->getMessage();
        }
    }
}
