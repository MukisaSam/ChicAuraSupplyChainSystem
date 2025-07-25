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
                
                // Traditional Supplier Performance
                'suppliers' => $this->getSupplierPerformance(),
                
                // ML Supplier Insights
                'mlSupplierInsights' => $this->getMLSupplierInsights(),
                
                // Revenue Analytics
                'revenue' => $this->getRevenueAnalytics(),
                
                // Order Analytics
                'orders' => $this->getOrderAnalytics(),
                
                // Customer Segmentation
                'customers' => $this->getCustomerSegmentation(),
                
                // Wholesaler Segmentation (ML-Powered)
                'wholesalerSegmentation' => $this->getWholesalerSegmentation(),
                
                // Recent Activities
                'recentActivities' => $this->getRecentActivities(),
                
                // Time-based data for charts
                'timeData' => $this->getTimeSeriesData(),
            ];
        } catch (\Exception $e) {
            \Log::error('Analytics Data Error: ' . $e->getMessage());
            
            // Return default structure
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
                'mlSupplierInsights' => [
                    'error' => 'Failed to load ML insights',
                    'last_updated' => null
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
                // Add wholesaler segmentation fallback
                'wholesalerSegmentation' => [
                    'error' => 'Failed to load segmentation data',
                    'last_updated' => null
                ],
            ];
        }
    }

    private function getBasicStats()
    {
        return [
            'raw_materials' => Item::where('type', 'raw_material')->count(),
            'products' => Item::where('type', 'finished_product')->count(),
            'suppliers' => Supplier::count(),
            'wholesalers' => Wholesaler::count(),
            'revenue' => number_format(Order::where('status', 'delivered')->sum('total_amount'), 2),
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
        try {
            \Log::info('Getting supplier performance data');
            
            // Check if required tables exist
            $suppliedItemsExists = \Schema::hasTable('supplied_items');
            $suppliersExists = \Schema::hasTable('suppliers');
            
            \Log::info('Tables exist check: supplied_items=' . ($suppliedItemsExists ? 'Yes' : 'No') . 
                      ', suppliers=' . ($suppliersExists ? 'Yes' : 'No'));
            
            if (!$suppliersExists) {
                return ['error' => 'Suppliers table does not exist'];
            }
            
            $query = DB::table('suppliers')
                ->select(
                    'suppliers.id',
                    'suppliers.name',
                    'suppliers.email',
                    'suppliers.phone',
                    DB::raw('COUNT(DISTINCT CASE WHEN supply_requests.status = "delivered" THEN supply_requests.id ELSE NULL END) as completed_requests'),
                    DB::raw('AVG(CASE WHEN supply_requests.status = "delivered" THEN DATEDIFF(supply_requests.delivered_at, supply_requests.created_at) ELSE NULL END) as avg_delivery_days')
                )
                ->leftJoin('supply_requests', 'suppliers.id', '=', 'supply_requests.supplier_id');
                
            // Only join supplied_items if the table exists
            if ($suppliedItemsExists) {
                \Log::info('Including supplied_items in supplier performance query');
                try {
                    $query->leftJoin('supplied_items', 'supply_requests.id', '=', 'supplied_items.supply_request_id');
                } catch (\Exception $e) {
                    \Log::error('Error joining supplied_items: ' . $e->getMessage());
                    // Continue without the join
                }
            } else {
                \Log::info('Skipping supplied_items join in supplier performance query');
            }
                
            $suppliers = $query->groupBy('suppliers.id', 'suppliers.name', 'suppliers.email', 'suppliers.phone')
                ->orderBy('completed_requests', 'desc')
                ->limit(10)
                ->get();
            
            \Log::info('Found ' . count($suppliers) . ' suppliers for performance data');
            
            return [
                'suppliers' => $suppliers,
                'total' => DB::table('suppliers')->count(),
                'with_completed_deliveries' => DB::table('suppliers')
                    ->whereExists(function ($query) {
                        $query->select(DB::raw(1))
                            ->from('supply_requests')
                            ->whereColumn('supply_requests.supplier_id', 'suppliers.id')
                            ->where('supply_requests.status', 'delivered');
                    })
                    ->count()
            ];
        } catch (\Exception $e) {
            \Log::error('Error in getSupplierPerformance: ' . $e->getMessage());
            return [
                'suppliers' => [],
                'total' => 0,
                'with_completed_deliveries' => 0,
                'error' => $e->getMessage()
            ];
        }
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
        $wholesalers = Wholesaler::with(['user', 'orders' => function ($query) {
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
                'name' => $wholesaler->user ? $wholesaler->user->name : '',
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
                        'time' => $order->created_at ? $order->created_at->diffForHumans() : 'N/A',
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
                        'time' => $request->created_at ? $request->created_at->diffForHumans() : 'N/A',
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
            \Log::error('Recent Activities Error: ' . $e->getMessage());
            return collect();
        }
    }

    private function getTimeSeriesData()
    {
        $months = collect();
        $productionData = [];
        $revenueData = [];
        $ordersData = [];

        for ($i = 11; $i >= 0; $i--) {
            $month = \Carbon\Carbon::now()->subMonths($i);
            $label = $month->format('M Y');
            $months->push($label);

            // Get all orders for this month
            $orders = \App\Models\Order::whereYear('order_date', $month->year)
                ->whereMonth('order_date', $month->month)
                ->get();

            // Sum production volume (sum of all order item quantities)
            $production = 0;
            foreach ($orders as $order) {
                $production += $order->orderItems->sum('quantity');
            }
            $productionData[] = $production;

            // Sum revenue (total_amount)
            $revenue = $orders->sum('total_amount');
            $revenueData[] = (float) $revenue;

            // Count orders
            $ordersData[] = $orders->count();
        }

        return [
            'labels' => $months->toArray(),
            'production_data' => $productionData,
            'revenue_data' => $revenueData,
            'orders_data' => $ordersData,
        ];
    }

    // API endpoints for AJAX requests
    // public function getChartData(Request $request)
    // {
    //     $chartType = $request->get('type', 'timeseries');
        
    //     switch ($chartType) {
    //         case 'production':
    //             return response()->json($this->getProductionAnalytics());
    //         case 'revenue':
    //             return response()->json($this->getRevenueAnalytics());
    //         case 'inventory':
    //             return response()->json($this->getInventoryAnalytics());
    //         case 'timeseries':
    //         default:
    //             return response()->json(['timeData' => $this->getTimeSeriesData()]);
    //     }
    // }
public function getChartData(Request $request)
{
    $chartType = $request->get('type', 'timeseries');
    
    switch ($chartType) {
        case 'production':
            return response()->json($this->getProductionAnalytics());
        case 'revenue':
            return response()->json($this->getRevenueAnalytics());
        case 'inventory':
            return response()->json($this->getInventoryAnalytics());
        case 'products':
            return response()->json(['products' => $this->getProductQuantityData()]);
        case 'timeseries':
        default:
            return response()->json(['timeData' => $this->getTimeSeriesData()]);
    }
}

private function getProductQuantityData()
{
    // Get all finished products
    $products = Item::where('type', 'finished_product')
        ->get()
        ->map(function($product) {
            // Get monthly quantity data for this product
            $monthlyData = OrderItem::join('orders', 'order_items.order_id', '=', 'orders.id')
                ->where('order_items.item_id', $product->id)
                ->where('orders.created_at', '>=', Carbon::now()->subMonths(6))
                ->selectRaw('MONTH(orders.created_at) as month, YEAR(orders.created_at) as year, SUM(order_items.quantity) as quantity')
                ->groupBy('year', 'month')
                ->orderBy('year')
                ->orderBy('month')
                ->get();
                
            $dates = [];
            $quantities = [];
            
            // Fill in data for the last 6 months
            for ($i = 5; $i >= 0; $i--) {
                $date = Carbon::now()->subMonths($i);
                $monthNum = $date->month;
                $yearNum = $date->year;
                $dateLabel = $date->format('M Y');
                
                $monthData = $monthlyData
                    ->where('month', $monthNum)
                    ->where('year', $yearNum)
                    ->first();
                
                $dates[] = $dateLabel;
                $quantities[] = $monthData ? $monthData->quantity : 0;
            }
            
            return [
                'id' => $product->id,
                'name' => $product->name,
                'dates' => $dates,
                'quantities' => $quantities
            ];
        });

    return $products;
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

    // Forecast API endpoints
    public function getForecastOptions()
    {
        try {
            $products = Item::select('name', 'base_price')
                ->where('is_active', true)
                ->where('type', 'finished_product')
                ->get()
                ->map(function ($item) {
                    return [
                        'name' => $item->name,
                        'price' => $item->base_price,
                        'default_price' => $item->base_price // Add default price for reference
                    ];
                });

            $locations = DB::table('orders')
                ->select('delivery_address as location')
                ->distinct()
                ->get()
                ->pluck('location');

            // Add some default locations if database is empty
            if ($locations->isEmpty()) {
                $locations = collect(['Wakiso', 'Kampala', 'Entebbe', 'Jinja', 'Gulu']);
            }
            
            // Add "Countrywide" as the first option
            $locations = $locations->prepend('Countrywide');

            return response()->json([
                'products' => $products,
                'locations' => $locations
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to fetch options'], 500);
        }
    }

    public function generateForecast(Request $request)
    {
        try {
            $request->validate([
                'product_name' => 'required|string',
                'unit_price' => 'required|numeric|min:0',
                'custom_price' => 'nullable|numeric|min:0', // Allow custom price input
                'location' => 'required|string'
            ]);

            $productName = $request->input('product_name');
            $defaultUnitPrice = $request->input('unit_price'); // Original/default price
            $customPrice = $request->input('custom_price'); // Custom price if provided
            $unitPrice = $customPrice ?? $defaultUnitPrice; // Use custom price if provided, otherwise default
            $location = $request->input('location');

            // Ensure forecast_plots directory exists
            $forecastDir = public_path('forecast_plots');
            if (!is_dir($forecastDir)) {
                mkdir($forecastDir, 0755, true);
            }

            $baseDir = base_path('../ml_models');
            
            // Check if model files exist
            if (!file_exists($baseDir . '/demand_model.pkl')) {
                return response()->json([
                    'error' => 'ML model not found. Please train the model first.',
                    'details' => 'demand_model.pkl is missing. Run: python demand_model.py'
                ], 500);
            }

            // Find Python executable
            $pythonPaths = [
                'python',
                'python3',
                'py',
                'C:\\Python311\\python.exe',
            ];

            $pythonExe = null;
            foreach ($pythonPaths as $path) {
                $testCommand = "$path --version 2>&1";
                $output = shell_exec($testCommand);
                if ($output && strpos($output, 'Python') !== false) {
                    $pythonExe = $path;
                    break;
                }
            }

            if (!$pythonExe) {
                return response()->json([
                    'error' => 'Python executable not found',
                    'details' => 'Please ensure Python is installed and accessible from the web server'
                ], 500);
            }

            \Log::info("Using Python executable: $pythonExe");
            \Log::info("Generating forecast with price: $unitPrice (default: $defaultUnitPrice)");

            // Execute daily forecast with the selected unit price
            $dailyCommand = "cd /d \"$baseDir\" && $pythonExe forecast_and_plot_daily.py --product_name \"$productName\" --unit_price $unitPrice --location \"$location\" --output_dir \"$forecastDir\" 2>&1";
            $dailyOutput = shell_exec($dailyCommand);

            // Execute monthly forecast with the selected unit price
            $monthlyCommand = "cd /d \"$baseDir\" && $pythonExe forecast_and_plot_monthly.py --product_name \"$productName\" --unit_price $unitPrice --location \"$location\" --output_dir \"$forecastDir\" 2>&1";
            $monthlyOutput = shell_exec($monthlyCommand);

            // Log outputs for debugging
            \Log::info('Daily forecast output: ' . $dailyOutput);
            \Log::info('Monthly forecast output: ' . $monthlyOutput);

            // Check for errors
            $dailySuccess = $dailyOutput && strpos($dailyOutput, 'SUCCESS:') !== false && strpos($dailyOutput, 'ERROR:') === false;
            $monthlySuccess = $monthlyOutput && strpos($monthlyOutput, 'SUCCESS:') !== false && strpos($monthlyOutput, 'ERROR:') === false;

            if (!$dailySuccess && !$monthlySuccess) {
                return response()->json([
                    'error' => 'Both forecast scripts failed',
                    'daily_output' => $dailyOutput,
                    'monthly_output' => $monthlyOutput
                ], 500);
            }

            // Use consistent filenames
            $dailyImageFilename = 'forecast_daily_latest.png';
            $monthlyImageFilename = 'forecast_monthly_latest.png';

            // Generate URLs for the images
            $dailyImageUrl = null;
            $monthlyImageUrl = null;
            
            if ($dailySuccess) {
                $dailyImagePath = public_path('forecast_plots/' . $dailyImageFilename);
                if (file_exists($dailyImagePath)) {
                    $dailyImageUrl = asset('forecast_plots/' . $dailyImageFilename) . '?t=' . time();
                }
            }
            
            if ($monthlySuccess) {
                $monthlyImagePath = public_path('forecast_plots/' . $monthlyImageFilename);
                if (file_exists($monthlyImagePath)) {
                    $monthlyImageUrl = asset('forecast_plots/' . $monthlyImageFilename) . '?t=' . time();
                }
            }

            return response()->json([
                'success' => true,
                'daily_image_url' => $dailyImageUrl,
                'monthly_image_url' => $monthlyImageUrl,
                'product_name' => $productName,
                'location' => $location,
                'unit_price' => $unitPrice,
                'default_price' => $defaultUnitPrice,
                'is_custom_price' => $customPrice !== null,
                'debug' => [
                    'python_exe' => $pythonExe,
                    'daily_success' => $dailySuccess,
                    'monthly_success' => $monthlySuccess,
                    'daily_filename' => $dailyImageFilename,
                    'monthly_filename' => $monthlyImageFilename,
                    'forecast_dir' => $forecastDir,
                    'timestamp' => time(),
                    'price_used' => $unitPrice,
                    'price_type' => $customPrice !== null ? 'custom' : 'default'
                ]
            ]);

        } catch (\Exception $e) {
            \Log::error('Forecast Generation Error: ' . $e->getMessage());
            return response()->json([
                'error' => 'Failed to generate forecast: ' . $e->getMessage(),
                'details' => $e->getTraceAsString()
            ], 500);
        }
    }

    private function getMLSupplierInsights()
    {
        try {
            // Look for the fixed filename instead of timestamped files
            $insightsFile = public_path('supplier_insights.json');
            
            if (!file_exists($insightsFile)) {
                return [
                    'error' => 'No ML supplier insights available',
                    'last_updated' => null,
                    'message' => 'Run the supplier performance analysis to generate insights'
                ];
            }

            $insights = json_decode(file_get_contents($insightsFile), true);
            
            if (json_last_error() !== JSON_ERROR_NONE) {
                return [
                    'error' => 'Failed to parse ML insights data',
                    'last_updated' => null
                ];
            }

            // Add file metadata
            $insights['last_updated'] = filemtime($insightsFile);
            $insights['file_name'] = basename($insightsFile);
            $insights['file_path'] = $insightsFile;

            return $insights;
        } catch (\Exception $e) {
            \Log::error('Failed to load ML supplier insights: ' . $e->getMessage());
            return [
                'error' => 'Failed to load supplier insights: ' . $e->getMessage(),
                'last_updated' => null
            ];
        }
    }

    public function refreshSupplierInsights()
    {
        try {
            // Trigger ML model to generate new insights
            $baseDir = base_path('../ml_models');
            $command = "cd $baseDir && python3 supplier_performance.py 2>&1";
            
            $output = shell_exec($command);
            
            if (!$output) {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to execute supplier analysis script'
                ]);
            }

            // Check if analysis was successful by looking for the insights file
            $insightsFile = public_path('supplier_insights.json');
            
            if (!file_exists($insightsFile)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Supplier analysis completed but no insights file was generated',
                    'output' => $output
                ]);
            }

            // Check if file was updated recently (within last 2 minutes)
            $fileTime = filemtime($insightsFile);
            
            if (time() - $fileTime < 120) {
                return response()->json([
                    'success' => true,
                    'message' => 'Supplier insights refreshed successfully',
                    'file_updated' => date('Y-m-d H:i:s', $fileTime)
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Analysis may have failed - insights file was not updated recently',
                    'output' => $output
                ]);
            }
        } catch (\Exception $e) {
            \Log::error('Supplier insights refresh error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error refreshing supplier insights: ' . $e->getMessage()
            ]);
        }
    }

    public function getSupplierInsightsDetails()
    {
        try {
            $insights = $this->getMLSupplierInsights();
            
            if (isset($insights['error'])) {
                return response()->json([
                    'success' => false,
                    'message' => $insights['error']
                ]);
            }
            
            return response()->json([
                'success' => true,
                'data' => $insights
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error loading supplier insights: ' . $e->getMessage()
            ]);
        }
    }

    public function downloadSupplierInsights()
    {
        try {
            $insights = $this->getMLSupplierInsights();
            
            if (isset($insights['error'])) {
                return response()->json([
                    'success' => false,
                    'message' => $insights['error']
                ]);
            }
            
            $fileName = 'supplier_insights_' . date('Y-m-d_H-i-s') . '.json';
            
            return response()->json($insights)
                ->header('Content-Type', 'application/json')
                ->header('Content-Disposition', 'attachment; filename="' . $fileName . '"');
                
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error downloading supplier insights: ' . $e->getMessage()
            ]);
        }
    }

    public function refreshMLSystem(Request $request)
    {
        try {
            $model = $request->input('model');
            $individual = $request->input('individual', false);
            $step = $request->input('step', 1);
            $totalSteps = $request->input('total_steps', 1);
            
            \Log::info("Refreshing ML system - Model: {$model}, Individual: {$individual}, Step: {$step}/{$totalSteps}");
            
            $baseDir = base_path('../ml_models');
            
            if (!is_dir($baseDir)) {
                return response()->json([
                    'success' => false,
                    'message' => 'ML models directory not found at: ' . $baseDir
                ], 500);
            }
            
            $results = [];
            
            switch ($model) {
                case 'demand':
                    $results = $this->retrainDemandModel($baseDir);
                    break;
                    
                case 'supplier':
                    $results = $this->refreshSupplierPerformance($baseDir);
                    break;
                    
                // case 'recommendations':
                //     $results = $this->refreshRecommendationSystem($baseDir);
                //     break;
                case 'wholesaler':
                    $results = $this->processWholesalerSegmentation($baseDir);
                    break;
                    
                default:
                    return response()->json([
                        'success' => false,
                        'message' => 'Invalid model specified: ' . $model
                    ], 400);
            }
            
            if ($results['success']) {
                return response()->json([
                    'success' => true,
                    'message' => $results['message'],
                    'model' => $model,
                    'step' => $step,
                    'total_steps' => $totalSteps,
                    'details' => $results['details'] ?? null
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => $results['message'],
                    'error_details' => $results['error_details'] ?? null
                ], 500);
            }
            
        } catch (\Exception $e) {
            \Log::error('ML System Refresh Error: ' . $e->getMessage());
            \Log::error('Stack trace: ' . $e->getTraceAsString());
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while refreshing ML system: ' . $e->getMessage(),
                'debug' => [
                    'file' => $e->getFile(),
                    'line' => $e->getLine(),
                    'trace' => $e->getTraceAsString()
                ]
            ], 500);
        }
    }

    private function retrainDemandModel($baseDir)
    {
        try {
            // Find Python executable
            $pythonExe = $this->findPythonExecutable();
            if (!$pythonExe) {
                return [
                    'success' => false,
                    'message' => 'Python executable not found. Please ensure Python is installed and accessible.'
                ];
            }
            
            \Log::info("Retraining demand model using Python: {$pythonExe}");
            
            // Check if demand_model.py exists
            if (!file_exists($baseDir . '/demand_model.py')) {
                return [
                    'success' => false,
                    'message' => 'demand_model.py not found in: ' . $baseDir
                ];
            }
            
            // Execute demand model training with UTF-8 encoding
            $command = "cd /d \"{$baseDir}\" && \"{$pythonExe}\" demand_model.py 2>&1";
            
            // Set UTF-8 environment
            putenv('PYTHONIOENCODING=utf-8');
            
            $output = shell_exec($command);
            
            // Clean the output to remove any malformed UTF-8 characters
            if ($output) {
                $output = mb_convert_encoding($output, 'UTF-8', 'UTF-8');
                $output = preg_replace('/[\x00-\x08\x0B\x0C\x0E-\x1F\x7F]/', '', $output);
            }
            
            \Log::info("Demand model training command: " . $command);
            \Log::info("Demand model training output: " . substr($output ?? '', 0, 1000)); // Limit log output
            
            // Check if model files were created
            $modelFile = $baseDir . '/demand_model.pkl';
            $featuresFile = $baseDir . '/model_features.pkl';
            $encodersFile = $baseDir . '/label_encoders.pkl';
            
            // Also check for success indicators in output
            $hasSuccess = $output && (strpos($output, 'Model training completed') !== false || strpos($output, 'successfully') !== false);
            $hasError = $output && (strpos($output, 'ERROR') !== false || strpos($output, 'Error') !== false || strpos($output, 'Traceback') !== false);
            
            if ((file_exists($modelFile) && file_exists($featuresFile)) || $hasSuccess) {
                return [
                    'success' => true,
                    'message' => 'Demand model retrained successfully',
                    'details' => [
                        'model_file_exists' => file_exists($modelFile),
                        'features_file_exists' => file_exists($featuresFile),
                        'encoders_exists' => file_exists($encodersFile),
                        'model_file_size' => file_exists($modelFile) ? filesize($modelFile) : 0,
                        'output_preview' => substr($output ?? '', 0, 500)
                    ]
                ];
            } else {
                return [
                    'success' => false,
                    'message' => 'Model training failed - required files not created or errors found',
                    'error_details' => [
                        'output_preview' => substr($output ?? '', 0, 1000),
                        'model_exists' => file_exists($modelFile),
                        'features_exists' => file_exists($featuresFile),
                        'has_error' => $hasError,
                        'command' => $command
                    ]
                ];
            }
            
        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => 'Failed to retrain demand model: ' . $e->getMessage(),
                'error_details' => $e->getTraceAsString()
            ];
        }
    }

    private function refreshSupplierPerformance($baseDir)
    {
        try {
            $pythonExe = $this->findPythonExecutable();
            if (!$pythonExe) {
                return [
                    'success' => false,
                    'message' => 'Python executable not found'
                ];
            }
            
            \Log::info("Refreshing supplier performance analysis using Python: {$pythonExe}");
            
            // Check if supplier_performance.py exists
            if (!file_exists($baseDir . '/supplier_performance.py')) {
                return [
                    'success' => false,
                    'message' => 'supplier_performance.py not found in: ' . $baseDir
                ];
            }
            
            // Execute supplier performance analysis with UTF-8 encoding
            $command = "cd /d \"{$baseDir}\" && \"{$pythonExe}\" supplier_performance.py 2>&1";
            
            // Set UTF-8 environment
            putenv('PYTHONIOENCODING=utf-8');
            
            $output = shell_exec($command);
            
            // Clean the output
            if ($output) {
                $output = mb_convert_encoding($output, 'UTF-8', 'UTF-8');
                $output = preg_replace('/[\x00-\x08\x0B\x0C\x0E-\x1F\x7F]/', '', $output);
            }
            
            \Log::info("Supplier performance analysis output: " . substr($output ?? '', 0, 1000));
            
            // Check if analysis completed successfully
            $insightsFile = public_path('supplier_insights.json');
            $fileUpdated = file_exists($insightsFile) && (time() - filemtime($insightsFile)) < 300;
            $hasSuccess = $output && (strpos($output, 'SUCCESS') !== false || strpos($output, 'completed') !== false);
            
            if ($fileUpdated || $hasSuccess) {
                return [
                    'success' => true,
                    'message' => 'Supplier performance analysis completed successfully',
                    'details' => [
                        'insights_file_updated' => $fileUpdated ? date('Y-m-d H:i:s', filemtime($insightsFile)) : 'Not updated',
                        'output_preview' => substr($output ?? '', 0, 500)
                    ]
                ];
            } else {
                return [
                    'success' => false,
                    'message' => 'Supplier performance analysis may have failed',
                    'error_details' => [
                        'output_preview' => substr($output ?? '', 0, 1000),
                        'insights_file_exists' => file_exists($insightsFile),
                        'file_age' => file_exists($insightsFile) ? (time() - filemtime($insightsFile)) : 'N/A',
                        'command' => $command
                    ]
                ];
            }
            
        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => 'Failed to refresh supplier performance: ' . $e->getMessage(),
                'error_details' => $e->getTraceAsString()
            ];
        }
    }

    private function refreshRecommendationSystem($baseDir)
    {
        try {
            $pythonExe = $this->findPythonExecutable();
            if (!$pythonExe) {
                return [
                    'success' => false,
                    'message' => 'Python executable not found'
                ];
            }
            
            \Log::info("Refreshing recommendation system using Python: {$pythonExe}");
            
            // Check if recommendation_system.py exists
            if (!file_exists($baseDir . '/recommendation_system.py')) {
                return [
                    'success' => false,
                    'message' => 'recommendation_system.py not found in: ' . $baseDir
                ];
            }
            
            // Execute recommendation system with UTF-8 encoding
            $command = "cd /d \"{$baseDir}\" && \"{$pythonExe}\" recommendation_system.py 2>&1";
            
            // Set UTF-8 environment
            putenv('PYTHONIOENCODING=utf-8');
            
            $output = shell_exec($command);
            
            // Clean the output
            if ($output) {
                $output = mb_convert_encoding($output, 'UTF-8', 'UTF-8');
                $output = preg_replace('/[\x00-\x08\x0B\x0C\x0E-\x1F\x7F]/', '', $output);
            }
            
            \Log::info("Recommendation system output: " . substr($output ?? '', 0, 1000));
            
            // Check if recommendations were generated successfully
            $recommendationsFile = public_path('recommendations.json');
            $fileUpdated = file_exists($recommendationsFile) && (time() - filemtime($recommendationsFile)) < 300;
            $hasSuccess = $output && (strpos($output, 'SUCCESS') !== false || strpos($output, 'completed') !== false);
            
            if ($fileUpdated || $hasSuccess) {
                return [
                    'success' => true,
                    'message' => 'Recommendation system refreshed successfully',
                    'details' => [
                        'recommendations_file_updated' => $fileUpdated ? date('Y-m-d H:i:s', filemtime($recommendationsFile)) : 'Not updated',
                        'output_preview' => substr($output ?? '', 0, 500)
                    ]
                ];
            } else {
                return [
                    'success' => false,
                    'message' => 'Recommendation system may have failed',
                    'error_details' => [
                        'output_preview' => substr($output ?? '', 0, 1000),
                        'recommendations_file_exists' => file_exists($recommendationsFile),
                        'file_age' => file_exists($recommendationsFile) ? (time() - filemtime($recommendationsFile)) : 'N/A',
                        'command' => $command
                    ]
                ];
            }
            
        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => 'Failed to refresh recommendation system: ' . $e->getMessage(),
                'error_details' => $e->getTraceAsString()
            ];
        }
    }

    private function findPythonExecutable()
    {
        $pythonPaths = [
            'python',
            'python3',
            'py',
            'C:\\Python311\\python.exe',
            'C:\\Users\\sam\\AppData\\Local\\Programs\\Python\\Python311\\python.exe'
        ];

        foreach ($pythonPaths as $path) {
            $testCommand = "\"{$path}\" --version 2>&1";
            $output = shell_exec($testCommand);
            if ($output && strpos($output, 'Python') !== false) {
                return $path;
            }
        }

        return null;
    }

    // public function refreshWholesalerSegmentation(Request $request)
    // {
    //     try {
    //         // Trigger ML model to generate new segmentation
    //         $baseDir = base_path('../ml_models');
    //         $pythonExe = $this->findPythonExecutable();
            
    //         if (!$pythonExe) {
    //             return response()->json([
    //                 'success' => false,
    //                 'message' => 'Python executable not found. Please ensure Python is installed and accessible.'
    //             ]);
    //         }
            
    //         $command = "cd /d \"{$baseDir}\" && \"{$pythonExe}\" wholesaler_segmentation.py 2>&1";
    //         putenv('PYTHONIOENCODING=utf-8');
            
    //         $output = shell_exec($command);
            
    //         // Clean the output
    //         if ($output) {
    //             $output = mb_convert_encoding($output, 'UTF-8', 'UTF-8');
    //             $output = preg_replace('/[\x00-\x08\x0B\x0C\x0E-\x1F\x7F]/', '', $output);
    //         }
            
    //         \Log::info("Wholesaler segmentation output: " . substr($output ?? '', 0, 1000));
            
    //         // Check if segmentation completed successfully
    //         $segmentationFile = public_path('wholesaler_segments.csv');
    //         $fileUpdated = file_exists($segmentationFile) && (time() - filemtime($segmentationFile)) < 300;
            
    //         if ($fileUpdated) {
    //             return response()->json([
    //                 'success' => true,
    //                 'message' => 'Wholesaler segmentation completed successfully',
    //                 'file_updated_at' => date('Y-m-d H:i:s', filemtime($segmentationFile))
    //             ]);
    //         } else {
    //             return response()->json([
    //                 'success' => false,
    //                 'message' => 'Segmentation may have failed - no output file found or file not updated',
    //                 'output_preview' => substr($output ?? '', 0, 500)
    //             ]);
    //         }
    //     } catch (\Exception $e) {
    //         \Log::error('Wholesaler segmentation error: ' . $e->getMessage());
    //         return response()->json([
    //             'success' => false,
    //             'message' => 'Error running wholesaler segmentation: ' . $e->getMessage()
    //         ]);
    //     }
    // }

 

/**
 * Process wholesaler segmentation - helper method for both API endpoint and ML system refresh
 */
private function processWholesalerSegmentation($baseDir)
{
    try {
        $pythonExe = $this->findPythonExecutable();
        
        if (!$pythonExe) {
            return [
                'success' => false,
                'message' => 'Python executable not found. Please ensure Python is installed and accessible.'
            ];
        }
        
        $command = "cd /d \"{$baseDir}\" && \"{$pythonExe}\" wholesaler_segmentation.py 2>&1";
        putenv('PYTHONIOENCODING=utf-8');
        
        $output = shell_exec($command);
        
        // Clean the output
        if ($output) {
            $output = mb_convert_encoding($output, 'UTF-8', 'UTF-8');
            $output = preg_replace('/[\x00-\x08\x0B\x0C\x0E-\x1F\x7F]/', '', $output);
        }
        
        \Log::info("Wholesaler segmentation output: " . substr($output ?? '', 0, 1000));
        
        // Check if segmentation completed successfully
        $segmentationFile = public_path('wholesaler_segments.csv');
        $jsonFile = public_path('wholesaler_segments_meta.json');
        $fileUpdated = (file_exists($segmentationFile) && (time() - filemtime($segmentationFile)) < 300) || 
                       (file_exists($jsonFile) && (time() - filemtime($jsonFile)) < 300);
        $hasSuccess = $output && (strpos($output, 'SUCCESS') !== false || strpos($output, 'completed') !== false);
        
        if ($fileUpdated || $hasSuccess) {
            return [
                'success' => true,
                'message' => 'Wholesaler segmentation completed successfully',
                'details' => [
                    'csv_file_updated' => file_exists($segmentationFile) ? date('Y-m-d H:i:s', filemtime($segmentationFile)) : 'Not updated',
                    'json_file_updated' => file_exists($jsonFile) ? date('Y-m-d H:i:s', filemtime($jsonFile)) : 'Not updated',
                    'output_preview' => substr($output ?? '', 0, 500)
                ]
            ];
        } else {
            return [
                'success' => false,
                'message' => 'Segmentation may have failed - no output file found or file not updated',
                'error_details' => [
                    'output_preview' => substr($output ?? '', 0, 1000),
                    'csv_file_exists' => file_exists($segmentationFile),
                    'json_file_exists' => file_exists($jsonFile),
                    'file_age' => file_exists($segmentationFile) ? (time() - filemtime($segmentationFile)) : 'N/A',
                    'command' => $command
                ]
            ];
        }
    } catch (\Exception $e) {
        return [
            'success' => false,
            'message' => 'Failed to process wholesaler segmentation: ' . $e->getMessage(),
            'error_details' => $e->getTraceAsString()
        ];
    }
}

/**
 * Update the existing refreshWholesalerSegmentation method to use our helper
 */
public function refreshWholesalerSegmentation(Request $request)
{
    try {
        $baseDir = base_path('../ml_models');
        $results = $this->processWholesalerSegmentation($baseDir);
        
        return response()->json([
            'success' => $results['success'],
            'message' => $results['message'],
            'file_updated_at' => $results['details']['csv_file_updated'] ?? null,
            'output_preview' => $results['details']['output_preview'] ?? $results['error_details']['output_preview'] ?? null
        ]);
    } catch (\Exception $e) {
        \Log::error('Wholesaler segmentation error: ' . $e->getMessage());
        return response()->json([
            'success' => false,
            'message' => 'Error running wholesaler segmentation: ' . $e->getMessage()
        ]);
    }
}

// private function getWholesalerSegmentation()
// {
//     try {
//         $segmentationFile = public_path('wholesaler_segments.csv');
        
//         if (!file_exists($segmentationFile)) {
//             return [
//                 'error' => 'No segmentation data available',
//                 'message' => 'Run the segmentation analysis to generate insights'
//             ];
//         }
        
//         // Read CSV file
//         $segmentData = [];
//         $segmentNames = [];
//         $segmentDescriptions = [];
        
//         // Get last modified time
//         $lastUpdated = filemtime($segmentationFile);
        
//         // Parse the CSV file
//         $csv = array_map('str_getcsv', file($segmentationFile));
//         $headers = array_shift($csv);
        
//         // Convert to associative array
//         $wholesalers = [];
//         foreach ($csv as $row) {
//             $wholesaler = array_combine($headers, $row);
//             $wholesalers[] = $wholesaler;
//         }
        
//         // Group by cluster
//         $segmentedWholesalers = [];
//         $segmentSummary = [];
        
//         foreach ($wholesalers as $wholesaler) {
//             $cluster = $wholesaler['cluster'];
            
//             if (!isset($segmentedWholesalers[$cluster])) {
//                 $segmentedWholesalers[$cluster] = [
//                     'name' => $this->getSegmentName($cluster, $wholesaler['recency'], $wholesaler['total_spent']),
//                     'description' => $this->getSegmentDescription($cluster),
//                     'wholesalers' => []
//                 ];
//             }
            
//             $segmentedWholesalers[$cluster]['wholesalers'][] = $wholesaler;
//         }
        
//         // Calculate summary statistics
//         $totalWholesalers = count($wholesalers);
//         foreach ($segmentedWholesalers as $cluster => $segment) {
//             $count = count($segment['wholesalers']);
//             $percentage = ($totalWholesalers > 0) ? round(($count / $totalWholesalers) * 100) : 0;
            
//             $segmentSummary[] = [
//                 'name' => $segment['name'],
//                 'count' => $count,
//                 'percentage' => $percentage
//             ];
//         }
        
//         return [
//             'segments' => $segmentedWholesalers,
//             'summary' => $segmentSummary,
//             'last_updated' => $lastUpdated,
//         ];
//     } catch (\Exception $e) {
//         \Log::error('Failed to load wholesaler segmentation: ' . $e->getMessage());
//         return [
//             'error' => 'Failed to load segmentation data: ' . $e->getMessage()
//         ];
//     }
// }

    private function getWholesalerSegmentation()
    {
        try {
            $jsonFile = public_path('wholesaler_segments_meta.json');
            $csvFile = public_path('wholesaler_segments.csv');
            
            // First try to use the JSON metadata if available
            if (file_exists($jsonFile)) {
                $jsonData = json_decode(file_get_contents($jsonFile), true);
                
                // Prepare the segment data for the view
                $segmentedWholesalers = [];
                
                // If we also need the detailed data, load the CSV
                if (file_exists($csvFile)) {
                    // Read CSV file for detailed wholesaler data
                    $csv = array_map('str_getcsv', file($csvFile));
                    $headers = array_shift($csv);
                    
                    // Convert to associative array
                    $wholesalers = [];
                    foreach ($csv as $row) {
                        $wholesaler = array_combine($headers, $row);
                        $cluster = $wholesaler['cluster'];
                        
                        if (!isset($segmentedWholesalers[$cluster])) {
                            $segmentedWholesalers[$cluster] = [
                                'name' => $jsonData['segments'][$cluster]['name'],
                                'description' => $jsonData['segments'][$cluster]['description'],
                                'wholesalers' => []
                            ];
                        }
                        
                        $segmentedWholesalers[$cluster]['wholesalers'][] = $wholesaler;
                    }
                }
                
                // Get the segment summary from the JSON
                $segmentSummary = [];
                foreach ($jsonData['segments'] as $cluster => $segment) {
                    $segmentSummary[] = [
                        'name' => $segment['name'],
                        'count' => $segment['count'],
                        'percentage' => $segment['percentage'],
                        'avg_total_spent' => $segment['avg_total_spent'],
                        'avg_orders' => $segment['avg_orders']
                    ];
                }
                
                return [
                    'segments' => $segmentedWholesalers,
                    'summary' => $segmentSummary,
                    'last_updated' => $jsonData['last_updated'],
                    'total_wholesalers' => $jsonData['total_wholesalers']
                ];
            } else if (file_exists($csvFile)) {
                // Fall back to CSV-only method if JSON doesn't exist
                // (your existing CSV parsing code)
            } else {
                return [
                    'error' => 'No segmentation data available',
                    'message' => 'Run the segmentation analysis to generate insights'
                ];
            }
        } catch (\Exception $e) {
            \Log::error('Failed to load wholesaler segmentation: ' . $e->getMessage());
            return [
                'error' => 'Failed to load segmentation data: ' . $e->getMessage()
            ];
        }
    }

private function getSegmentName($cluster, $recency, $totalSpent)
{
    // Define segment names based on the Python model's naming logic
    switch ($cluster) {
        case 0:
            return "Premium Wholesalers";
        case 1:
            return "High-Value Active Buyers";
        case 2:
            return "At-Risk/Dormant";
        case 3:
            return "Regular Active Buyers";
        default:
            // Fallback logic similar to Python script
            if ($recency <= 30 && $totalSpent > 100000000) {
                return "High-Value Active Buyers";
            } elseif ($recency <= 30) {
                return "Regular Active Buyers";
            } elseif ($recency > 90) {
                return "At-Risk/Dormant";
            } else {
                return "Occasional Buyers";
            }
    }
}

private function getSegmentDescription($cluster)
{
    // Define segment descriptions based on the Python model's descriptions
    switch ($cluster) {
        case 0:
            return "High-value, frequent buyers with large order volumes";
        case 1:
            return "Recently active customers with above-average spending";
        case 2:
            return "Haven't ordered recently, need re-engagement";
        case 3:
            return "Recently active customers with moderate spending";
        default:
            return "Moderate activity, potential for growth";
    }
}
}
