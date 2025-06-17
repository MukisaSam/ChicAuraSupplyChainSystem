<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\SupplyRequest;

class ManufacturerDashboardController extends Controller
{
    public function index()
    {
        $manufacturer = Auth::user()->manufacturer;
        $recentOrders = collect();

        if ($manufacturer) {
            $recentOrders = SupplyRequest::with(['item', 'supplier'])->latest()->take(10)->get();
        }

        return view('manufacturer.dashboard', compact('recentOrders'));
    }
}
