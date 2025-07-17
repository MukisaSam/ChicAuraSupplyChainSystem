<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Item;
use App\Models\Wholesaler;
use App\Models\SupplyRequest;
use App\Models\Supplier;
use App\Models\SuppliedItem;
use Illuminate\Support\Facades\DB;
use App\Notifications\OrderStatusUpdatedNotification;

class ManufacturerOrdersController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
        if ($user->role !== 'manufacturer') {
            abort(403, 'Access denied. Manufacturer privileges required.');
        }
        
        // Get orders from wholesalers
        $orders = Order::with(['wholesaler.user', 'orderItems.item'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);
        
        // Get supply requests to suppliers
        $supplyRequests = SupplyRequest::with(['supplier.user', 'item'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);
        
        // Statistics
        $stats = [
            'total_orders' => Order::count(),
            'pending_orders' => Order::where('status', 'pending')->count(),
            'in_production_orders' => Order::where('status', 'in_production')->count(),
            'completed_orders' => Order::where('status', 'delivered')->count(),
            'total_supply_requests' => SupplyRequest::count(),
            'pending_supply_requests' => SupplyRequest::where('status', 'pending')->count(),
            'total_revenue' => Order::where('status', 'delivered')->sum('total_amount'),
        ];
        
        return view('manufacturer.Orders.index', compact('orders', 'supplyRequests', 'stats'));
    }

    public function show(Order $order)
    {
        $user = Auth::user();
        
        if ($user->role !== 'manufacturer') {
            abort(403, 'Access denied. Manufacturer privileges required.');
        }
        
        $order->load(['wholesaler.user', 'orderItems.item']);
        
        return view('manufacturer.Orders.show', compact('order'));
    }

    public function updateStatus(Request $request, Order $order)
    {
        $user = Auth::user();
        
        if ($user->role !== 'manufacturer') {
            abort(403, 'Access denied. Manufacturer privileges required.');
        }
        
        $request->validate([
            'status' => 'required|in:pending,confirmed,in_production,shipped,delivered,cancelled',
            'notes' => 'nullable|string|max:500',
        ]);
        
        $oldStatus = $order->status;
        $order->update([
            'status' => $request->status,
            'notes' => $request->notes,
        ]);
        
        // Notify wholesaler of status update
        if ($order->wholesaler && $order->wholesaler->user) {
            $order->wholesaler->user->notify(new OrderStatusUpdatedNotification($order, $request->status));
        }
        // Notify all admins
        $admins = \App\Models\User::where('role', 'admin')->get();
        \Illuminate\Support\Facades\Notification::send($admins, new OrderStatusUpdatedNotification($order, $request->status));

        // Automatic stock update logic
        if ($oldStatus !== $request->status) {
            foreach ($order->orderItems as $orderItem) {
                $item = $orderItem->item;
                if ($request->status === 'confirmed' && $oldStatus !== 'confirmed') {
                    // Decrease stock when confirmed
                    $item->stock_quantity -= $orderItem->quantity;
                    $item->save();
                } elseif ($request->status === 'cancelled' && $oldStatus === 'confirmed') {
                    // Increase stock back if cancelled after being confirmed
                    $item->stock_quantity += $orderItem->quantity;
                    $item->save();
                }
            }
        }

        // If order is confirmed, check inventory and create supply requests if needed
        if ($request->status === 'confirmed') {
            $this->checkInventoryAndCreateSupplyRequests($order);
        }
        
        return redirect()->back()->with('success', 'Order status updated successfully.');
    }

    public function createSupplyRequest()
    {
        $user = Auth::user();
        if ($user->role !== 'manufacturer') {
            abort(403, 'Access denied. Manufacturer privileges required.');
        }
        // Only fetch raw materials
        $rawMaterials = Item::where('type', 'raw_material')->where('is_active', true)->get();
        $suppliers = Supplier::with('user')->get();
        return view('manufacturer.Orders.create-supply-request', compact('rawMaterials', 'suppliers'));
    }

    public function storeSupplyRequest(Request $request)
    {
        $user = Auth::user();
        
        if ($user->role !== 'manufacturer') {
            abort(403, 'Access denied. Manufacturer privileges required.');
        }
        
        $request->validate([
            'supplier_id' => 'required|exists:suppliers,id',
            'item_id' => 'required|exists:items,id',
            'quantity' => 'required|integer|min:1',
            'due_date' => 'required|date|after:today',
            'notes' => 'nullable|string|max:500',
        ]);
        
        SupplyRequest::create([
            'supplier_id' => $request->supplier_id,
            'item_id' => $request->item_id,
            'quantity' => $request->quantity,
            'due_date' => $request->due_date,
            'status' => 'pending',
            'notes' => $request->notes,
        ]);
        
        return redirect()->route('manufacturer.orders')->with('success', 'Supply request created successfully.');
    }

    public function showSupplyRequest(SupplyRequest $supplyRequest)
    {
        $user = Auth::user();
        
        if ($user->role !== 'manufacturer') {
            abort(403, 'Access denied. Manufacturer privileges required.');
        }
        
        $supplyRequest->load(['supplier.user', 'item']);
        
        return view('manufacturer.Orders.show-supply-request', compact('supplyRequest'));
    }

    public function updateSupplyRequestStatus(Request $request, SupplyRequest $supplyRequest)
    {
        $user = Auth::user();
        
        if ($user->role !== 'manufacturer') {
            abort(403, 'Access denied. Manufacturer privileges required.');
        }
        
        $request->validate([
            'status' => 'required|in:pending,approved,rejected,completed',
            'notes' => 'nullable|string|max:500',
        ]);
        
        $supplyRequest->update([
            'status' => $request->status,
            'notes' => $request->notes,
        ]);
        
        return redirect()->back()->with('success', 'Supply request status updated successfully.');
    }

    public function analytics()
    {
        $user = Auth::user();
        
        if ($user->role !== 'manufacturer') {
            abort(403, 'Access denied. Manufacturer privileges required.');
        }
        
        // Order analytics
        $orderStats = [
            'total_orders' => Order::count(),
            'pending_orders' => Order::where('status', 'pending')->count(),
            'completed_orders' => Order::where('status', 'delivered')->count(),
            'total_revenue' => Order::where('status', 'delivered')->sum('total_amount'),
            'monthly_orders' => Order::selectRaw('MONTH(created_at) as month, COUNT(*) as count')
                ->whereYear('created_at', date('Y'))
                ->groupBy('month')
                ->pluck('count', 'month')
                ->toArray(),
            'status_distribution' => Order::selectRaw('status, COUNT(*) as count')
                ->groupBy('status')
                ->pluck('count', 'status')
                ->toArray(),
            'top_wholesalers' => Order::with('wholesaler.user')
                ->selectRaw('wholesaler_id, COUNT(*) as order_count, SUM(total_amount) as total_revenue')
                ->groupBy('wholesaler_id')
                ->orderBy('total_revenue', 'desc')
                ->limit(5)
                ->get(),
        ];
        
        // Supply request analytics
        $supplyStats = [
            'total_requests' => SupplyRequest::count(),
            'pending_requests' => SupplyRequest::where('status', 'pending')->count(),
            'completion_rate' => SupplyRequest::where('status', 'completed')->count() > 0 
                ? round((SupplyRequest::where('status', 'completed')->count() / SupplyRequest::count()) * 100, 1)
                : 0,
            'monthly_requests' => SupplyRequest::selectRaw('MONTH(created_at) as month, COUNT(*) as count')
                ->whereYear('created_at', date('Y'))
                ->groupBy('month')
                ->pluck('count', 'month')
                ->toArray(),
            'supplier_performance' => SupplyRequest::with('supplier.user')
                ->selectRaw('supplier_id, COUNT(*) as request_count, AVG(CASE WHEN status = "completed" THEN 1 ELSE 0 END) as completion_rate')
                ->groupBy('supplier_id')
                ->orderBy('completion_rate', 'desc')
                ->limit(5)
                ->get(),
        ];
        
        return view('manufacturer.Orders.analytics', compact('orderStats', 'supplyStats'));
    }

    private function checkInventoryAndCreateSupplyRequests(Order $order)
    {
        foreach ($order->orderItems as $orderItem) {
            $item = $orderItem->item;
            
            // Check if we have enough inventory
            if ($item->stock_quantity < $orderItem->quantity) {
                // Find suppliers for this item
                $suppliers = Supplier::whereHas('suppliedItems', function($query) use ($item) {
                    $query->where('item_id', $item->id);
                })->get();
                
                if ($suppliers->count() > 0) {
                    // Create supply request for the first available supplier
                    $supplier = $suppliers->first();
                    $neededQuantity = $orderItem->quantity - $item->stock_quantity;
                    
                    SupplyRequest::create([
                        'supplier_id' => $supplier->id,
                        'item_id' => $item->id,
                        'quantity' => $neededQuantity,
                        'due_date' => now()->addDays(7),
                        'status' => 'pending',
                        'notes' => "Auto-generated for order #{$order->order_number}",
                    ]);
                }
            }
        }
    }
}
