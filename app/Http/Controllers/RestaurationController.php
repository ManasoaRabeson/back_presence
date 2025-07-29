<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RestaurationController extends Controller
{
    public function store(Request $request){
        $idProjet = $request->idProjet;
        $idRestauration = $request->idRestauration;
        $paidBy = $request->paidBy;

        $this->existRestauration($idProjet, $idRestauration) ? $this->updateRestauration($idProjet, $idRestauration, $paidBy) : $this->addRestauration($idProjet, $idRestauration, $paidBy);
    }

    private function existRestauration($idProjet, $idRestauration){
        $restauration = DB::table('project_restaurations')->where('idProjet', $idProjet)->where('idRestauration', $idRestauration)->exists();

        return $restauration;
    }

    private function addRestauration($idProjet, $idRestauration, $paidBy) {
        try {
            DB::beginTransaction();
            DB::table('project_restaurations')->insert([
                'idProjet' => $idProjet,
                'idRestauration' => $idRestauration,
                'paidBy' => $paidBy
            ]);

            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
            return response()->json(['error' => $e->getMessage()], 500); 
        }

        return response()->json(['success' => 'Succès'], 200);
    }

    private function updateRestauration($idProjet, $idRestauration, $paidBy){
        try {
            DB::beginTransaction();
            DB::table('project_restaurations')
                ->where('idProjet', $idProjet)
                ->where('idRestauration', $idRestauration)
                ->update(['paidBy' => $paidBy]);

            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
            return response()->json(['error' => $e->getMessage()], 500); 
        }

        return response()->json(['success' => 'Succès'], 200);
    }

    public function deleteRestauration(Request $request) {
        try {
            DB::beginTransaction();
            DB::table('project_restaurations')
                ->where('idProjet', $request->idProjet)
                ->where('idRestauration', $request->idRestauration)
                ->delete();
            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
            return response()->json(['error' => $e->getMessage()], 500);
        }
        return response()->json(['success' => 'Succès'], 200);
    }

    public function getRestauration($idProjet)
    {
        $restaurations = DB::table('project_restaurations')
            ->where('idProjet', $idProjet)
            ->get();

        return response()->json($restaurations);
    }
}
