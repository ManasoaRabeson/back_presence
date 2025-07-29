<?php

namespace App\Http\Controllers;

use App\Http\Requests\MobileMoneyAcountRequest;
use App\Models\Customer;
use App\Models\MobileMoneyAcount;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MobileMoneyAccountController extends Controller
{
    public function show($id)
    {
        $account = MobileMoneyAcount::find($id);

        if (!$account) {
            return response()->json(['message' => 'Account not found'], 404);
        }

        return response()->json($account);
    }

    public function store(MobileMoneyAcountRequest $request)
    {
        $validated = $request->validated();

        try {
            DB::beginTransaction();
            MobileMoneyAcount::create($validated);
            DB::commit();
            return response()->json(["success" => "Compte mobile money ajouté avec succès."]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(["error" => "Erreur inconnue ! " . $e->getMessage()], 500);
        }
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'mm_phone' => 'required|unique:mobilemoneyacounts,mm_phone,' . $id,
            'mm_operateur' => 'required|string',
            'mm_titulaire' => 'required|string',
        ]);

        try {
            $account = MobileMoneyAcount::findOrFail($id);
            $account->update($validated);

            return redirect()->back()->with('success', 'Compte mobile money mis à jour avec succès.');
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Erreur lors de la mise à jour : ' . $e->getMessage()]);
        }
    }


    public function destroy($id)
    {
        try {
            $account = MobileMoneyAcount::findOrFail($id);
            $account->delete();

            return redirect()->back()->with('success', 'Compte mobile money supprimé avec succès.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Ce compte mobile money est rattaché à des paiements et ne peut pas être supprimé.');
            // return response()->json(["error" => "Erreur lors de la suppression : " . $e->getMessage()], 500);
        }
    }
}
