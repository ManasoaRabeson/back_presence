<?php

namespace App\Http\Controllers;

use App\Services\CustomerOther\Employe\StoreService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class EtpInformalEmployeController extends Controller
{
    public function index(StoreService $emp){
        $employes = $emp->getAll();
        return view('Etp_informals.employes.index', compact('employes'));
    }

    public function store(Request $req, StoreService $emp){
        $validate = Validator::make($req->all(), [
            'emp_name' => 'required|min:2|max:200',
            'email' => 'required|min:2|max:200|email|unique:users,email'
        ]);
        
        if($validate->fails()){
            return back()->with('error', $validate->messages());
        }

        $emp->store($req);

        return back()->with('success', 'Succès');
    }
}
