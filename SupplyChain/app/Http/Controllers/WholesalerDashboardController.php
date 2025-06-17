<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\SuppliedItem;
use App\Models\SupplyRequest;

class WholesalerDashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
        if ($user->role !== 'wholesaler') {
            abort(403, 'Access denied. Wholesaler privileges required.');
        }
        
        // Stats for wholesaler dashboard
        $stats = [
            'total_orders' => 45,
            'total_spent' => '$125,000',
            'pending_shipments' => 8,
            'last_order' => '2024-06-15',
        ];

        // Recent orders data
        $recentOrders = [
            [
                'id' => 1,
                'item_summary' => 'Cotton T-Shirts (100 units)',
                'amount' => 2500.00,
                'status' => 'Delivered',
                'status_color' => 'bg-green-500',
                'icon' => 'fa-check'
            ],
            [
                'id' => 2,
                'item_summary' => 'Denim Jeans (50 units)',
                'amount' => 1800.00,
                'status' => 'In Transit',
                'status_color' => 'bg-yellow-500',
                'icon' => 'fa-shipping-fast'
            ],
            [
                'id' => 3,
                'item_summary' => 'Silk Dresses (25 units)',
                'amount' => 3200.00,
                'status' => 'Processing',
                'status_color' => 'bg-blue-500',
                'icon' => 'fa-cogs'
            ]
        ];

        return view('wholesaler.dashboard', [
            'user' => $user,
            'stats' => $stats,
            'recentOrders' => $recentOrders
        ]);
    }
}
