<?php

namespace App\Http\Controllers;

use App\Models\CommissionsReceived;
use App\Services\Qcm\QcmNavigationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CommissionsReceivedController extends Controller
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
     * Show commission dashboard (v3)
     * 
     * @param Request $request
     * @return \Illuminate\View\View
     */
    public function dashboard_commissions(Request $request)
    {
        $extends_containt = $this->navigationService->determineLayout(); // Set layout based on user role

        $commissionModel = new CommissionsReceived();

        // Get available years
        $years = $commissionModel->getDistinctCommissionYears();

        // Get current month and year from request or default to current
        $selectedMonth = $request->input('month', now()->month);
        $selectedYear = $request->input('year', now()->year);

        // Get dashboard data
        $dashboardData = $commissionModel->getCommissionDashboardData($selectedMonth, $selectedYear);

        // Prepare data for monthly commission chart
        $monthlyCommissionsChart = $commissionModel->getMonthlyCommissionsForChart($selectedYear);

        return view('TestingCenter.commissions.dashboard', [
            'extends_containt' => $extends_containt,
            'dashboardData' => $dashboardData,
            'years' => $years,
            'selectedMonth' => $selectedMonth,
            'selectedYear' => $selectedYear,
            'monthlyCommissionsChart' => $monthlyCommissionsChart
        ]);
    }

    /**
     * Show paginated commissions list (v2)
     * 
     * @param Request $request
     * @return \Illuminate\View\View
     */
    public function index_commissions(Request $request)
    {
        $extends_containt = $this->navigationService->determineLayout(); // Set layout based on user role

        $commissionModel = new CommissionsReceived();

        // Get available years
        $years = $commissionModel->getDistinctCommissionYears();

        // Get current month and year from request or default to current
        $selectedMonth = $request->input('month');
        $selectedYear = $request->input('year');

        // Paginate commissions with filters
        $commissions = CommissionsReceived::with(['creditPayment', 'receiver'])
            ->filterByMonthYear($selectedMonth, $selectedYear)
            ->orderBy('created_at', 'desc')
            ->paginate(5);

        return view('TestingCenter.commissions.index', [
            'extends_containt' => $extends_containt,
            'commissions' => $commissions,
            'years' => $years,
            'selectedMonth' => $selectedMonth,
            'selectedYear' => $selectedYear
        ]);

        // return response()->json($commissions);
    }

    /**
     * Get commission details for modal
     * 
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function getCommissionDetails($id)
    {
        $commissionModel = new CommissionsReceived();
        return $commissionModel->getCommissionById($id);
    }
}
