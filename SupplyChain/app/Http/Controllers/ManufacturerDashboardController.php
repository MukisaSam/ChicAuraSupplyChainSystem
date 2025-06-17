<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\SupplyRequest;

class ManufacturerDashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
        if ($user->role !== 'manufacturer') {
            abort(403, 'Access denied. Manufacturer privileges required.');
        }
        
        // Stats for manufacturer dashboard
        $stats = [
            'raw_materials' => 150,
            'products' => 85,
            'suppliers' => 12,
            'wholesalers' => 25,
            'revenue' => '125,000',
        ];

        // Recent orders data
        $recentOrders = [
            [
                'id' => 1,
                'product_name' => 'Cotton T-Shirts',
                'wholesaler_name' => 'Fashion Hub',
                'amount' => 2500.00,
                'status' => 'Completed',
                'status_color' => 'bg-green-500',
                'icon' => 'fa-check'
            ],
            [
                'id' => 2,
                'product_name' => 'Denim Jeans',
                'wholesaler_name' => 'Style Store',
                'amount' => 1800.00,
                'status' => 'In Progress',
                'status_color' => 'bg-yellow-500',
                'icon' => 'fa-clock'
            ],
            [
                'id' => 3,
                'product_name' => 'Silk Dresses',
                'wholesaler_name' => 'Luxury Boutique',
                'amount' => 3200.00,
                'status' => 'Pending',
                'status_color' => 'bg-blue-500',
                'icon' => 'fa-hourglass-half'
            ]
        ];

        return view('manufacturer.dashboard', [
            'user' => $user,
            'stats' => $stats,
            'recentOrders' => $recentOrders
        ]);
    }
}
