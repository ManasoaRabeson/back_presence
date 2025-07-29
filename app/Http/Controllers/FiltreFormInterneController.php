<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class FiltreFormInterneController extends Controller
{
    //
    // Filtres
    public function getDropdownItem()
    {

        $userId = Auth::user()->id;

        $status = DB::table('v_projet_internes')
            ->select('project_status', DB::raw('COUNT(idProjet) AS projet_nb'))
            ->where('idFormateur', $userId)
            ->where('headYear', Carbon::now()->format('Y'))
            ->groupBy('project_status')
            ->orderBy('project_status', 'asc')
            ->get();
        //dd($status);
        $etps = DB::table('v_projet_internes')
            ->select('idEtp', 'etp_name', DB::raw('COUNT(idProjet) AS projet_nb'))
            ->where('idFormateur', $userId)
            ->where('headYear', Carbon::now()->format('Y'))
            ->where('etp_name', '!=', 'null')
            ->groupBy('idEtp', 'etp_name')
            ->orderBy('etp_name', 'asc')
            ->get();

        $types = DB::table('v_projet_internes')
            ->select('project_type', DB::raw('COUNT(idProjet) AS projet_nb'))
            ->where('idFormateur', $userId)
            ->where('headYear', Carbon::now()->format('Y'))
            ->orderBy('project_type', 'asc')
            ->groupBy('project_type')
            ->get();

        $periodePrev3 = DB::table('v_projet_internes')
            ->select('idProjet', 'p_id_periode', DB::raw('COUNT(idProjet) AS projet_nb'))
            ->where('idFormateur', $userId)
            ->where('headYear', Carbon::now()->format('Y'))
            // ->where('p_id_periode', "prev_3_month")
            ->whereRaw("p_id_periode COLLATE utf8mb4_unicode_ci = 'prev_3_month'")
            ->groupBy('p_id_periode')
            ->first();

        $periodePrev6 = DB::table('v_projet_internes')
            ->select(DB::raw('"prev_6_month" AS p_id_periode'), DB::raw('COUNT(idProjet) AS projet_nb'))
            ->where('idFormateur', $userId)
            ->where('headYear', Carbon::now()->format('Y'))
            ->whereIn('p_id_periode', ["prev_3_month", "prev_6_month"])
            ->first();

        $periodePrev12 = DB::table('v_projet_internes')
            ->select(DB::raw('"prev_12_month" AS p_id_periode'), DB::raw('COUNT(idProjet) AS projet_nb'))
            ->where('idFormateur', $userId)
            ->where('headYear', Carbon::now()->format('Y'))
            ->whereIn('p_id_periode', ["prev_3_month", "prev_6_month", "prev_12_month"])
            ->first();

        $periodeNext3 = DB::table('v_projet_internes')
            ->select('p_id_periode', DB::raw('COUNT(idProjet) AS projet_nb'))
            ->where('idFormateur', $userId)
            ->where('headYear', Carbon::now()->format('Y'))
            ->where('p_id_periode', "next_3_month")
            ->groupBy('p_id_periode')
            ->first();

        $periodeNext6 = DB::table('v_projet_internes')
            ->select(DB::raw('"next_6_month" AS p_id_periode'), DB::raw('COUNT(idProjet) AS projet_nb'))
            ->where('idFormateur', $userId)
            ->where('headYear', Carbon::now()->format('Y'))
            ->whereIn('p_id_periode', ["next_3_month", "next_6_month"])
            ->first();

        $periodeNext12 = DB::table('v_projet_internes')
            ->select(DB::raw('"next_12_month" AS p_id_periode'), DB::raw('COUNT(idProjet) AS projet_nb'))
            ->where('idFormateur', $userId)
            ->where('headYear', Carbon::now()->format('Y'))
            ->whereIn('p_id_periode', ["next_3_month", "next_6_month", "next_12_month"])
            ->first();

        $modules = DB::table('v_projet_internes')
            ->select('idModule', 'module_name', DB::raw('COUNT(idProjet) AS projet_nb'))
            ->where('idFormateur', $userId)
            ->where('headYear', Carbon::now()->format('Y'))
            ->where('module_name', '!=', 'Default module')
            ->orderBy('module_name', 'asc')
            ->groupBy('idModule', 'module_name')
            ->get();

        $villes = DB::table('v_projet_internes')
            ->select('idVille', 'ville', DB::raw('COUNT(idProjet) AS projet_nb'))
            ->where('idFormateur', $userId)
            ->where('headYear', Carbon::now()->format('Y'))
            ->orderBy('ville', 'asc')
            ->groupBy('idVille', 'ville')
            ->get();

        // $financements = DB::table('v_projet_internes')
        //     ->select('idPaiement', 'paiement', DB::raw('COUNT(idProjet) AS projet_nb'))
        //     ->where('idFormateur', $userId)
        //     ->where('headYear', Carbon::now()->format('Y'))
        //     ->orderBy('paiement', 'asc')
        //     ->groupBy('idPaiement', 'paiement')
        //     ->get();

        return response()->json([
            'status' => $status,
            'etps' => $etps,
            'types' => $types,
            'periodePrev3' => $periodePrev3,
            'periodePrev6' => $periodePrev6,
            'periodePrev12' => $periodePrev12,
            'periodeNext3' => $periodeNext3,
            'periodeNext6' => $periodeNext6,
            'periodeNext12' => $periodeNext12,
            'modules' => $modules,
            'villes' => $villes,
            // 'financements' => $financements
        ]);
    }

    //
    public function filterItems(Request $req)
    {
        $idStatus = explode(',', $req->idStatut);
        $idEtps = explode(',', $req->idEtp);
        $idTypes = explode(',', $req->idType);
        $idPeriodes = $req->idPeriode;
        $idModules = explode(',', $req->idModule);
        $idVilles = explode(',', $req->idVille);
        // $idFinancements = explode(',', $req->idFinancement);


        $userId = Auth::user()->id;

        $query = DB::table('v_projet_internes')
            ->select('idProjet', 'dateDebut', 'dateFin', 'module_name', 'etp_name', 'ville', 'project_status', 'project_type', 'headDate', 'headMonthDebut', 'headMonthFin', 'headYear', 'headDayDebut', 'headDayFin', 'module_image', 'etp_logo', 'etp_initial_name', 'salle_name', 'salle_quartier', 'salle_code_postal', 'ville', 'idCfp_inter', 'modalite')
            ->where('idFormateur', $userId)
            ->where('headYear', Carbon::now()->format('Y'));

        if ($idStatus[0] != null) {
            $query->whereIn('project_status', $idStatus);

            $etps = DB::table('v_projet_internes')
                ->select('idEtp', 'etp_name', DB::raw('COUNT(idProjet) AS projet_nb'))
                ->where('idFormateur', $userId)
                ->whereIn('project_status', $idStatus)
                ->groupBy('idEtp', 'etp_name')
                ->orderBy('etp_name', 'asc')
                ->get();

            $types = DB::table('v_projet_internes')
                ->select('project_type', DB::raw('COUNT(idProjet) AS projet_nb'))
                ->where('idFormateur', $userId)
                ->whereIn('project_status', $idStatus)
                ->orderBy('project_type', 'asc')
                ->groupBy('project_type')
                ->get();

            $periodePrev3 = DB::table('v_projet_internes')
                ->select('idProjet', 'p_id_periode', DB::raw('COUNT(idProjet) AS projet_nb'))
                ->where('idFormateur', $userId)
                ->where('headYear', Carbon::now()->format('Y'))
                ->where('p_id_periode', "prev_3_month")
                ->whereIn('project_status', $idStatus)
                ->groupBy('p_id_periode')
                ->first();

            $periodePrev6 = DB::table('v_projet_internes')
                ->select(DB::raw('"prev_6_month" AS p_id_periode'), DB::raw('COUNT(idProjet) AS projet_nb'))
                ->where('idFormateur', $userId)
                ->where('headYear', Carbon::now()->format('Y'))
                ->whereIn('p_id_periode', ["prev_3_month", "prev_6_month"])
                ->whereIn('project_status', $idStatus)
                ->first();

            $periodePrev12 = DB::table('v_projet_internes')
                ->select(DB::raw('"prev_12_month" AS p_id_periode'), DB::raw('COUNT(idProjet) AS projet_nb'))
                ->where('idFormateur', $userId)
                ->where('headYear', Carbon::now()->format('Y'))
                ->whereIn('p_id_periode', ["prev_3_month", "prev_6_month", "prev_12_month"])
                ->whereIn('project_status', $idStatus)
                ->first();

            $periodeNext3 = DB::table('v_projet_internes')
                ->select('p_id_periode', DB::raw('COUNT(idProjet) AS projet_nb'))
                ->where('idFormateur', $userId)
                ->where('headYear', Carbon::now()->format('Y'))
                ->where('p_id_periode', "next_3_month")
                ->whereIn('project_status', $idStatus)
                ->groupBy('p_id_periode')
                ->first();

            $periodeNext6 = DB::table('v_projet_internes')
                ->select(DB::raw('"next_6_month" AS p_id_periode'), DB::raw('COUNT(idProjet) AS projet_nb'))
                ->where('idFormateur', $userId)
                ->where('headYear', Carbon::now()->format('Y'))
                ->whereIn('p_id_periode', ["next_3_month", "next_6_month"])
                ->whereIn('project_status', $idStatus)
                ->first();

            $periodeNext12 = DB::table('v_projet_internes')
                ->select(DB::raw('"next_12_month" AS p_id_periode'), DB::raw('COUNT(idProjet) AS projet_nb'))
                ->where('idFormateur', $userId)
                ->where('headYear', Carbon::now()->format('Y'))
                ->whereIn('p_id_periode', ["next_3_month", "next_6_month", "next_12_month"])
                ->whereIn('project_status', $idStatus)
                ->first();

            $modules = DB::table('v_projet_internes')
                ->select('idModule', 'module_name', DB::raw('COUNT(idProjet) AS projet_nb'))
                ->where('idFormateur', $userId)
                ->whereIn('project_status', $idStatus)
                ->orderBy('module_name', 'asc')
                ->groupBy('idModule', 'module_name')
                ->get();

            $villes = DB::table('v_projet_internes')
                ->select('idVille', 'ville', DB::raw('COUNT(idProjet) AS projet_nb'))
                ->where('idFormateur', $userId)
                ->whereIn('project_status', $idStatus)
                ->orderBy('ville', 'asc')
                ->groupBy('idVille', 'ville')
                ->get();

            // $financements = DB::table('v_projet_internes')
            //     ->select('idPaiement', 'paiement', DB::raw('COUNT(idProjet) AS projet_nb'))
            //     ->where('idFormateur', $userId)
            //     ->whereIn('project_status', $idStatus)
            //     ->orderBy('paiement', 'asc')
            //     ->groupBy('idPaiement', 'paiement')
            //     ->get();

            $projectDates = DB::table('v_projet_internes')
                ->select('headDate', 'headMonthDebut')
                ->groupBy('headDate')
                ->orderBy('dateDebut', 'asc')
                ->where('idFormateur', $userId)
                ->where('headYear', Carbon::now()->format('Y'))
                ->whereIn('project_status', $idStatus)
                ->get();
        }

        if ($idEtps[0] != null) {
            $query->whereIn('idEtp', $idEtps);

            $status = DB::table('v_projet_internes')
                ->select('project_status', DB::raw('COUNT(idProjet) AS projet_nb'))
                ->where('idFormateur', $userId)
                ->whereIn('idEtp', $idEtps)
                ->groupBy('project_status')
                ->orderBy('project_status', 'asc')
                ->get();

            $types = DB::table('v_projet_internes')
                ->select('project_type', DB::raw('COUNT(idProjet) AS projet_nb'))
                ->where('idFormateur', $userId)
                ->whereIn('idEtp', $idEtps)
                ->orderBy('project_type', 'asc')
                ->groupBy('project_type')
                ->get();

            $periodePrev3 = DB::table('v_projet_internes')
                ->select('idProjet', 'p_id_periode', DB::raw('COUNT(idProjet) AS projet_nb'))
                ->where('idFormateur', $userId)
                ->where('headYear', Carbon::now()->format('Y'))
                ->where('p_id_periode', "prev_3_month")
                ->whereIn('idEtp', $idEtps)
                ->groupBy('p_id_periode')
                ->first();

            $periodePrev6 = DB::table('v_projet_internes')
                ->select(DB::raw('"prev_6_month" AS p_id_periode'), DB::raw('COUNT(idProjet) AS projet_nb'))
                ->where('idFormateur', $userId)
                ->where('headYear', Carbon::now()->format('Y'))
                ->whereIn('p_id_periode', ["prev_3_month", "prev_6_month"])
                ->whereIn('idEtp', $idEtps)
                ->first();

            $periodePrev12 = DB::table('v_projet_internes')
                ->select(DB::raw('"prev_12_month" AS p_id_periode'), DB::raw('COUNT(idProjet) AS projet_nb'))
                ->where('idFormateur', $userId)
                ->where('headYear', Carbon::now()->format('Y'))
                ->whereIn('p_id_periode', ["prev_3_month", "prev_6_month", "prev_12_month"])
                ->whereIn('idEtp', $idEtps)
                ->first();

            $periodeNext3 = DB::table('v_projet_internes')
                ->select('p_id_periode', DB::raw('COUNT(idProjet) AS projet_nb'))
                ->where('idFormateur', $userId)
                ->where('headYear', Carbon::now()->format('Y'))
                ->where('p_id_periode', "next_3_month")
                ->whereIn('idEtp', $idEtps)
                ->groupBy('p_id_periode')
                ->first();

            $periodeNext6 = DB::table('v_projet_internes')
                ->select(DB::raw('"next_6_month" AS p_id_periode'), DB::raw('COUNT(idProjet) AS projet_nb'))
                ->where('idFormateur', $userId)
                ->where('headYear', Carbon::now()->format('Y'))
                ->whereIn('p_id_periode', ["next_3_month", "next_6_month"])
                ->whereIn('idEtp', $idEtps)
                ->first();

            $periodeNext12 = DB::table('v_projet_internes')
                ->select(DB::raw('"next_12_month" AS p_id_periode'), DB::raw('COUNT(idProjet) AS projet_nb'))
                ->where('idFormateur', $userId)
                ->where('headYear', Carbon::now()->format('Y'))
                ->whereIn('p_id_periode', ["next_3_month", "next_6_month", "next_12_month"])
                ->whereIn('idEtp', $idEtps)
                ->first();

            $modules = DB::table('v_projet_internes')
                ->select('idModule', 'module_name', DB::raw('COUNT(idProjet) AS projet_nb'))
                ->where('idFormateur', $userId)
                ->whereIn('idEtp', $idEtps)
                ->orderBy('module_name', 'asc')
                ->groupBy('idModule', 'module_name')
                ->get();

            $villes = DB::table('v_projet_internes')
                ->select('idVille', 'ville', DB::raw('COUNT(idProjet) AS projet_nb'))
                ->where('idFormateur', $userId)
                ->whereIn('idEtp', $idEtps)
                ->orderBy('ville', 'asc')
                ->groupBy('idVille', 'ville')
                ->get();

            // $financements = DB::table('v_projet_internes')
            //     ->select('idPaiement', 'paiement', DB::raw('COUNT(idProjet) AS projet_nb'))
            //     ->where('idFormateur', $userId)
            //     ->whereIn('idEtp', $idEtps)
            //     ->orderBy('paiement', 'asc')
            //     ->groupBy('idPaiement', 'paiement')
            //     ->get();

            $projectDates = DB::table('v_projet_internes')
                ->select('headDate', 'headMonthDebut')
                ->groupBy('headDate')
                ->orderBy('dateDebut', 'asc')
                ->where('idFormateur', $userId)
                ->where('headYear', Carbon::now()->format('Y'))
                ->whereIn('idEtp', $idEtps)
                ->get();
        }

        if ($idTypes[0] != null) {
            $query->whereIn('project_type', $idTypes);

            $status = DB::table('v_projet_internes')
                ->select('project_status', DB::raw('COUNT(idProjet) AS projet_nb'))
                ->where('idFormateur', $userId)
                ->whereIn('project_type', $idTypes)
                ->groupBy('project_status')
                ->orderBy('project_status', 'asc')
                ->get();

            $etps = DB::table('v_projet_internes')
                ->select('idEtp', 'etp_name', DB::raw('COUNT(idProjet) AS projet_nb'))
                ->where('idFormateur', $userId)
                ->whereIn('project_type', $idTypes)
                ->groupBy('idEtp', 'etp_name')
                ->orderBy('etp_name', 'asc')
                ->get();

            $periodePrev3 = DB::table('v_projet_internes')
                ->select('idProjet', 'p_id_periode', DB::raw('COUNT(idProjet) AS projet_nb'))
                ->where('idFormateur', $userId)
                ->where('headYear', Carbon::now()->format('Y'))
                ->where('p_id_periode', "prev_3_month")
                ->whereIn('project_type', $idTypes)
                ->groupBy('p_id_periode')
                ->first();

            $periodePrev6 = DB::table('v_projet_internes')
                ->select(DB::raw('"prev_6_month" AS p_id_periode'), DB::raw('COUNT(idProjet) AS projet_nb'))
                ->where('idFormateur', $userId)
                ->where('headYear', Carbon::now()->format('Y'))
                ->whereIn('p_id_periode', ["prev_3_month", "prev_6_month"])
                ->whereIn('project_type', $idTypes)
                ->first();

            $periodePrev12 = DB::table('v_projet_internes')
                ->select(DB::raw('"prev_12_month" AS p_id_periode'), DB::raw('COUNT(idProjet) AS projet_nb'))
                ->where('idFormateur', $userId)
                ->where('headYear', Carbon::now()->format('Y'))
                ->whereIn('p_id_periode', ["prev_3_month", "prev_6_month", "prev_12_month"])
                ->whereIn('project_type', $idTypes)
                ->first();

            $periodeNext3 = DB::table('v_projet_internes')
                ->select('p_id_periode', DB::raw('COUNT(idProjet) AS projet_nb'))
                ->where('idFormateur', $userId)
                ->where('headYear', Carbon::now()->format('Y'))
                ->where('p_id_periode', "next_3_month")
                ->whereIn('project_type', $idTypes)
                ->groupBy('p_id_periode')
                ->first();

            $periodeNext6 = DB::table('v_projet_internes')
                ->select(DB::raw('"next_6_month" AS p_id_periode'), DB::raw('COUNT(idProjet) AS projet_nb'))
                ->where('idFormateur', $userId)
                ->where('headYear', Carbon::now()->format('Y'))
                ->whereIn('p_id_periode', ["next_3_month", "next_6_month"])
                ->whereIn('project_type', $idTypes)
                ->first();

            $periodeNext12 = DB::table('v_projet_internes')
                ->select(DB::raw('"next_12_month" AS p_id_periode'), DB::raw('COUNT(idProjet) AS projet_nb'))
                ->where('idFormateur', $userId)
                ->where('headYear', Carbon::now()->format('Y'))
                ->whereIn('p_id_periode', ["next_3_month", "next_6_month", "next_12_month"])
                ->whereIn('project_type', $idTypes)
                ->first();

            $modules = DB::table('v_projet_internes')
                ->select('idModule', 'module_name', DB::raw('COUNT(idProjet) AS projet_nb'))
                ->where('idFormateur', $userId)
                ->whereIn('project_type', $idTypes)
                ->orderBy('module_name', 'asc')
                ->groupBy('idModule', 'module_name')
                ->get();

            $villes = DB::table('v_projet_internes')
                ->select('idVille', 'ville', DB::raw('COUNT(idProjet) AS projet_nb'))
                ->where('idFormateur', $userId)
                ->whereIn('project_type', $idTypes)
                ->orderBy('ville', 'asc')
                ->groupBy('idVille', 'ville')
                ->get();

            // $financements = DB::table('v_projet_internes')
            //     ->select('idPaiement', 'paiement', DB::raw('COUNT(idProjet) AS projet_nb'))
            //     ->where('idFormateur', $userId)
            //     ->whereIn('project_type', $idTypes)
            //     ->orderBy('paiement', 'asc')
            //     ->groupBy('idPaiement', 'paiement')
            //     ->get();

            $projectDates = DB::table('v_projet_internes')
                ->select('headDate', 'headMonthDebut')
                ->groupBy('headDate')
                ->orderBy('dateDebut', 'asc')
                ->where('idFormateur', $userId)
                ->where('headYear', Carbon::now()->format('Y'))
                ->whereIn('project_type', $idTypes)
                ->get();
        }

        if ($idPeriodes != null) {
            switch ($idPeriodes) {
                case 'prev_3_month':
                    $query->where('p_id_periode', $idPeriodes);

                    $projectDates = DB::table('v_projet_internes')
                        ->select('headDate', 'headMonthDebut')
                        ->groupBy('headDate')
                        ->orderBy('dateDebut', 'asc')
                        ->where('idFormateur', $userId)

                        ->where('headYear', Carbon::now()->format('Y'))
                        ->where('p_id_periode', $idPeriodes)
                        ->get();

                    break;
                case 'prev_6_month':
                    $query->whereIn('p_id_periode', ["prev_3_month", "prev_6_month"]);

                    $projectDates = DB::table('v_projet_internes')
                        ->select('headDate', 'headMonthDebut')
                        ->groupBy('headDate')
                        ->orderBy('dateDebut', 'asc')
                        ->where('idFormateur', $userId)
                        ->where('headYear', Carbon::now()->format('Y'))
                        ->whereIn('p_id_periode', ["prev_3_month", "prev_6_month"])
                        ->get();

                    break;
                case 'prev_12_month':
                    $query->whereIn('p_id_periode', ["prev_3_month", "prev_6_month", "prev_12_month"]);

                    $projectDates = DB::table('v_projet_internes')
                        ->select('headDate', 'headMonthDebut')
                        ->groupBy('headDate')
                        ->orderBy('dateDebut', 'asc')
                        ->where('idFormateur', $userId)
                        ->where('headYear', Carbon::now()->format('Y'))
                        ->whereIn('p_id_periode', ["prev_3_month", "prev_6_month", "prev_12_month"])
                        ->get();

                    break;
                case 'next_3_month':
                    $query->where('p_id_periode', $idPeriodes);

                    $projectDates = DB::table('v_projet_internes')
                        ->select('headDate', 'headMonthDebut')
                        ->groupBy('headDate')
                        ->orderBy('dateDebut', 'asc')
                        ->where('idFormateur', $userId)
                        ->where('headYear', Carbon::now()->format('Y'))
                        ->where('p_id_periode', $idPeriodes)
                        ->get();

                    break;
                case 'next_6_month':
                    $query->whereIn('p_id_periode', ["next_3_month", "next_6_month"]);

                    $projectDates = DB::table('v_projet_internes')
                        ->select('headDate', 'headMonthDebut')
                        ->groupBy('headDate')
                        ->orderBy('dateDebut', 'asc')
                        ->where('idFormateur', $userId)
                        ->where('headYear', Carbon::now()->format('Y'))
                        ->whereIn('p_id_periode', ["next_3_month", "next_6_month"])
                        ->get();
                    break;
                case 'next_12_month':
                    $query->whereIn('p_id_periode', ["next_3_month", "next_6_month", "next_12_month"]);

                    $projectDates = DB::table('v_projet_internes')
                        ->select('headDate', 'headMonthDebut')
                        ->groupBy('headDate')
                        ->orderBy('dateDebut', 'asc')
                        ->where('idFormateur', $userId)
                        ->where('headYear', Carbon::now()->format('Y'))
                        ->whereIn('p_id_periode', ["next_3_month", "next_6_month", "next_12_month"])
                        ->get();

                    break;

                default:
                    $query->where('p_id_periode', $idPeriodes);

                    $projectDates = DB::table('v_projet_internes')
                        ->select('headDate', 'headMonthDebut')
                        ->groupBy('headDate')
                        ->orderBy('dateDebut', 'asc')
                        ->where('idFormateur', $userId)

                        ->where('headYear', Carbon::now()->format('Y'))
                        ->where('p_id_periode', $idPeriodes)
                        ->get();

                    break;
            }

            $status = DB::table('v_projet_internes')
                ->select('project_status', DB::raw('COUNT(idProjet) AS projet_nb'))
                ->where('idFormateur', $userId)
                ->where('p_id_periode', $idPeriodes)
                ->groupBy('project_status')
                ->orderBy('project_status', 'asc')
                ->get();

            $etps = DB::table('v_projet_internes')
                ->select('idEtp', 'etp_name', DB::raw('COUNT(idProjet) AS projet_nb'))
                ->where('idFormateur', $userId)
                ->where('p_id_periode', $idPeriodes)
                ->groupBy('idEtp', 'etp_name')
                ->orderBy('etp_name', 'asc')
                ->get();

            $types = DB::table('v_projet_internes')
                ->select('project_type', DB::raw('COUNT(idProjet) AS projet_nb'))
                ->where('idFormateur', $userId)
                ->where('p_id_periode', $idPeriodes)
                ->orderBy('project_type', 'asc')
                ->groupBy('project_type')
                ->get();

            $modules = DB::table('v_projet_internes')
                ->select('idModule', 'module_name', DB::raw('COUNT(idProjet) AS projet_nb'))
                ->where('idFormateur', $userId)
                ->where('p_id_periode', $idPeriodes)
                ->orderBy('module_name', 'asc')
                ->groupBy('idModule', 'module_name')
                ->get();

            $villes = DB::table('v_projet_internes')
                ->select('idVille', 'ville', DB::raw('COUNT(idProjet) AS projet_nb'))
                ->where('idFormateur', $userId)
                ->where('p_id_periode', $idPeriodes)
                ->orderBy('ville', 'asc')
                ->groupBy('idVille', 'ville')
                ->get();

            // $financements = DB::table('v_projet_internes')
            //     ->select('idPaiement', 'paiement', DB::raw('COUNT(idProjet) AS projet_nb'))
            //     ->where('idFormateur', $userId)
            //     ->where('p_id_periode', $idPeriodes)
            //     ->orderBy('paiement', 'asc')
            //     ->groupBy('idPaiement', 'paiement')
            //     ->get();
        }

        if ($idModules[0] != null) {
            $query->whereIn('idModule', $idModules);

            $status = DB::table('v_projet_internes')
                ->select('project_status', DB::raw('COUNT(idProjet) AS projet_nb'))
                ->where('idFormateur', $userId)
                ->whereIn('idModule', $idModules)
                ->groupBy('project_status')
                ->orderBy('project_status', 'asc')
                ->get();

            $etps = DB::table('v_projet_internes')
                ->select('idEtp', 'etp_name', DB::raw('COUNT(idProjet) AS projet_nb'))
                ->where('idFormateur', $userId)
                ->whereIn('idModule', $idModules)
                ->groupBy('idEtp', 'etp_name')
                ->orderBy('etp_name', 'asc')
                ->get();

            $types = DB::table('v_projet_internes')
                ->select('project_type', DB::raw('COUNT(idProjet) AS projet_nb'))
                ->where('idFormateur', $userId)
                ->whereIn('idModule', $idModules)
                ->orderBy('project_type', 'asc')
                ->groupBy('project_type')
                ->get();

            $periodePrev3 = DB::table('v_projet_internes')
                ->select('idProjet', 'p_id_periode', DB::raw('COUNT(idProjet) AS projet_nb'))
                ->where('idFormateur', $userId)
                ->where('headYear', Carbon::now()->format('Y'))
                ->where('p_id_periode', "prev_3_month")
                ->whereIn('idModule', $idModules)
                ->groupBy('p_id_periode')
                ->first();

            $periodePrev6 = DB::table('v_projet_internes')
                ->select(DB::raw('"prev_6_month" AS p_id_periode'), DB::raw('COUNT(idProjet) AS projet_nb'))
                ->where('idFormateur', $userId)
                ->where('headYear', Carbon::now()->format('Y'))
                ->whereIn('p_id_periode', ["prev_3_month", "prev_6_month"])
                ->whereIn('idModule', $idModules)
                ->first();

            $periodePrev12 = DB::table('v_projet_internes')
                ->select(DB::raw('"prev_12_month" AS p_id_periode'), DB::raw('COUNT(idProjet) AS projet_nb'))
                ->where('idFormateur', $userId)
                ->where('headYear', Carbon::now()->format('Y'))
                ->whereIn('p_id_periode', ["prev_3_month", "prev_6_month", "prev_12_month"])
                ->whereIn('idModule', $idModules)
                ->first();

            $periodeNext3 = DB::table('v_projet_internes')
                ->select('p_id_periode', DB::raw('COUNT(idProjet) AS projet_nb'))
                ->where('idFormateur', $userId)
                ->where('headYear', Carbon::now()->format('Y'))
                ->where('p_id_periode', "next_3_month")
                ->whereIn('idModule', $idModules)
                ->groupBy('p_id_periode')
                ->first();

            $periodeNext6 = DB::table('v_projet_internes')
                ->select(DB::raw('"next_6_month" AS p_id_periode'), DB::raw('COUNT(idProjet) AS projet_nb'))
                ->where('idFormateur', $userId)
                ->where('headYear', Carbon::now()->format('Y'))
                ->whereIn('p_id_periode', ["next_3_month", "next_6_month"])
                ->whereIn('idModule', $idModules)
                ->first();

            $periodeNext12 = DB::table('v_projet_internes')
                ->select(DB::raw('"next_12_month" AS p_id_periode'), DB::raw('COUNT(idProjet) AS projet_nb'))
                ->where('idFormateur', $userId)
                ->where('headYear', Carbon::now()->format('Y'))
                ->whereIn('p_id_periode', ["next_3_month", "next_6_month", "next_12_month"])
                ->whereIn('idModule', $idModules)
                ->first();

            $villes = DB::table('v_projet_internes')
                ->select('idVille', 'ville', DB::raw('COUNT(idProjet) AS projet_nb'))
                ->where('idFormateur', $userId)
                ->whereIn('idModule', $idModules)
                ->orderBy('ville', 'asc')
                ->groupBy('idVille', 'ville')
                ->get();

            // $financements = DB::table('v_projet_internes')
            //     ->select('idPaiement', 'paiement', DB::raw('COUNT(idProjet) AS projet_nb'))
            //     ->where('idFormateur', $userId)
            //     ->whereIn('idModule', $idModules)
            //     ->orderBy('paiement', 'asc')
            //     ->groupBy('idPaiement', 'paiement')
            //     ->get();

            $projectDates = DB::table('v_projet_internes')
                ->select('headDate', 'headMonthDebut')
                ->groupBy('headDate')
                ->orderBy('dateDebut', 'asc')
                ->where('idFormateur', $userId)
                ->where('headYear', Carbon::now()->format('Y'))
                ->whereIn('idModule', $idModules)
                ->get();
        }

        if ($idVilles[0] != null) {
            $query->whereIn('idVille', $idVilles);

            $status = DB::table('v_projet_internes')
                ->select('project_status', DB::raw('COUNT(idProjet) AS projet_nb'))
                ->where('idFormateur', $userId)
                ->whereIn('idVille', $idVilles)
                ->groupBy('project_status')
                ->orderBy('project_status', 'asc')
                ->get();

            $etps = DB::table('v_projet_internes')
                ->select('idEtp', 'etp_name', DB::raw('COUNT(idProjet) AS projet_nb'))
                ->where('idFormateur', $userId)
                ->whereIn('idVille', $idVilles)
                ->groupBy('idEtp', 'etp_name')
                ->orderBy('etp_name', 'asc')
                ->get();

            $types = DB::table('v_projet_internes')
                ->select('project_type', DB::raw('COUNT(idProjet) AS projet_nb'))
                ->where('idFormateur', $userId)
                ->whereIn('idVille', $idVilles)
                ->orderBy('project_type', 'asc')
                ->groupBy('project_type')
                ->get();

            $periodePrev3 = DB::table('v_projet_internes')
                ->select('idProjet', 'p_id_periode', DB::raw('COUNT(idProjet) AS projet_nb'))
                ->where('idFormateur', $userId)
                ->where('headYear', Carbon::now()->format('Y'))
                ->where('p_id_periode', "prev_3_month")
                ->whereIn('idVille', $idVilles)
                ->groupBy('p_id_periode')
                ->first();

            $periodePrev6 = DB::table('v_projet_internes')
                ->select(DB::raw('"prev_6_month" AS p_id_periode'), DB::raw('COUNT(idProjet) AS projet_nb'))
                ->where('idFormateur', $userId)
                ->where('headYear', Carbon::now()->format('Y'))
                ->whereIn('p_id_periode', ["prev_3_month", "prev_6_month"])
                ->whereIn('idVille', $idVilles)
                ->first();

            $periodePrev12 = DB::table('v_projet_internes')
                ->select(DB::raw('"prev_12_month" AS p_id_periode'), DB::raw('COUNT(idProjet) AS projet_nb'))
                ->where('idFormateur', $userId)
                ->where('headYear', Carbon::now()->format('Y'))
                ->whereIn('p_id_periode', ["prev_3_month", "prev_6_month", "prev_12_month"])
                ->whereIn('idVille', $idVilles)
                ->first();

            $periodeNext3 = DB::table('v_projet_internes')
                ->select('p_id_periode', DB::raw('COUNT(idProjet) AS projet_nb'))
                ->where('idFormateur', $userId)
                ->where('headYear', Carbon::now()->format('Y'))
                ->where('p_id_periode', "next_3_month")
                ->whereIn('idVille', $idVilles)
                ->groupBy('p_id_periode')
                ->first();

            $periodeNext6 = DB::table('v_projet_internes')
                ->select(DB::raw('"next_6_month" AS p_id_periode'), DB::raw('COUNT(idProjet) AS projet_nb'))
                ->where('idFormateur', $userId)
                ->where('headYear', Carbon::now()->format('Y'))
                ->whereIn('p_id_periode', ["next_3_month", "next_6_month"])
                ->whereIn('idVille', $idVilles)
                ->first();

            $periodeNext12 = DB::table('v_projet_internes')
                ->select(DB::raw('"next_12_month" AS p_id_periode'), DB::raw('COUNT(idProjet) AS projet_nb'))
                ->where('idFormateur', $userId)
                ->where('headYear', Carbon::now()->format('Y'))
                ->whereIn('p_id_periode', ["next_3_month", "next_6_month", "next_12_month"])
                ->whereIn('idVille', $idVilles)
                ->first();

            $modules = DB::table('v_projet_internes')
                ->select('idModule', 'module_name', DB::raw('COUNT(idProjet) AS projet_nb'))
                ->where('idFormateur', $userId)
                ->whereIn('idVille', $idVilles)
                ->orderBy('module_name', 'asc')
                ->groupBy('idModule', 'module_name')
                ->get();

            // $financements = DB::table('v_projet_internes')
            //     ->select('idPaiement', 'paiement', DB::raw('COUNT(idProjet) AS projet_nb'))
            //     ->where('idFormateur', $userId)
            //     ->whereIn('idVille', $idVilles)
            //     ->orderBy('paiement', 'asc')
            //     ->groupBy('idPaiement', 'paiement')
            //     ->get();

            $projectDates = DB::table('v_projet_internes')
                ->select('headDate', 'headMonthDebut')
                ->groupBy('headDate')
                ->orderBy('dateDebut', 'asc')
                ->where('idFormateur', $userId)
                ->where('headYear', Carbon::now()->format('Y'))
                ->whereIn('idVille', $idVilles)
                ->get();
        }

        // if ($idFinancements[0] != null) {
        //     $query->whereIn('idPaiement', $idFinancements);

        //     $status = DB::table('v_projet_internes')
        //         ->select('project_status', DB::raw('COUNT(idProjet) AS projet_nb'))
        //         ->where('idFormateur', $userId)
        //         ->whereIn('idPaiement', $idFinancements)
        //         ->groupBy('project_status')
        //         ->orderBy('project_status', 'asc')
        //         ->get();

        //     $etps = DB::table('v_projet_internes')
        //         ->select('idEtp', 'etp_name', DB::raw('COUNT(idProjet) AS projet_nb'))
        //         ->where('idFormateur', $userId)
        //         ->whereIn('idPaiement', $idFinancements)
        //         ->groupBy('idEtp', 'etp_name')
        //         ->orderBy('etp_name', 'asc')
        //         ->get();

        //     $types = DB::table('v_projet_internes')
        //         ->select('project_type', DB::raw('COUNT(idProjet) AS projet_nb'))
        //         ->where('idFormateur', $userId)
        //         ->whereIn('idPaiement', $idFinancements)
        //         ->orderBy('project_type', 'asc')
        //         ->groupBy('project_type')
        //         ->get();

        //     $periodePrev3 = DB::table('v_projet_internes')
        //         ->select('idProjet', 'p_id_periode', DB::raw('COUNT(idProjet) AS projet_nb'))
        //         ->where('idFormateur', $userId)
        //         ->where('headYear', Carbon::now()->format('Y'))
        //         ->where('p_id_periode', "prev_3_month")
        //         ->whereIn('idPaiement', $idFinancements)
        //         ->groupBy('p_id_periode')
        //         ->first();

        //     $periodePrev6 = DB::table('v_projet_internes')
        //         ->select(DB::raw('"prev_6_month" AS p_id_periode'), DB::raw('COUNT(idProjet) AS projet_nb'))
        //         ->where('idFormateur', $userId)
        //         ->where('headYear', Carbon::now()->format('Y'))
        //         ->whereIn('p_id_periode', ["prev_3_month", "prev_6_month"])
        //         ->whereIn('idPaiement', $idFinancements)
        //         ->first();

        //     $periodePrev12 = DB::table('v_projet_internes')
        //         ->select(DB::raw('"prev_12_month" AS p_id_periode'), DB::raw('COUNT(idProjet) AS projet_nb'))
        //         ->where('idFormateur', $userId)
        //         ->where('headYear', Carbon::now()->format('Y'))
        //         ->whereIn('p_id_periode', ["prev_3_month", "prev_6_month", "prev_12_month"])
        //         ->whereIn('idPaiement', $idFinancements)
        //         ->first();

        //     $periodeNext3 = DB::table('v_projet_internes')
        //         ->select('p_id_periode', DB::raw('COUNT(idProjet) AS projet_nb'))
        //         ->where('idFormateur', $userId)
        //         ->where('headYear', Carbon::now()->format('Y'))
        //         ->where('p_id_periode', "next_3_month")
        //         ->whereIn('idPaiement', $idFinancements)
        //         ->groupBy('p_id_periode')
        //         ->first();

        //     $periodeNext6 = DB::table('v_projet_internes')
        //         ->select(DB::raw('"next_6_month" AS p_id_periode'), DB::raw('COUNT(idProjet) AS projet_nb'))
        //         ->where('idFormateur', $userId)
        //         ->where('headYear', Carbon::now()->format('Y'))
        //         ->whereIn('p_id_periode', ["next_3_month", "next_6_month"])
        //         ->whereIn('idPaiement', $idFinancements)
        //         ->first();

        //     $periodeNext12 = DB::table('v_projet_internes')
        //         ->select(DB::raw('"next_12_month" AS p_id_periode'), DB::raw('COUNT(idProjet) AS projet_nb'))
        //         ->where('idFormateur', $userId)
        //         ->where('headYear', Carbon::now()->format('Y'))
        //         ->whereIn('p_id_periode', ["next_3_month", "next_6_month", "next_12_month"])
        //         ->whereIn('idPaiement', $idFinancements)
        //         ->first();

        //     $modules = DB::table('v_projet_internes')
        //         ->select('idModule', 'module_name', DB::raw('COUNT(idProjet) AS projet_nb'))
        //         ->where('idFormateur', $userId)
        //         ->whereIn('idPaiement', $idFinancements)
        //         ->orderBy('module_name', 'asc')
        //         ->groupBy('idModule', 'module_name')
        //         ->get();

        //     $villes = DB::table('v_projet_internes')
        //         ->select('idVille', 'ville', DB::raw('COUNT(idProjet) AS projet_nb'))
        //         ->where('idFormateur', $userId)
        //         ->whereIn('idPaiement', $idFinancements)
        //         ->orderBy('ville', 'asc')
        //         ->groupBy('idVille', 'ville')
        //         ->get();

        //     $projectDates = DB::table('v_projet_internes')
        //         ->select('headDate', 'headMonthDebut')
        //         ->groupBy('headDate')
        //         ->orderBy('dateDebut', 'asc')
        //         ->where('idFormateur', $userId)
        //         ->where('headYear', Carbon::now()->format('Y'))
        //         ->whereIn('idPaiement', $idFinancements)
        //         ->get();
        // }

        if ($idStatus[0] == null && $idEtps[0] == null && $idTypes[0] == null && $idPeriodes == null && $idModules[0] == null && $idVilles[0] == null) {
            $status = DB::table('v_projet_internes')
                ->select('project_status', DB::raw('COUNT(idProjet) AS projet_nb'))
                ->where('idFormateur', $userId)
                ->groupBy('project_status')
                ->orderBy('project_status', 'asc')
                ->get();

            $etps = DB::table('v_projet_internes')
                ->select('idEtp', 'etp_name', DB::raw('COUNT(idProjet) AS projet_nb'))
                ->where('idFormateur', $userId)
                ->groupBy('idEtp', 'etp_name')
                ->orderBy('etp_name', 'asc')
                ->get();

            $types = DB::table('v_projet_internes')
                ->select('project_type', DB::raw('COUNT(idProjet) AS projet_nb'))
                ->where('idFormateur', $userId)
                ->orderBy('project_type', 'asc')
                ->groupBy('project_type')
                ->get();

            $periodePrev3 = DB::table('v_projet_internes')
                ->select('idProjet', 'p_id_periode', DB::raw('COUNT(idProjet) AS projet_nb'))
                ->where('idFormateur', $userId)
                ->where('headYear', Carbon::now()->format('Y'))
                ->where('p_id_periode', "prev_3_month")
                ->groupBy('p_id_periode')
                ->first();

            $periodePrev6 = DB::table('v_projet_internes')
                ->select(DB::raw('"prev_6_month" AS p_id_periode'), DB::raw('COUNT(idProjet) AS projet_nb'))
                ->where('idFormateur', $userId)
                ->where('headYear', Carbon::now()->format('Y'))
                ->whereIn('p_id_periode', ["prev_3_month", "prev_6_month"])
                ->first();

            $periodePrev12 = DB::table('v_projet_internes')
                ->select(DB::raw('"prev_12_month" AS p_id_periode'), DB::raw('COUNT(idProjet) AS projet_nb'))
                ->where('idFormateur', $userId)
                ->where('headYear', Carbon::now()->format('Y'))
                ->whereIn('p_id_periode', ["prev_3_month", "prev_6_month", "prev_12_month"])
                ->first();

            $periodeNext3 = DB::table('v_projet_internes')
                ->select('p_id_periode', DB::raw('COUNT(idProjet) AS projet_nb'))
                ->where('idFormateur', $userId)
                ->where('headYear', Carbon::now()->format('Y'))
                ->where('p_id_periode', "next_3_month")
                ->groupBy('p_id_periode')
                ->first();

            $periodeNext6 = DB::table('v_projet_internes')
                ->select(DB::raw('"next_6_month" AS p_id_periode'), DB::raw('COUNT(idProjet) AS projet_nb'))
                ->where('idFormateur', $userId)
                ->where('headYear', Carbon::now()->format('Y'))
                ->whereIn('p_id_periode', ["next_3_month", "next_6_month"])
                ->first();

            $periodeNext12 = DB::table('v_projet_internes')
                ->select(DB::raw('"next_12_month" AS p_id_periode'), DB::raw('COUNT(idProjet) AS projet_nb'))
                ->where('idFormateur', $userId)
                ->where('headYear', Carbon::now()->format('Y'))
                ->whereIn('p_id_periode', ["next_3_month", "next_6_month", "next_12_month"])
                ->first();

            $modules = DB::table('v_projet_internes')
                ->select('idModule', 'module_name', DB::raw('COUNT(idProjet) AS projet_nb'))
                ->where('idFormateur', $userId)
                ->orderBy('module_name', 'asc')
                ->groupBy('idModule', 'module_name')
                ->get();

            $villes = DB::table('v_projet_internes')
                ->select('idVille', 'ville', DB::raw('COUNT(idProjet) AS projet_nb'))
                ->where('idFormateur', $userId)
                ->orderBy('ville', 'asc')
                ->groupBy('idVille', 'ville')
                ->get();

            // $financements = DB::table('v_projet_internes')
            //     ->select('idPaiement', 'paiement', DB::raw('COUNT(idProjet) AS projet_nb'))
            //     ->where('idFormateur', $userId)
            //     ->orderBy('paiement', 'asc')
            //     ->groupBy('idPaiement', 'paiement')
            //     ->get();

            $projectDates = DB::table('v_projet_internes')
                ->select('headDate', 'headMonthDebut')
                ->groupBy('headDate')
                ->orderBy('dateDebut', 'asc')
                ->where('idFormateur', $userId)
                ->where('headYear', Carbon::now()->format('Y'))
                ->get();
        }

        $projects = $query->get();

        $projets = [];
        foreach ($projects as $project) {
            $projets[] = [
                'seanceCount' => $this->getSessionProject($project->idProjet),
                'formateurs' => $this->getFormProject($project->idProjet),
                'apprCount' => $this->getApprenantProject($project->idProjet, $project->idCfp_inter),
                'projectTotalPrice' => $this->getProjectTotalPrice($project->idProjet),
                'idProjet' => $project->idProjet,
                'dateDebut' => $project->dateDebut,
                'dateFin' => $project->dateFin,
                'module_name' => $project->module_name,
                // 'etp_name' => $this->getEtpProjectInter($project->idProjet, $project->idCfp_inter),
                'ville' => $project->ville,
                'project_status' => $project->project_status,
                'project_type' => $project->project_type,
                // 'paiement' => $project->paiement,
                'modalite' => $project->modalite,
                'headDate' => $project->headDate,
                'module_image' => $project->module_image,
                'etp_logo' => $project->etp_logo,
                'etp_initial_name' => $project->etp_initial_name,
                'salle_name' => $project->salle_name,
                'salle_quartier' => $project->salle_quartier,
                'salle_code_postal' => $project->salle_code_postal,
                'ville' => $project->ville,
                'headYear' => $project->headYear,
                'headMonthDebut' => $project->headMonthDebut,
                'headMonthFin' => $project->headMonthFin,
                'headDayDebut' => $project->headDayDebut,
                'headDayFin' => $project->headDayFin
            ];
        }

        if ($idStatus[0] != null) {
            return response()->json([
                'projets' => $projets,
                'etps' => $etps,
                'types' => $types,
                'periodePrev3' => $periodePrev3,
                'periodePrev6' => $periodePrev6,
                'periodePrev12' => $periodePrev12,
                'periodeNext3' => $periodeNext3,
                'periodeNext6' => $periodeNext6,
                'periodeNext12' => $periodeNext12,
                'modules' => $modules,
                'villes' => $villes,
                // 'financements' => $financements,
                'projectDates' => $projectDates
            ]);
        } elseif ($idEtps[0] != null) {
            return response()->json([
                'projets' => $projets,
                'status' => $status,
                'types' => $types,
                'periodePrev3' => $periodePrev3,
                'periodePrev6' => $periodePrev6,
                'periodePrev12' => $periodePrev12,
                'periodeNext3' => $periodeNext3,
                'periodeNext6' => $periodeNext6,
                'periodeNext12' => $periodeNext12,
                'modules' => $modules,
                'villes' => $villes,
                // 'financements' => $financements,
                'projectDates' => $projectDates
            ]);
        } elseif ($idTypes[0] != null) {
            return response()->json([
                'projets' => $projets,
                'status' => $status,
                'etps' => $etps,
                'periodePrev3' => $periodePrev3,
                'periodePrev6' => $periodePrev6,
                'periodePrev12' => $periodePrev12,
                'periodeNext3' => $periodeNext3,
                'periodeNext6' => $periodeNext6,
                'periodeNext12' => $periodeNext12,
                'modules' => $modules,
                'villes' => $villes,
                // 'financements' => $financements,
                'projectDates' => $projectDates
            ]);
        } elseif ($idPeriodes != null) {
            return response()->json([
                'projets' => $projets,
                'status' => $status,
                'etps' => $etps,
                'types' => $types,
                'modules' => $modules,
                'villes' => $villes,
                // 'financements' => $financements,
                'projectDates' => $projectDates
            ]);
        } elseif ($idModules[0] != null) {
            return response()->json([
                'projets' => $projets,
                'status' => $status,
                'etps' => $etps,
                'types' => $types,
                'periodePrev3' => $periodePrev3,
                'periodePrev6' => $periodePrev6,
                'periodePrev12' => $periodePrev12,
                'periodeNext3' => $periodeNext3,
                'periodeNext6' => $periodeNext6,
                'periodeNext12' => $periodeNext12,
                'villes' => $villes,
                // 'financements' => $financements,
                'projectDates' => $projectDates
            ]);
        } elseif ($idVilles[0] != null) {
            return response()->json([
                'projets' => $projets,
                'status' => $status,
                'etps' => $etps,
                'types' => $types,
                'periodePrev3' => $periodePrev3,
                'periodePrev6' => $periodePrev6,
                'periodePrev12' => $periodePrev12,
                'periodeNext3' => $periodeNext3,
                'periodeNext6' => $periodeNext6,
                'periodeNext12' => $periodeNext12,
                'modules' => $modules,
                // 'financements' => $financements,
                'projectDates' => $projectDates
            ]);
            // } elseif ($idFinancements[0] != null) {
            //     return response()->json([
            //         'projets' => $projets,
            //         'status' => $status,
            //         'etps' => $etps,
            //         'types' => $types,
            //         'periodePrev3' => $periodePrev3,
            //         'periodePrev6' => $periodePrev6,
            //         'periodePrev12' => $periodePrev12,
            //         'periodeNext3' => $periodeNext3,
            //         'periodeNext6' => $periodeNext6,
            //         'periodeNext12' => $periodeNext12,
            //         'modules' => $modules,
            //         'villes' => $villes,
            //         'projectDates' => $projectDates
            //     ]);
        } else {
            return response()->json([
                'projets' => $projets,
                'status' => $status,
                'etps' => $etps,
                'types' => $types,
                'periodePrev3' => $periodePrev3,
                'periodePrev6' => $periodePrev6,
                'periodePrev12' => $periodePrev12,
                'periodeNext3' => $periodeNext3,
                'periodeNext6' => $periodeNext6,
                'periodeNext12' => $periodeNext12,
                'modules' => $modules,
                'villes' => $villes,
                // 'financements' => $financements,
                'projectDates' => $projectDates
            ]);
        }
    }

    // 3
    public function filterItem(Request $req)
    {
        $idStatus = explode(',', $req->idStatut);
        $idEtps = explode(',', $req->idEtp);
        $idTypes = explode(',', $req->idType);
        $idPeriodes = $req->idPeriode;
        $idModules = explode(',', $req->idModule);
        $idVilles = explode(',', $req->idVille);
        // $idFinancements = explode(',', $req->idFinancement);

        $userId = Auth::user()->id;


        $query = DB::table('v_projet_internes')
            ->select('idProjet', 'dateDebut', 'dateFin', 'module_name', 'etp_name', 'ville', 'project_status', 'project_type', 'headDate', 'headMonthDebut', 'headMonthFin', 'headYear', 'headDayDebut', 'headDayFin', 'module_image', 'etp_logo', 'etp_initial_name', 'salle_name', 'salle_quartier', 'salle_code_postal', 'ville', 'idCfp_inter', 'modalite')
            ->where('idFormateur', $userId)
            ->where('headYear', Carbon::now()->format('Y'));

        $queryDate = DB::table('v_projet_internes')
            ->select('headDate', 'headMonthDebut')
            ->groupBy('headDate')
            ->orderBy('dateDebut', 'asc')
            ->where('idFormateur', $userId)
            ->where('headYear', Carbon::now()->format('Y'));

        if ($idStatus[0] != null) {
            $query->whereIn('project_status', $idStatus);

            $queryDate = DB::table('v_projet_internes')
                ->select('headDate', 'headMonthDebut')
                ->groupBy('headDate')
                ->orderBy('dateDebut', 'asc')
                ->where('idFormateur', $userId)
                ->where('headYear', Carbon::now()->format('Y'))
                ->whereIn('project_status', $idStatus);

            if ($idEtps[0] != null) {
                $query->whereIn('idEtp', $idEtps);
                $queryDate->whereIn('idEtp', $idEtps);
            }

            if ($idTypes[0] != null) {
                $query->whereIn('project_type', $idTypes);
                $queryDate->whereIn('project_type', $idTypes);
            }

            if ($idPeriodes != null) {
                switch ($idPeriodes) {
                    case 'prev_3_month':
                        $query->where('p_id_periode', $idPeriodes);
                        $queryDate->where('p_id_periode', $idPeriodes);

                        break;
                    case 'prev_6_month':
                        $query->whereIn('p_id_periode', ["prev_3_month", "prev_6_month"]);
                        $queryDate->whereIn('p_id_periode', ["prev_3_month", "prev_6_month"]);

                        break;
                    case 'prev_12_month':
                        $query->whereIn('p_id_periode', ["prev_3_month", "prev_6_month", "prev_12_month"]);
                        $queryDate->whereIn('p_id_periode', ["prev_3_month", "prev_6_month", "prev_12_month"]);

                        break;
                    case 'next_3_month':
                        $query->where('p_id_periode', $idPeriodes);
                        $queryDate->where('p_id_periode', $idPeriodes);

                        break;
                    case 'next_6_month':
                        $query->whereIn('p_id_periode', ["next_3_month", "next_6_month"]);
                        $queryDate->whereIn('p_id_periode', ["next_3_month", "next_6_month"]);
                        break;
                    case 'next_12_month':
                        $query->whereIn('p_id_periode', ["next_3_month", "next_6_month", "next_12_month"]);
                        $queryDate->whereIn('p_id_periode', ["next_3_month", "next_6_month", "next_12_month"]);

                        break;

                    default:
                        $query->where('p_id_periode', $idPeriodes);

                        $queryDate = DB::table('v_projet_internes')
                            ->select('headDate', 'headMonthDebut')
                            ->groupBy('headDate')
                            ->orderBy('dateDebut', 'asc')
                            ->where('idFormateur', $userId)
                            ->where('headYear', Carbon::now()->format('Y'))
                            ->where('p_id_periode', $idPeriodes);
                        break;
                }
            }

            if ($idModules[0] != null) {
                $query->whereIn('idModule', $idModules);
                $queryDate->whereIn('idModule', $idModules);
            }

            if ($idVilles[0] != null) {
                $query->whereIn('idVille', $idVilles);
                $queryDate->whereIn('idVille', $idVilles);
            }

            // if ($idFinancements[0] != null) {
            //     $query->whereIn('idPaiement', $idFinancements);
            //     $queryDate->whereIn('idPaiement', $idFinancements);
            // }
        }

        if ($idEtps[0] != null) {
            $query->whereIn('idEtp', $idEtps);

            $queryDate = DB::table('v_projet_internes')
                ->select('headDate', 'headMonthDebut')
                ->groupBy('headDate')
                ->orderBy('dateDebut', 'asc')
                ->where('idFormateur', $userId)
                ->where('headYear', Carbon::now()->format('Y'))
                ->whereIn('idEtp', $idEtps);

            if ($idStatus[0] != null) {
                $query->whereIn('project_status', $idStatus);
                $queryDate->whereIn('project_status', $idStatus);
            }

            if ($idTypes[0] != null) {
                $query->whereIn('project_type', $idTypes);
                $queryDate->whereIn('project_type', $idTypes);
            }

            if ($idPeriodes != null) {
                switch ($idPeriodes) {
                    case 'prev_3_month':
                        $query->where('p_id_periode', $idPeriodes);
                        $queryDate->where('p_id_periode', $idPeriodes);

                        break;
                    case 'prev_6_month':
                        $query->whereIn('p_id_periode', ["prev_3_month", "prev_6_month"]);
                        $queryDate->whereIn('p_id_periode', ["prev_3_month", "prev_6_month"]);

                        break;
                    case 'prev_12_month':
                        $query->whereIn('p_id_periode', ["prev_3_month", "prev_6_month", "prev_12_month"]);
                        $queryDate->whereIn('p_id_periode', ["prev_3_month", "prev_6_month", "prev_12_month"]);

                        break;
                    case 'next_3_month':
                        $query->where('p_id_periode', $idPeriodes);
                        $queryDate->where('p_id_periode', $idPeriodes);

                        break;
                    case 'next_6_month':
                        $query->whereIn('p_id_periode', ["next_3_month", "next_6_month"]);
                        $queryDate->whereIn('p_id_periode', ["next_3_month", "next_6_month"]);
                        break;
                    case 'next_12_month':
                        $query->whereIn('p_id_periode', ["next_3_month", "next_6_month", "next_12_month"]);
                        $queryDate->whereIn('p_id_periode', ["next_3_month", "next_6_month", "next_12_month"]);

                        break;

                    default:
                        $query->where('p_id_periode', $idPeriodes);

                        $queryDate = DB::table('v_projet_internes')
                            ->select('headDate', 'headMonthDebut')
                            ->groupBy('headDate')
                            ->orderBy('dateDebut', 'asc')
                            ->where('idFormateur', $userId)
                            ->where('headYear', Carbon::now()->format('Y'))
                            ->where('p_id_periode', $idPeriodes);
                        break;
                }
            }

            if ($idModules[0] != null) {
                $query->whereIn('idModule', $idModules);
                $queryDate->whereIn('idModule', $idModules);
            }

            if ($idVilles[0] != null) {
                $query->whereIn('idVille', $idVilles);
                $queryDate->whereIn('idVille', $idVilles);
            }

            // if ($idFinancements[0] != null) {
            //     $query->whereIn('idPaiement', $idFinancements);
            //     $queryDate->whereIn('idPaiement', $idFinancements);
            // }
        }

        if ($idTypes[0] != null) {
            $query->whereIn('project_type', $idTypes);

            $queryDate = DB::table('v_projet_internes')
                ->select('headDate', 'headMonthDebut')
                ->groupBy('headDate')
                ->orderBy('dateDebut', 'asc')
                ->where('idFormateur', $userId)
                ->where('headYear', Carbon::now()->format('Y'))
                ->whereIn('project_type', $idTypes);

            if ($idStatus[0] != null) {
                $query->whereIn('project_status', $idStatus);
                $queryDate->whereIn('project_status', $idStatus);
            }

            if ($idEtps[0] != null) {
                $query->whereIn('idEtp', $idEtps);
                $queryDate->whereIn('idEtp', $idEtps);
            }

            if ($idPeriodes != null) {
                switch ($idPeriodes) {
                    case 'prev_3_month':
                        $query->where('p_id_periode', $idPeriodes);
                        $queryDate->where('p_id_periode', $idPeriodes);

                        break;
                    case 'prev_6_month':
                        $query->whereIn('p_id_periode', ["prev_3_month", "prev_6_month"]);
                        $queryDate->whereIn('p_id_periode', ["prev_3_month", "prev_6_month"]);

                        break;
                    case 'prev_12_month':
                        $query->whereIn('p_id_periode', ["prev_3_month", "prev_6_month", "prev_12_month"]);
                        $queryDate->whereIn('p_id_periode', ["prev_3_month", "prev_6_month", "prev_12_month"]);

                        break;
                    case 'next_3_month':
                        $query->where('p_id_periode', $idPeriodes);
                        $queryDate->where('p_id_periode', $idPeriodes);

                        break;
                    case 'next_6_month':
                        $query->whereIn('p_id_periode', ["next_3_month", "next_6_month"]);
                        $queryDate->whereIn('p_id_periode', ["next_3_month", "next_6_month"]);
                        break;
                    case 'next_12_month':
                        $query->whereIn('p_id_periode', ["next_3_month", "next_6_month", "next_12_month"]);
                        $queryDate->whereIn('p_id_periode', ["next_3_month", "next_6_month", "next_12_month"]);

                        break;

                    default:
                        $query->where('p_id_periode', $idPeriodes);

                        $queryDate = DB::table('v_projet_internes')
                            ->select('headDate', 'headMonthDebut')
                            ->groupBy('headDate')
                            ->orderBy('dateDebut', 'asc')
                            ->where('idFormateur', $userId)
                            ->where('headYear', Carbon::now()->format('Y'))
                            ->where('p_id_periode', $idPeriodes);
                        break;
                }
            }

            if ($idModules[0] != null) {
                $query->whereIn('idModule', $idModules);
                $queryDate->whereIn('idModule', $idModules);
            }

            if ($idVilles[0] != null) {
                $query->whereIn('idVille', $idVilles);
                $queryDate->whereIn('idVille', $idVilles);
            }

            // if ($idFinancements[0] != null) {
            //     $query->whereIn('idPaiement', $idFinancements);
            //     $queryDate->whereIn('idPaiement', $idFinancements);
            // }
        }

        if ($idPeriodes != null) {
            switch ($idPeriodes) {
                case 'prev_3_month':
                    $query->where('p_id_periode', $idPeriodes);

                    $queryDate = DB::table('v_projet_internes')
                        ->select('headDate', 'headMonthDebut')
                        ->groupBy('headDate')
                        ->orderBy('dateDebut', 'asc')
                        ->where('idFormateur', $userId)
                        ->where('headYear', Carbon::now()->format('Y'))
                        ->where('p_id_periode', $idPeriodes);

                    break;
                case 'prev_6_month':
                    $query->whereIn('p_id_periode', ["prev_3_month", "prev_6_month"]);

                    $queryDate = DB::table('v_projet_internes')
                        ->select('headDate', 'headMonthDebut')
                        ->groupBy('headDate')
                        ->orderBy('dateDebut', 'asc')
                        ->where('idFormateur', $userId)
                        ->where('headYear', Carbon::now()->format('Y'))
                        ->whereIn('p_id_periode', ["prev_3_month", "prev_6_month"]);

                    break;
                case 'prev_12_month':
                    $query->whereIn('p_id_periode', ["prev_3_month", "prev_6_month", "prev_12_month"]);

                    $queryDate = DB::table('v_projet_internes')
                        ->select('headDate', 'headMonthDebut')
                        ->groupBy('headDate')
                        ->orderBy('dateDebut', 'asc')
                        ->where('idFormateur', $userId)
                        ->where('headYear', Carbon::now()->format('Y'))
                        ->whereIn('p_id_periode', ["prev_3_month", "prev_6_month", "prev_12_month"]);

                    break;
                case 'next_3_month':
                    $query->where('p_id_periode', $idPeriodes);

                    $queryDate = DB::table('v_projet_internes')
                        ->select('headDate', 'headMonthDebut')
                        ->groupBy('headDate')
                        ->orderBy('dateDebut', 'asc')
                        ->where('idFormateur', $userId)
                        ->where('headYear', Carbon::now()->format('Y'))
                        ->where('p_id_periode', $idPeriodes);

                    break;
                case 'next_6_month':
                    $query->whereIn('p_id_periode', ["next_3_month", "next_6_month"]);

                    $queryDate = DB::table('v_projet_internes')
                        ->select('headDate', 'headMonthDebut')
                        ->groupBy('headDate')
                        ->orderBy('dateDebut', 'asc')
                        ->where('idFormateur', $userId)
                        ->where('headYear', Carbon::now()->format('Y'))
                        ->whereIn('p_id_periode', ["next_3_month", "next_6_month"]);
                    break;
                case 'next_12_month':
                    $query->whereIn('p_id_periode', ["next_3_month", "next_6_month", "next_12_month"]);

                    $queryDate = DB::table('v_projet_internes')
                        ->select('headDate', 'headMonthDebut')
                        ->groupBy('headDate')
                        ->orderBy('dateDebut', 'asc')
                        ->where('idFormateur', $userId)
                        ->where('headYear', Carbon::now()->format('Y'))
                        ->whereIn('p_id_periode', ["next_3_month", "next_6_month", "next_12_month"]);

                    break;

                default:
                    $query->where('p_id_periode', $idPeriodes);

                    $queryDate = DB::table('v_projet_internes')
                        ->select('headDate', 'headMonthDebut')
                        ->groupBy('headDate')
                        ->orderBy('dateDebut', 'asc')
                        ->where('idFormateur', $userId)
                        ->where('headYear', Carbon::now()->format('Y'))
                        ->where('p_id_periode', $idPeriodes);
                    break;
            }

            if ($idStatus[0] != null) {
                $query->whereIn('project_status', $idStatus);
                $queryDate->whereIn('project_status', $idStatus);
            }

            if ($idEtps[0] != null) {
                $query->whereIn('idEtp', $idEtps);
                $queryDate->whereIn('idEtp', $idEtps);
            }

            if ($idTypes[0] != null) {
                $query->whereIn('project_type', $idTypes);
                $queryDate->whereIn('project_type', $idTypes);
            }

            if ($idModules[0] != null) {
                $query->whereIn('idModule', $idModules);
                $queryDate->whereIn('idModule', $idModules);
            }

            if ($idVilles[0] != null) {
                $query->whereIn('idVille', $idVilles);
                $queryDate->whereIn('idVille', $idVilles);
            }

            // if ($idFinancements[0] != null) {
            //     $query->whereIn('idPaiement', $idFinancements);
            //     $queryDate->whereIn('idPaiement', $idFinancements);
            // }
        }

        if ($idModules[0] != null) {
            $query->whereIn('idModule', $idModules);

            $queryDate = DB::table('v_projet_internes')
                ->select('headDate', 'headMonthDebut')
                ->groupBy('headDate')
                ->orderBy('dateDebut', 'asc')
                ->where('idFormateur', $userId)
                ->where('headYear', Carbon::now()->format('Y'))
                ->whereIn('idModule', $idModules);

            if ($idStatus[0] != null) {
                $query->whereIn('project_status', $idStatus);
                $queryDate->whereIn('project_status', $idStatus);
            }

            if ($idEtps[0] != null) {
                $query->whereIn('idEtp', $idEtps);
                $queryDate->whereIn('idEtp', $idEtps);
            }

            if ($idTypes[0] != null) {
                $query->whereIn('project_type', $idTypes);
                $queryDate->whereIn('project_type', $idTypes);
            }

            if ($idPeriodes != null) {
                switch ($idPeriodes) {
                    case 'prev_3_month':
                        $query->where('p_id_periode', $idPeriodes);
                        $queryDate->where('p_id_periode', $idPeriodes);

                        break;
                    case 'prev_6_month':
                        $query->whereIn('p_id_periode', ["prev_3_month", "prev_6_month"]);
                        $queryDate->whereIn('p_id_periode', ["prev_3_month", "prev_6_month"]);

                        break;
                    case 'prev_12_month':
                        $query->whereIn('p_id_periode', ["prev_3_month", "prev_6_month", "prev_12_month"]);
                        $queryDate->whereIn('p_id_periode', ["prev_3_month", "prev_6_month", "prev_12_month"]);

                        break;
                    case 'next_3_month':
                        $query->where('p_id_periode', $idPeriodes);
                        $queryDate->where('p_id_periode', $idPeriodes);

                        break;
                    case 'next_6_month':
                        $query->whereIn('p_id_periode', ["next_3_month", "next_6_month"]);
                        $queryDate->whereIn('p_id_periode', ["next_3_month", "next_6_month"]);
                        break;
                    case 'next_12_month':
                        $query->whereIn('p_id_periode', ["next_3_month", "next_6_month", "next_12_month"]);
                        $queryDate->whereIn('p_id_periode', ["next_3_month", "next_6_month", "next_12_month"]);

                        break;

                    default:
                        $query->where('p_id_periode', $idPeriodes);

                        $queryDate = DB::table('v_projet_internes')
                            ->select('headDate', 'headMonthDebut')
                            ->groupBy('headDate')
                            ->orderBy('dateDebut', 'asc')
                            ->where('idFormateur', $userId)
                            ->where('headYear', Carbon::now()->format('Y'))
                            ->where('p_id_periode', $idPeriodes);
                        break;
                }
            }

            if ($idVilles[0] != null) {
                $query->whereIn('idVille', $idVilles);
                $queryDate->whereIn('idVille', $idVilles);
            }

            // if ($idFinancements[0] != null) {
            //     $query->whereIn('idPaiement', $idFinancements);
            //     $queryDate->whereIn('idPaiement', $idFinancements);
            // }
        }

        if ($idVilles[0] != null) {
            $query->whereIn('idVille', $idVilles);

            $queryDate = DB::table('v_projet_internes')
                ->select('headDate', 'headMonthDebut')
                ->groupBy('headDate')
                ->orderBy('dateDebut', 'asc')
                ->where('idFormateur', $userId)
                ->where('headYear', Carbon::now()->format('Y'))
                ->whereIn('idVille', $idVilles);

            if ($idStatus[0] != null) {
                $query->whereIn('project_status', $idStatus);
                $queryDate->whereIn('project_status', $idStatus);
            }

            if ($idEtps[0] != null) {
                $query->whereIn('idEtp', $idEtps);
                $queryDate->whereIn('idEtp', $idEtps);
            }

            if ($idTypes[0] != null) {
                $query->whereIn('project_type', $idTypes);
                $queryDate->whereIn('project_type', $idTypes);
            }

            if ($idPeriodes != null) {
                switch ($idPeriodes) {
                    case 'prev_3_month':
                        $query->where('p_id_periode', $idPeriodes);
                        $queryDate->where('p_id_periode', $idPeriodes);

                        break;
                    case 'prev_6_month':
                        $query->whereIn('p_id_periode', ["prev_3_month", "prev_6_month"]);
                        $queryDate->whereIn('p_id_periode', ["prev_3_month", "prev_6_month"]);

                        break;
                    case 'prev_12_month':
                        $query->whereIn('p_id_periode', ["prev_3_month", "prev_6_month", "prev_12_month"]);
                        $queryDate->whereIn('p_id_periode', ["prev_3_month", "prev_6_month", "prev_12_month"]);

                        break;
                    case 'next_3_month':
                        $query->where('p_id_periode', $idPeriodes);
                        $queryDate->where('p_id_periode', $idPeriodes);

                        break;
                    case 'next_6_month':
                        $query->whereIn('p_id_periode', ["next_3_month", "next_6_month"]);
                        $queryDate->whereIn('p_id_periode', ["next_3_month", "next_6_month"]);
                        break;
                    case 'next_12_month':
                        $query->whereIn('p_id_periode', ["next_3_month", "next_6_month", "next_12_month"]);
                        $queryDate->whereIn('p_id_periode', ["next_3_month", "next_6_month", "next_12_month"]);

                        break;

                    default:
                        $query->where('p_id_periode', $idPeriodes);

                        $queryDate = DB::table('v_projet_internes')
                            ->select('headDate', 'headMonthDebut')
                            ->groupBy('headDate')
                            ->orderBy('dateDebut', 'asc')
                            ->where('idFormateur', $userId)
                            ->where('headYear', Carbon::now()->format('Y'))
                            ->where('p_id_periode', $idPeriodes);
                        break;
                }
            }

            if ($idModules[0] != null) {
                $query->whereIn('idModule', $idModules);
                $queryDate->whereIn('idModule', $idModules);
            }

            // if ($idFinancements[0] != null) {
            //     $query->whereIn('idPaiement', $idFinancements);
            //     $queryDate->whereIn('idPaiement', $idFinancements);
            // }
        }

        // if ($idFinancements[0] != null) {
        //     $query->whereIn('idPaiement', $idFinancements);

        //     $queryDate = DB::table('v_projet_internes')
        //         ->select('headDate', 'headMonthDebut')
        //         ->groupBy('headDate')
        //         ->orderBy('dateDebut', 'asc')
        //         ->where('idFormateur', $userId)
        //         ->where('headYear', Carbon::now()->format('Y'))
        //         ->whereIn('idPaiement', $idFinancements);

        //     if ($idStatus[0] != null) {
        //         $query->whereIn('project_status', $idStatus);
        //         $queryDate->whereIn('project_status', $idStatus);
        //     }

        //     if ($idEtps[0] != null) {
        //         $query->whereIn('idEtp', $idEtps);
        //         $queryDate->whereIn('idEtp', $idEtps);
        //     }

        //     if ($idTypes[0] != null) {
        //         $query->whereIn('project_type', $idTypes);
        //         $queryDate->whereIn('project_type', $idTypes);
        //     }

        //     if ($idPeriodes != null) {
        //         switch ($idPeriodes) {
        //             case 'prev_3_month':
        //                 $query->where('p_id_periode', $idPeriodes);
        //                 $queryDate->where('p_id_periode', $idPeriodes);

        //                 break;
        //             case 'prev_6_month':
        //                 $query->whereIn('p_id_periode', ["prev_3_month", "prev_6_month"]);
        //                 $queryDate->whereIn('p_id_periode', ["prev_3_month", "prev_6_month"]);

        //                 break;
        //             case 'prev_12_month':
        //                 $query->whereIn('p_id_periode', ["prev_3_month", "prev_6_month", "prev_12_month"]);
        //                 $queryDate->whereIn('p_id_periode', ["prev_3_month", "prev_6_month", "prev_12_month"]);

        //                 break;
        //             case 'next_3_month':
        //                 $query->where('p_id_periode', $idPeriodes);
        //                 $queryDate->where('p_id_periode', $idPeriodes);

        //                 break;
        //             case 'next_6_month':
        //                 $query->whereIn('p_id_periode', ["next_3_month", "next_6_month"]);
        //                 $queryDate->whereIn('p_id_periode', ["next_3_month", "next_6_month"]);
        //                 break;
        //             case 'next_12_month':
        //                 $query->whereIn('p_id_periode', ["next_3_month", "next_6_month", "next_12_month"]);
        //                 $queryDate->whereIn('p_id_periode', ["next_3_month", "next_6_month", "next_12_month"]);

        //                 break;

        //             default:
        //                 $query->where('p_id_periode', $idPeriodes);

        //                 $queryDate = DB::table('v_projet_internes')
        //                     ->select('headDate', 'headMonthDebut')
        //                     ->groupBy('headDate')
        //                     ->orderBy('dateDebut', 'asc')
        //                     ->where('idFormateur', $userId)
        //                     ->where('headYear', Carbon::now()->format('Y'))
        //                     ->where('p_id_periode', $idPeriodes);
        //                 break;
        //         }
        //     }

        //     if ($idModules[0] != null) {
        //         $query->whereIn('idModule', $idModules);
        //         $queryDate->whereIn('idModule', $idModules);
        //     }

        //     if ($idVilles[0] != null) {
        //         $query->whereIn('idVille', $idVilles);
        //         $queryDate->whereIn('idVille', $idVilles);
        //     }
        // }

        $projects = $query->get();
        $projectDates = $queryDate->get();

        $projets = [];
        foreach ($projects as $project) {
            $projets[] = [
                'seanceCount' => $this->getSessionProject($project->idProjet),
                'formateurs' => $this->getFormProject($project->idProjet),
                'apprCount' => $this->getApprenantProject($project->idProjet, $project->idCfp_inter),
                'projectTotalPrice' => $this->getProjectTotalPrice($project->idProjet),
                'idProjet' => $project->idProjet,
                'dateDebut' => $project->dateDebut,
                'dateFin' => $project->dateFin,
                'module_name' => $project->module_name,
                // 'etp_name' => $this->getEtpProjectInter($project->idProjet, $project->idCfp_inter),
                'ville' => $project->ville,
                'project_status' => $project->project_status,
                'project_type' => $project->project_type,
                // 'paiement' => $project->paiement,
                'modalite' => $project->modalite,
                'headDate' => $project->headDate,
                'module_image' => $project->module_image,
                'etp_logo' => $project->etp_logo,
                'etp_initial_name' => $project->etp_initial_name,
                'salle_name' => $project->salle_name,
                'salle_quartier' => $project->salle_quartier,
                'salle_code_postal' => $project->salle_code_postal,
                'ville' => $project->ville,
                'headYear' => $project->headYear,
                'headMonthDebut' => $project->headMonthDebut,
                'headMonthFin' => $project->headMonthFin,
                'headDayDebut' => $project->headDayDebut,
                'headDayFin' => $project->headDayFin
            ];
        }

        return response()->json([
            'projets' => $projets,
            'projectDates' => $projectDates
        ]);
    }

    public function getProjectTotalPrice($idProjet)
    {
        $projectPrice = DB::table('v_projet_cfps')
            ->select(DB::raw('SUM(project_price_pedagogique + project_price_annexe) AS project_total_price'))
            ->where('idProjet', $idProjet)
            ->first();

        return $projectPrice->project_total_price;
    }

    public function getApprenantProject($idProjet, $idCfp_inter)
    {
        if ($idCfp_inter == null) {
            $apprs = DB::table('v_list_apprenants')
                ->select('idEmploye', 'emp_initial_name', 'emp_name', 'emp_firstname', 'emp_email', 'emp_photo', 'emp_matricule', 'emp_phone', 'etp_name')
                ->where('idProjet', $idProjet)
                ->orderBy('emp_name', 'asc')
                ->get();
        } elseif ($idCfp_inter != null) {
            $apprs = DB::table('v_list_apprenant_inter_added')
                ->select('idEmploye', 'emp_name', 'emp_firstname', 'emp_fonction', 'emp_email', 'emp_photo', 'emp_matricule', 'etp_name', 'idEtp')
                ->where('idProjet', $idProjet)
                ->orderBy('emp_name', 'asc')
                ->get();
        }

        return count($apprs);
    }

    public function getFormProject($idProjet)
    {
        $forms = DB::table('v_formateur_cfps')
            ->select('idFormateur', 'name AS form_name', 'firstName AS form_firstname', 'photoForm AS form_photo', 'initialNameForm AS form_initial_name')
            ->groupBy('idFormateur', 'name', 'firstName', 'photoForm', 'initialNameForm')
            ->where('idProjet', $idProjet)->get();

        return $forms->toArray();
    }
    public function getSessionProject($idProjet)
    {
        $countSession = DB::table('v_seances')
            ->select('idSeance', 'dateSeance', 'heureDebut', 'id_google_seance', 'heureFin', 'idSalle', 'idProjet', 'salle_name', 'salle_quartier', 'project_title', 'project_description', 'idModule', 'module_name', 'ville')
            ->where('idProjet', $idProjet)
            ->get();

        return count($countSession);
    }

    public function index()
    {
        try {
            if (!Auth::check()) {
                throw new Exception('User is not authenticated.');
            }
            $userId = Auth::id();

            $list = DB::table('v_formateur_internes')
                ->select('idEmploye AS idFormateur', 'idEntreprise', 'customerName as name', 'isActive')
                ->where('idEmploye', $userId)
                ->first();

            if (!$list) {
                throw new Exception('No records found for the authenticated user.');
            }
        } catch (Exception $e) {
            Log::error($e->getMessage());
            return response()->json(['error' => ['message' => $e->getMessage()]], 500);
        }
        // dd($list);
        return view('formateurInternes.entreprise.index', compact('list'));
    }
}
