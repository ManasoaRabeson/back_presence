<?php

namespace App\Http\Controllers;
use Illuminate\Contracts\View\View;

class CfpAnnuaireController
{
    public function index(): View
    {
        return view('CFP.annuaires.agenda');
    }
}
