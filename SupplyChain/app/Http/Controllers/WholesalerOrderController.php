<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Item;
use App\Models\Wholesaler;
use Illuminate\Support\Str;
use App\Notifications\OrderPlacedNotification;
use App\Models\Invoice;

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
        $finishedProducts = Item::where('type', 'finished_product')->where('is_active', true)->get();
        $manufacturers = \App\Models\Manufacturer::orderBy('id')->get();

        return view('wholesaler.orders.create', compact('finishedProducts', 'user', 'manufacturers'));
    }
    
    public function store(Request $request)
    {
        $itemsInput = $request->input('items', []);
        // Filter only selected items
        $selectedItems = array_filter($itemsInput, function($item) {
            return isset($item['selected']) && $item['selected'];
        });
        $request->merge(['selected_items' => $selectedItems]);
        $request->validate([
            'manufacturer_id' => 'required|exists:manufacturers,id',
            'selected_items' => 'required|array|min:1',
            'selected_items.*.item_id' => 'required|exists:items,id',
            'selected_items.*.quantity' => 'required|integer|min:1',
            'payment_method' => 'required|in:cash on delivery,mobile money,bank_transfer',
            'delivery_address' => 'required|string|max:500',
            'notes' => 'nullable|string|max:1000',
        ], [
            'selected_items.required' => 'Please select at least one product and enter quantity.',
            'selected_items.*.quantity.required' => 'Please enter quantity for each selected product.',
        ]);
        
        $user = Auth::user();
        $wholesaler = $user->wholesaler;
        
        if (!$wholesaler) {
            abort(403, 'Wholesaler profile not found.');
        }
        
        // Calculate total amount
        $totalAmount = 0;
        $orderItems = [];
        
        foreach ($selectedItems as $itemData) {
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
            'manufacturer_id' => $request->manufacturer_id,
            'order_number' => 'ORD-' . strtoupper(Str::random(8)),
            'status' => 'pending',
            'order_date' => now(),
            'total_amount' => $totalAmount,
            'payment_method' => $request->payment_method,
            'delivery_address' => $request->delivery_address,
            'notes' => $request->notes,
            'estimated_delivery' => now()->addDays(14), // Default 2 weeks
        ]);
        
        // Generate invoice for the order
        $invoice = Invoice::create([
            'order_id' => $order->id,
            'invoice_number' => 'INV-' . strtoupper(Str::random(8)),
            'amount' => $order->total_amount,
            'status' => 'unpaid',
            'due_date' => now()->addDays(14),
        ]);
        
        // Notify manufacturer (database notification)
        $manufacturer = $order->manufacturer;
        if ($manufacturer && $manufacturer->user) {
            $manufacturer->user->notify(new OrderPlacedNotification($order));
        }
        
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
        
        $order->load(['orderItems.item', 'manufacturer.user']);
        // Example timeline logic (replace with real events if available)
        $timeline = [
            [
                'type' => 'success',
                'icon' => 'fa-check-circle',
                'title' => 'Order Placed',
                'description' => 'Your order was placed successfully.',
                'timestamp' => $order->created_at,
            ],
        ];
        return view('wholesaler.orders.show', compact('order', 'user', 'timeline'));
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