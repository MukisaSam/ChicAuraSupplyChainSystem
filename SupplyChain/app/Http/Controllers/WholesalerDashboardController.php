<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class WholesalerDashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        $stats = [
            'total_orders' => 124,
            'total_spent' => '$1.4M',
            'pending_shipments' => 2,
            'last_order' => '1 week ago',
        ];

        $recentOrders = [
            ['id' => 'ORD-9954', 'item_summary' => 'Fall Collection (12 items)', 'amount' => 15200.00, 'status' => 'Delivered', 'status_color' => 'bg-green-500', 'icon' => 'fa-box-open'],
            ['id' => 'ORD-9955', 'item_summary' => 'Denim Jackets (200 units)', 'amount' => 12300.50, 'status' => 'Shipped', 'status_color' => 'bg-blue-500', 'icon' => 'fa-truck'],
            ['id' => 'ORD-9956', 'item_summary' => 'Summer Dresses (150 units)', 'amount' => 7500.00, 'status' => 'Processing', 'status_color' => 'bg-yellow-500', 'icon' => 'fa-spinner'],
        ];

        return view('wholesaler.dashboard', [
            'user' => $user,
            'stats' => $stats,
            'recentOrders' => $recentOrders,
        ]);
    }
}
