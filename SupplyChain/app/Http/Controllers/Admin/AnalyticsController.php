<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AnalyticsController extends Controller
{
    public function analytics() {
        // Fetch data as needed
        return view('admin.analytics', [/* data */]);
    }

    public function index()
    {
        // Orders processed
        $ordersCount = \App\Models\Order::count();

        // Vendors registered (suppliers, manufacturers, wholesalers)
        $vendorsCount = \App\Models\User::whereIn('role', ['supplier', 'manufacturer', 'wholesaler'])->count();

        // Revenue for this month
        $monthlyRevenue = \App\Models\Order::whereMonth('created_at', now()->month)->sum('total_amount');

        // Orders chart data (orders per day this month)
        $orders = \App\Models\Order::selectRaw('DATE(created_at) as date, COUNT(*) as count')
            ->whereMonth('created_at', now()->month)
            ->groupBy('date')
            ->orderBy('date')
            ->get();
        $chartData = [
            'labels' => $orders->pluck('date')->toArray(),
            'datasets' => [
                [
                    'label' => 'Orders',
                    'data' => $orders->pluck('count')->toArray(),
                    'borderColor' => '#3B82F6',
                    'backgroundColor' => 'rgba(59, 130, 246, 0.2)',
                    'fill' => true,
                ]
            ]
        ];

        return view('admin.analytics.index', compact(
            'ordersCount', 'vendorsCount', 'monthlyRevenue', 'chartData'
        ));
    }
}
