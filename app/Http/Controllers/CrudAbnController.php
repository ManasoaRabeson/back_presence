<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Laravelcm\Subscriptions\Models\Feature;
use Laravelcm\Subscriptions\Models\Plan;
use Laravelcm\Subscriptions\Models\Subscription;

class CrudAbnController extends Controller
{
    public function index()
    {
        $plans = Plan::all();
        $features = Feature::with('plan')->get();
        return view('superAdmin.abonnements.index', compact('plans', 'features'));
    }

    public function card()
    {
        $plans = Plan::with('features')->get();
        return view('superAdmin.abonnements.card', compact('plans'));
    }

    public function create()
    {
        $plans = Plan::all();
        return view('superAdmin.abonnements.pages.create', compact('plans'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'type' => 'required|in:plan,feature',
        ]);

        if ($request->type === 'plan') {
            $request->validate([
                'name' => 'required',
                'user_type' => 'required',
                'price' => 'required|numeric',
                'invoice_period' => 'required|integer',
                'invoice_interval' => 'required',
                'currency' => 'required',
            ]);

            Plan::create($request->all());
        } elseif ($request->type === 'feature') {
            $request->validate([
                'plan_id' => 'required|exists:plans,id',
                'name' => 'required',
                'value' => 'required',
                'slug' => 'required|unique:features,slug',
            ]);

            Feature::create($request->all());
        }

        return redirect()->route('crudAbn.index');
    }


    public function show($id)
    {
        $plan = Plan::find($id);
        $features = Feature::where('plan_id', $id)->get();

        return view('crudAbn.show', compact('plan', 'features'));
    }

    public function editPlan($id)
    {
        $plans = Plan::find($id);

        return view('superAdmin.abonnements.pages.editPlan', compact('plans'));
    }

    public function editFeature($id)
    {
        $features = Feature::with('plan')->find($id);
        $plans = Plan::all();
        return view('superAdmin.abonnements.pages.editFeature', compact('features', 'plans'));
    }


    public function update(Request $request, $id)
    {
        $request->validate([
            'type' => 'required|in:plan,feature',
        ]);

        if ($request->type === 'plan') {
            $plan = Plan::find($id);

            $request->validate([
                'name' => 'required',
                'price' => 'required|numeric',
                'invoice_period' => 'required|integer',
                'invoice_interval' => 'required',
                'currency' => 'required',
            ]);

            $plan->update($request->all());
        } elseif ($request->type === 'feature') {
            $feature = Feature::find($id);

            $request->validate([
                'plan_id' => 'required|exists:plans,id',
                'name' => 'required',
                'value' => 'required',
                'slug' => 'required|unique:features,slug,' . $feature->id,
            ]);

            $feature->update($request->all());
        }

        return redirect()->route('crudAbn.index');
    }


    public function destroy($id)
    {
        $plan = Plan::find($id);
        $feature = Feature::find($id);

        if ($plan) {
            $plan->delete();
        } elseif ($feature) {
            $feature->delete();
        }

        return redirect()->route('crudAbn.index');
    }

    public function allAbn()
    {
        $subscriptions = Subscription::with('plan', 'customer')->get();
        // dd($subscriptions);
        return view('superAdmin.abonnements.listAbn', compact('subscriptions'));
    }

    public function change($id)
    {
        $plan = Plan::find(1);
        $subscription = Subscription::findOrFail($id);
        $subscription->changePlan($plan);
        return redirect()->back()->with('status', 'L\'abonnement a été changé en Invité');
    }
}
