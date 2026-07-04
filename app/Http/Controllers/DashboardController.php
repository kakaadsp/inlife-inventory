<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Services\DashboardService;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function __construct(
        private readonly DashboardService $dashboardService
    ) {}

    public function index()
    {
        $stats              = $this->dashboardService->getStats();
        $monthlyChart       = $this->dashboardService->getMonthlyBorrowingChart();
        $categoryChart      = $this->dashboardService->getCategoryDistribution();
        $recentBorrowings   = $this->dashboardService->getRecentBorrowings(5);
        $lowStockItems      = $this->dashboardService->getLowStockItems(5);

        return view('dashboard.index', compact(
            'stats',
            'monthlyChart',
            'categoryChart',
            'recentBorrowings',
            'lowStockItems'
        ));
    }
}
