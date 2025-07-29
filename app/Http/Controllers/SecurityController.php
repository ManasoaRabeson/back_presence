<?php

namespace App\Http\Controllers;

use App\Http\Requests\ChangePasswordRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class SecurityController extends Controller
{
    public function index()
    {
        return view('CFP.security.index');
    }
    public function indexEtp()
    {
        return view('ETP.security.index');
    }

    public function changePassword(ChangePasswordRequest $request)
    {
        Auth::user()->update([
            'password' => Hash::make($request->new_password)
        ]);

        return redirect()->back()->with('status', 'Mot de passe changé avec succès.');
    }
}
