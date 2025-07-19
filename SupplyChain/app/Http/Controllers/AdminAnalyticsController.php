<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Order; 
use App\Models\Supplier;      

class AdminAnalyticsController extends Controller
{
    public function index()
    {
        // Metrics
        $totalUsers = User::count();
        $totalOrders = Order::count();
        $totalRevenue = Order::sum('total');
        $activeSuppliers = Supplier::where('status', 'active')->count();

        // Orders Over Time (last 7 days)
        $orders = Order::selectRaw('DATE(created_at) as date, COUNT(*) as count')
            ->where('created_at', '>=', now()->subDays(6))
            ->groupBy('date')
            ->orderBy('date')
            ->get();
        $ordersChartLabels = $orders->pluck('date')->toArray();
        $ordersChartData = $orders->pluck('count')->toArray();

        // User Registrations Over Time (last 7 days)
        $users = User::selectRaw('DATE(created_at) as date, COUNT(*) as count')
            ->where('created_at', '>=', now()->subDays(6))
            ->groupBy('date')
            ->orderBy('date')
            ->get();
        $usersChartLabels = $users->pluck('date')->toArray();
        $usersChartData = $users->pluck('count')->toArray();

        // Recent Orders
        $recentOrders = Order::with('customer')->latest()->take(5)->get();

        return view('admin.analytics.index', compact(
            'totalUsers',
            'totalOrders',
            'totalRevenue',
            'activeSuppliers',
            'ordersChartLabels',
            'ordersChartData',
            'usersChartLabels',
            'usersChartData',
            'recentOrders'
        ));
    }
}
