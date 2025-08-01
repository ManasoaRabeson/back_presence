<?php

namespace App\Interfaces;

interface ProjectRepository
{
    // Dans ProjectRepository.php
    public function index($idCustomer, $status = null, array $filters = []): mixed;

    // public function indexStatus($idCustomer, $status): array;
    public function store($idCustomer, $reference = null, $title, $description = null, $isProjectReserved, $idModalite, $idModule, $idTypeProjet, $idSalle, $dateDebut = null, $dateFin = null): void;
    public function show($idCustomer, $idProjet): mixed;
    public function headDate($idCustomer): mixed;
    public function getProject($idCustomer): mixed;
}
