<?php

namespace App\Http\Controllers;


use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Exports\FinanceExport;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\FormationExcelExport;
use Illuminate\Support\Facades\Validator;
use App\Traits\ProjectQuery;
use App\Traits\StudentQuery;

class ReportingControllerEtp extends Controller
{

    //Formation

    public function formation(Request $request)
    {
        $idEtp = Auth::user()->id;
        $createdCfp = Auth::user()->created_at->format('m-d-Y');
        $all_learner = DB::table('v_apprenant_information')
            ->select('emp_matricule', 'module_name', 'emp_name', 'emp_firstname', 'emp_fonction', 'salle_name', 'salle_quartier', 'project_status', 'project_type', 'etp_name', 'cfp_name', 'dateDebut', 'dateFin', 'dureeH')
            ->where('idEtp', $idEtp)
            ->get();
        $all_etp_formation = DB::table('v_apprenant_information')
            ->select('idModule', 'module_name')
            ->where('idEtp', $idEtp)
            ->whereNotNull('module_name')
            ->distinct()
            ->get();

        $data_filter = ['Tous les dates', 'Tous les formation'];
        $latestDate = DB::table('v_apprenant_information')->max('dateDebut');
        $earliestDate = DB::table('v_apprenant_information')->min('dateDebut');
        if (!is_null($earliestDate)) {
            $formatedEarliestDate = Carbon::createFromFormat('Y-m-d', $earliestDate)->format('m-d-Y');
        } else {
            $formatedEarliestDate = 'Date non disponible';
        }

        if (!is_null($latestDate)) {
            $formatedLatestDate = Carbon::createFromFormat('Y-m-d', $latestDate)->format('m-d-Y');
        } else {
            $formatedLatestDate = 'Date non disponible';
        }

        $request->session()->put('data', $all_learner);
        $request->session()->put('data_filter', $data_filter);

        // dd($earliestDate, $latestDate);
        return view('ETP.reportings.formation.formation', compact(['all_learner', 'all_etp_formation', 'data_filter', 'formatedEarliestDate', 'formatedLatestDate']));
    }
    public function filterFormation(Request $request)
    {
        $validate = Validator::make($request->all(), [
            'daterange' => 'required',
            'formation' => 'required'
        ]);
        $idEtp = Auth::user()->id;
        $createdCfp = Auth::user()->created_at->format('m-d-Y');

        $returnDate = explode(" - ", $request->daterange);
        $date1 = Carbon::createFromFormat('m/d/Y', $returnDate[0])->format('Y-m-d');
        $date2 = Carbon::createFromFormat('m/d/Y', $returnDate[1])->format('Y-m-d');

        $query = DB::table('v_apprenant_information')
            ->select('emp_matricule', 'module_name', 'emp_name', 'emp_firstname', 'emp_fonction', 'salle_name', 'salle_quartier', 'project_status', 'project_type', 'etp_name', 'cfp_name', 'dateDebut', 'dateFin', 'dureeH')
            ->where('idEtp', $idEtp)
            ->where('dateDebut', '>=', $date1)
            ->where('dateDebut', '<=', $date2);
        if ($request->formation !== 'all') {
            $query->where('idModule', $request->formation);
        }
        $all_learner = $query->get();

        if ($request->formation !== 'all') {
            $queryModules = DB::table('mdls')->select('moduleName')->where('idModule', $request->formation)->first();
            $moduleName = $queryModules->moduleName;
        } else {
            $moduleName = 'Tous les formation';
        }
        $data_filter = [$request->daterange, $moduleName];

        $latestDate = DB::table('v_apprenant_information')->max('dateDebut');
        $earliestDate = DB::table('v_apprenant_information')->min('dateDebut');
        if (!is_null($earliestDate)) {
            $formatedEarliestDate = Carbon::createFromFormat('Y-m-d', $earliestDate)->format('m-d-Y');
        } else {
            $formatedEarliestDate = 'Date non disponible';
        }

        if (!is_null($latestDate)) {
            $formatedLatestDate = Carbon::createFromFormat('Y-m-d', $latestDate)->format('m-d-Y');
        } else {
            $formatedLatestDate = 'Date non disponible';
        }

        $all_etp_formation = DB::table('v_apprenant_information')
            ->select('idModule', 'module_name')
            ->where('idEtp', $idEtp)
            ->whereNotNull('module_name')
            ->distinct()
            ->get();

        $request->session()->put('data', $all_learner);
        $request->session()->put('data_filter', $data_filter);

        return view('ETP.reportings.formation.result', compact(['all_learner', 'all_etp_formation', 'formatedEarliestDate', 'formatedLatestDate', 'data_filter']));
    }
    public function exportFinanceXl()
    {
        return Excel::download(new FinanceExport, 'Finance.xlsx');
    }
    public function exportXl(Request $request)
    {
        return Excel::download(new FormationExcelExport($request->session()->get('data')), 'FormationETP.xlsx');
    }
    public function exportPdf()
    {

        $all_learner = session()->get('data');
        $data_filter = session()->get('data_filter');
        $pdf = PDF::loadView('ETP.reportings.dataForm', compact(['all_learner', 'data_filter']))->setPaper('a4', 'landscape')->setOption(['defaultFont' => 'Helvetica']);
        return $pdf->download('reportingformationETP.pdf');
    }

    // Apprenant
    public function apprenantEtp(Request $request)
    {
        $idEtp = Auth::user()->id;
        $createdCfp = Auth::user()->created_at->format('m-d-Y');
        $all_learner = DB::table('v_apprenant_information')
            ->select('emp_matricule', 'module_name', 'emp_name', 'emp_firstname', 'emp_fonction', 'salle_name', 'salle_quartier', 'project_status', 'project_type', 'etp_name', 'cfp_name', 'dateDebut', 'dateFin', 'dureeH', 'taux_de_presence')
            ->where('idEtp', $idEtp)
            ->get();
        $all_etp_formation = DB::table('v_apprenant_information')
            ->select('idModule', 'module_name')
            ->where('idEtp', $idEtp)
            ->whereNotNull('module_name')
            ->distinct()
            ->get();

        $data_filter = ['Tous les dates', 'Tous les formation'];
        $latestDate = DB::table('v_apprenant_information')->max('dateDebut');
        $earliestDate = DB::table('v_apprenant_information')->min('dateDebut');
        if (!is_null($earliestDate)) {
            $formatedEarliestDate = Carbon::createFromFormat('Y-m-d', $earliestDate)->format('m-d-Y');
        } else {
            $formatedEarliestDate = 'Date non disponible';
        }

        if (!is_null($latestDate)) {
            $formatedLatestDate = Carbon::createFromFormat('Y-m-d', $latestDate)->format('m-d-Y');
        } else {
            $formatedLatestDate = 'Date non disponible';
        }

        $request->session()->put('data', $all_learner);
        $request->session()->put('data_filter', $data_filter);

        // dd($earliestDate, $latestDate);
        return view('ETP.reportings.apprenants.index', compact(['all_learner', 'all_etp_formation', 'data_filter', 'formatedEarliestDate', 'formatedLatestDate']));
    }
    public function exportAppEtpXl(Request $request)
    {
        return Excel::download(new FormationExcelExport($request->session()->get('data')), 'FormationETP.xlsx');
    }
    public function exportAppEtpPdf()
    {

        $all_learner = session()->get('data');
        $data_filter = session()->get('data_filter');
        $pdf = PDF::loadView('ETP.reportings.dataForm', compact(['all_learner', 'data_filter']))->setPaper('a4', 'landscape')->setOption(['defaultFont' => 'Helvetica']);
        return $pdf->download('reportingformationETP.pdf');
    }


    // Centre de formation
    public function client(Request $request)
    {
        $idEtp = Auth::user()->id;
        $createdCfp = Auth::user()->created_at->format('m-d-Y');
        $all_learner = DB::table('v_apprenant_information')
            ->select('emp_matricule', 'module_name', 'emp_name', 'emp_firstname', 'emp_fonction', 'salle_name', 'salle_quartier', 'project_status', 'project_type', 'idCfp', 'id_cfp', 'cfp_name', 'dateDebut', 'dateFin', 'dureeH')
            ->where('idEtp', $idEtp)
            ->get();
        $all_cfp = DB::table('v_apprenant_information')
            ->select('idCfp', 'cfp_name', 'idModule')
            ->where('idEtp', $idEtp)
            ->whereNotNull('cfp_name')
            ->distinct()
            ->get();

        $data_filter = ['Tous les dates', 'Tous les formation'];

        $request->session()->put('data', $all_learner);
        $request->session()->put('data_filter', $data_filter);

        // dd($all_learner); 
        return view('ETP.reportings.clients.index', compact(['all_learner', 'all_cfp', 'data_filter']));
    }

    public function exportXlCl(Request $request)
    {
        return Excel::download(new FormationExcelExport($request->session()->get('data')), 'FormationETP.xlsx');
    }
    public function exportPdfCl()
    {

        $all_learner = session()->get('data');
        $data_filter = session()->get('data_filter');
        $pdf = PDF::loadView('ETP.reportings.dataForm', compact(['all_learner', 'data_filter']))->setPaper('a4', 'landscape')->setOption(['defaultFont' => 'Helvetica']);
        return $pdf->download('reportingformationETP.pdf');
    }

    // Cours 
    public function cours(Request $request)
    {
        $idEtp = Auth::user()->id;
        $createdCfp = Auth::user()->created_at->format('m-d-Y');
        $all_learner = DB::table('v_apprenant_information')
            ->select('emp_matricule', 'module_name', 'emp_name', 'emp_firstname', 'emp_fonction', 'salle_name', 'salle_quartier', 'project_status', 'project_type', 'etp_name', 'cfp_name', 'dateDebut', 'dateFin', 'dureeH')
            ->where('idEtp', $idEtp)
            ->get();
        $all_etp_formation = DB::table('v_apprenant_information')
            ->select('idModule', 'module_name')
            ->where('idEtp', $idEtp)
            ->whereNotNull('module_name')
            ->distinct()
            ->get();

        $data_filter = ['Tous les dates', 'Tous les formation'];
        $latestDate = DB::table('v_apprenant_information')->max('dateDebut');
        $earliestDate = DB::table('v_apprenant_information')->min('dateDebut');
        if (!is_null($earliestDate)) {
            $formatedEarliestDate = Carbon::createFromFormat('Y-m-d', $earliestDate)->format('m-d-Y');
        } else {
            $formatedEarliestDate = 'Date non disponible';
        }

        if (!is_null($latestDate)) {
            $formatedLatestDate = Carbon::createFromFormat('Y-m-d', $latestDate)->format('m-d-Y');
        } else {
            $formatedLatestDate = 'Date non disponible';
        }

        $request->session()->put('data', $all_learner);
        $request->session()->put('data_filter', $data_filter);

        // dd($earliestDate, $latestDate);
        return view('ETP.reportings.cours.index', compact(['all_learner', 'all_etp_formation', 'data_filter', 'formatedEarliestDate', 'formatedLatestDate']));
    }

    public function exportXlCours(Request $request)
    {
        return Excel::download(new FormationExcelExport($request->session()->get('data')), 'CoursETP.xlsx');
    }
    public function exportPdfCours()
    {

        $all_learner = session()->get('data');
        $data_filter = session()->get('data_filter');
        $pdf = PDF::loadView('ETP.reportings.dataForm', compact(['all_learner', 'data_filter']))->setPaper('a4', 'landscape')->setOption(['defaultFont' => 'Helvetica']);
        return $pdf->download('reportingCoursETP.pdf');
    }


    // Chiffre d'affaire
    use ProjectQuery;
    use StudentQuery;

    public function chiffreAEtp()
    {
        $idEtp = Auth::user()->id;
        $current_month =  date('m');
        $months = ['Jan', 'Feb', 'Mar', 'Apr', 'Mai', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
        $remain_months = array_slice([1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12], intval($current_month) - 1);

        $current_month_projects = $this->getEtpProjects($current_month, 'Terminé', $idEtp);
        $last_year_current_month_projects = $this->getEtpProjects($current_month, 'Terminé', $idEtp, date('Y') - 1);

        $total_cost = $current_month_projects->sum('total_ttc');
        $last_year_total_cost = $last_year_current_month_projects->sum('total_ttc');

        $current_year_projects = $this->getEtpProjectsByYear('Terminé', $idEtp);
        $last_year_projects = $this->getEtpProjectsByYear('Terminé', $idEtp, date('Y') - 1);

        $apprenants = $this->getStudents($current_year_projects->pluck('idProjet')->toArray());
        $last_year_apprenants = $this->getStudents($last_year_projects->pluck('idProjet'));

        $total_trained = count($apprenants);
        $last_total_trained = count($last_year_apprenants);

        $unique_trained = collect($apprenants)->unique()->count();
        $last_unique_trained = collect($last_year_apprenants)->unique()->count();


        $prepared_and_in_progress_projects = $this->getEtpProjects($remain_months, ['En cours', 'Planifié'], $idEtp);
        $total_cost_year_to_date = ($current_year_projects->sum('total_ttc'));

        if ($unique_trained != 0) {
            $cost_by_employee = $total_cost_year_to_date / $unique_trained;
        } else {
            $cost_by_employee = 0;
        }

        if ($last_unique_trained != 0) {
            $last_cost_by_employee = $last_year_total_cost / $last_unique_trained;
        } else {
            $last_cost_by_employee = 0;
        }

        $project_by_month = $this->groupProjectsByMonth($current_year_projects);
        $last_year_projects_by_month = $this->groupProjectsByMonth($last_year_projects);

        $prices = [];
        $students = [];
        foreach ($project_by_month as $month => $projects) {
            $prices[$month] = collect($projects)->sum('total_ttc');
            $students[$month] = $this->getStudents(collect($projects)->pluck('idProjet'))->count();
        }
        foreach ($last_year_projects_by_month as $month => $projects) {
            $last_year_prices[$month] = collect($projects)->sum('total_ttc');
        }
        foreach ($prepared_and_in_progress_projects as $month => $projects) {
            $forecast_prices[$month] = collect($projects)->sum('total_ttc');
        }
        for ($i = 0; $i < 12; $i++) {
            if ($i <= $current_month - 1) {
                if (!isset($prices[$i])) {
                    $prices[$i] = 0;
                }
                if (!isset($forecast_prices[$i])) {
                    $forecast_prices[$i] = 'null';
                }
            } else {
                if (!isset($forecast_prices[$i])) {
                    $forecast_prices[$i] = 0;
                }
            }
            if (!isset($students[$i])) {
                $students[$i] = 0;
            }
            if (!isset($last_year_prices[$i])) {
                $last_year_prices[$i] = 0;
            }
        }
        ksort($prices);
        ksort($students);
        ksort($forecast_prices);
        ksort($last_year_prices);

        $last_year_total_YTD = collect(array_slice($last_year_prices, (12 - intval($current_month))))->sum();

        $user = Auth::user();
        $notifications = $user->unreadNotifications;

        return view('ETP.reportings.CA.index', [
            'months' => $months,
            'project_by_month' => $project_by_month,
            'finished_data' => $prices,
            'forecast_data' => $forecast_prices,
            'total_trained' => $total_trained,
            'unique_trained' => $unique_trained,
            'total_cost' => $total_cost,
            'total_YTD' => $total_cost_year_to_date,
            'cost_by_employee' => $cost_by_employee,
            'current_year_projects' => $current_year_projects,
            'histogram_data' => $students,
            'last_total_trained' => $last_total_trained,
            'last_unique_trained' => $last_unique_trained,
            'last_total_cost' => $last_year_total_cost,
            'last_cost_by_employee' => $last_cost_by_employee,
            'last_year_YTD' => $last_year_total_YTD,
            'last_year_prices' => $last_year_prices,
            'notifications' => $notifications
        ]);
    }
}
