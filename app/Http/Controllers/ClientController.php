<?php

namespace App\Http\Controllers;

use App\Mail\DevisMail;
use App\Mail\IndividualDevisMail;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use App\Models\Customer;
use App\Models\Employe;
use App\Models\Module;
use App\Models\RoleUser;
use App\Providers\RouteServiceProvider;
use App\Rules\GoogleRecaptcha;
use Barryvdh\DomPDF\Facade\Pdf;
use Exception;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Concerns\ToArray;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat\NumberFormatter;

class ClientController extends Controller
{

    public function landing()
    {
        $domaine = DB::table('domaine_formations')->select('*')->get();
        return view('client.landing', compact('domaine'));
    }

    public function index()
    {
        $projects = DB::table('v_module_cfps AS M')
            ->join('customers AS C', 'C.idCustomer', '=', 'M.idCustomer')
            ->select(
                'idModule',
                'module_image',
                'reference',
                'moduleName',
                'moduleStatut',
                'M.description',
                'minApprenant',
                'dureeH',
                'dureeJ',
                'maxApprenant',
                'prix',
                'prixGroupe',
                'M.idCustomer',
                'initialName',
                'cfpName as nameCfp',
                'C.logo as etp_logo',
                'M.logo as cfpLogo',
                'nomDomaine',
                'idDomaine',
                'module_is_complete',
                'module_subtitle'
            )
            ->where('moduleStatut', 1)
            ->where('moduleName', '!=', 'Default module')
            ->orderBy('moduleName', 'desc')
            ->get();
        $places = DB::table('villes')->select('*')->where('idVille', '!=', 1)->orderBy('ville')->get();
        $cfp = DB::table('entreprises AS E')->join('customers AS C', 'E.idCustomer', '=', 'C.idCustomer')->select('C.idCustomer AS idCustomer', 'C.customerName AS customerName')->get();
        $all_domaines = DB::table('domaine_formations')->select('idDomaine', 'nomDomaine')->orderBy('nomDomaine')->get();
        $domaines = [];

        foreach ($all_domaines as $doma) {
            $domaines[] = [
                'idDomaine' => $doma->idDomaine,
                'nomDomaine' => $doma->nomDomaine,
                'nb_module' => $this->getModuleDomaine($doma->idDomaine)
            ];
        }
        return view('client.project.index', compact('projects', 'places', 'domaines', 'cfp'));
    }

    public function getFormationByCategory($id)
    {
        $extends_containt = null;

        if (Auth::check()) {
            $user = Auth::user();
            if ($user->hasRole('Formateur')) {
                $extends_containt = "layouts.masterForm";
            } elseif ($user->hasRole('Formateur interne')) {
                $extends_containt = "layouts.masterFormInterne";
            } elseif ($user->hasRole('Particulier')) {
                $extends_containt = "layouts.masterParticulier";
            } elseif ($user->hasRole('EmployeCfp')) {
                $extends_containt = "layouts.masterEmpCfp";
            } elseif ($user->hasRole('Employe') || $user->hasRole('EmployeEtp')) {
                $extends_containt = "layouts.masterEmp";
            } elseif ($user->hasRole('Cfp')) {
                $extends_containt = "layouts.master";
            } elseif ($user->hasRole('Admin') || $user->hasRole('SuperAdmin')) {
                $extends_containt = "layouts.masterAdmin";
            } elseif ($user->hasRole('Referent')) {
                $extends_containt = "layouts.masterEtp";
            }
        } else {
            $extends_containt = "layouts.masterGuest";
        }

        $projects = DB::table('v_projet_cfps_inters')
            ->select(
                'idModule',
                'module_image',
                'module_name',
                'moduleStatut',
                'module_description',
                'minApprenant',
                'prix',
                'dureeH',
                'dureeJ',
                'prixGroupe',
                'idCfp_inter as idCustomer',
                'cfp_name',
                'logo_cfp',
                'domaine_name',
                'idDomaine'
            )
            ->where('moduleStatut', 1)
            ->where('project_status', "Planifié")
            ->where('module_name', '!=', 'Default module')
            ->where('idDomaine', $id)
            ->orderBy('module_name', 'desc')
            ->groupBy('idModule')
            ->get();
        $course = '';
        $category = $id;
        $place = 'all';
        $cfp = '';

        $places = DB::table('villes')->select('*')->where('idVille', '!=', 1)->orderBy('ville')->get();
        $all_domaines = DB::table('domaine_formations')->select('idDomaine', 'nomDomaine')->orderBy('nomDomaine')->get();
        $domaines = [];

        foreach ($all_domaines as $doma) {
            $domaines[] = [
                'idDomaine' => $doma->idDomaine,
                'nomDomaine' => $doma->nomDomaine,
                'nb_module' => $this->getModuleDomaine($doma->idDomaine)
            ];
        }

        $firstPublicite = DB::table('mdls')
            ->join('publicites', 'publicites.idModule', 'mdls.idModule')
            ->join('customers', 'customers.idCustomer', 'mdls.idCustomer')
            ->where('publicites.is_active', 1)
            ->where('publicites.idType', 1)
            ->first();

        $otherPublicites = DB::table('mdls')
            ->join('publicites', 'publicites.idModule', 'mdls.idModule')
            ->join('customers', 'customers.idCustomer', 'mdls.idCustomer')
            ->where('publicites.is_active', 1)
            ->where('publicites.idType', 1)
            ->skip(1)
            ->take(100)
            ->get();

        return view('client.project.index', compact('places', 'domaines', 'projects', 'cfp', 'course', 'category', 'place', 'extends_containt', 'firstPublicite', 'otherPublicites'));
    }

    public function formationByNumerika($cours)
    {
        $course = ($cours == 'excel') ? 'excel' : 'power bi';

        $place = 'all';
        $category = 'all';
        $cfp = 2;
        $all_domaines = DB::table('domaine_formations')->select('idDomaine', 'nomDomaine')->orderBy('nomDomaine')->get();
        $domaines = [];

        foreach ($all_domaines as $doma) {
            $domaines[] = [
                'idDomaine' => $doma->idDomaine,
                'nomDomaine' => $doma->nomDomaine,
                'nb_module' => $this->getModuleDomaine($doma->idDomaine)
            ];
        }

        if (Auth::check()) {
            $user = Auth::user();
            if ($user->hasRole('Formateur')) {
                $extends_containt = "layouts.masterForm";
            } elseif ($user->hasRole('Formateur interne')) {
                $extends_containt = "layouts.masterFormInterne";
            } elseif ($user->hasRole('Particulier')) {
                $extends_containt = "layouts.masterParticulier";
            } elseif ($user->hasRole('EmployeCfp')) {
                $extends_containt = "layouts.masterEmpCfp";
            } elseif ($user->hasRole('Employe') || $user->hasRole('EmployeEtp')) {
                $extends_containt = "layouts.masterEmp";
            } elseif ($user->hasRole('Cfp')) {
                $extends_containt = "layouts.master";
            } elseif ($user->hasRole('Admin') || $user->hasRole('SuperAdmin')) {
                $extends_containt = "layouts.masterAdmin";
            } elseif ($user->hasRole('Referent')) {
                $extends_containt = "layouts.masterEtp";
            }
        } else {
            $extends_containt = "layouts.masterGuest";
        }

        $firstPublicite = DB::table('mdls')
            ->join('publicites', 'publicites.idModule', 'mdls.idModule')
            ->join('customers', 'customers.idCustomer', 'mdls.idCustomer')
            ->where('publicites.is_active', 1)
            ->where('publicites.idType', 1)
            ->first();

        $otherPublicites = DB::table('mdls')
            ->join('publicites', 'publicites.idModule', 'mdls.idModule')
            ->join('customers', 'customers.idCustomer', 'mdls.idCustomer')
            ->where('publicites.is_active', 1)
            ->where('publicites.idType', 1)
            ->skip(1) // Ignorer le premier résultat
            ->take(100)
            ->get();

        return view('client.project.index', compact('domaines', 'cfp', 'course', 'category', 'place', 'extends_containt', 'firstPublicite', 'otherPublicites'));
    }

    public function indexFormation($cfp = null)
    {
        $extends_containt = null; // Variable pour stocker les extends selon l'utilisateur connecté

        // Condition pour l'extends selon l'utilisateur connecté
        if (Auth::check()) {
            $user = Auth::user();
            if ($user->hasRole('Formateur')) {
                $extends_containt = "layouts.masterForm";
            } elseif ($user->hasRole('Formateur interne')) {
                $extends_containt = "layouts.masterFormInterne";
            } elseif ($user->hasRole('Particulier')) {
                $extends_containt = "layouts.masterParticulier";
            } elseif ($user->hasRole('EmployeCfp')) {
                $extends_containt = "layouts.masterEmpCfp";
            } elseif ($user->hasRole('Employe') || $user->hasRole('EmployeEtp')) {
                $extends_containt = "layouts.masterEmp";
            } elseif ($user->hasRole('Cfp')) {
                $extends_containt = "layouts.master";
            } elseif ($user->hasRole('Admin') || $user->hasRole('SuperAdmin')) {
                $extends_containt = "layouts.masterAdmin";
            } elseif ($user->hasRole('Referent')) {
                $extends_containt = "layouts.masterEtp";
            }
        } else {
            $extends_containt = "layouts.masterGuest";
        }

        $all_domaines = DB::table('domaine_formations')->select('idDomaine', 'nomDomaine')->orderBy('nomDomaine')->get();
        $domaines = [];

        foreach ($all_domaines as $doma) {
            $domaines[] = [
                'idDomaine' => $doma->idDomaine,
                'nomDomaine' => $doma->nomDomaine,
                'nb_module' => $this->getModuleDomaine($doma->idDomaine)
            ];
        }
        $place = 'all';
        $category = 'all';
        $cfp = '';
        $course = '';

        $firstPublicite = DB::table('mdls')
            ->join('publicites', 'publicites.idModule', 'mdls.idModule')
            ->join('customers', 'customers.idCustomer', 'mdls.idCustomer')
            ->where('publicites.is_active', 1)
            ->where('publicites.idType', 1)
            ->first();

        $otherPublicites = DB::table('mdls')
            ->join('publicites', 'publicites.idModule', 'mdls.idModule')
            ->join('customers', 'customers.idCustomer', 'mdls.idCustomer')
            ->where('publicites.is_active', 1)
            ->where('publicites.idType', 1)
            ->skip(1)
            ->take(100)
            ->get();

        return view('client.project.index', compact('domaines', 'place', 'category', 'cfp', 'course', 'extends_containt', 'firstPublicite', 'otherPublicites'));
    }

    public function exportPdf($id)
    {
        $module = DB::table('v_module_cfps AS M')
            ->join('customers AS C', 'C.idCustomer', '=', 'M.idCustomer')
            ->select('idModule', 'module_image', 'reference', 'moduleName', 'moduleStatut', 'M.description', 'minApprenant', 'dureeH', 'dureeJ', DB::raw('COALESCE(maxApprenant, 0) as maxApprenant'), 'prix', 'prixGroupe', 'M.idCustomer', 'initialName', 'cfpName as nameCfp', 'C.logo as etp_logo', 'nomDomaine', 'idDomaine', 'module_is_complete', 'module_subtitle', 'module_level_name')
            ->whereNot('moduleName', 'Default module')
            ->where('idModule', $id)
            ->orderBy('moduleName', 'desc')
            ->first();
        $idCustomer = $module->idCustomer;

        $cfp = DB::table('customers')->select('*')->where('idCustomer', $idCustomer)->first();

        $cibles = DB::table('cible_modules')->where('idModule', $module->idModule)->get('cible');
        $prerequis = DB::table('prerequis_modules')
            ->where('idModule', $module->idModule)
            ->get('prerequis_name');
        $all_domaines = DB::table('domaine_formations')->select('idDomaine', 'nomDomaine')->orderBy('nomDomaine')->get();
        $domaines = [];

        foreach ($all_domaines as $doma) {
            $domaines[] = [
                'idDomaine' => $doma->idDomaine,
                'nomDomaine' => $doma->nomDomaine,
                'nb_module' => $this->getModuleDomaine($doma->idDomaine)
            ];
        }
        $objectifs = DB::table('objectif_modules')->select('idObjectif', 'objectif', 'idModule')->where('idModule', $module->idModule)->get();

        $projects_with_sessions = [];

        $project_cfp = $this->getProjectCfp($id);

        foreach ($project_cfp as $p) {
            $projects_with_sessions[$p->idProjet] = [
                'project' => $p,
                'sessionsGroupedByDate' => $this->sessionsGroupedByDate($p->idProjet, $id),
                'projectStartDate' => $this->monthConverted($p->dateDebut),
                'projectEndDate' => $this->dateConverted($p->dateFin),
                'forms' => $this->getForms($p->idProjet),
                'ville' => $p->ville,
                'nbPlace' => $this->getNbPlace($p->idProjet),
                'availability' => $this->placeIsAvailable($p->idProjet)
            ];
        }
        $prog = $this->getPrograms($id);
        if (Auth::user() && Auth::user()->id != 1) {
            $type_customer = Customer::where('idCustomer', Auth::user()->id)->first();
        }
        $note = $this->getEval($id);

        $get_domaines = DB::table('v_module_cfps')->select('idDomaine', 'nomDomaine')->where('moduleStatut', 1)->where('idCUstomer', $idCustomer)->whereNot('moduleName', "Default module")->groupBy('idDomaine')->get();

        $onlineModules = [];
        if (count($get_domaines) < 4) {
            foreach ($get_domaines as $domaine) {
                $get_module_domaine = DB::table('v_module_cfps')
                    ->join('domaine_formations', 'domaine_formations.idDomaine', 'v_module_cfps.idDomaine')
                    ->where('v_module_cfps.idDomaine', $domaine->idDomaine)
                    ->get();

                foreach ($get_module_domaine as $modulenew) {
                    $modules[] = [
                        'idDomaine' => $modulenew->idDomaine,
                        'idModule' => $modulenew->idModule,
                        'module_name' => $modulenew->moduleName,
                        'prix' => $modulenew->prix,
                        'dureeJ' => $modulenew->dureeJ,
                        'dureeH' => $modulenew->dureeH,
                        'moduleStatut' => $modulenew->moduleStatut,
                        'module_image' => $modulenew->module_image,
                        'module_is_complete' => $modulenew->module_is_complete,
                        'cfp_name' => $modulenew->cfpName,
                        'logo_cfp' => $modulenew->logo,
                        'note' => $this->getEval($modulenew->idModule)
                    ];
                    $onlineModules[] = [
                        'idDomaine' => $modulenew->idDomaine,
                        'nomDomaine' => $modulenew->nomDomaine,
                        "modules" => $modules
                    ];
                }
            }
        }

        foreach ($get_domaines as $d) {
            $onlineModules[] = [
                'idDomaine' => $d->idDomaine,
                'nomDomaine' => $d->nomDomaine,
                "modules" => $this->getModules($d->idDomaine, $idCustomer)
            ];
        }
        $pdf = PDF::loadView('client.project.projetDetailPDF', compact(['domaines', 'module', 'cibles', 'cfp', 'prerequis', 'objectifs', 'prog', 'projects_with_sessions', 'note', 'onlineModules', 'id']));
        return $pdf->download($module->moduleName . '.pdf');
    }


    public function getDetailFormation($id)
    {
        $extends_containt = null; // Variable pour stocker les extends selon l'utilisateur connecté

        // Condition pour l'extends selon l'utilisateur connecté
        if (Auth::check()) {
            $user = Auth::user();
            if ($user->hasRole('Formateur')) {
                $extends_containt = "layouts.masterForm";
            } elseif ($user->hasRole('Formateur interne')) {
                $extends_containt = "layouts.masterFormInterne";
            } elseif ($user->hasRole('Particulier')) {
                $extends_containt = "layouts.masterParticulier";
            } elseif ($user->hasRole('EmployeCfp')) {
                $extends_containt = "layouts.masterEmpCfp";
            } elseif ($user->hasRole('Employe') || $user->hasRole('EmployeEtp')) {
                $extends_containt = "layouts.masterEmp";
            } elseif ($user->hasRole('Cfp')) {
                $extends_containt = "layouts.master";
            } elseif ($user->hasRole('Admin') || $user->hasRole('SuperAdmin')) {
                $extends_containt = "layouts.masterAdmin";
            } elseif ($user->hasRole('Referent')) {
                $extends_containt = "layouts.masterEtp";
            }
        } else {
            $extends_containt = "layouts.masterGuest";
        }

        $module = DB::table('v_module_cfps AS M')
            ->join('customers AS C', 'C.idCustomer', '=', 'M.idCustomer')
            ->select('idModule', 'module_image', 'reference', 'moduleName', 'moduleStatut', 'M.description', 'minApprenant', 'dureeH', 'dureeJ', DB::raw('COALESCE(maxApprenant, 0) as maxApprenant'), 'prix', 'prixGroupe', 'M.idCustomer', 'initialName', 'cfpName as nameCfp', 'C.logo as etp_logo', 'nomDomaine', 'idDomaine', 'module_is_complete', 'module_subtitle', 'module_level_name')
            ->whereNot('moduleName', 'Default module')
            ->where('idModule', $id)
            ->orderBy('moduleName', 'desc')
            ->first();
        $idCustomer = $module->idCustomer;

        $cfp = DB::table('customers')->select('*')->where('idCustomer', $idCustomer)->first();

        $cibles = DB::table('cible_modules')->where('idModule', $module->idModule)->get('cible');
        $prerequis = DB::table('prerequis_modules')
            ->where('idModule', $module->idModule)
            ->get('prerequis_name');
        $all_domaines = DB::table('domaine_formations')->select('idDomaine', 'nomDomaine')->orderBy('nomDomaine')->get();
        $domaines = [];

        foreach ($all_domaines as $doma) {
            $domaines[] = [
                'idDomaine' => $doma->idDomaine,
                'nomDomaine' => $doma->nomDomaine,
                'nb_module' => $this->getModuleDomaine($doma->idDomaine)
            ];
        }
        $objectifs = DB::table('objectif_modules')->select('idObjectif', 'objectif', 'idModule')->where('idModule', $module->idModule)->get();

        $projects_with_sessions = [];

        $project_cfp = $this->getProjectCfp($id);
        foreach ($project_cfp as $p) {
            $projects_with_sessions[$p->idProjet] = [
                'project' => $p,
                'sessionsGroupedByDate' => $this->sessionsGroupedByDate($p->idProjet, $id),
                'projectStartDate' => $this->monthConverted($p->dateDebut),
                'projectEndDate' => $this->dateConverted($p->dateFin),
                'forms' => $this->getForms($p->idProjet),
                'ville' => $p->ville,
                'nbPlace' => $this->getNbPlace($p->idProjet),
                'availability' => $this->placeIsAvailable($p->idProjet)
            ];
        }
        $prog = $this->getPrograms($id);
        if (Auth::user() && Auth::user()->id != 1) {
            $type_customer = Customer::where('idCustomer', Auth::user()->id)->first();
        }
        $note = $this->getEval($id);

        $get_domaines = DB::table('v_module_cfps')->select('idDomaine', 'nomDomaine')->where('moduleStatut', 1)->where('idCUstomer', $idCustomer)->whereNot('moduleName', "Default module")->groupBy('idDomaine')->get();

        $onlineModules = [];
        if (count($get_domaines) < 4) {
            foreach ($get_domaines as $domaine) {
                $get_module_domaine = DB::table('v_module_cfps')
                    ->join('domaine_formations', 'domaine_formations.idDomaine', 'v_module_cfps.idDomaine')
                    ->where('v_module_cfps.idDomaine', $domaine->idDomaine)
                    ->get();

                foreach ($get_module_domaine as $modulenew) {
                    $modules[] = [
                        'idDomaine' => $modulenew->idDomaine,
                        'idModule' => $modulenew->idModule,
                        'module_name' => $modulenew->moduleName,
                        'prix' => $modulenew->prix,
                        'dureeJ' => $modulenew->dureeJ,
                        'dureeH' => $modulenew->dureeH,
                        'moduleStatut' => $modulenew->moduleStatut,
                        'module_image' => $modulenew->module_image,
                        'module_is_complete' => $modulenew->module_is_complete,
                        'cfp_name' => $modulenew->cfpName,
                        'logo_cfp' => $modulenew->logo,
                        'module_level_name' => $modulenew->module_level_name,
                        'note' => $this->getEval($modulenew->idModule)
                    ];
                    $onlineModules[] = [
                        'idDomaine' => $modulenew->idDomaine,
                        'nomDomaine' => $modulenew->nomDomaine,
                        "modules" => $modules
                    ];
                }
            }
        }

        foreach ($get_domaines as $d) {
            $onlineModules[] = [
                'idDomaine' => $d->idDomaine,
                'nomDomaine' => $d->nomDomaine,
                "modules" => $this->getModules($d->idDomaine, $idCustomer)
            ];
        }
        return view('client.project.projectDetail', compact('extends_containt', 'domaines', 'module', 'cibles', 'cfp', 'prerequis', 'objectifs', 'prog', 'projects_with_sessions', 'note', 'onlineModules', 'id'));
    }

    public function getDetailFormationInter($id, $idProjet)
    {

        $extends_containt = null; // Variable pour stocker les extends selon l'utilisateur connecté

        // Condition pour l'extends selon l'utilisateur connecté
        if (Auth::check()) {
            $user = Auth::user();
            if ($user->hasRole('Formateur')) {
                $extends_containt = "layouts.masterForm";
            } elseif ($user->hasRole('Formateur interne')) {
                $extends_containt = "layouts.masterFormInterne";
            } elseif ($user->hasRole('Particulier')) {
                $extends_containt = "layouts.masterParticulier";
            } elseif ($user->hasRole('EmployeCfp')) {
                $extends_containt = "layouts.masterEmpCfp";
            } elseif ($user->hasRole('Employe') || $user->hasRole('EmployeEtp')) {
                $extends_containt = "layouts.masterEmp";
            } elseif ($user->hasRole('Cfp')) {
                $extends_containt = "layouts.master";
            } elseif ($user->hasRole('Admin') || $user->hasRole('SuperAdmin')) {
                $extends_containt = "layouts.masterAdmin";
            } elseif ($user->hasRole('Referent')) {
                $extends_containt = "layouts.masterEtp";
            }
        } else {
            $extends_containt = "layouts.masterGuest";
        }

        $module = DB::table('v_module_cfps AS M')
            ->join('customers AS C', 'C.idCustomer', '=', 'M.idCustomer')
            ->select('idModule', 'module_image', 'reference', 'moduleName', 'moduleStatut', 'M.description', 'minApprenant', 'dureeH', 'dureeJ', 'maxApprenant', 'prix', 'prixGroupe', 'M.idCustomer', 'initialName', 'cfpName as nameCfp', 'C.logo as etp_logo', 'nomDomaine', 'idDomaine', 'module_is_complete', 'module_subtitle', 'module_level_name')
            ->where('moduleStatut', 1)
            ->where('moduleName', '!=', 'Default module')
            ->where('idModule', $id)
            ->orderBy('moduleName', 'desc')
            ->first();

        $idCustomer = $module->idCustomer;
        $cfp = DB::table('customers')->select('*')->where('idCustomer', $idCustomer)->first();
        $cibles = DB::table('cible_modules')->where('idModule', $module->idModule)->get('cible');
        $prerequis = DB::table('prerequis_modules')
            ->where('idModule', $module->idModule)
            ->get('prerequis_name');
        $all_domaines = DB::table('domaine_formations')->select('idDomaine', 'nomDomaine')->orderBy('nomDomaine')->get();
        $domaines = [];

        foreach ($all_domaines as $doma) {
            $domaines[] = [
                'idDomaine' => $doma->idDomaine,
                'nomDomaine' => $doma->nomDomaine,
                'nb_module' => $this->getModuleDomaine($doma->idDomaine)
            ];
        }
        $objectifs = DB::table('objectif_modules')->select('idObjectif', 'objectif', 'idModule')->where('idModule', $module->idModule)->get();

        $projects_with_sessions = [];

        $project_cfp = $this->getProjectInterCfp($idProjet);

        $projects_with_sessions[$project_cfp->idProjet] = [
            'project' => $project_cfp,
            'sessionsGroupedByDate' => $this->sessionsGroupedByDate($project_cfp->idProjet, $id),
            'projectStartDate' => $this->monthConverted($project_cfp->dateDebut),
            'projectEndDate' => $this->dateConverted($project_cfp->dateFin),
            'forms' => $this->getForms($project_cfp->idProjet),
            'ville' => $project_cfp->ville,
            'nbPlace' => $this->getNbPlace($project_cfp->idProjet),
            'availability' => $this->placeIsAvailable($project_cfp->idProjet)
        ];

        $prog = $this->getPrograms($id);
        if (Auth::user() && Auth::user()->id != 1) {
            $type_customer = Customer::where('idCustomer', Auth::user()->id)->first();
        }
        $note = $this->getEval($id);

        $get_domaines = DB::table('v_module_cfps')->select('idDomaine', 'nomDomaine')->where('moduleStatut', 1)->where('idCUstomer', $idCustomer)->whereNot('moduleName', "Default module")->groupBy('idDomaine')->get();

        $onlineModules = [];
        if (count($get_domaines) < 4) {
            foreach ($get_domaines as $domaine) {
                $get_module_domaine = DB::table('v_module_cfps')
                    ->join('domaine_formations', 'domaine_formations.idDomaine', 'v_module_cfps.idDomaine')
                    ->where('v_module_cfps.idDomaine', $domaine->idDomaine)
                    ->get();

                foreach ($get_module_domaine as $modulenew) {
                    $modules[] = [
                        'idDomaine' => $modulenew->idDomaine,
                        'idModule' => $modulenew->idModule,
                        'module_name' => $modulenew->moduleName,
                        'prix' => $modulenew->prix,
                        'dureeJ' => $modulenew->dureeJ,
                        'dureeH' => $modulenew->dureeH,
                        'moduleStatut' => $modulenew->moduleStatut,
                        'module_image' => $modulenew->module_image,
                        'module_is_complete' => $modulenew->module_is_complete,
                        'cfp_name' => $modulenew->cfpName,
                        'logo_cfp' => $modulenew->logo,
                        'module_level_name' => $modulenew->module_level_name,
                        'note' => $this->getEval($modulenew->idModule)
                    ];
                    $onlineModules[] = [
                        'idDomaine' => $modulenew->idDomaine,
                        'nomDomaine' => $modulenew->nomDomaine,
                        "modules" => $modules
                    ];
                }
            }
        }

        foreach ($get_domaines as $d) {
            $onlineModules[] = [
                'idDomaine' => $d->idDomaine,
                'nomDomaine' => $d->nomDomaine,
                "modules" => $this->getModules($d->idDomaine, $idCustomer)
            ];
        }

        return view('client.project.projectDetail', compact('extends_containt', 'domaines', 'module', 'cibles', 'cfp', 'prerequis', 'objectifs', 'prog', 'projects_with_sessions', 'note', 'onlineModules', 'id'));
    }

    public function getNbPlace($idProjet)
    {
        $nbPlace = 0;
        if (auth()->check()) {
            $nbPlace = DB::table('inter_entreprises')
                ->where('idProjet', $idProjet)
                ->where('idEtp', auth()->id())
                ->value('nbPlaceReserved') ?? 0;
        }
        return $nbPlace;
    }


    public function getProjectCfp($idModule)
    {
        $project_cfp = DB::table('v_projet_cfps_inters')
            ->select(
                'idProjet',
                'dateDebut as dateDebut',
                'dateFin as dateFin',
                'project_title',
                'module_name as moduleName',
                'ville_name as ville',
                'project_status',
                'project_description',
                'project_type',
                'logo_cfp',
                'idCfp_inter',
                'idCfp_inter'
            )
            ->where('project_status', "Planifié")
            ->where('idModule', $idModule)
            ->where('project_type', 'Inter')
            ->orderBy('dateDebut')
            ->get();
        return $project_cfp;
    }

    public function getProjectInterCfp($idProjet)
    {
        $project_cfp = DB::table('v_projet_cfps_inters')
            ->select(
                'idProjet',
                'dateDebut as dateDebut',
                'dateFin as dateFin',
                'project_title',
                'module_name as moduleName',
                'ville',
                'project_status',
                'project_description',
                'project_type',
                'logo_cfp',
                'idCfp_inter',
                'idCfp_inter'
            )
            ->where('project_status', "Planifié")
            ->where('idProjet', $idProjet)
            ->where('project_type', 'Inter')
            ->orderBy('dateDebut')
            ->first();
        return $project_cfp;
    }

    private function placeIsAvailable($idProjet)
    {
        $sumNbPlace = DB::table('inter_entreprises')->where('idProjet', $idProjet)->where('isActiveInter', 1)->sum('nbPlaceReserved');
        $nbPlace = DB::table('inters')->where('idProjet', $idProjet)->value('nbPlace');

        return intval($nbPlace) > intval($sumNbPlace) ? 1 : 0;
    }

    public function sessionsGroupedByDate($idProjet, $idModule)
    {
        $sessions = DB::table('v_seances')
            ->select('idSeance', 'dateSeance', 'heureDebut', 'heureFin', 'project_date_debut', 'project_date_fin', 'ville', 'idModule')
            ->where('idProjet', $idProjet)
            ->where('idModule', $idModule)
            ->orderBy('dateSeance')
            ->get()
            ->groupBy('dateSeance');
        $sessionsGroupedByDate = $sessions->map(function ($sessions, $date) {
            $morningSessions = $sessions->filter(function ($session) {
                return strtotime($session->heureDebut) < strtotime('12:00:00');
            })->map(function ($session) {
                return [
                    'heureDebut' => $this->timeConverted($session->heureDebut),
                    'heureFin' => $this->timeConverted($session->heureFin)
                ];
            });

            $afternoonSessions = $sessions->filter(function ($session) {
                return strtotime($session->heureDebut) >= strtotime('12:00:00');
            })->map(function ($session) {
                return [
                    'heureDebut' => $this->timeConverted($session->heureDebut),
                    'heureFin' => $this->timeConverted($session->heureFin)
                ];
            });

            return [
                'dateSeance' => $this->dateConverted($date),
                'morningSessions' => $morningSessions,
                'afternoonSessions' => $afternoonSessions,
                'ville' => $sessions->first()->ville
            ];
        });
        return $sessionsGroupedByDate;
    }

    public function getPrograms($idModule)
    {
        $programmes = DB::table('programmes')
            ->select('program_title', 'program_description', 'idModule')
            ->where('idModule', $idModule)
            ->get();

        return $programmes;
    }

    public function timeConverted($time)
    {
        $timestamp = strtotime($time);
        return date('G\hi', $timestamp);
    }

    public function dateConverted($date)
    {
        Carbon::setLocale('fr');
        $dateSeance = \Carbon\Carbon::parse($date);
        return  $dateSeance->translatedFormat('d M Y');
    }

    public function monthConverted($date)
    {
        Carbon::setLocale('fr');
        $dateSeance = \Carbon\Carbon::parse($date);
        return  $dateSeance->translatedFormat('d M');
    }

    public function getForms($id_project)
    {
        $forms = DB::table('v_formateur_cfps')
            ->select('idProjet', 'idFormateur', 'name AS form_name', 'firstName AS form_firstname', 'photoForm AS form_photo', 'email AS form_email', 'initialNameForm AS form_initial_name')
            ->groupBy('idProjet', 'idFormateur', 'name', 'firstName', 'photoForm', 'email', 'initialNameForm')
            ->where('idProjet', $id_project)
            ->get();

        return $forms;
    }

    public function search(Request $request)
    {
        $all_domaines = DB::table('domaine_formations')->select('idDomaine', 'nomDomaine')->orderBy('nomDomaine')->get();
        $domaines = [];

        foreach ($all_domaines as $doma) {
            $domaines[] = [
                'idDomaine' => $doma->idDomaine,
                'nomDomaine' => $doma->nomDomaine,
                'nb_module' => $this->getModuleDomaine($doma->idDomaine)
            ];
        }

        if (Auth::check()) {
            $user = Auth::user();
            if ($user->hasRole('Formateur')) {
                $extends_containt = "layouts.masterForm";
            } elseif ($user->hasRole('Formateur interne')) {
                $extends_containt = "layouts.masterFormInterne";
            } elseif ($user->hasRole('Particulier')) {
                $extends_containt = "layouts.masterParticulier";
            } elseif ($user->hasRole('EmployeCfp')) {
                $extends_containt = "layouts.masterEmpCfp";
            } elseif ($user->hasRole('Employe') || $user->hasRole('EmployeEtp')) {
                $extends_containt = "layouts.masterEmp";
            } elseif ($user->hasRole('Cfp')) {
                $extends_containt = "layouts.master";
            } elseif ($user->hasRole('Admin') || $user->hasRole('SuperAdmin')) {
                $extends_containt = "layouts.masterAdmin";
            } elseif ($user->hasRole('Referent')) {
                $extends_containt = "layouts.masterEtp";
            }
        } else {
            $extends_containt = "layouts.masterGuest";
        }

        $course = $request->course;
        $place = $request->place;
        $category = $request->category;
        $cfp = null;

        $firstPublicite = DB::table('mdls')
            ->join('publicites', 'publicites.idModule', 'mdls.idModule')
            ->join('customers', 'customers.idCustomer', 'mdls.idCustomer')
            ->where('publicites.is_active', 1)
            ->where('publicites.idType', 1)
            ->first();

        $otherPublicites = DB::table('mdls')
            ->join('publicites', 'publicites.idModule', 'mdls.idModule')
            ->join('customers', 'customers.idCustomer', 'mdls.idCustomer')
            ->where('publicites.is_active', 1)
            ->where('publicites.idType', 1)
            ->skip(1) // Ignorer le premier résultat
            ->take(100)
            ->get();

        return view('client.project.index', compact('cfp', 'domaines', 'course', 'place', 'category', 'extends_containt', 'firstPublicite', 'otherPublicites'));
    }

    private function getModuleDomaine($idDomaine)
    {
        return DB::table('v_module_cfps')->select('idDomaine')->where('idDomaine', $idDomaine)->where('moduleStatut', 1)->count();
    }

    public function getEval($idModule)
    {
        $projectIds = $this->getProjectByModule($idModule);

        $result = DB::table('eval_chauds')
            ->select(
                DB::raw('SUM(firstNotes.generalApreciate) as sumFirstNotes'),
                DB::raw('COUNT(DISTINCT firstNotes.idEmploye) as totalEmployees')
            )
            ->fromSub(function ($query) use ($projectIds) {
                $query->select('idEmploye', 'idProjet', 'generalApreciate')
                    ->from('eval_chauds')
                    ->whereIn('idProjet', $projectIds)
                    ->whereNotNull('generalApreciate')
                    ->groupBy('idEmploye', 'idProjet');
            }, 'firstNotes')
            ->first();

        $average = $result->totalEmployees > 0 ? $result->sumFirstNotes / $result->totalEmployees : 0;

        return [
            'totalEmployees' => $result->totalEmployees,
            'average' => round($average, 1)
        ];
    }

    public function getProjectByModule($idModule)
    {
        $projects = DB::table('v_projet_cfps')
            ->select('idProjet')
            ->where('idModule', $idModule)
            ->pluck('idProjet');
        return $projects;
    }

    public function extractText($var)
    {
        $dom = new \DOMDocument;

        $html = mb_convert_encoding($var, 'HTML-ENTITIES', 'UTF-8');
        libxml_use_internal_errors(true);
        $dom->loadHTML($html, LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD);
        libxml_clear_errors();

        $spans = $dom->getElementsByTagName('span');
        $texts = [];

        foreach ($spans as $span) {
            $texts[] = $span->textContent;
        }

        return $texts;
    }

    public function extractTextProgram($var)
    {
        $dom = new \DOMDocument;

        $html = mb_convert_encoding($var, 'HTML-ENTITIES', 'UTF-8');
        libxml_use_internal_errors(true);
        $dom->loadHTML($html, LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD);
        libxml_clear_errors();

        $spans = $dom->getElementsByTagName('span');
        $texts = [];

        foreach ($spans as $span) {
            $texts[] = $span->textContent;
        }

        return $texts;
    }


    public function organisme()
    {
        $extends_containt = null; // Variable pour stocker les extends selon l'utilisateur connecté

        // Condition pour l'extends selon l'utilisateur connecté
        if (Auth::check()) {
            $user = Auth::user();
            if ($user->hasRole('Formateur')) {
                $extends_containt = "layouts.masterForm";
            } elseif ($user->hasRole('Formateur interne')) {
                $extends_containt = "layouts.masterFormInterne";
            } elseif ($user->hasRole('Particulier')) {
                $extends_containt = "layouts.masterParticulier";
            } elseif ($user->hasRole('EmployeCfp')) {
                $extends_containt = "layouts.masterEmpCfp";
            } elseif ($user->hasRole('Employe') || $user->hasRole('EmployeEtp')) {
                $extends_containt = "layouts.masterEmp";
            } elseif ($user->hasRole('Cfp')) {
                $extends_containt = "layouts.master";
            } elseif ($user->hasRole('Admin') || $user->hasRole('SuperAdmin')) {
                $extends_containt = "layouts.masterAdmin";
            } elseif ($user->hasRole('Referent')) {
                $extends_containt = "layouts.masterEtp";
            }
        } else {
            $extends_containt = "layouts.masterGuest";
        }

        $customers = DB::table('customers as C')
            ->join('cfp_selected_by_admin as CSA', 'CSA.idCfp', '=', 'C.idCustomer')
            ->select('C.*', 'CSA.date_added')
            ->join('users', 'users.id', 'C.idCustomer')
            ->where('users.user_is_deleted', 0)
            ->where('C.idTypeCustomer', 1)
            ->get();
        $organismes = DB::table('customers')
            ->select('*')
            ->join('role_users', 'role_users.user_id', 'customers.idCustomer')
            ->join('users', 'users.id', 'customers.idCustomer')
            ->where('users.user_is_deleted', 0)
            ->where('role_users.isActive', 1)
            ->where('idTypeCustomer', 1)->get();
        $countOrganismes = $organismes->count();
        $roundedOrganismes = floor($countOrganismes / 10) * 10;
        $all_domaines = DB::table('domaine_formations')->select('idDomaine', 'nomDomaine')->orderBy('nomDomaine')->get();
        $domaines = [];

        $endpointController = config('filesystems.disks.do.url_cdn_digital');
        $bucketController = config('filesystems.disks.do.bucket');

        $digitalOcean = $endpointController . '/' . $bucketController;

        foreach ($all_domaines as $doma) {
            $domaines[] = [
                'idDomaine' => $doma->idDomaine,
                'nomDomaine' => $doma->nomDomaine,
                'nb_module' => $this->getModuleDomaine($doma->idDomaine)
            ];
        }

        $firstTwoPublicites = DB::table('v_module_cfps')
            ->join('publicites', 'publicites.idModule', 'v_module_cfps.idModule')
            ->join('customers', 'customers.idCustomer', 'v_module_cfps.idCustomer')
            ->where('publicites.is_active', 1)
            ->where('publicites.idType', 1)
            ->take(2)
            ->get();
        $firstTwoPublicites->transform(function ($publicite) {
            $publicite->note = $this->getEval($publicite->idModule); // Ajoute la note
            return $publicite;
        });


        $otherPublicites = DB::table('mdls')
            ->join('publicites', 'publicites.idModule', 'mdls.idModule')
            ->join('customers', 'customers.idCustomer', 'mdls.idCustomer')
            ->where('publicites.is_active', 1)
            ->where('publicites.idType', 1)
            ->skip(2)
            ->take(100)
            ->get();
        $otherPublicites->transform(function ($publicite) {
            $publicite->note = $this->getEval($publicite->idModule); // Ajoute la note
            return $publicite;
        });

        return view('client.organisme', compact('domaines', 'customers', 'roundedOrganismes', 'digitalOcean', 'extends_containt', 'firstTwoPublicites', 'otherPublicites'));
    }

    public function listeOrganisme()
    {
        $extends_containt = null; // Variable pour stocker les extends selon l'utilisateur connecté

        // Condition pour l'extends selon l'utilisateur connecté
        if (Auth::check()) {
            $user = Auth::user();
            if ($user->hasRole('Formateur')) {
                $extends_containt = "layouts.masterForm";
            } elseif ($user->hasRole('Formateur interne')) {
                $extends_containt = "layouts.masterFormInterne";
            } elseif ($user->hasRole('Particulier')) {
                $extends_containt = "layouts.masterParticulier";
            } elseif ($user->hasRole('EmployeCfp')) {
                $extends_containt = "layouts.masterEmpCfp";
            } elseif ($user->hasRole('Employe') || $user->hasRole('EmployeEtp')) {
                $extends_containt = "layouts.masterEmp";
            } elseif ($user->hasRole('Cfp')) {
                $extends_containt = "layouts.master";
            } elseif ($user->hasRole('Admin') || $user->hasRole('SuperAdmin')) {
                $extends_containt = "layouts.masterAdmin";
            } elseif ($user->hasRole('Referent')) {
                $extends_containt = "layouts.masterEtp";
            }
        } else {
            $extends_containt = "layouts.masterGuest";
        }


        $customers = DB::table('customers')
            ->select('*')
            ->join('role_users', 'role_users.user_id', 'customers.idCustomer')
            ->join('users', 'users.id', 'customers.idCustomer')
            ->where('users.user_is_deleted', 0)
            ->where('role_users.isActive', 1)
            ->where('idTypeCustomer', 1)
            ->get();
        $all_domaines = DB::table('domaine_formations')->select('idDomaine', 'nomDomaine')->get();
        $domaines = [];

        $endpointController = config('filesystems.disks.do.url_cdn_digital');
        $bucketController = config('filesystems.disks.do.bucket');

        $digitalOcean = $endpointController . '/' . $bucketController;

        foreach ($all_domaines as $doma) {
            $domaines[] = [
                'idDomaine' => $doma->idDomaine,
                'nomDomaine' => $doma->nomDomaine,
                'nb_module' => $this->getModuleDomaine($doma->idDomaine)
            ];
        }
        return view('client.listeOrganisme', compact('domaines', 'customers', 'digitalOcean', 'extends_containt'));
    }

    public function formationInfo($id)
    {

        $extends_containt = null; // Variable pour stocker les extends selon l'utilisateur connecté

        // Condition pour l'extends selon l'utilisateur connecté
        if (Auth::check()) {
            $user = Auth::user();
            if ($user->hasRole('Formateur')) {
                $extends_containt = "layouts.masterForm";
            } elseif ($user->hasRole('Formateur interne')) {
                $extends_containt = "layouts.masterFormInterne";
            } elseif ($user->hasRole('Particulier')) {
                $extends_containt = "layouts.masterParticulier";
            } elseif ($user->hasRole('EmployeCfp')) {
                $extends_containt = "layouts.masterEmpCfp";
            } elseif ($user->hasRole('Employe') || $user->hasRole('EmployeEtp')) {
                $extends_containt = "layouts.masterEmp";
            } elseif ($user->hasRole('Cfp')) {
                $extends_containt = "layouts.master";
            } elseif ($user->hasRole('Admin') || $user->hasRole('SuperAdmin')) {
                $extends_containt = "layouts.masterAdmin";
            } elseif ($user->hasRole('Referent')) {
                $extends_containt = "layouts.masterEtp";
            }
        } else {
            $extends_containt = "layouts.masterGuest";
        }


        $customer = DB::table('customers')
            ->select('*')
            ->join('secteurs as SC', 'SC.idSecteur', '=', 'customers.idSecteur')
            ->where('idCustomer', $id)
            ->first();
        $all_domaines = DB::table('domaine_formations')->select('idDomaine', 'nomDomaine')->orderBy('nomDomaine')->get();
        $domaines = [];

        foreach ($all_domaines as $doma) {
            $domaines[] = [
                'idDomaine' => $doma->idDomaine,
                'nomDomaine' => $doma->nomDomaine,
                'nb_module' => $this->getModuleDomaine($doma->idDomaine)
            ];
        }

        $get_projects = DB::table('v_module_cfps')
            ->select(
                'idModule',
                'module_image',
                'moduleName as module_name',
                'moduleStatut',
                'description as module_description',
                'minApprenant',
                'prix',
                'dureeH',
                'dureeJ',
                'description',
                'prixGroupe',
                'idCustomer',
                'cfpName as cfp_name',
                'logo as logo_cfp',
                'nomDomaine as domaine_name',
                'idDomaine'
            )
            ->where('moduleStatut', 1)
            ->where('moduleName', '!=', 'Default module')
            ->where('idCustomer', $id)
            ->orderBy('moduleName')
            ->get();

        $projects = [];
        foreach ($get_projects as $projec) {
            $projects[] = [
                'idModule' => $projec->idModule,
                'module_name' => $projec->module_name,
                'prix' => $projec->prix,
                'dureeJ' => $projec->dureeJ,
                'dureeH' => $projec->dureeH,
                'module_description' => $projec->module_description,
                'module_image' => $projec->module_image,
                'logo_cfp' => $projec->logo_cfp,
                'note' => $this->getEval($projec->idModule)
            ];
        }

        $allCollabs = DB::table('v_formateur_cfps')
            ->select('idFormateur', 'photoForm', 'name', 'firstName', 'form_titre', 'form_speciality')
            ->where('idCfp', $id)
            ->groupBy('idFormateur')
            ->orderBy('isActive', 'desc')
            ->get();

        $user = DB::table('users')->select('name', 'firstName')->where('id', $id)->first();

        $endpointController = config('filesystems.disks.do.url_cdn_digital');
        $bucketController = config('filesystems.disks.do.bucket');

        $digitalOcean = $endpointController . '/' . $bucketController;

        $get_domaines = DB::table('v_module_cfps')->select('idDomaine', 'nomDomaine')->where('moduleStatut', 1)->where('idCUstomer', $id)->whereNot('moduleName', "Default module")->groupBy('idDomaine')->get();

        $onlineModules = [];
        foreach ($get_domaines as $d) {
            $onlineModules[] = [
                'idDomaine' => $d->idDomaine,
                'nomDomaine' => $d->nomDomaine,
                "modules" => $this->getModules($d->idDomaine, $id)
            ];
        }
        return view('client.formationInfo', compact('domaines', 'customer', 'user', 'allCollabs', 'digitalOcean', 'onlineModules', 'extends_containt'));
    }

    public function getModules($idDomaine, $id)
    {
        $get_mod = DB::table('v_module_cfps')
            ->select('idDomaine', 'moduleName', 'idModule', 'prix', 'dureeJ', 'dureeH', 'moduleStatut', 'module_image', 'module_is_complete', 'cfpName', 'logo', 'module_level_name')
            ->where('idCustomer', $id)
            ->whereNot('moduleName', 'Default module')
            ->where('idDomaine', $idDomaine)
            ->get();

        $modules = [];
        foreach ($get_mod as $gm) {
            $modules[] = [
                'idDomaine' => $gm->idDomaine,
                'idModule' => $gm->idModule,
                'module_name' => $gm->moduleName,
                'prix' => $gm->prix,
                'dureeJ' => $gm->dureeJ,
                'dureeH' => $gm->dureeH,
                'moduleStatut' => $gm->moduleStatut,
                'module_image' => $gm->module_image,
                'module_is_complete' => $gm->module_is_complete,
                'cfp_name' => $gm->cfpName,
                'logo_cfp' => $gm->logo,
                'module_level_name' => $gm->module_level_name,
                'note' => $this->getEval($gm->idModule)
            ];
        }

        return $modules;
    }

    public function getEvals($idModule)
    {
        $idProjets = DB::table('projets')->where('idModule', $idModule)->pluck('idProjet');
        $get_eval = DB::table('eval_chauds')
            ->select(DB::raw("AVG(generalApreciate) AS note"), DB::raw("COUNT(idEmploye) AS total_emp"))
            ->whereIn('idProjet', $idProjets)
            ->groupBy('idEmploye')
            ->get();

        return $get_eval->toArray();
    }

    public function checkUser(Request $request)
    {
        $email = $request->email;
        Session::put('email', $email);
        $userExists = User::where('email', $email)->exists();
        if ($userExists) {
            return redirect('user/login');
        } else {
            return redirect('user/register');
        }
    }

    public function resetPassword()
    {
        return view('client.resetPassword');
    }

    public function indexQuote($id, $idModule)
    {
        $etpId = $id;
        $module = $idModule;

        $extends_containt = null; // Variable pour stocker les extends selon l'utilisateur connecté

        // Condition pour l'extends selon l'utilisateur connecté
        if (Auth::check()) {
            $user = Auth::user();
            if ($user->hasRole('Formateur')) {
                $extends_containt = "layouts.masterForm";
            } elseif ($user->hasRole('Formateur interne')) {
                $extends_containt = "layouts.masterFormInterne";
            } elseif ($user->hasRole('Particulier')) {
                $extends_containt = "layouts.masterParticulier";
            } elseif ($user->hasRole('EmployeCfp')) {
                $extends_containt = "layouts.masterEmpCfp";
            } elseif ($user->hasRole('Employe') || $user->hasRole('EmployeEtp')) {
                $extends_containt = "layouts.masterEmp";
            } elseif ($user->hasRole('Cfp')) {
                $extends_containt = "layouts.master";
            } elseif ($user->hasRole('Admin') || $user->hasRole('SuperAdmin')) {
                $extends_containt = "layouts.masterAdmin";
            } elseif ($user->hasRole('Referent')) {
                $extends_containt = "layouts.masterEtp";
            }
        } else {
            $extends_containt = "layouts.masterGuest";
        }

        $all_domaines = DB::table('domaine_formations')->select('idDomaine', 'nomDomaine')->orderBy('nomDomaine')->get();
        $domaines = [];

        foreach ($all_domaines as $doma) {
            $domaines[] = [
                'idDomaine' => $doma->idDomaine,
                'nomDomaine' => $doma->nomDomaine,
                'nb_module' => $this->getModuleDomaine($doma->idDomaine)
            ];
        }
        return view('client.quote.index', compact('domaines', 'extends_containt', 'etpId', 'module'));
    }

    public function quoteCompany($id, $idModule)
    {

        $etpId = $id;
        $module = $idModule;

        $extends_containt = null; // Variable pour stocker les extends selon l'utilisateur connecté

        // Condition pour l'extends selon l'utilisateur connecté
        if (Auth::check()) {
            $user = Auth::user();
            if ($user->hasRole('Formateur')) {
                $extends_containt = "layouts.masterForm";
            } elseif ($user->hasRole('Formateur interne')) {
                $extends_containt = "layouts.masterFormInterne";
            } elseif ($user->hasRole('Particulier')) {
                $extends_containt = "layouts.masterParticulier";
            } elseif ($user->hasRole('EmployeCfp')) {
                $extends_containt = "layouts.masterEmpCfp";
            } elseif ($user->hasRole('Employe') || $user->hasRole('EmployeEtp')) {
                $extends_containt = "layouts.masterEmp";
            } elseif ($user->hasRole('Cfp')) {
                $extends_containt = "layouts.master";
            } elseif ($user->hasRole('Admin') || $user->hasRole('SuperAdmin')) {
                $extends_containt = "layouts.masterAdmin";
            } elseif ($user->hasRole('Referent')) {
                $extends_containt = "layouts.masterEtp";
            }
        } else {
            $extends_containt = "layouts.masterGuest";
        }

        $all_domaines = DB::table('domaine_formations')->select('idDomaine', 'nomDomaine')->orderBy('nomDomaine')->get();
        $domaines = [];

        foreach ($all_domaines as $doma) {
            $domaines[] = [
                'idDomaine' => $doma->idDomaine,
                'nomDomaine' => $doma->nomDomaine,
                'nb_module' => $this->getModuleDomaine($doma->idDomaine)
            ];
        }
        return view('client.quote.company', compact('domaines', 'extends_containt', 'etpId', 'module'));
    }

    public function quoteIndividual($id, $idModule)
    {

        $etpId = $id;
        $module = $idModule;

        $extends_containt = null; // Variable pour stocker les extends selon l'utilisateur connecté

        // Condition pour l'extends selon l'utilisateur connecté
        if (Auth::check()) {
            $user = Auth::user();
            if ($user->hasRole('Formateur')) {
                $extends_containt = "layouts.masterForm";
            } elseif ($user->hasRole('Formateur interne')) {
                $extends_containt = "layouts.masterFormInterne";
            } elseif ($user->hasRole('Particulier')) {
                $extends_containt = "layouts.masterParticulier";
            } elseif ($user->hasRole('EmployeCfp')) {
                $extends_containt = "layouts.masterEmpCfp";
            } elseif ($user->hasRole('Employe') || $user->hasRole('EmployeEtp')) {
                $extends_containt = "layouts.masterEmp";
            } elseif ($user->hasRole('Cfp')) {
                $extends_containt = "layouts.master";
            } elseif ($user->hasRole('Admin') || $user->hasRole('SuperAdmin')) {
                $extends_containt = "layouts.masterAdmin";
            } elseif ($user->hasRole('Referent')) {
                $extends_containt = "layouts.masterEtp";
            }
        } else {
            $extends_containt = "layouts.masterGuest";
        }

        $all_domaines = DB::table('domaine_formations')->select('idDomaine', 'nomDomaine')->orderBy('nomDomaine')->get();
        $domaines = [];

        foreach ($all_domaines as $doma) {
            $domaines[] = [
                'idDomaine' => $doma->idDomaine,
                'nomDomaine' => $doma->nomDomaine,
                'nb_module' => $this->getModuleDomaine($doma->idDomaine)
            ];
        }
        return view('client.quote.individual', compact('domaines', 'extends_containt', 'etpId', 'module'));
    }

    public function reservation($id)
    {

        $extends_containt = null; // Variable pour stocker les extends selon l'utilisateur connecté

        // Condition pour l'extends selon l'utilisateur connecté
        if (Auth::check()) {
            $user = Auth::user();
            if ($user->hasRole('Formateur')) {
                $extends_containt = "layouts.masterForm";
            } elseif ($user->hasRole('Formateur interne')) {
                $extends_containt = "layouts.masterFormInterne";
            } elseif ($user->hasRole('Particulier')) {
                $extends_containt = "layouts.masterParticulier";
            } elseif ($user->hasRole('EmployeCfp')) {
                $extends_containt = "layouts.masterEmpCfp";
            } elseif ($user->hasRole('Employe') || $user->hasRole('EmployeEtp')) {
                $extends_containt = "layouts.masterEmp";
            } elseif ($user->hasRole('Cfp')) {
                $extends_containt = "layouts.master";
            } elseif ($user->hasRole('Admin') || $user->hasRole('SuperAdmin')) {
                $extends_containt = "layouts.masterAdmin";
            } elseif ($user->hasRole('Referent')) {
                $extends_containt = "layouts.masterEtp";
            }
        } else {
            $extends_containt = "layouts.masterGuest";
        }

        $project_cfp = DB::table('v_projet_cfps AS P')
            ->select(
                'P.idProjet',
                'P.dateDebut as dateDebut',
                'P.dateFin as dateFin',
                'P.project_title',
                'P.module_name as moduleName',
                'P.etp_name as etp_name',
                'ville',
                'project_status',
                'project_description',
                'project_type',
                'paiement',
                'P.etp_logo',
                'etp_initial_name',
                'salle_name',
                'salle_quartier',
                'salle_code_postal',
                'idCfp_inter',
                'P.idCfp_inter'
            )
            ->where('project_status', "Planifié")
            ->where('idProjet', $id)
            ->orderBy('dateDebut')
            ->first();
        $module_name = $project_cfp->moduleName;
        $date_begin = $this->monthConverted($project_cfp->dateDebut);
        $date_end = $this->dateConverted($project_cfp->dateFin);
        session(['id_project_inter' => $id]);
        $all_domaines = DB::table('domaine_formations')->select('idDomaine', 'nomDomaine')->orderBy('nomDomaine')->get();
        $domaines = [];

        foreach ($all_domaines as $doma) {
            $domaines[] = [
                'idDomaine' => $doma->idDomaine,
                'nomDomaine' => $doma->nomDomaine,
                'nb_module' => $this->getModuleDomaine($doma->idDomaine)
            ];
        }
        return view('client.project.reservation', compact('domaines', 'date_begin', 'date_end', 'module_name', 'id', 'extends_containt'));
    }

    public function convertirEnLettres($nombre)
    {
        $f = new \NumberFormatter("fr_FR", \NumberFormatter::SPELLOUT);
        return ucfirst($f->format($nombre));
    }

    public function reservationStore(Request $request)
    {
        $id_etp = DB::table('entreprises')->where('idCustomer', Auth::user()->id)->exists();
        $projet = DB::table('projets as P')
            ->select(
                'P.idProjet',
                'P.dateDebut',
                'P.dateFin',
                'P.project_title',
                'V.ville',
                'M.prix',
                'MD.moduleName as module_name',
                'P.idCustomer as idCustomer',
                'P.idModule as idModule'
            )
            ->join('ville_codeds', 'ville_codeds.id', 'P.idVilleCoded')
            ->join('villes as V', 'V.idVille', '=', 'ville_codeds.idVille')
            ->join('inters as I', 'I.idProjet', '=', 'P.idProjet')
            ->join('modules as M', 'M.idModule', '=', 'P.idModule')
            ->join('mdls as MD', 'MD.idModule', '=', 'P.idModule')
            ->where('P.idProjet', session('id_project_inter'))
            ->first();

        $customer = DB::table('customers')->select('customerName')->where('idCustomer', $projet->idCustomer)->first();
        $reservation = DB::table('inter_entreprises')->where('idEtp', Auth::user()->id)->where('idProjet', session('id_project_inter'))->exists();

        if (!$id_etp) {
            return redirect()->route('formation.detail', ['id' => $projet->idModule])->with('error', 'Vous ne pouvez pas réserver de places; seule l\'entreprise en a la possibilité.');
        }

        if ($reservation) {
            return redirect()->route('formation.detail', ['id' => $projet->idModule])->with('error', 'Vous avez déjà effectué une réservation pour ce projet.');
        }

        $reservation_id = null;
        DB::transaction(function () use ($request, $projet, $customer, &$reservation_id) {
            $reservation_id = DB::table('inter_entreprises')->insertGetId([
                'idProjet' => session('id_project_inter'),
                'idEtp' => Auth::user()->id,
                'isActiveInter' => 0,
                'nbPlaceReserved' => $request->nbPlace
            ]);

            $prixTotal = $projet->prix * $request->nbPlace;

            $idPaiement = DB::table('mode_paiements')->insertGetId([
                'idTypePm' => 1
            ]);

            $invoiceId = DB::table('invoices')->insertGetId([
                'invoice_number' => 'RSV-' . $reservation_id,
                'invoice_date' => now(),
                'invoice_date_pm' => now()->addDays(10),
                'invoice_status' => 1,
                'invoice_reduction' => 0,
                'invoice_tva' => 0,
                'invoice_sub_total' => $prixTotal,
                'invoice_total_amount' => $prixTotal,
                // 'invoice_letter' => $this->convertirEnLettres($prixTotal), miverina amin'ito zoma
                'invoice_letter' => null,
                'idCustomer' => $projet->idCustomer,
                'idEntreprise' => Auth::user()->id,
                'idPaiement' => $idPaiement,
                'idTypeFacture' => 2
            ]);

            DB::table('invoice_details')->insert([
                'idInvoice' => $invoiceId,
                'idItems' => 0,
                'idProjet' => $projet->idProjet,
                'item_qty' => $request->nbPlace,
                'item_description' => 'Réservation pour ' . $projet->project_title,
                'item_unit_price' => $projet->prix,
                'idUnite' => 1,
                'item_total_price' => $prixTotal,
            ]);

            session()->flash('reservation_data', [
                'id' => $projet->idModule,
                'nbPlace' => $request->nbPlace,
                'date_begin' => $this->monthConverted($projet->dateDebut),
                'date_end' => $this->dateConverted($projet->dateFin),
                'project_title' => $projet->project_title,
                'customer_name' => $customer->customerName,
                'ville' => $projet->ville,
                'module_name' => $projet->module_name,
                'prix_total' => $prixTotal,
                'invoice_id' => $invoiceId
            ]);
        });

        return redirect()->route('reservation.confirmed', $reservation_id);
    }


    public function reservationConfirmed()
    {
        $reservation_data = session('reservation_data');
        $domaines = DB::table('domaine_formations')->select('*')->get();
        return view('client.reservation.confirmed', compact('domaines', 'reservation_data'));
    }

    public function register(Request $req)
    {
        if ($req->account_type == 2) {
            $validate = Validator::make($req->all(), [
                'customer_name' => 'required|min:2|max:200',
                'customer_nif'  => 'required|unique:customers,nif',
                'referent_name' => 'required|min:2|max:250',
                'referent_firstName' => 'required|min:2|max:250',
                'customer_email' => 'required|unique:users,email',
                'password' => 'required|min:8',
            ], [
                'customer_name.required' => "Ce champs est obligatoire",
                'customer_name.min' => "Veuillez mettre au moins 2 Caractères",
                'customer_name.max' => "Veuillez ne pas dépasser les 50 Caractères",
                'referent_name.required' => "Ce champs est obligatoire",
                'referent_name.min' => "Veuillez mettre au moins 2 Caractères",
                'referent_firstName.required' => "Ce champs est obligatoire",
                'password.required' => "Ce champs est obligatoire",
                'password.min' => "Veuillez mettre au moins 8 Caractères",
            ]);

            if ($validate->fails()) {
                return response()->json(['errors' => $validate->errors()], 422);
            } else {
                try {
                    DB::beginTransaction();

                    $user = new User();
                    $user->name = $req->referent_name;
                    $user->email = $req->customer_email;
                    $password = Hash::make($req->password);
                    $user->password = $password;
                    $user->save();

                    $cst = new Customer();
                    $cst->idCustomer = $user->id;
                    $cst->customerName = $req->customer_name;
                    $cst->customerEmail = $req->customer_email;
                    $cst->idSecteur = 7;
                    $cst->idTypeCustomer = 2;
                    $cst->nif = $req->customer_nif;
                    $cst->save();

                    $idModule = DB::table('mdls')->insertGetId([
                        'moduleName' => "Default module",
                        'idDomaine' => 1,
                        'idCustomer' => $user->id,
                        'idTypeModule' => 2
                    ]);

                    DB::table('salles')->insert([
                        'idCustomer' => $user->id,
                        'salle_name' => "In situ",
                        'idVille' => 1
                    ]);

                    DB::table('fonctions')->insert([
                        'fonction' => "default_fonction",
                        'idCustomer' => $user->id
                    ]);

                    DB::table('module_internes')->insert(['idModule' => $idModule]);
                    DB::table('entreprises')->insert([
                        'idCustomer' => $user->id,
                        'idTypeEtp' => 1
                    ]);
                    DB::table('etp_singles')->insert(['idEntreprise' => $user->id]);

                    $emp = new Employe();
                    $emp->idEmploye = $user->id;
                    $emp->idNiveau = 6;
                    $emp->idCustomer = $user->id;
                    $emp->idSexe = 1;
                    $emp->save();

                    RoleUser::create([
                        'role_id'  => 6,
                        'user_id'  => $user->id,
                        'isActive' => 0,
                        'hasRole' => 1,
                        'user_is_in_service' => 1
                    ]);

                    DB::commit();

                    Auth::login($user);
                    return response()->json(['success' => true, 'redirect' => route('reservation', ['project_id' => $req->project_id])]);
                } catch (Exception $e) {
                    DB::rollBack();
                    return response()->json(['errors' => $validate->errors()], 422);
                }
            }
        } else {
            return back()->with('error', "Erreur inconnue !");
        }
    }

    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|string|email',
            'password' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
            return response()->json(['success' => true, 'redirect' => route('reservation', ['project_id' => $request->project_id])]);
        } else {
            return response()->json(['errors' => ['email' => ['Invalid credentials.']]], 401);
        }
    }

    public function accueilFormation()
    {

        $extends_containt = null; // Variable pour stocker les extends selon l'utilisateur connecté

        // Condition pour l'extends selon l'utilisateur connecté
        if (Auth::check()) {
            $user = Auth::user();
            if ($user->hasRole('Formateur')) {
                $extends_containt = "layouts.masterForm";
            } elseif ($user->hasRole('Formateur interne')) {
                $extends_containt = "layouts.masterFormInterne";
            } elseif ($user->hasRole('Particulier')) {
                $extends_containt = "layouts.masterParticulier";
            } elseif ($user->hasRole('EmployeCfp')) {
                $extends_containt = "layouts.masterEmpCfp";
            } elseif ($user->hasRole('Employe') || $user->hasRole('EmployeEtp')) {
                $extends_containt = "layouts.masterEmp";
            } elseif ($user->hasRole('Cfp')) {
                $extends_containt = "layouts.master";
            } elseif ($user->hasRole('Admin') || $user->hasRole('SuperAdmin')) {
                $extends_containt = "layouts.masterAdmin";
            } elseif ($user->hasRole('Referent')) {
                $extends_containt = "layouts.masterEtp";
            }
        } else {
            $extends_containt = "layouts.masterGuest";
        }

        $places = DB::table('villes')->select('*')->where('idVille', '!=', 1)->orderBy('ville')->get();
        $all_domaines = DB::table('domaine_formations')->select('idDomaine', 'nomDomaine')->orderBy('nomDomaine')->get();
        $domaines = [];

        foreach ($all_domaines as $doma) {
            $domaines[] = [
                'idDomaine' => $doma->idDomaine,
                'nomDomaine' => $doma->nomDomaine,
                'nb_module' => $this->getModuleDomaine($doma->idDomaine)
            ];
        }
        return view('client.project.formationAccueil', compact('domaines', 'places', 'extends_containt'));
    }

    public function detailFormation()
    {
        $extends_containt = null; // Variable pour stocker les extends selon l'utilisateur connecté

        // Condition pour l'extends selon l'utilisateur connecté
        if (Auth::check()) {
            $user = Auth::user();
            if ($user->hasRole('Formateur')) {
                $extends_containt = "layouts.masterForm";
            } elseif ($user->hasRole('Formateur interne')) {
                $extends_containt = "layouts.masterFormInterne";
            } elseif ($user->hasRole('Particulier')) {
                $extends_containt = "layouts.masterParticulier";
            } elseif ($user->hasRole('EmployeCfp')) {
                $extends_containt = "layouts.masterEmpCfp";
            } elseif ($user->hasRole('Employe') || $user->hasRole('EmployeEtp')) {
                $extends_containt = "layouts.masterEmp";
            } elseif ($user->hasRole('Cfp')) {
                $extends_containt = "layouts.master";
            } elseif ($user->hasRole('Admin') || $user->hasRole('SuperAdmin')) {
                $extends_containt = "layouts.masterAdmin";
            } elseif ($user->hasRole('Referent')) {
                $extends_containt = "layouts.masterEtp";
            }
        } else {
            $extends_containt = "layouts.masterGuest";
        }

        $places = DB::table('villes')->select('*')->where('idVille', '!=', 1)->orderBy('ville')->get();
        $all_domaines = DB::table('domaine_formations')->select('idDomaine', 'nomDomaine')->orderBy('nomDomaine')->get();
        $domaines = [];

        foreach ($all_domaines as $doma) {
            $domaines[] = [
                'idDomaine' => $doma->idDomaine,
                'nomDomaine' => $doma->nomDomaine,
                'nb_module' => $this->getModuleDomaine($doma->idDomaine)
            ];
        }
        return view('client.project.detailFormation', compact('domaines', 'places', 'extends_containt'));
    }

    public function contacterFormaFUsion()
    {
        $extends_containt = null; // Variable pour stocker les extends selon l'utilisateur connecté

        // Condition pour l'extends selon l'utilisateur connecté
        if (Auth::check()) {
            $user = Auth::user();
            if ($user->hasRole('Formateur')) {
                $extends_containt = "layouts.masterForm";
            } elseif ($user->hasRole('Formateur interne')) {
                $extends_containt = "layouts.masterFormInterne";
            } elseif ($user->hasRole('Particulier')) {
                $extends_containt = "layouts.masterParticulier";
            } elseif ($user->hasRole('EmployeCfp')) {
                $extends_containt = "layouts.masterEmpCfp";
            } elseif ($user->hasRole('Employe') || $user->hasRole('EmployeEtp')) {
                $extends_containt = "layouts.masterEmp";
            } elseif ($user->hasRole('Cfp')) {
                $extends_containt = "layouts.master";
            } elseif ($user->hasRole('Admin') || $user->hasRole('SuperAdmin')) {
                $extends_containt = "layouts.masterAdmin";
            } elseif ($user->hasRole('Referent')) {
                $extends_containt = "layouts.masterEtp";
            }
        } else {
            $extends_containt = "layouts.masterGuest";
        }

        $places = DB::table('villes')->select('*')->where('idVille', '!=', 1)->orderBy('ville')->get();
        $all_domaines = DB::table('domaine_formations')->select('idDomaine', 'nomDomaine')->orderBy('nomDomaine')->get();
        $domaines = [];

        foreach ($all_domaines as $doma) {
            $domaines[] = [
                'idDomaine' => $doma->idDomaine,
                'nomDomaine' => $doma->nomDomaine,
                'nb_module' => $this->getModuleDomaine($doma->idDomaine)
            ];
        }
        return view('client.project.contacterFormaFusion', compact('domaines', 'places', 'extends_containt'));
    }

    public function vousEtes()
    {
        $extends_containt = null; // Variable pour stocker les extends selon l'utilisateur connecté

        // Condition pour l'extends selon l'utilisateur connecté
        if (Auth::check()) {
            $user = Auth::user();
            if ($user->hasRole('Formateur')) {
                $extends_containt = "layouts.masterForm";
            } elseif ($user->hasRole('Formateur interne')) {
                $extends_containt = "layouts.masterFormInterne";
            } elseif ($user->hasRole('Particulier')) {
                $extends_containt = "layouts.masterParticulier";
            } elseif ($user->hasRole('EmployeCfp')) {
                $extends_containt = "layouts.masterEmpCfp";
            } elseif ($user->hasRole('Employe') || $user->hasRole('EmployeEtp')) {
                $extends_containt = "layouts.masterEmp";
            } elseif ($user->hasRole('Cfp')) {
                $extends_containt = "layouts.master";
            } elseif ($user->hasRole('Admin') || $user->hasRole('SuperAdmin')) {
                $extends_containt = "layouts.masterAdmin";
            } elseif ($user->hasRole('Referent')) {
                $extends_containt = "layouts.masterEtp";
            }
        } else {
            $extends_containt = "layouts.masterGuest";
        }

        $places = DB::table('villes')->select('*')->where('idVille', '!=', 1)->orderBy('ville')->get();
        $all_domaines = DB::table('domaine_formations')->select('idDomaine', 'nomDomaine')->orderBy('nomDomaine')->get();
        $domaines = [];

        foreach ($all_domaines as $doma) {
            $domaines[] = [
                'idDomaine' => $doma->idDomaine,
                'nomDomaine' => $doma->nomDomaine,
                'nb_module' => $this->getModuleDomaine($doma->idDomaine)
            ];
        }
        return view('client.project.vousEtes', compact('domaines', 'places', 'extends_containt'));
    }


    public function vousEtesFormateur()
    {
        $extends_containt = null; // Variable pour stocker les extends selon l'utilisateur connecté

        // Condition pour l'extends selon l'utilisateur connecté
        if (Auth::check()) {
            $user = Auth::user();
            if ($user->hasRole('Formateur')) {
                $extends_containt = "layouts.masterForm";
            } elseif ($user->hasRole('Formateur interne')) {
                $extends_containt = "layouts.masterFormInterne";
            } elseif ($user->hasRole('Particulier')) {
                $extends_containt = "layouts.masterParticulier";
            } elseif ($user->hasRole('EmployeCfp')) {
                $extends_containt = "layouts.masterEmpCfp";
            } elseif ($user->hasRole('Employe') || $user->hasRole('EmployeEtp')) {
                $extends_containt = "layouts.masterEmp";
            } elseif ($user->hasRole('Cfp')) {
                $extends_containt = "layouts.master";
            } elseif ($user->hasRole('Admin') || $user->hasRole('SuperAdmin')) {
                $extends_containt = "layouts.masterAdmin";
            } elseif ($user->hasRole('Referent')) {
                $extends_containt = "layouts.masterEtp";
            }
        } else {
            $extends_containt = "layouts.masterGuest";
        }

        $places = DB::table('villes')->select('*')->where('idVille', '!=', 1)->orderBy('ville')->get();
        $all_domaines = DB::table('domaine_formations')->select('idDomaine', 'nomDomaine')->orderBy('nomDomaine')->get();
        $domaines = [];

        foreach ($all_domaines as $doma) {
            $domaines[] = [
                'idDomaine' => $doma->idDomaine,
                'nomDomaine' => $doma->nomDomaine,
                'nb_module' => $this->getModuleDomaine($doma->idDomaine)
            ];
        }
        return view('client.project.vousEtesFormateur', compact('domaines', 'places', 'extends_containt'));
    }

    public function vousEtesEtp()
    {
        $extends_containt = null; // Variable pour stocker les extends selon l'utilisateur connecté

        // Condition pour l'extends selon l'utilisateur connecté
        if (Auth::check()) {
            $user = Auth::user();
            if ($user->hasRole('Formateur')) {
                $extends_containt = "layouts.masterForm";
            } elseif ($user->hasRole('Formateur interne')) {
                $extends_containt = "layouts.masterFormInterne";
            } elseif ($user->hasRole('Particulier')) {
                $extends_containt = "layouts.masterParticulier";
            } elseif ($user->hasRole('EmployeCfp')) {
                $extends_containt = "layouts.masterEmpCfp";
            } elseif ($user->hasRole('Employe') || $user->hasRole('EmployeEtp')) {
                $extends_containt = "layouts.masterEmp";
            } elseif ($user->hasRole('Cfp')) {
                $extends_containt = "layouts.master";
            } elseif ($user->hasRole('Admin') || $user->hasRole('SuperAdmin')) {
                $extends_containt = "layouts.masterAdmin";
            } elseif ($user->hasRole('Referent')) {
                $extends_containt = "layouts.masterEtp";
            }
        } else {
            $extends_containt = "layouts.masterGuest";
        }

        $places = DB::table('villes')->select('*')->where('idVille', '!=', 1)->orderBy('ville')->get();
        $all_domaines = DB::table('domaine_formations')->select('idDomaine', 'nomDomaine')->orderBy('nomDomaine')->get();
        $domaines = [];

        foreach ($all_domaines as $doma) {
            $domaines[] = [
                'idDomaine' => $doma->idDomaine,
                'nomDomaine' => $doma->nomDomaine,
                'nb_module' => $this->getModuleDomaine($doma->idDomaine)
            ];
        }
        return view('client.project.vousEtesEtp', compact('domaines', 'places', 'extends_containt'));
    }

    public function vousEtesCfp()
    {
        $extends_containt = null; // Variable pour stocker les extends selon l'utilisateur connecté

        // Condition pour l'extends selon l'utilisateur connecté
        if (Auth::check()) {
            $user = Auth::user();
            if ($user->hasRole('Formateur')) {
                $extends_containt = "layouts.masterForm";
            } elseif ($user->hasRole('Formateur interne')) {
                $extends_containt = "layouts.masterFormInterne";
            } elseif ($user->hasRole('Particulier')) {
                $extends_containt = "layouts.masterParticulier";
            } elseif ($user->hasRole('EmployeCfp')) {
                $extends_containt = "layouts.masterEmpCfp";
            } elseif ($user->hasRole('Employe') || $user->hasRole('EmployeEtp')) {
                $extends_containt = "layouts.masterEmp";
            } elseif ($user->hasRole('Cfp')) {
                $extends_containt = "layouts.master";
            } elseif ($user->hasRole('Admin') || $user->hasRole('SuperAdmin')) {
                $extends_containt = "layouts.masterAdmin";
            } elseif ($user->hasRole('Referent')) {
                $extends_containt = "layouts.masterEtp";
            }
        } else {
            $extends_containt = "layouts.masterGuest";
        }

        $places = DB::table('villes')->select('*')->where('idVille', '!=', 1)->orderBy('ville')->get();
        $all_domaines = DB::table('domaine_formations')->select('idDomaine', 'nomDomaine')->orderBy('nomDomaine')->get();
        $domaines = [];

        foreach ($all_domaines as $doma) {
            $domaines[] = [
                'idDomaine' => $doma->idDomaine,
                'nomDomaine' => $doma->nomDomaine,
                'nb_module' => $this->getModuleDomaine($doma->idDomaine)
            ];
        }
        return view('client.project.vousEtesCfp', compact('domaines', 'places', 'extends_containt'));
    }

    public function vousEtesApprenant()
    {
        $places = DB::table('villes')->select('*')->where('idVille', '!=', 1)->orderBy('ville')->get();
        $all_domaines = DB::table('domaine_formations')->select('idDomaine', 'nomDomaine')->orderBy('nomDomaine')->get();
        $domaines = [];

        foreach ($all_domaines as $doma) {
            $domaines[] = [
                'idDomaine' => $doma->idDomaine,
                'nomDomaine' => $doma->nomDomaine,
                'nb_module' => $this->getModuleDomaine($doma->idDomaine)
            ];
        }
        return view('client.project.vousEtesApprenant', compact('domaines', 'places'));
    }

    public function vousEtesParticulier()
    {
        $extends_containt = null; // Variable pour stocker les extends selon l'utilisateur connecté

        // Condition pour l'extends selon l'utilisateur connecté
        if (Auth::check()) {
            $user = Auth::user();
            if ($user->hasRole('Formateur')) {
                $extends_containt = "layouts.masterForm";
            } elseif ($user->hasRole('Formateur interne')) {
                $extends_containt = "layouts.masterFormInterne";
            } elseif ($user->hasRole('Particulier')) {
                $extends_containt = "layouts.masterParticulier";
            } elseif ($user->hasRole('EmployeCfp')) {
                $extends_containt = "layouts.masterEmpCfp";
            } elseif ($user->hasRole('Employe') || $user->hasRole('EmployeEtp')) {
                $extends_containt = "layouts.masterEmp";
            } elseif ($user->hasRole('Cfp')) {
                $extends_containt = "layouts.master";
            } elseif ($user->hasRole('Admin') || $user->hasRole('SuperAdmin')) {
                $extends_containt = "layouts.masterAdmin";
            } elseif ($user->hasRole('Referent')) {
                $extends_containt = "layouts.masterEtp";
            }
        } else {
            $extends_containt = "layouts.masterGuest";
        }

        $places = DB::table('villes')->select('*')->where('idVille', '!=', 1)->orderBy('ville')->get();
        $all_domaines = DB::table('domaine_formations')->select('idDomaine', 'nomDomaine')->orderBy('nomDomaine')->get();
        $domaines = [];

        foreach ($all_domaines as $doma) {
            $domaines[] = [
                'idDomaine' => $doma->idDomaine,
                'nomDomaine' => $doma->nomDomaine,
                'nb_module' => $this->getModuleDomaine($doma->idDomaine)
            ];
        }
        return view('client.project.vousEtesParticulier', compact('domaines', 'places', 'extends_containt'));
    }

    public function vousEtesCfp2()
    {
        $extends_containt = null; // Variable pour stocker les extends selon l'utilisateur connecté

        // Condition pour l'extends selon l'utilisateur connecté
        if (Auth::check()) {
            $user = Auth::user();
            if ($user->hasRole('Formateur')) {
                $extends_containt = "layouts.masterForm";
            } elseif ($user->hasRole('Formateur interne')) {
                $extends_containt = "layouts.masterFormInterne";
            } elseif ($user->hasRole('Particulier')) {
                $extends_containt = "layouts.masterParticulier";
            } elseif ($user->hasRole('EmployeCfp')) {
                $extends_containt = "layouts.masterEmpCfp";
            } elseif ($user->hasRole('Employe') || $user->hasRole('EmployeEtp')) {
                $extends_containt = "layouts.masterEmp";
            } elseif ($user->hasRole('Cfp')) {
                $extends_containt = "layouts.master";
            } elseif ($user->hasRole('Admin') || $user->hasRole('SuperAdmin')) {
                $extends_containt = "layouts.masterAdmin";
            } elseif ($user->hasRole('Referent')) {
                $extends_containt = "layouts.masterEtp";
            }
        } else {
            $extends_containt = "layouts.masterGuest";
        }

        $places = DB::table('villes')->select('*')->where('idVille', '!=', 1)->orderBy('ville')->get();
        $all_domaines = DB::table('domaine_formations')->select('idDomaine', 'nomDomaine')->orderBy('nomDomaine')->get();
        $domaines = [];

        foreach ($all_domaines as $doma) {
            $domaines[] = [
                'idDomaine' => $doma->idDomaine,
                'nomDomaine' => $doma->nomDomaine,
                'nb_module' => $this->getModuleDomaine($doma->idDomaine)
            ];
        }
        return view('client.project.vousEtesCfp2', compact('domaines', 'places', 'extends_containt'));
    }


    public function sendEmail(Request $request)
    {
        $validated = $request->validate([
            'email' => 'required|email',
            'nom' => 'required|string',
            'telephone' => 'required',
            'entreprise' => 'required',
            'demandeFormation' => 'required',
            'g-recaptcha-response' => ['required', new GoogleRecaptcha]
        ]);

        Mail::raw(
            "
        Nom: {$request->nom}
        Téléphone: {$request->telephone}
        Email: {$request->email}
        Entreprise: {$request->entreprise}
        Demande: {$request->demandeFormation}
        ",
            function ($message) use ($request) {
                $message->to('contact@forma-fusion.com')
                    ->subject('Requête à votre attention');
            }
        );

        return redirect()->back()->with('success', 'Votre demande a été envoyée avec succès.');
    }

    public function sendDemandCompany(Request $req)
    {
        // Validation des données d'entrée
        $validated = Validator::make($req->all(), [
            'idVille' => 'required|integer',
            'idModule' => 'required|integer',
            'etp_name' => 'required|string',
            'etp_email' => 'required|email',
            'etp_phone' => 'nullable|string',
            'ref_name' => 'required|string',
            'ref_firstName' => 'nullable|string',
            'project_type' => 'nullable|string',
            'modalite' => 'required|string',
            'nb_appr' => 'integer|min:1',
            'financement' => 'nullable|string',
            'dateDeb' => 'required|date',
            'dateFin' => 'required|date|after_or_equal:dateDeb',
            'lieu_formation' => 'nullable|string',
            'note' => 'nullable|string',
            'idCustomer' => 'nullable|integer',
        ]);

        if ($validated->fails()) {
            return response()->json(['error' => $validated->messages()], 422); // 422 : Unprocessable Entity
        }

        $data = $validated->validated();


        try {
            DB::beginTransaction();

            // Gestion des prospects
            $id_prospect = null;
            if (empty($req->idEtp)) {
                $existingProspect = DB::table('prospects')
                    ->where('prospect_name', $data['etp_name'])
                    ->first();

                if ($existingProspect) {
                    $id_prospect = $existingProspect->id;
                } else {
                    $id_prospect = DB::table('prospects')->insertGetId([
                        'prospect_name' => $data['etp_name'],
                        'idCustomer' => $data['idCustomer'] ?? null,
                    ]);
                }
            }

            // Ajout de l'opportunité
            DB::table('opportunites')->insert([
                'id_prospect' => $id_prospect,
                'idVille' => $data['idVille'],
                'idModule' => $data['idModule'],
                'statut' => 5, // Statut "Pré-réservation"
                'nbPersonne' => $data['nb_appr'],
                'dateDeb' => $data['dateDeb'],
                'dateFin' => $data['dateFin'],
                'ref_name' => $data['ref_name'] ?? null,
                'ref_firstname' => $data['ref_firstName'] ?? null,
                'ref_email' => $data['etp_email'],
                'ref_phone' => $data['etp_phone'] ?? null,
                'source' => "Site web Forma-Fusion",
                'note' => $data['note'] ?? null,
                'idCustomer' => $data['idCustomer'] ?? null,
            ]);

            $refEmailToSend = DB::table('employes AS e')
                ->select('u.email')
                ->join('users AS u', 'e.idEmploye', '=', 'u.id')
                ->join('role_users AS ru', 'e.idEmploye', '=', 'ru.user_id')
                ->where('e.idCustomer', $data['idCustomer'])
                ->whereIn('ru.role_id', [3, 8])
                ->pluck('u.email');

            if ($refEmailToSend->isEmpty()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Aucune adresse e-mail trouvée pour l\'envoi.'
                ], 422);
            }

            switch ($data['modalite']) {
                case '1':
                    $modalite = "Présentielle";
                    break;
                case '2':
                    $modalite = "En ligne";
                    break;
                case '3':
                    $modalite = "Blended";
                    break;
                default;
            }

            switch ($data['project_type']) {
                case '1':
                    $type_projet = "Intra";
                    break;
                case '2':
                    $type_projet = "Inter";
                    break;
                default;
            }

            switch ($data['financement']) {
                case '1':
                    $type_financement = "FMFP";
                    break;
                case '2':
                    $type_financement = "Fonds Propres";
                    break;
                case '3':
                    $type_financement = "Autres";
                    break;
                default;
            }

            try {
                Mail::to($refEmailToSend->toArray())->send(
                    new DevisMail(
                        $data['etp_name'],
                        $data['etp_email'],
                        $data['etp_phone'] ?? '',
                        $data['ref_name'] ?? '',
                        $data['ref_firstName'] ?? '',
                        $type_projet ?? '',
                        $modalite ?? '',
                        $data['nb_appr'],
                        $type_financement ?? '',
                        $data['dateDeb'],
                        $data['dateFin'],
                        $data['lieu_formation'] ?? '',
                        $data['note'] ?? ''
                    )
                );
            } catch (\Exception $e) {
                \Log::error('Erreur lors de l\'envoi de l\'e-mail : ' . $e->getMessage());
                return response()->json([
                    'success' => false,
                    'message' => 'Erreur lors de l\'envoi de l\'e-mail.' . $e->getMessage()
                ], 500);
            }
            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'E-mail envoyé avec succès.'
            ], 200);
        } catch (\Exception $e) {
            DB::rollBack();

            // Log de l'erreur (optionnel)
            \Log::error('Erreur lors de la création de l\'opportunité : ' . $e->getMessage());

            return response()->json([
                'error' => true,
                'message' => 'Une erreur est survenue lors de l\'ajout de l\'opportunité.',
            ], 500);
        }
    }

    public function sendDemandIndividual(Request $req)
    {
        // Validation des données d'entrée
        $validated = Validator::make($req->all(), [
            'idVille' => 'required|integer',
            'idModule' => 'required|integer',
            'name' => 'required',
            'firstname' => 'nullable|string',
            'situationPro' => 'nullable|string',
            'email' => 'required|email',
            'phone' => 'nullable|string',
            'modalite' => 'required',
            'financement' => 'nullable|string',
            'dateDeb' => 'required|date',
            'dateFin' => 'required|date|after_or_equal:dateDeb',
            'lieu_formation' => 'nullable|string',
            'note' => 'nullable|string',
            'idCustomer' => 'nullable|integer',
        ]);

        if ($validated->fails()) {
            return response()->json(['error' => $validated->messages()], 422); // 422 : Unprocessable Entity
        }

        if (isset($req->modalite)) {
            switch ($req->modalite) {
                case '1':
                    $modalite = 'Présentielle';
                    break;
                case '2':
                    $modalite = 'En ligne';
                    break;
                case '3':
                    $modalite = 'Blended';
                    break;
                default;
            }
        }

        if (isset($req->financement)) {
            switch ($req->financement) {
                case '1':
                    $financement = 'FMFP';
                    break;
                case '2':
                    $financement = 'Fonds Propres';
                    break;
                case '3':
                    $financement = 'Autres';
                    break;
                default;
            }
        }

        $data = $validated->validated();

        try {
            $refEmailToSend = DB::table('employes AS e')
                ->select('u.email')
                ->join('users AS u', 'e.idEmploye', '=', 'u.id')
                ->join('role_users AS ru', 'e.idEmploye', '=', 'ru.user_id')
                ->where('e.idCustomer', $data['idCustomer'])
                ->whereIn('ru.role_id', [3, 8])
                ->pluck('u.email');

            if ($refEmailToSend->isEmpty()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Aucune adresse e-mail trouvée pour l\'envoi.'
                ], 422);
            }

            Mail::to($refEmailToSend->toArray())->send(
                new IndividualDevisMail(
                    $data['name'],
                    $data['firstname'] ?? '',
                    $data['situationPro'] ?? '',
                    $data['email'],
                    $data['phone'] ?? '',
                    $modalite ?? '',
                    $financement ?? '',
                    $data['dateDeb'],
                    $data['dateFin'],
                    $data['lieu_formation'] ?? '',
                    $data['note'] ?? '',
                )
            );

            return response()->json([
                'success' => true,
                'message' => 'E-mail envoyé avec succès.'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'error' => "Une erreur est survenue lors de l'ajout de l'opportunité." . $e->getMessage()
            ], 500);
        }
    }
}
