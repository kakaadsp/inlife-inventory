<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Borrowing;
use App\Models\Item;
use App\Models\Setting;
use Illuminate\Support\Facades\DB;

class DashboardService
{
    /**
     * Get all statistics for the dashboard cards.
     */
    public function getStats(): array
    {
        $totalItems     = Item::count();
        $availableItems = Item::where('stock', '>', 0)->count();
        $lowStockItems  = Item::whereColumn('stock', '<=', 'min_stock')->count();

        $activeBorrowings   = Borrowing::where('status', 'borrowed')->count();
        $overdueBorrowings  = Borrowing::where('status', 'overdue')->count();
        $returnedThisMonth  = Borrowing::where('status', 'returned')
            ->whereMonth('actual_return_date', now()->month)
            ->whereYear('actual_return_date', now()->year)
            ->count();

        return [
            'total_items'          => $totalItems,
            'available_items'      => $availableItems,
            'low_stock_items'      => $lowStockItems,
            'active_borrowings'    => $activeBorrowings,
            'overdue_borrowings'   => $overdueBorrowings,
            'returned_this_month'  => $returnedThisMonth,
        ];
    }

    /**
     * Get monthly borrowing chart data for the past 12 months.
     */
    public function getMonthlyBorrowingChart(): array
    {
        $months = [];
        $data   = [];

        for ($i = 11; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            $months[] = $date->format('M Y');

            $count = Borrowing::whereYear('borrow_date', $date->year)
                ->whereMonth('borrow_date', $date->month)
                ->count();

            $data[] = $count;
        }

        return [
            'labels' => $months,
            'data'   => $data,
        ];
    }

    /**
     * Get item distribution by category (for donut chart).
     */
    public function getCategoryDistribution(): array
    {
        return DB::table('items')
            ->join('categories', 'items.category_id', '=', 'categories.id')
            ->whereNull('items.deleted_at')
            ->whereNull('categories.deleted_at')
            ->select('categories.name', DB::raw('COUNT(items.id) as total'))
            ->groupBy('categories.id', 'categories.name')
            ->get()
            ->toArray();
    }

    /**
     * Get recent active borrowings for dashboard table.
     */
    public function getRecentBorrowings(int $limit = 5): mixed
    {
        return Borrowing::with(['details.item', 'creator'])
            ->whereIn('status', ['borrowed', 'overdue'])
            ->orderByDesc('created_at')
            ->limit($limit)
            ->get();
    }

    /**
     * Get low stock items for alert list.
     */
    public function getLowStockItems(int $limit = 10): mixed
    {
        return Item::with('category')
            ->whereColumn('stock', '<=', 'min_stock')
            ->orderBy('stock')
            ->limit($limit)
            ->get();
    }
}
