<?php

namespace App\Services;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\User;
use App\Models\SuppliedItem;
use Illuminate\Support\Facades\View;
use Carbon\Carbon;

class SupplierWeeklyReportService
{
    /**
     * Gather data for the supplier's weekly report.
     * @param User $supplierUser
     * @return array
     */
    public function generateReportData(User $supplierUser): array
    {
        $now = Carbon::now();
        $weekAgo = $now->copy()->subWeek();

        // Get the Supplier model from the user
        $supplier = $supplierUser->supplier;
        if (!$supplier) {
            return [];
        }

        // Get all supplied items for this supplier
        $suppliedItems = $supplier->suppliedItems()->with('item')->get();
        $itemIds = $suppliedItems->pluck('item_id')->unique();

        // Get all order items for these item_ids in the last 7 days
        $orderItems = OrderItem::whereIn('item_id', $itemIds)
            ->whereBetween('created_at', [$weekAgo, $now])
            ->get();

        // Get all unique orders from these order items
        $orderIds = $orderItems->pluck('order_id')->unique();
        $orders = Order::whereIn('id', $orderIds)->get();
        $totalSales = $orderItems->sum('total_price');
        $salesCount = $orders->count();

        // Inventory: items supplied by this supplier
        $lowStockItems = $suppliedItems->filter(function($suppliedItem) {
            return optional($suppliedItem->item)->stock_quantity < 10;
        });
        $totalInventory = $suppliedItems->sum(function($suppliedItem) {
            return optional($suppliedItem->item)->stock_quantity ?? 0;
        });

        return [
            'period_start' => $weekAgo,
            'period_end' => $now,
            'orders' => $orders,
            'orderItems' => $orderItems,
            'totalSales' => $totalSales,
            'salesCount' => $salesCount,
            'suppliedItems' => $suppliedItems,
            'lowStockItems' => $lowStockItems,
            'totalInventory' => $totalInventory,
            'supplier' => $supplier,
        ];
    }

    /**
     * Render the report as HTML using a Blade view.
     * @param array $data
     * @return string
     */
    public function renderHtmlReport(array $data): string
    {
        return View::make('supplier.reports.weekly_report_email', $data)->render();
    }
} 