<?php

namespace App\Http\Controllers;

use App\Http\Requests\EmpGrpStoreRequest;
use App\Models\Customer;
use App\Models\Employe;
use App\Models\Entreprise;
use App\Models\RoleUser;
use App\Models\Salle;
use App\Models\User;
use App\Models\VListEtpGrouped;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Response;

class EtpGroupeController extends Controller
{
    public function getCustomer()
    {
        $etps = Customer::get();
        return response()->json(['etps' => $etps]);
    }

    public function getCustomerByNif($nif)
    {
        $customer = DB::table('etp_singles AS es')
            ->join('customers AS c', 'es.idEntreprise', '=', 'c.idCustomer')
            ->join('employes AS e', 'e.idCustomer', '=', 'c.idCustomer')
            ->join('users AS u', 'e.idEmploye', '=', 'u.id')
            ->select(
                'es.idEntreprise',
                'c.customerName',
                'c.nif',
                'c.customerEmail',
                'u.name AS resp_name',
                'u.firstName AS resp_firstname'
            )
            ->where('nif', $nif)
            ->first();

        if ($customer) {
            return response()->json([
                'exists' => true,
                'customer' => $customer
            ]);
        }

        return response()->json(['exists' => false]);
    }

    public function index()
    {
        $etps = VListEtpGrouped::with('subscriptions')->where('idEntrepriseParent', Auth::user()->id)->orderBy('etp_name', 'asc')->get();
        $etpCount = count($etps);

        $checkEtp = DB::table('entreprises')->where('idCustomer', Customer::idCustomer())->first();

        return view('ETP.etp_groupes.index', compact('etps', 'etpCount', 'checkEtp'));
    }

    public function create()
    {
        return view('ETP.etp_groupes.create');
    }

    public function storeLieu($idCustomer){
        DB::transaction(function() use($idCustomer){
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

            Salle::create([
                'salle_name' => 'In situ',
                'idLieu' => $idLieu
            ]);
        });
    }

    public function store(Request $req)
    {
        $validate = Validator::make($req->all(), [
            'etp_nif' => 'required|min:5|max:70',
            'etp_name' => 'required|min:2|max:150',
            'etp_email' => 'required|email',
            'etp_ref_name' => 'required|min:3|max:150'
        ]);

        if ($validate->fails()) {
            return back()->with('error', $validate->messages());
        } else {
            $getEtp = DB::table('customers')
                ->select('idCustomer')
                ->where('nif', $req->etp_nif)
                ->first();

            if ($getEtp) {
                $checkEtp = DB::table('entreprises')->where('idCustomer', $getEtp->idCustomer)->where('idTypeEtp', '!=', 1)->exists();
                $checkEtpGrp = DB::table('etp_groupeds')->where('idEntreprise', $getEtp->idCustomer)->exists();

                if ($checkEtp || $checkEtpGrp) {
                    return redirect()->route('etp.groupes.index')->with('error', 'Erreur inconnue !');
                } else {
                    DB::transaction(function () use ($getEtp) {
                        DB::table('etp_singles')->where('idEntreprise', $getEtp->idCustomer)->delete();
                        DB::table('etp_groupeds')->insert([
                            'idEntreprise' => $getEtp->idCustomer,
                            'idEntrepriseParent' => Auth::user()->id
                        ]);
                        DB::table('entreprises')->where('idCustomer', $getEtp->idCustomer)->update(['idTypeEtp' => 3]);
                    });

                    return redirect()->route('etp.groupes.index')->with('success', 'Entreprise ajouté avec succès');
                }
            } else {
                try {
                    DB::beginTransaction();

                    $user = new User();
                    $user->name = $req->etp_ref_name;
                    $user->email = $req->etp_email;
                    $user->password = Hash::make("1234@#");
                    $user->save();

                    $cst = new Customer();
                    $cst->idCustomer = $user->id;
                    $cst->customerName = $req->etp_name;
                    $cst->customerEmail = $req->etp_email;
                    $cst->idSecteur = 7;
                    $cst->idTypeCustomer = 2;
                    $cst->nif = $req->etp_nif;
                    $cst->idVilleCoded = 1;
                    $cst->save();

                    $idModule = DB::table('mdls')->insertGetId([
                        'moduleName' => "Default module",
                        'idDomaine' => 1,
                        'idCustomer' => $user->id,
                        'idTypeModule' => 2
                    ]);

                    $this->storeLieu($user->id);

                    $idFonction = DB::table('fonctions')->insertGetId([
                        'fonction' => "default_fonction",
                        'idCustomer' => $user->id
                    ]);

                    DB::table('module_internes')->insert(['idModule' => $idModule]);
                    DB::table('entreprises')->insert([
                        'idCustomer' => $user->id,
                        'idTypeEtp' => 3
                    ]);
                    DB::table('etp_groupeds')->insert([
                        'idEntreprise' => $user->id,
                        'idEntrepriseParent' => Auth::user()->id
                    ]);

                    $emp = new Employe();
                    $emp->idEmploye = $user->id;
                    $emp->idNiveau = 6;
                    $emp->idCustomer = $user->id;
                    $emp->idSexe = 1;
                    $emp->idFonction = $idFonction;
                    $emp->save();

                    RoleUser::create([
                        'role_id'  => 6,
                        'user_id'  => $user->id,
                        'isActive' => 1,
                        'hasRole' => 1,
                        'user_is_in_service' => 1
                    ]);

                    DB::commit();

                    return redirect()->route('etp.groupes.index')->with('success', 'Entreprise ajouté avec succès');
                } catch (Exception $e) {
                    DB::rollBack();
                    return back()->with("error", "Une erreur s'est produite, veuillez réessayer plus tard !" . $e->getMessage());
                }
            }
        }
    }

    public function update($idEntreprise)
    {
        $etp = Entreprise::find($idEntreprise);

        if ($etp) {
            if ($etp->idTypeEtp == 1) {
                DB::transaction(function () use ($etp, $idEntreprise) {
                    DB::table('etp_groupes')->insert(['idEntreprise' => $idEntreprise]);
                    DB::table('etp_singles')->where('idEntreprise', $idEntreprise)->delete();

                    $etp->idTypeEtp = 2;
                    $etp->save();
                });
            }

            return back()->with('success', 'Changement avec succès');
        } else {
            return back()->with('error', 'Entreprise introuvable !');
        }
    }

    public function openModalEmp()
    {
        $etp = DB::table('entreprises')->select('idCustomer', 'idTypeEtp')->where('idCustomer', Auth::user()->id)->first();

        if ($etp) {
            return response(['etp' => $etp], Response::HTTP_OK);
        } else {
            return response(['error' => 'Entreprise introuvable'], Response::HTTP_NOT_FOUND);
        }
    }

    public function getFonction()
    {
        $fonction = DB::table('fonctions')->select('idFonction')->where('idCustomer', Customer::idCustomer())->first();

        return $fonction->idFonction;
    }

    public function storeEmploye(Request $request, $idUser)
    {
        $emp = new Employe();
        $emp->idEmploye = $idUser;
        $emp->idSexe = 1;
        $emp->idNiveau = 6;
        $emp->idCustomer = Customer::idCustomer();
        $emp->idFonction = $this->getFonction();
        $emp->save();
    }

    public function storeRoleUser($idUser)
    {
        RoleUser::create([
            'role_id'  => 4,
            'user_id'  => $idUser,
            'isActive' => 1,
            'hasRole' => 1
        ]);
    }

    public function checkGrp()
    {
        $etpGroupe = DB::table('entreprises')
            ->where('idCustomer', Customer::idCustomer())
            ->where('idTypeEtp', 2)
            ->first();

        return $etpGroupe;
    }

    // Ajout employes pour le "Groupe" via l'interface "Groupe d'entreprise"
    public function addEmp(EmpGrpStoreRequest $request)
    {
        if ($this->checkGrp()) {
            try {
                DB::beginTransaction();

                $user = new User();
                $user->matricule = $request->emp_matricule;
                $user->name = $request->emp_name;
                $user->firstName = $request->emp_firstname;
                $user->email = $request->emp_email;
                $user->phone = $request->emp_phone;
                $user->password = Hash::make('0000@#');
                $user->save();

                $this->storeEmploye($request, $user->id);
                $this->storeRoleUser($user->id);

                DB::commit();

                return response()->json([
                    'message' => 'Employés ajouté avec succès',
                    'status' => 200
                ]);
            } catch (Exception $e) {
                DB::rollBack();
                return response()->json([
                    'message' => 'Erreur inconnue',
                    'status' => 401
                ]);
            }
        } else {
            return response([
                'message' => 'Entreprise introuvable !',
                'status' => 404
            ]);
        }
    }
}
