<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StatisticsController extends Controller
{
    public function learner()
    {
        $learner = DB::table('apprenants as A')
                        ->join('employes as E', 'E.idEmploye', '=', 'A.idEmploye')
                        ->select(DB::raw('CASE WHEN MONTH(E.created_at) = 1 THEN "Jan"
                                               WHEN MONTH(E.created_at) = 2 THEN "Fev"
                                               WHEN MONTH(E.created_at) = 3 THEN "Mar"
                                               WHEN MONTH(E.created_at) = 4 THEN "Avr"
                                               WHEN MONTH(E.created_at) = 5 THEN "Mai"
                                               WHEN MONTH(E.created_at) = 6 THEN "Juin"
                                               WHEN MONTH(E.created_at) = 7 THEN "Juil"
                                               WHEN MONTH(E.created_at) = 8 THEN "Sept"
                                               WHEN MONTH(E.created_at) = 9 THEN "Aout"
                                               WHEN MONTH(E.created_at) = 10 THEN "Oct"
                                               WHEN MONTH(E.created_at) = 11 THEN "Nov"
                                               WHEN MONTH(E.created_at) = 12 THEN "Dec"
                                               END as month
                                               '), DB::raw('COUNT(E.idEmploye) as monthly_count'))
                        ->where('E.created_at', '>', DB::raw('DATE_SUB(now(), INTERVAL 12 MONTH)'))
                        ->groupBy(DB::raw('MONTH(E.created_at)'))
                        ->get();

        $sommeCumulative = 0;
        foreach ($learner as $entry) {
            $sommeCumulative += $entry->monthly_count;
            $entry->somme_cumulative = $sommeCumulative;
        }

        $mois = [];
        $count_learner = [];

        foreach($learner as $l){
            $mois[] = $l->month;
            $count_learner[] = $l->somme_cumulative;
        }

        return response()->json([
            'mois' => $mois,
            'count' => $count_learner
        ]);
    }

    public function project(){
        $projects = DB::table('projets')
                        ->select(DB::raw('CASE WHEN MONTH(created_at) = 1 THEN "Jan"
                                               WHEN MONTH(created_at) = 2 THEN "Fev"
                                               WHEN MONTH(created_at) = 3 THEN "Mar"
                                               WHEN MONTH(created_at) = 4 THEN "Avr"
                                               WHEN MONTH(created_at) = 5 THEN "Mai"
                                               WHEN MONTH(created_at) = 6 THEN "Juin"
                                               WHEN MONTH(created_at) = 7 THEN "Juil"
                                               WHEN MONTH(created_at) = 8 THEN "Sept"
                                               WHEN MONTH(created_at) = 9 THEN "Aout"
                                               WHEN MONTH(created_at) = 10 THEN "Oct"
                                               WHEN MONTH(created_at) = 11 THEN "Nov"
                                               WHEN MONTH(created_at) = 12 THEN "Dec"
                                               END as month'), DB::raw('COUNT(idProjet) as monthly_count'))
                        ->where('created_at', '>', DB::raw('DATE_SUB(now(), INTERVAL 12 MONTH)'))
                        ->groupBy(DB::raw('MONTH(created_at)'))
                        ->get();

        $sommeCumulative = 0;
        foreach ($projects as $entry) {
            $sommeCumulative += $entry->monthly_count;
            $entry->somme_cumulative = $sommeCumulative;
        }

        $mois = [];
        $count_project = [];

        foreach($projects as $p){
            $mois[] = $p->month;
            $count_project[] = $p->somme_cumulative;
        }

        return response()->json([
            'mois' => $mois,
            'count' => $count_project
        ]);
    }

    public function cfp(){
        $cfps = DB::table('customers as C')
                    ->join('users as U', 'U.id', '=', 'C.idCustomer')
                    ->select(DB::raw('CASE WHEN MONTH(U.created_at) = 1 THEN "Jan"
                                        WHEN MONTH(U.created_at) = 2 THEN "Fev"
                                        WHEN MONTH(U.created_at) = 3 THEN "Mar"
                                        WHEN MONTH(U.created_at) = 4 THEN "Avr"
                                        WHEN MONTH(U.created_at) = 5 THEN "Mai"
                                        WHEN MONTH(U.created_at) = 6 THEN "Juin"
                                        WHEN MONTH(U.created_at) = 7 THEN "Juil"
                                        WHEN MONTH(U.created_at) = 8 THEN "Sept"
                                        WHEN MONTH(U.created_at) = 9 THEN "Aout"
                                        WHEN MONTH(U.created_at) = 10 THEN "Oct"
                                        WHEN MONTH(U.created_at) = 11 THEN "Nov"
                                        WHEN MONTH(U.created_at) = 12 THEN "Dec"
                                        END as month'), DB::raw('COUNT(U.id) as monthly_count'))
                    ->where('U.created_at', '>', DB::raw('DATE_SUB(now(), INTERVAL 12 MONTH)'))
                    ->where('idTypeCustomer', 1)
                    ->groupBy(DB::raw('MONTH(U.created_at)'))
                    ->get();

        $sommeCumulative = 0;
        foreach ($cfps as $entry) {
            $sommeCumulative += $entry->monthly_count;
            $entry->somme_cumulative = $sommeCumulative;
        }

        $mois = [];
        $count_cfp = [];

        foreach($cfps as $c){
            $mois[] = $c->month;
            $count_cfp[] = $c->somme_cumulative;
        }

        return response()->json([
            'mois' => $mois,
            'count' => $count_cfp
        ]);
    }

    public function entreprise(){
        $etps = DB::table('customers as C')
                        ->join('users as U', 'U.id', '=', 'C.idCustomer')
                        ->select(DB::raw('CASE WHEN MONTH(U.created_at) = 1 THEN "Jan"
                                               WHEN MONTH(U.created_at) = 2 THEN "Fev"
                                               WHEN MONTH(U.created_at) = 3 THEN "Mar"
                                               WHEN MONTH(U.created_at) = 4 THEN "Avr"
                                               WHEN MONTH(U.created_at) = 5 THEN "Mai"
                                               WHEN MONTH(U.created_at) = 6 THEN "Juin"
                                               WHEN MONTH(U.created_at) = 7 THEN "Juil"
                                               WHEN MONTH(U.created_at) = 8 THEN "Sept"
                                               WHEN MONTH(U.created_at) = 9 THEN "Aout"
                                               WHEN MONTH(U.created_at) = 10 THEN "Oct"
                                               WHEN MONTH(U.created_at) = 11 THEN "Nov"
                                               WHEN MONTH(U.created_at) = 12 THEN "Dec"
                                               END as month'), DB::raw('COUNT(U.id) as monthly_count'))
                        ->where('U.created_at', '>', DB::raw('DATE_SUB(now(), INTERVAL 12 MONTH)'))
                        ->where('idTypeCustomer', 2)
                        ->groupBy(DB::raw('MONTH(U.created_at)'))
                        ->get();

        $sommeCumulative = 0;
        foreach ($etps as $entry) {
            $sommeCumulative += $entry->monthly_count;
            $entry->somme_cumulative = $sommeCumulative;
        }

        $mois = [];
        $count_entreprise = [];

        foreach($etps as $e){
            $mois[] = $e->month;
            $count_entreprise[] = $e->somme_cumulative;
        }

        return response()->json([
            'mois' => $mois,
            'count' => $count_entreprise
        ]);
    }
}
