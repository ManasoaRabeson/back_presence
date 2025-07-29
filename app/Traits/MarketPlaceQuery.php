<?php

namespace App\Traits;

use App\Models\Customer;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Request;

trait MarketPlaceQuery
{

    public function getProject($isCfp, $key, $category, $place, $cfp){
        $projects = DB::table('v_module_cfps as M')
                        ->select('M.idModule', 'M.module_image', 'M.moduleName', 'M.moduleStatut', 'M.description', 'M.minApprenant', 'M.prix', 'M.dureeH', 'M.dureeJ', 'M.prixGroupe', 'M.idCustomer', 'M.cfpName', 'M.logo as logo_cfp', 'M.nomDomaine', 'M.idDomaine', 'M.module_level_name as level_name'
                        )
                        ->where('M.moduleStatut', 1)
                        ->where('M.moduleName', '!=', 'Default module');

        if (isset($isCfp)) {
            if (!empty($key)) {
                $projects->where('moduleName', 'like', "%$key%")->where('idCustomer', $cfp);
            }

            if ($category !== 'all') {
                $projects->where('idDomaine', $category)->where('idCustomer', $cfp);
            }

            if ($place !== 'all') {
                $projects->where('idVille', $place)->where('idCustomer', $cfp);
            }
        }
        else{
            if (!empty($key)) {
                $projects->where('moduleName', 'like', "%$key%");
            }

            if ($category !== 'all') {
                $projects->where('idDomaine', $category);
            }

            if ($place !== 'all') {
                $projects->join('projets as P', 'P.idModule', '=', 'M.idModule')
                    ->join('ville_codeds', 'ville_codeds.id', 'P.idVilleCoded')
                    ->join('villes as V', 'V.idVille', '=', 'ville_codeds.idVille')
                    ->where('ville_codeds.id', $place)
                    ->where('P.dateDebut', '>', Carbon::now());
            }
        }

        if ($place !== 'all') {
            $resultQuery = $projects->orderBy('M.moduleName')->groupBy('M.idModule');
        } else {
            $resultQuery = $projects->orderBy('moduleName')->groupBy('idModule');
        }

        return $resultQuery;
    }

    public function getDomaine($isCfp, $key, $category, $place, $cfp){
        $domaines = DB::table('v_module_cfps as M')
            ->select('M.idDomaine', 'M.nomDomaine', DB::raw('COUNT(M.idModule) as nb_module'))
            ->whereNot('M.moduleName', 'Default module')
            ->where('M.moduleStatut', 1);

            if (isset($isCfp)) {
                if (!empty($key)) {
                    $domaines->where('moduleName', 'like', "%$key%")->where('idCustomer', $cfp);
                }
    
                if ($category !== 'all') {
                    $domaines->where('idDomaine', $category)->where('idCustomer', $cfp);
                }
    
                if ($place !== 'all') {
                    $domaines->where('idVille', $place)->where('idCustomer', $cfp);
                }
            } else {
                if (!empty($key)) {
                    $domaines->where('moduleName', 'like', "%$key%");
                }
    
                if ($category !== 'all') {
                    $domaines->where('idDomaine', $category);
                }
    
                if ($place !== 'all') {
                    $domaines->join('projets as P', 'P.idModule', '=', 'M.idModule')
                        ->join('ville_codeds', 'ville_codeds.id', 'P.idVilleCoded')
                        ->join('villes as V', 'V.idVille', '=', 'ville_codeds.idVille')
                        ->where('ville_codeds.id', $place)
                        ->where('P.dateDebut', '>', Carbon::now());
                }
            }
        return $domaines->orderBy('nomDomaine')->groupBy('idDomaine')->get();
    }

    public function getCfp($isCfp, $key, $category, $place, $cfp){
        $cfps = DB::table('v_module_cfps as M')
        ->select('M.cfpName', 'M.idCustomer', DB::raw('COUNT(M.idModule) as nb_module'))
        ->whereNot('M.moduleName', 'Default module')
        ->where('M.moduleStatut', 1);

        if (isset($isCfp)) {
            if (!empty($key)) {
                $cfps->where('moduleName', 'like', "%$key%")->where('idCustomer', $cfp);
            }

            if ($category !== 'all') {
                $cfps->where('idDomaine', $category)->where('idCustomer', $cfp);
            }

            if ($place !== 'all') {
                $cfps->where('idVille', $place)->where('idCustomer', $cfp);
            }
        } else {
            if (!empty($key)) {
                $cfps->where('moduleName', 'like', "%$key%");
            }

            if ($category !== 'all') {
                $cfps->where('idDomaine', $category);
            }

            if ($place !== 'all') {
                $cfps->join('projets as P', 'P.idModule', '=', 'M.idModule')
                    ->join('ville_codeds', 'ville_codeds.id', 'P.idVilleCoded')
                    ->join('villes as V', 'V.idVille', '=', 'ville_codeds.idVille')
                    ->where('ville_codeds.id', $place)
                    ->where('P.dateDebut', '>', Carbon::now());
            }
        }

        return $cfps->orderBy('cfpName')->groupBy('idCustomer')->get();
    }

    public function getVille($isCfp, $key, $category, $place, $cfp){
        $villes = DB::table('mdls as M')
        ->join('projets as P', 'P.idModule', '=', 'M.idModule')
        ->join('ville_codeds as V', 'V.id', 'P.idVilleCoded')
        ->select('V.id', 'V.ville_name', 'V.vi_code_postal', DB::raw('COUNT(M.idModule) as nb_module'));

        if (isset($isCfp)) {
            if (!empty($key)) {
                $villes->where('moduleName', 'like', "%$key%")->where('M.idCustomer', $cfp);
            }

            if ($category !== 'all') {
                $villes->where('idDomaine', $category)->where('idCustomer', $cfp);
            }

            if ($place !== 'all') {
                $villes->where('id', $place)->where('idCustomer', $cfp);
            }
        } else {
            if (!empty($key)) {
                $villes->where('moduleName', 'like', "%$key%");
            }

            if ($category !== 'all') {
                $villes->where('idDomaine', $category);
            }

            if ($place !== 'all') {
                $villes->where('V.id', $place);
            }
        }

        $villes = $villes->where('P.project_is_active', 1)
            ->where('P.dateDebut', '>', Carbon::now())
            ->where('V.ville_name', '!=', 'Default')
            ->where('P.idTypeProjet', 2)
            ->orderBy('ville_name')->groupBy('V.id')
            ->get();

        return $villes;
    }

    public function getLevel($isCfp, $key, $category, $place, $cfp){
        $levels = DB::table('v_module_cfps as M')
        ->select('M.module_level_name as level_name', 'M.idLevel', DB::raw('COUNT(M.idModule) as nb_module'))
        ->whereNot('M.moduleName', 'Default module')
        ->where('M.moduleStatut', 1);

        if (isset($isCfp)) {
            if (!empty($key)) {
                $levels->where('moduleName', 'like', "%$key%")->where('idCustomer', $cfp);
            }

            if ($category !== 'all') {
                $levels->where('idDomaine', $category)->where('idCustomer', $cfp);
            }

            if ($place !== 'all') {
                $levels->where('idVille', $place)->where('idCustomer', $cfp);
            }
        } else {
            if (!empty($key)) {
                $levels = $levels->where('moduleName', 'like', "%$key%");
            }

            if ($category !== 'all') {
                $levels->where('idDomaine', $category);
            }

            if ($place !== 'all') {
                $levels->join('projets as P', 'P.idModule', '=', 'M.idModule')
                    ->join('ville_codeds', 'ville_codeds.id', 'P.idVilleCoded')
                    ->join('villes as V', 'V.idVille', '=', 'ville_codeds.idVille')
                    ->where('ville_codeds.id', $place)
                    ->where('P.dateDebut', '>', Carbon::now());
            }
        }

        return $levels->groupBy('idLevel')->get();
    }

    public function getSessionGuaranteed($key){
        $sessions = DB::table('v_projet_cfps_inters')->select('idProjet')->where('module_name', 'like', "%$key%")->where('project_status', 'Planifié')->count();

        return $sessions;
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

    public function getCourseFiltered($domaineIds, $cfpIds, $villeIds, $duringIds, $levelIds, $key){

        $projectsQuery = DB::table('v_module_cfp_with_ville')
            ->select(
                'idVille',
                'ville',
                'idModule',
                'moduleName',
                'prix',
                'cfp_name as cfpName',
                'dureeJ',
                'dureeH',
                'idDomaine',
                'module_image',
                'logo_cfp',
                'idCustomer',
                'description',
                'level_name'
            )->where('moduleName', 'like', "%$key%");

        if (isset($duringIds)) {
            $projectsQuery->whereIn('during', $duringIds);
        }

        if (isset($domaineIds)) {
            $projectsQuery->whereIn('idDomaine', $domaineIds);
        }

        if (isset($villeIds)) {
            $projectsQuery->whereIn('idVille', $villeIds)->where('ville', '!=', 'Default')->where('project_is_active', 1)->where('idTypeProjet', 2)->where('dateDebut', '>', Carbon::now());
        }

        if (isset($cfpIds)) {
            $projectsQuery->whereIn('idCustomer', $cfpIds);
        }

        if (isset($levelIds)) {
            $projectsQuery->whereIn('idLevel', $levelIds);
        }

        $project_count = count($projectsQuery->orderBy('moduleName')->groupBy('idModule')->get());
        $resultsQuery = $projectsQuery->orderBy('moduleName')->groupBy('idModule');
        $results = $resultsQuery->paginate(21);

        $projects = [];
        foreach ($results as $p) {
            $projects[] = [
                'project' => $p,
                'note' => $this->getEval($p->idModule)
            ];
        }

        return [
            'projects' => $projects,
            'project_count' => $project_count,
            'domaine' => $domaineIds,
            'project_count' => $project_count,
            'current_page' => $results->currentPage(),
            'last_page' => $results->lastPage()
            
        ];
    }

    public function getAllSessionGuaranteed($key){
        $projectQuery = DB::table('v_projet_cfps_inters')->select('idProjet', 'idCustomer', 'module_name', 'idModule', 'logo_cfp', 'dateDebut', 'dateFin', 'ville_name as ville', 'module_description', 'module_image', 'prix', 'dureeH', 'dureeJ', 'cfp_name', 'level_name')
            ->where('project_status', 'Planifié');

        $domainesQuery = DB::table('v_projet_cfps_inters')
            ->select('idDomaine', 'domaine_name as nomDomaine', DB::raw('COUNT(idProjet) as nb_module'))
            ->whereNot('module_name', 'Default module')
            ->where('moduleStatut', 1)
            ->where('project_status', 'Planifié');

        $cfpQuery = DB::table('v_projet_cfps_inters')
            ->select('cfp_name as cfpName', 'idCustomer', DB::raw('COUNT(idProjet) as nb_module'))
            ->whereNot('module_name', 'Default module')
            ->where('moduleStatut', 1)
            ->where('project_status', 'Planifié');

        $villeQuery = DB::table('v_projet_cfps_inters')
            ->select('idVille as id', 'ville_name', 'vi_code_postal',  DB::raw('COUNT(idProjet) as nb_module'))
            ->whereNot('module_name', 'Default module')
            ->where('moduleStatut', 1)
            ->whereNot('ville', 'Default')
            ->where('project_status', 'Planifié');

        $levelQuery = DB::table('v_projet_cfps_inters')
            ->select('idLevel', 'level_name', DB::raw('COUNT(idProjet) as nb_module'))
            ->whereNot('module_name', 'Default module')
            ->where('moduleStatut', 1)
            ->where('project_status', 'Planifié');

        if (isset($key)) {
            $projectQuery->where('module_name', 'like', "%$key%");
            $domainesQuery->where('module_name', 'like', "%$key%");
            $cfpQuery->where('module_name', 'like', "%$key%");
            $villeQuery->where('module_name', 'like', "%$key%");
            $levelQuery->where('module_name', 'like', "%$key%");
        }

        $domaines = $domainesQuery->groupBy('idDomaine')->get();
        $cfps = $cfpQuery->groupBy('idCustomer')->get();
        $villes = $villeQuery->groupBy('idVille')->get();
        $levels = $levelQuery->groupBy('idLevel')->get();

        $project_count = count($projectQuery->orderBy('dateDebut')->get());
        $resultProjects = $projectQuery->orderBy('dateDebut')->paginate(10);

        $projects = [];
        foreach ($resultProjects as $projec) {
            $dateDebut = Carbon::parse($projec->dateDebut);
            Carbon::setLocale('fr');
            $projects[] = [
                'idProjet' => $projec->idProjet,
                'idModule' => $projec->idModule,
                'module_name' => $projec->module_name,
                'idCustomer' => $projec->idCustomer,
                'date_debut' => $this->dateConverted($projec->dateDebut),
                'date_fin' => $this->dateConverted($projec->dateFin),
                'cfp_name' => $projec->cfp_name,
                'ville' => $projec->ville,
                'prix' => $projec->prix,
                'dureeJ' => $projec->dureeJ,
                'dureeH' => $projec->dureeH,
                'module_description' => $projec->module_description,
                'module_image' => $projec->module_image,
                'day' => $dateDebut->day,
                'logo_cfp' => $projec->logo_cfp,
                'mois' => $dateDebut->format('M Y'),
                'note' => $this->getEval($projec->idModule),
                'level_name' => $projec->level_name
            ];
        }

        return [
            'cfps' => $cfps,
            'villes' => $villes,
            'domaines' => $domaines,
            'levels' => $levels,
            'projects' => $projects,
            'project_count' => $project_count,
            'current_page' => $resultProjects->currentPage(),
            'last_page' => $resultProjects->lastPage()
        ];
    }

    public function getSessionGuaranteedFilter($domaineIds, $cfpIds, $villeIds, $duringIds, $levelIds, $key, $selectedTime, $startDate, $endDate){

        $projectsQuery = DB::table('v_projet_cfps_inters')
            ->select('idProjet', 'module_name', 'idModule', 'logo_cfp', 'cfp_name', 'dateDebut', 'dateFin', 'ville', 'module_description', 'module_image', 'during', 'prix', 'dureeH', 'dureeJ', 'level_name', 'idCustomer')
            ->where('project_status', 'Planifié')
            ->where('moduleStatut', 1)
            ->where('module_name', 'like', "%$key%");

        if (isset($selectedTime)) {
            if ($selectedTime == 'week') {
                $projectsQuery->whereBetween('dateDebut', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()]);
            }
            if ($selectedTime == 'month') {
                $projectsQuery->whereMonth('dateDebut', Carbon::now()->month);
            }
            if ($selectedTime == 'next_month') {
                $projectsQuery->whereMonth('dateDebut', Carbon::now()->month + 1);
            }
        }

        if (isset($startDate) && !isset($endStart)) {
            $projectsQuery->where('dateDebut', '>=', $startDate);
        }
        if (!isset($startDate) && isset($endDate)) {
            $projectsQuery->where('dateFin', '<=', $endDate);
        }
        if (isset($startDate) && isset($endDate)) {
            $projectsQuery->where('dateDebut', '>=', $startDate)->where('dateFin', '<=', $endDate);
        }

        if (isset($duringIds)) {
            $projectsQuery->whereIn('during', $duringIds);
        }

        if (isset($domaineIds)) {
            $projectsQuery->whereIn('idDomaine', $domaineIds);
        }

        if (isset($villeIds)) {
            $projectsQuery->whereIn('idVille', $villeIds)->where('ville', '!=', 'Default');
        }

        if (isset($cfpIds)) {
            $projectsQuery->whereIn('idCustomer', $cfpIds);
        }

        if (isset($levelIds)) {
            $projectsQuery->whereIn('idLevel', $levelIds);
        }

        $project_count = count($projectsQuery->orderBy('dateDebut')->groupBy('idModule')->get());
        $results = $projectsQuery->orderBy('dateDebut')->groupBy('idModule')->paginate(10);

        $projects = [];
        foreach ($results as $projec) {
            $dateDebut = Carbon::parse($projec->dateDebut);
            Carbon::setLocale('fr');
            $projects[] = [
                'idProjet' => $projec->idProjet,
                'idModule' => $projec->idModule,
                'module_name' => $projec->module_name,
                'date_debut' => $this->dateConverted($projec->dateDebut),
                'date_fin' => $this->dateConverted($projec->dateFin),
                'ville' => $projec->ville,
                'prix' => $projec->prix,
                'dureeH' => $projec->dureeH,
                'dureeJ' => $projec->dureeJ,
                'module_description' => $projec->module_description,
                'module_image' => $projec->module_image,
                'day' => $dateDebut->day,
                'logo_cfp' => $projec->logo_cfp,
                'cfp_name' => $projec->cfp_name,
                'mois' => $dateDebut->format('M Y'),
                'note' => $this->getEval($projec->idModule),
                'level_name' => $projec->level_name,
                'idCustomer' => $projec->idCustomer
            ];
        }

        return [
            'projects' => $projects,
            'total_project' => $project_count,
            'current_page' => $results->currentPage(),
            'last_page' => $results->lastPage(),
            'project_count_result' => $results->total(),
            'res' => $results
        ];
    }

    public function dateConverted($date)
    {
        Carbon::setLocale('fr');
        $dateSeance = \Carbon\Carbon::parse($date);
        return  $dateSeance->translatedFormat('d M Y');
    }

    public function getModuleDomaine($idDomaine)
    {
        return DB::table('v_module_cfps')->select('idDomaine')->where('idDomaine', $idDomaine)->where('moduleStatut', 1)->count();
    }
}
