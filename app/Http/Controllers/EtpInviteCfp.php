<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator;

class EtpInviteCfp extends Controller
{
    public function idEtp()
    {
        $customer = DB::select("SELECT employes.idEmploye, employes.idCustomer, customers.idTypeCustomer FROM employes INNER JOIN customers ON employes.idCustomer = customers.idCustomer WHERE idEmploye = ?", [Auth::user()->id]);
        return $customer[0]->idCustomer;
    }

    public function index()
    {
        $countOnCollab = DB::select("SELECT COUNT(idEtp) AS nbEtp FROM v_collaboration_cfp_etps WHERE idCfp = ? AND activiteCfp = ? AND activiteEtp = ? AND isSent = ?", [$this->idEtp(), 1, 1, 0]);
        $countSentInvitations = DB::select("SELECT COUNT(idEtp) AS nbEtp FROM v_collaboration_cfp_etps WHERE idCfp = ? AND activiteCfp = ? AND activiteEtp = ? AND isSent = ?", [$this->idEtp(), 1, 0, 1]);
        $countAllCfp = DB::table('v_collaboration_etp_cfps')
            ->select('etp_initial_name', 'etp_name', 'etp_logo', 'etp_description', 'etp_phone', 'etp_addr_lot', 'etp_site_web', 'etp_email', 'idEtp', 'idCfp', 'activiteCfp', 'activiteEtp', 'dateInvitation', 'etp_referent_name', 'etp_referent_firstname', 'etp_referent_fonction', 'etp_referent_phone')
            ->where('idEtp', $this->idEtp())
            ->get();
        // $countAllEtps = DB::select("SELECT COUNT(idEtp) AS nbEtp FROM v_collaboration_cfp_etps WHERE idCfp = ? AND activiteCfp = ? AND activiteEtp = ? AND isSent = ?");

        $onCollab = DB::table('v_collaboration_etp_cfps')
            ->select('etp_initial_name', 'etp_name', 'etp_logo', 'etp_description', 'etp_phone', 'etp_addr_lot', 'etp_site_web', 'etp_email', 'idEtp', 'idCfp', 'activiteCfp', 'activiteEtp', 'dateInvitation', 'etp_referent_name', 'etp_referent_firstname', 'etp_referent_fonction', 'etp_referent_phone')
            ->where('idEtp', $this->idEtp())
            ->where('activiteCfp', 1)
            ->where('activiteEtp', 1)
            ->where('isSent', 0)
            ->get();

        $allCfps = DB::table('v_collaboration_etp_cfps')
            ->select('etp_initial_name', 'etp_name', 'etp_logo', 'etp_description', 'etp_phone', 'etp_addr_lot', 'etp_site_web', 'etp_email', 'idEtp', 'idCfp', 'activiteCfp', 'activiteEtp', 'dateInvitation', 'etp_referent_name', 'etp_referent_firstname', 'etp_referent_fonction', 'etp_referent_phone')
            ->where('idEtp', $this->idEtp())
            ->orderBy('etp_name', 'ASC')
            ->get();

        // Trouver les lettres ayant des données
        $enabledLetters = [];
        foreach (range('A', 'Z') as $letter) {
            if ($allCfps->first(fn($etp) => strtoupper(substr($etp->etp_name, 0, 1)) === $letter)) {
                $enabledLetters[] = $letter;
            }
        }

        // Vérifier les chiffres
        if ($allCfps->first(fn($etp) => preg_match('/^[0-9]/', $etp->etp_name))) {
            $enabledLetters[] = '0-9';
        }

        // Trouver la première lettre ayant des données
        $firstLetter = $enabledLetters[0] ?? null;

        // Filtrer les entreprises par la première lettre valide
        if ($firstLetter === '0-9') {
            $filteredCfps = $allCfps->filter(fn($etp) => preg_match('/^[0-9]/', $etp->etp_name));
        } else {
            $filteredCfps = $allCfps->filter(fn($etp) => strtoupper(substr($etp->etp_name, 0, 1)) === $firstLetter);
        }

        $sentInvitations = DB::table('v_collaboration_etp_cfps')
            ->select('etp_initial_name', 'etp_name', 'etp_logo', 'etp_description', 'etp_phone', 'etp_addr_lot', 'etp_site_web', 'etp_email', 'idEtp', 'idCfp', 'activiteCfp', 'activiteEtp', 'dateInvitation', 'etp_referent_name', 'etp_referent_firstname', 'etp_referent_fonction', 'etp_referent_phone')
            ->where('idEtp', $this->idEtp())
            ->where('activiteCfp', 1)
            ->where('activiteEtp', 0)
            ->where('isSent', 1)
            ->get();
        // dd($allCfps);
        return view('ETP.collaborations.index', compact([
            'onCollab',
            'sentInvitations',
            'countOnCollab',
            'countSentInvitations',
            'allCfps',
            'filteredCfps',
            'firstLetter',
            'enabledLetters',
            'countAllCfp'
        ]));
    }

    public function getEtpDetail($idEtp)
    {
        $etp = DB::table('customers')
            ->select('idCustomer', 'nif AS customer_rcs', 'customerName AS customer_name', 'customerEmail AS customer_email')
            ->where('idCustomer', $idEtp)
            ->first();

        return response()->json(['etp' => $etp]);
    }

    public function searchName(string $name)
    {
        $etps = DB::table('v_collaboration_etp_cfps')
            ->select('etp_initial_name', 'etp_name', 'etp_logo', 'etp_description', 'etp_phone', 'etp_addr_lot', 'etp_site_web', 'etp_email', 'idEtp', 'idCfp', 'activiteCfp', 'activiteEtp', 'dateInvitation', 'etp_referent_name', 'etp_referent_firstname', 'etp_referent_fonction', 'etp_referent_phone')
            ->where('idEtp', $this->idEtp())
            ->where('etp_name', 'like', '%' . $name . '%')
            ->get();

        return response()->json(['etps' => $etps]);
    }

    public function getAllEtps()
    {
        $etps = DB::table('v_collaboration_etp_cfps')
            ->select('idEtp', 'etp_name', 'etp_email', 'etp_initial_name', 'etp_logo')
            ->where('idEtp', $this->idEtp())
            ->orderBy('etp_name', 'asc')
            ->get();

        return response()->json(['etps' => $etps]);
    }

    public function edit($idEtp)
    {
        $etp = DB::table('v_collaboration_etp_cfps')
            ->select('idEtp', 'etp_name', 'etp_email', 'etp_initial_name', 'etp_nif', 'etp_stat', 'etp_rcs', 'etp_addr_lot', 'etp_addr_quartier', 'etp_addr_code_postal', 'etp_phone', 'etp_logo', 'etp_referent_name', 'etp_referent_firstname', 'etp_referent_fonction')
            ->where('idEtp', $idEtp)
            ->first();

        return response()->json(['etp' => $etp]);
    }

    public function update(Request $req, $idEtp)
    {
        $validate = Validator::make($req->all(), [
            'etp_name' => 'required|min:2|max:200',
            'etp_email' => 'required|email',
            'etp_referent_name' => 'required|min:2|max:200'
        ]);

        if ($validate->fails()) {
            return response()->json(['error' => $validate->messages()]);
        } else {
            try {
                DB::beginTransaction();
                DB::table('customers')
                    ->join('users', 'users.id', 'customers.idCustomer')
                    ->where(function ($query) use ($idEtp) {
                        $query->where('customers.idCustomer', $idEtp)
                            ->where('users.id', $idEtp);
                    })
                    ->update([
                        'customers.nif' => $req->etp_nif,
                        'customers.stat' => $req->etp_stat,
                        'customers.rcs' => $req->etp_rcs,
                        'customers.customerName' => $req->etp_name,
                        'customers.customerPhone' => $req->etp_phone,
                        'customers.customerEmail' => $req->etp_email,
                        'customers.customer_addr_lot' => $req->etp_addr_lot,
                        'customers.customer_addr_quartier' => $req->etp_addr_quartier,
                        'customers.customer_addr_code_postal' => $req->etp_addr_code_postal,
                        'users.name' => $req->etp_referent_name,
                        'users.firstName' => $req->etp_referent_firstname,
                        'users.fonction' => $req->etp_referent_fonction,
                        'users.email' => $req->etp_email
                    ]);
                DB::commit();

                return response()->json(['success' => 'Opération effectuée avec succès']);
            } catch (Exception $e) {
                DB::rollBack();
                return response()->json($e->getMessage());
            }
        }
    }

    public function updateLogo(Request $req, $idEtp)
    {
        $etp = DB::table('customers')->select('logo')->where('idCustomer', $idEtp)->first();

        if ($etp != null) {
            $folder = 'img/entreprises/' . $etp->logo;

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

            DB::table('customers')->where('idCustomer', $idEtp)->update([
                'logo' => $imageName,
            ]);
            return response()->json([
                'success' => 'Image Uploaded Successfully',
                'imageName' =>  $imageName
            ]);
        }
    }

    public function getAllFrais()
    {
        $frais = DB::table('frais')
            ->select('idFrais', 'Frais', 'exemple')
            ->get();

        return response()->json(['frais' => $frais]);
    }
}
