<?php
namespace App\Services;

use App\Interfaces\EmployeInterface;
use App\Models\Employe;
use App\Traits\GetQuery;
use Exception;
use Illuminate\Support\Facades\DB;
use App\Traits\StoreQuery;
use Illuminate\Support\Facades\Auth;

class EmployeService implements EmployeInterface{
    use StoreQuery, GetQuery;

    public function store($id, $idNiveau, $idCustomer, $idSexe, $idFonction): void
    {
        $emp = new Employe();
        $emp->idEmploye = $id;
        $emp->idNiveau = $idNiveau;
        $emp->idCustomer = $idCustomer;
        $emp->idSexe = $idSexe;
        $emp->idFonction = $idFonction;
        $emp->save();
    }
}