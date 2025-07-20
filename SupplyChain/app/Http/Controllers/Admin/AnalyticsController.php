<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class AnalyticsController extends Controller
{
    public function index()
    {
        // Total Users
        $totalUsers = User::count();
        
        // Total Orders
        $totalOrders = Order::count();

        // Total Revenue (using the correct field name from Order model)
        $totalRevenue = Order::sum('total_amount');

        // Active Suppliers (using the role constant from User model)
        // Removed is_active check since column doesn't exist
        $activeSuppliers = User::where('role', User::ROLE_SUPPLIER)
                            ->count();

        // Orders chart data (orders per day this month)
        $orders = Order::selectRaw('DATE(order_date) as date, COUNT(*) as count')
            ->whereMonth('order_date', now()->month)
            ->groupBy('date')
            ->orderBy('date')
            ->get();
            
        $ordersChartLabels = $orders->pluck('date');
        $ordersChartData = $orders->pluck('count');

        // Users registration chart data (users per day this month)
        $users = User::selectRaw('DATE(created_at) as date, COUNT(*) as count')
            ->whereMonth('created_at', now()->month)
            ->groupBy('date')
            ->orderBy('date')
            ->get();
            
        $usersChartLabels = $users->pluck('date');
        $usersChartData = $users->pluck('count');

        // Recent Orders with wholesaler relationship
        $recentOrders = Order::with(['wholesaler.user'])
            ->latest('order_date')
            ->take(10)
            ->get();

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