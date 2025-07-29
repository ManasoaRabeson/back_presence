<?php
namespace App\Interfaces;

interface FormateurInterface{
    public function storeForm($idFormateur, $idTypeFormateur): void;
    public function storeFormateur($idFormateur): void;
    public function storeCfpFormateur($idCfp, $idFormateur, $isActiveFormateur, $isActiveCfp): void;
}