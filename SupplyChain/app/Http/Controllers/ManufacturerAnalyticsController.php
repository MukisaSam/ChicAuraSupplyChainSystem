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
                
                // ML Supplier Insights (NEW)
                'mlSupplierInsights' => $this->getMLSupplierInsights(),
                
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
            
            // Return default data structure with ML insights
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
        $suppliers = Supplier::with(['user', 'suppliedItems' => function ($query) {
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

            // Calculate additional metrics
            $totalValue = $supplier->suppliedItems->sum(function ($item) {
                return $item->quantity * $item->unit_price;
            });

            $avgOrderValue = $totalDeliveries > 0 ? $totalValue / $totalDeliveries : 0;
            
            // Calculate reliability score
            $reliabilityScore = 0;
            if ($totalDeliveries > 0) {
                $onTimeRate = ($onTimeDeliveries / $totalDeliveries) * 100;
                $deliverySpeed = max(0, 100 - ($avgDeliveryTime ?? 0) * 2); // Penalty for slow delivery
                $reliabilityScore = ($onTimeRate * 0.7) + ($deliverySpeed * 0.3);
            }

            return [
                'id' => $supplier->id,
                'name' => $supplier->user ? $supplier->user->name : 'Unknown',
                'company_name' => $supplier->company_name ?? 'N/A',
                'on_time_delivery_rate' => $totalDeliveries > 0 ? round(($onTimeDeliveries / $totalDeliveries) * 100, 1) : 0,
                'avg_delivery_time' => round($avgDeliveryTime ?? 0, 1),
                'total_deliveries' => $totalDeliveries,
                'total_value' => $totalValue,
                'avg_order_value' => round($avgOrderValue, 2),
                'reliability_score' => round($reliabilityScore, 1),
                'quality_rating' => rand(70, 100), // Replace with actual quality metrics
                'last_delivery' => $supplier->suppliedItems->max('delivery_date'),
                'status' => $totalDeliveries > 0 ? 'Active' : 'Inactive',
            ];
        });

        // Calculate overall metrics
        $avgOnTimeDelivery = $supplierMetrics->count() > 0 ? $supplierMetrics->avg('on_time_delivery_rate') : 0;
        $avgDeliveryTime = $supplierMetrics->count() > 0 ? $supplierMetrics->avg('avg_delivery_time') : 0;
        $avgReliabilityScore = $supplierMetrics->count() > 0 ? $supplierMetrics->avg('reliability_score') : 0;

        return [
            'suppliers' => $supplierMetrics,
            'avg_on_time_delivery' => round($avgOnTimeDelivery, 1),
            'avg_delivery_time' => round($avgDeliveryTime, 1),
            'avg_reliability_score' => round($avgReliabilityScore, 1),
            'top_performers' => $supplierMetrics->sortByDesc('reliability_score')->take(5),
            'total_suppliers' => $supplierMetrics->count(),
            'active_suppliers' => $supplierMetrics->where('status', 'Active')->count(),
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
            case 'timeseries':
            default:
                return response()->json(['timeData' => $this->getTimeSeriesData()]);
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

            // Add some default locations if warehouses are empty
            if ($locations->isEmpty()) {
                $locations = collect(['Wakiso', 'Kampala', 'Entebbe', 'Jinja', 'Gulu']);
            }

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
                    
                case 'recommendations':
                    $results = $this->refreshRecommendationSystem($baseDir);
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
}
