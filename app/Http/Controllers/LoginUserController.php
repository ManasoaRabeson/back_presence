<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Providers\RouteServiceProvider;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class LoginUserController extends Controller
{

    public function login(Request $request)
    {
        if (!Auth::attempt($request->only('email', 'password'))) {
            return response()->json(['error' => 'Identifiants incorrects'], 401);
        } else {
            // Si l'utilisateur est authentifié, créer une session partagée
            session(['user_id' => Auth::user()->id]);
            
            $req = DB::table('role_users')
                ->select('role_id', 'user_id', 'isActive', 'user_is_deleted')
                ->join('users', 'users.id', 'role_users.user_id')
                ->where('hasRole', '=', 1)
                ->where('user_id', '=', Auth::user()->id)
                ->get();

            foreach ($req as $r) {
                if ($r->isActive == 1 && $r->user_is_deleted == 0) {
                    if ($r->role_id == 3 || $r->role_id == 8) {
                        $email = User::where('email', 'rktsoandry@gmail.com')->pluck('email');
                        Session::put('email_google', $email);
                        return redirect(RouteServiceProvider::HOME);
                    } elseif ($r->role_id == 6) {
                        return redirect(RouteServiceProvider::HOMEETP);
                    } elseif ($r->role_id == 4) {
                        return redirect(RouteServiceProvider::HOMEEMP);
                    } elseif ($r->role_id == 5) {
                        return redirect(RouteServiceProvider::HOMEFORM);
                    } elseif ($r->role_id == 7) {
                        return redirect(RouteServiceProvider::HOMEFORMINTERN);
                    } elseif ($r->role_id == 1) {
                        return redirect(RouteServiceProvider::HOMESADMIN);
                    } elseif ($r->role_id == 10) {
                        return redirect(RouteServiceProvider::HOMEPARTICULIER);
                    } else {
                        abort(403);
                    }
                } else {
                    Auth::logout();
                    return view('errors.403');
                }
            }
        }
    }
}
