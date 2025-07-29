<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\VListEtpGrouped;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator;

class ProfilEtpController extends Controller
{

    public function idCustomer()
    {
        $customer = DB::select("SELECT employes.idEmploye, employes.idCustomer, customers.idTypeCustomer FROM employes INNER JOIN customers ON employes.idCustomer = customers.idCustomer WHERE idEmploye = ?", [Auth::user()->id]);
        return $customer[0]->idCustomer;
    }

    public function index()
    {
        //On récupère les infos de l'ETP (LOGO, nom_etp, ...)

        $infoProfilEtp = DB::table('v_detail_customers')
            ->select('idCustomer', 'initialName', 'customerName', 'customer_addr_quartier', 'customer_addr_rue', 'customer_addr_lot', 'customer_addr_code_postal', 'nif', 'stat', 'assujetti', 'customerPhone', 'rcs', 'description', 'siteWeb', 'logo', 'customerEmail', 'customer_slogan')
            ->where('idCustomer', $this->idCustomer())
            ->first();

        //On récupère les infos des référents(photo, nom_referent, ...)

        $referentAll = DB::table('users')
            ->select('users.photo as ref_photo', 'users.id', 'users.phone as ref_phone', 'users.name as ref_name', 'users.firstName as ref_firstname', 'users.matricule as ref_matricule', 'users.email as ref_email', 'employes.idCustomer', DB::raw('LEFT (name, 1) as ref_initial_name'), 'role_users.role_id')
            ->join('employes', 'users.id', '=', 'employes.idEmploye')
            ->join('role_users', 'users.id', '=', 'role_users.user_id')
            ->where('employes.idCustomer', $this->idCustomer())
            ->whereIn('role_users.role_id', [6, 9])
            ->get();

        $id = Auth::user()->id;

        $refConnected = DB::table('users')
            ->select('role_users.role_id')
            ->join('role_users', 'users.id', '=', 'role_users.user_id')
            ->where('users.id',  $id)
            ->where('role_users.role_id', 6)
            ->first();
        $ville_codeds = DB::table('ville_codeds')->get();

        $etps = VListEtpGrouped::with('subscriptions')->where('idEntrepriseParent', Auth::user()->id)->orderBy('etp_name', 'asc')->get();
        $etpCount = count($etps);

        $checkEtp = DB::table('entreprises')->where('idCustomer', Customer::idCustomer())->first();

        return view('ETP.profil.index', compact('infoProfilEtp', 'referentAll', 'id', 'refConnected', 'ville_codeds', 'etps', 'etpCount', 'checkEtp'));
    }


    public function update(Request $req, $idCustomer)
    {
        $validate = Validator::make($req->all(), [
            'customer_name' => 'required|min:2|max:200',
            'customer_email' => 'required|email',
            // 'customer_rcs' => 'required|min:2|max:200',
        ]);

        if ($validate->fails()) {
            return response()->json(['error' => $validate->messages()]);
        } else {
            try {
                DB::beginTransaction();

                DB::table('customers')
                    ->join('users', 'users.id', 'customers.idCustomer')
                    ->where(function ($query) use ($idCustomer) {
                        $query->where('customers.idCustomer', $idCustomer)
                            ->where('users.id', $idCustomer);
                    })
                    ->update([
                        'customers.nif' => $req->customer_nif,
                        'customers.stat' => $req->customer_stat,
                        'customers.rcs' => $req->customer_rcs,
                        'customers.customerName' => $req->customer_name,
                        'customers.customerPhone' => $req->customer_phone,
                        'customers.customerEmail' => $req->customer_email,
                        'customers.customer_addr_lot' => $req->customer_addr_lot,
                        'customers.customer_addr_quartier' => $req->customer_addr_quartier,
                        // 'customers.customer_addr_code_postal' => $req->customer_addr_code_postal,
                        'customers.idVilleCoded' => $req->customer_ville_id,
                        'customers.description' => $req->customer_description,
                        'customers.siteWeb' => $req->customer_site_web,
                        'customers.customer_slogan' => $req->customer_slogan,
                        'users.email' => $req->customer_email
                    ]);
                DB::commit();

                return response()->json(['success' => 'Opération effectuée avec succès']);
            } catch (Exception $e) {
                DB::rollBack();
                return response()->json($e->getMessage());
            }
        }
    }

    public function updateLogo(Request $req, $idCustomer)
    {
        $customer = DB::table('customers')->select('logo')->where('idCustomer', $idCustomer)->first();

        if ($customer != null) {
            $folder = 'img/entreprises/' . $customer->logo;

            if (File::exists($folder)) {
                File::delete($folder);
            }

            $folderPath = public_path('img/entreprises/');

            $image_parts = explode(";base64,", $req->image);
            $image_type_aux = explode("image/", $image_parts[0]);
            $image_type = $image_type_aux[1];
            $image_base64 = base64_decode($image_parts[1]);

            $imageName = uniqid() . '.webp';
            $imageFullPath = $folderPath . $imageName;

            file_put_contents($imageFullPath, $image_base64);

            DB::table('customers')->where('idCustomer', $idCustomer)->update([
                'logo' => $imageName,
            ]);
            return response()->json([
                'success' => 'Image Uploaded Successfully',
                'imageName' =>  $imageName
            ]);
        }
    }
}
