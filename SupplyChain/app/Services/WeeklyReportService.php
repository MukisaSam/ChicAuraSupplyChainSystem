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

        // Active wholesalers and manufacturers
        $activeWholesalers = Order::whereNotNull('wholesaler_id')
            ->whereBetween('created_at', [$weekAgo, $now])
            ->distinct('wholesaler_id')
            ->count('wholesaler_id');
        $activeManufacturers = Order::whereNotNull('manufacturer_id')
            ->whereBetween('created_at', [$weekAgo, $now])
            ->distinct('manufacturer_id')
            ->count('manufacturer_id');

        // Inventory data
        $lowStockItems = \App\Models\InventoryItem::where('quantity', '<', 10)->get();
        $totalInventory = \App\Models\InventoryItem::sum('quantity');

        return [
            'period_start' => $weekAgo,
            'period_end' => $now,
            'sales' => $sales,
            'totalSales' => $totalSales,
            'salesCount' => $salesCount,
            'newUsers' => $newUsers,
            'newUsersCount' => $newUsersCount,
            'activeWholesalers' => $activeWholesalers,
            'activeManufacturers' => $activeManufacturers,
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