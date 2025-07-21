<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SupplierDashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $supplier = $user->supplier;

        // Total supplied (sum of delivered_quantity)
        $totalSupplied = $supplier->suppliedItems()->sum('delivered_quantity');

        // Quality rating (average)
        $qualityRating = $supplier->suppliedItems()->avg('quality_rating') ?? 0;

        // Active requests (not completed)
        $activeRequests = $supplier->supplyRequests()
            ->whereIn('status', ['pending', 'approved', 'in_progress'])
            ->count();

        // Last supply (latest delivery_date)
        $lastSupply = $supplier->suppliedItems()->orderByDesc('delivery_date')->first();
        $lastSupplyText = $lastSupply && $lastSupply->delivery_date
            ? $lastSupply->delivery_date->diffForHumans()
            : 'N/A';

        // Supply volume trends (for chart)
        $supplyTrends = $supplier->suppliedItems()
            ->selectRaw('MONTH(delivery_date) as month, SUM(delivered_quantity) as total')
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        // Active supply requests (for the card)
        $activeSupplyRequests = $supplier->supplyRequests()
            ->with('item')
            ->whereIn('status', ['pending', 'approved', 'in_progress'])
            ->latest()
            ->take(10)
            ->get();

        $stats = [
            'total_supplied' => $totalSupplied,
            'rating' => number_format($qualityRating, 1),
            'active_requests' => $activeRequests,
            'last_supply' => $lastSupplyText,
        ];

        return view('supplier.dashboard', [
            'user' => $user,
            'stats' => $stats,
            'supplyTrends' => $supplyTrends,
            'supplyRequests' => $activeSupplyRequests,
        ]);
    }
}
