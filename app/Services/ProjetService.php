<?php

namespace App\Services;

use App\Interfaces\ProjectRepository;
use App\Models\Customer;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Drivers\Gd\Driver;
use Intervention\Image\ImageManager;

class ProjetService implements ProjectRepository
{
    // public function index($idCustomer): mixed
    // {
    //     $query = DB::table('v_projet_cfps')
    //         ->select('idProjet', 'dateDebut', 'idEtp', 'dateFin', 'module_name', 'etp_name', 'li_name', 'ville', 'project_status', 'project_reference', 'project_description', 'project_type', 'paiement', DB::raw('DATE_FORMAT(dateDebut, "%M, %Y") AS headDate'), 'module_image', 'etp_logo', 'etp_initial_name', 'idSalle', 'salle_name', 'salle_quartier', 'salle_code_postal', 'ville', 'idCfp_inter', 'modalite', 'total_ht', 'total_ttc', 'idModule', 'project_inter_privacy', 'sub_name', 'idSubContractor', 'idCfp', 'cfp_name', 'headYear', 'headMonthDebut', 'headMonthFin', 'headDayDebut', 'headDayFin', 'total_ht_sub_contractor')
    //         ->where(function ($query) {
    //             $query->where(function ($query) {
    //                 $query->where('idCfp', Customer::idCustomer())
    //                     ->orWhere('idCfp_inter', Customer::idCustomer())
    //                     ->orWhere('idSubContractor', Customer::idCustomer());
    //             });
    //         })
    //         ->where('module_name', '!=', 'Default module')
    //         ->groupBy('idProjet', 'dateDebut', 'idEtp', 'dateFin', 'module_name', 'etp_name', 'li_name', 'ville', 'project_status', 'project_reference', 'project_description', 'project_type', 'paiement', 'module_image', 'etp_logo', 'etp_initial_name', 'idSalle', 'salle_name', 'salle_quartier', 'salle_code_postal', 'ville', 'idCfp_inter', 'modalite', 'total_ht', 'total_ttc', 'idModule', 'project_inter_privacy', 'sub_name', 'idSubContractor', 'idCfp', 'cfp_name', 'headYear', 'headMonthDebut', 'headMonthFin', 'headDayDebut', 'headDayFin', 'total_ht_sub_contractor');

    //     return $query;
    // }
    // public function index($idCustomer, $status = null): mixed
    // {
    //     return DB::table('v_projet_cfps')
    //         ->select(
    //             'idProjet',
    //             'dateDebut',
    //             'idEtp',
    //             'dateFin',
    //             'module_name',
    //             'etp_name',
    //             'li_name',
    //             'ville',
    //             'project_status',
    //             'project_reference',
    //             'project_description',
    //             'project_type',
    //             'paiement',
    //             DB::raw('DATE_FORMAT(dateDebut, "%M, %Y") AS headDate'),
    //             'module_image',
    //             'etp_logo',
    //             'etp_initial_name',
    //             'idSalle',
    //             'salle_name',
    //             'salle_quartier',
    //             'salle_code_postal',
    //             'idCfp_inter',
    //             'modalite',
    //             'total_ht',
    //             'total_ttc',
    //             'idModule',
    //             'project_inter_privacy',
    //             'sub_name',
    //             'idSubContractor',
    //             'idCfp',
    //             'cfp_name',
    //             'headYear',
    //             'headMonthDebut',
    //             'headMonthFin',
    //             'headDayDebut',
    //             'headDayFin',
    //             'total_ht_sub_contractor'
    //         )
    //         ->where(function ($query) use ($idCustomer) {
    //             $query->where('idCfp', $idCustomer)
    //                 ->orWhere('idCfp_inter', $idCustomer)
    //                 ->orWhere('idSubContractor', $idCustomer);
    //         })
    //         ->where('module_name', '!=', 'Default module')
    //         ->when($status, fn($q) => $q->where('project_status', $status))
    //         ->groupBy(
    //             'idProjet',
    //             'dateDebut',
    //             'idEtp',
    //             'dateFin',
    //             'module_name',
    //             'etp_name',
    //             'li_name',
    //             'ville',
    //             'project_status',
    //             'project_reference',
    //             'project_description',
    //             'project_type',
    //             'paiement',
    //             'module_image',
    //             'etp_logo',
    //             'etp_initial_name',
    //             'idSalle',
    //             'salle_name',
    //             'salle_quartier',
    //             'salle_code_postal',
    //             'ville',
    //             'idCfp_inter',
    //             'modalite',
    //             'total_ht',
    //             'total_ttc',
    //             'idModule',
    //             'project_inter_privacy',
    //             'sub_name',
    //             'idSubContractor',
    //             'idCfp',
    //             'cfp_name',
    //             'headYear',
    //             'headMonthDebut',
    //             'headMonthFin',
    //             'headDayDebut',
    //             'headDayFin',
    //             'total_ht_sub_contractor'
    //         )
    //         ->orderBy('dateDebut', 'desc')
    //         ->paginate(20); // 10 résultats par page
    // }
    public function index($idCustomer, $status = null, array $filters = []): mixed
    {
        $query = DB::table('v_projet_cfps')
            ->select(
                'idProjet',
                'dateDebut',
                'idEtp',
                'dateFin',
                'module_name',
                'etp_name',
                'li_name',
                'ville',
                'project_status',
                'project_reference',
                'project_description',
                'project_type',
                'paiement',
                DB::raw('DATE_FORMAT(dateDebut, "%M, %Y") AS headDate'),
                'module_image',
                'etp_logo',
                'etp_initial_name',
                'idSalle',
                'salle_name',
                'salle_quartier',
                'salle_code_postal',
                'idCfp_inter',
                'modalite',
                'total_ht',
                'total_ttc',
                'idModule',
                'project_inter_privacy',
                'sub_name',
                'idSubContractor',
                'idCfp',
                'cfp_name',
                'headYear',
                'headMonthDebut',
                'headMonthFin',
                'headDayDebut',
                'headDayFin',
                'total_ht_sub_contractor'
            )
            ->where(function ($query) use ($idCustomer) {
                $query->where('idCfp', $idCustomer)
                    ->orWhere('idCfp_inter', $idCustomer)
                    ->orWhere('idSubContractor', $idCustomer);
            })
            ->where('module_name', '!=', 'Default module')
            ->when($status, fn($q) => $q->where('project_status', $status));

        // Appliquer le filtre sur les formateurs en SQL
        if (!empty($filters['Formateur'])) {
            $formateurIds = $filters['Formateur'];

            $query->whereExists(function ($q) use ($formateurIds) {
                $q->select(DB::raw(1))
                    ->from('v_formateur_cfps')
                    ->whereColumn('v_formateur_cfps.idProjet', 'v_projet_cfps.idProjet')
                    ->whereIn('v_formateur_cfps.idFormateur', $formateurIds);
            });
        }

        // Autres filtres dynamiques (Ville, Projet, Entreprise, Cours, Mois, Periode)
        if (!empty($filters['Ville'])) {
            $villes = is_array($filters['Ville']) ? $filters['Ville'] : [$filters['Ville']];
            $query->whereIn('li_name', $villes);
        }

        if (!empty($filters['Projet'])) {
            $projets = is_array($filters['Projet']) ? $filters['Projet'] : [$filters['Projet']];
            $query->whereIn('project_type', $projets);
        }

        if (!empty($filters['Entreprise'])) {
            $entreprises = is_array($filters['Entreprise']) ? $filters['Entreprise'] : [$filters['Entreprise']];
            $query->whereIn('idEtp', $entreprises);
        }

        if (!empty($filters['Cours'])) {
            $cours = is_array($filters['Cours']) ? $filters['Cours'] : [$filters['Cours']];
            $query->whereIn('idModule', $cours);
        }

        if (!empty($filters['Mois'])) {
            $mois = is_array($filters['Mois']) ? $filters['Mois'] : [$filters['Mois']];
            $query->where(function ($q) use ($mois) {
                foreach ($mois as $m) {
                    $q->orWhereRaw('DATE_FORMAT(dateDebut, "%Y-%m") = ?', [$m]);
                }
            });
        }

        if (!empty($filters['Periode'])) {
            $today = now();
            $periods = [
                'prev_3_month' => [$today->copy()->subMonths(3), $today],
                'prev_6_month' => [$today->copy()->subMonths(6), $today],
                'prev_12_month' => [$today->copy()->subMonths(12), $today],
                'next_3_month' => [$today, $today->copy()->addMonths(3)],
                'next_6_month' => [$today, $today->copy()->addMonths(6)],
                'next_12_month' => [$today, $today->copy()->addMonths(12)],
            ];
            if (isset($periods[$filters['Periode']])) {
                [$from, $to] = $periods[$filters['Periode']];
                $query->whereBetween('dateDebut', [$from, $to]);
            }
        }

        // Group By
        $query->groupBy(
            'idProjet',
            'dateDebut',
            'idEtp',
            'dateFin',
            'module_name',
            'etp_name',
            'li_name',
            'ville',
            'project_status',
            'project_reference',
            'project_description',
            'project_type',
            'paiement',
            'module_image',
            'etp_logo',
            'etp_initial_name',
            'idSalle',
            'salle_name',
            'salle_quartier',
            'salle_code_postal',
            'ville',
            'idCfp_inter',
            'modalite',
            'total_ht',
            'total_ttc',
            'idModule',
            'project_inter_privacy',
            'sub_name',
            'idSubContractor',
            'idCfp',
            'cfp_name',
            'headYear',
            'headMonthDebut',
            'headMonthFin',
            'headDayDebut',
            'headDayFin',
            'total_ht_sub_contractor'
        );

        return $query->orderBy('dateDebut', 'desc')->paginate(20);
    }


    public function indexFilter($idCustomer, $status = null): mixed
    {
        return DB::table('v_projet_cfps')
            ->select(
                'idProjet',
                'dateDebut',
                'idEtp',
                'dateFin',
                'module_name',
                'etp_name',
                'li_name',
                'ville',
                'project_status',
                'project_reference',
                'project_description',
                'project_type',
                'paiement',
                DB::raw('DATE_FORMAT(dateDebut, "%M, %Y") AS headDate'),
                'module_image',
                'etp_logo',
                'etp_initial_name',
                'idSalle',
                'salle_name',
                'salle_quartier',
                'salle_code_postal',
                'idCfp_inter',
                'modalite',
                'total_ht',
                'total_ttc',
                'idModule',
                'project_inter_privacy',
                'sub_name',
                'idSubContractor',
                'idCfp',
                'cfp_name',
                'headYear',
                'headMonthDebut',
                'headMonthFin',
                'headDayDebut',
                'headDayFin',
                'total_ht_sub_contractor'
            )
            ->where(function ($query) use ($idCustomer) {
                $query->where('idCfp', $idCustomer)
                    ->orWhere('idCfp_inter', $idCustomer)
                    ->orWhere('idSubContractor', $idCustomer);
            })
            ->where('module_name', '!=', 'Default module')
            ->when($status, fn($q) => $q->where('project_status', $status))
            ->groupBy(
                'idProjet',
                'dateDebut',
                'idEtp',
                'dateFin',
                'module_name',
                'etp_name',
                'li_name',
                'ville',
                'project_status',
                'project_reference',
                'project_description',
                'project_type',
                'paiement',
                'module_image',
                'etp_logo',
                'etp_initial_name',
                'idSalle',
                'salle_name',
                'salle_quartier',
                'salle_code_postal',
                'ville',
                'idCfp_inter',
                'modalite',
                'total_ht',
                'total_ttc',
                'idModule',
                'project_inter_privacy',
                'sub_name',
                'idSubContractor',
                'idCfp',
                'cfp_name',
                'headYear',
                'headMonthDebut',
                'headMonthFin',
                'headDayDebut',
                'headDayFin',
                'total_ht_sub_contractor'
            )
            ->orderBy('dateDebut', 'desc')
            ->get();
    }


    public function countByStatus($idCustomer, $status): int
    {
        return DB::table('v_projet_cfps')
            ->where(function ($query) use ($idCustomer) {
                $query->where('idCfp', $idCustomer)
                    ->orWhere('idCfp_inter', $idCustomer)
                    ->orWhere('idSubContractor', $idCustomer);
            })
            ->where('module_name', '!=', 'Default module')
            ->where('project_status', $status)
            ->count();
    }


    // public function indexStatus($idCustomer, $status): array
    // {
    //     $query = $this->index($idCustomer)->where('project_status', $status)->orderBy('dateDebut', 'asc')->get();
    //     return $query->toArray();
    // }

    public function store(
        $idCustomer,
        $reference = null,
        $title,
        $description = null,
        $isProjectReserved,
        $idModalite,
        $idModule,
        $idTypeProjet,
        $idSalle,
        $dateDebut = null,
        $dateFin = null
    ): void {
        DB::transaction(
            function ()
            use (
                $idCustomer,
                $reference,
                $title,
                $description,
                $isProjectReserved,
                $idModalite,
                $idModule,
                $idTypeProjet,
                $idSalle,
                $dateDebut,
                $dateFin
            ) {
                $projet = DB::table('projets')->insertGetId([
                    'project_reference' => $reference,
                    'project_title' => $title,
                    'project_description' => $description,
                    'project_is_reserved' => $isProjectReserved,
                    'idModalite' => $idModalite,
                    'idCustomer' => $idCustomer,
                    'idModule' => $idModule,
                    'idTypeProjet' => $idTypeProjet,
                    'idVilleCoded' => 1,
                    'project_is_active' => 0,
                    'idSalle' => $idSalle,
                    'dateDebut' => $dateDebut,
                    'dateFin' => $dateFin
                ]);

                if ($idTypeProjet == 1) {
                    DB::table('intras')->insert([
                        'idProjet' => $projet,
                        'idPaiement' => 3,
                        'idEtp' => $idCustomer,
                        'idCfp' => $idCustomer
                    ]);
                } elseif ($idTypeProjet == 2) {
                    DB::table('inters')->insert([
                        'idProjet' => $projet,
                        'idPaiement' => 3,
                        'idCfp' => $idCustomer,
                        'project_inter_privacy' => 0,
                    ]);
                }
            }
        );
    }

    public function show($idCustomer, $idProjet): mixed
    {
        $query = $this->index($idCustomer)->where('idProjet', $idProjet);

        return $query;
    }

    public function headDate($idCustomer): mixed
    {
        $query = DB::table('v_projet_cfps')
            ->select(DB::raw('DATE_FORMAT(dateDebut, "%M, %Y") AS headDate'))
            ->groupBy('headDate')
            ->orderBy('dateDebut', 'asc')
            ->where(function ($query) {
                $query->where(function ($query) {
                    $query->where('idCfp', Customer::idCustomer())
                        ->orWhere('idCfp_inter', Customer::idCustomer())
                        ->orWhere('idSubContractor', Customer::idCustomer());
                });
            })
            ->where('module_name', '!=', 'Default module');

        return $query;
    }

    public function getProject($idCustomer): mixed
    {
        $query = DB::table('projets')->select('*');

        return $query;
    }
}
