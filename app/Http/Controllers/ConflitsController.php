<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ConflitsController extends Controller
{
    // public function getChevauchementHeure()
    // {
    //     $id = Auth::user()->id;
    //     $chevauchement = DB::table('seances as s1')
    //         ->join('seances as s2', function ($join) {
    //             $join->on('s1.dateSeance', '=', 's2.dateSeance')
    //                  ->where('s1.idSeance', '<>', 's2.idSeance')
    //                  ->whereRaw('s1.heureDebut < s2.heureFin AND s1.heureFin > s2.heureDebut');
    //         })
    //         ->join('projets','projets.idProjet','s2.idProjet')
    //         ->select('s1.idProjet','s1.dateSeance','s1.heureDebut','s1.heureFin')
    //         ->where('projets.idCustomer',$id)
    //         ->distinct() 
    //         ->orderBy('s1.idProjet', 'DESC')
    //         ->get();
    //         dd($chevauchement);
    //     return $chevauchement;
    // }

    public function getChevauchementHeure()
    {
        $id = Auth::user()->id;

        // Obtenir toutes les séances
        $seances = DB::table('seances as s1')
            ->join('projets', 'projets.idProjet', '=', 's1.idProjet')
            ->select('s1.idProjet', 's1.dateSeance', 's1.heureDebut', 's1.heureFin')
            ->where('projets.idCustomer', $id)
            ->orderBy('s1.dateSeance')
            ->orderBy('s1.heureDebut')
            ->get();

        // Initialiser un tableau pour stocker les résultats finaux
        $resultats = [];

        // Comparer chaque séance avec les autres
        foreach ($seances as $key => $seance) {
            // Vérifier cette séance contre toutes les suivantes
            for ($i = $key + 1; $i < count($seances); $i++) {
                $seanceSuivante = $seances[$i];

                // Vérifier si les deux séances ont la même date et le même idProjet
                if (
                    $seance->dateSeance == $seanceSuivante->dateSeance &&
                    $seance->idProjet == $seanceSuivante->idProjet
                ) {

                    // Vérifier les chevauchements
                    if (
                        $seance->heureDebut < $seanceSuivante->heureFin &&
                        $seance->heureFin > $seanceSuivante->heureDebut
                    ) {
                        $resultats[] = $seance;  // Ajouter la séance actuelle si elle chevauche
                        break;  // Pas besoin de vérifier davantage pour cette séance
                    }
                }
            }
        }

        // dd($resultats);
        return $resultats;
    }

    public function getConflitsLieux()
    {
        $chevauchement = $this->getChevauchementHeure();
        $conflitsArray = [];
        $idCfp = Auth::user()->id;
        $ignoredPairs = DB::table('ignoredConflitLieu')->get();
        $dateSeance = [];
    
        foreach ($chevauchement as $conflit) {
            $conflitsArray[] = $conflit->idProjet;
            $dateSeance[] = $conflit->dateSeance;
        }

        $projets = DB::table('projets')
            ->select(
                'projets.idProjet',
                'projets.idCustomer',
                'projets.idSalle',
                'customers.customerName',
                'projets.project_title',
                'projets.project_description',
                'salles.salle_name',
                'seances.dateSeance',
                'seances.heureDebut',
                'seances.heureFin'
            )
            ->join('customers', 'customers.idCustomer', 'projets.idCustomer')
            ->join('salles', 'salles.idSalle', 'projets.idSalle')
            ->join('seances', 'seances.idProjet', 'projets.idProjet')
            ->whereIn('seances.dateSeance', $dateSeance)
            ->whereIn('projets.idProjet', $conflitsArray);

        foreach ($ignoredPairs as $pair) {
            $projets->where(function ($query) use ($pair) {
                $query->where('projets.idSalle', '!=', $pair->idSalle)
                    ->orWhere('projets.idProjet', '!=', $pair->idProjet);
            });
        }

        $projets = $projets->get();

        $projets->each(function ($projet) {
            $projet->formattedDate = \Carbon\Carbon::parse($projet->dateSeance)->translatedFormat('l, d F Y');
        });

        $duplicates = $projets->groupBy('idSalle')->filter(function ($group) {
            return $group->count() > 1;
        });


        $filteredDuplicates = $duplicates->filter(function ($group) use ($idCfp) {
            $idCustomersInGroup = $group->pluck('idCustomer');
            return $idCustomersInGroup->contains($idCfp);
        });

        return $filteredDuplicates->isEmpty() ? $duplicates : $filteredDuplicates;
    }

    public function getConflitsFormateurs()
    {
        $chevauchement = $this->getChevauchementHeure();
        $conflitsArray = [];
        $tableignore = DB::table('ignoredConflitFormateur')->get();
        $idCfp = Auth::user()->id;
        // $ignoredPairs = [
        //   ['idFormateur' => 6, 'idProjet' => 7],
        // ];
        $ignoredPairs = $tableignore->map(function ($item) {
            return [
                'idFormateur' => $item->idFormateur,
                'idProjet' => $item->idProjet
            ];
        })->toArray();

        $dateSeance = [];

        foreach ($chevauchement as $conflit) {
            $conflitsArray[] = $conflit->idProjet;
            $dateSeance[] = $conflit->dateSeance;
        }
        // dd($dateSeance);

        $projets = DB::table('projets')
            ->select('projets.idProjet', 'idSalle', 'projets.idCustomer', 'project_forms.idFormateur', 'customers.customerName', 'projets.project_title', 'projets.project_description', 'users.name', 'users.firstName', 'seances.dateSeance', 'seances.heureDebut', 'seances.heureFin')
            ->join('project_forms', 'project_forms.idProjet', 'projets.idProjet')
            ->join('customers', 'customers.idCustomer', 'projets.idCustomer')
            ->join('users', 'users.id', 'project_forms.idFormateur')
            ->join('seances', 'seances.idProjet', 'projets.idProjet')
            ->whereIn('seances.dateSeance', $dateSeance)
            ->whereIn('projets.idProjet', $conflitsArray);

        foreach ($ignoredPairs as $pair) {
            $projets->where(function ($query) use ($pair) {
                $query->where('project_forms.idFormateur', '!=', $pair['idFormateur'])
                    ->orWhere('projets.idProjet', '!=', $pair['idProjet']);
            });
        }

        $projets->whereNotIn('project_forms.idFormateur', array_column($ignoredPairs, 'idFormateur'))
            ->whereNotIn('projets.idProjet', array_column($ignoredPairs, 'idProjet'));

        $projets = $projets->get();

        $projets->each(function ($projet) {
            $projet->formattedDate = \Carbon\Carbon::parse($projet->dateSeance)->translatedFormat('l, d F Y');
        });

        $duplicates = $projets->groupBy('idFormateur')->filter(function ($group) {
            return $group->count() > 1;
        });

        $filteredDuplicates = $duplicates->filter(function ($group) use ($idCfp) {
            $idCustomersInGroup = $group->pluck('idCustomer');
            return $idCustomersInGroup->contains($idCfp);
        });

        return $filteredDuplicates->isEmpty() ? $duplicates : $filteredDuplicates;
    }


    public function ignoredConflitLieu($id, $idProjet)
    {
        $existing = DB::table('ignoredConflitLieu')
            ->where('idSalle', $id)
            ->where('idProjet', $idProjet)
            ->first();

        if ($existing) {
            return redirect()->back()->with('error', 'Ce conflit a déjà été ignoré.');
        }

        DB::table('ignoredConflitLieu')->insert([
            'idSalle' => $id,
            'idProjet' => $idProjet
        ]);

        return redirect()->back()->with('success', 'Conflit ignoré avec succès.');
    }


    public function ignoredConflitFormateur($id, $idProjet)
    {
        $existing = DB::table('ignoredConflitFormateur')
            ->where('idFormateur', $id)
            ->where('idProjet', $idProjet)
            ->first();

        if ($existing) {
            return redirect()->back()->with('error', 'Ce conflit de formateur a déjà été ignoré.');
        }

        DB::table('ignoredConflitFormateur')->insert([
            'idFormateur' => $id,
            'idProjet' => $idProjet
        ]);

        return redirect()->back()->with('success', 'Conflit de formateur ignoré avec succès.');
    }


    public function totalconflits()
    {
        $conflitsLieux = $this->getConflitsLieux()->count();
        $conflitsFormateurs = $this->getConflitsFormateurs()->count();
        $totalconflits = $conflitsLieux + $conflitsFormateurs;
        return $totalconflits;
    }

    public function index()
    {
        $idCfp = Auth::user()->id;
        $conflitsLieux = $this->getConflitsLieux();
        $conflitsFormateurs = $this->getConflitsFormateurs();
        return view('CFP.conflits.index', compact('conflitsLieux', 'conflitsFormateurs', 'idCfp'));
    }

    public function conflitsLieu()
    {
        $idCfp = Auth::user()->id;
        $conflitsLieux = $this->getConflitsLieux();
        // dd($conflitsLieux);
        return view('CFP.conflits.conflitsLieu', compact('conflitsLieux', 'idCfp'));
    }

    public function conflitsFormateur()
    {
        $idCfp = Auth::user()->id;
        $conflitsFormateurs = $this->getConflitsFormateurs();
        // dd($conflitsFormateurs);
        return view('CFP.conflits.conflitsFormateur', compact('conflitsFormateurs', 'idCfp'));
    }
}
