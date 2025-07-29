<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use NumberToWords\NumberToWords;
use App\Models\User;
use App\Models\Customer;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Laravelcm\Subscriptions\Models\Plan;

class AbnEtpController extends Controller
{

    public function index()
    {
        $plans = Plan::where('user_type', 'entreprise')->with('features')->get();
        return view('ETP.abonnements.index', compact('plans'));
    }

    public function recapAbn($id)
    {
        $plan = Plan::with('features')->findOrFail($id);

        $monthlyPrice = $plan->price;
        $annualSavings = ($monthlyPrice * 12) * 0.20;
        $annualPrice = ($monthlyPrice * 12) - $annualSavings;

        //paiement en ligne
        $lastOrder = Payment::orderBy('id', 'desc')->first();
        if ($lastOrder) {
            $lastIdOrder = (int) str_replace('ord_', '', $lastOrder->id_order);
            $id_order = 'ord_' . ($lastIdOrder + 1);
        } else {
            $id_order = 'ord_1';
        }
        $amount = $monthlyPrice * 100;
        $currency = 'MGA';
        $custom_url = 'www.raveloson.com/exemple.php';
        $id_merchant = 'WXro6VZxM2XPKGifqB5l7eFPYV0IQdNV';
        $id_entity = 'Jmf5DehzIXlMeCwiEcInthV4Ikm0ljnz';
        $operator_id = 'R1v5WfplQkrZkU1FKv387IHF8ryWAVjl';
        $remote_user_password = 'QZulqzjuHK5a6IwczPH7lVEPnktLRrN9';
        $complete_array_message = [
            'id_merchant' => $id_merchant,
            'id_entity' => $id_entity,
            'operator_id' => $operator_id,
            'remote_user_password' => $remote_user_password,
            'message' => [
                'operation' => 'load_iframe',
                'custom_url' => $custom_url,
                'order_id' => $id_order,
                'amount' => $amount,
                'currency' => $currency,
            ],
        ];
        $complete_array_message = json_encode($complete_array_message);
        $url = 'https://mypay.bni.mg/qp_api/qp_api.php';
        $options = [
            'http' => [
                'header' => ['Content-type: application/x-www-form-urlencoded', 'Authorization: Basic ' . base64_encode('bni_6ydkl4:Mjd67$yebdf9gdhHYT#w2uewbM63Rts2'), 'Cache-Control: no-cache'],
                'method' => 'POST',
                'content' => http_build_query(['posted_data' => $complete_array_message]),
            ],
        ];
        $context = stream_context_create($options);
        $response = file_get_contents($url, false, $context);
        if ($response === false) {
            echo 'Request failed.';
            exit();
        }
        $response_decoded = json_decode($response, true);
        $payment_iframe_html = $response_decoded['answer']['payment_iframe'] ?? 'Failed to load the payment form. Please try again later.';

        $plan = Plan::with('features')->findOrFail($id);
        return view('ETP.abonnements.recapPage', compact('plan', 'monthlyPrice', 'annualSavings', 'annualPrice', 'id_order'))->with(['pay' => $payment_iframe_html]);
    }

    public function subscribe(Request $request, $planId)
    {
        $authenticatedUser = Customer::idCustomer();

        $user = Customer::findOrFail($authenticatedUser);
        $plan = Plan::findOrFail($planId);

        // Check
        $allsubscriptions = $user->planSubscriptions()->get();
        $existingSubscription = $user->planSubscriptions()->first();

        if ($allsubscriptions->count() >= 2) {
            return redirect()->route('cfp.abonnement.forfait', $planId)->with('success', 'Vous disposez déjà de 2 abonnements. Profitez-en pleinement !');
        }

        if ($existingSubscription) {
            if ($existingSubscription->plan_id == 1 || $existingSubscription->plan_id == 5) {
                $existingSubscription->delete();
                $user->newPlanSubscription('main', $plan);
            } elseif ($existingSubscription && $existingSubscription->ended()) {
                $existingSubscription->delete();
                $user->newPlanSubscription('main', $plan);
            } else {
                $newStartDate = Carbon::parse($existingSubscription->ends_at)->addDay();
                $user->newPlanSubscription('main', $plan, $newStartDate);
            }
        } else {
            $newStartDate = Carbon::now();
            $user->newPlanSubscription('main', $plan, $newStartDate);
        }

        $request->validate(
            [
                'payment_method' => 'required',
                'total_price' => 'required',
                'id_order' => 'required'
            ],
            [
                'payment_method.required' => 'Le mode de paiement est requis.',
                'total_price.required' => 'Le prix total est requis.',
                'id_order.required' => 'id_order inconnue'
            ]
        );

        // Insert payment details
        Payment::create([
            'due_date' => now()->addDays(10),
            'payment_date' => now(),
            'user_id' => $user->idCustomer,
            'payment_method' => $request->input('payment_method'),
            'subscription_name' => $plan->name,
            'total_price' => $request->input('total_price'),
            'id_order' => $request->input('id_order')
        ]);

        return redirect()->route('etp.abonnement.forfait', $planId)->with('success', 'Vous avez souscrit au plan avec succès.');
    }

    public function showSubscriptions()
    {
        $authenticatedUser = Customer::idCustomer();
        $user = Customer::findOrFail($authenticatedUser);
        // All abonnement
        $subscriptions = $user->planSubscriptions()->get();
        // Mon abonnement
        $mysubscriptions = $user->planSubscriptions()->first();

        if ($mysubscriptions && $mysubscriptions->ended()) {
            $nextSubscription = $user->planSubscriptions()->where('starts_at', '>', $mysubscriptions->ends_at)->first();
            if ($nextSubscription) {
                $subscriptions->delete();
            }
        }
        $payments = Payment::where('user_id', $user->idCustomer)->orderBy('id', 'desc')->get();

        return view('ETP.abonnements.forfait', compact('payments', 'subscriptions', 'mysubscriptions'));
    }
}
