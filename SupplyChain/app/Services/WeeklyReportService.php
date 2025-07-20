<?php

namespace App\Services;

use App\Models\Order;
use App\Models\User;
use App\Models\InventoryItem; // Adjust if your inventory model is named differently
use Illuminate\Support\Facades\View;
use Carbon\Carbon;

class WeeklyReportService
{
    /**
     * Gather data for the weekly report.
     * @return array
     */
    public function generateReportData(): array
    {
        $now = Carbon::now();
        $weekAgo = $now->copy()->subWeek();

        // Sales data
        $sales = Order::whereBetween('created_at', [$weekAgo, $now])->get();
        $totalSales = $sales->sum('total_amount');
        $salesCount = $sales->count();

        // User activity
        $newUsers = User::whereBetween('created_at', [$weekAgo, $now])->get();
        $newUsersCount = $newUsers->count();
        $activeUsers = User::whereHas('orders', function($q) use ($weekAgo, $now) {
            $q->whereBetween('created_at', [$weekAgo, $now]);
        })->get();
        $activeUsersCount = $activeUsers->count();

        // Inventory data
        $lowStockItems = InventoryItem::where('quantity', '<', 10)->get(); // threshold can be adjusted
        $totalInventory = InventoryItem::sum('quantity');

        return [
            'period_start' => $weekAgo,
            'period_end' => $now,
            'sales' => $sales,
            'totalSales' => $totalSales,
            'salesCount' => $salesCount,
            'newUsers' => $newUsers,
            'newUsersCount' => $newUsersCount,
            'activeUsers' => $activeUsers,
            'activeUsersCount' => $activeUsersCount,
            'lowStockItems' => $lowStockItems,
            'totalInventory' => $totalInventory,
        ];
    }

    /**
     * Render the report as HTML using a Blade view.
     * @param array $data
     * @return string
     */
    public function renderHtmlReport(array $data): string
    {
        return View::make('admin.reports.weekly_report_email', $data)->render();
    }
} 