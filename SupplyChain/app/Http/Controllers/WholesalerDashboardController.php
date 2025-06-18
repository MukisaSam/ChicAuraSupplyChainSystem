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
        $totalSpent = Order::where('wholesaler_id', $wholesaler->id)->sum('total_amount');
        $pendingShipments = Order::where('wholesaler_id', $wholesaler->id)
            ->whereIn('status', ['confirmed', 'in_production', 'shipped'])
            ->count();
        $lastOrder = Order::where('wholesaler_id', $wholesaler->id)
            ->latest()
            ->first();
        
        $stats = [
            'total_orders' => $totalOrders,
            'total_spent' => '$' . number_format($totalSpent, 2),
            'pending_shipments' => $pendingShipments,
            'last_order' => $lastOrder ? $lastOrder->order_date->format('M d, Y') : 'N/A',
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
