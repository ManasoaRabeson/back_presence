<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AgendaFormInterneController extends Controller
{
    public function index()
    {
        return view('formateurInternes.agendas.index');
    }
}
