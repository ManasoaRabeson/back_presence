<?php
namespace App\Traits;

use Illuminate\Support\Facades\DB;


trait EvaluationQuery {

    public function getEvaluations(mixed $idProjet) {
        if (!is_countable($idProjet)) {
            $idProjet = [$idProjet];
        }
        $evaluation = DB::table('eval_chauds')
            ->select('generalApreciate','idEmploye', 'idProjet')
            ->whereIn('idProjet', $idProjet)
            ->get()
            ->groupBy('idProjet');
        return $evaluation;
    }

    private function getEval($idProjet)
    {
        $result = DB::table('eval_chauds')
            ->select(
                DB::raw('SUM(firstNotes.generalApreciate) as sumFirstNotes'),
                DB::raw('COUNT(DISTINCT firstNotes.idEmploye) as totalEmployees')
            )
            ->fromSub(function ($query) use ($idProjet) {
                $query->select('idEmploye', 'idProjet', 'generalApreciate')
                    ->from('eval_chauds')
                    ->where('idProjet', $idProjet)
                    ->whereNotNull('generalApreciate')
                    ->groupBy('idEmploye', 'idProjet');
            }, 'firstNotes')
            ->first();

        $average = $result->totalEmployees > 0 ? $result->sumFirstNotes / $result->totalEmployees : 0;

        return round($average, 1);
    }
}