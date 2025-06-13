<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SupplierDashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user(supplier);

        $stats = [
            'total_supplied' => '1.2M Units',
            'rating' => '4.8 / 5.0',
            'active_requests' => 3,
            'last_supply' => '3 days ago',
        ];

        $supplyRequests = [
            ['id' => 'REQ-305', 'item' => 'Organic Cotton (200kg)', 'status' => 'Accepted', 'status_color' => 'bg-green-500', 'icon' => 'fa-check'],
            ['id' => 'REQ-304', 'item' => 'Brass Zippers (5,000 units)', 'status' => 'Pending', 'status_color' => 'bg-yellow-500', 'icon' => 'fa-clock'],
            ['id' => 'REQ-302', 'item' => 'Indigo Dye (50L)', 'status' => 'Delivered', 'status_color' => 'bg-blue-500', 'icon' => 'fa-truck'],
        ];

        return view('supplier.dashboard', [
            'user' => $user,
            'stats' => $stats,
            'supplyRequests' => $supplyRequests,
        ]);
    }//
}
