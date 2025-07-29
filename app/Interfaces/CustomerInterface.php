<?php
namespace App\Interfaces;

interface CustomerInterface{
    public function store($idCustomer, $name, $email, $idSecteur, $idType, $idVilleCoded): void;
}
