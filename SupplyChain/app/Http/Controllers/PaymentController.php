<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Stripe\Stripe;
use Stripe\Checkout\Session as StripeSession;
use App\Models\Order;
use App\Models\Item;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Mail;

class PaymentController extends Controller
{
    public function process(Request $request)
    {
        // Validate the request
        $validated = $request->validate([
            'shipping_address.name' => 'required',
            'shipping_address.phone' => 'required',
            'payment_method' => 'required|in:credit_card,debit_card,paypal,bank_transfer,mobile_money,cash_on_delivery'
        ]);

        // Get cart from session (using CartController's logic)
        $cart = Session::get('cart', []);
        if (empty($cart)) {
            return redirect()->route('cart.view')->with('error', 'Your cart is empty');
        }

        // For cash on delivery - no payment processing needed
        if ($request->payment_method === 'cash_on_delivery') {
            return $this->handleCashOnDelivery($request, $cart);
        }

        // For card payments (Stripe)
        if (in_array($request->payment_method, ['credit_card', 'debit_card'])) {
            return $this->handleStripePayment($request, $cart);
        }

        // For other payment methods
        return back()->with('error', 'Payment method not yet implemented');
    }

    protected function handleCashOnDelivery(Request $request, $cart)
    {
        // Create order with 'pending' status
        $order = $this->createOrder($request, $cart, 'cash_on_delivery');
        
        // Clear cart
        Session::forget('cart');
        
        // Redirect to success page with order details
        return redirect()->route('payment.success')->with([
            'order_id' => $order->id,
            'payment_method' => 'Cash on Delivery'
        ]);
    }

    protected function handleStripePayment(Request $request, $cart)
    {
        Stripe::setApiKey(env('STRIPE_SECRET'));

        $lineItems = [];
        $subtotal = 0;

        foreach ($cart as $cartKey => $item) {
            $product = Item::find($item['product_id']);
            if ($product) {
                $unitAmount = $product->base_price * 100; // Convert to cents
                $subtotal += $product->base_price * $item['quantity'];
                
                $lineItems[] = [
                    'price_data' => [
                        'currency' => 'usd',
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

        // Add tax (10% as in CartController)
        $tax = $subtotal * 0.1;
        $lineItems[] = [
            'price_data' => [
                'currency' => 'usd',
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

    protected function createOrder(Request $request, $cart, $paymentMethod)
    {
        // Calculate totals using CartController's logic
        $subtotal = 0;
        foreach ($cart as $item) {
            $product = Item::find($item['product_id']);
            if ($product) {
                $subtotal += $product->base_price * $item['quantity'];
            }
        }
        $tax = $subtotal * 0.1;
        $total = $subtotal + $tax;

        $order = Order::create([
            'customer_id' => auth()->guard('customer')->id(),
            'wholesaler_id' => 1,
            'manufacturer_id' => 1,
            'shipping_address' => $request->shipping_address,
            'billing_address' => $request->billing_same_as_shipping ? 
                               $request->shipping_address : 
                               $request->billing_address,
            'payment_method' => $paymentMethod,
            'status' => $paymentMethod === 'cash_on_delivery' ? 'pending' : 'processing',
            'subtotal' => $subtotal,
            'tax' => $tax,
            'total' => $total,
            'notes' => $request->notes
        ]);

        // Add order items using cart data
        foreach ($cart as $cartKey => $item) {
            $product = Item::find($item['product_id']);
            if ($product) {
                $order->items()->create([
                    'product_id' => $product->id,
                    'quantity' => $item['quantity'],
                    'price' => $product->base_price,
                    'options' => [
                        'size' => $item['size'] ?? null,
                        'color' => $item['color'] ?? null
                    ]
                ]);
            }
        }

        return $order;
    }

    public function success(Request $request)
    {
        if ($request->has('session_id') && $request->query('payment_method') === 'stripe') {
            // Verify Stripe payment
            Stripe::setApiKey(env('STRIPE_SECRET'));
            $session = StripeSession::retrieve($request->session_id);
            
            if ($session->payment_status === 'paid') {
                $cart = json_decode($session->metadata->cart_data, true);
                $orderData = json_decode($session->metadata->order_data, true);
                
                $request = new Request($orderData);
                $order = $this->createOrder($request, $cart, 'stripe');
                
                // Clear cart
                Session::forget('cart');
                
                return view('payment.success', [
                    'order' => $order,
                    'payment_method' => 'Credit/Debit Card'
                ]);
            }
        }

        // For cash on delivery
        if (session()->has('order_id')) {
            $order = Order::find(session('order_id'));
            
            return view('payment.success', [
                'order' => $order,
                'payment_method' => session('payment_method')
            ]);
        }

        return redirect()->route('cart.view')->with('error', 'Invalid payment confirmation');
    }

    public function cancel()
    {
        return view('payment.cancel');
    }
}