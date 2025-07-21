<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Item;
use App\Models\Supplier;
use App\Models\SuppliedItem;
use App\Models\Warehouse;
use App\Models\SupplyRequest;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Carbon\Carbon;

class ManufacturerReportsController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        if ($user->role !== 'manufacturer') {
            abort(403, 'Access denied. Manufacturer privileges required.');
        }
        return view('manufacturer.Reports.index');
    }

    public function sales(Request $request)
    {
        $user = Auth::user();
        if ($user->role !== 'manufacturer') {
            abort(403, 'Access denied. Manufacturer privileges required.');
        }

        $startDate = $request->get('start_date', Carbon::now()->startOfMonth());
        $endDate = $request->get('end_date', Carbon::now());

        $orders = Order::with('orderItems')
            ->when($startDate, function($query) use ($startDate) {
                return $query->where('created_at', '>=', $startDate);
            })
            ->when($endDate, function($query) use ($endDate) {
                return $query->where('created_at', '<=', $endDate);
            })
            ->orderBy('created_at', 'desc')
            ->limit(100)
            ->get();

        $totalSales = $orders->sum('total_amount');
        $orderCount = $orders->count();

        return view('manufacturer.Reports.sales', compact('orders', 'totalSales', 'orderCount', 'startDate', 'endDate'));
    }

    public function inventory(Request $request)
    {
        $user = Auth::user();
        if ($user->role !== 'manufacturer') {
            abort(403, 'Access denied. Manufacturer privileges required.');
        }

        $warehouseFilter = $request->get('warehouse_id');
        
        $items = Item::with('warehouse')
            ->when($warehouseFilter, function($query) use ($warehouseFilter) {
                return $query->where('warehouse_id', $warehouseFilter);
            })
            ->orderBy('stock_quantity', 'asc')
            ->get();

        $warehouses = Warehouse::all();

        return view('manufacturer.Reports.inventory', compact('items', 'warehouses', 'warehouseFilter'));
    }

    public function suppliers(Request $request)
    {
        $user = Auth::user();
        if ($user->role !== 'manufacturer') {
            abort(403, 'Access denied. Manufacturer privileges required.');
        }

        $startDate = $request->get('start_date', Carbon::now()->subMonths(3));
        $endDate = $request->get('end_date', Carbon::now());

        $suppliers = Supplier::with(['suppliedItems' => function($query) use ($startDate, $endDate) {
            $query->when($startDate, function($q) use ($startDate) {
                return $q->where('delivery_date', '>=', $startDate);
            })
            ->when($endDate, function($q) use ($endDate) {
                return $q->where('delivery_date', '<=', $endDate);
            });
        }, 'user'])->get();

        return view('manufacturer.Reports.suppliers', compact('suppliers', 'startDate', 'endDate'));
    }

    public function fulfillment(Request $request)
    {
        $user = Auth::user();
        if ($user->role !== 'manufacturer') {
            abort(403, 'Access denied. Manufacturer privileges required.');
        }

        $statusFilter = $request->get('status');
        $startDate = $request->get('start_date', Carbon::now()->subMonths(3));
        $endDate = $request->get('end_date', Carbon::now());

        $orders = Order::with('orderItems')
            ->when($statusFilter, function($query) use ($statusFilter) {
                return $query->where('status', $statusFilter);
            })
            ->when($startDate, function($query) use ($startDate) {
                return $query->where('created_at', '>=', $startDate);
            })
            ->when($endDate, function($query) use ($endDate) {
                return $query->where('created_at', '<=', $endDate);
            })
            ->orderBy('created_at', 'desc')
            ->get();

        return view('manufacturer.Reports.fulfillment', compact('orders', 'statusFilter', 'startDate', 'endDate'));
    }

    public function getSalesChartData(Request $request)
    {
        $user = Auth::user();
        if ($user->role !== 'manufacturer') {
            abort(403, 'Access denied. Manufacturer privileges required.');
        }

        $startDate = $request->get('start_date', Carbon::now()->subMonths(6));
        $endDate = $request->get('end_date', Carbon::now());

        $salesData = Order::selectRaw('DATE(created_at) as date, SUM(total_amount) as total_sales, COUNT(*) as order_count')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        // Fallback: if no data in range, fetch all-time data
        if ($salesData->isEmpty()) {
            $salesData = Order::selectRaw('DATE(created_at) as date, SUM(total_amount) as total_sales, COUNT(*) as order_count')
                ->groupBy('date')
                ->orderBy('date')
                ->get();
        }

        return response()->json([
            'labels' => $salesData->pluck('date'),
            'sales' => $salesData->pluck('total_sales'),
            'orders' => $salesData->pluck('order_count')
        ]);
    }

    public function getInventoryChartData(Request $request)
    {
        $user = Auth::user();
        if ($user->role !== 'manufacturer') {
            abort(403, 'Access denied. Manufacturer privileges required.');
        }

        $inventoryData = Item::with('warehouse')
            ->selectRaw('warehouse_id, SUM(stock_quantity) as total_stock')
            ->groupBy('warehouse_id')
            ->get();

        return response()->json([
            'labels' => $inventoryData->pluck('warehouse.location'),
            'data' => $inventoryData->pluck('total_stock')
        ]);
    }

    public function getSupplierChartData(Request $request)
    {
        $user = Auth::user();
        if ($user->role !== 'manufacturer') {
            abort(403, 'Access denied. Manufacturer privileges required.');
        }

        $startDate = $request->get('start_date', Carbon::now()->subMonths(3));
        $endDate = $request->get('end_date', Carbon::now());

        $supplierData = Supplier::with(['suppliedItems' => function($query) use ($startDate, $endDate) {
            $query->whereBetween('delivery_date', [$startDate, $endDate]);
        }, 'user'])
        ->get()
        ->map(function($supplier) {
            return [
                'name' => $supplier->user->full_name ?? 'Unknown',
                'total_supplied' => $supplier->suppliedItems->sum('delivered_quantity'),
                'total_value' => $supplier->suppliedItems->sum(DB::raw('delivered_quantity * price'))
            ];
        })
        ->sortByDesc('total_supplied')
        ->take(10);

        // Fallback: if no data in range, fetch all-time data
        if ($supplierData->sum('total_supplied') == 0 && $supplierData->sum('total_value') == 0) {
            $supplierData = Supplier::with(['suppliedItems' => function($query) {}, 'user'])
                ->get()
                ->map(function($supplier) {
                    return [
                        'name' => $supplier->user->full_name ?? 'Unknown',
                        'total_supplied' => $supplier->suppliedItems->sum('delivered_quantity'),
                        'total_value' => $supplier->suppliedItems->sum(DB::raw('delivered_quantity * price'))
                    ];
                })
                ->sortByDesc('total_supplied')
                ->take(10);
        }

        return response()->json([
            'labels' => $supplierData->pluck('name'),
            'quantities' => $supplierData->pluck('total_supplied'),
            'values' => $supplierData->pluck('total_value')
        ]);
    }

    public function export($type)
    {
        $user = Auth::user();
        if ($user->role !== 'manufacturer') {
            abort(403, 'Access denied. Manufacturer privileges required.');
        }

        if ($type === 'sales') {
            $orders = Order::with('orderItems')->orderBy('created_at', 'desc')->get();
            $response = new StreamedResponse(function () use ($orders) {
                $handle = fopen('php://output', 'w');
                fputcsv($handle, ['Order ID', 'Date', 'Total Amount', 'Status']);
                foreach ($orders as $order) {
                    fputcsv($handle, [
                        $order->id,
                        $order->created_at,
                        $order->total_amount,
                        $order->status
                    ]);
                }
                fclose($handle);
            });
            $response->headers->set('Content-Type', 'text/csv');
            $response->headers->set('Content-Disposition', 'attachment; filename="sales_report.csv"');
            return $response;
        }

        abort(404);
    }
}
