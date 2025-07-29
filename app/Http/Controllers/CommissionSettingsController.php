<?php

namespace App\Http\Controllers;

use App\Models\CommissionSettings;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CommissionSettingsController extends Controller
{
    /**
     * Method leading to the view of the commission settings (v1)
     */
    // public function index()
    // {
    //     $extends_containt = $this->determineLayout();
    //     $commissionSettings = CommissionSettings::all();
    //     return view('TestingCenter.commission-settings.index', compact('extends_containt', 'commissionSettings'));
    // }

    /**
     * Method leading to the view of the commission settings (v2)
     */
    public function index()
    {
        $extends_containt = $this->determineLayout();
        $commissionSettings = CommissionSettings::all();
        $currencies = DB::table('devises')->pluck('devise'); // Fetch all currencies

        return view(
            'TestingCenter.commission-settings.index',
            compact(
                'extends_containt',
                'commissionSettings',
                'currencies',
            )
        );
    }

    /**
     * Method that determine the layout based on the authentified user
     */
    private function determineLayout()
    {
        if (!Auth::check()) {
            return null;
        }

        $user = Auth::user();
        $layoutMap = [
            'Formateur' => 'layouts.masterForm',
            'Formateur interne' => 'layouts.masterFormInterne',
            'Particulier' => 'layouts.masterParticulier',
            'EmployeCfp' => 'layouts.masterEmpCfp',
            'Employe' => 'layouts.masterEmp',
            'EmployeEtp' => 'layouts.masterEmp',
            'Cfp' => 'layouts.master',
            'Admin' => 'layouts.masterAdmin',
            'SuperAdmin' => 'layouts.masterAdmin',
            'Referent' => 'layouts.masterEtp'
        ];

        foreach ($layoutMap as $role => $layout) {
            if ($user->hasRole($role)) {
                return $layout;
            }
        }

        return null;
    }

    /**
     * Method that store the commission setting (v2)
     * 
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'payment_type' => ['required', 'string', 'in:cb,cheque,virement'],
                'commission_rate' => ['required', 'numeric', 'min:0', 'max:100'],
                'currency' => ['required', 'string', 'max:10']
            ]);

            $setting = CommissionSettings::create($validatedData);

            // return response()->json([
            //     'success' => true,
            //     'setting' => $setting
            // ]);

            return redirect()->route('commission-settings.index')
                ->with('success', __('Paramètre créé avec succès.'));
        } catch (QueryException $e) {
            // Check if it's a duplicate entry error
            if ($e->getCode() === '23000') { // MySQL duplicate entry error code
                return redirect()->route('commission-settings.index')
                    ->with('error', __('Une commission avec ce type de paiement et cette devise existe déjà.'));
            }

            // Handle other database errors
            return redirect()->route('commission-settings.index')
                ->with('error', __('Une erreur est survenue lors de la création de la commission.'));
        }
    }

    /**
     * Method to fetch a specific commission setting for editing
     * 
     * @param $id (id of the commission setting)
     */
    public function edit($id)
    {
        $commissionSetting = CommissionSettings::findOrFail($id);
        return response()->json($commissionSetting);
    }

    /**
     * Method for updating the commission setting (v2)
     * 
     * @param $request, $id (id of the commission setting)
     */
    public function update(Request $request, $id)
    {
        try {
            $commissionSetting = CommissionSettings::findOrFail($id);

            $validatedData = $request->validate([
                'payment_type' => ['string', 'in:cb,cheque,virement'],
                'commission_rate' => ['numeric', 'min:0', 'max:100'],
                'currency' => ['string', 'max:10']
            ]);

            $commissionSetting->update($validatedData);
            // return response()->json([
            //     'success' => true,
            //     'setting' => $commissionSetting
            // ]);

            return redirect()->route('commission-settings.index')
                ->with('success', __('Paramètre mise à jour avec succès.'));
        } catch (QueryException $e) {
            // Check if it's a duplicate entry error
            if ($e->getCode() === '23000') {
                return redirect()->route('commission-settings.index')
                    ->with('error', __('Une commission avec ce type de paiement et cette devise existe déjà.'));
            }

            // Handle other database errors
            return redirect()->route('commission-settings.index')
                ->with('error', __('Une erreur est survenue lors de la mise à jour de la commission.'));
        }
    }

    /**
     * Method for deleting a commission setting
     * 
     * @param $id (id of the commission setting)
     */
    public function destroy($id)
    {
        $commissionSetting = CommissionSettings::findOrFail($id);
        $commissionSetting->delete();
        return response()->json(['success' => true]);
        // return redirect()->route('commission-settings.index')->with('success', __('Paramètre supprimé avec succès.'));
    }
}
