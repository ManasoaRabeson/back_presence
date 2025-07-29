<?php

namespace App\Http\Controllers;

use App\Traits\MarketPlaceQuery;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class MarketPlaceController extends Controller
{
    use MarketPlaceQuery;
    
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
            ->skip(1) 
            ->take(100)
            ->get();

        return view('client.project.index', compact('cfp', 'domaines', 'course', 'place', 'category', 'extends_containt', 'firstPublicite', 'otherPublicites'));
    }

    public function searchJson(Request $request)
    {
        $key = $request->course;
        $place = $request->place;
        $category = $request->category;
        $cfp = $request->cfp;
        $isCfp = (isset($cfp)) ? $cfp : null;
        
        $projects = $this->getProject($isCfp, $key, $category, $place, $cfp);
        $project_count = count($projects->get());
        $results = $projects->paginate(21);
        $domaines = $this->getDomaine($isCfp, $key, $category, $place, $cfp);
        $villes = $this->getVille($isCfp, $key, $category, $place, $cfp);

        $cfp = $this->getCfp($isCfp, $key, $category, $place, $cfp);
        $levels = $this->getLevel($isCfp, $key, $category, $place, $cfp);

        $sessionGuaranteed = $this->getSessionGuaranteed($key);

        $projects = [];
        foreach ($results as $p) {
            $projects[] = [
                'project' => $p,
                'note' => $this->getEval($p->idModule)
            ];
        }

        $endpointController = config('filesystems.disks.do.url_cdn_digital');
        $bucketController = config('filesystems.disks.do.bucket');

        $digitalOcean = $endpointController . '/' . $bucketController;

        $projectHtml = view('components.project-list-search', [
            'projects' => $projects,
            'count' => $project_count,
            'digitalOcean' => $digitalOcean
        ])->render();

        $filterHtml = view('components.project-list-filter', [
            'cfp' => $cfp,
            'domaine' => $domaines,
            'villes' => $villes,
            'levels' => $levels
        ])->render();

        $filterHtmlMobile = view('components.project-list-filter-mobile', [
            'cfp' => $cfp,
            'domaine' => $domaines,
            'villes' => $villes
        ])->render();

        return response()->json([
            'projectHtml' => $projectHtml,
            'projects' => $projects,
            'cfp' => $cfp,
            'domaines_search' => $domaines,
            'villes' => $villes,
            'places' => $place,
            'categories' => $category,
            'project_count' => $project_count,
            'key' => $key,
            'filterHtml' => $filterHtml,
            'filterHtmlMobile' => $filterHtmlMobile,
            'is_cfp' => $isCfp,
            'session_guaranteeds' => $sessionGuaranteed,
            'current_page' => $results->currentPage(),
            'last_page' => $results->lastPage(),
        ]);
    }

    public function filterCourse(Request $request)
    {
        $domaineIds = empty($request->domaineIds) ? null : array_map('intval', explode(',', $request->domaineIds));
        $cfpIds = empty($request->cfpIds) ? null : array_map('intval', explode(',', $request->cfpIds));
        $villeIds = empty($request->villeIds) ? null : array_map('intval', explode(',', $request->villeIds));
        $duringIds = empty($request->duringIds) ? null : array_map('intval', explode(',', $request->duringIds));
        $levelIds = empty($request->levelIds) ? null : array_map('intval', explode(',', $request->levelIds));
        $key = $request->valueSearch;

        $projects = $this->getCourseFiltered($domaineIds, $cfpIds, $villeIds, $duringIds, $levelIds, $key)['projects'];
        $projectCount = $this->getCourseFiltered($domaineIds, $cfpIds, $villeIds, $duringIds, $levelIds, $key)['project_count'];
        $currentPage = $this->getCourseFiltered($domaineIds, $cfpIds, $villeIds, $duringIds, $levelIds, $key)['current_page'];
        $lastPage = $this->getCourseFiltered($domaineIds, $cfpIds, $villeIds, $duringIds, $levelIds, $key)['last_page'];

        $projectHtml = view('components.project-list-search', [
            'projects' => $projects,
            'count' => $projectCount
        ])->render();

        return response()->json([
            'projectHtml' => $projectHtml,
            'domaine' => $domaineIds,
            'project_count' => $projectCount,
            'projects' => $projects,
            'current_page' => $currentPage,
            'last_page' => $lastPage
        ]);
    }

    public function searchSessionGuaranteed(Request $request)
    {
        $sessionGuaranteed = $this->getAllSessionGuaranteed($request->valueSearch);
        $filterHtml = view('components.project-list-filter', [
            'cfp' => $sessionGuaranteed['cfps'],
            'villes' => $sessionGuaranteed['villes'],
            'domaine' => $sessionGuaranteed['domaines'],
            'levels' => $sessionGuaranteed['levels']
        ])->render();

        $projectsHtml = view('components.projecct-list-guaranteed', ['projects' => $sessionGuaranteed['projects']])->render();

        return response()->json([
            'projects' => $sessionGuaranteed['projects'],
            'projectsHtml' => $projectsHtml,
            'filterHtml' => $filterHtml,
            'domaines' => $sessionGuaranteed['domaines'],
            'villes' => $sessionGuaranteed['villes'],
            'cfps' => $sessionGuaranteed['cfps'],
            'project_count' => $sessionGuaranteed['project_count'],
            'current_page' => $sessionGuaranteed['current_page'],
            'last_page' => $sessionGuaranteed['last_page']
        ]);
    }

    public function filterCourseGuaranteed(Request $request)
    {
        $domaineIds = empty($request->domaineIds) ? null : array_map('intval', explode(',', $request->domaineIds));
        $cfpIds = empty($request->cfpIds) ? null : array_map('intval', explode(',', $request->cfpIds));
        $villeIds = empty($request->villeIds) ? null : array_map('intval', explode(',', $request->villeIds));
        $duringIds = empty($request->duringIds) ? null : array_map('intval', explode(',', $request->duringIds));
        $levelIds = empty($request->levelIds) ? null : array_map('intval', explode(',', $request->levelIds));

        $selectedTime = $request->selectedTime;
        $startDate = $request->startDate;
        $endDate = $request->endDate;

        $key = $request->valueSearch;

        $sessionGuaranteed = $this->getSessionGuaranteedFilter($domaineIds, $cfpIds, $villeIds, $duringIds, $levelIds, $key, $selectedTime, $startDate, $endDate);

        $projectHtml = view('components.projecct-list-guaranteed', [
            'projects' => $sessionGuaranteed['projects'],
            'count' => $sessionGuaranteed['total_project']
        ])->render();

        return response()->json([
            'projectHtml' => $projectHtml,
            'domaine' => $domaineIds,
            'project_count' => $sessionGuaranteed['project_count_result'],
            'projects' => $sessionGuaranteed['projects'],
            'current_page' => $sessionGuaranteed['current_page'],
            'last_page' => $sessionGuaranteed['last_page'],
            'res' => $sessionGuaranteed['res']
        ]);
    }
}
