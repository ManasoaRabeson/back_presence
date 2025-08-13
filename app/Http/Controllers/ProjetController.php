<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Customer;
use App\Models\Projet;
use App\Services\ParticulierService;
use App\Services\ProjetService;
use App\Services\UtilService;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;
use Google\Service\Monitoring\Custom;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Drivers\Gd\Driver;
use Intervention\Image\ImageManager;
use Laravelcm\Subscriptions\Models\Feature;
use Laravelcm\Subscriptions\Models\Subscription;

class ProjetController extends Controller
{
    protected $utilService;
    protected $project;

    public function __construct(UtilService $utilService, ProjetService $prj)
    {
        $this->utilService = $utilService;
        $this->project = $prj;
    }


    private function getStatus(string $status, array $filters = [])
    {
        $userId = Auth::id();
        $roleId = DB::table('role_users')
            ->where('user_id', $userId)
            ->value('role_id');

        // Sélection de la source de projets selon le rôle
        if ($roleId == 3) {
            // CFP
            $projects = $this->project->index(null, Customer::idCustomer(), $status, $filters);
            $getEtpMethod = 'getEtpProjectInter';
        } else  if ($roleId == 5) {
            // Formateur
            $projects = $this->project->indexByFormateur($userId, $status, $filters);
            $getEtpMethod = 'getEtpProjectInterByFormateur';
        } else if ($roleId == 4) {
            $projects = $this->project->indexByApprenant($userId, $status, $filters);
            $getEtpMethod = 'getEtpProjectInterByApprenant';
        }

        $projets = [];
        foreach ($projects as $project) {
            $idProjet = $project->idProjet;
            $idCfpInter = $project->idCfp_inter;

            $apprs = $this->getApprListProjet($idProjet);
            $etpName = $this->$getEtpMethod($idProjet, $idCfpInter);
            //$sessionHour = $this->getSessionHour($idProjet);
            $formateurs = $this->getFormProject($idProjet);

            $projets[] = [
                'formateurs' => $formateurs,
                //'totalSessionHour' => $sessionHour,
                'idProjet' => $idProjet,
                'idCfp_inter' => $idCfpInter,
                'dateDebut' => $project->dateDebut,
                'dateFin' => $project->dateFin,
                'module_name' => $project->module_name,
                'etp_name' => $etpName,
                'project_status' => $project->project_status,
                'project_type' => $project->project_type,
                'modalite' => $project->modalite,
                'project_description' => $project->project_description,
                'headDate' => $project->headDate,
                'module_image' => $project->module_image,
                'etp_logo' => $project->etp_logo,
                'etp_initial_name' => $project->etp_initial_name,
                'idModule' => $project->idModule,
                'apprs' => $apprs,
                'li_name' => $project->li_name
            ];
        }

        return [
            'projets' => $projets,
            'pagination' => method_exists($projects, 'links') ? $projects->toArray() : null,
        ];
    }

    private function getFilterByStatus(string $status): array
    {
        $lieux = collect();
        $entreprises = collect();
        $modules = collect();
        $formateursUniques = collect();
        $mois = collect();

        $type_projets = DB::table('type_projets')->get();

        $roleId = DB::table('role_users')
            ->where('user_id', Auth::id())
            ->value('role_id');
        $projets = match ($roleId) {
            3 => $this->project->indexFilter(Customer::idCustomer(), $status),
            5 => $this->project->indexFilterByFormateur($status, Auth::id()),
            4 => $this->project->indexFilterByApprenant($status, Auth::id()),
            default => [],
        };

        foreach ($projets as $pj) {
            $idProjet = $pj->idProjet;
            $idCfpInter = $pj->idCfp_inter;
            $etpName = match ($roleId) {
                3 =>  $this->getEtpProjectInter($idProjet, $idCfpInter),
                5 =>  $this->getEtpProjectInterByFormateur($idProjet, $idCfpInter),
                4 =>  $this->getEtpProjectInterByApprenant($idProjet, $idCfpInter),
                default => []
            };
            $formateurs = $this->getFormProject($idProjet);
            $lieux->push($pj->li_name);
            foreach ($etpName as $etp) {
                $entreprises->push((object)[
                    'idEtp' => $etp->idEtp,
                    'etp_name' => $etp->etp_name
                ]);
            }

            // Modules uniques
            $modules->push([
                'idModule' => $pj->idModule,
                'module_name' => $pj->module_name,
                'module_image' => $pj->module_image,
            ]);

            // Formateurs uniques
            foreach ($formateurs as $form) {
                $formateursUniques->push($form);
            }

            // Mois uniques basés sur dateDebut
            $date = Carbon::parse($pj->dateDebut);
            $mois->push([
                'id' => $date->format('Y-m'),
                'label' => $date->format('F Y')
            ]);
        }

        return [
            'type_projets' => $type_projets,
            'lieux' => $lieux->unique()->values()->all(),
            'entreprises' => $entreprises->unique('idEtp')->values()->all(),
            'modules' => $modules->unique('idModule')->values()->all(),
            'formateurs' => $formateursUniques->unique('idFormateur')->values()->all(),
            'mois' => $mois->unique('id')->values()->all(),
        ];
    }
    public function index(string $status, Request $request)
    {
        $validStatuses = ['Cloturé', 'En cours', 'Terminé'];

        // Phase 1 : Validation du status
        if (!in_array($status, $validStatuses)) {
            return response()->json([
                'status' => 200,
                'projets' => [],
                'filtre' => [
                    'type_projets' => [],
                    'lieux' => [],
                    'entreprises' => [],
                    'modules' => [],
                    'formateurs' => [],
                    'mois' => [],
                ],
            ]);
        }
        $filters = $request->all(); // récupère tous les filtres envoyés en query/body
        $projets = $this->getStatus($status, $filters);

        // Phase 3 : Réponse JSON
        return response()->json([
            'status' => 200,
            'projets' => $projets['projets'],
            'pagination' => $projets['pagination'],
        ]);
    }

    public function getFiltre(string $status)
    {
        $validStatuses = ['Cloturé', 'En cours', 'Terminé'];

        if (!in_array($status, $validStatuses)) {
            // Retourne structure vide si status invalide
            return response()->json([
                'status' => 200,
                'filtre' => [
                    'type_projets' => [],
                    'lieux' => [],
                    'entreprises' => [],
                    'modules' => [],
                    'formateurs' => [],
                    'mois' => [],
                ],
            ]);
        }

        $projets = $this->getFilterByStatus($status);

        return response()->json([
            'status' => 200,
            'filtre' => [
                'type_projets' => $projets['type_projets'],
                'lieux' => $projets['lieux'],
                'entreprises' => $projets['entreprises'],
                'modules' => $projets['modules'],
                'formateurs' => $projets['formateurs'],
                'mois' => $projets['mois'],
            ],
        ]);
    }

    public function getCountProject()
    {
        $req = DB::table('role_users')
            ->select('role_id', 'user_id')
            ->where('user_id', Auth::user()->id)
            ->first();

        [$projetEnCours, $projetTermines, $projetClotures] = match ($req->role_id) {
            3 => [
                $this->project->countByStatus(Customer::idCustomer(), "En cours"),
                $this->project->countByStatus(Customer::idCustomer(), "Terminé"),
                $this->project->countByStatus(Customer::idCustomer(), "Cloturé"),
            ],
            5 => [
                $this->project->countByStatusByFormateur("En cours", Auth::user()->id),
                $this->project->countByStatusByFormateur("Terminé", Auth::user()->id),
                $this->project->countByStatusByFormateur("Cloturé", Auth::user()->id),
            ],
            4 => [
                $this->project->countByStatusByApprenant("En cours", Auth::user()->id),
                $this->project->countByStatusByApprenant("Terminé", Auth::user()->id),
                $this->project->countByStatusByApprenant("Cloturé", Auth::user()->id),
            ],
            default => throw new \Exception('Rôle non reconnu'),
        };


        return response()->json([
            'status' => 200,
            'projet_counts' => [
                'en_cours' => $projetEnCours,
                'termines' => $projetTermines,
                'clotures' => $projetClotures,
            ]
        ]);
    }

    public function getDataPresence($idProjet)
    {
        // ✅ Récupération unique du projet
        $projet = DB::table('v_projet_cfps')->where('idProjet', $idProjet)->first();

        if (!$projet) {
            abort(404, 'Projet non trouvé');
        }


        // ✅ Récupération des sessions et leur durée en une seule requête
        $seances = DB::table('v_seances')
            ->where('idProjet', $idProjet)
            ->orderBy('dateSeance', 'asc')
            ->get([
                'idSeance',
                'dateSeance',
                'heureDebut',
                'heureFin',
                'idProjet',
                'idModule',
                DB::raw("TIME_FORMAT(SEC_TO_TIME(TIME_TO_SEC(intervalle_raw)), '%H:%i') AS intervalle_raw")
            ]);

        // ✅ Obtenir les dates de sessions en une seule requête
        $datesSession = $seances->pluck('dateSeance');
        $dateDebut = DB::table('v_projet_cfps')->where('idProjet', $idProjet)->value('dateDebut');
        $dateFin = DB::table('v_projet_cfps')->where('idProjet', $idProjet)->value('dateFin');

        $deb = $datesSession->first() ? Carbon::parse($datesSession->first())->locale('fr')->translatedFormat('l j F Y') : Carbon::parse($dateDebut)->locale('fr')->translatedFormat('l j F Y');
        $fin = $datesSession->last() ? Carbon::parse($datesSession->last())->locale('fr')->translatedFormat('l j F Y') : Carbon::parse($dateFin)->locale('fr')->translatedFormat('l j F Y');

        // ✅ Calcul du total des heures en une requête
        $totalSession = DB::table('v_seances')
            ->where('idProjet', $idProjet)
            ->selectRaw("IFNULL(TIME_FORMAT(SEC_TO_TIME(SUM(TIME_TO_SEC(intervalle_raw))), '%H:%i'), '00:00') as sumHourSession")
            ->value('sumHourSession');

        // ✅ Regroupement des autres données générales
        $generalData = DB::table('v_seances')
            ->where('idProjet', $idProjet)
            ->groupBy('idProjet')
            ->selectRaw("COUNT(DISTINCT dateSeance) as countDate")
            ->first();

        // ✅ Récupération des modules sans répéter les appels SQL
        $modules = DB::table('mdls')
            ->where('moduleName', '!=', 'Default module')
            // ->where('idCustomer', Customer::idCustomer())
            ->orderBy('moduleName', 'asc')
            ->get(['idModule', 'moduleName AS module_name']);


        // ✅ Récupération des autres données en une seule fois
        $apprenantInter = DB::table('v_list_apprenant_inter_added')->where('idProjet', $idProjet)->get();
        $villes = DB::table('villes')->get(['idVille', 'ville']);
        $paiements = DB::table('paiements')->get(['idPaiement', 'paiement']);
        $modalites = DB::table('modalites')->get(['idModalite', 'modalite']);

        // ✅ Récupération du dossier lié au projet
        $dossier = DB::table('dossiers AS d')
            ->join('projets AS p', 'd.idDossier', '=', 'p.idDossier')
            ->where('p.idProjet', $idProjet)
            ->first(['nomDossier', 'd.idDossier']);

        $nomDossier = $dossier->nomDossier ?? null;
        $idDossier = $dossier->idDossier ?? null;


        return response()->json([
            'projet' => $projet,
            'seances' => $seances,
            'date_debut' => $deb,
            'date_fin' => $fin,
            'total_session' => $totalSession,
            'general_data' => $generalData,
            'modules' => $modules,


            'apprenants' => $apprenantInter,
            'villes' => $villes,
            'paiements' => $paiements,
            'modalites' => $modalites,

            'dossier' => [
                'nomDossier' => $nomDossier,
                'idDossier' => $idDossier,
            ],
            // 'images_momentums' => $imagesMomentums,
            // 'module_ressources' => $module_ressources,
            // 'prerequis_modules' => $prerequis,
            // 'objectif_modules' => $objectifs,
            // 'materiels_modules' => $materiels,            
            //'restaurations' => $restaurations,
            //'evaluations' => $evaluations,
            //             'nb_place' => $nbPlace,
            // 'place_available' => $place_available,
            // 'place_reserved' => $place_reserved,
        ]);
    }

    public static function getApprListProjet($idProjet)
    {
        $apprIntras = DB::table('v_list_apprenants')
            ->select('idEmploye', 'emp_name', 'emp_firstname', 'emp_photo', 'etp_name', 'emp_initial_name')
            ->where('idProjet', $idProjet)
            ->orderBy('emp_name', 'asc')
            ->get()
            ->toArray();

        $apprenantInters = DB::table('v_list_apprenant_inter_added')
            ->select('idEmploye', 'emp_name', 'emp_firstname', 'emp_photo', 'etp_name')
            ->where('idProjet', $idProjet)
            ->orderBy('emp_name', 'asc')
            ->get()
            ->toArray();

        $apprs = array_merge($apprIntras, $apprenantInters);

        // return response()->json(['apprs' => $apprs]);
        return $apprs;
    }





    public static function getEtpProjectInter($idProjet, $idCfp_inter)
    {
        if ($idCfp_inter == null || $idCfp_inter == 'null') {
            $etp = DB::table('v_projet_cfps')
                ->select('idEtp', 'etp_name', 'etp_logo', 'etp_initial_name')
                ->where('idProjet', $idProjet)
                ->whereNot('idEtp', Customer::idCustomer())
                ->groupBy('idEtp', 'etp_name', 'etp_logo', 'etp_initial_name')
                ->get();
        } elseif ($idCfp_inter != null) {
            $etp = DB::table('v_list_entreprise_inter')
                ->select('idEtp', 'etp_name', 'etp_logo', 'etp_initial_name')
                ->where('idProjet', $idProjet)
                ->where('etp_name', '!=', 'null')
                ->orderBy('etp_name', 'asc')
                ->groupBy('idEtp')
                ->get();
        }

        return $etp->toArray();
    }
    public static function getEtpProjectInterByFormateur($idProjet, $idCfp_inter)
    {
        if ($idCfp_inter == null || $idCfp_inter == 'null') {
            $etp = DB::table('v_projet_cfps')
                ->select('idEtp', 'etp_name', 'etp_logo', 'etp_initial_name')
                ->where('idProjet', $idProjet)
                // ->whereNot('idEtp', Customer::idCustomer())
                ->groupBy('idEtp', 'etp_name', 'etp_logo', 'etp_initial_name')
                ->get();
        } elseif ($idCfp_inter != null) {
            $etp = DB::table('v_list_entreprise_inter')
                ->select('idEtp', 'etp_name', 'etp_logo', 'etp_initial_name')
                ->where('idProjet', $idProjet)
                ->where('etp_name', '!=', 'null')
                ->orderBy('etp_name', 'asc')
                ->groupBy('idEtp')
                ->get();
        }

        return $etp->toArray();
    }
    public static function getEtpProjectInterByApprenant($idProjet, $idCfp_inter)
    {
        if ($idCfp_inter == null || $idCfp_inter == 'null') {
            $etp = DB::table('v_projet_cfps')
                ->select('idEtp', 'etp_name', 'etp_logo', 'etp_initial_name')
                ->where('idProjet', $idProjet)
                // ->whereNot('idEtp', Customer::idCustomer())
                ->groupBy('idEtp', 'etp_name', 'etp_logo', 'etp_initial_name')
                ->get();
        } elseif ($idCfp_inter != null) {
            $etp = DB::table('v_list_entreprise_inter')
                ->select('idEtp', 'etp_name', 'etp_logo', 'etp_initial_name')
                ->where('idProjet', $idProjet)
                ->where('etp_name', '!=', 'null')
                ->orderBy('etp_name', 'asc')
                ->groupBy('idEtp')
                ->get();
        }

        return $etp->toArray();
    }




    public function show($idProjet)
    {

        // ✅ Récupération unique du projet
        $projet = DB::table('v_projet_cfps')->where('idProjet', $idProjet)->first();

        if (!$projet) {
            abort(404, 'Projet non trouvé');
        }


        // ✅ Récupération des sessions et leur durée en une seule requête
        $seances = DB::table('v_seances')
            ->where('idProjet', $idProjet)
            ->orderBy('dateSeance', 'asc')
            ->get([
                'idSeance',
                'dateSeance',
                'heureDebut',
                'heureFin',
                'idProjet',
                'idModule',
                DB::raw("TIME_FORMAT(SEC_TO_TIME(TIME_TO_SEC(intervalle_raw)), '%H:%i') AS intervalle_raw")
            ]);

        // ✅ Obtenir les dates de sessions en une seule requête
        $datesSession = $seances->pluck('dateSeance');
        $dateDebut = DB::table('v_projet_cfps')->where('idProjet', $idProjet)->value('dateDebut');
        $dateFin = DB::table('v_projet_cfps')->where('idProjet', $idProjet)->value('dateFin');

        $deb = $datesSession->first() ? Carbon::parse($datesSession->first())->locale('fr')->translatedFormat('l j F Y') : Carbon::parse($dateDebut)->locale('fr')->translatedFormat('l j F Y');
        $fin = $datesSession->last() ? Carbon::parse($datesSession->last())->locale('fr')->translatedFormat('l j F Y') : Carbon::parse($dateFin)->locale('fr')->translatedFormat('l j F Y');

        // ✅ Calcul du total des heures en une requête
        $totalSession = DB::table('v_seances')
            ->where('idProjet', $idProjet)
            ->selectRaw("IFNULL(TIME_FORMAT(SEC_TO_TIME(SUM(TIME_TO_SEC(intervalle_raw))), '%H:%i'), '00:00') as sumHourSession")
            ->value('sumHourSession');

        // ✅ Regroupement des autres données générales
        $generalData = DB::table('v_seances')
            ->where('idProjet', $idProjet)
            ->groupBy('idProjet')
            ->selectRaw("COUNT(DISTINCT dateSeance) as countDate")
            ->first();

        // ✅ Récupération des modules sans répéter les appels SQL
        $modules = DB::table('mdls')
            ->where('moduleName', '!=', 'Default module')
            ->where('idCustomer', Customer::idCustomer())
            ->orderBy('moduleName', 'asc')
            ->get(['idModule', 'moduleName AS module_name']);

        // ✅ Récupération des infos d’évaluation en une seule requête
        $evaluations = DB::table('v_evaluation_alls')
            ->where('idProjet', $idProjet)
            ->groupBy('idProjet')
            ->selectRaw("COUNT(idEmploye) as countNotationProjet, IFNULL(AVG(generalApreciate), 0) as noteGeneral")
            ->first() ?? (object) ['countNotationProjet' => 0, 'noteGeneral' => 0];


        // ✅ Récupération des places
        $placeData = DB::table('inters')
            ->where('idProjet', $idProjet)
            ->select(['nbPlace'])
            ->first();

        $nbPlace = $placeData->nbPlace ?? null;
        $place_available = $this->getPlaceAvailable($idProjet) ?? null;
        $place_reserved = $this->getNbPlaceReserved($idProjet) ?? null;

        // ✅ Récupération des autres données en une seule fois
        $apprenantInter = DB::table('v_list_apprenant_inter_added')->where('idProjet', $idProjet)->get();
        $villes = DB::table('villes')->get(['idVille', 'ville']);
        $paiements = DB::table('paiements')->get(['idPaiement', 'paiement']);
        $modalites = DB::table('modalites')->get(['idModalite', 'modalite']);

        // ✅ Récupération des restaurations avec JOIN
        $restaurations = DB::table('project_restaurations AS pr')
            ->join('restaurations AS rst', 'pr.idRestauration', '=', 'rst.idRestauration')
            ->where('idProjet', $idProjet)
            ->get(['pr.idRestauration', 'rst.typeRestauration', 'pr.paidBy']);

        // ✅ Récupération du dossier lié au projet
        $dossier = DB::table('dossiers AS d')
            ->join('projets AS p', 'd.idDossier', '=', 'p.idDossier')
            ->where('p.idProjet', $idProjet)
            ->first(['nomDossier', 'd.idDossier']);

        $nomDossier = $dossier->nomDossier ?? null;
        $idDossier = $dossier->idDossier ?? null;

        // ✅ Récupération des images d'événements
        $imagesMomentums = DB::table('images')
            ->where('idProjet', $idProjet)
            ->where('idTypeImage', 1)
            ->get(['nomImage', 'idImages']);

        // ✅ Récupération des ressources des modules en une seule requête
        $module_ressources = DB::table('module_ressources AS mr')
            ->join('mdls AS m', 'mr.idModule', 'm.idModule')
            ->join('projets AS p', 'p.idModule', 'm.idModule')
            ->where('p.idProjet', $idProjet)
            ->get(['idModuleRessource', 'taille', 'module_ressource_name', 'file_path', 'module_ressource_extension', 'mr.idModule']);

        $idCfp = Customer::idCustomer();

        $objectifs = DB::table('objectif_modules')->select('idObjectif', 'objectif', 'idModule')->get();
        $materiels = DB::table('prestation_modules')
            ->select('idPrestation', 'prestation_name', 'idModule')
            ->get();

        $prerequis = DB::table('prerequis_modules')
            ->select('idPrerequis', 'prerequis_name', 'idModule')
            ->get();

        return view('CFP.projets.detail', compact(
            'idCfp',
            'objectifs',
            'materiels',
            'prerequis',
            'module_ressources',
            'restaurations',
            'dossier',
            'imagesMomentums',
            'projet',
            'villes',
            'paiements',
            'seances',
            'modules',
            'totalSession',
            'generalData',
            'apprenantInter',
            'modalites',
            'evaluations',
            'nbPlace',
            'place_available',
            'place_reserved',
            'nomDossier',
            'idDossier',
            'deb',
            'fin'
        ));
    }



    public function detailProjetCfpPdf($idProjet)
    {
        $idCfp = Customer::idCustomer();

        $projet = DB::table('v_projet_cfps')
            ->select('idProjet', 'dateDebut', 'dateFin', 'project_title', 'etp_name', 'ville', 'project_status', 'project_description',  'project_type', 'paiement', 'idPaiement', 'project_reference', 'idModalite', 'modalite', 'idEtp', 'etp_initial_name', 'etp_logo', 'idModule', 'module_name', 'module_image', 'project_price_pedagogique', 'project_price_annexe', 'module_description', 'salle_name', 'salle_rue', 'salle_quartier', 'salle_code_postal', 'ville', 'idCfp_inter', 'idCfp', 'modalite', 'idModule', 'idSubContractor',)
            ->where('idProjet', $idProjet)
            ->first();

        $apprenantInter = DB::table('v_list_apprenant_inter_added')
            ->select('*')
            ->where('idProjet', $idProjet)
            ->get();

        $etp = DB::table('v_projet_cfps')->select('idProjet', 'idEtp', 'etp_initial_name', 'etp_name', 'etp_logo', 'etp_email')->where('idProjet', $projet->idProjet)->first();

        $forms = DB::table('v_formateur_cfps')
            ->select('idProjet', 'idFormateur', 'name AS form_name', 'firstName AS form_firstname', 'photoForm AS form_photo', 'email AS form_email', 'initialNameForm AS form_initial_name', 'form_phone')
            ->groupBy('idProjet', 'idFormateur', 'name', 'firstName', 'photoForm', 'email', 'initialNameForm')
            ->where('idProjet', $projet->idProjet)
            ->get();

        $apprs = DB::table('v_list_apprenants as L')
            ->leftJoin('eval_apprenant as E', function ($join) use ($idProjet) {
                $join->on('E.idEmploye', '=', 'L.idEmploye')
                    ->where('E.idProjet', '=', $idProjet);
            })
            ->select(
                'L.idEmploye',
                'emp_initial_name',
                'emp_name',
                'emp_firstname',
                'emp_fonction',
                'emp_email',
                'emp_photo',
                'emp_matricule',
                'etp_name',
                'idEtp',
                'E.avant as avant',
                'E.apres as apres'
            )
            ->where('L.idProjet', $idProjet)
            ->orderBy('emp_name', 'asc')
            ->get();
        // dd($apprs);

        $villes = DB::table('villes')->select('idVille', 'ville')->get();
        $paiements = DB::table('paiements')->select('idPaiement', 'paiement')->get();

        $seances = DB::table('v_seances')
            ->select('idSeance', 'dateSeance', 'heureDebut', 'heureFin', 'idProjet', 'idModule', DB::raw("TIME_FORMAT(SEC_TO_TIME(TIME_TO_SEC(intervalle_raw)), '%H:%i') AS intervalle_raw"))
            ->where('idProjet', $idProjet)
            ->orderBy('dateSeance', 'asc')
            ->get();

        $debSession = DB::table('v_seances')
            ->select('dateSeance as dateDebut')
            ->where('idProjet', $idProjet)
            ->orderBy('dateDebut', 'asc')
            ->pluck('dateDebut')
            ->first();

        $finSession = DB::table('v_seances')
            ->select('dateSeance as dateFin')
            ->where('idProjet', $idProjet)
            ->orderBy('dateFin', 'desc')
            ->pluck('dateFin')
            ->first();

        $countDate = DB::table('v_seances')
            ->select('idProjet', 'dateSeance', 'idSeance', DB::raw('COUNT(*) as count'))
            ->where('idProjet', $idProjet)
            ->groupBy('dateSeance')
            ->get();

        $totalSession = DB::table('v_seances')
            ->selectRaw("IFNULL(TIME_FORMAT(SEC_TO_TIME(SUM(TIME_TO_SEC(intervalle_raw))), '%H:%i'), '00:00') as sumHourSession")
            ->where('idProjet', $idProjet)
            ->groupBy('idProjet')
            ->first();


        $deb =  Carbon::parse($projet->dateDebut)->locale('fr')->translatedFormat('l j F Y');
        $fin =  Carbon::parse($projet->dateFin)->locale('fr')->translatedFormat('l j F Y');

        $modules = DB::table('mdls')
            ->select('idModule', 'moduleName AS module_name')
            ->where('moduleName', '!=', 'Default module')
            ->where('idCustomer', Customer::idCustomer())
            ->orderBy('moduleName', 'asc')
            ->get();

        // Matériel - Prérequis - Objectif pour le projet
        $materiels = DB::table('prestation_modules')
            ->select('idPrestation', 'prestation_name', 'idModule')
            ->get();

        $prerequis = DB::table('prerequis_modules')
            ->select('idPrerequis', 'prerequis_name', 'idModule')
            ->get();

        $objectifs = DB::table('objectif_modules')->select('idObjectif', 'objectif', 'idModule')->get();

        $emargements = DB::table('emargements')
            ->select('idProjet', 'idEmploye', 'idSeance', 'isPresent')
            ->where('idProjet', $idProjet)
            ->get();

        $eval_content = DB::table('questions')
            ->select('idQuestion', 'question', 'idTypeQuestion')
            ->get();

        $eval_type = DB::table('questions')
            ->select('idQuestion', 'question', 'idTypeQuestion')
            ->groupBy('idTypeQuestion')
            ->get();

        $modalites = DB::table('modalites')->select('idModalite', 'modalite')->get();

        $checkEvaluation = DB::table('eval_chauds')->select('idProjet')->get();
        $checkEvaluationCount = count($checkEvaluation);

        if ($checkEvaluationCount > 0) {
            $notationProjet = DB::table('v_evaluation_alls')
                ->select('idProjet', 'idEmploye', 'generalApreciate')
                ->where('idProjet', $idProjet)
                ->groupBy('idProjet', 'idEmploye')
                ->get();

            $generalNotation = DB::table('v_general_note_evaluation')
                ->select(DB::raw('SUM(generalApreciate) as generalNote'))
                ->where('idProjet', $idProjet)
                ->first();

            $countNotationProjet = count($notationProjet);

            if ($countNotationProjet > 0) {
                $noteGeneral = $generalNotation->generalNote / $countNotationProjet;
            } else {
                $noteGeneral = 0;
            }
        } else {
            $countNotationProjet = 0;
            $noteGeneral = 0;
        }

        $imagesMomentums = DB::table('images')
            ->select('url', 'idImages')
            ->where('idProjet', $idProjet)
            ->where('idTypeImage', 1)
            ->get();

        $nbPl = DB::table('inters')->select('nbPlace')->where('idProjet', $idProjet)->first();
        $place_available = $this->getPlaceAvailable($idProjet) ?? null;
        $place_reserved = $this->getNbPlaceReserved($idProjet) ?? null;
        $nbPlace = $nbPl->nbPlace ?? null;

        $restaurations = DB::table('project_restaurations AS pr')
            ->select('pr.idRestauration', 'rst.typeRestauration')
            ->join('restaurations AS rst', 'pr.idRestauration', 'rst.idRestauration')
            ->where('idProjet', $idProjet)
            ->get();

        $pdf = PDF::loadView('CFP.projets.detailProjetCfpPdf', compact(['restaurations', 'imagesMomentums', 'projet', 'villes', 'paiements', 'seances', 'modules', 'materiels', 'objectifs', 'totalSession', 'countDate', 'emargements', 'apprenantInter', 'modalites', 'prerequis', 'eval_content', 'eval_type', 'countNotationProjet', 'noteGeneral', 'nbPlace', 'place_available', 'place_reserved', 'idCfp', 'deb', 'fin', 'etp', 'forms', 'apprs']));
        return $pdf->download($projet->module_name . '.pdf');
        // return view('CFP.projets.detailProjetCfpPdf');
    }

    private function getEtpNameProjectInter($idProjet, $idCfp_inter)
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
        return $etp;
    }


    public function detailsJson($idProjet)
    {
        $projet = DB::table('v_projet_cfps')
            ->select(
                'idProjet',
                'dateDebut',
                'dateFin',
                'project_title',
                'etp_name',
                'ville',
                'idModule',
                'project_status',
                'project_type',
                'module_image',
                'paiement',
                'project_reference',
                'idModalite',
                'modalite',
                'idEtp',
                'salle_quartier',
                'salle_code_postal',
            )
            ->where('idProjet', $idProjet)
            ->first();

        $villes = DB::table('villes')->select('idVille', 'ville')->get();
        $paiements = DB::table('paiements')->select('idPaiement', 'paiement')->get();

        $forms = DB::table('v_formateur_cfps')
            ->select('idProjet', 'idFormateur', 'name AS form_name', 'firstName AS form_firstname', 'photoForm AS form_photo', 'initialNameForm AS form_initial_name', 'email')
            ->groupBy('idProjet', 'idFormateur', 'name', 'firstName', 'photoForm', 'initialNameForm')
            ->where('idProjet', $idProjet)
            ->get();

        $apprs = DB::table('v_list_apprenants')
            ->select('idEmploye', 'emp_initial_name', 'emp_name', 'emp_firstname', 'emp_email', 'emp_photo', 'emp_matricule', 'emp_phone', 'etp_name')
            ->where('idProjet', $idProjet)
            ->orderBy('emp_name', 'asc')
            ->get();

        $apprenantInterCount = DB::table('v_list_apprenant_inter_added')
            ->select('idProjet')
            ->where('idProjet', $idProjet)
            ->get();

        $seances = DB::table('v_seances')
            ->select('idSeance', 'dateSeance', 'heureDebut', 'id_google_seance', 'heureFin', 'idSalle', 'idProjet', 'salle_name', 'salle_quartier', 'project_title', 'project_description', 'idModule', 'salle_code_postal', 'module_name', 'ville')
            ->where('idProjet', $idProjet)
            ->get();
        // Matériel - Prérequis - Objectif pour le projet
        $materiels = DB::table('prestation_modules')
            ->select('idPrestation', 'prestation_name', 'idModule')
            ->where('idModule', $projet->idModule)->get();

        $nameEtps = $this->getEtpNameProjectInter($idProjet, Customer::idCustomer());

        $nameCfp = DB::table('customers')
            ->select('customerName')
            ->where('idCustomer', Customer::idCustomer())->pluck('customerName')
            ->first();

        // dd($projet, $materiels, $nameEtps, $seances);

        return response()->json([
            'project' => $projet,
            //'etps' => $etps,
            'paiements' => $paiements,
            'modalite' => $projet->modalite,
            //'modules' => $modules,
            'villes' => $villes,
            'forms' => $forms,
            'apprenants' => $apprs,
            'apprenantInterCount' => count($apprenantInterCount),
            'materiels' => $materiels,
            'nameEtps' => $nameEtps,
            'reference' => $projet->project_reference,
            'quartier' => $projet->salle_quartier,
            'codePostal' => $projet->salle_code_postal,
            'apprsCount' => count($apprs),
            'seanceCount' => count($seances),
            'nameCfp' => $nameCfp,
        ]);
    }

    public function getFormAssign($idProjet)
    {
        $projet = DB::table('projets')
            ->select('idProjet')
            ->where('idProjet', $idProjet)
            ->first();

        return view('CFP.projets.assign_form', compact('projet'));
    }

    public function formAssign($idProjet, $idFormateur)
    {
        $check = DB::table('project_forms')
            ->select('idProjet', 'idFormateur')
            ->where('idProjet', $idProjet)
            ->where('idFormateur', $idFormateur)
            ->count();

        if ($check <= 0) {
            $insert = DB::table('project_forms')->insert([
                'idProjet' => $idProjet,
                'idFormateur' => $idFormateur
            ]);

            if ($insert) {
                return response()->json(['success' => 'Succès']);
            } else {
                return response()->json(['error' => 'Erreur inconnue !']);
            }
        } else {
            return response()->json(['error' => 'Formateur déjas inscrit au projet !']);
        }
    }

    public function getFormAdded($idProjet)
    {
        $forms = DB::table('v_formateur_cfps')
            ->select('idProjet', 'idFormateur', 'name AS form_name', 'firstName AS form_firstname', 'photoForm AS form_photo', 'email AS form_email', 'initialNameForm AS form_initial_name', 'form_phone')
            ->groupBy('idProjet', 'idFormateur', 'name', 'firstName', 'photoForm', 'email', 'initialNameForm')
            ->where('idProjet', $idProjet)
            ->get();

        return response()->json(['forms' => $forms]);
    }

    public function formRemove($idProjet, $idFormateur)
    {
        try {
            $delete = DB::table('project_forms')->where('idFormateur', $idFormateur)->where('idProjet', $idProjet)->delete();

            if ($delete) {
                return response()->json(['success' => 'Succès']);
            } else {
                return response()->json(['error' => 'Erreur inconnue !']);
            }
        } catch (Exception $e) {
            return response()->json(['error' => 'Erreur !']);
        }
    }

    public function getEtpAssign($idProjet)
    {
        $etp = DB::table('v_projet_cfps')->select('idProjet', 'idEtp', 'etp_initial_name', 'etp_name', 'etp_logo', 'etp_email')->where('idProjet', $idProjet)->first();

        return response()->json(['etp' => $etp]);
    }

    public function etpAssign($idProjet, $idEtp)
    {
        $checkEval = DB::table('eval_chauds')
            ->join('detail_apprenants', 'eval_chauds.idEmploye', '=', 'detail_apprenants.idEmploye')
            ->select('eval_chauds.*')
            ->where('eval_chauds.idProjet', $idProjet)
            ->get();

        $checkPresence = DB::table('emargements')
            ->join('detail_apprenants', 'emargements.idEmploye', '=', 'detail_apprenants.idEmploye')
            ->select('emargements.*')
            ->where('emargements.idProjet', $idProjet)
            ->get();

        try {
            if (count($checkEval) > 0 && count($checkPresence) > 0) {
                DB::beginTransaction();

                //Remove Evaluation
                DB::table('eval_chauds')
                    ->join('detail_apprenants', 'eval_chauds.idEmploye', '=', 'detail_apprenants.idEmploye')
                    ->select('eval_chauds.*')
                    ->where('eval_chauds.idProjet', $idProjet)
                    ->delete();

                //Remove presence
                DB::table('emargements')
                    ->join('detail_apprenants', 'emargements.idEmploye', '=', 'detail_apprenants.idEmploye')
                    ->select('emargements.*')
                    ->where('emargements.idProjet', $idProjet)
                    ->delete();

                DB::table('detail_apprenants')->where('idProjet', $idProjet)->delete();

                DB::table('projets')
                    ->join('intras', 'intras.idProjet', 'projets.idProjet')
                    ->where('projets.idProjet', $idProjet)
                    ->update(['idEtp' => $idEtp]);

                DB::commit();
            } elseif (count($checkEval) > 0 && count($checkPresence) <= 0) {
                DB::beginTransaction();

                //Remove Evaluation
                DB::table('eval_chauds')
                    ->join('detail_apprenants', 'eval_chauds.idEmploye', '=', 'detail_apprenants.idEmploye')
                    ->select('eval_chauds.*')
                    ->where('eval_chauds.idProjet', $idProjet)
                    ->delete();

                DB::table('detail_apprenants')->where('idProjet', $idProjet)->delete();

                DB::table('projets')
                    ->join('intras', 'intras.idProjet', 'projets.idProjet')
                    ->where('projets.idProjet', $idProjet)
                    ->update(['idEtp' => $idEtp]);
                DB::commit();
            } elseif (count($checkEval) <= 0 && count($checkPresence) > 0) {
                DB::beginTransaction();

                //Remove presence
                DB::table('emargements')
                    ->join('detail_apprenants', 'emargements.idEmploye', '=', 'detail_apprenants.idEmploye')
                    ->select('emargements.*')
                    ->where('emargements.idProjet', $idProjet)
                    ->delete();

                DB::table('detail_apprenants')->where('idProjet', $idProjet)->delete();

                DB::table('projets')
                    ->join('intras', 'intras.idProjet', 'projets.idProjet')
                    ->where('projets.idProjet', $idProjet)
                    ->update(['idEtp' => $idEtp]);
                DB::commit();
            } else {
                DB::beginTransaction();
                DB::table('detail_apprenants')->where('idProjet', $idProjet)->delete();

                DB::table('projets')
                    ->join('intras', 'intras.idProjet', 'projets.idProjet')
                    ->where('projets.idProjet', $idProjet)
                    ->update(['idEtp' => $idEtp]);
                DB::commit();
            }
            return response()->json(['success' => 'Succès']);
        } catch (\Throwable $th) {
            return response()->json(['error' => $th->getMessage()]);
        }
    }

    public function mainGetIdEtp($idProjet)
    {
        $projet = DB::table('v_projet_cfps')->select('idProjet', 'idEtp')->where('idProjet', $idProjet)->first();

        return response()->json(['projet' => $projet]);
    }

    public function mainGetIdModule($idProjet)
    {
        $projet = DB::table('v_projet_cfps')->select('idProjet', 'idModule')->where('idProjet', $idProjet)->first();

        return response()->json(['projet' => $projet]);
    }

    public function moduleAssign($idProjet, $idModule)
    {
        $update = DB::table('projets')->where('idProjet', $idProjet)->update(['idModule' => $idModule]);

        if ($update) {
            return response()->json(['success' => 'Succès']);
        } else {
            return response()->json(['error' => 'Erreur inconnue !']);
        }
    }

    public function dateAssign(Request $req, $idProjet)
    {
        $validate = Validator::make($req->all(), [
            'dateDebut' => 'required|date',
            'dateFin' => 'required|date|after_or_equal:dateDebut'
        ]);

        if ($validate->fails()) {
            return response()->json(['error' => $validate->messages()]);
        } else {
            $update = DB::table('projets')->where('idProjet', $idProjet)->update([
                'dateDebut' => $req->dateDebut,
                'dateFin' => $req->dateFin,
                'project_is_reserved' => $req->project_reservation,
            ]);

            if ($update) {
                return response()->json(['success' => 'Succès']);
            } else {
                return response()->json(['error' => 'Erreur inconnue !']);
            }
        }
    }

    public function getSessionProject($idProjet)
    {
        $countSession = DB::table('v_seances')
            ->select('idSeance', 'dateSeance', 'heureDebut', 'id_google_seance', 'heureFin', 'idSalle', 'idProjet', 'salle_name', 'salle_quartier', 'project_title', 'project_description', 'idModule', 'module_name', 'ville')
            ->where('idProjet', $idProjet)
            ->get();

        return count($countSession);
    }

    public function getFormProject($idProjet)
    {
        $forms = DB::table('v_formateur_cfps')
            ->select('idFormateur', 'name AS form_name', 'firstName AS form_firstname', 'photoForm AS form_photo', 'initialNameForm AS form_initial_name')
            ->groupBy('idFormateur', 'name', 'firstName', 'photoForm', 'initialNameForm')
            ->where('idProjet', $idProjet)->get();

        return $forms->toArray();
    }

    public function getApprenantProject($idProjet, $idCfp_inter)
    {
        if ($idCfp_inter == null) {
            $apprs = DB::table('v_list_apprenants')
                ->select('idEmploye', 'emp_initial_name', 'emp_name', 'emp_firstname', 'emp_email', 'emp_photo', 'emp_matricule', 'emp_phone', 'etp_name')
                ->where('idProjet', $idProjet)
                ->orderBy('emp_name', 'asc')
                ->count();
        } elseif ($idCfp_inter != null) {
            $apprs_inter = DB::table('v_list_apprenant_inter_added')
                ->select('idEmploye', 'emp_name', 'emp_firstname', 'emp_fonction', 'emp_email', 'emp_photo', 'emp_matricule', 'etp_name', 'idEtp')
                ->where('idProjet', $idProjet)
                ->orderBy('emp_name', 'asc')
                ->count();

            $parts = $this->getParticulierProject($idProjet, $idCfp_inter);

            $apprs = $apprs_inter + $parts;
        }

        return $apprs;
    }

    public function getParticulierProject($idProjet, $idCfp_inter)
    {
        $parts = []; // Initialiser $parts comme un tableau vide

        if ($idCfp_inter != null) {
            $parts = DB::table('v_particuliers_projet')
                ->select('idParticulier', 'part_name', 'part_firstname', 'part_email', 'part_cin', 'part_matricule', 'part_role_id', 'part_has_role', 'user_is_in_service', 'idProjet')
                ->where('idProjet', $idProjet)
                ->orderBy('part_name', 'asc')
                ->get();
        }
        return count($parts);
    }


    public function getProjectTotalPrice($idProjet)
    {
        $projectPrice = DB::table('v_projet_cfps')
            ->select(DB::raw('SUM(project_price_pedagogique + project_price_annexe) AS project_total_price'))
            ->where('idProjet', $idProjet)
            ->first();

        return $projectPrice->project_total_price;
    }

    public function getProgramme($idModule)
    {
        $programmes = DB::table('programmes')->select('program_title', 'program_description', 'idModule')->where('idModule', $idModule)->get();

        return response()->json(['programmes' => $programmes]);
    }

    public function getModuleRessourceProject($idModule)
    {
        $module_ressources = DB::table('module_ressources')
            ->select('idModuleRessource', 'taille', 'module_ressource_name', 'module_ressource_extension', 'idModule')
            ->where('idModule', $idModule)
            ->get();

        return response()->json(['module_ressources' => $module_ressources]);
    }

    public function destroy($idProjet)
    {
        $query = DB::table('projets')->where('idProjet', $idProjet);

        if ($query->first()) {
            $query->update([
                'project_is_trashed' => 1,
                'project_is_active' => 0,
                'project_is_reserved' => 0,
                'project_is_repported' => 0,
                'project_is_cancelled' => 0,
                'project_is_closed' => 0,
                'project_is_archived' => 0
            ]);

            return response(['success' => 'Opération éffectuée avec succès']);
        } else {
            return response(['error' => 'projet introuvable !'], 404);
        }
    }

    public function updateDate(Request $req, $idProjet)
    {
        if ($req->dateDebut) {
            $validate = Validator::make($req->all(), [
                'dateDebut' => 'required|date'
            ]);

            if ($validate->fails()) {
                return response()->json(['error' => $validate->messages()]);
            } else {
                $update = DB::table('projets')->where('idProjet', $idProjet)->update([
                    'dateDebut' => $req->dateDebut
                ]);

                if ($update) {
                    return response()->json(['success' => 'Opération effectuée avec succès']);
                } else {
                    return response()->json(['error' => 'Erreur inconnue !']);
                }
            }
        } elseif ($req->dateFin) {
            $validate = Validator::make($req->all(), [
                'dateFin' => 'required|date'
            ]);

            if ($validate->fails()) {
                return response()->json(['error' => $validate->messages()]);
            } else {
                $update = DB::table('projets')->where('idProjet', $idProjet)->update([
                    'dateFin' => $req->dateFin
                ]);

                if ($update) {
                    return response()->json(['success' => 'Opération effectuée avec succès']);
                } else {
                    return response()->json(['error' => 'Erreur inconnue !']);
                }
            }
        }
    }

    public function updateModule(Request $req, $idProjet)
    {
        $validate = Validator::make($req->all(), [
            'idModule' => 'required|exists:mdls,idModule'
        ]);

        if ($validate->fails()) {
            return response()->json(['error' => $validate->messages()]);
        } else {
            $update = DB::table('projets')->where('idProjet', $idProjet)->update([
                'idModule' => $req->idModule
            ]);

            if ($update) {
                return response()->json(['success' => 'Opération effectuée avec succès']);
            } else {
                return response()->json(['error' => 'Erreur inconnue !']);
            }
        }
    }

    public function updateFinancement(Request $req, $idProjet, $idCfp_inter)
    {
        $validate = Validator::make($req->all(), [
            'idPaiement' => 'required|exists:paiements,idPaiement'
        ]);

        if ($validate->fails()) {
            return response()->json(['error' => $validate->messages()]);
        } else {
            try {
                if ($idCfp_inter == 0) {
                    $update = DB::table('intras')->where('idProjet', $idProjet)->update([
                        'idPaiement' => $req->idPaiement
                    ]);
                } else {
                    $update = DB::table('inters')->where('idProjet', $idProjet)->update([
                        'idPaiement' => $req->idPaiement
                    ]);
                }
                return response()->json(['success' => 'Opération effectuée avec succès']);
            } catch (Exception $th) {
                dd($th->getMessage());
                return response()->json(['error' => $th->getMessage()]);
            }
        }
    }

    public function updatePrice(Request $req, $idProjet)
    {
        if ($req->project_price_pedagogique) {
            $validate = Validator::make($req->all(), [
                'project_price_pedagogique' => 'required|numeric'
            ]);

            if ($validate->fails()) {
                return response()->json(['error' => $validate->messages()]);
            } else {
                $update = DB::table('projets')->where('idProjet', $idProjet)->update([
                    'project_price_pedagogique' => $req->project_price_pedagogique
                ]);

                if ($update) {
                    return response()->json(['success' => 'Opération effectuée avec succès']);
                } else {
                    return response()->json(['error' => 'Erreur inconnue !']);
                }
            }
        } elseif ($req->project_price_annexe) {
            $validate = Validator::make($req->all(), [
                'project_price_annexe' => 'required|numeric'
            ]);

            if ($validate->fails()) {
                return response()->json(['error' => $validate->messages()]);
            } else {
                $update = DB::table('projets')->where('idProjet', $idProjet)->update([
                    'project_price_annexe' => $req->project_price_annexe
                ]);

                if ($update) {
                    return response()->json(['success' => 'Opération effectuée avec succès']);
                } else {
                    return response()->json(['error' => 'Erreur inconnue !']);
                }
            }
        }
    }

    public function salleAssign($idProjet, $idSalle)
    {
        $idVilleCoded = DB::table('salles')
            ->join('lieux', 'salles.idLieu', 'lieux.idLieu')
            ->select('lieux.idVilleCoded')
            ->where('salles.idSalle', $idSalle)
            ->first();


        DB::table('projets')->where('idProjet', $idProjet)->update([
            'idSalle' => $idSalle,
            'idVilleCoded' => $idVilleCoded->idVilleCoded
        ]);

        return response()->json(['success' => 'Opération effectuée avec succès']);
    }

    public function getSalleAdded($idProjet)
    {
        $idSalleProjet = DB::table('v_projet_cfps')
            ->select('idProjet', 'idSalle')
            ->where('idProjet', $idProjet)
            ->first();

        $salle = DB::table('v_list_salles')
            ->select('idSalle', 'salle_name', 'salle_rue', 'salle_quartier', 'vi_code_postal', 'ville', 'salle_image', 'lieu_name', 'idLieu')
            ->where(function ($query) use ($idSalleProjet) {
                $query->where('idSalle', $idSalleProjet->idSalle)
                    ->where('salle_name', '!=', 'null');
            })
            ->first();

        return response()->json(['salle' => $salle]);
    }

    public function cancel($idProjet)
    {
        $cancel = DB::table('projets')->where('idProjet', $idProjet)->update([
            'project_is_active' => 0,
            'project_is_reserved' => 0,
            'project_is_repported' => 0,
            'project_is_trashed' => 0,
            'project_is_cancelled' => 1,
            'project_is_closed' => 0,
            'project_is_archived' => 0
        ]);

        if ($cancel) {
            return response()->json(['success' => 'Succès']);
        } else {
            return response()->json(['error' => 'Erreur inconnue']);
        }
    }

    public function repport(Request $req, $idProjet)
    {
        $validate = Validator::make($req->all(), [
            'dateDebut' => 'required|date',
            'dateFin' => 'required|date|after_or_equal:dateDebut'
        ]);

        if ($validate->fails()) {
            return response()->json(['error' => $validate->messages()]);
        } else {
            $repport = DB::table('projets')->where('idProjet', $idProjet)->update([
                'dateDebut' => $req->dateDebut,
                'dateFin' => $req->dateFin,
                'project_is_repported' => 1,
                'project_is_active' => 0,
                'project_is_reserved' => 0,
                'project_is_trashed' => 0,
                'project_is_cancelled' => 0,
                'project_is_closed' => 0,
                'project_is_archived' => 0
            ]);

            if ($repport) {
                return response()->json(['success' => 'Succès']);
            } else {
                return response()->json(['error' => 'Erreur inconnue']);
            }
        }
    }

    public function close($idProjet)
    {
        $query = DB::table('projets')->where('idProjet', $idProjet);

        if ($query->first()) {
            $query->update([
                'project_is_active' => 0,
                'project_is_reserved' => 0,
                'project_is_repported' => 0,
                'project_is_trashed' => 0,
                'project_is_cancelled' => 0,
                'project_is_archived' => 0,
                'project_is_closed' => 1
            ]);

            return response()->json(['success' => 'Succès']);
        } else {
            return response()->json(['error' => 'Projet introuvable !']);
        }
    }

    public function checkEmg($idProjet)
    {
        $query = DB::table('emargements')->where('idProjet', $idProjet);

        if ($query) {
            return $query->count();
        } else {
            return null;
        }
    }

    public function checkEval($idProjet)
    {
        $query = DB::table('eval_chauds')->where('idProjet', $idProjet);

        if ($query) {
            return $query->count();
        } else {
            return null;
        }
    }

    public function updateProjet(Request $req, $idProjet)
    {
        $validate = Validator::make($req->all(), [
            // 'project_title' => 'required|min:2|max:150',
            'nbPlace' => 'numeric'
        ]);

        if ($validate->fails()) {
            return response()->json(['error' => $validate->messages()]);
        } else {
            try {
                if ($req->project_type == 'Inter') {
                    DB::table('inters')->where('idProjet', $idProjet)->update([
                        'nbPlace' => $req->nbPlace,
                    ]);
                }

                DB::table('projets')->where('idProjet', $idProjet)->update([
                    'project_reference' => $req->project_reference,
                    'project_title' => $req->project_title,
                    'project_description' => $req->project_description
                ]);

                return response()->json(['success' => 'Modifié avec succès !']);
            } catch (Exception $e) {
                return response()->json(['error' => 'Erreur inconnue !']);
            };
        }
    }

    public function updateNbPlace(Request $req, $idProjet)
    {
        $validate = Validator::make($req->all(), [
            'nbPlace' => 'numeric'
        ]);

        if ($validate->fails()) {
            return response()->json(['error' => $validate->messages()]);
        } else {
            try {
                DB::table('inters')->where('idProjet', $idProjet)->update([
                    'nbPlace' => $req->nbPlace,
                ]);
                return response()->json(['success' => 'Modifié avec succès !']);
            } catch (Exception $e) {
                return response()->json(['error' => 'Erreur inconnue !']);
            };
        }
    }

    //fonction pour selectionne tous les id des formateurs...
    public function getIdFormateur()
    {
        $allId = [];
        // $allId = DB::select("SELECT idFormateur FROM `v_formateur_cfps` WHERE idCfp = ? GROUP BY idFormateur ", [Customer::idCustomer()] );       
        $allId = DB::table('v_formateur_cfps')
            ->select('idFormateur')
            ->where('idCfp', Customer::idCustomer())
            ->groupBy('idFormateur')
            ->get();

        return $allId;
    }

    // Filtres






    // CFP
    public function projets($statut)
    {
        $projets = DB::select('SELECT idProjet, referenceEtp, projectName, idEtp, idCfp, cfp_inter, statut, initialEtpName, etpName, logoEtp, type, idCustomer, modalite, dateDebut, dateFin, moduleName, ville, paiement, isActiveProjet
            FROM v_projet_cfps 
            WHERE idCustomer = ? AND statut = ? ORDER BY idProjet DESC', [Customer::idCustomer(), $statut]);

        return $projets;
    }

    public function fmfp()
    {
        return view('CFP.fmfp.index');
    }

    public function getModule($idFormation)
    {
        $modules = DB::table('mdls')->select('idModule', 'moduleName', 'idFormation')->where('idCustomer', Customer::idCustomer())->where('idFormation', $idFormation)->where('moduleStatut', 1)->get();
        return response()->json(['modules' => $modules]);
    }

    public function storeIntra(Request $req)
    {
        $req->validate([
            'dateDebut' => 'required | date | after_or_equal:today',
            'dateFin' => 'required | date | after_or_equal:dateDebut',
            'idModuleIntra' => 'required | integer',
            'idEntreprise' => 'required | integer',
            'idModePaiement' => 'required | integer',
            'idModalite' => 'required | integer',
            'idVille' => 'required | integer',
        ], [
            'dateDebut.required' => 'Ce champs est obligatoire',
            'dateDebut.after_or_equal' => 'Veuillez entrer une date valide',
            'dateFin.required' => 'Ce champs est obligatoire',
            'dateFin.after' => 'La date de fin est incorrect',
            'idEntreprise.required' => 'Ce champs est obligatoire',
            'idModePaiement.required' => 'Ce champs est obligatoire',
            'idModalite.required' => 'Ce champs est obligatoire',
            'idVille.required' => 'Ce champs est obligatoire',
        ]);

        try {
            DB::beginTransaction();

            $projet = new Projet();
            $projet->dateDebut = $req->dateDebut;
            $projet->dateFin = $req->dateFin;
            $projet->idModalite = $req->idModalite;
            $projet->idCustomer = Customer::idCustomer();
            $projet->idModule = $req->idModuleIntra;
            $projet->idTypeProjet = $req->idTypeProjet;
            $projet->idVille = $req->idVille;
            $projet->isActiveProjet = 0;
            $projet->save();

            $prj = DB::table('projets')->select('idProjet')->orderBy('idProjet', 'desc')->first();

            if ($req->idTypeProjet == 1) {
                DB::table('intras')->insert([
                    'idProjet' => $prj->idProjet,
                    'idPaiement' => $req->idModePaiement,
                    'idEtp' => $req->idEntreprise,
                    'idCfp' => Customer::idCustomer()
                ]);
            } elseif ($req->idTypeProjet == 2) {
                DB::table('inters')->insert([
                    'idProjet' => $prj->idProjet,
                    'idCfp' => Customer::idCustomer()
                ]);
            }

            DB::commit();

            return response()->json([
                'status' => 200,
                'success' => 'Opération effectuée avec succès'
            ]);
        } catch (Exception $e) {
            DB::rollBack();
            return response()->json([
                'status' => 401,
                'error' => "Erreur inconnue !"
            ]);
        }
    }


    public function etpDetailP()
    {
        return view('ETP.projets.detailP');
    }

    public function etpAppr()
    {
        return view('ETP.projets.pages.apprenant');
    }

    public function etpSatisfaction()
    {
        return view('ETP.projets.pages.satisfaction');
    }

    public function programmeEtp()
    {
        return view('ETP.projets.pages.programme');
    }


    public function planning()
    {
        return view('CFP.projets.pages.planning');
    }

    public function programme()
    {
        return view('CFP.projets.pages.programme');
    }

    public function ressourcePage()
    {
        return view('CFP.projets.pages.ressourcePage');
    }

    public function presence()
    {
        return view('CFP.projets.pages.presence');
    }

    public function apprenant()
    {
        return view('CFP.projets.pages.apprenant');
    }

    public function formSatisfaction()
    {
        return view('formateurs.projets.pages.satisfaction');
    }

    public function avis()
    {
        return view('formateurs.projets.pages.avis');
    }

    public function avisEmp()
    {
        return view('employes.projets.pages.avis');
    }

    public function competence()
    {
        return view('CFP.projets.pages.skillsMatrix');
    }
    public function mapGoogle()
    {
        return view('CFP.projets.pages.lieu');
    }
    public function satisfaction()
    {
        return view('CFP.projets.pages.satisfaction');
    }
    public function efficience()
    {
        return view('CFP.projets.pages.efficience');
    }
    public function formateur()
    {
        return view('CFP.projets.pages.formateur');
    }
    public function restauration()
    {
        return view('CFP.projets.pages.restauration');
    }
    public function entreprise()
    {
        return view('CFP.projets.pages.entrepriseMob');
    }
    public function module()
    {
        return view('CFP.projets.pages.moduleMob');
    }

    // CONTROLLER FIRS PROJECT
    public function inviteEtp()
    {
        return view('CFP.projets.firstProject.inviteEtp');
    }

    public function newProject()
    {
        return view('CFP.projets.firstProject.newProject');
    }
    public function createModule()
    {
        return view('CFP.projets.firstProject.createModule');
    }
    public function date()
    {
        return view('CFP.projets.firstProject.date');
    }

    public function inviteFormateur()
    {
        return view('CFP.projets.firstProject.inviteFormateur');
    }

    public function etpPresence()
    {
        return view('ETP.projets.pages.presence');
    }

    // CONTROLLER INTER PROJECT
    public function newProjectInter()
    {
        return view('CFP.projets.inter.index');
    }
    public function createModuleInter()
    {
        return view('CFP.projets.inter.createModuleInter');
    }
    public function dateInter()
    {
        return view('CFP.projets.inter.dateInter');
    }
    public function inviteFormateurInter()
    {
        return view('CFP.projets.inter.inviteFormateurInter');
    }
    public function detailProInter()
    {
        return view('CFP.inter.projetInter.index');
    }


    public function detailTab($idProjet)
    {
        $projet = DB::table('v_projet_cfps')
            ->select('idProjet', 'referenceEtp', 'projectName', 'idEtp', 'idCfp', 'initialEtpName', 'etpName', 'logoEtp', 'etpDescription', 'type', 'dateDebut', 'dateFin', 'moduleName', 'statut')
            ->where('idProjet', $idProjet)
            ->first();

        $formations = DB::select('SELECT idFormation, formation FROM formations  WHERE idCustomer = ?', [Customer::idCustomer()]);
        $etps = DB::select('SELECT idEtp, etpName FROM v_collaboration_cfp_etps WHERE idCfp = ? AND activiteCfp = ? AND activiteEtp = ?', [Customer::idCustomer(), 1, 1]);

        $paiements = DB::table('paiements')
            ->select('idPaiement', 'paiement')
            ->get();

        $modalites = DB::table('modalites')
            ->select('idModalite', 'modalite')
            ->get();

        $villes = DB::table('villes')->select('idVille', 'ville')->get();

        return view('CFP.projets.detail', compact(['projet', 'formations', 'etps', 'paiements', 'modalites', 'villes']));
        // return view('CFP.projets.cardDetail');
    }

    public function getTotalHeure($idProjet)
    {
        $totalHeure = DB::select('SELECT totalHeure FROM v_total_h_projets WHERE idProjet = ?', [$idProjet]);

        return response()->json($totalHeure);
    }

    public function trash($idProjet)
    {
        $update = DB::table('projets')->where('idProjet', $idProjet)->update([
            'isTrashed' => 1
        ]);

        if ($update == 1) {
            return response()->json(['success' => 'Succès']);
        } else {
            return response()->json(['error' => 'Erreur inconnue']);
        }
    }

    public function restore($idProjet)
    {
        $restore = DB::table('projets')->where('idProjet', $idProjet)->where('isTrashed', 1)->update([
            'isTrashed' => 0
        ]);

        if ($restore == 1) {
            return response()->json(['success' => 'Succès']);
        } else {
            return response()->json(['error' => 'Erreur inconnue']);
        }
    }

    public function detailPdf($projetId)
    {
        $project = DB::table('v_projets')
            ->select(
                'idProjet',
                'projectName',
                'sessionName',
                'dateDebut',
                'dateFin',
                'modalite',
                'moduleName',
                'type',
                'statut',
                'idCustomer',
                'idEtp',
                'customerName'
            )
            ->where('idCustomer', '=', Auth::user()->id)
            ->where('idProjet', '=', $projetId)
            ->first();

        $seances = DB::table('seances')
            ->join('projets', 'seances.idProjet', 'projets.idProjet')
            ->join('mdls', 'projets.idModule', 'mdls.idModule')
            ->join('forms', 'seances.idFormateur', 'forms.idFormateur')
            ->join('salles', 'seances.idSalle', 'salles.idSalle')
            ->join('villes', 'salles.idVille', 'villes.idVille')
            ->select(
                'seances.idSeance',
                'seances.dateSeance',
                'seances.heureDebut',
                'seances.heureFin',
                'forms.name',
                'forms.firstName',
                'salles.salle',
                'mdls.moduleName',
                'villes.ville'
            )
            ->where('seances.idProjet', '=', $projetId)
            ->get();

        $formateurs = DB::select('SELECT
            cfp_formateurs.idFormateur, forms.name, forms.firstName 
            FROM cfp_formateurs 
            INNER JOIN formateurs ON cfp_formateurs.idFormateur = formateurs.idFormateur 
            INNER JOIN forms ON formateurs.idFormateur = forms.idFormateur 
            INNER JOIN cfps ON cfp_formateurs.idCfp = cfps.idCustomer 
            WHERE cfp_formateurs.idCfp = ?
            AND isActiveFormateur = ?
            AND isActiveCfp = ?', [Auth::user()->id, 1, 1]);

        $idVille = DB::table('projets')
            ->join('villes', 'projets.idVille', 'villes.idVille')
            ->select('projets.idVille')
            ->where('projets.idProjet', '=', $projetId)
            ->first();

        $salles = DB::table('salles')
            ->join('customers', 'salles.idCustomer', 'customers.idCustomer')
            ->select('customers.idCustomer as customerId', 'salles.salle', 'salles.idSalle as salleId')
            ->where('salles.idCustomer', '=', Auth::user()->id)
            ->where('salles.idVille', '=', $idVille->idVille)
            ->get();

        $typeR = DB::table('type_ressources')
            ->select('idType', 'type')
            ->get();

        $apprenants = DB::table('detail_apprenants')
            ->join('projets', 'detail_apprenants.idProjet', 'projets.idProjet')
            ->join('apprenants', 'detail_apprenants.idEmploye', 'apprenants.idEmploye')
            ->join('employes', 'apprenants.idEmploye', 'employes.idEmploye')
            ->join('fonctions', 'employes.idFonction', 'fonctions.idFonction')
            ->select(
                'employes.matricule',
                'employes.name',
                'employes.firstName',
                'employes.mailEmp',
                'employes.phoneEmp',
                'detail_apprenants.idProjet',
                'fonctions.fonction',
                'employes.idCustomer'
            )
            ->where('detail_apprenants.idProjet', '=', $projetId)
            ->get();

        $pdf = Pdf::loadView('CFP.projets.pdfs.detail', compact('project', 'seances', 'formateurs', 'salles', 'typeR', 'apprenants'))->setPaper('a4', 'landscape');
        return $pdf->download('Fiche_technique.pdf');
    }

    // DEBUT ETP
    public function indexFmfp()
    {
        return view('ETP.fmfp.index');
    }


    public function getProjetEtp($statut)
    {
        $projets = DB::table('v_union_projets')
            ->select('idProjet', 'projectName', 'dateDebut', 'dateFin', 'modalite', 'idFormation', 'formation', 'moduleName', 'type', 'paiement', 'statut', 'idEtp as idCustomer', 'cfpName as customerName', 'ville', 'isActiveProjet')
            ->where('idEtp', Customer::idCustomer())
            ->where('statut', $statut)
            ->get();

        $countProjetE = DB::table('v_union_projets')
            ->select('idProjet')
            ->where('idEtp', Customer::idCustomer())
            ->where('statut', "En cours")
            ->where('isActiveProjet', 1)
            ->count();

        $countProjetP = DB::table('v_union_projets')
            ->select('idProjet')
            ->where('idEtp', Customer::idCustomer())
            ->where('statut', "Prévisionnel")
            ->where('isActiveProjet', 1)
            ->count();

        $countProjetB = DB::table('v_union_projets')
            ->select('idProjet')
            ->where('idEtp', Customer::idCustomer())
            ->where('statut', "Brouillant")
            ->where('isActiveProjet', 1)
            ->count();

        $countProjetT = DB::table('v_union_projets')
            ->select('idProjet')
            ->where('idEtp', Customer::idCustomer())
            ->where('statut', "Terminée")
            ->where('isActiveProjet', 1)
            ->count();

        return response()->json([
            'projets' => $projets,
            'countProjetE' => $countProjetE,
            'countProjetP' => $countProjetP,
            'countProjetB' => $countProjetB,
            'countProjetT' => $countProjetT,
        ]);
    }

    public function indexEtpFini()
    {
        return view('ETP.projets.projetsFini');
    }

    public function indexEtp()
    {
        // $formations = DB::select('SELECT formations.idFormation, formations.formation FROM formations WHERE formations.idCustomer = ?', [Customer::idCustomer()]);

        // $modalites = DB::table('modalites')->select('idModalite', 'modalite')->get();

        // $villes = DB::table('villes')->select('idVille', 'ville')->get();

        // $checkMdls = DB::select("SELECT COUNT(idModule) AS nbMdl FROM v_module_etps WHERE idCustomer = ?", [Customer::idCustomer()]);

        // $projets = DB::table('v_union_projets')
        //     ->select('idProjet', 'projectName', 'dateDebut', 'dateFin', 'modalite', 'idFormation', 'formation', 'type', 'paiement', 'statut', 'idEtp as idCustomer', 'cfpName as customerName', 'ville', 'isActiveProjet')
        //     ->where('idEtp', Customer::idCustomer())
        //     ->get();

        // return view('ETP.projets.index', compact(['formations', 'modalites', 'villes']));

        $projects = DB::table('v_projet_cfps')
            ->select('idProjet', 'dateDebut', 'dateFin', 'module_name', 'etp_name', 'ville', 'project_status', 'project_type', 'paiement', DB::raw('DATE_FORMAT(dateDebut, "%M, %Y") AS headDate'), 'module_image', 'etp_logo', 'etp_initial_name', 'salle_name', 'salle_quartier', 'salle_code_postal', 'ville', 'idCfp_inter', 'modalite')
            ->where(function ($query) {
                $query->where('idCfp', Customer::idCustomer())
                    ->orWhere('idCfp_inter', Customer::idCustomer());
            })
            ->orderBy('dateDebut', 'asc')
            ->paginate(10);

        $projectPaginates = DB::table('v_projet_cfps')->orderBy('dateDebut', 'asc')->where(function ($query) {
            $query->where('idCfp', Customer::idCustomer())
                ->orWhere('idCfp_inter', Customer::idCustomer());
        })->paginate(10);

        $projets = [];
        foreach ($projects as $project) {
            $projets[] = [
                'nbDocument' => $this->getNombreDocument($project->idProjet),
                'seanceCount' => $this->getSessionProject($project->idProjet),
                'formateurs' => $this->getFormProject($project->idProjet),
                'apprCount' => $this->getApprenantProject($project->idProjet, $project->idCfp_inter),
                'projectTotalPrice' => $this->getProjectTotalPrice($project->idProjet),
                'idProjet' => $project->idProjet,
                'dateDebut' => $project->dateDebut,
                'dateFin' => $project->dateFin,
                'module_name' => $project->module_name,
                'etp_name' => $this->getEtpProjectInter($project->idProjet, $project->idCfp_inter),
                'ville' => $project->ville,
                'project_status' => $project->project_status,
                'project_type' => $project->project_type,
                'paiement' => $project->paiement,
                'modalite' => $project->modalite,
                'headDate' => $project->headDate,
                'module_image' => $project->module_image,
                'etp_logo' => $project->etp_logo,
                'etp_initial_name' => $project->etp_initial_name,
                'salle_name' => $project->salle_name,
                'salle_quartier' => $project->salle_quartier,
                'salle_code_postal' => $project->salle_code_postal,
                'ville' => $project->ville,
                'project_description' => $project->project_description,
                'total_ht' => $project->total_ht,
                'total_ttc' => $project->total_ttc,
                'totalSessionHour' => $this->getSessionHour($project->idProjet),
                'general_note' => $this->getNote($project->idProjet),
                'idModule' => $project->idModule,
                'restaurations' => $this->getRestauration($project->idProjet),
                'idCfp_inter' => $project->idCfp_inter,
            ];
        }

        $projectDates = DB::table('v_projet_cfps')
            ->select(DB::raw('DATE_FORMAT(dateDebut, "%M, %Y") AS headDate'))
            ->groupBy('headDate')
            ->orderBy('dateDebut', 'asc')
            ->where(function ($query) {
                $query->where('idCfp', Customer::idCustomer())
                    ->orWhere('idCfp_inter', Customer::idCustomer());
            })
            ->get();

        $projetCount = DB::table('v_projet_cfps')->where(function ($query) {
            $query->where('idCfp', Customer::idCustomer())
                ->orWhere('idCfp_inter', Customer::idCustomer());
        })->count();
        // return view('ETP.projets.index');
        return view('ETP.projets.index', compact(['projets', 'projetCount', 'projectPaginates', 'projectDates']));
        // if($checkMdls[0]->nbMdl <= 0){
        //     return redirect('/moduleInternes')->with("addMdl", "Veuillez d'abord ajouter un module");
        // }else{

        // return view('ETP.projets.index', compact(['projetsB', 'projetsP', 'projetsE', 'projetsT', 'formations', 'modalites', 'villes']));
        // }
    }

    public function detailEtp($idProjet)
    {
        $projet = DB::table('v_union_projets')
            ->select('idProjet', 'projectName', 'dateDebut', 'dateFin', 'dureeH', 'dureeJ', 'modalite', 'idFormation', 'formation', 'moduleName', 'type', 'paiement', 'statut', 'idEtp as idCustomer', 'cfpName as customerName', 'ville', 'isActiveProjet')
            ->where('idEtp', Customer::idCustomer())
            ->where('idProjet', $idProjet)
            ->first();

        $seances = DB::table('v_union_seanceEtps')
            ->select('idProjet', 'idSeance', 'dateSeance', 'heureDebut', 'heureFin', 'initialNameForm', 'nameForm', 'firstNameForm', 'photoForm', 'salle', 'quartier', 'ville', 'moduleName')
            ->where('idEtp', Customer::idCustomer())
            ->where('idProjet', $idProjet)
            ->get();

        $countSeances = DB::table('v_union_seanceEtps')
            ->select('idSeance')
            ->where('idEtp', Customer::idCustomer())
            ->where('idProjet', $idProjet)
            ->count();

        $countApprs = DB::table('v_list_apprenants')
            ->select('idEmploye')
            ->where('idProjet', $idProjet)
            ->count();

        return response()->json([
            'projet' => $projet,
            'seances' => $seances,
            'countSeances' => $countSeances,
            'countApprs' => $countApprs
        ]);
    }

    public function editEtp($projetId)
    {
        $project = DB::table('projets')
            ->join('formations', 'projets.idFormation', 'formations.idFormation')
            ->select('idProjet', 'projectName', 'dateDebut', 'dateFin', 'projets.idFormation', 'formations.formation', 'projets.idCustomer')
            ->where('projets.idCustomer', Customer::idCustomer())
            ->where('idProjet', $projetId)
            ->first();

        $formations = DB::select('SELECT idFormation, formation FROM formations WHERE idCustomer = ? AND idTypeFormation = ?', [Auth::user()->id, 2]);

        return view('ETP.projets.edit', compact(['project', 'formations']));
    }

    public function updateEtp(Request $req, $idProjet)
    {
        $req->validate([
            'dateDebut' => 'required',
            'dateFin' => 'required',
            'idFormation' => 'required'
        ]);

        DB::table('projets')
            ->join('formations', 'projets.idFormation', 'formations.idFormation')
            ->where('projets.idCustomer', Customer::idCustomer())
            ->where('idProjet', $idProjet)
            ->update([
                'projets.dateDebut' => $req->dateDebut,
                'projets.dateFin' => $req->dateFin,
                'projets.idFormation' => $req->idFormation
            ]);

        return redirect('projetEtps')->with('successMod', 'Modification avec succès');
    }

    // Employe
    public function getProjetEmp($statut)
    {
        $projets = DB::table('v_projet_emps')
            ->select('idProjet', 'idEmploye', 'dateDebut', 'dateFin', 'isActiveProjet', 'formation', 'moduleName', 'customerName')
            ->where('idEmploye', Auth::user()->id)
            ->where('isActiveProjet', 1)
            ->where('statut', $statut)
            ->get();

        $countProjetE = DB::table('v_projet_emps')
            ->select('idProjet')
            ->where('idEmploye', Auth::user()->id)
            ->where('statut', "En cours")
            ->where('isActiveProjet', 1)
            ->count();

        $countProjetP = DB::table('v_projet_emps')
            ->select('idProjet')
            ->where('idEmploye', Auth::user()->id)
            ->where('statut', "Prévisionnel")
            ->where('isActiveProjet', 1)
            ->count();

        $countProjetB = DB::table('v_projet_emps')
            ->select('idProjet')
            ->where('idEmploye', Auth::user()->id)
            ->where('statut', "Brouillant")
            ->where('isActiveProjet', 1)
            ->count();

        $countProjetT = DB::table('v_projet_emps')
            ->select('idProjet')
            ->where('idEmploye', Auth::user()->id)
            ->where('statut', "Terminée")
            ->where('isActiveProjet', 1)
            ->count();

        return response()->json([
            'projets' => $projets,
            'countProjetE' => $countProjetE,
            'countProjetP' => $countProjetP,
            'countProjetB' => $countProjetB,
            'countProjetT' => $countProjetT,
        ]);
    }


    public function indexEmp()
    {
        // Vérifier si l'utilisateur est authentifié
        if (!Auth::check()) {
            abort(401, 'Vous devez être authentifié pour accéder à cette ressource.');
        }

        $userId = Auth::user()->id;

        $projects = DB::table('v_projet_emps')
            ->select('idProjet', 'dateDebut', 'dateFin', 'module_name', 'etp_name', 'ville', 'project_status', 'project_description', 'project_type', DB::raw('DATE_FORMAT(dateDebut, "%M, %Y") AS headDate'), 'module_image', 'etp_logo', 'etp_initial_name', 'salle_name', 'salle_quartier', 'salle_code_postal', 'ville', 'idCfp_inter')
            ->where('idEmploye', $userId)
            // ->where('headYear', Carbon::now()->format('Y'))
            ->where('module_name', '!=', 'Default module')
            ->orderBy('dateDebut', 'asc')
            ->get();
        $projets = [];
        foreach ($projects as $project) {
            $projets[] = [
                'nbDocument' => $this->getNombreDocument($project->idProjet),
                'seanceCount' => $this->getSessionProject($project->idProjet),
                'formateurs' => $this->getFormProject($project->idProjet),
                'apprCount' => $this->getApprenantProject($project->idProjet, $project->idCfp_inter),
                'projectTotalPrice' => $this->getProjectTotalPrice($project->idProjet),
                'totalSessionHour' => $this->getSessionHour($project->idProjet),
                'idProjet' => $project->idProjet,
                'dateDebut' => $project->dateDebut,
                'dateFin' => $project->dateFin,
                'module_name' => $project->module_name,
                'etp_name' => $this->getEtpAssign($project->idProjet),
                'ville' => $project->ville,
                'project_status' => $project->project_status,
                'project_type' => $project->project_type,
                'project_description' => $project->project_description,
                'headDate' => $project->headDate,
                'module_image' => $project->module_image,
                'etp_logo' => $project->etp_logo,
                'etp_initial_name' => $project->etp_initial_name,
                'salle_name' => $project->salle_name,
                'salle_quartier' => $project->salle_quartier,
                'salle_code_postal' => $project->salle_code_postal,
                'ville' => $project->ville,
                'totalSessionHour' => $this->getSessionHour($project->idProjet),
                'general_note' => $this->getNote($project->idProjet),
                //'idModule' => $project->idModule,
                'restaurations' => $this->getRestauration($project->idProjet),
                'idCfp_inter' => $project->idCfp_inter,
            ];
        }
        $projectDates = DB::table('v_projet_emps')
            ->select(DB::raw('DATE_FORMAT(dateDebut, "%M, %Y") AS headDate'))
            ->groupBy('headDate')
            ->orderBy('dateDebut', 'asc')
            ->where('idEmploye', $userId) // Condition pour filtrer par utilisateur
            // ->where('headYear', Carbon::now()->format('Y'))
            ->where('module_name', '!=', 'Default module')
            ->get();

        $projetCount = DB::table('v_projet_emps')
            ->where('idEmploye', $userId) // Condition pour filtrer par utilisateur
            // ->where('headYear', Carbon::now()->format('Y'))
            ->where('module_name', '!=', 'Default module')
            ->count();
        // dd($projetCount);
        return view('employes.projets.index', compact(['projets', 'projectDates', 'projetCount']));
    }


    public function detailEmp($idProjet)
    {
        $projet = DB::table('v_projet_cfps')
            ->select('idProjet', 'dateDebut', 'dateFin', 'project_title', 'etp_name', 'ville', 'project_status', 'project_type', 'module_image', 'paiement', 'project_reference', 'project_description', 'idModule', 'modalite', 'idEtp', 'idCfp_inter')
            ->where('idProjet', $idProjet)
            ->first();


        $apprenantInter = DB::table('v_list_apprenant_inter_added')
            ->select('*')
            ->where('idProjet', $idProjet)
            ->get();

        $villes = DB::table('villes')->select('idVille', 'ville')->get();
        $paiements = DB::table('paiements')->select('idPaiement', 'paiement')->get();

        $seances = DB::table('v_seances')
            ->select('idSeance', 'dateSeance', 'heureDebut', 'heureFin', 'idProjet', 'idModule', 'intervalle_raw')
            ->where('idProjet', $idProjet)
            ->orderBy('dateSeance', 'asc')
            ->get();

        $countDate = DB::table('v_seances')
            ->select('idProjet', 'dateSeance', 'idSeance', DB::raw('COUNT(*) as count'))
            ->where('idProjet', $idProjet)
            ->groupBy('dateSeance')
            ->get();

        $totalSession = DB::table('v_seances')
            ->selectRaw('SEC_TO_TIME(SUM(TIME_TO_SEC(intervalle_raw))) as sumHourSession')
            ->where('idProjet', $idProjet)
            ->groupBy('idProjet')
            ->first();

        $modules = DB::table('mdls')
            ->select('idModule', 'moduleName AS module_name')
            ->where('moduleName', '!=', 'Default module')
            ->where('idCustomer', Customer::idCustomer())
            ->orderBy('moduleName', 'asc')
            ->get();

        $apprs = DB::table('v_list_apprenants')
            ->select('idEmploye', 'emp_initial_name', 'emp_name', 'emp_firstname', 'emp_fonction', 'emp_email', 'emp_photo', 'emp_matricule', 'etp_name', 'idEtp')
            ->where('idProjet', $idProjet)
            ->orderBy('emp_name', 'asc')
            ->get();

        $getSeance = DB::table('v_emargement_appr')
            ->select('idSeance', 'idProjet', 'heureDebut', 'heureFin', 'dateSeance')
            ->where('idProjet', $idProjet)
            ->groupBy('idSeance')
            ->get();

        $getAppr = DB::table('v_emargement_appr')
            ->select('idProjet', 'idEmploye', 'name', 'firstName', 'photo')
            ->where('idProjet', $idProjet)
            ->groupBy('idEmploye')
            ->get();

        $getIdAppr = DB::table('v_emargement_appr')
            ->select('idProjet', 'idEmploye', 'idSeance', 'name', 'firstName', 'photo')
            ->where('idProjet', $idProjet)
            ->get();

        $materiels = DB::table('prestation_modules')
            ->select('idPrestation', 'prestation_name', 'idModule')
            ->get();

        $objectifs = DB::table('objectif_modules')->select('idObjectif', 'objectif', 'idModule')->get();

        $emargements = DB::table('emargements')
            ->select('idProjet', 'idEmploye', 'idSeance', 'isPresent')
            ->where('idProjet', $idProjet)
            ->get();

        return view('employes.projets.detail', compact('projet', 'villes', 'paiements', 'seances', 'modules', 'materiels', 'objectifs', 'totalSession', 'countDate', 'apprs', 'getSeance', 'getAppr', 'emargements', 'getIdAppr', 'apprenantInter'));
    }


    // Seance Employes
    public function getAllSeance($idProjet)
    {
        $seances = DB::table('v_seance_emps')
            ->select('idProjet', 'idSeance', 'dateSeance', 'heureDebut', 'heureFin', 'initialNameForm', 'nameForm', 'firstNameForm', 'photoForm', 'nomSalle AS salle', 'quartier', 'ville', 'moduleName')
            ->where('idEmploye', Auth::user()->id)
            ->where('idProjet', $idProjet)
            ->get();

        return response()->json(['seances' => $seances]);
    }

    public function detailEmpPdf($idProjet)
    {
        $projet = DB::table('v_projet_emp')
            ->select(
                'name',
                'firstName',
                'idCustomer',
                'idProjet',
                'projectName',
                'sessionName',
                'dateDebut',
                'dateFin',
                'type',
                'ville',
                'customerName',
                'statut',
                'modalite',
                'moduleName'
            )
            ->where('idProjet', '=', $idProjet)
            ->first();

        $seances = DB::table('v_seances')
            ->select('moduleName', 'ville', 'salle', 'dateSeance', 'heureDebut', 'heureFin', 'nameForm', 'firstNameForm')
            ->where('idProjet', '=', $idProjet)
            ->get();

        $etpId = DB::table('employes')
            ->join('customers', 'employes.idCustomer', 'customers.idCustomer')
            ->select('employes.idCustomer as etpId')
            ->where('employes.idEmploye', '=', Auth::user()->id)
            ->first();

        $pdf = Pdf::loadView('employes.projets.pdfs.detail', compact(['projet', 'seances', 'etpId']))->setPaper('a4', 'landscape');
        return $pdf->download('Fiche_technique.pdf');
    }

    // Ajax
    public function checkFinishF($idSeance, $idEmploye)
    {
        $check = DB::select('SELECT  idSeance FROM detail_emargements WHERE idSeance = ? AND idEmploye = ?', [$idSeance, $idEmploye]);
        $checkFinished = count($check);

        return response()->json($checkFinished);
    }

    public function detailFormInternePdf($idProjet)
    {
        $projet = DB::table('v_projet_form_interne')
            ->select(
                'name',
                'firstName',
                'idProjet',
                'projectName',
                'sessionName',
                'dateDebut',
                'dateFin',
                'type',
                'ville',
                'customerName',
                'statut',
                'modalite',
                'moduleName'
            )
            ->where('idProjet', '=', $idProjet)
            ->first();

        $seances = DB::table('v_seances')
            ->select('moduleName', 'ville', 'salle', 'dateSeance', 'heureDebut', 'heureFin', 'nameForm', 'firstNameForm')
            ->where('idProjet', '=', $idProjet)
            ->get();

        $apprenants = DB::table('detail_apprenants')
            ->join('projets', 'detail_apprenants.idProjet', 'projets.idProjet')
            ->join('apprenants', 'detail_apprenants.idEmploye', 'apprenants.idEmploye')
            ->join('employes', 'apprenants.idEmploye', 'employes.idEmploye')
            ->join('fonctions', 'employes.idFonction', 'fonctions.idFonction')
            ->select(
                'employes.matricule',
                'employes.name',
                'employes.firstName',
                'employes.mailEmp',
                'employes.phoneEmp',
                'detail_apprenants.idProjet',
                'fonctions.fonction',
                'employes.idCustomer'
            )
            ->where('detail_apprenants.idProjet', '=', $idProjet)
            ->get();

        $pdf = Pdf::loadView('formateurInternes.projets.pdfs.detail', compact(['projet', 'seances', 'apprenants']))->setPaper('a4', 'landscape');
        return $pdf->download('Fiche_technique.pdf');
    }

    public function getSessionHour($idProjet)
    {
        $countSessionHour = DB::table('v_seances')
            ->selectRaw("IFNULL(TIME_FORMAT(SEC_TO_TIME(SUM(TIME_TO_SEC(intervalle_raw))), '%H:%i'), '0') as sumHourSession")
            ->where('idProjet', $idProjet)
            ->first();

        return $countSessionHour->sumHourSession;
    }

    public function assignType($idType)
    {
        DB::table('projets')->update([
            'idTypeProjet' => $idType
        ]);

        return response()->json(['success' => 'Opération effectuée avec succès']);
    }

    public function getVille()
    {
        $villes = DB::table('ville_codeds')
            ->select('id as idVille', 'ville_name as ville')
            ->orderBy('ville', 'asc')
            ->get();

        return response()->json(['villes' => $villes]);
    }

    public function updateVille($idProjet, Request $req)
    {
        $update = DB::table('projets')->where('idProjet', $idProjet)->update([
            'idVilleCoded' => $req->idVilleCoded
        ]);

        if ($update) {
            return response()->json(['success' => 'Ville selectionnée avec succès !']);
        } else {
            return response()->json(['error' => 'Erreur inconnue !']);
        }
    }

    public function etpAssignInter($idProjet, $idEtp)
    {
        $idEtpGrp = DB::table('etp_groupeds')->where('idEntrepriseParent', $idEtp)->pluck('idEntreprise')->toArray();
        $idEtpParentGrp = DB::table('etp_groupeds')->where('idEntreprise', $idEtp)->pluck('idEntrepriseParent')->toArray();

        $allIdEtp = [];

        if ($idEtpGrp == []) {
            $allIdEtp = array_merge($idEtpParentGrp, [$idEtp]);
        } else {
            $allIdEtp = array_merge($idEtpGrp, [$idEtp]);
        }

        $check = DB::table('inter_entreprises')->where('idProjet', $idProjet)->whereIn('idEtp', $allIdEtp)->get();
        $checkAppr = DB::table('detail_apprenant_inters')->where('idProjet', $idProjet)->whereIn('idEtp', $allIdEtp)->get();

        if (count($check) < 1 && count($checkAppr) < 1) {

            $insert = DB::table('inter_entreprises')->insert([
                'idProjet' => $idProjet,
                'idEtp' => $idEtp,
            ]);

            return response()->json([
                'success' => 'Entreprise ajoutée avec succès !'
            ]);
        } elseif (count($check) < 1 && count($checkAppr) >= 1) {

            DB::beginTransaction();
            DB::table('detail_apprenant_inters')->where('idProjet', $idProjet)->where('idEtp', $idEtp)->delete();
            DB::table('inter_entreprises')->insert([
                'idProjet' => $idProjet,
                'idEtp' => $idEtp,
            ]);
            DB::commit();

            return response()->json([
                'success' => 'Entreprise ajouté avec succès !'
            ]);
        } elseif (count($check) >= 1) {
            return response()->json([
                'error' => 'Cette entreprise ou une entreprise parent est déjà assignée à ce projet.'
            ]);
        }
    }




    public function removeEtpFraisProjet($idProjet, $idEtp)
    {
        try {
            $delete = DB::table('fraisprojet')->where('idPayeur', $idEtp)->where('idProjet', $idProjet)->delete();

            if ($delete) {
                return response()->json(['success' => 'Succès']);
            } else {
                return response()->json(['info' => 'Pas de frais !']);
            }
        } catch (Exception $e) {
            return response()->json(['error' => 'Erreur !']);
        }
    }

    public function updateModalite(Request $req, $idProjet)
    {
        $validate = Validator::make($req->all(), [
            'idModalite' => 'required|exists:modalites,idModalite'
        ]);

        if ($validate->fails()) {
            return response()->json(['error' => $validate->messages()]);
        } else {
            $update = DB::table('projets')->where('idProjet', $idProjet)->update([
                'idModalite' => $req->idModalite
            ]);

            if ($update) {
                return response()->json(['success' => 'Opération effectuée avec succès']);
            } else {
                return response()->json(['error' => 'Erreur inconnue !']);
            }
        }
    }

    public function getModalite()
    {
        $modalites = DB::table('modalites')
            ->select('idModalite', 'modalite')
            ->orderBy('idModalite', 'asc')
            ->get();

        return response()->json(['modalites' => $modalites]);
    }

    //Particulier

    public function getAllParts(ParticulierService $part)
    {
        $parts = $part->getAll(Customer::idCustomer());

        // if(count($parts) <= 0){
        //     return response([
        //         'status' => 404,
        //         'message' => "Aucun résultat trouvé !"
        //     ]);
        // }

        return response(['parts' => $parts]);
    }

    public function assignPart(Request $request, $idProjet, $idParticulier)
    {
        DB::beginTransaction();

        try {
            // Utiliser les paramètres d'URL directement
            $check = DB::table('particulier_projet')
                ->where('idProjet', $idProjet)
                ->where('idParticulier', $idParticulier)
                ->exists();

            if (!$check) {
                $insert = DB::table('particulier_projet')->insert([
                    'idProjet' => $idProjet,
                    'idParticulier' => $idParticulier,
                    'date_attribution' => now()
                ]);

                if ($insert) {
                    DB::commit();
                    return response()->json(['success' => 'Particulier assigné avec succès.']);
                } else {
                    DB::rollBack();
                    return response()->json(['error' => 'Erreur inconnue lors de l\'assignation.'], 500);
                }
            } else {
                return response()->json(['error' => 'Particulier déjà assigné au projet !'], 400);
            }
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => 'Erreur lors de l\'assignation : ' . $e->getMessage()], 500);
        }
    }

    public function getPartAdded($idProjet)
    {
        try {
            $parts = DB::table('particulier_projet')
                ->join('users', 'particulier_projet.idParticulier', '=', 'users.id')
                ->where('particulier_projet.idProjet', $idProjet)
                ->select('users.id as idParticulier', 'users.name as part_name', 'users.firstName as part_firstname', 'users.email as part_email', 'users.photo as part_photo')
                ->where('idProjet', $idProjet)
                ->get();

            return response()->json(['parts' => $parts]);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Erreur lors de la récupération : ' . $e->getMessage()], 500);
        }
    }


    public function unassignPart($idProjet, $idParticulier)
    {
        try {
            $delete = DB::table('particulier_projet')
                ->where('idProjet', $idProjet)
                ->where('idParticulier', $idParticulier)
                ->delete();

            if ($delete) {
                return response()->json(['success' => 'Succès']);
            } else {
                return response()->json(['error' => 'Erreur inconnue !']);
            }
        } catch (\Exception $e) {
            return response()->json(['error' => 'Erreur !']);
        }
    }

    public function updatePrivacy($idProjet)
    {
        $query = DB::table('inters')->where('idProjet', $idProjet);

        if ($query->first()) {
            if ($query->first()->project_inter_privacy == 0) {
                $query->update(['project_inter_privacy' => 1]);
            } elseif ($query->first()->project_inter_privacy == 1) {
                $query->update(['project_inter_privacy' => 0]);
            }

            return response()->json(['success' => 'Succès']);
        } else {
            return response()->json(['error' => 'Projet introuvable !']);
        }
    }

    // FormateurInterne
    public function indexFormInterne()
    {

        if (!Auth::check()) {
            abort(401, 'Vous devez être authentifié pour accéder à cette ressource.');
        }

        $userId = Auth::user()->id;

        $projects = DB::table('v_projet_internes')
            ->select('idProjet', 'dateDebut', 'dateFin', 'module_name', 'etp_name', 'ville', 'project_status', 'project_description', 'project_type', DB::raw('DATE_FORMAT(dateDebut, "%M, %Y") AS headDate'), 'module_image', 'etp_logo', 'etp_initial_name', 'salle_name', 'salle_quartier', 'salle_code_postal', 'ville', 'idCfp_inter', 'modalite')
            ->where('idFormateur', $userId)
            // ->where('headYear', Carbon::now()->format('Y'))
            ->where('module_name', '!=', 'Default module')
            ->orderBy('dateDebut', 'asc')
            ->get();
        // dd($projects);
        $projets = [];
        foreach ($projects as $project) {
            $projets[] = [
                'nbDocument' => $this->getNombreDocument($project->idProjet),
                'seanceCount' => $this->getSessionProject($project->idProjet),
                'formateurs' => $this->getFormProject($project->idProjet),
                'apprCount' => $this->getApprenantProject($project->idProjet, $project->idCfp_inter),
                'projectTotalPrice' => $this->getProjectTotalPrice($project->idProjet),
                'totalSessionHour' => $this->getSessionHour($project->idProjet),
                'idProjet' => $project->idProjet,
                'dateDebut' => $project->dateDebut,
                'dateFin' => $project->dateFin,
                'module_name' => $project->module_name,
                'etp_name' => $this->getEtpProjectInter($project->idProjet, $project->idCfp_inter),
                'ville' => $project->ville,
                'project_status' => $project->project_status,
                'project_type' => $project->project_type,
                // 'paiement' => $project->paiement,
                'modalite' => $project->modalite,
                'project_description' => $project->project_description,
                'headDate' => $project->headDate,
                'module_image' => $project->module_image,
                'etp_logo' => $project->etp_logo,
                'etp_initial_name' => $project->etp_initial_name,
                'salle_name' => $project->salle_name,
                'salle_quartier' => $project->salle_quartier,
                'salle_code_postal' => $project->salle_code_postal,
                'ville' => $project->ville,
                'etp_name_in_situ' => $project->etp_name,
                'general_note' => $this->getNote($project->idProjet),
                'idModule' => $project->idModule,
                'restaurations' => $this->getRestauration($project->idProjet),
                'idCfp_inter' => $project->idCfp_inter,
            ];
        }

        $projectDates = DB::table('v_projet_internes')
            ->select(DB::raw('DATE_FORMAT(dateDebut, "%M, %Y") AS headDate'))
            ->groupBy('headDate')
            ->orderBy('dateDebut', 'asc')
            // ->where('headYear', Carbon::now()->format('Y'))
            ->where('module_name', '!=', 'Default module')
            ->get();

        $projetCount = DB::table('v_projet_internes')
            // ->where('idFormateur', $userId)
            ->where('headYear', Carbon::now()
                ->format('Y'))
            ->count();

        return view('formateurInternes.projets.index', compact('projects', 'projets', 'projectDates', 'projetCount'));
    }

    public function getEvents()
    {
        $evenements = DB::table('v_projet_internes')
            ->select('dateDebut AS start', 'dateFin AS end', 'project_description AS title')
            // ->where('headYear', Carbon::now()->format('Y'))
            ->where('module_name', '!=', 'Default module')
            ->orderBy('start', 'asc')
            ->get();
        // Remplacez ceci par votre logique pour récupérer les événements
        $events = [];
        foreach ($evenements as $events) {
            $events[] = [
                'title' => $evenements->title,
                'start' => $evenements->start,
                'end' => $evenements->end,
            ];
        }
        //dd($events);
        return response()->json($events);
    }

    public function detailFormInterne($idProjet)
    {

        $userId = Auth::user()->id;
        $projet = DB::table('v_union_projets')
            ->select('idProjet', 'idEtp_inter', 'idCfp_intra', 'idCfp_inter', 'dateDebut', 'dateFin', 'project_title', 'etp_name', 'ville', 'project_status', 'project_description',  'project_type', 'project_reference', 'modalite', 'idEtp', 'etp_initial_name', 'etp_logo', 'idModule', 'module_name', 'module_image', 'module_description', 'salle_name', 'salle_rue', 'salle_quartier', 'salle_code_postal', 'ville', 'modalite')
            ->where('idProjet', $idProjet)
            ->first();

        $apprenantInter = DB::table('v_list_apprenant_inter_added')
            ->select('*')
            ->where('idProjet', $idProjet)
            ->get();

        $villes = DB::table('villes')->select('idVille', 'ville')->get();
        $paiements = DB::table('paiements')->select('idPaiement', 'paiement')->get();

        $seances = DB::table('v_seances')
            ->select('idSeance', 'dateSeance', 'heureDebut', 'heureFin', 'idProjet', 'idModule', 'intervalle_raw')
            ->where('idProjet', $idProjet)
            ->orderBy('dateSeance', 'asc')
            ->get();

        $countDate = DB::table('v_seances')
            ->select('idProjet', 'dateSeance', DB::raw('COUNT(*) as count'))
            ->where('idProjet', $idProjet)
            ->groupBy('dateSeance')
            ->get();

        $totalSession = DB::table('v_seances') //<------ A modifier
            ->selectRaw('SEC_TO_TIME(SUM(TIME_TO_SEC(intervalle_raw))) as sumHourSession')
            ->where('idProjet', $idProjet)
            ->groupBy('idProjet')
            ->first();

        $modules = DB::table('mdls')
            ->select('idModule', 'moduleName AS module_name')
            ->where('moduleName', '!=', 'Default module')
            ->where('idCustomer', $userId)
            ->orderBy('moduleName', 'asc')
            ->get();

        $apprs = DB::table('v_list_apprenants')
            ->select('idEmploye', 'emp_initial_name', 'emp_name', 'emp_firstname', 'emp_fonction', 'emp_email', 'emp_photo', 'emp_matricule', 'etp_name')
            ->where('idProjet', $idProjet)
            ->where('idEmploye', $userId)
            ->orderBy('emp_name', 'asc')
            ->get();

        $getSeance = DB::table('v_emargement_appr')
            ->select('idSeance', 'idProjet', 'heureDebut', 'heureFin', 'dateSeance')
            ->where('idProjet', $idProjet)
            ->groupBy('idSeance')
            ->get();

        $getAppr = DB::table('v_emargement_appr')
            ->select('idProjet', 'idEmploye', 'name', 'firstName', 'photo')
            ->where('idProjet', $idProjet)
            ->groupBy('idEmploye')
            ->get();

        $getIdAppr = DB::table('v_emargement_appr')
            ->select('idProjet', 'idEmploye', 'idSeance', 'name', 'firstName', 'photo')
            ->where('idProjet', $idProjet)
            ->get();

        $materiels = DB::table('prestation_modules')
            ->select('idPrestation', 'prestation_name', 'idModule')
            ->get();

        $objectifs = DB::table('objectif_modules')->select('idObjectif', 'objectif', 'idModule')->get();

        $emargements = DB::table('emargements')
            ->select('idProjet', 'idEmploye', 'idSeance', 'isPresent')
            ->where('idProjet', $idProjet)
            ->get();

        return view('formateurInternes.projets.detail', compact('projet', 'villes', 'paiements', 'seances', 'modules', 'materiels', 'objectifs', 'totalSession', 'countDate', 'apprs', 'getSeance', 'getAppr', 'emargements', 'getIdAppr', 'apprenantInter'));
    }

    public function getMiniCV($idFormateur)
    {
        try {
            if (!Auth::check()) {
                throw new Exception('User is not authenticated.');
            }

            // Vérifier que l'utilisateur a accès aux informations demandées
            $userId = Auth::user()->id;

            $form = DB::table('users')
                ->select('id', 'name', 'email', 'firstName', 'phone', 'photo')
                ->where('id', $idFormateur)
                ->first();

            // Expériences
            $exp = DB::table('experiences')
                ->select('id', 'idFormateur', 'Lieu_de_stage', 'Fonction', 'Date_debut', 'Date_fin', 'Lieu')
                ->where('idFormateur', $idFormateur)
                ->get();

            // Diplômes
            $dp = DB::table('diplomes')
                ->select('id', 'idFormateur', 'Ecole', 'Diplome', 'Domaine', 'Date_debut', 'Date_fin')
                ->where('idFormateur', $idFormateur)
                ->get();

            // Compétences
            $cpc = DB::table('competences')
                ->select('id', 'idFormateur', 'Competence', 'note')
                ->where('idFormateur', $idFormateur)
                ->get();

            // Langues
            $lg = DB::table('langues')
                ->select('id', 'idFormateur', 'Langue', 'note')
                ->where('idFormateur', $idFormateur)
                ->get();

            $speciality = DB::table('formateurs')->select('form_titre')->where('idFormateur', $idFormateur)->first();
        } catch (Exception $e) {
            Log::error($e->getMessage());
            return response()->json(['error' => ['message' => $e->getMessage()]], 500);
        }

        // Retourner les données au format JSON
        return response()->json([
            'form' => $form,
            'experiences' => $exp,
            'diplomes' => $dp,
            'competences' => $cpc,
            'langues' => $lg,
            'speciality' => $speciality
        ]);
    }

    public function duplicate($idProjet)
    {

        $project =  Projet::where('idProjet', $idProjet)->first();


        $newProject = Projet::create([
            'referenceEtp' => $project->referenceEtp,
            'project_reference' => $project->project_reference,
            'project_title' => $project->project_title,
            'projectName' => $project->projectName,
            'dateDebut' => $project->dateDebut,
            'dateFin' => $project->dateFin,
            'dateFin' => $project->dateFin,
            'lieu' => $project->lieu,
            'idVilleCoded' => 1,
            'idModule' => $project->idModule,
            'idCustomer' => $project->idCustomer,
            'idModalite' => $project->idModalite,
            'idTypeProjet' => $project->idTypeProjet,
            'idSalle' => $project->idSalle,
            'project_description' => $project->project_description,
            'project_num_fmfp' => $project->project_num_fmfp,
            'project_is_active' => 0,
            'project_is_reserved' => 0,
            'project_is_cancelled' => 0,
            'project_is_repported' => 0,
            'project_is_trashed' => 0,
            'project_price_pedagogique' => 0,
            'project_price_annexe' => 0,
            'total_ht' => 0,
            'total_ttc' => 0,
        ]);

        if (!$newProject) {
            return response()->json(['error' => 'Erreur inconnue !']);
        }

        $new_idProjet = Projet::latest()->first()->idProjet;


        if ($project->idTypeProjet === 1) {

            $intra = DB::table('intras')->where('idProjet', $idProjet)->first();
            $insert_intra = DB::table('intras')->insert([
                'idProjet' => $new_idProjet,
                'idPaiement' => $intra->idPaiement,
                'idEtp' => $intra->idEtp,
                'idCfp' => $intra->idCfp
            ]);
            if (!$insert_intra) {
                DB::table('projets')->where('idProjet', $new_idProjet)->delete();
                return response()->json(['error' => 'Erreur inconnue !']);
            }
        }

        if ($project->idTypeProjet === 2) {

            $inters = DB::table('inters')->where('idProjet', $idProjet)->first();
            $insert_inters = DB::table('inters')->insert([
                'idProjet' => $new_idProjet,
                'idPaiement' => $inters->idPaiement,
                'idCfp' => $inters->idCfp
            ]);
            if (!$insert_inters) {
                DB::table('projets')->where('idProjet', $new_idProjet)->delete();
                return response()->json(['error' => 'Erreur inconnue !']);
            }

            $inter_entreprises = DB::table('inter_entreprises')->where('idProjet', $idProjet)->get();
            foreach ($inter_entreprises as $inter_entreprise) {
                $insert_inter_entreprise = DB::table('inter_entreprises')->insert([
                    'idProjet' => $new_idProjet,
                    'idEtp' => $inter_entreprise->idEtp
                ]);
                if (!$insert_inter_entreprise) {
                    DB::table('projets')->where('idProjet', $new_idProjet)->delete();
                    return response()->json(['error' => 'Erreur inconnue !']);
                }
            }
        }
        return response()->json(['success' => 'Projet dupliquer avec succès']);
    }

    public function uploadPhotoMomentum(Request $request)
    {
        // Ajuster les paramètres PHP
        ini_set('upload_max_filesize', '5M');
        ini_set('post_max_size', '50M');
        ini_set('memory_limit', '256M');
        ini_set('max_execution_time', '300');
        ini_set('max_input_time', '300');

        $driver = new Driver();
        $manager = new ImageManager($driver);

        $validate = Validator::make($request->all(), [
            'myFile.*' => 'required|image|max:5120', // Validation Laravel (taille en KB)
        ]);

        if ($validate->fails()) {
            return back()->with(['error' => $validate->messages()]);
        }

        $files = $request->file('myFile');
        $idProjet = $request->idProjet;
        $maxFileSize = 5 * 1024 * 1024; // 5 MB
        $urls = [];

        if ($files) {
            foreach ($files as $file) {
                if ($file->getSize() > $maxFileSize) {
                    return response()->json(['error' => 'L\'un des fichiers est trop grand. La taille maximale autorisée est de 5 MB par fichier.']);
                }

                try {
                    $image = $manager->read($file)->toWebp(25);

                    $disk = Storage::disk('do');
                    $filename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME) . '.webp';
                    $path = 'img/momentum/' . $idProjet . '/' . $filename;

                    $disk->put($path, $image->__toString());

                    $url = $disk->url($path);
                    $urls[] = $url;

                    DB::table('images')->insert([
                        'idTypeImage' => 1,
                        'idProjet' => $idProjet,
                        'url' => $url,
                        'path' => $path,
                        'nomImage' => $filename,
                    ]);
                } catch (\Exception $e) {
                    Log::error('Erreur lors du traitement de l\'image : ' . $e->getMessage(), [
                        'file' => $file->getClientOriginalName(),
                        'idProjet' => $idProjet,
                    ]);

                    return back()->with('error', 'Une erreur est survenue lors du traitement de l\'image. Vérifiez les logs pour plus de détails.');
                }
            }

            return back()->with('success', 'Photos téléchargées avec succès');
        }

        return back()->with(['error' => 'Aucun fichier n\'a été téléchargé.']);
    }

    public function showmomentum($idProjet, Request $request)
    {
        $idProjet = $request->idProjet;

        $images = DB::table('images')
            ->select('idProjet', 'idImages', 'url', 'nomImage')
            ->where('idProjet', $idProjet)
            ->where('idTypeImage', 1)
            ->get();

        if ($images->isEmpty()) {
            return redirect()->route('cfp.projets.showmomentum', ['idProjet' => $idProjet]);
        }

        return view('CFP.projets.photo_momentum', compact('images', 'idProjet'));
    }

    public function destroyPhoto($idProjet, $idImages)
    {
        // Récupérer l'image spécifique en fonction de l'idProjet et de l'idImages
        $image = DB::table('images')
            ->where('idImages', $idImages)
            ->where('idProjet', $idProjet)
            ->first();

        if ($image) {
            $filePath = $image->path;

            // Supprimer le fichier du stockage
            Storage::disk('do')->delete($filePath);

            // Supprimer l'image de la base de données
            DB::table('images')
                ->where('idImages', $idImages)
                ->where('idProjet', $idProjet)
                ->delete();

            return response()->json(['success' => true, 'message' => 'Image supprimée avec succès.']);
        } else {
            return response()->json(['success' => false, 'message' => 'Image non trouvée.']);
        }
    }


    public function getNote($idProjet)
    {
        $checkEvaluation = DB::table('eval_chauds')->select('idProjet')->get();
        $checkEvaluationCount = count($checkEvaluation);

        if ($checkEvaluationCount > 0) {
            $notationProjet = DB::table('v_evaluation_alls')
                ->select('idProjet', 'idEmploye', 'generalApreciate')
                ->where('idProjet', $idProjet)
                ->groupBy('idProjet', 'idEmploye')
                ->get();

            $generalNotation = DB::table('v_general_note_evaluation')
                ->select(DB::raw('SUM(generalApreciate) as generalNote'))
                ->where('idProjet', $idProjet)
                ->first();

            $countNotationProjet = count($notationProjet);

            if ($countNotationProjet > 0) {
                $noteGeneral = $generalNotation->generalNote / $countNotationProjet;
                return array_merge([$noteGeneral], [$countNotationProjet]);
            } else {
                $noteGeneral = 0;
                return array_merge([$noteGeneral], [$countNotationProjet]);
            }
        } else {
            $countNotationProjet = 0;
            $noteGeneral = 0;
            return array_merge([$noteGeneral], [$countNotationProjet]);
        }
    }

    public function reservation(Request $request)
    {
        return view('CFP.reservations.index');
    }

    public function reservationList(Request $request)
    {
        $query = DB::table('inter_entreprises as I')
            ->join('projets as P', 'P.idProjet', '=', 'I.idProjet')
            ->join('customers as C', 'C.idCustomer', '=', 'I.idEtp')
            ->join('mdls as M', 'M.idModule', '=', 'P.idModule')
            ->leftJoin('modules as MD', 'MD.idModule', '=', 'M.idModule')
            ->leftJoin('inters as IE', 'IE.idProjet', '=', 'P.idProjet')
            ->leftJoin(
                DB::raw('(SELECT inter_entreprises.idProjet, 
                                        SUM(inter_entreprises.nbPlaceReserved) as reserved
                                FROM inter_entreprises 
                                WHERE inter_entreprises.isActiveInter = 1
                                GROUP BY inter_entreprises.idProjet) as reservation'),
                'reservation.idProjet',
                '=',
                'P.idProjet'
            )
            ->join('ville_codeds as VC', 'VC.id', 'P.idVilleCoded')
            ->select(
                'M.module_image as moduleLogo',
                'IE.nbPlace as nbPlace',
                DB::raw('COALESCE(IE.nbPlace - reservation.reserved, IE.nbPlace) as available'),
                'M.moduleName as module_name',
                'P.project_title',
                'I.nbPlaceReserved',
                'C.logo as etpLogo',
                'C.customerName',
                'I.isActiveInter',
                'I.id',
                'C.customerEmail as customerEmail',
                'MD.prix as totalPrice',
                'P.dateDebut',
                'P.dateFin',
                'VC.ville_name',
                'VC.vi_code_postal',
                'P.idProjet',
                'C.customerPhone',
                'P.project_reference',
                DB::raw("SUBSTR(C.customerName, 1, 1) as initialName"),
                'IE.nbPlace as nbPlaceTotal'
            )
            ->where('P.idCustomer', auth()->user()->id);

        if ($request->has('search') && !empty($request->search)) {
            $search = $request->search;
            $query->where(function ($query) use ($search) {
                $query->where('M.moduleName', 'like', '%' . $search . '%')
                    ->orWhere('C.customerName', 'like', '%' . $search . '%');
            });
        }

        $reservations = $query->orderBy('I.id', 'desc')->paginate(10);

        $reservation_validate = DB::table('inter_entreprises as I')
            ->join('projets as P', 'P.idProjet', '=', 'I.idProjet')
            ->where('I.isActiveInter', 1)
            ->where('P.idCustomer', auth()->user()->id)
            ->count();
        $reservation_waiting = DB::table('inter_entreprises as I')
            ->join('projets as P', 'P.idProjet', '=', 'I.idProjet')
            ->where('I.isActiveInter', 2)
            ->where('P.idCustomer', auth()->user()->id)
            ->count();
        $reservation_refused = DB::table('inter_entreprises as I')
            ->join('projets as P', 'P.idProjet', '=', 'I.idProjet')
            ->where('I.isActiveInter', 3)
            ->where('P.idCustomer', auth()->user()->id)
            ->count();
        $reservation_invalid = DB::table('inter_entreprises as I')
            ->join('projets as P', 'P.idProjet', '=', 'I.idProjet')
            ->where('I.isActiveInter', 0)
            ->where('P.idCustomer', auth()->user()->id)
            ->count();

        $paginationLinks = $reservations->links()->toHtml();

        return response()->json([
            'reservations' => $reservations->items(),
            'validate' => $reservation_validate,
            'waiting' => $reservation_waiting,
            'refused' => $reservation_refused,
            'invalid' => $reservation_invalid,
            'pagination' => $paginationLinks
        ]);
    }

    public function reservationFilter(Request $request)
    {
        $query = DB::table('inter_entreprises as I')
            ->join('projets as P', 'P.idProjet', '=', 'I.idProjet')
            ->join('customers as C', 'C.idCustomer', '=', 'I.idEtp')
            ->join('mdls as M', 'M.idModule', '=', 'P.idModule')
            ->leftJoin('modules as MD', 'MD.idModule', '=', 'M.idModule')
            ->leftJoin('inters as IE', 'IE.idProjet', '=', 'P.idProjet')
            ->leftJoin(
                DB::raw('(SELECT inter_entreprises.idProjet, 
                                        SUM(inter_entreprises.nbPlaceReserved) as reserved
                                FROM inter_entreprises 
                                WHERE inter_entreprises.isActiveInter = 1
                                GROUP BY inter_entreprises.idProjet) as reservation'),
                'reservation.idProjet',
                '=',
                'P.idProjet'
            )
            ->join('ville_codeds as VC', 'VC.id', 'P.idVilleCoded')
            ->select(
                'M.module_image as moduleLogo',
                'IE.nbPlace as nbPlace',
                DB::raw('COALESCE(IE.nbPlace - reservation.reserved, IE.nbPlace) as available'),
                'M.moduleName as module_name',
                'P.project_title',
                'I.nbPlaceReserved',
                'C.logo as etpLogo',
                'C.customerName',
                'I.isActiveInter',
                'I.id',
                'C.customerEmail as customerEmail',
                'MD.prix as totalPrice',
                'P.dateDebut',
                'P.dateFin',
                'VC.ville_name',
                'VC.vi_code_postal',
                'P.idProjet',
                'C.customerPhone',
                'P.project_reference',
                DB::raw("SUBSTR(C.customerName, 1, 1) as initialName"),
                'IE.nbPlace as nbPlaceTotal'
            )
            ->where('P.idCustomer', auth()->user()->id)
            ->where('I.isActiveInter', $request->typeReservation);

        if ($request->has('search') && !empty($request->search)) {
            $search = $request->search;
            $query->where(function ($query) use ($search) {
                $query->where('M.moduleName', 'like', '%' . $search . '%')
                    ->orWhere('C.customerName', 'like', '%' . $search . '%');
            });
        }

        $reservations = $query->orderBy('I.id', 'desc')->paginate(10);

        $paginationLinks = $reservations->links()->toHtml();

        return response()->json([
            'reservations' => $reservations->items(),
            'pagination' => $paginationLinks
        ]);
    }



    public function reservationProject(Request $request, $idProject)
    {
        $query = DB::table('inter_entreprises as I')
            ->join('projets as P', 'P.idProjet', '=', 'I.idProjet')
            ->join('customers as C', 'C.idCustomer', '=', 'I.idEtp')
            ->join('mdls as M', 'M.idModule', '=', 'P.idModule')
            ->leftJoin('inters as IE', 'IE.idProjet', '=', 'P.idProjet')
            ->leftJoin(
                DB::raw('(SELECT inter_entreprises.idProjet as idProjet, 
                                        SUM(inter_entreprises.nbPlaceReserved) as reserved
                                FROM inter_entreprises 
                                WHERE inter_entreprises.isActiveInter = 1
                                GROUP BY inter_entreprises.idProjet) as reservation'),
                'reservation.idProjet',
                '=',
                'P.idProjet'
            )
            ->select(
                'M.module_image as moduleLogo',
                'IE.nbPlace',
                DB::raw('COALESCE(IE.nbPlace - reservation.reserved, IE.nbPlace) as available'),
                'M.moduleName as module_name',
                'P.project_title',
                'I.nbPlaceReserved',
                'C.logo as etpLogo',
                'C.customerName',
                'I.isActiveInter',
                'I.id',
                'C.customerEmail as customerEmail',
                DB::raw("SUBSTR(C.customerName, 1, 1) as initialName")
            )
            ->where('P.idCustomer', auth()->user()->id)
            ->where('IE.idProjet', $idProject);

        if ($request->has('search') && !empty($request->search)) {
            $search = $request->search;
            $query->where(function ($query) use ($search) {
                $query->where('P.project_title', 'like', '%' . $search . '%')
                    ->orWhere('C.customerName', 'like', '%' . $search . '%');
            });
        }

        $reservations = $query->orderBy('I.id', 'desc')->paginate(10);

        $reservation_validate = DB::table('inter_entreprises as I')
            ->join('projets as P', 'P.idProjet', '=', 'I.idProjet')
            ->where('I.isActiveInter', 1)
            ->where('P.idCustomer', auth()->user()->id)
            ->where('I.idProjet', $idProject)
            ->count();
        $reservation_waiting = DB::table('inter_entreprises as I')
            ->join('projets as P', 'P.idProjet', '=', 'I.idProjet')
            ->where('I.isActiveInter', 2)
            ->where('P.idCustomer', auth()->user()->id)
            ->where('I.idProjet', $idProject)
            ->count();
        $reservation_refused = DB::table('inter_entreprises as I')
            ->join('projets as P', 'P.idProjet', '=', 'I.idProjet')
            ->where('I.isActiveInter', 3)
            ->where('P.idCustomer', auth()->user()->id)
            ->where('I.idProjet', $idProject)
            ->count();
        $reservation_invalid = DB::table('inter_entreprises as I')
            ->join('projets as P', 'P.idProjet', '=', 'I.idProjet')
            ->where('I.isActiveInter', 0)
            ->where('P.idCustomer', auth()->user()->id)
            ->where('I.idProjet', $idProject)
            ->count();

        $paginationLinks = $reservations->links()->toHtml();

        return response()->json([
            'reservations' => $reservations->items(),
            'validate' => $reservation_validate,
            'waiting' => $reservation_waiting,
            'refused' => $reservation_refused,
            'invalid' => $reservation_invalid,
            'pagination' => $paginationLinks
        ]);
    }

    private function getPlaceAvailable($idProjet)
    {
        $place_validated = DB::table('inter_entreprises')->where('idProjet', $idProjet)->where('isActiveInter', 1)->sum('nbPlaceReserved');
        $place_project = DB::table('inters')->where('idProjet', $idProjet)->value('nbPlace');
        $place_available = $place_project - $place_validated;
        return $place_available;
    }

    private function getNbPlaceReserved($idProjet)
    {
        $place_reserved = DB::table('inter_entreprises')->where('idProjet', $idProjet)->where('isActiveInter', 1)->sum('nbPlaceReserved');
        return $place_reserved;
    }

    public function reservationvalidation($id, $type)
    {
        $project_reserved = DB::table('inter_entreprises')->select('idProjet', 'nbPlaceReserved')->where('id', $id)->first();
        $nb_place_reserved = $project_reserved->nbPlaceReserved;
        $place_available = $this->getPlaceAvailable($project_reserved->idProjet);
        if ($type == 'validate') {
            if ($place_available >= $nb_place_reserved) {
                DB::table('inter_entreprises')->where('id', $id)->update(['isActiveInter' => 1]);
                return response()->json(['success' => 'Reservation validé avec succes.']);
            } else {
                return response()->json(['error' => 'Impossible de valider la réservation car le nombre de places demandées dépasse le nombre de places disponibles.']);
            }
        } elseif ($type == 'stack') {
            DB::table('inter_entreprises')->where('id', $id)->update(['isActiveInter' => 2]);
            return response()->json(['success' => 'Réservation sur la liste d\'attente effectuée avec succès.']);
        } else {
            DB::table('inter_entreprises')->where('id', $id)->update(['isActiveInter' => 3]);
            return response()->json(['success' => 'Reservation refusé svec succes.']);
        }
    }

    public function filterReservation(Request $request)
    {
        $status = $request->input('status', []);
        $page = $request->input('page', 1);

        $query = DB::table('inter_entreprises as I')
            ->join('projets as P', 'P.idProjet', '=', 'I.idProjet')
            ->join('customers as C', 'C.idCustomer', '=', 'I.idEtp')
            ->join('mdls as M', 'M.idModule', '=', 'P.idModule')
            ->select('M.module_image as moduleLogo', 'P.project_title', 'I.nbPlace', 'C.logo as etpLogo', 'C.customerName', 'I.isActiveInter', 'I.id')
            ->where('P.idCustomer', auth()->user()->id);

        if (!empty($status)) {
            $query->whereIn('I.isActiveInter', $status);
        }

        $reservations = $query->orderBy('I.id', 'desc')->paginate(1, ['*'], 'page', $page);

        return response()->json([
            'tableHtml' => view('CFP.reservation.table', ['reservations' => $reservations])->render(),
            'paginationInfo' => 'Affichage de ' . $reservations->firstItem() . ' à ' . $reservations->lastItem() . ' sur ' . $reservations->total() . ' entrées',
            'prevPage' => $reservations->previousPageUrl(),
            'nextPage' => $reservations->nextPageUrl(),
            'prevPageDisabled' => !$reservations->onFirstPage(),
            'nextPageDisabled' => !$reservations->hasMorePages(),
            'filteredReservations' => $reservations->items()
        ]);
    }
}
