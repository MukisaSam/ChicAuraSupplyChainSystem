<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Item;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class WholesalerAnalyticsController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $wholesaler = $user->wholesaler;
        
        if (!$wholesaler) {
            abort(403, 'Wholesaler profile not found.');
        }
        
        // Get date range (last 12 months)
        $endDate = Carbon::now();
        $startDate = Carbon::now()->subMonths(12);
        
        // Monthly order trends
        $monthlyOrders = Order::where('wholesaler_id', $wholesaler->id)
            ->whereBetween('order_date', [$startDate, $endDate])
            ->selectRaw('MONTH(order_date) as month, YEAR(order_date) as year, COUNT(*) as count, SUM(total_amount) as total')
            ->groupBy('month', 'year')
            ->orderBy('year')
            ->orderBy('month')
            ->get();
        
        // Prepare chart data
        $chartLabels = [];
        $orderCounts = [];
        $orderAmounts = [];
        
        for ($i = 0; $i < 12; $i++) {
            $date = Carbon::now()->subMonths(11 - $i);
            $chartLabels[] = $date->format('M Y');
            
            $monthData = $monthlyOrders->where('month', $date->month)->where('year', $date->year)->first();
            $orderCounts[] = $monthData ? $monthData->count : 0;
            $orderAmounts[] = $monthData ? round($monthData->total, 2) : 0;
        }
        
        // Top products by quantity
        $topProducts = OrderItem::join('orders', 'order_items.order_id', '=', 'orders.id')
            ->join('items', 'order_items.item_id', '=', 'items.id')
            ->where('orders.wholesaler_id', $wholesaler->id)
            ->select('items.name', DB::raw('SUM(order_items.quantity) as total_quantity'), DB::raw('SUM(order_items.total_price) as total_revenue'))
            ->groupBy('items.id', 'items.name')
            ->orderBy('total_quantity', 'desc')
            ->limit(5)
            ->get();
        
        // Order status distribution
        $statusDistribution = Order::where('wholesaler_id', $wholesaler->id)
            ->select('status', DB::raw('COUNT(*) as count'))
            ->groupBy('status')
            ->get();
        
        // Payment method distribution
        $paymentDistribution = Order::where('wholesaler_id', $wholesaler->id)
            ->select('payment_method', DB::raw('COUNT(*) as count'), DB::raw('SUM(total_amount) as total'))
            ->groupBy('payment_method')
            ->get();
        
        // Recent activity
        $recentActivity = Order::where('wholesaler_id', $wholesaler->id)
            ->with(['orderItems.item'])
            ->latest()
            ->take(10)
            ->get();
        
        // Summary statistics
        $totalOrders = Order::where('wholesaler_id', $wholesaler->id)->count();
        $totalSpent = Order::where('wholesaler_id', $wholesaler->id)->sum('total_amount');
        $averageOrderValue = $totalOrders > 0 ? $totalSpent / $totalOrders : 0;
        $pendingOrders = Order::where('wholesaler_id', $wholesaler->id)
            ->whereIn('status', ['pending', 'confirmed', 'in_production'])
            ->count();
        
        // Monthly growth
        $currentMonthOrders = Order::where('wholesaler_id', $wholesaler->id)
            ->whereMonth('order_date', Carbon::now()->month)
            ->whereYear('order_date', Carbon::now()->year)
            ->count();
        
        $lastMonthOrders = Order::where('wholesaler_id', $wholesaler->id)
            ->whereMonth('order_date', Carbon::now()->subMonth()->month)
            ->whereYear('order_date', Carbon::now()->subMonth()->year)
            ->count();
        
        $orderGrowth = $lastMonthOrders > 0 ? (($currentMonthOrders - $lastMonthOrders) / $lastMonthOrders) * 100 : 0;
        
        return view('wholesaler.analytics.index', compact(
            'user',
            'chartLabels',
            'orderCounts',
            'orderAmounts',
            'topProducts',
            'statusDistribution',
            'paymentDistribution',
            'recentActivity',
            'totalOrders',
            'totalSpent',
            'averageOrderValue',
            'pendingOrders',
            'orderGrowth'
        ));
    }
} 