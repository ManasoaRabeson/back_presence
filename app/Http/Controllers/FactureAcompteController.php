<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class FactureAcompteController extends Controller
{
    public function index()
    {
        return view('CFP.facturesAcompte.index');
    }
    public function approuveIndex()
    {
        return view('CFP.facturesAcompte.approuveIndex');
    }
    public function creerIndex()
    {
        return view('CFP.facturesAcompte.creerIndex');
    }
}
