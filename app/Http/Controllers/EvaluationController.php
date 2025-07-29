<?php

namespace App\Http\Controllers;

use App\Mail\EvaluationFroid;
use App\Models\Customer;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;

use function PHPUnit\Framework\returnSelf;

class EvaluationController extends Controller
{
    // Employes
    // Check evalChaud
    public function checkEval($idProjet, $idEmploye)
    {
        // Vérifie si l'employé a une évaluation
        $hasEvaluation = DB::table('eval_chauds')
            ->where('idProjet', $idProjet)
            ->where('idEmploye', $idEmploye)
            ->exists();

        // Récupère les types de questions (hors type 5)
        $typeQuestions = DB::table('type_questions')
            ->where('idTypeQuestion', '<>', 5)
            ->get(['idTypeQuestion', 'typeQuestion']);

        // Récupère toutes les questions
        $questions = DB::table('questions')
            ->get(['idQuestion', 'question', 'idTypeQuestion']);

        // Si l'évaluation existe, on récupère les autres informations
        if ($hasEvaluation) {
            $evaluation = DB::table('v_evaluation_alls')
                ->where('idProjet', $idProjet)
                ->where('idEmploye', $idEmploye)
                ->first(['com1', 'com2', 'generalApreciate', 'idValComment']);

            $notes = DB::table('v_evaluation_alls')
                ->where('idProjet', $idProjet)
                ->where('idEmploye', $idEmploye)
                ->get(['note', 'idQuestion']);

            $examiner = DB::table('v_evaluation_alls')
                ->where('idProjet', $idProjet)
                ->where('idEmploye', $idEmploye)
                ->distinct()
                ->get(['idEmploye', 'name_examiner', 'firstname_examiner']);

            $projet = DB::table('v_projet_cfps')
                ->where('idProjet', $idProjet)
                ->first(['idProjet']);

            return response()->json([
                'checkEval' => 1,
                'typeQuestions' => $typeQuestions,
                'questions' => $questions,
                'notes' => $notes,
                'one' => $evaluation,
                'examiner' => $examiner,
                'projet' => $projet
            ]);
        }

        // Si aucune évaluation trouvée
        return response()->json([
            'checkEval' => 0,
            'typeQuestions' => $typeQuestions,
            'questions' => $questions
        ]);
    }


    public function satisfaction()
    {
        return view('employes.evaluations.components');
    }

    public function store(Request $req)
    {
        $req->validate([
            'idProjet' => 'required|integer|exists:projets,idProjet',
            'com1' => 'max:255',
            'com2' => 'max:255',
            'idValComment' => 'max:255',
            'generalApreciate' => 'required|integer',
            'idQuestion' => 'required',
            'eval_note' => 'required'
        ]);

        $check = DB::select("SELECT idProjet, idEmploye FROM eval_chauds WHERE idProjet = ? AND idEmploye = ? GROUP BY idProjet, idEmploye", [$req->idProjet, $req->idEmploye]);
        $checkEval = count($check);

        if ($checkEval <= 0) {
            foreach ($req->idQuestion as $key => $value) {
                $insert = DB::table('eval_chauds')->insert([
                    'idProjet' => $req->idProjet,
                    'idEmploye' => $req->idEmploye,
                    'idExaminer' => Auth::user()->id,
                    'idValComment' => $req->idValComment ? $req->idValComment : 'Pas de réponse',
                    'idQuestion' => $req->idQuestion[$key],
                    'note' => $req->eval_note[$key],
                    'com1' => $req->com1 ? $req->com1 : 'Pas de réponse',
                    'com2' => $req->com2 ? $req->com2 : 'Pas de réponse',
                    'generalApreciate' => $req->generalApreciate,
                ]);
            }

            if ($insert) {
                // return response()->json(['success' => "Succès"]);
                return back()->with('message', 'Operation Successful !');
            } else {
                return response()->json(['error' => "Erreur lors de l'insertion des données !"]);
            }
        } else {
            $typeQuestions = DB::select("SELECT idTypeQuestion, typeQuestion FROM type_questions WHERE idTypeQuestion <> 4");
            $questions = DB::select("SELECT idQuestion, question, idTypeQuestion FROM questions");
            $notes = DB::select('select idQuestion, note from v_evaluation_alls where idProjet = ? AND idEmploye = ?', [$req->idProjet, Auth::user()->id]);
            $one = DB::select("SELECT idEmploye, com1, com2, generalApreciate FROM v_evaluation_alls WHERE idProjet = ? AND idEmploye = ? GROUP BY com1, com2, generalApreciate", [$req->idProjet, Auth::user()->id]);

            $project = DB::table('projets')->select('idProjet')->where('idProjet', $req->idProjet)->first();

            return response()->json([
                'typeQuestions' => $typeQuestions,
                'questions' => $questions,
                'notes' => $notes,
                'one' => $one,
                'project' => $project
            ]);
        }
    }

    // CFP
    public function evalCfp($idProjet, $idEmploye)
    {
        $evaluation = DB::table('v_evaluation_alls')
            ->select('idEmploye', 'idProjet', 'typeQuestion', 'idQuestion', 'question', 'note', 'generalApreciate')
            ->where('idProjet', $idProjet)
            ->where('idEmploye', $idEmploye)
            ->get();

        $countEvaluation = DB::table('v_evaluation_alls')
            ->select('idEmploye', 'idProjet', 'typeQuestion', 'idQuestion', 'question', 'note', 'generalApreciate')
            ->where('idProjet', $idProjet)
            ->where('idEmploye', $idEmploye)
            ->count();

        $generalAppreciate = DB::table('v_evaluation_alls')
            ->select('idEmploye', 'idProjet', 'generalApreciate')
            ->groupBy('idEmploye', 'idProjet', 'generalApreciate')
            ->where('idProjet', $idProjet)
            ->where('idEmploye', $idEmploye)
            ->first();

        $typeQuestions = DB::select("SELECT idTypeQuestion, typeQuestion FROM type_questions WHERE idTypeQuestion <> 4");
        $questions = DB::select("SELECT idQuestion, question, idTypeQuestion FROM questions");

        return response()->json([
            'evaluation' => $evaluation,
            'countEvaluation' => $countEvaluation,
            'generalAppreciate' => $generalAppreciate,
            'typeQuestions' => $typeQuestions,
            'questions' => $questions,
        ]);
    }

    // ETP
    public function evalEtp($idProjet, $idSession, $idEmploye)
    {
        $typeQuestions = DB::select("SELECT idTypeQuestion, typeQuestion FROM type_questions");
        $questions = DB::select("SELECT idQuestion, question, idTypeQuestion FROM questions");
        $checkEval = DB::select('SELECT eval_chauds.idSession, sessions.idProjet FROM eval_chauds 
            INNER JOIN sessions ON eval_chauds.idSession = sessions.idSession
            WHERE eval_chauds.idSession = ? AND sessions.idProjet = ? AND idEmploye = ? GROUP BY eval_chauds.idSession, sessions.idProjet', [$idSession, $idProjet, $idEmploye]);
        $evalChecked = count($checkEval);

        if ($evalChecked <= 0) {
            return back()->with('errorEvaluate', 'Evaluation indisponible');
        } else {
            $notes = DB::select('select idQuestion, note from v_evaluation_alls where idSession = ? and idProjet = ? AND idEmploye = ?', [$idSession, $idProjet, $idEmploye]);
            $one = DB::select("SELECT idEmploye, com1, com2, generalApreciate FROM v_evaluation_alls WHERE idSession = ? AND idProjet = ? AND idEmploye = ? GROUP BY com1, com2, generalApreciate", [$idSession, $idProjet, $idEmploye]);

            $project = DB::table('v_union_sessions')
                ->select('idProjet', 'idSession')
                ->where('idSession', $idSession)
                ->where('idProjet', $idProjet)
                ->first();

            return view('ETP.evaluations.index', compact(['questions', 'typeQuestions', 'evalChecked', 'notes', 'one', 'project']));
        }
    }

    // Formateur
    // EvaluationChaud
    public function evalForm($idProjet, $idEmploye)
    {
        $checkEval = DB::table('eval_chauds')
            ->select('idProjet', 'idEmploye')
            ->where('idProjet', $idProjet)
            ->where('idEmploye', $idEmploye)
            ->groupBy('idProjet', 'idEmploye')
            ->count();

        if ($checkEval <= 0) {
            return response()->json(['error' => 'Erreur inconnue !']);
        } else {
            $typeQuestions = DB::select("SELECT idTypeQuestion, typeQuestion FROM type_questions WHERE idTypeQuestion <> 4");
            $questions = DB::select("SELECT idQuestion, question, idTypeQuestion FROM questions");
            $evaluation = DB::select('select idQuestion, note from v_evaluation_alls where idProjet = ? AND idEmploye = ?', [$idProjet, $idEmploye]);
            $generalAppreciate = DB::select("SELECT idEmploye, com1, com2, generalApreciate FROM v_evaluation_alls WHERE idProjet = ? AND idEmploye = ? GROUP BY com1, com2, generalApreciate", [$idProjet, $idEmploye]);

            $project = DB::table('projets')->select('idProjet')->where('idProjet', $idProjet)->first();

            return response()->json([
                'typeQuestions' => $typeQuestions,
                'questions' => $questions,
                'evaluation' => $evaluation,
                'generalAppreciate' => $generalAppreciate[0],
                'project' => $project
            ]);
        }
    }

    // Eval chaud
    public function pdfForm($idProjet, $idEmploye)
    {
        $projet = DB::table('v_head_evals')
            ->select('idProjet', 'dateDebut', 'dateFin', 'ville', 'nomSalle', 'customerName', 'moduleName', 'formName', 'formFirstName', 'empName', 'empFirstName')
            ->where('idProjet', $idProjet)
            ->where('idEmploye', $idEmploye)
            ->first();

        $typeQuestions = DB::select("SELECT idTypeQuestion, typeQuestion FROM type_questions WHERE idTypeQuestion <> 4");
        $questions = DB::select("SELECT idQuestion, question, idTypeQuestion FROM questions");

        $notes = DB::select('select idQuestion, note from v_evaluation_alls where idProjet = ? AND idEmploye = ?', [$idProjet, $idEmploye]);
        $one = DB::select("SELECT idEmploye, com1, com2, generalApreciate FROM v_evaluation_alls WHERE idProjet = ? AND idEmploye = ? GROUP BY com1, com2, generalApreciate", [$idProjet, $idEmploye]);

        if (count($notes) <= 0 || count($one) <= 0) {
            return back()->with("error", "Evaluation indisponible !");
        } else {
            $pdf = Pdf::loadView('CFP.evaluations.pdf', compact(['projet', 'questions', 'typeQuestions', 'notes', 'one']))->setPaper('a4', 'portrait');

            return $pdf->download('Fiche_evaluation_chaud.pdf');
        }
    }

    public function editEval(Request $req)
    {
        //dd($req->all());
        $req->validate([
            'idProjet' => 'required|integer|exists:projets,idProjet',
            'com1' => 'max:255',
            'com2' => 'max:255',
            'idValComment' => 'max:255',
            'generalApreciate' => 'required|integer',
            'idQuestion' => 'required',
            'eval_note' => 'required'
        ]);


        try {
            foreach ($req->idQuestion as $key => $value) {
                $update = DB::table('eval_chauds')
                    ->where('idProjet', $req->idProjet)
                    ->where('idEmploye', $req->idEmploye)
                    ->where('idQuestion', $req->idQuestion[$key])
                    ->update([
                        'idExaminer' => Auth::user()->id,
                        'idValComment' => $req->idValComment ? $req->idValComment : 'Pas de réponse',
                        'note' => $req->eval_note[$key],
                        'com1' => $req->com1 ? $req->com1 : 'Pas de réponse',
                        'com2' => $req->com2 ? $req->com2 : 'Pas de réponse',
                        'generalApreciate' => $req->generalApreciate,
                    ]);
            }
            return back()->with('message', 'Operation Successful !');
        } catch (Exception $th) {
            return response()->json(['error' => $th->getMessage()]);
        }
    }





    public function save(Request $request)
    {
        $apprenant = DB::table('eval_apprenant')->where('idEmploye', $request->idEmploye)->where('idProjet', $request->idProjet)->first();
        if ($apprenant) {
            $this->update($apprenant->id, $request->before, $request->after);
        } else {
            $this->storeEval($request->idEmploye, $request->idProjet, $request->before, $request->after);
        }
        return response()->json(['success' => 'Apprenant evalué avec succes'], 200);
    }

    private function storeEval($idEmploye, $idProjet, $before, $after)
    {
        DB::table('eval_apprenant')->insert([
            'idEmploye' => $idEmploye,
            'idProjet' => $idProjet,
            'avant' => $before,
            'apres' => $after
        ]);
    }

    private function update($id_eval, $before, $after)
    {
        DB::table('eval_apprenant')->where('id', $id_eval)->update([
            'avant' => $before,
            'apres' => $after
        ]);
    }

    public function get($idEmploye, $idProjet)
    {
        $ratings = DB::table('eval_apprenant')
            ->where('idEmploye', $idEmploye)
            ->where('idProjet', $idProjet)
            ->first(['avant', 'apres']);

        return response()->json($ratings);
    }




    // FROIDS
    public function getAllSelect($table)
    {
        $data = DB::table($table)->select('*')->get();
        return $data;
    }

    // check evaluation à froids if she is already evaluated
    public function checkEvalFroid()
    {
        $check = DB::table('eval_froids')
            ->select('idProjet', 'idEmploye')
            ->where('idEmploye', Auth::user()->id)
            ->groupBy('idProjet', 'idEmploye')
            ->pluck('idProjet');

        return $check;
    }

    // check evaluation à froids if she is already sent()
    public function checkEvalFroidSent()
    {
        $check = DB::table('eval_froid_sents')
            ->where('idEtp', Customer::idCustomer())
            ->pluck('idProjet');

        return $check;
    }

    public function index()
    {
        $typeQuestions = $this->getAllSelect('quizz_types');
        $questions = $this->getAllSelect('quizz_colds');
        $notes = $this->getAllSelect('quizz_levels');

        $projets = DB::table('v_projet_emps')
            ->select('idProjet', 'dateDebut', 'idEtp', 'dateFin', 'cfp_name', 'module_name', 'etp_name', 'ville', 'project_status', 'project_description', 'project_type', 'paiement', DB::raw('DATE_FORMAT(dateDebut, "%M, %Y") AS headDate'), 'module_image', 'etp_logo', 'etp_initial_name', 'salle_name', 'salle_quartier', 'salle_code_postal', 'ville', 'idCfp_inter', 'modalite', 'total_ht', 'total_ttc', 'idModule', 'project_inter_privacy', 'sub_name', 'idSubContractor', 'idCfp', 'cfp_name')
            ->where('idEmploye', Auth::user()->id)
            ->where('project_status', "Terminé")
            ->where(function ($query) {
                $query->whereIn('idProjet', $this->checkEvalFroidSent())
                    ->whereNotIn('idProjet', $this->checkEvalFroid());
            })
            ->orderBy('dateDebut', 'asc')
            ->get();

        return view('employes.evaluations.eval-froid', compact('typeQuestions', 'questions', 'notes', 'projets'));
    }

    public function storeColdEvaluation(Request $req, $idProjet)
    {
        $validate = Validator::make($req->all(), [
            'quizz_level' => 'required',
            'quizz_aspect' => 'required|min:2',
            'quizz_suggestion' => 'required|min:2',
            'global_satisfaction' => 'required|integer',
            'general_recomand' => 'required|integer'
        ]);

        // dd($req->all());

        if ($validate->fails()) {
            return back()->with('error', $validate->messages());
        }

        $check = DB::table('eval_froids')
            ->select('idProjet', 'idEmploye')
            ->where('idProjet', $idProjet)
            ->where('idEmploye', Auth::user()->id)
            ->groupBy('idProjet', 'idEmploye')
            ->get();

        if (count($check) >= 1) {
            return back()->with('error', "Ré-évaluation impossible !");
        }

        try {
            DB::transaction(function () use ($req, $idProjet) {
                for ($i = 0; $i < count($req->quizz_level); $i++) {
                    DB::table('eval_froids')->insert([
                        'idProjet' => $idProjet,
                        'idEmploye' => Auth::user()->id,
                        'idQuizzCold' => $req->idQuizzCold[$i],
                        'note' => $req->quizz_level[$i],
                        'date_added' => Carbon::now(),
                        'general_aspect' => $req->quizz_aspect,
                        'general_suggestion' => $req->quizz_suggestion,
                        'general_satisfaction' => $req->global_satisfaction,
                        'general_recomand' => $req->general_recomand
                    ]);
                }

                // Notification / email ho an'ilay referent hoe "vita sady lasa ilay Evaluation"

                // Mail::to("referent-forma-fusion@test.test")->send();
            });

            dd("mety tsara fa mila rechargéna ilay pejy teo aloha tompoko");
        } catch (Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }
    // Fin evaluation FROID Employes

    // Evaluation à Froid CFP
    public function indexFroid()
    {
        $projetCollections = DB::table('v_projet_cfps')
            ->select('idProjet', 'dateDebut', 'dateFin', 'project_title', 'project_status', 'etp_name', 'ville', 'li_name', 'module_name')
            ->where(function ($query) {
                $query->where('idCfp', Customer::idCustomer())
                    ->orWhere('idCfp_inter', Customer::idCustomer())
                    ->orWhere('idSubContractor', Customer::idCustomer());
            })
            ->where('project_status', "Terminé")
            ->where('module_name', '!=', 'Default module')
            ->orderBy('module_name', 'asc')
            ->get();

        $projets = $projetCollections->map(function ($p) {
            return [
                'idProjet' => $p->idProjet,
                'dateDebut' => $this->formatDate($p->dateDebut),
                'dateFin' => $this->formatDate($p->dateFin),
                'etp_name' => $p->etp_name,
                'module_name' => $p->module_name,
            ];
        });

        // lasa aiza ilay "sous-traitants" ?
        $projectResults = DB::table('v_result_evaluation_froids')
            ->select('idProjet', 'module_name', 'module_image', 'projet_date_debut', 'projet_date_fin', 'sub_name', 'etp_name')
            ->where(function ($query) {
                $query->where('idCfp', Customer::idCustomer())
                    ->orWhere('idSubContractor', Customer::idCustomer());
            })
            ->groupBy('idProjet', 'module_name', 'module_image', 'projet_date_debut', 'projet_date_fin', 'sub_name', 'etp_name')
            ->get();

        return view('CFP.evaluations.eval-froid', compact('projets', 'projectResults'));
    }

    public function getApprenants($idProjet)
    {
        $typeProjet = DB::table('projets')->select('idTypeProjet')->where('idProjet', $idProjet)->first();

        if (!$typeProjet) {
            return response([
                'status' => 404,
                'message' => "Projet introuvable"
            ]);
        }

        switch ($typeProjet->idTypeProjet) {
            case 1:
                $apprenants = DB::table("detail_apprenants as d")
                    ->select('idProjet', 'idEmploye', 'name as emp_name', 'firstName as emp_firstname', 'email as emp_email')
                    ->join('users as u', 'u.id', 'd.idEmploye')
                    ->where('idProjet', $idProjet)
                    ->get();
                break;
            case 2:
                $apprenants = DB::table("detail_apprenant_inters as d")
                    ->select('idProjet', 'idEmploye', 'name as emp_name', 'firstName as emp_firstname', 'email as emp_email')
                    ->join('users as u', 'u.id', 'd.idEmploye')
                    ->where('idProjet', $idProjet)
                    ->get();
                break;
        }

        if (count($apprenants) <= 0) {
            return response([
                'status' => 404,
                'message' => "Aucun apprenant trouvé"
            ]);
        }

        return response([
            'status' => 200,
            'apprenants' => $apprenants
        ]);
    }

    public function sendEvaluation($idProjet)
    {
        $typeProjet = DB::table('projets')->select('idTypeProjet')->where('idProjet', $idProjet)->first();

        $entreprise = DB::table('projets as p')
            ->join('intras as itr', 'p.idProjet', 'itr.idProjet')
            ->select('itr.idEtp')
            ->where('p.idProjet', $idProjet)
            ->first();

        if (!$entreprise) {
            return response([
                'status' => 404,
                'message' => "Entreprise introuvable"
            ]);
        }

        if (!$typeProjet) {
            return response([
                'status' => 404,
                'message' => "Projet introuvable"
            ]);
        }

        switch ($typeProjet->idTypeProjet) {
            case 1:
                $apprenants = DB::table("detail_apprenants as d")
                    ->select('idProjet', 'd.idEmploye', 'name as emp_name', 'firstName as emp_firstname', 'email as emp_email', 'customerEmail as etp_email')
                    ->join('users as u', 'u.id', 'd.idEmploye')
                    ->join('employes as e', 'u.id', 'e.idEmploye')
                    ->join('customers as c', 'e.idCustomer', 'c.idCustomer')
                    ->where('idProjet', $idProjet)
                    ->get();
                break;
            case 2:
                $apprenants = DB::table("detail_apprenant_inters as d")
                    ->select('idProjet', 'd.idEmploye', 'name as emp_name', 'firstName as emp_firstname', 'email as emp_email', 'customerEmail as etp_email')
                    ->join('users as u', 'u.id', 'd.idEmploye')
                    ->join('employes as e', 'u.id', 'e.idEmploye')
                    ->join('customers as c', 'e.idCustomer', 'c.idCustomer')
                    ->where('idProjet', $idProjet)
                    ->get();
                break;
        }


        try {
            DB::transaction(function () use ($apprenants, $idProjet, $entreprise) {
                $check = DB::table('eval_froid_sents')
                    ->where('idProjet', $idProjet)
                    ->where('idEtp', $entreprise->idEtp)
                    ->count();

                if ($check <= 0) {
                    DB::table('eval_froid_sents')
                        ->insert([
                            'idProjet' => $idProjet,
                            'idEtp' => $entreprise->idEtp,
                            'date_sent' => Carbon::now(),
                            'eval_is_sent' => 1
                        ]);
                }

                foreach ($apprenants as $apprenant) {
                    if (isset($apprenant->emp_email)) {
                        Mail::to($apprenant->emp_email)->send(new EvaluationFroid(Customer::getCustomer(Customer::idCustomer())->customer_name));
                    }
                }

                // mbola mila ovaina ito email iray ito

                // atao ahoana ilay apprenants groupe fa otrany tsy mazava tsara ilay izy
                Mail::to($apprenant->etp_email)->send(new EvaluationFroid(Customer::getCustomer(Customer::idCustomer())->customer_name));
            });

            return response([
                'status' => 200,
                'message' => 'Evaluation envoyée avec succès'
            ]);
        } catch (Exception $e) {
            return response([
                'status' => 422,
                'message' => $e->getMessage()
            ]);
        }
    }

    // listes des apprenants pour chaque résultat par projets
    public function getApprenantByProjectResult($idProjet)
    {
        $apprs = DB::table('v_result_evaluation_froids')
            ->select('idProjet', 'idEmploye', 'emp_name', 'emp_firstname', 'emp_email')
            ->where('idProjet', $idProjet)
            ->groupBy('idProjet', 'idEmploye', 'emp_name', 'emp_firstname', 'emp_email')
            ->get();

        if (count($apprs) <= 0) {
            return response([
                'status' => 404,
                'message' => "Aucun apprenant trouvé !"
            ]);
        }

        return response([
            'status' => 200,
            'apprenants' => $apprs
        ]);
    }

    public function getTest($idProjet, $idEmploye)
    {
        $query = DB::table('v_result_evaluation_froids')
            ->select('idQuizzType', 'quiz_type_name', 'quizz_cold_name', 'module_name', 'projet_date_debut', 'projet_date_fin', 'emp_name', 'emp_firstname', 'etp_name', 'general_suggestion', 'general_aspect', 'general_satisfaction', 'general_recomand_libelle', 'note_libelle')
            ->where('idProjet', $idProjet)
            ->where('idEmploye', $idEmploye);

        return $query;
    }

    // listes des evaluations par apprenants
    public function apprenantEvaluationResult($idProjet, $idEmploye)
    {
        $heading = $this->getTest($idProjet, $idEmploye)->first();
        $notes = $this->getTest($idProjet, $idEmploye)->get();

        $pdf = Pdf::loadView('CFP.evaluations.eval-froid-pdf', compact('heading', 'notes'))->setPaper('a4', 'portrait');

        return $pdf->download('evaluation_froid.pdf');
    }

    private function formatDate($date, $type = 'j M Y')
    {
        return Carbon::parse($date)->locale('fr')->translatedFormat($type);
    }
}
