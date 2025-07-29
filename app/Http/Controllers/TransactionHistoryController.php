<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\TransactionHistory;
use App\Models\User;
use App\Services\Qcm\QcmNavigationService;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class TransactionHistoryController extends Controller
{
    # Services part added 18-02-2025
    protected $transactionHistory;
    private QcmNavigationService $navigationService;

    public function __construct(
        TransactionHistory $transactionHistory,
        QcmNavigationService $navigationService
    ) {
        $this->transactionHistory = $transactionHistory;
        $this->navigationService = $navigationService;
    }
    # Services part added 18-02-2025

    /**
     * Function for the layout, depend of the authentified user
     */
    private function getLayout($user)
    {
        if ($user->hasRole('Formateur')) {
            return "layouts.masterForm";
        } elseif ($user->hasRole('Formateur interne')) {
            return "layouts.masterFormInterne";
        } elseif ($user->hasRole('Particulier')) {
            return "layouts.masterParticulier";
        } elseif ($user->hasRole('EmployeCfp')) {
            return "layouts.masterEmpCfp";
        } elseif ($user->hasRole('Employe') || $user->hasRole('EmployeEtp')) {
            return "layouts.masterEmp";
        } elseif ($user->hasRole('Cfp')) {
            return "layouts.master";
        } elseif ($user->hasRole('Admin') || $user->hasRole('SuperAdmin')) {
            return "layouts.masterAdmin";
        } elseif ($user->hasRole('Referent')) {
            return "layouts.masterEtp";
        }
        return "layouts.master"; // default layout
    }

    /**
     * Display a listing of transactions with filtering and pagination (v3)
     * 
     * @param $request
     */
    public function index(Request $request)
    {
        $extends_containt = $this->navigationService->determineLayout(); // Variable pour stocker les extends selon l'utilisateur connecté

        // Get validated filters with default values
        $filters = array_merge([
            'user_id' => null,
            'date' => null,
            'type' => null,
            'userName' => null // Add userName to filters
        ], $this->validateFilters($request));

        // Cache key based on filters
        $cacheKey = "transactions_" . md5(json_encode($filters));

        // Get transactions from cache or database
        $transactions = Cache::remember($cacheKey, now()->addMinutes(5), function () use ($filters) {
            $creditTransactions = collect();
            $debitTransactions = collect();

            // Only get credit transactions if no type filter or type is credit
            if (!$filters['type'] || $filters['type'] === 'credit') {
                $creditTransactions = $this->transactionHistory->getCreditTransaction(
                    $filters['user_id'],
                    $filters['date']
                ) ?? collect();
            }

            // Only get debit transactions if no type filter or type is debit
            if (!$filters['type'] || $filters['type'] === 'debit') {
                $debitTransactions = $this->transactionHistory->getDebitTransaction(
                    $filters['user_id'],
                    $filters['date']
                ) ?? collect();
            }

            // Merge and filter by userName if provided
            $allTransactions = $creditTransactions->concat($debitTransactions);

            if ($filters['userName']) {
                $allTransactions = $allTransactions->filter(function ($transaction) use ($filters) {
                    return str_contains(
                        strtolower($transaction->userName),
                        strtolower($filters['userName'])
                    );
                });
            }

            return $allTransactions->sortByDesc('created_at')->values();
        });

        // Paginate the results
        $perPage = 5;
        $page = $request->input('page', 1);
        $pagedData = $transactions->forPage($page, $perPage);

        $paginatedTransactions = new LengthAwarePaginator(
            $pagedData,
            $transactions->count(),
            $perPage,
            $page,
            ['path' => $request->url()]
        );

        return view('TestingCenter.transactions.index_transactions_credits', [
            'transactions' => $paginatedTransactions,
            'filters' => $filters,
            'extends_containt' => $extends_containt,
        ]);
    }

    /**
     * Get transaction details for modal (v2)
     * 
     * @param $request
     */
    public function getDetails(Request $request)
    {
        try {
            // Validate the request
            $validated = $request->validate([
                'transaction_id' => 'required|numeric',
                'type' => 'required|in:credit,debit'
            ]);

            $transactionId = $validated['transaction_id'];
            $transactionType = $validated['type'];

            // Get transaction details based on type
            if ($transactionType === 'credit') {
                $transaction = $this->transactionHistory->getCreditTransaction()
                    ->where('transactionId', $transactionId)
                    ->first();
            } else {
                $transaction = $this->transactionHistory->getDebitTransaction()
                    ->where('transactionId', $transactionId)
                    ->first();
            }

            if (!$transaction) {
                return response()->json([
                    'error' => 'Transaction not found'
                ], 404);
            }

            // Load user data if needed
            if (isset($transaction->userId)) {
                $user = User::find($transaction->userId);
                if ($user->hasRole('Referent')) {
                    $etp = Customer::find($transaction->userId);
                    $transaction->userName = $etp ? $etp->customerName : 'N/A';
                } else {
                    $user = User::find($transaction->userId);
                    $transaction->userName = $user ? $user->name . ' ' . $user->firstName : 'N/A';
                }
            }

            if ($transactionType === 'debit' && isset($transaction->employeeId)) {
                $employee = User::find($transaction->employeeId);
                $transaction->employeeName = $employee ? $employee->name . ' ' . $employee->firstName : 'N/A';
            }

            try {
                $html = view('TestingCenter.transactions.partials.detail-modal', [
                    'transaction' => $transaction,
                    'transactionType' => $transactionType
                ])->render();

                return response()->json([
                    'success' => true,
                    'transaction' => $transaction,
                    'html' => $html
                ]);
            } catch (\Throwable $viewError) {
                Log::error('View rendering error: ' . $viewError->getMessage());
                return response()->json([
                    'error' => 'View rendering error',
                    'message' => $viewError->getMessage(),
                    'transaction' => $transaction // Pour déboguer
                ], 500);
            }
        } catch (\Exception $e) {
            Log::error('Transaction detail error: ' . $e->getMessage());
            return response()->json([
                'error' => 'An error occurred while fetching transaction details',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Validate and prepare filters
     * 
     * @param $request
     */
    private function validateFilters(Request $request)
    {
        return $request->validate([
            'user_id' => 'nullable|integer',
            'date' => 'nullable|date_format:Y-m-d',
            'type' => 'nullable|in:credit,debit',
            'userName' => 'nullable|string',
        ]);
    }

    /**
     * Function for displaying the dashboard of transaction history
     * 
     * @param $request
     */
    public function dashboardTransactionHistory(Request $request)
    {
        $user = Auth::user();
        $extends_containt = $this->getLayout($user);

        // Get validated filters with default values
        $filters = array_merge([
            'user_id' => null,
            'date' => null,
            'type' => null,
            'userName' => null,
        ], $this->validateFiltersDashboard($request));

        // Cache key based on filters
        $cacheKey = "dashboard_transactions_" . md5(json_encode($filters));

        // Get transactions from cache or database
        $transactions = Cache::remember($cacheKey, now()->addMinutes(5), function () use ($filters) {
            return $this->transactionHistory->getFilteredTransactions($filters);
        });

        // Summary Metrics
        $totalCredits = $transactions->where('typeTransaction', 'credit')->sum('montant');
        $totalDebits = $transactions->where('typeTransaction', 'debit')->sum('montant');
        $balance = $totalCredits - $totalDebits;
        $transactionCount = $transactions->count();

        // Prepare data for charts
        $monthlyData = $this->transactionHistory->getMonthlyData($transactions);
        $categoryData = $this->transactionHistory->getCategoryData($transactions);

        // Paginate the results
        $perPage = 5;
        $page = $request->input('page', 1);
        $pagedData = $transactions->forPage($page, $perPage);

        $paginatedTransactions = new LengthAwarePaginator(
            $pagedData,
            $transactions->count(),
            $perPage,
            $page,
            ['path' => $request->url()]
        );

        return view('TestingCenter.transactions.dashboard_transactions', [
            'extends_containt' => $extends_containt,
            'transactions' => $paginatedTransactions,
            'filters' => $filters,
            'totalCredits' => $totalCredits,
            'totalDebits' => $totalDebits,
            'balance' => $balance,
            'transactionCount' => $transactionCount,
            'monthlyData' => $monthlyData,
            'categoryData' => $categoryData,
        ]);

        // return response()->json([
        //     $paginatedTransactions,
        //     $totalCredits,
        //     $totalDebits,
        //     $transactionCount,
        //     $monthlyData,
        //     $categoryData
        // ]);
    }

    /**
     * Validate and prepare filters for the dashboard
     */
    private function validateFiltersDashboard(Request $request)
    {
        return $request->validate([
            'user_id' => 'nullable|integer|exists:users,id',
            'date' => 'nullable|date_format:Y-m-d',
            'type' => 'nullable|in:credit,debit',
            'userName' => 'nullable|string|max:255',
        ]);
    }

    /**
     * Display user transactions based on roles
     * 
     * @param Request $request
     */
    public function userTransactions(Request $request)
    {
        // Get transactions based on user role
        $transactionHistory = new TransactionHistory();
        $transactionData = $transactionHistory->getTransactionsByUserRole();

        // Combine and format transactions for the view
        $allTransactions = collect();

        // Add credit transactions
        foreach ($transactionData['creditTransactions'] as $transaction) {
            $transaction = (object) $transaction;
            $transaction->userName = auth()->user()->name; // Add the username
            $allTransactions->push($transaction);
        }

        // Add debit transactions
        foreach ($transactionData['debitTransactions'] as $transaction) {
            $transaction = (object) $transaction;
            $transaction->userName = auth()->user()->name; // Add the username
            $allTransactions->push($transaction);
        }

        // Sort by created_at date (most recent first)
        $sortedTransactions = $allTransactions->sortByDesc('created_at');

        // Apply filters if any
        $filters = [
            'user_id' => $request->input('user_id', ''),
            'userName' => $request->input('userName', ''),
            'date' => $request->input('date', ''),
            'type' => $request->input('type', '')
        ];

        $filteredTransactions = $sortedTransactions;

        if ($filters['user_id']) {
            $filteredTransactions = $filteredTransactions->where('userId', $filters['user_id']);
        }

        if ($filters['userName']) {
            $filteredTransactions = $filteredTransactions->filter(function ($transaction) use ($filters) {
                return stripos($transaction->userName, $filters['userName']) !== false;
            });
        }

        if ($filters['date']) {
            $filteredTransactions = $filteredTransactions->where('transactionDate', $filters['date']);
        }

        if ($filters['type']) {
            $filteredTransactions = $filteredTransactions->where('typeTransaction', $filters['type']);
        }

        // Paginate results
        $paginator = new \Illuminate\Pagination\LengthAwarePaginator(
            $filteredTransactions->values()->forPage($request->input('page', 1), 10),
            $filteredTransactions->count(),
            10,
            $request->input('page', 1),
            ['path' => $request->url(), 'query' => $request->query()]
        );

        // Determine layout
        $extends_containt = $this->navigationService->determineLayout();

        return view('TestingCenter.transactions.user-transactions', compact('paginator', 'filters', 'extends_containt'));
    }

    /**
     * Get transaction details for modal (v1)
     * 
     * @param Request $request
     */
    public function getTransactionDetails(Request $request)
    {
        try {
            // Validate the request
            $validated = $request->validate([
                'transaction_id' => 'required|numeric',
                'type' => 'required|in:credit,debit'
            ]);

            $transactionId = $validated['transaction_id'];
            $type = $validated['type'];

            // Get transaction details based on type
            if ($type === 'credit') {
                $transaction = DB::table('v_credit_transactions')
                    ->where('transactionId', $transactionId)
                    ->first();
            } else {
                $transaction = DB::table('v_debit_transactions')
                    ->where('transactionId', $transactionId)
                    ->first();
            }

            if (!$transaction) {
                return response()->json([
                    'error' => true,
                    'message' => 'Transaction not found'
                ], 404);
            }

            // Render the appropriate view
            if ($type === 'credit') {
                $html = view('TestingCenter.transactions.partials.credit-details', compact('transaction'))->render();
            } else {
                $html = view('TestingCenter.transactions.partials.debit-details', compact('transaction'))->render();
            }

            return response()->json(['html' => $html]);
        } catch (Exception $e) {
            Log::error('Transaction detail error: ' . $e->getMessage());
            return response()->json([
                'error' => true,
                'message' => $e->getMessage()
            ], 500);
        }
    }
}
