<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Models\SupplyRequest;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Item;
use App\Models\Supplier;
use App\Models\Wholesaler;
use App\Models\SuppliedItem;
use App\Models\PriceNegotiation;

class ManufacturerAnalyticsController extends Controller
{
    public function index()
    {
        try {
            $user = Auth::user();
            
            if ($user->role !== 'manufacturer') {
                abort(403, 'Access denied. Manufacturer privileges required.');
            }
            
            // Get comprehensive analytics data
            $analytics = $this->getAnalyticsData();
            
            return view('manufacturer.analytics.index', compact('analytics'));
        } catch (\Exception $e) {
            // Log the error for debugging
            \Log::error('Analytics Error: ' . $e->getMessage());
            
            // Return a fallback view with error message
            return view('manufacturer.analytics.index', [
                'analytics' => [
                    'stats' => [
                        'raw_materials' => 0,
                        'products' => 0,
                        'suppliers' => 0,
                        'wholesalers' => 0,
                        'revenue' => '$0.00',
                        'pending_orders' => 0,
                        'active_supply_requests' => 0,
                    ],
                    'error' => 'Unable to load analytics data. Please try again later.'
                ]
            ]);
        }
    }

    private function getAnalyticsData()
    {
        try {
            $now = Carbon::now();
            $lastMonth = $now->copy()->subMonth();
            $lastYear = $now->copy()->subYear();

            return [
                // Basic Stats
                'stats' => $this->getBasicStats(),
                
                // Production Analytics
                'production' => $this->getProductionAnalytics(),
                
                // Inventory Analytics
                'inventory' => $this->getInventoryAnalytics(),
                
                // Supplier Performance
                'suppliers' => $this->getSupplierPerformance(),
                
                // Revenue Analytics
                'revenue' => $this->getRevenueAnalytics(),
                
                // Order Analytics
                'orders' => $this->getOrderAnalytics(),
                
                // Customer Segmentation
                'customers' => $this->getCustomerSegmentation(),
                
                // Recent Activities
                'recentActivities' => $this->getRecentActivities(),
                
                // Time-based data for charts
                'timeData' => $this->getTimeSeriesData(),
            ];
        } catch (\Exception $e) {
            \Log::error('Analytics Data Error: ' . $e->getMessage());
            
            // Return default data structure
            return [
                'stats' => [
                    'raw_materials' => 0,
                    'products' => 0,
                    'suppliers' => 0,
                    'wholesalers' => 0,
                    'revenue' => '$0.00',
                    'pending_orders' => 0,
                    'active_supply_requests' => 0,
                ],
                'production' => [
                    'monthly_data' => [],
                    'current_efficiency' => 0,
                    'total_production_this_month' => 0,
                    'defect_rate' => 0,
                    'capacity_utilization' => 0,
                ],
                'inventory' => [
                    'total_items' => 0,
                    'low_stock_items' => 0,
                    'overstock_items' => 0,
                    'total_inventory_value' => '$0.00',
                    'average_stock_level' => 0,
                    'turnover_rate' => 0,
                ],
                'suppliers' => [
                    'suppliers' => [],
                    'avg_on_time_delivery' => 0,
                    'avg_delivery_time' => 0,
                    'top_performers' => [],
                ],
                'revenue' => [
                    'monthly_revenue' => [],
                    'current_month_revenue' => 0,
                    'growth_rate' => 0,
                    'avg_order_value' => 0,
                    'total_revenue_ytd' => 0,
                ],
                'orders' => [
                    'total_orders' => 0,
                    'status_distribution' => [],
                    'avg_processing_time' => 0,
                    'fulfillment_rate' => 0,
                    'orders_this_month' => 0,
                    'pending_orders' => 0,
                ],
                'customers' => [
                    'segments' => [],
                    'segment_distribution' => [],
                    'premium_wholesalers' => [],
                    'total_wholesalers' => 0,
                ],
                'recentActivities' => [],
                'timeData' => [
                    'labels' => [],
                    'production_data' => [],
                    'revenue_data' => [],
                    'orders_data' => [],
                ],
            ];
        }
    }

    private function getBasicStats()
    {
        return [
            'raw_materials' => Item::where('category', 'raw_material')->count(),
            'products' => Item::where('category', 'finished_product')->count(),
            'suppliers' => Supplier::count(),
            'wholesalers' => Wholesaler::count(),
            'revenue' => '$' . number_format(Order::where('status', 'delivered')->sum('total_amount'), 2),
            'pending_orders' => Order::whereIn('status', ['pending', 'confirmed'])->count(),
            'active_supply_requests' => SupplyRequest::whereIn('status', ['pending', 'approved'])->count(),
        ];
    }

    private function getProductionAnalytics()
    {
        $last6Months = collect();
        for ($i = 5; $i >= 0; $i--) {
            $month = Carbon::now()->subMonths($i);
            $last6Months->push([
                'month' => $month->format('M Y'),
                'production_volume' => rand(500, 2000), // Mock data - replace with actual production data
                'efficiency' => rand(75, 95),
                'defects' => rand(10, 50),
            ]);
        }

        return [
            'monthly_data' => $last6Months,
            'current_efficiency' => 87.5,
            'total_production_this_month' => 1850,
            'defect_rate' => 2.3,
            'capacity_utilization' => 78.2,
        ];
    }

    private function getInventoryAnalytics()
    {
        $items = Item::with('suppliedItems')->get();
        
        $lowStockItems = $items->filter(function ($item) {
            return $item->stock_quantity < 50;
        })->count();

        $overstockItems = $items->filter(function ($item) {
            return $item->stock_quantity > 500;
        })->count();

        $totalInventoryValue = $items->sum(function ($item) {
            return $item->stock_quantity * $item->base_price;
        });

        // Fix potential division by zero in average calculation
        $averageStockLevel = $items->count() > 0 ? $items->avg('stock_quantity') : 0;

        return [
            'total_items' => $items->count(),
            'low_stock_items' => $lowStockItems,
            'overstock_items' => $overstockItems,
            'total_inventory_value' => '$' . number_format($totalInventoryValue, 2),
            'average_stock_level' => $averageStockLevel,
            'turnover_rate' => 4.2, // Mock data - replace with actual calculation
        ];
    }

    private function getSupplierPerformance()
    {
        $suppliers = Supplier::with(['suppliedItems' => function ($query) {
            $query->where('delivery_date', '>=', Carbon::now()->subMonths(6));
        }])->get();

        $supplierMetrics = $suppliers->map(function ($supplier) {
            $totalDeliveries = $supplier->suppliedItems->count();
            $onTimeDeliveries = $supplier->suppliedItems->filter(function ($item) {
                return $item->delivery_date <= $item->supplyRequest->due_date;
            })->count();
            
            $avgDeliveryTime = $supplier->suppliedItems->avg(function ($item) {
                return Carbon::parse($item->delivery_date)->diffInDays($item->supplyRequest->created_at);
            });

            return [
                'id' => $supplier->id,
                'name' => $supplier->name,
                'on_time_delivery_rate' => $totalDeliveries > 0 ? round(($onTimeDeliveries / $totalDeliveries) * 100, 1) : 0,
                'avg_delivery_time' => round($avgDeliveryTime ?? 0, 1),
                'total_deliveries' => $totalDeliveries,
                'quality_rating' => rand(70, 100), // Mock data - replace with actual quality metrics
            ];
        });

        // Fix potential division by zero in averages
        $avgOnTimeDelivery = $supplierMetrics->count() > 0 ? $supplierMetrics->avg('on_time_delivery_rate') : 0;
        $avgDeliveryTime = $supplierMetrics->count() > 0 ? $supplierMetrics->avg('avg_delivery_time') : 0;

        return [
            'suppliers' => $supplierMetrics,
            'avg_on_time_delivery' => $avgOnTimeDelivery,
            'avg_delivery_time' => $avgDeliveryTime,
            'top_performers' => $supplierMetrics->sortByDesc('on_time_delivery_rate')->take(5),
        ];
    }

    private function getRevenueAnalytics()
    {
        $monthlyRevenue = Order::selectRaw('
            DATE_FORMAT(order_date, "%Y-%m") as month,
            SUM(total_amount) as revenue,
            COUNT(*) as order_count
        ')
        ->where('status', 'delivered')
        ->where('order_date', '>=', Carbon::now()->subMonths(12))
        ->groupBy('month')
        ->orderBy('month')
        ->get();

        $currentMonthRevenue = Order::where('status', 'delivered')
            ->whereMonth('order_date', Carbon::now()->month)
            ->sum('total_amount');

        $lastMonthRevenue = Order::where('status', 'delivered')
            ->whereMonth('order_date', Carbon::now()->subMonth()->month)
            ->sum('total_amount');

        $growthRate = $lastMonthRevenue > 0 ? 
            (($currentMonthRevenue - $lastMonthRevenue) / $lastMonthRevenue) * 100 : 0;

        // Fix potential division by zero in average calculation
        $avgOrderValue = Order::where('status', 'delivered')->count() > 0 ? 
            Order::where('status', 'delivered')->avg('total_amount') : 0;

        return [
            'monthly_revenue' => $monthlyRevenue,
            'current_month_revenue' => $currentMonthRevenue,
            'growth_rate' => round($growthRate, 1),
            'avg_order_value' => $avgOrderValue,
            'total_revenue_ytd' => Order::where('status', 'delivered')
                ->whereYear('order_date', Carbon::now()->year)
                ->sum('total_amount'),
        ];
    }

    private function getOrderAnalytics()
    {
        $orders = Order::with('orderItems')->get();

        $orderStatusDistribution = $orders->groupBy('status')->map(function ($group) {
            return $group->count();
        });

        $avgProcessingTime = $orders->where('status', 'delivered')->avg(function ($order) {
            return Carbon::parse($order->order_date)->diffInDays($order->actual_delivery);
        });

        // Fix division by zero error
        $totalOrders = $orders->count();
        $fulfillmentRate = $totalOrders > 0 ? 
            ($orders->whereIn('status', ['delivered', 'shipped'])->count() / $totalOrders) * 100 : 0;

        return [
            'total_orders' => $totalOrders,
            'status_distribution' => $orderStatusDistribution,
            'avg_processing_time' => round($avgProcessingTime ?? 0, 1),
            'fulfillment_rate' => round($fulfillmentRate, 1),
            'orders_this_month' => $orders->where('order_date', '>=', Carbon::now()->startOfMonth())->count(),
            'pending_orders' => $orders->whereIn('status', ['pending', 'confirmed'])->count(),
        ];
    }

    private function getCustomerSegmentation()
    {
        $wholesalers = Wholesaler::with(['orders' => function ($query) {
            $query->where('order_date', '>=', Carbon::now()->subMonths(6));
        }])->get();

        $segments = $wholesalers->map(function ($wholesaler) {
            $totalOrders = $wholesaler->orders->count();
            $totalSpent = $wholesaler->orders->sum('total_amount');
            $avgOrderValue = $totalOrders > 0 ? $totalSpent / $totalOrders : 0;

            // Simple segmentation based on order frequency and value
            if ($totalOrders >= 10 && $avgOrderValue >= 1000) {
                $segment = 'Premium';
                $color = 'bg-purple-100 text-purple-800';
            } elseif ($totalOrders >= 5 && $avgOrderValue >= 500) {
                $segment = 'Regular';
                $color = 'bg-blue-100 text-blue-800';
            } elseif ($totalOrders >= 2) {
                $segment = 'Occasional';
                $color = 'bg-yellow-100 text-yellow-800';
            } else {
                $segment = 'New';
                $color = 'bg-gray-100 text-gray-800';
            }

            return [
                'id' => $wholesaler->id,
                'name' => $wholesaler->name,
                'segment' => $segment,
                'color' => $color,
                'total_orders' => $totalOrders,
                'total_spent' => $totalSpent,
                'avg_order_value' => $avgOrderValue,
                'last_order' => $wholesaler->orders->max('order_date'),
            ];
        });

        return [
            'segments' => $segments,
            'segment_distribution' => $segments->groupBy('segment')->map(function ($group) {
                return $group->count();
            }),
            'premium_wholesalers' => $segments->where('segment', 'Premium'),
            'total_wholesalers' => $segments->count(),
        ];
    }

    private function getRecentActivities()
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
                        'time' => $delivery->delivery_date->diffForHumans(),
                        'icon' => 'fa-check-circle',
                        'color' => 'text-purple-600',
                    ]);
                }
            }

            return $activities->sortByDesc('time')->take(10)->values();
        } catch (\Exception $e) {
            \Log::error('Recent Activities Error: ' . $e->getMessage());
            return collect();
        }
    }

    private function getTimeSeriesData()
    {
        $months = collect();
        for ($i = 11; $i >= 0; $i--) {
            $month = Carbon::now()->subMonths($i);
            $months->push($month->format('M Y'));
        }

        return [
            'labels' => $months->toArray(),
            'production_data' => [65, 59, 80, 81, 56, 55, 70, 85, 90, 75, 88, 92],
            'revenue_data' => [12000, 15000, 18000, 16000, 14000, 17000, 20000, 22000, 19000, 21000, 23000, 25000],
            'orders_data' => [45, 52, 68, 74, 58, 62, 78, 85, 72, 88, 95, 102],
        ];
    }

    // API endpoints for AJAX requests
    public function getChartData(Request $request)
    {
        $chartType = $request->get('type', 'production');
        
        switch ($chartType) {
            case 'production':
                return response()->json($this->getProductionAnalytics());
            case 'revenue':
                return response()->json($this->getRevenueAnalytics());
            case 'inventory':
                return response()->json($this->getInventoryAnalytics());
            default:
                return response()->json(['error' => 'Invalid chart type']);
        }
    }

    public function getSupplierReport()
    {
        $supplierData = $this->getSupplierPerformance();
        return view('manufacturer.analytics.supplier-report', compact('supplierData'));
    }

    public function getCustomerReport()
    {
        $wholesalerData = $this->getCustomerSegmentation();
        return view('manufacturer.analytics.wholesaler-report', compact('wholesalerData'));
    }
}
