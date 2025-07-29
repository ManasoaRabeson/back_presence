<?php

namespace App\Http\Controllers;

use App\Models\CommissionsReceived;
use App\Models\CreditsPacks;
use App\Models\CreditsPayment;
use App\Models\CreditsWallet;
use App\Services\Qcm\QcmNavigationService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class CreditsPacksController extends Controller
{
    # Services part added 18-02-2025
    private QcmNavigationService $navigationService;

    public function __construct(
        QcmNavigationService $navigationService
    ) {
        $this->navigationService = $navigationService;
    }
    # Services part added 18-02-2025

    /**
     * Fonction menant à l'index des types de pack de crédits (v3)
     * 
     * @param $request
     */
    public function index_credits_packs(Request $request): View
    {
        $extends_containt = $this->navigationService->determineLayout(); // Variable pour stocker les extends selon l'utilisateur connecté
        $all_credits_packs = CreditsPacks::all();
        $devises = DB::table('devises')->get(); // Utilisation de Query Builder

        if ($request->ajax()) {
            return view('TestingCenter.creditspacks.partials.index-creditsPacksPartial', compact('extends_containt', 'all_credits_packs', 'devises'));
        }

        return view('TestingCenter.indexTCenter.index_credits_packs', compact(
            'extends_containt',
            'all_credits_packs',
            'devises'
        ));
    }

    /**
     * Fonction menant à l'index pour les packs de crédits à l'achat (v2)
     */
    public function index_buy_credits_pack()
    {
        $extends_containt = $this->navigationService->determineLayout(); // Variable pour stocker les extends selon l'utilisateur connecté

        $user_auth = Auth::user();

        // $creditPacks = CreditsPacks::all();
        $creditPacks = CreditsPacks::where('is_active', 1)->get(); # On obtient seulement les packs de crédits qui sont actifs  
        return view('TestingCenter.indexTCenter.index_buycredits_packs', compact(
            'extends_containt',
            'creditPacks',
            'user_auth',
        ));
    }

    /**
     * Fonction pour sauvegarder un pack de crédits
     * 
     * @param $request
     */
    public function store_credits_packs(Request $request)
    {
        try {
            $request->validate([
                'typePack' => 'required|string|max:255',
                'descriptionPack' => 'required|string',
                'creditsAmount' => 'required|numeric',
                'packPrice' => 'required|numeric',
                'currency' => 'required|exists:devises,idDevise',
                'is_active' => 'required|boolean', // Add validation for is_active
            ]);

            // Récupérer la devise avec Query Builder
            $devise = DB::table('devises')
                ->where('idDevise', $request->currency)
                ->first();

            if (!$devise) {
                throw new \Exception('Devise non trouvée');
            }

            CreditsPacks::create([
                'type_pack' => $request->typePack,
                'description_pack' => $request->descriptionPack,
                'credits' => $request->creditsAmount,
                'pack_price' => $request->packPrice,
                'currency' => $devise->devise,
                'is_active' => $request->is_active, // Save is_active status
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Pack de crédits créé avec succès',
                'packs' => CreditsPacks::all()
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la création du pack : ' . $e->getMessage()
            ], 422);
        }
    }

    /**
     * Fonction pour mettre à jour un pack de crédits
     * 
     * @param $id (id du pack de crédits), $request
     */
    public function update_credits_packs($id, Request $request)
    {
        try {
            $request->validate([
                'typePack' => 'required|string|max:255',
                'descriptionPack' => 'required|string',
                'creditsAmount' => 'required|numeric',
                'packPrice' => 'required|numeric',
                'currency' => 'required|exists:devises,idDevise',
                'is_active' => 'required|boolean', // Add validation for is_active
            ]);

            // Récupérer la devise avec Query Builder
            $devise = DB::table('devises')
                ->where('idDevise', $request->currency)
                ->first();

            if (!$devise) {
                throw new \Exception('Devise non trouvée');
            }

            $pack = CreditsPacks::findOrFail($id);
            $pack->update([
                'type_pack' => $request->typePack,
                'description_pack' => $request->descriptionPack,
                'credits' => $request->creditsAmount,
                'pack_price' => $request->packPrice,
                'currency' => $devise->devise,
                'is_active' => $request->is_active, // Update is_active status
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Pack de crédits mis à jour avec succès',
                'packs' => CreditsPacks::all()
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la mise à jour du Pack de crédits : ' . $e->getMessage()
            ], 422);
        }
    }

    /**
     * Fonction pour supprimer un pack de crédit
     * 
     * @param $id (id du pack de crédits)
     */
    public function delete_credits_packs($id)
    {
        try {
            $pack = CreditsPacks::findOrFail($id);
            $pack->delete();

            return response()->json([
                'success' => true,
                'message' => 'Pack de crédits supprimé avec succès',
                'packs' => CreditsPacks::all()
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la suppression du Pack de crédits : ' . $e->getMessage()
            ], 422);
        }
    }

    /**
     * Fonction menant à la vue pour faire un achat pour un pack (v2)
     * 
     * @param $id (id du pack de crédits)
     */
    public function recapPurchase($id)
    {
        $creditPack = CreditsPacks::findOrFail($id);
        $extends_containt = $this->navigationService->determineLayout(); // Variable pour stocker les extends selon l'utilisateur connecté
        $user_auth = Auth::user();

        // Paiement en ligne
        $lastPayment = CreditsPayment::orderBy('idCreditPayment', 'desc')->first();
        if ($lastPayment) {
            $lastIdPayment = (int) str_replace('PAY-', '', $lastPayment->reference);
            $reference = 'PAY-' . ($lastIdPayment + 1);
        } else {
            $reference = 'PAY-1';
        }

        // Configuration pour BNI
        $amount = $creditPack->pack_price * 100;
        // $currency = 'MGA';
        $currency = $creditPack->currency;
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
                'order_id' => $reference,
                'amount' => $amount,
                'currency' => $currency,
            ],
        ];

        $complete_array_message = json_encode($complete_array_message);
        $url = 'https://mypay.bni.mg/qp_api/qp_api.php';
        $options = [
            'http' => [
                'header' => [
                    'Content-type: application/x-www-form-urlencoded',
                    'Authorization: Basic ' . base64_encode('bni_6ydkl4:Mjd67$yebdf9gdhHYT#w2uewbM63Rts2'),
                    'Cache-Control: no-cache'
                ],
                'method' => 'POST',
                'content' => http_build_query(['posted_data' => $complete_array_message]),
            ],
        ];

        $context = stream_context_create($options);
        $response = file_get_contents($url, false, $context);

        if ($response === false) {
            return back()->with('error', 'Échec de la requête de paiement.');
        }

        $response_decoded = json_decode($response, true);
        $payment_iframe_html = $response_decoded['answer']['payment_iframe'] ?? 'Failed to load the payment form. Please try again later.';

        return view('TestingCenter.creditspacks.recap', compact('creditPack', 'reference', 'extends_containt', 'user_auth'))
            ->with(['pay' => $payment_iframe_html]);
    }

    /**
     * Fonction pour procéder à l'achat avec prise en compte des commissions (v3)
     * 
     * @param $request, $id (id du pack)
     */
    public function processPurchase(Request $request, $id)
    {
        $request->validate([
            'payment_method' => 'required|in:cb,virement,cheque',
            'reference' => 'required'
        ]);

        $user = Auth::user();
        $creditPack = CreditsPacks::findOrFail($id);

        // Create payment record
        $payment = CreditsPayment::create([
            'user_id' => $user->id,
            'pack_credits_id' => $id,
            'reference' => $request->reference,
            'amount_paid' => $creditPack->pack_price,
            'currency' => $creditPack->currency,
            'payment_type' => $request->payment_method,
            'status' => $request->payment_method === 'cb' ? 'pending' : 'paid'
        ]);

        // Try to find a referrer (if applicable) (we have put the referrer to null for now because we don't know who received the commission)
        $referrer = null;
        // if ($user->referrer) {
        //     $referrer = $user->referrer;
        // }

        // Calculate and save commission
        CommissionsReceived::calculateAndSaveCommission($payment, $referrer);

        // If payment is by check or bank transfer, credit immediately
        if (in_array($request->payment_method, ['cheque', 'virement'])) {
            CreditsWallet::crediter($user->id, $creditPack->credits);

            if (Auth::check()) {
                $user = Auth::user();
                if ($user->hasRole('Referent')) {
                    return redirect()->route('credits.history.etp')
                        ->with('success', 'Votre compte a été crédité de ' . $creditPack->credits . ' crédits.');
                } elseif ($user->hasRole('Particulier')) {
                    return redirect()->route('credits.history.particulier')
                        ->with('success', 'Votre compte a été crédité de ' . $creditPack->credits . ' crédits.');
                }
            }
        }

        // For credit card payments, redirect to history page
        return redirect()->route('credits.history')
            ->with('info', 'Votre paiement est en cours de traitement.');
    }

    /**
     * Fonction pour afficher l'historique de payement après avoir (v3)
     * acheter le crédit
     * 
     * @param $request
     */
    public function history(Request $request)
    {
        $user = Auth::user();

        $extends_containt = $this->navigationService->determineLayout(); // Variable pour stocker les extends selon l'utilisateur connecté

        // Récupérer les paramètres de filtrage de la requête
        $startDate = $request->input('start_date') ?
            Carbon::parse($request->input('start_date')) : null;
        $minAmount = $request->input('min_amount');
        $maxAmount = $request->input('max_amount');

        $creditPayment = new CreditsPayment();

        $payments = $creditPayment->getAllTransactionList(
            $startDate,
            $minAmount,
            $maxAmount
        );;

        return view('TestingCenter.credits_payments.index_credits_payments', [
            'extends_containt' => $extends_containt,
            'transactions' => $payments->original['transactions'] ?? [],
            'total_transactions' => $result->original['total_transactions'] ?? 0,
            'start_date' => $request->input('start_date'),
            'min_amount' => $request->input('min_amount'),
            'max_amount' => $request->input('max_amount')
        ]);
    }

    /**
     * Pour l'administrateur : valider un paiement par CB
     * 
     * @param $paymentId
     */
    // public function validatePayment($paymentId)
    // {
    //     $payment = CreditsPayment::findOrFail($paymentId);

    //     if ($payment->status !== 'pending') {
    //         return back()->with('error', 'Ce paiement ne peut pas être validé.');
    //     }

    //     $payment->status = 'paid';
    //     $payment->save();

    //     // Créditer le compte utilisateur
    //     CreditsWallet::crediter($payment->user_id, $creditPack->credits);

    //     return back()->with('success', 'Paiement validé et compte crédité.');
    // }
}
