<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Item;
use App\Models\Wholesaler;
use Illuminate\Support\Str;

class WholesalerOrderController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $wholesaler = $user->wholesaler;
        
        if (!$wholesaler) {
            abort(403, 'Wholesaler profile not found.');
        }
        
        $orders = Order::where('wholesaler_id', $wholesaler->id)
            ->with(['orderItems.item'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);
            
        return view('wholesaler.orders.index', compact('orders', 'user'));
    }
    
    public function create()
    {
        $user = Auth::user();
        $items = Item::where('is_active', true)
            ->where('stock_quantity', '>', 0)
            ->orderBy('name')
            ->get();
            
        return view('wholesaler.orders.create', compact('items', 'user'));
    }
    
    public function store(Request $request)
    {
        $request->validate([
            'items' => 'required|array|min:1',
            'items.*.item_id' => 'required|exists:items,id',
            'items.*.quantity' => 'required|integer|min:1',
            'payment_method' => 'required|in:cash,credit,bank_transfer,installment',
            'shipping_address' => 'required|string|max:500',
            'notes' => 'nullable|string|max:1000',
        ]);
        
        $user = Auth::user();
        $wholesaler = $user->wholesaler;
        
        if (!$wholesaler) {
            abort(403, 'Wholesaler profile not found.');
        }
        
        // Calculate total amount
        $totalAmount = 0;
        $orderItems = [];
        
        foreach ($request->items as $itemData) {
            $item = Item::find($itemData['item_id']);
            if (!$item || $item->stock_quantity < $itemData['quantity']) {
                return back()->withErrors(['items' => "Insufficient stock for {$item->name}"]);
            }
            
            $itemTotal = $item->base_price * $itemData['quantity'];
            $totalAmount += $itemTotal;
            
            $orderItems[] = [
                'item_id' => $item->id,
                'quantity' => $itemData['quantity'],
                'unit_price' => $item->base_price,
                'total_price' => $itemTotal,
            ];
        }
        
        // Create order
        $order = Order::create([
            'wholesaler_id' => $wholesaler->id,
            'order_number' => 'ORD-' . strtoupper(Str::random(8)),
            'status' => 'pending',
            'order_date' => now(),
            'total_amount' => $totalAmount,
            'payment_method' => $request->payment_method,
            'shipping_address' => $request->shipping_address,
            'notes' => $request->notes,
            'estimated_delivery' => now()->addDays(14), // Default 2 weeks
        ]);
        
        // Create order items
        foreach ($orderItems as $itemData) {
            $order->orderItems()->create($itemData);
            
            // Update stock quantity
            $item = Item::find($itemData['item_id']);
            $item->decrement('stock_quantity', $itemData['quantity']);
        }
        
        return redirect()->route('wholesaler.orders.index')
            ->with('success', 'Order placed successfully! Order #' . $order->order_number);
    }
    
    public function show(Order $order)
    {
        $user = Auth::user();
        $wholesaler = $user->wholesaler;
        
        if ($order->wholesaler_id !== $wholesaler->id) {
            abort(403, 'Access denied.');
        }
        
        $order->load(['orderItems.item']);
        
        return view('wholesaler.orders.show', compact('order', 'user'));
    }
    
    public function cancel(Order $order)
    {
        $user = Auth::user();
        $wholesaler = $user->wholesaler;
        
        if ($order->wholesaler_id !== $wholesaler->id) {
            abort(403, 'Access denied.');
        }
        
        if (!in_array($order->status, ['pending', 'confirmed'])) {
            return back()->withErrors(['status' => 'Cannot cancel order in current status.']);
        }
        
        // Restore stock quantities
        foreach ($order->orderItems as $orderItem) {
            $item = $orderItem->item;
            $item->increment('stock_quantity', $orderItem->quantity);
        }
        
        $order->update(['status' => 'cancelled']);
        
        return redirect()->route('wholesaler.orders.index')
            ->with('success', 'Order cancelled successfully.');
    }
} 