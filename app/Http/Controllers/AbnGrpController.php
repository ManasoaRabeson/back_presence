<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Payment;
use NumberToWords\NumberToWords;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Laravelcm\Subscriptions\Models\Plan;
use Laravelcm\Subscriptions\Models\Subscription;

class AbnGrpController extends Controller
{

    public function index()
    {
        $plans = Plan::where('user_type', 'entreprise')->with('features')->get();
        return view('GRP.abonnements.index', compact('plans'));
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

        $entreprise = DB::table('v_list_etp_groupeds')->where('idEntrepriseParent', Auth::user()->id)->get();
        return view('GRP.abonnements.recapPage', compact('plan', 'monthlyPrice', 'annualSavings', 'annualPrice', 'id_order', 'entreprise'))->with(['pay' => $payment_iframe_html]);
    }

    public function subscribe(Request $request, $planId)
    {
        //maka UserId
        $userId = $request->input('userId');
        $user = Customer::findOrFail($userId);
        $plan = Plan::findOrFail($planId);

        // Check
        $existingSubscription = $user->planSubscriptions()->first();

        if ($existingSubscription) {
            $existingSubscription->delete();
        }
        $user->newPlanSubscription('main', $plan);

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

        return redirect()->route('grp.abonnement.forfait', $planId)->with('success', 'Vous avez souscrit au plan avec succès.');
    }

    public function showSubscriptions()
    {
        $subscriptions = Subscription::with([
            'customer',
            'customer.etp_groupeds'
        ])
            ->whereHas('customer.etp_groupeds', function ($query) {
                $query->where('idEntrepriseParent', Customer::idCustomer());
            })
            ->get();

        $payments = Payment::with([
            'customer',
            'customer.etp_groupeds'
        ])
            ->whereHas('customer.etp_groupeds', function ($query) {
                $query->where('idEntrepriseParent', Customer::idCustomer());
            })
            ->get();
        return view('GRP.abonnements.forfait', compact('subscriptions', 'payments'));
    }
}
