<?php
namespace App\Services;

use App\Interfaces\CustomerInterface;
use App\Models\Customer;

class CustomerService implements CustomerInterface{
    public function store($idCustomer, $name, $email, $idSecteur, $idType, $idVilleCoded): void
    {
        $cst = new Customer();
        $cst->idCustomer = $idCustomer;
        $cst->customerName = $name;
        $cst->customerEmail = $email;
        $cst->idSecteur = $idSecteur;
        $cst->idTypeCustomer = $idType;
        $cst->idVilleCoded = $idVilleCoded;
        $cst->save();
    }
}