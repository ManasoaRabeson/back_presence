<?php

namespace App\Http\Controllers;

use App\Http\Requests\BankAcountRequest;
use Illuminate\Http\Request;
use App\Models\BankAcount;
use App\Models\Customer;
use Exception;
use Illuminate\Support\Facades\DB;

class BankAccountController extends Controller
{
    public function index()
    {
        $accounts = DB::table('bankacounts')
            ->join('ville_codeds', 'bankacounts.ba_idPostal', 'ville_codeds.id')
            ->select('bankacounts.id as idAcount', 'bankacounts.*', 'ville_codeds.*')
            ->where('ba_idCustomer', Customer::idCustomer())
            ->get();

        $vl = DB::table('ville_codeds as vc')
            ->orderBy('vc.vi_code_postal', 'asc')->get();
        return view('CFP.bankCard.index', compact(['accounts', 'vl']));
    }

    public function show($id)
    {
        $account = BankAcount::find($id);

        if (!$account) {
            return response()->json(['message' => 'Account not found'], 404);
        }

        return response()->json($account);
    }

    public function store(BankAcountRequest $request)
    {
        $validated = $request->validated();

        try {
            DB::beginTransaction();
            BankAcount::create($validated);
            DB::commit();
            return response()->json(["success" => "Compte bancaire ajouté avec succès."]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(["error" => "Erreur inconnue ! " . $e->getMessage()], 500);
        }
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'ba_account_number' => 'required|unique:bankacounts,ba_account_number,' . $id,
            'ba_name' => 'required|string',
            'ba_idPostal' => 'required|string',
            'ba_quartier' => 'nullable|string',
            'ba_titulaire' => 'required|string',
        ]);

        try {
            $bankAccount = BankAcount::findOrFail($id);
            $bankAccount->update($validated);

            return redirect()->back()->with('success', 'Compte bancaire mis à jour avec succès.');
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Erreur lors de la mise à jour : ' . $e->getMessage()]);
        }
    }


    public function destroy($id)
    {
        try {
            $bankAccount = BankAcount::findOrFail($id);
            $bankAccount->delete();

            return redirect()->back()->with('success', 'Compte bancaire supprimé avec succès.');
        } catch (\Exception $e) {
            return response()->json(["error" => "Erreur lors de la suppression : " . $e->getMessage()], 500);
        }
    }
}
