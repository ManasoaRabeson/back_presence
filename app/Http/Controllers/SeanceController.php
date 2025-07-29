<?php

namespace App\Http\Controllers;

use App\Mail\InvitationCalendar;
use App\Mail\SendInvitationCalendar;
use AWS\CRT\HTTP\Response;
use Carbon\Carbon;
use Exception;
use Illuminate\Database\Events\TransactionBeginning;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class SeanceController extends Controller
{
    public function idCfp()
    {
        $customer = DB::select("SELECT employes.idEmploye, employes.idCustomer, customers.idTypeCustomer FROM employes INNER JOIN customers ON employes.idCustomer = customers.idCustomer WHERE idEmploye = ?", [Auth::user()->id]);
        return $customer[0]->idCustomer;
    }

    public function getEtpProjectInter($idProjet, $idCfp_inter)
    {
        if ($idCfp_inter == null) {
            $etp = DB::table('v_projet_cfps')
                ->select('etp_name', 'etp_logo', 'etp_initial_name')
                ->where('idProjet', $idProjet)
                ->orderBy('etp_name', 'asc')
                ->get();
        } elseif ($idCfp_inter != null) {
            $etp = DB::table('v_list_entreprise_inter')
                ->select('etp_name', 'etp_logo', 'etp_initial_name')
                ->where('idProjet', $idProjet)
                ->where('etp_name', '!=', 'null')
                ->orderBy('etp_name', 'asc')
                ->get();
        }
        return $etp->toArray();
    }

    // CFP
    public function store(Request $req)
    {
        $req->validate([
            'dateSeance' => 'required|date',
            'heureDebut' => 'required',
            'heureFin' => 'required|after:heureDebut',
            'idProjet' => 'required',
        ]);

        $insert = DB::table('seances')->insert([
            'dateSeance' => $req->dateSeance,
            'heureDebut' => $req->heureDebut,
            'heureFin' => $req->heureFin,
            'idProjet' => $req->idProjet,
            // 'intervalle' => $req->intervalle,
        ]);

        $this->updateProjet($req->idProjet);

        return response()->json($insert);
    }

    private function getNameCfp($idCfp)
    {
       return  DB::table('customers')
        ->select('customerName')
        ->where('idCustomer', $idCfp)->pluck('customerName')
        ->first();       
    }

    public function getAllSeances($idProjet)
    {
        
        $seances = DB::table('v_seances')
            ->select('idSeance', 'dateSeance', 'id_google_seance', 'heureDebut', 'heureFin', 'idSalle', 'idProjet', 'salle_name', 'salle_quartier', 'project_title', 'project_description', 'idModule', 'module_name', 'ville')
            ->where('idCfp', $this->idCfp())
            ->where('idProjet', $idProjet)
            ->get();

        if (count($seances) > 0) {

            foreach ($seances as $seance) {
                $events[] =  [
                    'idSeance' => $seance->idSeance,      //<===== idSeance
                    'idCfp' => $this->idCfp(),
                    'idEtp' => $this->getFieldsProject($seance->idProjet)->idEtp,
                    'end' => $seance->dateSeance . "T" . $seance->heureFin,
                    'start' => $seance->dateSeance . "T" . $seance->heureDebut,
                    'idProjet' => $seance->idProjet,
                    'idSalle' => $seance->idSalle,
                    'idModule' => $seance->idModule,
                    'text' => $seance->project_title,
                    'description' => $seance->project_description,
                    'idCalendar' => $seance->id_google_seance,      //id reliant à Google calendar
                    'salle' => $seance->salle_name,
                    'module' => $seance->module_name,
                    'ville' => $seance->ville,
                    'formateurs' => $this->getFormProject($seance->idProjet),
                    'apprCount' => $this->getApprenantProject($seance->idProjet),
                    'imgModule' => $this->getFieldsProject($seance->idProjet)->module_image,
                    'statut' => $this->getFieldsProject($seance->idProjet)->project_status,
                    'nameEtp' => $this->getFieldsProject($seance->idProjet)->etp_name,
                    'nameEtps' => $this->getEtpProjectInter($seance->idProjet, $this->idCfp()),
                    'paiementEtp' => $this->getFieldsProject($seance->idProjet)->paiement,
                    'typeProjet' => $this->getFieldsProject($seance->idProjet)->project_type,
                    'nameCfp' => $this->getNameCfp($this->idCfp()),

                ];
            }
        } else {
            return response()->json(['pas de donnee']);
        }
        return response()->json(['seances' => $events]);
    }

    public function getInfoSeances($idProjet)
    {
        $seances = DB::table('v_seances')
            ->select('idSeance', 'dateSeance', 'id_google_seance', 'heureDebut', 'heureFin', 'idSalle', 'idProjet', 'salle_name', 'salle_quartier', 'project_title', 'project_description', 'idModule', 'module_name', 'ville')
            ->where('idCfp', $this->idCfp())
            ->where('idProjet', $idProjet)
            ->groupBy('idProjet')
            ->get();

        if (count($seances) > 0) {

            foreach ($seances as $seance) {
                $event[] =  [

                    'end' => $seance->dateSeance . "T" . $seance->heureFin,
                    'start' => $seance->dateSeance . "T" . $seance->heureDebut,
                    'idProjet' => $seance->idProjet,
                    'idSalle' => $seance->idSalle,
                    'idModule' => $seance->idModule,
                    'text' => $seance->project_title,
                    'description' => $seance->project_description,
                    'idCalendar' => $seance->id_google_seance,      //id reliant à Google calendar
                    'salle' => $seance->salle_name,
                    'module' => $seance->module_name,
                    'ville' => $seance->ville,
                    'formateurs' => $this->getFormProject($seance->idProjet),
                    'apprCount' => $this->getApprenantProject($seance->idProjet),
                    'imgModule' => $this->getFieldsProject($seance->idProjet)->module_image,
                    'statut' => $this->getFieldsProject($seance->idProjet)->project_status,
                    'nameEtp' => $this->getFieldsProject($seance->idProjet)->etp_name,
                    'nameEtps' => $this->getEtpProjectInter($seance->idProjet, $this->idCfp()),
                    'paiementEtp' => $this->getFieldsProject($seance->idProjet)->paiement,
                    'typeProjet' => $this->getFieldsProject($seance->idProjet)->project_type,

                ];
            }
        } else {
            return response()->json(['pas de donnee']);
        }
        return response()->json(['seance' => $event]);
    }

    public function getFormProject($idProjet)
    {
        $forms = DB::table('v_formateur_cfps')
            ->select('idFormateur', 'name AS form_name', 'firstName AS form_firstname', 'photoForm AS form_photo', 'initialNameForm AS form_initial_name', 'email')
            ->groupBy('idFormateur', 'name', 'firstName', 'photoForm', 'initialNameForm')
            ->where('idProjet', $idProjet)->get();

        return $forms->toArray();
    }

    public function getApprenantProject($idProjet)
    {
        $apprs = DB::table('v_list_apprenants')
            ->select('idEmploye', 'emp_initial_name', 'emp_name', 'emp_firstname', 'emp_email', 'emp_photo', 'emp_matricule', 'emp_phone', 'etp_name')
            ->where('idProjet', $idProjet)
            ->orderBy('emp_name', 'asc')
            ->get();

        return count($apprs);
    }

    public function getFieldsProject($idProjet)
    {

        $projet = DB::table('v_projet_cfps')
            ->select('idProjet', 'dateDebut', 'dateFin', 'project_title', 'etp_name', 'ville', 'project_status', 'project_type', 'module_image', 'paiement', 'project_reference', 'modalite', 'idEtp')
            ->where('idProjet', $idProjet)
            ->first();
        return $projet;
    }

    public function update(Request $req, $idSeance)
    {
        /*$req->validate([
            'dateSeance' => 'required|after_or_equal:today',
            'heureDebut' => 'required',
            'heureFin' => 'required|after:heureDebut',
            //'idFormateur' => 'required',
        ]);*/

        $update = DB::table('seances')
            ->where('idSeance', $idSeance)
            ->update([
                'dateSeance' => Carbon::parse($req->dateSeance)->format('Y-m-d'),
                'heureDebut' => $req->heureDebut,
                'heureFin' =>   $req->heureFin,

                //'id_google_seance' => $req->id_google_seance,
            ]);

        if ($update == 1) {
            $idProjet = DB::table('seances')->where('idSeance', $idSeance)->value('idProjet');
            $this->updateProjet($idProjet);
            return response()->json(['success' => 'Succès...']);
        } else {
            return response()->json(['error' => 'Erreur inconnue !']);
        }
    }

    public function destroy($idSeance)
    {
        $queryPresence = DB::table('emargements')->where('idSeance', $idSeance);

        $checkPresence = $queryPresence->get();
        $idProjet = DB::table('seances')->where('idSeance', $idSeance)->value('idProjet');
        $projet_count = DB::table('seances')->where('idProjet', $idProjet)->get();

        try {
            if (count($checkPresence) < 1) {
                DB::table('seances')->where('idSeance', $idSeance)->delete();
                if (count($projet_count) > 1) {
                    $this->updateProjet($idProjet);
                }
                return response()->json(['success' => 'Succès']);
            } elseif (count($checkPresence) > 0) {
                DB::beginTransaction();
                $queryPresence->delete();
                DB::table('seances')->where('idSeance', $idSeance)->delete();
                DB::commit();
                if (count($projet_count) > 1) {
                    $this->updateProjet($idProjet);
                }
                return response()->json(['success' => 'Succès']);
            }
        } catch (Exception $th) {
            return response()->json(['error' => $th->getMessage()]);
        }
    }
    // Récupère le dernier élément de la table seances
    public function getLastFieldSeances()
    {

        $lastRecord = DB::table('seances')->latest('idSeance')->first();

        return response()->json(['seance' => $lastRecord]);
    }

    // Récupère le dernier élément de la vue v_seances
    public function getLastFieldVueSeances()
    {

        $lastVueSeance = DB::table('v_seances')->latest('idSeance')->first();

        return response()->json(['seance' => $lastVueSeance]);
    }

    //Update projectDate
    private function updateProjet($idProjet)
    {
        $dateDebut = DB::table('seances')->where('idProjet', $idProjet)->value(DB::raw('MIN(dateSeance) as dateDebut'));
        $dateFin = DB::table('seances')->where('idProjet', $idProjet)->value(DB::raw('MAX(dateSeance) as dateFin'));
        DB::table('projets')
            ->where('idProjet', $idProjet)
            ->update([
                'dateDebut' => $dateDebut,
                'dateFin' =>   $dateFin
            ]);
    }

    public function getAll($idProjet)
    {
        $seances = DB::table('v_seances')
            ->select('idProjet', 'idSeance', 'dateSeance', 'heureDebut', 'heureFin', 'initialNameForm', 'nameForm', 'firstNameForm', 'photoForm', 'nomSalle', 'quartier', 'ville', 'moduleName')
            ->where('idCfp', $this->idCfp())
            ->where('idProjet', $idProjet)
            ->get();

        return response()->json(['seances' => $seances]);
    }

    // ETP
    public function getAllEtp($idProjet)
    {
        $seances = DB::table('v_union_seanceEtps')
            ->select('idProjet', 'idSeance', 'dateSeance', 'heureDebut', 'heureFin', 'initialNameForm', 'nameForm', 'firstNameForm', 'photoForm', 'salle', 'quartier', 'ville', 'moduleName')
            ->where('idEtp', $this->idCfp())
            ->where('idProjet', $idProjet)
            ->get();

        return response()->json(['seances' => $seances]);
    }

    public function getInsert()
    {
        $foramteurs = DB::table('v_formateur_cfps')
            ->select('idFormateur', 'name', 'firstName')
            ->where('idCfp', $this->idCfp())
            ->where('isActiveCfp', 1)
            ->where('isActiveFormateur', 1)
            ->get();

        $salles = DB::table('villes')
            ->join('salles', 'salles.idVille', 'villes.idVille')
            ->select('salles.idSalle', 'salles.nomSalle', 'villes.ville')
            ->where('idCustomer', $this->idCfp())
            ->get();

        return response()->json([
            'formateurs' => $foramteurs,
            'salles' => $salles
        ]);
    }

    public function edit($idSeance)
    {
        $seance = DB::table('seances')
            ->select('idProjet', 'idSeance', 'dateSeance', 'heureDebut', 'heureFin')
            ->where('idSeance', $idSeance)
            ->first();

        $formateurs = DB::table('v_formateur_cfps')
            ->select('idFormateur', 'name', 'firstName')
            ->where('idCfp', $this->idCfp())
            ->where('isActiveCfp', 1)
            ->where('isActiveFormateur', 1)
            ->get();

        $salles = DB::table('villes')
            ->join('salles', 'salles.idVille', 'villes.idVille')
            ->select('salles.idSalle', 'salles.nomSalle', 'villes.ville')
            ->where('idCustomer', $this->idCfp())
            ->get();

        return response()->json([
            'seance' => $seance,
            'formateurs' => $formateurs,
            'salles' => $salles
        ]);
    }

    public function getSeanceAndTotalTime($idProjet)
    {
        $seances = DB::table('v_seances')
            ->select('idSeance', 'dateSeance', 'heureDebut', 'heureFin', 'idProjet', 'idModule', DB::raw("TIME_FORMAT(SEC_TO_TIME(TIME_TO_SEC(intervalle_raw)), '%H:%i') AS intervalle_raw"))
            ->where('idProjet', $idProjet)
            ->orderBy('dateSeance', 'asc')
            ->get();
        $nbSeance = count($seances);

        $totalSession = DB::table('v_seances')
            ->selectRaw("IFNULL(TIME_FORMAT(SEC_TO_TIME(SUM(TIME_TO_SEC(intervalle_raw))), '%H:%i'), '00:00') as sumHourSession")
            ->where('idProjet', $idProjet)
            ->groupBy('idProjet')
            ->first();

        return response()->json([
            //'seances' => $seances,
            'nbSeance' => $nbSeance,
            'totalSession' => $totalSession
        ]);
    }

    public function sendInvitationCalendar(Request $req)
    {
        try {

            $emailRefs = $req->referents;
            $emailForms = $req->forms;

            // Mail::to($inviterName1)->send(new SendInvitationCalendar($inviterName1));
            // Mail::to($inviterName2)->send(new SendInvitationCalendar($inviterName1));
            foreach ($emailRefs as $email) {
                Mail::to($email)->send(new SendInvitationCalendar($email));
            }
            foreach ($emailForms as $email) {
                Mail::to($email)->send(new SendInvitationCalendar($email));
            }
            return response([
                'status' => 200,
                'message' => "Invitation envoyée avec succès",
                //'to_email' => $req->email,
                'to_email_ref' => $emailRefs,
                'to_email_form' => $emailForms,

            ]);
        } catch (Exception $e) {
            //DB::rollBack();
            return response()->json(['error' => $e->getMessage()]);
        }
    }

    public function updateIdCalendarLastSession(Request $req)
    {

        $lastSeance = DB::table('seances')
            ->latest('idSeance')
            ->first();

        if ($lastSeance) {
            $update = DB::table('seances')
                ->where('idSeance', $lastSeance->idSeance)
                ->update([
                    'id_google_seance' => $req->idCalendar
                ]);

            return response()->json([
                "success" => "Succès",
                "idCalendar" => $req->idCalendar,
                "update" => $update,
            ]);
        }

        return response()->json([
            "error" => "Aucune séance trouvée",
            "idCalendar" => $req->idCalendar,
        ], 404);
    }

       
    public function updateIdListCalendarSession(Request $req)
    {
   
            $update =  DB::table('seances')
                        ->where('idSeance',$req->idSeance)
                        ->update([
                            'id_google_seance' => $req->idGoogle,
                        ]);
       
       return response()->json([
        "success" => "Succès",
        "idGoogle" => $req->idGoogle,
        "update" => $update,
        ]);
        
    }

    public function getFieldVueSeanceOfId($idSeance)
    {
        $seance = DB::table('v_seances')->where('idSeance', $idSeance)->first();
        return response()->json(['seance' => $seance]);
    }
}
