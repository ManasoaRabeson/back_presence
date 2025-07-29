<?php

namespace App\Http\Controllers;

use App\Models\CategoriesReponses;
use App\Models\CreditsWallet;
use App\Models\DomainesFormation;
use App\Models\Qcm;
use App\Models\QcmBareme;
use App\Models\QcmInvitation;
use App\Models\QcmQuestions;
use App\Models\QcmReponses;
use App\Models\ReponsesQcmUsers;
use App\Models\SessionsQcm;
use App\Models\User;
use App\Services\Qcm\QcmCreditService;
use App\Services\Qcm\QcmNavigationService;
use App\Services\Qcm\QcmSessionService;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class QcmController extends Controller
{
    # Services part added 18-02-2025
    private QcmSessionService $sessionService;
    private QcmCreditService $creditService;
    private QcmNavigationService $navigationService;

    public function __construct(
        QcmSessionService $sessionService,
        QcmCreditService $creditService,
        QcmNavigationService $navigationService
    ) {
        $this->sessionService = $sessionService;
        $this->creditService = $creditService;
        $this->navigationService = $navigationService;
    }
    # Services part added 18-02-2025

    /**
     * Fonction menant à l'index de l'utilisateur connecté (v5)
     * 
     * @param $request
     */
    public function index_qcm(Request $request)
    {
        $all_qcm = null; // Initialize the variable to hold the QCMs
        $user = null;    // Initialize the user as null for non-authenticated users
        $roleNames = collect(); // Empty collection for roles if no user is authenticated
        $extends_containt = $this->navigationService->determineLayout(); // Variable pour stocker les extends selon l'utilisateur connecté

        // Récupère l'id de l'entreprise de l'employé si c'est un employé
        $idEtpOfEmp = new CreditsWallet();
        $idEtp = $idEtpOfEmp->getIdEtpByidEmp(Auth::user()->id);
        $walletAuthUser = $idEtpOfEmp->user_credit_walletBasedOnRole(Auth::user()->id); # Pour voir le portefeuille de l'utilisateur connecté

        // Récupérer le domaine sélectionné depuis la requête
        $selectedDomaine = $request->input('domaine');

        // Récupérer tous les domaines pour le filtre
        $domaines = DomainesFormation::orderBy('nomDomaine')->get();

        // Check if the user is authenticated
        if (Auth::check()) {
            // User is authenticated, retrieve the authenticated user's data
            $user = Auth::user();
            $id_auth_user = $user->id;
            $roleNames = $user->roles->pluck('roleName');

            // Initialiser la requête de base
            $query = Qcm::query();

            // Appliquer le filtre par domaine si sélectionné
            if ($selectedDomaine) {
                $query->whereHas('domaineFormation', function ($q) use ($selectedDomaine) {
                    $q->where('idDomaine', $selectedDomaine);
                });
            }

            // Filtrer selon le rôle
            if ($user->hasRole('Formateur') || $user->hasRole('Cfp') || $user->hasRole('EmployeCfp')) {
                $query->where('user_id', $id_auth_user);
            } else {
                $query->where('statut', 1);
            }

            // Exécuter la requête
            $all_qcm = $query->get();
        } else {
            // Si non authentifié, montrer tous les QCMs actifs
            $query = Qcm::query();
            if ($selectedDomaine) {
                $query->whereHas('domaineFormation', function ($q) use ($selectedDomaine) {
                    $q->where('idDomaine', $selectedDomaine);
                });
            }
            $all_qcm = $query->where('statut', 1)->get();
        }

        return view('TestingCenter.indexTCenter.index_qcm', compact(
            'all_qcm',
            'user',
            'roleNames',
            'extends_containt',
            'id_auth_user',
            'idEtp',
            'walletAuthUser',
            'domaines',
            'selectedDomaine',
        ));
    }

    /**
     * Fonction pour le toggle button
     * 
     * @param $request, $id
     */
    public function updateStatus(Request $request, $id)
    {
        $qcm = Qcm::find($id);

        if (!$qcm) {
            return response()->json(['success' => false, 'message' => 'QCM introuvable.']);
        }

        $qcm->statut = $request->statut;
        $qcm->save();

        return response()->json(['success' => true, 'message' => 'Statut mis à jour avec succès.']);
    }

    /**
     * Fonction menant à l'index de l'utilisateur non connecté (v1)
     * 
     * @param $request
     */
    public function index_qcm_not_auth(Request $request)
    {
        // Récupérer le domaine sélectionné depuis la requête
        $selectedDomaine = $request->input('domaine');

        // Récupérer tous les domaines pour le filtre
        $domaines = DomainesFormation::orderBy('nomDomaine')->get();

        // Récupérer les QCMs selon le filtre
        if ($selectedDomaine) {
            $all_qcm = Qcm::getQcmsByDomaine($selectedDomaine);
        } else {
            $all_qcm = Qcm::getAllPublicQcms();
        }

        return view('TestingCenter.indexTCenter.index_qcm_public', compact(
            'all_qcm',
            'domaines',
            'selectedDomaine'
        ));

        // return response()->json($all_qcm);
    }

    /**
     * Vue du formulaire de création de Qcm (v2)
     */
    public function create_qcm()
    {
        $all_domaines = DomainesFormation::all();
        $all_responses_cat = CategoriesReponses::all(); # toutes les catégories de réponses / sections
        $extends_containt = $this->navigationService->determineLayout(); // Variable pour stocker les extends selon l'utilisateur connecté

        return view('TestingCenter.create_qcm', compact(
            'all_domaines',
            'all_responses_cat',
            'extends_containt'
        ));
    }

    /**
     * Stocker le Qcm créer (v3)
     */
    public function storeQcm(Request $request)
    {
        // Validate the incoming request data
        $validatedData = $request->validate([
            'intituleQCM' => 'required|string|max:255',
            'descriptionQCM' => 'required|string',
            'duree' => 'required|integer',
            'prixUnitaire' => 'required|numeric',
            'idDomaine' => 'required|exists:domaine_formations,idDomaine',
            'sections' => 'required|array',
            'sections.*.categorie_id' => 'required|exists:categories_reponses,idCategorie',
            'sections.*.questions' => 'required|array',
            'sections.*.questions.*.texteQuestion' => 'required|string',
            'sections.*.questions.*.reponses' => 'required|array|min:1',
            'sections.*.questions.*.reponses.*.texteReponse' => 'required|string',
            'sections.*.questions.*.reponses.*.points' => 'required|integer',
            'sections.*.questions.*.reponses.*.categorie_id' => 'required|exists:categories_reponses,idCategorie',
        ], [
            'required' => 'Le champ :attribute est requis.',
            'exists' => 'Le champ :attribute n\'existe pas dans la base de données.',
        ]);

        // Use a transaction to ensure all operations succeed or none
        DB::transaction(function () use ($validatedData) {
            $qcm = Qcm::create([
                'intituleQCM' => $validatedData['intituleQCM'],
                'descriptionQCM' => $validatedData['descriptionQCM'],
                'duree' => $validatedData['duree'] * 60, # Convertir les minutes en secondes
                'prixUnitaire' => $validatedData['prixUnitaire'],
                'idDomaine' => $validatedData['idDomaine'],
                'user_id' => Auth::id(),
            ]);

            // Loop through the sections and create questions with responses
            foreach ($validatedData['sections'] as $sectionData) {
                foreach ($sectionData['questions'] as $questionData) {
                    $question = QcmQuestions::create([
                        'idQCM' => $qcm->idQCM,
                        'texteQuestion' => $questionData['texteQuestion'],
                    ]);

                    $reponses = [];
                    foreach ($questionData['reponses'] as $reponseData) {
                        $reponses[] = [
                            'idQuestion' => $question->idQuestion,
                            'texteReponse' => $reponseData['texteReponse'],
                            'points' => $reponseData['points'],
                            'categorie_id' => $reponseData['categorie_id'],
                            'created_at' => now(),
                            'updated_at' => now(),
                        ];
                    }

                    // Batch insert responses for the current question
                    QcmReponses::insert($reponses);
                }
            }
        });

        return redirect()->route('index.qcm')->with('success', __('QCM créé avec succès.'));
    }

    /**
     * Fonction menant aux détails du qcm en utilisant l'id du qcm concerné (v2)
     * 
     * @param $id
     */
    public function show_qcm_details($id)
    {
        $one_qcm = Qcm::find($id);

        $extends_containt = $this->navigationService->determineLayout(); // Variable pour stocker les extends selon l'utilisateur connecté

        return view('TestingCenter.show_qcm', compact(
            'one_qcm',
            'extends_containt'
        ));
    }

    /**
     * Fonction pour afficher le formulaire pour répondre aux questions avec l'index de la question
     * pour les afffichées un par un (v7)
     * $id -> id du qcm
     * avec débit de crédit, pour pouvoir faire le test qcm
     * 
     * @param $id, $questionIndex = 0, $invitationId = null
     * @return qcm 
     */
    // public function show_qcm_to_respond($id, $questionIndex = 0, $invitationId = null)
    // {
    //     $extends_containt = null; // Variable pour stocker les extends selon l'utilisateur connecté

    //     // Condition pour l'extends selon l'utilisateur connecté
    //     if (Auth::check()) {
    //         $user = Auth::user();
    //         if ($user->hasRole('Formateur')) {
    //             $extends_containt = "layouts.masterForm";
    //         } elseif ($user->hasRole('Formateur interne')) {
    //             $extends_containt = "layouts.masterFormInterne";
    //         } elseif ($user->hasRole('Particulier')) {
    //             $extends_containt = "layouts.masterParticulier";
    //         } elseif ($user->hasRole('EmployeCfp')) {
    //             $extends_containt = "layouts.masterEmpCfp";
    //         } elseif ($user->hasRole('Employe') || $user->hasRole('EmployeEtp')) {
    //             $extends_containt = "layouts.masterEmp";
    //         } elseif ($user->hasRole('Cfp')) {
    //             $extends_containt = "layouts.master";
    //         } elseif ($user->hasRole('Admin') || $user->hasRole('SuperAdmin')) {
    //             $extends_containt = "layouts.masterAdmin";
    //         } elseif ($user->hasRole('Referent')) {
    //             $extends_containt = "layouts.masterEtp";
    //         }
    //     }

    //     // Check invitation validity if invitationId is provided
    //     if ($invitationId !== null) {
    //         $invitation = QcmInvitation::findOrFail($invitationId);
    //         $validationResult = $invitation->validateInvitation($id, Auth::user()->id);

    //         if (!$validationResult['valid']) {
    //             return $validationResult['redirect'];
    //         }
    //     }

    //     // Récupérer le QCM avec ses détails
    //     $qcm = Qcm::with(['questions_qcm.reponses_questions'])->findOrFail($id);

    //     // Récupère l'id de l'entreprise de l'employé
    //     $idEtpOfEmp = new CreditsWallet();
    //     $idEtp = $idEtpOfEmp->getIdEtpByidEmp(Auth::user()->id);

    //     // Vérifier les crédits de l'utilisateur
    //     try {
    //         if (Auth::user()->hasRole('Employe') || Auth::user()->hasRole('EmployeEtp')) {
    //             $userWallet = CreditsWallet::where('idUser', $idEtp)->firstOrFail();
    //             $hasEnoughCredits = $userWallet->solde >= $qcm->prixUnitaire;
    //         } elseif (Auth::user()->hasRole('Particulier')) {
    //             $userWallet = CreditsWallet::where('idUser', Auth::id())->firstOrFail();
    //             $hasEnoughCredits = $userWallet->solde >= $qcm->prixUnitaire;
    //         }

    //         // Si l'utilisateur a déjà commencé ce QCM (vérifié par la présence du timer),
    //         // ne pas revérifier les crédits
    //         if (!session()->has('qcm_timer')) {
    //             if (!$hasEnoughCredits) {
    //                 return redirect()->back()->with(
    //                     'error',
    //                     "Vous n'avez pas assez de crédits pour passer ce test. " .
    //                         "Solde actuel: {$userWallet->solde} crédit(s). " .
    //                         "Prix du test: {$qcm->prixUnitaire} crédit(s)."
    //                 );
    //             }

    //             // Si l'utilisateur a assez de crédits, débiter son compte
    //             try {
    //                 if (Auth::user()->hasRole('Employe') || Auth::user()->hasRole('EmployeEtp')) {
    //                     // Si l'employé connecté est soit "Employe" ou "EmployeEtp", le crédit est déduit du compte en crédit de l'entreprise
    //                     CreditsWallet::debiter($idEtp, $qcm->prixUnitaire, Auth::id()); // Passer l'ID de l'employé connecté
    //                     session(['qcm_payment_processed' => true]); // Marquer le paiement comme traité
    //                 } elseif (Auth::user()->hasRole('Particulier')) {
    //                     // Si l'utilisateur connecté est un particulier, le crédit est déduit de son propre compte en crédit
    //                     CreditsWallet::debiter(Auth::id(), $qcm->prixUnitaire);
    //                     session(['qcm_payment_processed' => true]); // Marquer le paiement comme traité
    //                 }
    //             } catch (\Exception $e) {
    //                 return redirect()->back()->with(
    //                     'error',
    //                     "Une erreur est survenue lors du traitement des crédits: " . $e->getMessage()
    //                 );
    //             }
    //         }

    //         // ====== PARTIE 1: GESTION DU TIMER ======
    //         if (!session()->has('qcm_timer')) {
    //             session(['qcm_timer' => [
    //                 'start_time' => now(),
    //                 'duration' => $qcm->duree,
    //                 'end_time' => now()->addSeconds($qcm->duree)
    //             ]]);
    //         }

    //         $timer = session('qcm_timer');
    //         $now = now();
    //         $endTime = Carbon::parse($timer['end_time']);

    //         if ($now->gt($endTime)) {
    //             session(['time_expired' => true]);
    //             return redirect()->route('qcm.review', ['id' => $id]);
    //         }

    //         // ====== PARTIE 2: GESTION DES QUESTIONS ======
    //         if (!session()->has('qcm_progress')) {
    //             session(['qcm_progress' => [
    //                 'current_index' => 0,
    //                 'responses' => []
    //             ]]);
    //         }

    //         $progress = session('qcm_progress');
    //         $questions = $qcm->questions_qcm;
    //         $questionIndex = max(0, min($questionIndex, count($questions) - 1));
    //         $progress['current_index'] = $questionIndex;
    //         session(['qcm_progress' => $progress]);

    //         $question = $questions[$questionIndex];
    //         $timeLeft = $endTime->diffInSeconds($now);

    //         return view('TestingCenter.qcm_to_respond', compact(
    //             'qcm',
    //             'question',
    //             'questionIndex',
    //             'progress',
    //             'extends_containt',
    //             'timeLeft',
    //             'hasEnoughCredits',
    //             'userWallet'
    //         ));
    //     } catch (\Exception $e) {
    //         return redirect()->back()->with(
    //             'error',
    //             "Une erreur est survenue lors de la vérification des crédits. Veuillez réessayer."
    //         );
    //     }
    // }

    /**
     * Fonction pour afficher le formulaire pour répondre aux questions avec l'index de la question
     * pour les afffichées un par un (v8)
     * $id -> id du qcm
     * avec débit de crédit, pour pouvoir faire le test qcm
     * avec partie changer en services
     * 
     * @param $id, $questionIndex = 0, $invitationId = null
     * @return qcm 
     */
    public function show_qcm_to_respond($id, $questionIndex = 0, $invitationId = null)
    {
        try {
            # Determine the layout based on the user's role
            $extends_containt = $this->navigationService->determineLayout();

            // Check invitation validity if invitationId is provided
            if ($invitationId !== null) {
                $invitation = QcmInvitation::findOrFail($invitationId);
                $validationResult = $invitation->validateInvitation($id, Auth::user()->id);

                # Return the redirect if the validation is not valid
                if (!$validationResult['valid']) {
                    return $validationResult['redirect'];
                }
            }

            // Get QCM with questions and responses
            $qcm = Qcm::with(['questions_qcm.reponses_questions'])->findOrFail($id);

            // Get enterprise ID for employee
            $idEtpOfEmp = new CreditsWallet();
            $idEtp = $idEtpOfEmp->getIdEtpByidEmp(Auth::user()->id);

            // Handle credits for employees and individuals
            if (Auth::user()->hasRole('Employe') || Auth::user()->hasRole('EmployeEtp') || Auth::user()->hasRole('Particulier')) {
                $creditResult = $this->creditService->validateAndProcessCredits(
                    Auth::id(),
                    $idEtp,
                    $qcm->prixUnitaire
                );

                if (!$creditResult['success']) {
                    return redirect()->back()->with('error', $creditResult['message']);
                }
            } else {
                // For other roles, set a default credit result
                $creditResult = [
                    'success' => true,
                    'hasEnoughCredits' => true,
                    'wallet' => null
                ];
            }

            // Initialize timer if not exists
            if (!session()->has('qcm_timer')) {
                $timer = $this->sessionService->initializeTimer($qcm->duree);
            }

            // Check if time has expired
            if ($this->sessionService->validateTimer()) {
                session(['time_expired' => true]);
                return redirect()->route('qcm.review', ['id' => $id]);
            }

            // Initialize progress if not exists
            if (!session()->has('qcm_progress')) {
                $progress = $this->sessionService->initializeProgress();
            }

            // Get current progress from session
            $progress = $this->sessionService->getCurrentProgress();

            // Validate and adjust question index
            $questions = $qcm->questions_qcm;
            $totalQuestions = count($questions);

            if ($totalQuestions === 0) {
                return redirect()->back()->with('error', "Ce QCM ne contient aucune question.");
            }

            // Ensure questionIndex is within valid range
            $questionIndex = max(0, min($questionIndex, $totalQuestions - 1));

            // Get current question
            $question = $questions[$questionIndex];

            // Update progress with current index only (preserve existing responses)
            $progress['current_index'] = $questionIndex;
            session(['qcm_progress' => $progress]);

            // Calculate remaining time
            $timer = session('qcm_timer');
            $endTime = Carbon::parse($timer['end_time']);
            $timeLeft = $endTime->diffInSeconds(now());

            // Get user wallet if applicable
            $hasEnoughCredits = $creditResult['success'] ?? false;
            $userWallet = $creditResult['wallet'] ?? null;

            return view('TestingCenter.qcm_to_respond', compact(
                'qcm',
                'question',
                'questionIndex',
                'progress',
                'extends_containt',
                'timeLeft',
                'hasEnoughCredits',
                'userWallet',
                'totalQuestions'
            ));
        } catch (\Exception $e) {
            Log::error('Error in show_qcm_to_respond: ' . $e->getMessage());
            return redirect()->back()->with('error', "Une erreur est survenue: " . $e->getMessage());
        }
    }

    /**
     * Save each choosen responses that the user choose in session (v4)
     * $id -> id du qcm
     * 
     * @param $request, $id
     */
    // public function save_qcm_response(Request $request, $id)
    // {
    //     // Check if time has expired
    //     $timer = session('qcm_timer');
    //     $now = now();
    //     $endTime = Carbon::parse($timer['end_time']);

    //     // Si le temps est écoulé, rediriger vers la revue
    //     if ($now->gt($endTime)) {
    //         session(['time_expired' => true]);
    //         if ($request->ajax()) {
    //             return response()->json(['redirect' => route('qcm.review', ['id' => $id])]);
    //         }
    //         return redirect()->route('qcm.review', ['id' => $id]);
    //     }

    //     // Validate response
    //     $request->validate([
    //         'idQuestion' => 'required|exists:qcm_questions,idQuestion',
    //         'idReponse' => 'nullable|exists:qcm_reponses,idReponse'
    //     ]);

    //     // Get current progress
    //     $progress = session('qcm_progress', ['responses' => [], 'current_index' => 0]);

    //     // Save response (null if unselected)
    //     $progress['responses'][$request->idQuestion] = $request->idReponse ?: null;

    //     // Get total questions
    //     $qcm = Qcm::with('questions_qcm')->findOrFail($id);
    //     $totalQuestions = $qcm->questions_qcm->count();

    //     // Update session
    //     session(['qcm_progress' => $progress]);

    //     // Handle AJAX requests
    //     if ($request->ajax()) {
    //         return response()->json(['success' => true]);
    //     }

    //     // Handle navigation (finish, next, previous)
    //     if ($request->has('finish')) {
    //         return redirect()->route('qcm.review', ['id' => $id]);
    //     } elseif ($request->has('next')) {
    //         $progress['current_index'] = min($progress['current_index'] + 1, $totalQuestions - 1);
    //     } elseif ($request->has('previous')) {
    //         $progress['current_index'] = max($progress['current_index'] - 1, 0);
    //     }

    //     // Save updated index
    //     session(['qcm_progress' => $progress]);

    //     // Redirect to appropriate question
    //     return redirect()->route('qcm.show_respond', [
    //         'id' => $id,
    //         'questionIndex' => $progress['current_index']
    //     ]);
    // }

    /**
     * Save each choosen responses that the user choose in session (v5)
     * $id -> id du qcm
     * avec partie changer en services
     * 
     * @param $request, $id
     */
    public function save_qcm_response(Request $request, $id)
    {
        # Check if time has expired
        if ($this->sessionService->validateTimer()) {
            session(['time_expired' => true]);
            return $request->ajax()
                ? response()->json(['redirect' => route('qcm.review', ['id' => $id])])
                : redirect()->route('qcm.review', ['id' => $id]);
        }

        # Validate response
        $request->validate([
            'idQuestion' => 'required|exists:qcm_questions,idQuestion',
            'idReponse' => 'nullable|exists:qcm_reponses,idReponse'
        ]);

        // Get the QCM and current progress
        $qcm = Qcm::with('questions_qcm')->findOrFail($id);
        $progress = $this->sessionService->getCurrentProgress();

        // Save the response first
        $this->sessionService->saveResponse($request->idQuestion, $request->idReponse);

        # Handle AJAX requests
        if ($request->ajax()) {
            return response()->json(['success' => true]);
        }

        // Handle navigation
        if ($request->has('finish')) {
            return redirect()->route('qcm.review', ['id' => $id]);
        }

        # Handle next and previous navigation
        $newIndex = $progress['current_index'];
        if ($request->has('next')) {
            $newIndex = min($progress['current_index'] + 1, $qcm->questions_qcm->count() - 1);
        } elseif ($request->has('previous')) {
            $newIndex = max($progress['current_index'] - 1, 0);
        }

        // Update the progress with new index
        $this->sessionService->updateProgress(
            $request->idQuestion,
            $request->idReponse,
            $newIndex
        );

        # Redirect to the new question
        return redirect()->route('qcm.show_respond', [
            'id' => $id,
            'questionIndex' => $newIndex
        ]);
    }

    /**
     * Fonction pour faire une revue des réponses choisies par l'utilisateur  (v3)
     * $id -> id du qcm
     * 
     * @param $id
     */
    public function review_qcm($id)
    {
        // Charger le QCM avec ses questions et réponses
        $qcm = Qcm::with(['questions_qcm.reponses_questions'])->findOrFail($id);

        // Récupérer la progression et l'état du timer
        $progress = session('qcm_progress');
        $timer = session('qcm_timer');
        $timeExpired = session('time_expired', false);

        // Calculer le temps restant si le temps n'est pas expiré
        $timeLeft = null;
        if (!$timeExpired && $timer) {
            $now = now();
            $endTime = Carbon::parse($timer['end_time']);
            // Si le temps n'est pas écoulé, calculer le temps restant
            $timeLeft = $now->gt($endTime) ? 0 : $endTime->diffInSeconds($now);
        }

        // Définir le layout selon le rôle de l'utilisateur
        $extends_containt = $this->navigationService->determineLayout();

        // Renvoyer la vue avec toutes les données nécessaires
        return view('TestingCenter.qcm_review', compact(
            'qcm',
            'progress',
            'extends_containt',
            'timeExpired',
            'timeLeft'
        ));
        // dd($progress);
    }

    /**
     * Fonction pour soumettre (ou valider) les réponses choisies par l'utilisateur (v2)
     * $id -> id du qcm
     * 
     * @param $id 
     */
    public function submit_qcm(Request $request, $id)
    {
        // Vérifier l'état du timer
        $timer = session('qcm_timer');
        $now = now();
        $endTime = Carbon::parse($timer['end_time']);

        // Marquer si le temps est expiré
        if ($now->gt($endTime)) {
            session(['time_expired' => true]);
        }

        // Récupérer la progression
        $progress = session('qcm_progress', []);
        // dd($progress);
        if (empty($progress)) {
            return redirect()->route('qcm.show', ['id' => $id])
                ->with('error', 'No responses found in session.');
        }

        // Créer une nouvelle session de QCM
        $qcm = Qcm::with('questions_qcm')->findOrFail($id);
        $session = new SessionsQcm();
        $session->idUtilisateur = Auth::id();
        $session->idQCM = $qcm->idQCM;
        $session->dateDebut = Carbon::parse($timer['start_time']);
        $session->save();

        // Calculer les points totaux
        $totalPoints = 0;

        // Sauvegarder les réponses et calculer les points
        foreach ($qcm->questions_qcm as $question) {
            $userResponse = new ReponsesQcmUsers();
            $userResponse->idSession = $session->idSession;
            $userResponse->idQuestion = $question->idQuestion;

            // Récupérer la réponse de l'utilisateur
            $responseId = $progress['responses'][$question->idQuestion] ?? null;

            if ($responseId) {
                $userResponse->idReponse = $responseId;
                $reponse = QcmReponses::find($responseId);
                if ($reponse) {
                    $totalPoints += $reponse->points;
                }
            } else {
                $userResponse->idReponse = null;
            }

            $userResponse->save();
        }

        // Finaliser la session
        $session->dateFin = now();
        $session->totalPoints = $totalPoints;
        $session->save();

        // Nettoyer toutes les sessions
        session()->forget(['qcm_progress', 'qcm_timer', 'time_expired']);

        // Rediriger vers les résultats
        return redirect()->route('qcm.results', ['id' => $id]);
    }

    /**
     * Fonction pour afficher les points final de l'utilisateur après avoir
     * soumis ses réponses après avoir résolut le qcm pour les apprenants
     * $id -> id du qcm (v2)
     * 
     * @param $id
     */
    public function show_qcm_results_after_test($id)
    {
        $extends_containt = $this->navigationService->determineLayout(); // Variable pour stocker les extends selon l'utilisateur connecté

        // Retrieve the session or result data
        $session = SessionsQcm::where('idQCM', $id)
            ->where('idUtilisateur', Auth::id())
            ->latest()
            ->firstOrFail();

        // Retrieve the user's responses and the total points scored
        $responses = ReponsesQcmUsers::with('questionOfResponse', 'userChoosenReponse')
            ->where('idSession', $session->idSession)
            ->get();

        // Déterminer le niveau de l'apprenant après le QCM
        $bareme = QcmBareme::where('idQCM', $id)
            ->where('minPoints', '<=', $session->totalPoints)
            ->where('maxPoints', '>=', $session->totalPoints)
            ->first();

        $niveau = $bareme ? $bareme->niveau : 'Niveau inconnu';

        // Pass the session, responses, total points, and niveau to the view
        return view('TestingCenter.qcm_results', compact(
            'session',
            'responses',
            'niveau',
            'extends_containt'
        ));
    }

    /**
     * Fonction menant au formulaire pour mettre à jour un qcm (v2)
     * $id -> id du qcm
     * 
     * @param $id
     */
    public function edit_qcm($id)
    {
        $extends_containt = $this->navigationService->determineLayout(); // Variable pour stocker les extends selon l'utilisateur connecté

        // Retrieve the QCM along with its questions and responses
        $qcm = Qcm::with(['questions_qcm.reponses_questions'])->findOrFail($id);

        // Fetch all available domaines and Response's categories
        $all_domaines = DomainesFormation::all();
        $all_categories_reponses = CategoriesReponses::all();

        // Return the view with the QCM data and domaines
        return view('TestingCenter.edit_qcm', compact(
            'qcm',
            'all_domaines',
            'all_categories_reponses',
            'extends_containt'
        ));
    }

    /**
     * Fonction pour mettre à jour un qcm suivit de ses questions et réponses (v2)
     * $id -> id du qcm
     * 
     * @param $request, $id
     */
    public function update_qcm(Request $request, $id)
    {
        // Start a database transaction
        DB::beginTransaction();

        try {
            // Update the QCM
            $qcm = Qcm::findOrFail($id);
            $qcm->update($request->only(['intituleQCM', 'descriptionQCM', 'idDomaine', 'prixUnitaire', 'duree']));

            // Get all current question IDs for this QCM
            $currentQuestionIds = $qcm->questions_qcm->pluck('idQuestion')->toArray();

            // Process questions
            foreach ($request->input('questions', []) as $questionId => $questionData) {
                if (strpos($questionId, 'new_') === 0) {
                    // This is a new question
                    $question = new QcmQuestions([
                        'texteQuestion' => $questionData['texteQuestion'],
                        'idQCM' => $id
                    ]);
                    $question->save();
                } else {
                    // This is an existing question
                    $question = QcmQuestions::findOrFail($questionId);
                    $question->update(['texteQuestion' => $questionData['texteQuestion']]);

                    // Remove this ID from the current questions array
                    $currentQuestionIds = array_diff($currentQuestionIds, [$questionId]);
                }

                // Process responses for this question
                $currentResponseIds = $question->reponses_questions->pluck('idReponse')->toArray();
                foreach ($questionData['responses'] ?? [] as $responseId => $responseData) {
                    // Handle the case where no category is selected
                    $categorie_id = $responseData['categorie_id'] ?: null;

                    if (strpos($responseId, 'new_') === 0) {
                        // This is a new response
                        $response = new QcmReponses([
                            'texteReponse' => $responseData['texteReponse'],
                            'points' => $responseData['points'],
                            'categorie_id' => $categorie_id,
                            'idQuestion' => $question->idQuestion
                        ]);
                        $response->save();
                    } else {
                        // This is an existing response
                        $response = QcmReponses::findOrFail($responseId);
                        $response->update([
                            'texteReponse' => $responseData['texteReponse'],
                            'points' => $responseData['points'],
                            'categorie_id' => $categorie_id
                        ]);

                        // Remove this ID from the current responses array
                        $currentResponseIds = array_diff($currentResponseIds, [$responseId]);
                    }
                }

                // Delete responses that were removed
                QcmReponses::destroy($currentResponseIds);
            }

            // Delete questions that were removed along with their responses
            foreach ($currentQuestionIds as $questionIdToDelete) {
                $questionToDelete = QcmQuestions::findOrFail($questionIdToDelete);
                // Delete all responses associated with this question
                $questionToDelete->reponses_questions()->delete();
                // Now delete the question itself
                $questionToDelete->delete();
            }

            // Commit the transaction
            DB::commit();

            // return redirect()->route('qcm.edit', $id)->with('success', 'QCM updated successfully!');
            return redirect()->route('index.qcm')->with('success', 'QCM updated successfully!');
        } catch (\Exception $e) {
            // Something went wrong, rollback the transaction
            DB::rollBack();
            // return redirect()->back()->with('error', 'An error occurred while updating the QCM: ' . $e->getMessage());
            return redirect()->route('index.qcm')->with('error', 'Error update');
        }
    }

    /**
     * Fonction pour supprimer un qcm avec ses questions et réponses en utilisant l'id
     * du qcm
     * @param $id
     */
    public function destroy_qcm($id)
    {
        try {
            Qcm::deleteQcmWithRelated($id);
            // return response()->json(['message' => 'QCM avec ses questions et réponses supprimé'], 200);
            return redirect()->route('index.qcm')->with('success', 'QCM avec ses questions et réponses supprimé');
        } catch (\Exception $e) {
            // return response()->json(['message' => 'Erreur lors de la suppression du QCM: ' . $e->getMessage()], 500);
            return redirect()->route('index.qcm')->with('error', 'Erreur lors de la suppression du QCM');
        }
    }

    /**
     * Method for listing all the Cfp for their qcm's results (superadmin side) (v3)
     * 
     * @param $request 
     */
    public function indexCfpListForQCM(Request $request)
    {
        $extends_containt = $this->navigationService->determineLayout();

        // Application des filtres
        $filterName = $request->get('filterName');

        $CfpQcm = new Qcm();
        $query = DB::table('v_cfp_all')
            ->select('idCfp', 'customerName', 'description', 'customerPhone', 'customerEmail', 'customer_addr_lot');

        if ($filterName) {
            $query->where('customerName', 'LIKE', '%' . $filterName . '%');
        }

        $CfpList = $query->paginate(10); // Show 10 items per page

        return view('TestingCenter.indexTCenter.indexCfpListForQcm', compact(
            'extends_containt',
            'CfpList',
        ));
    }

    /**
     * Method for showing all Cfp created by the Cfp (superadmin side) (v2)
     * 
     * @param $id (id Cfp), $request
     */
    public function showCfpQcm(Request $request, $id)
    {
        $extends_containt = $this->navigationService->determineLayout();

        // Fetch Qcm's creator datas
        $qcm = new Qcm();
        $customerDatas = $qcm->fetchQcmCreatorDatas($id);

        // Récupération des filtres
        $filterQcmName = $request->get('filterQcmName');
        $filterDomain = $request->get('filterDomain');

        // Récupération des domaines
        $domains = DomainesFormation::select('idDomaine', 'nomDomaine')->get();

        // Récupération des QCM avec filtres
        $query = DB::table('qcm')
            ->join('users', 'qcm.user_id', '=', 'users.id')
            ->join('domaine_formations', 'qcm.idDomaine', '=', 'domaine_formations.idDomaine')
            ->select(
                'qcm.idQCM',
                'qcm.user_id',
                'qcm.intituleQCM',
                'qcm.descriptionQCM',
                'qcm.idDomaine',
                'qcm.prixUnitaire',
                'users.name as creatorName',
                'users.email as creatorEmail',
                'users.phone as creatorPhone',
                'domaine_formations.nomDomaine'
            )
            ->where('qcm.user_id', '=', $id);

        if ($filterQcmName) {
            $query->where('qcm.intituleQCM', 'LIKE', '%' . $filterQcmName . '%');
        }

        if ($filterDomain) {
            $query->where('qcm.idDomaine', '=', $filterDomain);
        }

        $qcmList = $query->get();

        return view('TestingCenter.indexTCenter.showCfpQcm', compact(
            'extends_containt',
            'qcmList',
            'domains',
            'id',
            'customerDatas',
        ));
    }

    /**
     * Method for getting the results of a qcm (superadmin side) (v2)
     * 
     * @param $id (id qcm), Request $request
     */
    public function showQcmResults($id, Request $request)
    {
        $extends_containt = $this->navigationService->determineLayout(); // Variable pour définir la mise en page selon l'utilisateur connecté

        // Récupérer l'utilisateur connecté
        $user = Auth::user();
        $id_auth_user = $user->id;
        $qcm_id = $id;

        // Récupérer les informations du QCM pour obtenir l'ID du créateur
        $qcm = Qcm::findOrFail($id); // Assurez-vous que le modèle Qcm existe
        $creatorId = $qcm->user_id; // Remplacez `creator_id` par le champ réel indiquant le créateur

        // Récupérer les résultats du QCM
        $QcmResults = new QcmBareme();
        $results = $QcmResults->getAllResultsApprPostTestWithAlreadyApprCtf($id, $creatorId);

        if ($results['status'] === 'error') {
            return view('TestingCenter.indexTCenter.qcm_results_superAdmin', [
                'message' => $results['message'],
                'apprenants' => [],
                'extends_containt' => $extends_containt,
                'filters' => [],
                'qcmId' => $id,
            ]);
        }

        // Formater les données des apprenants
        $apprenants = collect($results['data'])->map(function ($apprenant) {
            return [
                'id' => $apprenant->idUtilisateur,
                'name' => $apprenant->name,
                'firstname' => $apprenant->firstName,
                'session' => $apprenant->idSession,
                'etp' => $apprenant->etp_name,
                'date' => $apprenant->date_session,
                'points' => $apprenant->total_points,
                'niveau' => $apprenant->niveau,
                'rang' => $apprenant->rang
            ];
        });

        // Appliquer les filtres
        $filters = $request->only(['name', 'etp', 'date', 'points_min', 'points_max', 'level']);
        $filteredData = $apprenants->filter(function ($apprenant) use ($filters) {
            return (empty($filters['name']) || stripos($apprenant['name'], $filters['name']) !== false) &&
                (empty($filters['etp']) || stripos($apprenant['etp'], $filters['etp']) !== false) &&
                (empty($filters['date']) || stripos($apprenant['date'], $filters['date']) !== false) &&
                (empty($filters['points_min']) || $apprenant['points'] >= $filters['points_min']) &&
                (empty($filters['points_max']) || $apprenant['points'] <= $filters['points_max']) &&
                (empty($filters['level']) || $apprenant['niveau'] == $filters['level']);
        });

        return view('TestingCenter.indexTCenter.qcm_results_superAdmin', [
            'apprenants' => $filteredData,
            'filters' => $filters,
            'extends_containt' => $extends_containt,
            'qcmId' => $id,
        ]);
    }

    /**
     * Method for the global results of all qcm created by an user, superadmin part (v2)
     * 
     * @param $id (id ctf), $request
     */
    public function index_global_results_allQcmOfUserSA($id, Request $request)
    {
        $extends_containt = $this->navigationService->determineLayout(); // Variable pour stocker les extends selon l'utilisateur connecté

        $user = Auth::user();
        $id_auth_user = $user->id;

        // Initialision de QcmBareme
        $QcmResults = new QcmBareme();
        $results = $QcmResults->fetchGlobalResultsQcmCtf($id); # $id_auth_user -> idCtf

        // Avoir les détails du centre de formation
        $user = User::findOrFail($id_auth_user);

        if ($results['status'] === 'error') {
            return view('TestingCenter.indexTCenter.index_list_global_apprenants_test', [
                'message' => $results['message'],
                'apprenants' => [],
                'extends_containt' => $extends_containt,
                'user' => $user,
                'id_auth_user' => $id_auth_user,
            ]);
        }

        $apprenants = $results['data'];
        $message = '';

        // Si il n'y a aucun apprenant(s) ayant fait le test
        if (count($apprenants) === 0) {
            $message = 'Aucun apprenant n\'a encore passé ce test.';
        }

        // Formattage des données (optionnel)
        $formattedData = $apprenants->map(function ($apprenant) {
            return [
                'id' => $apprenant->idUtilisateur,
                'name' => $apprenant->name,
                'firstname' => $apprenant->firstName,
                'session' => $apprenant->idSession,
                'id_etp' => $apprenant->idEtp,
                'etp' => $apprenant->etp_name,
                'idqcm' => $apprenant->idQCM,
                'intituleqcm' => $apprenant->intituleQCM,
                'date' => $apprenant->date_session,
                'points' => $apprenant->total_points,
                'niveau' => $apprenant->niveau,
                'rang' => $apprenant->rang
            ];
        });

        // Récupération des filtres depuis la requête
        $nameFilter = $request->get('name_filter', '');
        $etpFilter = $request->get('etp_filter', '');
        $intituleQcm = $request->get('qcm_filter', '');
        $dateFilter = $request->get('date_filter', '');
        $pointsMin = $request->get('points_min', '');
        $pointsMax = $request->get('points_max', '');
        $levelFilter = $request->get('level_filter', '');

        // Application des filtres
        if ($formattedData->isNotEmpty()) {
            $filteredData = $formattedData->filter(function ($apprenant) use ($nameFilter, $etpFilter, $intituleQcm, $dateFilter, $pointsMin, $pointsMax, $levelFilter) {
                $nameMatch = empty($nameFilter) || str_contains(
                    strtolower($apprenant['name'] . ' ' . $apprenant['firstname']),
                    strtolower($nameFilter)
                );

                $etpMatch = empty($etpFilter) || str_contains(strtolower($apprenant['etp']), strtolower($etpFilter));

                $qcmMatch = empty($intituleQcm) || str_contains(strtolower($apprenant['intituleqcm']), strtolower($intituleQcm));

                $dateMatch = empty($dateFilter) || str_contains($apprenant['date'], $dateFilter);

                $pointsMatch = (empty($pointsMin) || $apprenant['points'] >= $pointsMin) &&
                    (empty($pointsMax) || $apprenant['points'] <= $pointsMax);

                $levelMatch = empty($levelFilter) || $apprenant['niveau'] === $levelFilter;

                return $nameMatch && $etpMatch && $qcmMatch && $dateMatch && $pointsMatch && $levelMatch;
            });

            // Récupération des niveaux uniques pour le filtre
            $uniqueLevels = $formattedData->pluck('niveau')->unique();
        } else {
            $filteredData = collect([]);
            $uniqueLevels = collect([]);
        }

        return view('TestingCenter.indexTCenter.index_list_global_apprenants_test', [
            'apprenants' => $filteredData,
            'message' => $message,
            'extends_containt' => $extends_containt,
            'user' => $user,
            'filters' => [
                'name' => $nameFilter,
                'date' => $dateFilter,
                'points_min' => $pointsMin,
                'points_max' => $pointsMax,
                'level' => $levelFilter
            ],
            'uniqueLevels' => $uniqueLevels,
            'cfpId' => $id_auth_user,
            'id_auth_user' => $id_auth_user,
        ]);
    }

    /**
     * Method for displaying the dashboard of results of a qcm (v2)
     * 
     * @param $id (id qcm)
     */
    public function dashboardQcmResults($id)
    {
        $extends_containt = $this->navigationService->determineLayout();

        // Récupérer les données du dashboard
        $qcm = new Qcm();
        if (Auth::user()->hasRole('Referent')) {
            $dashboardData = $qcm->fetchQcmDashboardDatasForEtp($id, Auth::user()->id);
        } else {
            $dashboardData = $qcm->fetchQcmDashboardDatas($id);
        }

        // Récupérer les informations du QCM
        $qcmInfo = Qcm::find($id);

        // Préparer les données pour le graphique de participation
        if (isset($dashboardData['status']) && $dashboardData['status'] === 'error') {
            return view('TestingCenter.errors.dashboard', [
                'message' => $dashboardData['message'] ?? 'Une erreur est survenue lors de la récupération des données.'
            ], compact('extends_containt'));
        } elseif (isset($dashboardData['data']['participation_by_month'])) {
            $participationData = [
                'labels' => array_keys($dashboardData['data']['participation_by_month']),
                'data' => array_values($dashboardData['data']['participation_by_month']),
            ];
        }

        return view('TestingCenter.dashboardQcmResults', compact(
            'extends_containt',
            'dashboardData',
            'qcmInfo',
            'participationData'
        ));

        // return response()->json($dashboardData);
    }

    /**
     * Method for displaying abilities's report of a student after a qcm (v2)
     * 
     * @param int $id QCM ID
     * @param int $idApprenant Student ID
     * @param int $idSession Session ID
     * @return \Illuminate\View\View
     */
    public function getAbilitiesReport($id, $idApprenant, $idSession)
    {
        $extends_containt = $this->navigationService->determineLayout();

        // Récupérer le QCM
        $qcm = Qcm::findOrFail($id);

        // Récupérer l'utilisateur (apprenant)
        $apprenant = User::findOrFail($idApprenant);

        // Récupérer les résultats détaillés
        $results = $qcm->fetchUserPointsInCategories($id, $idApprenant, $idSession);

        // Récupérer la session
        $session = SessionsQcm::findOrFail($idSession);

        return view('TestingCenter.abilitiesReport', compact(
            'extends_containt',
            'qcm',
            'apprenant',
            'results',
            'session'
        ));
    }

    /**
     * Export abilities report as PDF
     * 
     * @param $id, $idApprenant, $idSession
     */
    public function exportAbilitiesReportPDF($id, $idApprenant, $idSession)
    {
        $qcm = Qcm::findOrFail($id);
        $apprenant = User::findOrFail($idApprenant);
        $results = $qcm->fetchUserPointsInCategories($id, $idApprenant, $idSession);
        $session = SessionsQcm::findOrFail($idSession);

        $pdf = Pdf::loadView('TestingCenter.abilitiesReportPDF', compact(
            'qcm',
            'apprenant',
            'results',
            'session'
        ));

        return $pdf->download('rapport-competences-' . $apprenant->name . '_' . $apprenant->firstName . '.pdf');
    }

    /**
     * Handle the QCM invitation start, checking authentication
     * 
     * @param int $qcmId
     * @param int $invitationId
     * @return \Illuminate\Http\RedirectResponse
     */
    public function handleInvitationStart($qcmId, $invitationId)
    {
        if (!Auth::check()) {
            // Store the intended URL in the session
            session(['url.intended' => url("/qcm/solve/{$qcmId}/respond/0/invitation/{$invitationId}")]);

            // Redirect to login
            return redirect()
                ->route('user.login')
                ->with('message', 'Please log in to access your QCM invitation.');
        }

        // If authenticated, redirect to the QCM
        return redirect("/qcm/solve/{$qcmId}/respond/0/invitation/{$invitationId}");
    }
}
