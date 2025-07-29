<?php

namespace App\Http\Controllers;

use App\Models\CreditsPayment;
use App\Services\Qcm\QcmNavigationService;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class CreditsPaymentController extends Controller
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
     * Method for getting the list of all transactions (history) (v2)
     * 
     * @param $request
     */
    public function index_credits_payments(Request $request)
    {
        $extends_containt = $this->navigationService->determineLayout(); // Set layout based on user role
        $user_auth = Auth::user();

        // Récupérer les paramètres de filtrage de la requête
        $startDate = $request->input('start_date') ?
            Carbon::parse($request->input('start_date')) : null;
        $minAmount = $request->input('min_amount');
        $maxAmount = $request->input('max_amount');

        // Créer une instance du modèle CreditsPayment
        $creditPayment = new CreditsPayment();

        // Appeler la méthode avec les filtres
        $result = $creditPayment->getAllTransactionList(
            $startDate,
            $minAmount,
            $maxAmount
        );

        // Retourner à la vue avec les résultats et les paramètres de filtrage
        return view('TestingCenter.credits_payments.index_credits_payments', [
            'extends_containt' => $extends_containt,
            'transactions' => $result->original['transactions'] ?? [],
            'total_transactions' => $result->original['total_transactions'] ?? 0,
            'start_date' => $request->input('start_date'),
            'min_amount' => $request->input('min_amount'),
            'max_amount' => $request->input('max_amount'),
            'user_auth' => $user_auth
        ]);

        // return response()->json($result->original['transactions'] ?? []);
    }

    /**
     * Method for making filter on the list of transactions (v2)
     * 
     * @param $request
     */
    public function filterTransactions(Request $request)
    {
        // Récupérer les paramètres de filtrage de la requête
        $startDate = $request->input('start_date') ?
            Carbon::createFromFormat('Y-m-d', $request->input('start_date'))->startOfDay() : null;
        $minAmount = $request->input('min_amount');
        $maxAmount = $request->input('max_amount');

        // Créer une instance du modèle CreditsPayment
        $creditPayment = new CreditsPayment();

        // Appeler la méthode avec les filtres
        $result = $creditPayment->getAllTransactionList(
            $startDate,
            $minAmount,
            $maxAmount
        );

        // Transformer les dates correctement
        $transformedTransactions = collect($result->original['transactions'] ?? [])->map(function ($transaction) {
            // Assurez-vous que la date est correctement formatée
            $transaction->created_at = Carbon::parse($transaction->created_at)->format('Y-m-d H:i');
            return $transaction;
        });

        // Retourner une réponse JSON
        return response()->json([
            'transactions' => $transformedTransactions,
            'total_transactions' => $result->original['total_transactions'] ?? 0
        ]);
    }

    /**
     * Fonction menant à la vue pour toutes les transactions effectuées pour l'achat de crédit (v2)
     * Chiffre d'affaire (test)
     */
    public function getSalesRevenus()
    {
        $extends_containt = $this->navigationService->determineLayout(); // Set layout based on user role

        $credits_payment = new CreditsPayment();
        $all_payment = $credits_payment->getAllTransaction();
        $months_sales_revenus = $credits_payment->getMonthSaleRevenue();
        $sales_revenus_by_currency = $credits_payment->getSaleRevenueByCurrency();
        $sales_revenus_by_pay_types = $credits_payment->getSaleRevenueByPayType();
        $sales_revenus_by_pay_types = $credits_payment->getSaleRevenueByPayType();
        $sales_revenus_mga_without_api = $credits_payment->getTotalSaleRevenueWithoutApi();
        $sales_revenus_mga_with_api = $credits_payment->getTotalSaleRevenueWithApi();

        return response()->json([
            'extends_containt' => $extends_containt,
            'sales_revenue_data' => $all_payment,
            'months_sales_revenus' => $months_sales_revenus,
            'sales_revenus_by_currency' => $sales_revenus_by_currency,
            'sales_revenus_by_pay_types' => $sales_revenus_by_pay_types,
            'sales_revenus_mga_without_api' => $sales_revenus_mga_without_api,
            'sales_revenus_mga_with_api' => $sales_revenus_mga_with_api,
        ]);
    }

    /**
     * Fonction menant au dashboard des payements (v2)
     */
    public function salesRevenusDashboard()
    {
        $extends_containt = $this->navigationService->determineLayout(); // Set layout based on user role

        $credits_payment = new CreditsPayment();
        $all_payment = $credits_payment->getAllTransaction();
        $months_sales_revenus = $credits_payment->getMonthSaleRevenue();
        $sales_revenus_by_currency = $credits_payment->getSaleRevenueByCurrency();
        $sales_revenus_by_pay_types = $credits_payment->getSaleRevenueByPayType();
        $total_revenue_without_api = $credits_payment->getTotalSaleRevenueWithoutApi();
        $total_revenue_with_api = $credits_payment->getTotalSaleRevenueWithApi();

        return view('TestingCenter.sales_revenue.dashboard', compact(
            'extends_containt',
            'all_payment',
            'months_sales_revenus',
            'sales_revenus_by_currency',
            'sales_revenus_by_pay_types',
            'total_revenue_without_api',
            'total_revenue_with_api'
        ));
    }

    /**
     * Filter for sales revenues
     * 
     * @param $request
     */
    public function filterSalesRevenue(Request $request)
    {
        $currency = $request->input('currency');
        $paymentType = $request->input('paymentType');
        $dateRange = $request->input('dateRange');

        $credits_payment = new CreditsPayment();

        // Get filtered data
        $filtered_data = [
            'months_sales_revenus' => $credits_payment->getFilteredMonthSaleRevenue(
                $currency,
                $paymentType,
                $dateRange
            ),
            'sales_revenus_by_pay_types' => $credits_payment->getFilteredSaleRevenueByPayType(
                $currency,
                $paymentType,
                $dateRange
            )
        ];

        return response()->json($filtered_data);
    }

    /**
     * Method for displaying the invoice of one credits purchase (v3)
     * 
     * @param int $id (invoice of credits purchase id)
     * @return \Illuminate\View\View
     */
    public function oneCreditsPaymentInvoice($id)
    {
        $extends_containt = $this->navigationService->determineLayout();

        // Retrieve transaction details
        $creditsPaymentModel = new CreditsPayment();
        $response = $creditsPaymentModel->fetchCreditsTransactionPurchase($id);

        // Ensure the response is a JsonResponse and extract the data
        if ($response instanceof JsonResponse) {
            $transactionDetails = $response->getData(true)['data'];
        } else {
            return redirect()->back()->with('error', 'Invalid response format.');
        }

        // Handle case where transaction data is not found
        if (!$transactionDetails) {
            return redirect()->back()->with('error', 'Transaction not found.');
        }

        // Format the price if credits_pack exists
        if (isset($transactionDetails['credits_pack']) && isset($transactionDetails['credits_pack']['price'])) {
            $transactionDetails['credits_pack']['price'] = $this->formatPriceWithCurrency($transactionDetails['credits_pack']['price']);
        }

        // Pass the transaction details to the invoice view
        return view('TestingCenter.credits_payments.invoiceCreditsPay', [
            'extends_containt' => $extends_containt,
            'transaction' => $transactionDetails,
        ]);
    }

    /**
     * Method for displaying the invoice of one credits purchase
     * 
     * @param int $id (invoice of credits purchase id)
     * @return \Illuminate\View\View
     */
    protected function formatPriceWithCurrency($priceString)
    {
        $priceParts = explode(' ', $priceString);
        $number = floatval($priceParts[0]);
        $currency = $priceParts[1] ?? 'MGA';
        return number_format($number, 2) . ' ' . $currency;
    }

    /**
     * Method for generating the PDF of one credits purchase invoice (v1)
     * 
     * @param int $id (invoice of credits purchase id)
     * @return \Illuminate\Http\Response
     */
    // public function generateCreditsInvoicePDF($id)
    // {
    //     set_time_limit(120);

    //     try {
    //         $creditsPaymentModel = new CreditsPayment();
    //         $response = $creditsPaymentModel->fetchCreditsTransactionPurchase($id);

    //         if (!$response instanceof JsonResponse) {
    //             return redirect()->back()->with('error', 'Invalid response format.');
    //         }

    //         $transactionDetails = $response->getData(true)['data'];
    //         if (!$transactionDetails) {
    //             return redirect()->back()->with('error', 'Transaction not found.');
    //         }

    //         if (isset($transactionDetails['credits_pack']) && isset($transactionDetails['credits_pack']['price'])) {
    //             $transactionDetails['credits_pack']['price'] = $this->formatPriceWithCurrency($transactionDetails['credits_pack']['price']);
    //         }

    //         # This view is not used anymore
    //         $pdf = Pdf::loadView('TestingCenter.credits_payments.layouts_pdfInvoiceCredits', [
    //             'transaction' => $transactionDetails,
    //         ])
    //             ->setPaper('A4')
    //             ->setOption('isRemoteEnabled', true)  // Enable for logo loading
    //             ->setOption('isPhpEnabled', true)     // Enable PHP processing
    //             ->setOption('isHtml5ParserEnabled', true)
    //             ->setOption('isFontSubsettingEnabled', true)
    //             ->setOption('dpi', 150)
    //             ->setOption('defaultFont', 'DejaVu Sans')
    //             ->setOption('margin-top', 20)
    //             ->setOption('margin-right', 20)
    //             ->setOption('margin-bottom', 20)
    //             ->setOption('margin-left', 20);

    //         $filename = 'invoice-' . $transactionDetails['transaction']['reference'] . '.pdf';

    //         return $pdf->download($filename);
    //     } catch (\Exception $e) {
    //         Log::error('PDF Generation Error: ' . $e->getMessage());
    //         return redirect()->back()->with('error', 'Failed to generate PDF. Please try again.');
    //     }
    // }

    /**
     * Method for generating the PDF of one credits purchase invoice (v2)
     * 
     * @param int $id (invoice of credits purchase id)
     * @return \Illuminate\Http\Response
     */
    public function generateCreditsInvoicePDF($id)
    {
        // Retrieve transaction details (similar to oneCreditsPaymentInvoice method)
        $creditsPaymentModel = new CreditsPayment();
        $response = $creditsPaymentModel->fetchCreditsTransactionPurchase($id);

        // Ensure the response is a JsonResponse and extract the data
        if ($response instanceof JsonResponse) {
            $transactionDetails = $response->getData(true)['data'];
        } else {
            return redirect()->back()->with('error', 'Invalid response format.');
        }

        // Handle case where transaction data is not found
        if (!$transactionDetails) {
            return redirect()->back()->with('error', 'Transaction not found.');
        }

        // Format the price if credits_pack exists
        if (isset($transactionDetails['credits_pack']) && isset($transactionDetails['credits_pack']['price'])) {
            $transactionDetails['credits_pack']['price'] = $this->formatPriceWithCurrency($transactionDetails['credits_pack']['price']);
        }

        $logoPath = public_path('img/logo/Logo_horizontal.png'); // Change to PNG
        $logoBase64 = '';

        if (file_exists($logoPath)) {
            $imageData = file_get_contents($logoPath);
            $logoBase64 = 'data:image/png;base64,' . base64_encode($imageData);
        }

        // Generate a filename for the PDF
        $filename = 'invoice-' . $transactionDetails['transaction']['reference'] . '.pdf';

        // Generate PDF from the view
        $pdf = PDF::loadView('TestingCenter.credits_payments.invoiceCreditsPdf', [
            'transaction' => $transactionDetails,
            'logoBase64' => $logoBase64,
        ]);

        // Set paper size to A4
        $pdf->setPaper('a4');

        // Enable Unicode characters if needed
        $pdf->setOptions([
            'isHtml5ParserEnabled' => true,
            'isRemoteEnabled' => true,
            'isPhpEnabled' => true,
            'isSvgEnabled' => true,  // Important pour le support SVG
        ]);

        $pdf->setPaper('a4');

        // Return the PDF as a download
        return $pdf->download($filename);
    }
}
