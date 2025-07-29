<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AgendaSuperAdminController extends Controller
{
    //Agenda SA
    public function agenda()
    {
        return view('superAdmin.agenda.index');
    }
}
