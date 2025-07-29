<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class LoginController extends Controller
{
    public function login(Request $req){
        // Validation
        $req->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        $user = User::where('email', $req->email);

        if($user->exists()){
            if(Hash::check($req->password, $user->first()->password)){
                // $country = DB::table('countriess as cnt')
                //     ->select('cnt.name as country_name', 'cnt.code as country_code', 'cr.code as currency_code', 'cr.unit as currency_unit', 'cnt.id_nif_name', 'nf.name as nif_name', 'nf.description as nif_description', 'cntf.id_stat_name', 'stn.name as stat_name')
                //     ->join('currencies as cr', 'cnt.id_currency', 'cr.id')
                //     ->join('nif_names as nf', 'cnt.id_nif_name', 'nf.id')
                //     ->join('country_fulls as cntf', 'cnt.id', 'cntf.id')
                //     ->join('stat_names as stn', 'cntf.id_stat_name', 'stn.id')
                //     ->where('cnt.id', 2);
                $country = DB::table('customers as cst')
                ->select('cnt.name as country_name', 'cnt.code as country_code', 'cr.code as currency_code', 'cr.unit as currency_unit', 'cnt.id_nif_name', 'nf.name as nif_name', 'nf.description as nif_description', 'cntf.id_stat_name', 'stn.name as stat_name')
                ->join('countriess as cnt', 'cst.id_country', 'cnt.id')
                ->join('currencies as cr', 'cnt.id_currency', 'cr.id')
                ->join('nif_names as nf', 'cnt.id_nif_name', 'nf.id')
                ->join('country_fulls as cntf', 'cnt.id', 'cntf.id')
                ->join('stat_names as stn', 'cntf.id_stat_name', 'stn.id');

                if($country->exists()){
                    $token = $user->first()->createToken('formafusion-token')->plainTextToken;
                    $role = DB::table('role_users')
                    ->select('role_id', 'user_id')
                    ->where('user_id', '=', $user->first()->id)
                    ->first();
                    return response()->json([
                        'status' => 200,
                        'message' => 'OK',
                        'token' => $token,
                        'user' => $user->first(),
                        'role' => $role,
                        'setting' => $country->first()
                    ]);
                }else{
                    return response()->json([
                        'status' => 400,
                        'message' => 'Erreur inconnue !'
                    ]);
                }
            }else{
                return response()->json([
                    'status' => 400,
                    'message' => "Votre mot de passe est incorrect !"
                ]);
            }
        }else{
            return response()->json([
                'status' => 404,
                'message' => "Utilisateur introuvable !"
            ]);
        }
    }

    public function logout(Request $req){
        if($req->user()->exists()){
            $req->user()->currentAccessToken()->delete();

            return response()->json([
                'status' => 200,
                'message' => "Déconnexion réussie"
            ]);
        }else{
            return response()->json([
                'status' => 404,
                'message' => "Utilisateur introuvable !"
            ]);
        }
    }

    
}
