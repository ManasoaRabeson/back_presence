<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DevisesController extends Controller
{
    /**
     * Method for storing currency
     * 
     * @param Request $request
     */
    public function store(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'devise' => 'required|string|max:200|unique:devises,devise'
            ]);

            $devise = DB::table('devises')->insert([
                'devise' => $validatedData['devise']
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Currency added successfully.'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e instanceof \Illuminate\Validation\ValidationException
                    ? 'This currency already exists.'
                    : 'An error occurred while adding the currency.'
            ], 422);
        }
    }
}
