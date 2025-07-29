<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class ProgrammeController extends Controller
{
    public function store(Request $req, $idModule)
    {
        $validate = Validator::make($req->all(), [
            'program_title' => 'required|min:2|max:250',
            'program_description' => 'required|min:2'
        ]);

        // dd($req->all());

        if ($validate->fails()) {
            return back()->with('error', 'Erreur');
        } else {
            $insert = DB::table('programmes')->insert([
                'program_title' => $req->program_title,
                'program_description' => $req->program_description,
                'idModule' => $idModule
            ]);

            if ($insert) {
                return back()->with('success', 'Succès');
            } else {
                return back()->with('error', 'Erreur inconnue !');
            }
        }
    }

    public function getProgramme($idModule)
    {
        $programmes = DB::table('programmes')->select('idProgramme', 'program_title', 'program_description')->where('idModule', $idModule)->get();

        return response()->json(['programmes' => $programmes]);
    }

    public function destroy($idProgramme)
    {
        $delete = DB::table('programmes')->where('idProgramme', $idProgramme)->delete();

        if ($delete) {
            return response()->json(["success" => "Suppression avec succès"]);
        } else {
            return response()->json(["error" => "Erreur inconnue !"]);
        }
    }

    public function edit($idProgramme)
    {
        $programme = DB::table('programmes')->select('idProgramme', 'program_title', 'program_description')->where('idProgramme', $idProgramme)->first();
        return response()->json(['programme' => $programme]);
    }

    public function update(Request $req, $idProgramme)
    {
        $validate = Validator::make($req->all(), [
            'program_title' => 'required|min:2|max:250',
            'program_description' => 'required|min:2'
        ]);

        if ($validate->fails()) {
            return response()->json(['error' => $validate->messages()]);
        } else {
            $update = DB::table('programmes')->where('idProgramme', $idProgramme)->update([
                'program_title' => $req->program_title,
                'program_description' => $req->program_description,
                'idProgramme' => $idProgramme
            ]);

            if ($update) {
                return response()->json(['success' => 'Succès']);
            } else {
                return response()->json(['error' => 'Erreur inconnue !']);
            }
        }
    }
}
