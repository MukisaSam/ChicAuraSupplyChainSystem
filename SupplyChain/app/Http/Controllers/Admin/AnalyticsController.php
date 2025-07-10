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
        // User stats
        $userStats = [
            'total' => \App\Models\User::count(),
            'new_this_month' => \App\Models\User::whereMonth('created_at', now()->month)->count(),
            'active' => \App\Models\User::where('last_login_at', '>=', now()->subMonth())->count(),
        ];

        // Revenue stats (example: sum of all orders)
        $revenueStats = [
            'total' => \App\Models\Order::sum('total_amount'),
        ];

        // Orders chart data (example: orders per month)
        $orders = \App\Models\Order::selectRaw('MONTH(created_at) as month, COUNT(*) as count')
            ->groupBy('month')
            ->orderBy('month')
            ->get();
        $ordersChart = [
            'labels' => $orders->pluck('month')->map(fn($m) => \Carbon\Carbon::create()->month($m)->format('F'))->toArray(),
            'data' => $orders->pluck('count')->toArray(),
        ];

        // Revenue chart data (example: revenue per month)
        $revenues = \App\Models\Order::selectRaw('MONTH(created_at) as month, SUM(total_amount) as total')
            ->groupBy('month')
            ->orderBy('month')
            ->get();
        $revenueChart = [
            'labels' => $revenues->pluck('month')->map(fn($m) => \Carbon\Carbon::create()->month($m)->format('F'))->toArray(),
            'data' => $revenues->pluck('total')->toArray(),
        ];

        // Top suppliers (example: by total supplied)
        $topSuppliers = \App\Models\Supplier::withCount('suppliedItems')
            ->orderByDesc('supplied_items_count')
            ->take(5)
            ->get()
            ->map(function($supplier) {
                return (object)[
                    'name' => $supplier->name,
                    'total_supplied' => $supplier->supplied_items_count,
                ];
            });

        // Top wholesalers (example: by total orders)
        $topWholesalers = \App\Models\Wholesaler::withCount('orders')
            ->orderByDesc('orders_count')
            ->take(5)
            ->get()
            ->map(function($wholesaler) {
                return (object)[
                    'name' => $wholesaler->name,
                    'total_orders' => $wholesaler->orders_count,
                ];
            });

        return view('admin.analytics', compact(
            'userStats', 'revenueStats', 'ordersChart', 'revenueChart', 'topSuppliers', 'topWholesalers'
        ));
    }
}
