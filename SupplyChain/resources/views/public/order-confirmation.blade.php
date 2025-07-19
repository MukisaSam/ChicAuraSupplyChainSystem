@extends('layouts.public')

@section('title', 'Order Confirmation')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card shadow-sm">
                <div class="card-header bg-success text-white">
                    <h3 class="mb-0">Order Confirmed</h3>
                </div>
                <div class="card-body">
                    <div class="alert alert-success">
                        <h4 class="alert-heading">Thank you for your order!</h4>
                        <p>Your order #{{ $order->order_number }} has been received.</p>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <h5>Order Summary</h5>
                            <p><strong>Order Number:</strong> {{ $order->order_number }}</p>
                            <p><strong>Date:</strong> {{ $order->created_at->format('M d, Y \a\t g:i A') }}</p>
                            <p><strong>Total:</strong> ${{ number_format($order->total_amount, 2) }}</p>
                            <p><strong>Payment Method:</strong> {{ ucfirst(str_replace('_', ' ', $order->payment_method)) }}</p>
                        </div>
                        <div class="col-md-6">
                            <h5>Shipping Information</h5>
                            <p>{{ $order->shipping_address['name'] }}</p>
                            <p>{{ $order->shipping_address['address'] }}</p>
                            <p>{{ $order->shipping_address['city'] }}</p>
                            <p>{{ $order->shipping_address['phone'] }}</p>
                        </div>
                    </div>

                    <div class="mt-4">
                        <a href="{{ route('customer.order.detail', $order->id) }}" class="btn btn-primary">
                            View Order Details
                        </a>
                        <a href="{{ route('public.products') }}" class="btn btn-outline-secondary">
                            Continue Shopping
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection