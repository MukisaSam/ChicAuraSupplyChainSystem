<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Stripe\Stripe;
use Stripe\Checkout\Session as StripeSession;
use App\Models\CustomerOrder;
use App\Models\CustomerOrderItem;
use App\Models\Item;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Auth;

class PaymentController extends Controller
{
    public function process(Request $request)
    {
        $request->validate([
            'payment_method' => 'required|in:credit_card,debit_card,paypal,bank_transfer,mobile_money,cash_on_delivery',
            'shipping_address.name' => 'required',
            'shipping_address.phone' => 'required',
            'payment_method' => 'required|in:credit_card,debit_card,paypal,bank_transfer,mobile_money,cash_on_delivery'
        ]);

        $cart = Session::get('cart', []);
        if (empty($cart)) {
            return redirect()->route('cart.view')->with('error', 'Your cart is empty');
        }

        if ($request->payment_method === 'cash_on_delivery') {
            return $this->handleCashOnDelivery($request, $cart);
        }

        if (in_array($request->payment_method, ['credit_card', 'debit_card'])) {
            return $this->handleStripePayment($request, $cart); // Keep original Stripe handling
        }

        return back()->with('error', 'Payment method not yet implemented');
    }

    protected function handleCashOnDelivery(Request $request, $cart)
    {
        $order = $this->createCustomerOrder($request, $cart, 'cash_on_delivery');
        Session::forget('cart');
        
        return redirect()->route('customer.order.confirmation', $order->id)
            ->with('success', 'Order placed successfully! Payment will be collected on delivery.');
    }

    protected function handleStripePayment(Request $request, $cart)
    {
        // Keep original Stripe implementation exactly the same
        Stripe::setApiKey(env('STRIPE_SECRET'));

        $lineItems = [];
        $subtotal = 0;

        foreach ($cart as $cartKey => $item) {
            $product = Item::find($item['product_id']);
            if ($product) {
                $unitAmount = $product->base_price * 100;
                $subtotal += $product->base_price * $item['quantity'];
                
                $lineItems[] = [
                    'price_data' => [
                        'currency' => 'UGX',
                        'product_data' => [
                            'name' => $product->name,
                            'metadata' => [
                                'product_id' => $product->id,
                                'size' => $item['size'] ?? null,
                                'color' => $item['color'] ?? null
                            ]
                        ],
                        'unit_amount' => $unitAmount,
                    ],
                    'quantity' => $item['quantity'],
                ];
            }
        }

        $tax = $subtotal * 0.1;
        $lineItems[] = [
            'price_data' => [
                'currency' => 'UGX',
                'product_data' => ['name' => 'Tax (10%)'],
                'unit_amount' => $tax * 100,
            ],
            'quantity' => 1,
        ];

        $session = StripeSession::create([
            'payment_method_types' => ['card'],
            'line_items' => $lineItems,
            'mode' => 'payment',
            'success_url' => route('payment.success').'?session_id={CHECKOUT_SESSION_ID}&payment_method=stripe',
            'cancel_url' => route('payment.cancel'),
            'customer_email' => auth()->guard('customer')->user()->email,
            'metadata' => [
                'cart_data' => json_encode($cart),
                'order_data' => json_encode($request->except('_token'))
            ]
        ]);

        return redirect($session->url);
    }

    protected function createCustomerOrder(Request $request, $cart, $paymentMethod)
    {
        $totalAmount = 0;
        $orderItems = [];

        foreach ($cart as $cartKey => $item) {
            $product = Item::find($item['product_id']);
            if ($product) {
                $itemTotal = $product->base_price * $item['quantity'];
                $totalAmount += $itemTotal;
                
                $orderItems[] = [
                    'item_id' => $product->id,
                    'quantity' => $item['quantity'],
                    'unit_price' => $product->base_price,
                    'total_price' => $itemTotal,
                    'size' => $item['size'] ?? null,
                    'color' => $item['color'] ?? null
                ];
            }
        }

        // Add tax (10%)
        $totalAmount *= 1.1;

        $order = CustomerOrder::create([
            'customer_id' => auth()->guard('customer')->id(),
            'total_amount' => $totalAmount,
            'payment_method' => $paymentMethod,
            'payment_status' => $paymentMethod === 'cash_on_delivery' ? 'pending' : 'pending',
            'shipping_address' => $request->shipping_address,
            'billing_address' => $request->billing_same_as_shipping ? 
                               $request->shipping_address : 
                               $request->billing_address,
            'status' => 'pending',
            'estimated_delivery' => now()->addDays(7),
            'notes' => $request->notes
        ]);

        foreach ($orderItems as $itemData) {
            CustomerOrderItem::create(array_merge($itemData, [
                'customer_order_id' => $order->id
            ]));
        }

        return $order;
    }

    public function success(Request $request)
    {
        if ($request->has('session_id') && $request->query('payment_method') === 'stripe') {
            Stripe::setApiKey(env('STRIPE_SECRET'));
            $session = StripeSession::retrieve($request->session_id);
            
            if ($session->payment_status === 'paid') {
                $cart = json_decode($session->metadata->cart_data, true);
                $orderData = json_decode($session->metadata->order_data, true);
                
                $request = new Request($orderData);
                $order = $this->createCustomerOrder($request, $cart, 'credit_card');
                
                Session::forget('cart');
                
                return redirect()->route('customer.order.confirmation', $order->id);
            }
        }

        if (session()->has('order_id')) {
            $order = CustomerOrder::find(session('order_id'));
            return redirect()->route('customer.order.confirmation', $order->id);
        }

        return redirect()->route('cart.view')->with('error', 'Invalid payment confirmation');
    }

    public function cancel()
    {
        return view('payment.cancel');
    }
}