<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ManufacturerDashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user(manufacturer);

        $stats = [
            'raw_materials' => 152,
            'products' => 84,
            'suppliers' => 25,
            'wholesalers' => 42,
        ];

        $recentOrders = [
            ['id' => 'ORD-7892', 'product_name' => 'Summer Collection Dresses', 'wholesaler_name' => 'Fashion Hub Inc.', 'amount' => 7500.00, 'status' => 'Shipped', 'status_color' => 'bg-green-500', 'icon' => 'fa-check'],
            ['id' => 'ORD-7891', 'product_name' => 'Denim Jackets', 'wholesaler_name' => 'Urban Outfitters Co.', 'amount' => 12300.50, 'status' => 'Processing', 'status_color' => 'bg-yellow-500', 'icon' => 'fa-spinner'],
            ['id' => 'ORD-7890', 'product_name' => 'Cotton T-Shirts', 'wholesaler_name' => 'Retail Express', 'amount' => 4200.00, 'status' => 'Pending', 'status_color' => 'bg-red-500', 'icon' => 'fa-clock'],
        ];

        return view('manufacturer.dashboard', [
            'user' => $user,
            'stats' => $stats,
            'recentOrders' => $recentOrders,
        ]);
    }
}
