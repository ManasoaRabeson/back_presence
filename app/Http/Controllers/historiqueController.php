<?php

namespace App\Http\Controllers;

use App\Exports\ApprenantExcelExport;
use App\Exports\FinanceExport;
use App\Exports\FormationExcelExport;
use App\Models\Customer;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

class historiqueController extends Controller
{


    public function idCfp()
    {
        $customer = DB::select("SELECT employes.idEmploye, employes.idCustomer, customers.idTypeCustomer FROM employes INNER JOIN customers ON employes.idCustomer = customers.idCustomer WHERE idEmploye = ?", [Auth::user()->id]);
        return $customer[0]->idCustomer;
    }
    public function apprenant(Request $request)
    {
        $idCfp = Auth::user()->id;
        $createdCfp = Auth::user()->created_at->format('m-d-Y');

        // Récupération des apprenants
        $all_learner = DB::table('v_apprenant_information')
            ->select('emp_matricule', 'module_name', 'emp_name', 'emp_firstname', 'emp_fonction', 'salle_name', 'salle_quartier', 'project_status', 'project_type', 'etp_name', 'cfp_name', 'dateDebut', 'dateFin', 'dureeH', 'taux_de_presence')
            ->where('idCfp', $idCfp)
            ->get();

        // Récupération des formations
        $all_cfp_formation = DB::table('v_apprenant_information')
            ->select('idModule', 'module_name')
            ->where('idCfp', $idCfp)
            ->whereNotNull('module_name')
            ->distinct()
            ->get();
        $data_filter = ['Tous les dates',  'Tous les formation'];
        $latestDate = DB::table('v_apprenant_information')->max('dateDebut');
        $earliestDate = DB::table('v_apprenant_information')->min('dateDebut');

        if (is_Null($latestDate) || is_Null($earliestDate)) {
            $formatedEarliestDate = Carbon::today()->format('m-d-Y');
            $formatedLatestDate = Carbon::today()->format('m-d-Y');
        } else {
            $formatedEarliestDate = Carbon::createFromFormat('Y-m-d', $earliestDate)->format('m-d-Y');
            $formatedLatestDate = Carbon::createFromFormat('Y-m-d', $latestDate)->format('m-d-Y');
        }

        $request->session()->put('data', $all_learner);
        $request->session()->put('data_filter', $data_filter);

        // Retourner uniquement les apprenants et les formations à la vue
        return view('CFP.Reporting.formation.historique', compact(['all_learner', 'all_cfp_formation']));
    }

    public function getLearner(){
        $learners = DB::table('v_apprenant_etp_alls')
                    ->where(function ($query) {
                        $query->where('idCfp', Customer::idCustomer())
                            ->where('id_cfp', Customer::idCustomer())
                            ->orWhere('id_cfp_appr', Customer::idCustomer());
                    })
                    ->whereNotNull('emp_name')
                    ->orderBy('emp_name')
                    ->pluck(DB::raw('CONCAT(emp_name, " ", COALESCE(emp_firstname, ""))'), 'idEmploye');

        return response()->json($learners);
    }

    public function getProjectLearner(Request $request){
        $idProjects = DB::table('detail_apprenants as D')
                        ->join('projets as P', 'D.idProjet', '=', 'P.idProjet')
                        ->where('P.idCustomer', Customer::idCustomer())
                        ->where('D.idEmploye', $request->id)
                        ->pluck('D.idProjet');
        
        $projects = DB::table('projets AS P')
                        ->join('mdls as M', 'M.idModule', '=', 'P.idModule')
                        ->join('module_levels as L', 'L.idLevel', '=', 'M.idLevel')
                        ->join('ville_codeds','ville_codeds.id','P.idVilleCoded')
                        ->join('villes as V', 'V.idVille', '=', 'ville_codeds.idVille')
                        ->select('P.idProjet', 'M.moduleName', 'V.ville', 'P.dateDebut', 'P.dateFin', 'M.module_image', 'M.idModule', 'M.description', 'M.dureeJ', 'M.dureeH', 'L.module_level_name' )
                        ->whereIn('P.idProjet', $idProjects)->get();

        $results = [];
        foreach ($projects as $projec) {
            $dateDebut = Carbon::parse($projec->dateDebut);
            Carbon::setLocale('fr');
            $results[] = [
                'idProjet' => $projec->idProjet,
                'idModule' => $projec->idModule,
                'module_name' => $projec->moduleName,
                'date_debut' => $this->dateConverted($projec->dateDebut),
                'date_fin' => $this->dateConverted($projec->dateFin),
                'ville' => $projec->ville,
                'dureeJ' => $projec->dureeJ,
                'dureeH' => $projec->dureeH,
                'module_description' => $projec->description,
                'module_image' => $projec->module_image,
                'day' => $dateDebut->day,
                'mois' => $dateDebut->format('M Y'),
                'note' => $this->getEval($projec->idModule),
                'level_name' => $projec->module_level_name
            ];
        }

        $projectHtml = view('components.reporting-learner', [
            'projects' => $results
        ])->render();

        return response()->json([
            'results_html' => $projectHtml]);
    }

    private function getProjectByModule($idModule)
    {
        $projects = DB::table('v_projet_cfps')
            ->select('idProjet')
            ->where('idModule', $idModule)
            ->pluck('idProjet');
        return $projects;
    }

    private function getEval($idModule)
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

    private function dateConverted($date)
    {
        Carbon::setLocale('fr');
        $dateSeance = \Carbon\Carbon::parse($date);
        return  $dateSeance->translatedFormat('d M Y');
    }

    public function searchName($name)
    {
        $idCfp = Auth::user()->id;

        $apprenants = DB::table('v_apprenant_information')
            ->where('idCfp', $idCfp)
            ->where(function ($query) use ($name) {
                $query->where('emp_name', 'LIKE', '%' . $name . '%')
                    ->orWhere('emp_firstname', 'LIKE', '%' . $name . '%');
            })
            ->get();

        return response()->json(['apprenants' => $apprenants]);
    }

    // Export Apprenant List
    public function exportFinanceXl()
    {
        return Excel::download(new FinanceExport, 'Finance.xlsx');
    }
    public function exportXlApp(Request $request)
    {
        return Excel::download(new ApprenantExcelExport($request->session()->get('data')), 'Apprenant.xlsx');
    }
    public function exportPdfApp()
    {
        $all_learner = session()->get('data');
        $data_filter = session()->get('data_filter');
        $pdf = PDF::loadView('CFP.Reporting.formation.dataAppExport', compact(['all_learner', 'data_filter']))->setPaper('a4', 'landscape')->setOption(['defaultFont' => 'Helvetica']);
        return $pdf->download('reportingformation.pdf');
    }
}
