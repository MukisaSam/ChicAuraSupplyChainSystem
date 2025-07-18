@extends('layouts.public')

@section('title', 'Payment Cancelled')

@section('content')
<div class="container py-5">
    <div class="text-center">
        <i class="bi bi-x-circle-fill text-danger display-1"></i>
        <h1 class="mt-3">Payment Cancelled</h1>
        <p class="lead">Your payment was not completed</p>
        
        <div class="mt-4">
            <a href="{{ route('cart.checkout') }}" class="btn btn-primary">
                <i class="bi bi-arrow-left"></i> Back to Checkout
            </a>
            <a href="{{ route('public.products') }}" class="btn btn-outline-secondary">
                Continue Shopping
            </a>
        </div>
    </div>
</div>
@endsection