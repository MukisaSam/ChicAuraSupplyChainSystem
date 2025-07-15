<?php

namespace App\Http\Controllers;

use App\Models\CustomerOrder;
use App\Models\CustomerOrderItem;
use App\Models\Item;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class CustomerOrderController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'payment_method' => ['required', 'in:credit_card,debit_card,paypal,bank_transfer,cash_on_delivery,mobile_money'],
            'shipping_address' => ['required', 'array'],
            'shipping_address.name' => ['required', 'string', 'max:255'],
            'shipping_address.phone' => ['required', 'string', 'max:20'],
            'shipping_address.address' => ['required', 'string', 'max:1000'],
            'shipping_address.city' => ['required', 'string', 'max:255'],
            'shipping_address.postal_code' => ['nullable', 'string', 'max:20'],
            'billing_same_as_shipping' => ['nullable', 'boolean'],
            'billing_address' => ['required_if:billing_same_as_shipping,false', 'array'],
            'notes' => ['nullable', 'string', 'max:1000'],
        ]);

        $customer = Auth::guard('customer')->user();
        $cart = Session::get('cart', []);

        if (empty($cart)) {
            return redirect()->route('public.cart')->with('error', 'Your cart is empty');
        }

        try {
            DB::beginTransaction();

            // Calculate total amount
            $totalAmount = 0;
            $orderItems = [];

            foreach ($cart as $cartKey => $item) {
                $product = Item::find($item['product_id']);
                if (!$product || !$product->is_active) {
                    throw new \Exception("Product no longer available: " . ($product->name ?? 'Unknown'));
                }

                $itemTotal = $product->base_price * $item['quantity'];
                $totalAmount += $itemTotal;

                $orderItems[] = [
                    'item_id' => $product->id,
                    'quantity' => $item['quantity'],
                    'unit_price' => $product->base_price,
                    'total_price' => $itemTotal,
                    'size' => $item['size'] ?? null,
                    'color' => $item['color'] ?? null,
                ];
            }

            // Prepare billing address
            $billingAddress = $request->billing_same_as_shipping 
                ? $request->shipping_address 
                : $request->billing_address;

            // Create customer order
            $order = CustomerOrder::create([
                'customer_id' => $customer->id,
                'total_amount' => $totalAmount,
                'payment_method' => $request->payment_method,
                'payment_status' => $request->payment_method === 'cash_on_delivery' ? 'pending' : 'pending',
                'shipping_address' => $request->shipping_address,
                'billing_address' => $billingAddress,
                'notes' => $request->notes,
                'status' => 'pending',
                'estimated_delivery' => now()->addDays(7), // Default 7 days delivery
            ]);

            // Create order items
            foreach ($orderItems as $itemData) {
                CustomerOrderItem::create(array_merge($itemData, [
                    'customer_order_id' => $order->id,
                ]));
            }

            // Clear cart
            Session::forget('cart');

            DB::commit();

            // Redirect to order confirmation
            return redirect()->route('customer.order.confirmation', $order->id)
                ->with('success', 'Order placed successfully!');

        } catch (\Exception $e) {
            DB::rollBack();
            
            return redirect()->back()
                ->withInput()
                ->with('error', 'Failed to place order: ' . $e->getMessage());
        }
    }

    public function confirmation($orderId)
    {
        $customer = Auth::guard('customer')->user();
        $order = $customer->customerOrders()
            ->with('customerOrderItems.item')
            ->findOrFail($orderId);

        return view('public.order-confirmation', compact('order'));
    }

    public function show($orderId)
    {
        $customer = Auth::guard('customer')->user();
        $order = $customer->customerOrders()
            ->with('customerOrderItems.item')
            ->findOrFail($orderId);

        return view('public.customer-order-detail', compact('order'));
    }

    public function cancel(Request $request, $orderId)
    {
        $customer = Auth::guard('customer')->user();
        $order = $customer->customerOrders()->findOrFail($orderId);

        if (!in_array($order->status, ['pending', 'confirmed'])) {
            return redirect()->back()->with('error', 'This order cannot be cancelled');
        }

        $order->update([
            'status' => 'cancelled',
            'notes' => ($order->notes ? $order->notes . "\n" : '') . 'Cancelled by customer on ' . now()->format('Y-m-d H:i:s')
        ]);

        return redirect()->route('customer.orders')->with('success', 'Order cancelled successfully');
    }

    public function reorder($orderId)
    {
        $customer = Auth::guard('customer')->user();
        $order = $customer->customerOrders()
            ->with('customerOrderItems.item')
            ->findOrFail($orderId);

        $cart = Session::get('cart', []);

        foreach ($order->customerOrderItems as $orderItem) {
            $product = $orderItem->item;
            
            if ($product && $product->is_active) {
                $cartKey = $product->id;
                if ($orderItem->size || $orderItem->color) {
                    $cartKey = $product->id . '_' . ($orderItem->size ?? '') . '_' . ($orderItem->color ?? '');
                }

                if (isset($cart[$cartKey])) {
                    $cart[$cartKey]['quantity'] += $orderItem->quantity;
                } else {
                    $cart[$cartKey] = [
                        'product_id' => $product->id,
                        'quantity' => $orderItem->quantity,
                        'size' => $orderItem->size,
                        'color' => $orderItem->color,
                        'added_at' => now()
                    ];
                }
            }
        }

        Session::put('cart', $cart);

        return redirect()->route('public.cart')->with('success', 'Items from previous order added to cart');
    }

    public function track($orderId)
    {
        $customer = Auth::guard('customer')->user();
        $order = $customer->customerOrders()->findOrFail($orderId);

        $trackingSteps = [
            'pending' => 'Order Received',
            'confirmed' => 'Order Confirmed',
            'processing' => 'Processing',
            'shipped' => 'Shipped',
            'delivered' => 'Delivered',
        ];

        return view('public.order-tracking', compact('order', 'trackingSteps'));
    }
}