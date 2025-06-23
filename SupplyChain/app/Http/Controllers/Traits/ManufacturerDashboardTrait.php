<?php

namespace App\Http\Controllers\Traits;

use App\Models\Item;
use App\Models\Supplier;
use App\Models\Wholesaler;
use App\Models\Order;
use App\Models\SupplyRequest;
use App\Models\SuppliedItem;
use Illuminate\Support\Facades\Log;

trait ManufacturerDashboardTrait
{
    public function getBasicStats()
    {
        return [
            'raw_materials' => Item::where('category', 'raw_material')->count(),
            'products' => Item::where('category', 'finished_product')->count(),
            'suppliers' => Supplier::count(),
            'wholesalers' => Wholesaler::count(),
            'revenue' => '$' . number_format(Order::where('status', 'delivered')->sum('total_amount'), 2),
        ];
    }

    public function getRecentActivities()
    {
        try {
            $activities = collect();

            // Recent orders
            $recentOrders = Order::with('wholesaler')->latest()->take(5)->get();
            foreach ($recentOrders as $order) {
                if ($order->wholesaler) {
                    $activities->push([
                        'type' => 'order',
                        'description' => "New order #{$order->order_number} from {$order->wholesaler->name}",
                        'time' => $order->created_at->diffForHumans(),
                        'icon' => 'fa-shopping-cart',
                        'color' => 'text-blue-600',
                    ]);
                }
            }

            // Recent supply requests
            $recentSupplyRequests = SupplyRequest::with(['supplier', 'item'])->latest()->take(5)->get();
            foreach ($recentSupplyRequests as $request) {
                if ($request->supplier && $request->item) {
                    $activities->push([
                        'type' => 'supply_request',
                        'description' => "Supply request for {$request->item->name} from {$request->supplier->name}",
                        'time' => $request->created_at->diffForHumans(),
                        'icon' => 'fa-truck',
                        'color' => 'text-green-600',
                    ]);
                }
            }

            // Recent deliveries
            $recentDeliveries = SuppliedItem::with(['supplier', 'item'])->latest()->take(5)->get();
            foreach ($recentDeliveries as $delivery) {
                if ($delivery->supplier && $delivery->item) {
                    $activities->push([
                        'type' => 'delivery',
                        'description' => "Delivery received: {$delivery->item->name} from {$delivery->supplier->name}",
                        'time' => $delivery->delivery_date ? $delivery->delivery_date->diffForHumans() : 'N/A',
                        'icon' => 'fa-check-circle',
                        'color' => 'text-purple-600',
                    ]);
                }
            }

            return $activities->sortByDesc('time')->take(10)->values();
        } catch (\Exception $e) {
            Log::error('Recent Activities Error: ' . $e->getMessage());
            return collect();
        }
    }
} 