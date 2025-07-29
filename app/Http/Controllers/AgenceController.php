<?php

namespace App\Http\Controllers;

use App\Http\Requests\AgenceStoreRequest;
use App\Models\Agence;
use App\Models\Customer;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AgenceController extends Controller
{
    public function allAgences()
    {
        $ags = DB::table('v_liste_agences')
            ->select('idAgence', 'ag_name', 'idCustomer', 'idVilleCoded', 'idVille', 'ville', 'ville_name_coded as ville_name', 'vi_code_postal', 'customer_name')
            ->where('idCustomer', Customer::idCustomer())
            ->orderBy('ag_name', 'asc');

        return $ags;
    }

    public function allVilleCodeds()
    {
        $vcs = DB::table('ville_codeds')->select('id', 'ville_name', 'vi_code_postal');
        return $vcs;
    }

    public function index()
    {
        $agences = $this->allAgences()->get();
        $villeCodeds = $this->allVilleCodeds()->get();

        switch (Customer::typeCustomer()) {
            case 1:
                return view('CFP.agences.index', compact('agences', 'villeCodeds'));
                break;
            case 2:
                return view('ETP.agences.index', compact('agences', 'villeCodeds'));
                break;
            default:
                return abort(404);
                break;
        }
    }

    public function store(AgenceStoreRequest $request)
    {
        Agence::create($request->validated());
        return back()->with('success', 'Succès');
    }

    public function destroy($id)
    {
        $agence = Agence::find($id);

        if ($agence)
            try {
                $agence->delete();
                return back()->with('success', 'Succès');
            } catch (Exception $e) {
                return back()->with('error', 'Suppression impossible !');
            }

        else
            return back()->with('error', 'Agence introuvable !');
    }
}
