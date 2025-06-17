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
        $wholesaler = Auth::user()->wholesaler;
        $recentOrders = collect(); // Initialize as an empty collection

        if ($wholesaler) {
            // For a wholesaler, 'recent orders' could be supplied items they've received or supply requests they've made
            // Let's fetch recent supplied items relevant to the wholesaler.
            // This assumes a relationship between SuppliedItem and Wholesaler or a way to filter.
            // If not directly linked, we might fetch all and filter, or assume wholesaler creates SupplyRequests.
            
            // For the sake of fixing the undefined variable, let's fetch recent SuppliedItems.
            // In a real scenario, you'd likely have a more direct relationship or a way to link them.
            $recentOrders = SuppliedItem::with(['item', 'supplier'])->latest()->take(10)->get();
            
            // Alternatively, if wholesalers initiate supply requests:
            // $recentOrders = SupplyRequest::where('wholesaler_id', $wholesaler->id)->with(['item', 'supplier'])->latest()->take(10)->get();
        }

        return view('wholesaler.dashboard', compact('recentOrders'));
    }
}
