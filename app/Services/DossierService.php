<?php

namespace App\Services;

use App\Interfaces\DossierInterface;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class DossierService implements DossierInterface
{
    public function createDossier(string $dossier, int $idCfp): int
    {
        $originalDossier = $dossier;
        $counter = 1;

        while (DB::table('dossiers')->where('nomDossier', $dossier)->where('idCfp', $idCfp)->exists()) {
            $dossier = $originalDossier . "($counter)";
            $counter++;
        }

        return DB::table('dossiers')->insertGetId([
            'nomDossier' => $dossier,
            'idCfp' => $idCfp,
        ]);
    }

    public function getDossiersByCfpAndYear(int $cfpId, int $year)
    {
        // Ordre des statuts
        $statusOrder = [
            'En préparation',
            'En cours',
            'Planifié',
            'Terminé',
            'Annulé',
            'Reporté',
            'Cloturé'
        ];

        // Requête avec la vue 'v_projet_cfps'
        $dossiers = DB::table('dossiers')
            ->leftJoin('v_projet_cfps', 'dossiers.idDossier', '=', 'v_projet_cfps.idDossier')
            ->where('dossiers.idCfp', $cfpId)
            ->whereYear('dossiers.created_at', $year)
            ->select(
                'dossiers.idDossier',
                'dossiers.nomDossier',
                DB::raw(
                    'MIN(CASE ' .
                        implode(' ', array_map(function ($status, $index) {
                            return "WHEN v_projet_cfps.project_status = '$status' AND v_projet_cfps.project_is_trashed = 0 THEN $index";
                        }, $statusOrder, array_keys($statusOrder)))
                        . ' END) as statusIndex'
                )
            )
            ->groupBy('dossiers.idDossier', 'dossiers.nomDossier')
            ->orderBy('dossiers.nomDossier', 'asc')
            ->get();

        // Traitement des statuts pour chaque dossier
        return $dossiers->map(function ($dossier) use ($statusOrder) {
            $dossier->minStatus = isset($statusOrder[$dossier->statusIndex]) ? $statusOrder[$dossier->statusIndex] : null;
            unset($dossier->statusIndex);
            return $dossier;
        });
    }

    public function getAllDossiersByCfpAndYear(int $idCfp, int $year)
    {
        // dd($idCfp, $year);
        return DB::table('dossiers')
            ->where('idCfp', $idCfp)
            ->whereYear('created_at', $year)
            ->orderBy('nomDossier', 'asc')
            ->get();
    }

    public function dossierExists($nomDossier)
    {
        return DB::table('dossiers')->where('nomDossier', $nomDossier)->exists();
    }

    public function updateDossier($idDossier, $nouveauNom)
    {
        return DB::table('dossiers')
            ->where('idDossier', $idDossier)
            ->update(['nomDossier' => $nouveauNom]);
    }

    public function deleteFiles($idDossier)
    {
        $filePaths = DB::table('documents')
            ->where('idDossier', $idDossier)
            ->pluck('path');

        foreach ($filePaths as $filePath) {
            if (Storage::disk('do')->exists($filePath)) {
                Storage::disk('do')->delete($filePath);
            }
        }

        DB::table('documents')->where('idDossier', $idDossier)->delete();
    }

    public function deleteRelatedProjets($idDossier)
    {
        DB::table('projets')
            ->where('idDossier', $idDossier)
            ->update(['idDossier' => null]);
    }

    public function deleteDossier($idDossier)
    {
        return DB::table('dossiers')->where('idDossier', $idDossier)->delete();
    }

    // modifier
    public function getEntreprisesDossierDetail($idDossier, $idCfp)
    {
        return DB::table('v_projet_cfps')
            ->distinct()
            ->select('etp_name')
            ->where(function ($query) use ($idCfp) {
                $query->where('idCfp', $idCfp)
                    ->orWhere('idCfp_inter', $idCfp)
                    ->orWhere('idSubContractor', $idCfp);
            })
            ->where('idDossier', $idDossier)
            ->where('project_is_trashed', 0)
            ->get();
    }

    public function getMontantTotalDossierDetail($idDossier)
    {
        return DB::table('projets')
            ->select(DB::raw('sum(total_ht) as montantTotal'))
            ->where('project_is_trashed', 0)
            ->where('idDossier', $idDossier)
            ->first();
    }

    public function getProjectTypesDossierDetail($idDossier, $idCfp)
    {
        return DB::table('v_projet_cfps')
            ->distinct()
            ->select('project_type')
            ->where('idDossier', $idDossier)
            ->where('project_is_trashed', 0)
            ->get();
    }

    public function getModuleNamesDossierDetail($idDossier, $idCfp)
    {
        return DB::table('v_projet_cfps')
            ->distinct()
            ->select('module_name')
            ->where('idDossier', $idDossier)
            ->where('project_is_trashed', 0)
            ->get();
    }

    public function getVillesDossierDetail($idDossier, $idCfp)
    {
        return DB::table('v_projet_cfps')
            ->distinct()
            ->select('ville')
            ->where('idDossier', $idDossier)
            ->where('project_is_trashed', 0)
            ->get();
    }

    public function getDateMinProjetDossierDetail($idDossier)
    {
        return DB::table('projets')
            ->select(DB::raw('min(dateDebut) as dateDebut'))
            ->where('project_is_trashed', 0)
            ->where('idDossier', $idDossier)
            ->pluck('dateDebut');
    }

    public function getDateMaxProjetDossierDetail($idDossier)
    {
        return DB::table('projets')
            ->select(DB::raw('max(dateFin) as dateFin'))
            ->where('project_is_trashed', 0)
            ->where('idDossier', $idDossier)
            ->pluck('dateFin');
    }

    public function getNombreDocumentDossierDetail($idDossier)
    {
        return DB::table('documents')
            ->select(DB::raw('count(*) as nombreDocument'))
            ->where('idDossier', $idDossier)
            ->pluck('nombreDocument');
    }

    public function getNbProjetDossierDetail($idDossier)
    {
        return DB::table('v_projet_cfps')
            ->select(DB::raw('COUNT(idDossier) as projet_count'))
            ->where('idDossier', $idDossier)
            ->where('project_is_trashed', 0)
            ->pluck('projet_count');
    }

    public function getApprenantCountDossierDetail($idDossier)
    {
        return DB::table('detail_apprenants')
            ->join('projets', 'detail_apprenants.idProjet', '=', 'projets.idProjet')
            ->where('project_is_trashed', 0)
            ->where('idDossier', $idDossier)
            ->count();
    }

    public function getPaymentStatusDossierDetail(int $idDossier)
    {
        $idProjets = DB::table('projets')
            ->where('idDossier', $idDossier)
            ->pluck('idProjet');

        $status = 6; // Par défaut : non payé

        foreach ($idProjets as $projet) {
            $isPaid = DB::table('invoice_details as ID')
                ->select('I.invoice_status')
                ->join('invoices as I', 'I.idInvoice', '=', 'ID.idInvoice')
                ->join('invoice_payments as IP', 'IP.invoice_id', '=', 'ID.idInvoice')
                ->where('ID.idProjet', $projet)
                ->whereNotExists(function ($query) {
                    $query->select('IL.id')
                        ->from('invoice_deleted as IL')
                        ->whereRaw('IL.idInvoice = ID.idInvoice');
                })
                ->first();

            $projectStatus = $isPaid->invoice_status ?? 0;

            switch ($projectStatus) {
                case 5:
                    return 5;
                case 4:
                    return 4;
                default:
                    return 6;
            }
        }

        return $status;
    }
}
