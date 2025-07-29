<?php
namespace App\Services;

use App\Interfaces\FormateurInterface;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class FormateurService implements FormateurInterface{
    public function storeForm($idFormateur, $idTypeFormateur): void
    {
        DB::table('forms')->insert([
            'idFormateur' => $idFormateur,
            'idTypeFormateur' => $idTypeFormateur,
            'idSexe' => 1
        ]);
    }

    public function storeFormateur($idFormateur): void
    {
        DB::table('formateurs')->insert([
            'idFormateur' => $idFormateur,
            'idSp' => 1
        ]);
    }

    public function storeCfpFormateur($idCfp, $idFormateur, $isActiveFormateur, $isActiveCfp): void
    {
        DB::table('cfp_formateurs')->insert([
            'idCfp' => $idCfp,
            'idFormateur' => $idFormateur,
            'dateCollaboration' => Carbon::now(),
            'isActiveFormateur' => $isActiveFormateur,
            'isActiveCfp' => $isActiveCfp
        ]);
    }
}