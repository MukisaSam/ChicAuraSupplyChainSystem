<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Item;
use App\Models\Wholesaler;
use Carbon\Carbon;

class WholesalerReportsController extends Controller
{
    /**
     * Display the main reports dashboard.
     */
    public function index()
    {
        $user = Auth::user();
        $wholesaler = $user->wholesaler;
        
        if (!$wholesaler) {
            abort(403, 'Wholesaler profile not found.');
        }

        // Get date range from request or default to last 30 days
        $startDate = request('start_date', Carbon::now()->subDays(30)->format('Y-m-d'));
        $endDate = request('end_date', Carbon::now()->format('Y-m-d'));

        // Sales Overview
        $salesOverview = $this->getSalesOverview($wholesaler->id, $startDate, $endDate);
        
        // Order Analytics
        $orderAnalytics = $this->getOrderAnalytics($wholesaler->id, $startDate, $endDate);
        
        // Top Products
        $topProducts = $this->getTopProducts($wholesaler->id, $startDate, $endDate);
        
        // Monthly Trends
        $monthlyTrends = $this->getMonthlyTrends($wholesaler->id);
        
        // Payment Method Distribution
        $paymentMethods = $this->getPaymentMethodDistribution($wholesaler->id, $startDate, $endDate);
        
        // Order Status Distribution
        $orderStatuses = $this->getOrderStatusDistribution($wholesaler->id, $startDate, $endDate);

        return view('wholesaler.reports.index', compact(
            'user',
            'salesOverview',
            'orderAnalytics',
            'topProducts',
            'monthlyTrends',
            'paymentMethods',
            'orderStatuses',
            'startDate',
            'endDate'
        ));
    }

    /**
     * Generate detailed sales report.
     */
    public function salesReport(Request $request)
    {
        $user = Auth::user();
        $wholesaler = $user->wholesaler;
        
        if (!$wholesaler) {
            abort(403, 'Wholesaler profile not found.');
        }

        $startDate = $request->get('start_date', Carbon::now()->subDays(30)->format('Y-m-d'));
        $endDate = $request->get('end_date', Carbon::now()->format('Y-m-d'));

        $salesData = Order::where('wholesaler_id', $wholesaler->id)
            ->whereBetween('order_date', [$startDate, $endDate])
            ->with(['orderItems.item'])
            ->orderBy('order_date', 'desc')
            ->get();

        $dailySales = Order::where('wholesaler_id', $wholesaler->id)
            ->whereBetween('order_date', [$startDate, $endDate])
            ->selectRaw('DATE(order_date) as date, SUM(total_amount) as total_sales, COUNT(*) as order_count')
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        return view('wholesaler.reports.sales', compact('user', 'salesData', 'dailySales', 'startDate', 'endDate'));
    }

    /**
     * Generate order performance report.
     */
    public function orderReport(Request $request)
    {
        $user = Auth::user();
        $wholesaler = $user->wholesaler;
        
        if (!$wholesaler) {
            abort(403, 'Wholesaler profile not found.');
        }

        $startDate = $request->get('start_date', Carbon::now()->subDays(30)->format('Y-m-d'));
        $endDate = $request->get('end_date', Carbon::now()->format('Y-m-d'));

        $orders = Order::where('wholesaler_id', $wholesaler->id)
            ->whereBetween('order_date', [$startDate, $endDate])
            ->with(['orderItems.item'])
            ->orderBy('order_date', 'desc')
            ->paginate(20);

        $orderMetrics = $this->getOrderMetrics($wholesaler->id, $startDate, $endDate);

        // Debug: Check if data is being passed correctly
        \Log::info('Order Report Data', [
            'user' => $user->name,
            'wholesaler_id' => $wholesaler->id,
            'orders_count' => $orders->count(),
            'orderMetrics' => $orderMetrics,
            'startDate' => $startDate,
            'endDate' => $endDate
        ]);

        return view('wholesaler.reports.orders', compact('user', 'orders', 'orderMetrics', 'startDate', 'endDate'));
    }

    /**
     * Export reports to PDF or Excel.
     */
    public function export(Request $request)
    {
        $user = Auth::user();
        $wholesaler = $user->wholesaler;
        
        if (!$wholesaler) {
            abort(403, 'Wholesaler profile not found.');
        }

        $reportType = $request->get('type', 'sales');
        $format = $request->get('format', 'pdf');
        $startDate = $request->get('start_date', Carbon::now()->subDays(30)->format('Y-m-d'));
        $endDate = $request->get('end_date', Carbon::now()->format('Y-m-d'));

        switch ($reportType) {
            case 'sales':
                return $this->exportSalesReport($wholesaler->id, $startDate, $endDate, $format);
            case 'orders':
                return $this->exportOrderReport($wholesaler->id, $startDate, $endDate, $format);
            default:
                abort(400, 'Invalid report type');
        }
    }

    /**
     * Get sales overview data.
     */
    private function getSalesOverview($wholesalerId, $startDate, $endDate)
    {
        $currentPeriod = Order::where('wholesaler_id', $wholesalerId)
            ->whereBetween('order_date', [$startDate, $endDate])
            ->selectRaw('SUM(total_amount) as total_sales, COUNT(*) as order_count, AVG(total_amount) as avg_order_value')
            ->first();

        $previousPeriod = Order::where('wholesaler_id', $wholesalerId)
            ->whereBetween('order_date', [
                Carbon::parse($startDate)->subDays(Carbon::parse($endDate)->diffInDays(Carbon::parse($startDate)))->format('Y-m-d'),
                Carbon::parse($startDate)->subDay()->format('Y-m-d')
            ])
            ->selectRaw('SUM(total_amount) as total_sales, COUNT(*) as order_count, AVG(total_amount) as avg_order_value')
            ->first();

        return [
            'current' => [
                'total_sales' => $currentPeriod->total_sales ?? 0,
                'order_count' => $currentPeriod->order_count ?? 0,
                'avg_order_value' => $currentPeriod->avg_order_value ?? 0,
            ],
            'previous' => [
                'total_sales' => $previousPeriod->total_sales ?? 0,
                'order_count' => $previousPeriod->order_count ?? 0,
                'avg_order_value' => $previousPeriod->avg_order_value ?? 0,
            ],
            'growth' => [
                'sales_growth' => $previousPeriod->total_sales > 0 
                    ? (($currentPeriod->total_sales - $previousPeriod->total_sales) / $previousPeriod->total_sales) * 100 
                    : 0,
                'order_growth' => $previousPeriod->order_count > 0 
                    ? (($currentPeriod->order_count - $previousPeriod->order_count) / $previousPeriod->order_count) * 100 
                    : 0,
            ]
        ];
    }

    /**
     * Get order analytics data.
     */
    private function getOrderAnalytics($wholesalerId, $startDate, $endDate)
    {
        $orders = Order::where('wholesaler_id', $wholesalerId)
            ->whereBetween('order_date', [$startDate, $endDate])
            ->get();

        $statusCounts = $orders->groupBy('status')->map->count();
        $paymentCounts = $orders->groupBy('payment_method')->map->count();

        return [
            'total_orders' => $orders->count(),
            'completed_orders' => $orders->where('status', 'delivered')->count(),
            'pending_orders' => $orders->whereIn('status', ['pending', 'confirmed', 'in_production', 'shipped'])->count(),
            'cancelled_orders' => $orders->where('status', 'cancelled')->count(),
            'status_distribution' => $statusCounts,
            'payment_distribution' => $paymentCounts,
        ];
    }

    /**
     * Get top products data.
     */
    private function getTopProducts($wholesalerId, $startDate, $endDate)
    {
        return OrderItem::join('orders', 'order_items.order_id', '=', 'orders.id')
            ->join('items', 'order_items.item_id', '=', 'items.id')
            ->where('orders.wholesaler_id', $wholesalerId)
            ->whereBetween('orders.order_date', [$startDate, $endDate])
            ->selectRaw('items.name, items.image_url, SUM(order_items.quantity) as total_quantity, SUM(order_items.total_price) as total_revenue')
            ->groupBy('items.id', 'items.name', 'items.image_url')
            ->orderBy('total_revenue', 'desc')
            ->limit(10)
            ->get();
    }

    /**
     * Get monthly trends data.
     */
    private function getMonthlyTrends($wholesalerId)
    {
        return Order::where('wholesaler_id', $wholesalerId)
            ->where('order_date', '>=', Carbon::now()->subMonths(12))
            ->selectRaw('YEAR(order_date) as year, MONTH(order_date) as month, SUM(total_amount) as total_sales, COUNT(*) as order_count')
            ->groupBy('year', 'month')
            ->orderBy('year')
            ->orderBy('month')
            ->get()
            ->map(function ($item) {
                $item->month_name = Carbon::createFromDate($item->year, $item->month, 1)->format('M Y');
                return $item;
            });
    }

    /**
     * Get payment method distribution.
     */
    private function getPaymentMethodDistribution($wholesalerId, $startDate, $endDate)
    {
        return Order::where('wholesaler_id', $wholesalerId)
            ->whereBetween('order_date', [$startDate, $endDate])
            ->selectRaw('payment_method, COUNT(*) as count, SUM(total_amount) as total_amount')
            ->groupBy('payment_method')
            ->get();
    }

    /**
     * Get order status distribution.
     */
    private function getOrderStatusDistribution($wholesalerId, $startDate, $endDate)
    {
        return Order::where('wholesaler_id', $wholesalerId)
            ->whereBetween('order_date', [$startDate, $endDate])
            ->selectRaw('status, COUNT(*) as count')
            ->groupBy('status')
            ->get();
    }

    /**
     * Get order metrics.
     */
    private function getOrderMetrics($wholesalerId, $startDate, $endDate)
    {
        $orders = Order::where('wholesaler_id', $wholesalerId)
            ->whereBetween('order_date', [$startDate, $endDate])
            ->get();

        return [
            'total_orders' => $orders->count(),
            'total_revenue' => $orders->sum('total_amount'),
            'avg_order_value' => $orders->avg('total_amount'),
            'delivery_rate' => $orders->count() > 0 ? ($orders->where('status', 'delivered')->count() / $orders->count()) * 100 : 0,
            'cancellation_rate' => $orders->count() > 0 ? ($orders->where('status', 'cancelled')->count() / $orders->count()) * 100 : 0,
        ];
    }

    /**
     * Export sales report.
     */
    private function exportSalesReport($wholesalerId, $startDate, $endDate, $format)
    {
        $wholesaler = Wholesaler::findOrFail($wholesalerId);
        $salesOverview = $this->getSalesOverview($wholesalerId, $startDate, $endDate);
        $topProducts = $this->getTopProducts($wholesalerId, $startDate, $endDate);
        $monthlyTrends = $this->getMonthlyTrends($wholesalerId);
        $paymentMethods = $this->getPaymentMethodDistribution($wholesalerId, $startDate, $endDate);

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="sales-report-' . now()->format('Y-m-d') . '.csv"',
        ];

        $callback = function() use ($salesOverview, $topProducts, $monthlyTrends, $paymentMethods) {
            $file = fopen('php://output', 'w');

            // Sales Overview
            fputcsv($file, ['Sales Overview']);
            fputcsv($file, ['Metric', 'Value']);
            fputcsv($file, ['Total Sales', '$' . number_format($salesOverview['current']['total_sales'], 2)]);
            fputcsv($file, ['Total Orders', $salesOverview['current']['order_count']]);
            fputcsv($file, ['Average Order Value', '$' . number_format($salesOverview['current']['avg_order_value'], 2)]);
            fputcsv($file, ['Sales Growth', number_format($salesOverview['growth']['sales_growth'], 1) . '%']);
            fputcsv($file, []); // Empty line for spacing

            // Top Products
            fputcsv($file, ['Top Products']);
            fputcsv($file, ['Product Name', 'Quantity Sold', 'Revenue']);
            foreach ($topProducts as $product) {
                fputcsv($file, [
                    $product->name,
                    $product->total_quantity,
                    '$' . number_format($product->total_revenue, 2)
                ]);
            }
            fputcsv($file, []); // Empty line for spacing

            // Monthly Trends
            fputcsv($file, ['Monthly Trends']);
            fputcsv($file, ['Month', 'Sales', 'Orders']);
            foreach ($monthlyTrends as $trend) {
                fputcsv($file, [
                    $trend->month_name,
                    '$' . number_format($trend->total_sales, 2),
                    $trend->order_count
                ]);
            }
            fputcsv($file, []); // Empty line for spacing

            // Payment Methods
            fputcsv($file, ['Payment Methods']);
            fputcsv($file, ['Method', 'Orders', 'Total Amount']);
            foreach ($paymentMethods as $method) {
                fputcsv($file, [
                    ucfirst($method->payment_method),
                    $method->count,
                    '$' . number_format($method->total_amount, 2)
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Export order report.
     */
    private function exportOrderReport($wholesalerId, $startDate, $endDate, $format)
    {
        $wholesaler = Wholesaler::findOrFail($wholesalerId);
        $orders = Order::where('wholesaler_id', $wholesalerId)
            ->whereBetween('order_date', [$startDate, $endDate])
            ->with(['items.item', 'manufacturer'])
            ->get();
        
        $orderMetrics = $this->getOrderMetrics($wholesalerId, $startDate, $endDate);
        $orderStatuses = $this->getOrderStatusDistribution($wholesalerId, $startDate, $endDate);

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="orders-report-' . now()->format('Y-m-d') . '.csv"',
        ];

        $callback = function() use ($orders, $orderMetrics, $orderStatuses) {
            $file = fopen('php://output', 'w');

            // Order Metrics
            fputcsv($file, ['Order Metrics']);
            fputcsv($file, ['Metric', 'Value']);
            fputcsv($file, ['Total Orders', $orderMetrics['total_orders']]);
            fputcsv($file, ['Total Revenue', '$' . number_format($orderMetrics['total_revenue'], 2)]);
            fputcsv($file, ['Average Order Value', '$' . number_format($orderMetrics['avg_order_value'], 2)]);
            fputcsv($file, ['Delivery Rate', number_format($orderMetrics['delivery_rate'], 1) . '%']);
            fputcsv($file, []); // Empty line for spacing

            // Order Status Distribution
            fputcsv($file, ['Order Status Distribution']);
            fputcsv($file, ['Status', 'Count', 'Percentage']);
            foreach ($orderStatuses as $status) {
                $percentage = ($status->count / $orderMetrics['total_orders']) * 100;
                fputcsv($file, [
                    ucfirst($status->status),
                    $status->count,
                    number_format($percentage, 1) . '%'
                ]);
            }
            fputcsv($file, []); // Empty line for spacing

            // Orders List
            fputcsv($file, ['Orders List']);
            fputcsv($file, ['Order ID', 'Date', 'Manufacturer', 'Items', 'Total Amount', 'Status']);
            foreach ($orders as $order) {
                fputcsv($file, [
                    '#' . $order->id,
                    $order->order_date->format('M d, Y'),
                    $order->manufacturer->user->name,
                    $order->items->count(),
                    '$' . number_format($order->total_amount, 2),
                    ucfirst($order->status)
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
