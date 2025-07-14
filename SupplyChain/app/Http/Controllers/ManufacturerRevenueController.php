<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\SupplyRequest;

class ManufacturerRevenueController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
        if ($user->role !== 'manufacturer') {
            abort(403, 'Access denied. Manufacturer privileges required.');
        }
        
        // Revenue stats
        $stats = [
            'revenue' => '$' . number_format(\App\Models\Order::where('status', 'delivered')->sum('total_amount'), 2),
            'products' => \App\Models\Item::count(),
            'raw_materials' => \App\Models\SupplyRequest::sum('quantity'),
            'suppliers' => \App\Models\Supplier::count(),
        ];

        // Recent activities (last 7 days)
        $recentOrders = \App\Models\Order::where('created_at', '>=', now()->subDays(7))
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get()
            ->map(function($order) {
                return [
                    'description' => 'Order #' . $order->order_number . ' (' . ucfirst($order->status) . ')',
                    'icon' => $order->getStatusIconAttribute(),
                    'color' => $order->getStatusColorAttribute(),
                    'time' => $order->created_at ? $order->created_at->diffForHumans() : 'N/A',
                ];
            });
        $recentSupplyRequests = \App\Models\SupplyRequest::where('created_at', '>=', now()->subDays(7))
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get()
            ->map(function($req) {
                return [
                    'description' => 'Supply Request for ' . ($req->item->name ?? 'Item') . ' (' . ucfirst($req->status) . ')',
                    'icon' => 'fa-truck',
                    'color' => 'text-yellow-600',
                    'time' => $req->created_at ? $req->created_at->diffForHumans() : 'N/A',
                ];
            });
        $recentActivities = collect($recentOrders)->merge(collect($recentSupplyRequests))->sortByDesc('time')->take(7)->values();

        return view('manufacturer.Revenue.index', compact('stats', 'recentActivities'));
    }
}
