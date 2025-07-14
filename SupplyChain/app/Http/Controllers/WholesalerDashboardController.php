<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Item;

class WholesalerDashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
        if ($user->role !== 'wholesaler') {
            abort(403, 'Access denied. Wholesaler privileges required.');
        }
        
        $wholesaler = $user->wholesaler;
        
        if (!$wholesaler) {
            abort(403, 'Wholesaler profile not found.');
        }
        
        // Get real stats from database
        $totalOrders = Order::where('wholesaler_id', $wholesaler->id)->count();
        $totalRevenue = Order::where('wholesaler_id', $wholesaler->id)->sum('total_amount');
        $avgOrderValue = $totalOrders > 0 ? $totalRevenue / $totalOrders : 0;
        $pendingOrders = Order::where('wholesaler_id', $wholesaler->id)
            ->where('status', 'pending')
            ->count();
        // Pending orders change: percent change from last month to this month
        $currentMonth = now()->month;
        $currentYear = now()->year;
        $lastMonth = now()->subMonth()->month;
        $lastMonthYear = now()->subMonth()->year;
        $pendingThisMonth = Order::where('wholesaler_id', $wholesaler->id)
            ->where('status', 'pending')
            ->whereMonth('order_date', $currentMonth)
            ->whereYear('order_date', $currentYear)
            ->count();
        $pendingLastMonth = Order::where('wholesaler_id', $wholesaler->id)
            ->where('status', 'pending')
            ->whereMonth('order_date', $lastMonth)
            ->whereYear('order_date', $lastMonthYear)
            ->count();
        $pendingOrdersChange = $pendingLastMonth > 0 ? round((($pendingThisMonth - $pendingLastMonth) / $pendingLastMonth) * 100, 1) : ($pendingThisMonth > 0 ? 100 : 0);

        $stats = [
            'total_orders' => $totalOrders,
            'total_revenue' => $totalRevenue,
            'avg_order_value' => $avgOrderValue,
            'pending_orders' => $pendingOrders,
            'pending_orders_change' => $pendingOrdersChange,
        ];

        // Get recent orders
        $recentOrders = Order::where('wholesaler_id', $wholesaler->id)
            ->with(['orderItems.item'])
            ->latest()
            ->take(5)
            ->get()
            ->map(function ($order) {
                $itemSummary = $order->orderItems->take(2)->map(function ($orderItem) {
                    return $orderItem->item->name . ' (' . $orderItem->quantity . ' units)';
                })->join(', ');
                
                if ($order->orderItems->count() > 2) {
                    $itemSummary .= ' +' . ($order->orderItems->count() - 2) . ' more';
                }
                
                return [
                    'id' => $order->id,
                    'item_summary' => $itemSummary,
                    'amount' => $order->total_amount,
                    'status' => ucfirst(str_replace('_', ' ', $order->status)),
                    'status_color' => $order->status_color,
                    'icon' => $order->status_icon,
                ];
            })
            ->toArray();

        return view('wholesaler.dashboard', [
            'user' => $user,
            'stats' => $stats,
            'recentOrders' => $recentOrders
        ]);
    }
}
