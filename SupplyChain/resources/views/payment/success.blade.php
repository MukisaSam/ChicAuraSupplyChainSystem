@extends('layouts.public')

@section('title', 'Payment Successful')

@section('content')
<div class="container py-5">
    <div class="text-center">
        <i class="bi bi-check-circle-fill text-success display-1"></i>
        <h1 class="mt-3">Payment Successful!</h1>
        <p class="lead">Thank you for your order</p>
        
        <div class="card mt-4">
            <div class="card-body text-start">
                <h5>Order Details</h5>
                <p><strong>Order ID:</strong> #{{ $order->id }}</p>
                <p><strong>Payment Method:</strong> {{ $payment_method }}</p>
                <p><strong>Total Paid:</strong> ${{ number_format($order->total, 2) }}</p>
                
                <a href="{{ route('customer.orders.show', $order->id) }}" class="btn btn-primary mt-3">
                    View Order Details
                </a>
                <a href="{{ route('public.products') }}" class="btn btn-outline-secondary mt-3">
                    Continue Shopping
                </a>
            </div>
        </div>
    </div>
</div>
@endsection