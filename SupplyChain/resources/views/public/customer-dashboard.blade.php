@extends('layouts.public')

@section('title', 'Dashboard')

@section('content')
<div class="container py-5">
    <!-- Welcome Header -->
    <div class="mb-5">
        <h1 class="display-5 fw-bold text-primary mb-2">Welcome back, {{ $customer->name }}!</h1>
        <p class="text-muted">Here's what's happening with your account</p>
    </div>

    <!-- Dashboard Stats -->
    <div class="row g-4 mb-5">
        <div class="col-md-4">
            <div class="card h-100 shadow-sm border-0">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="p-3 bg-primary bg-opacity-10 rounded-circle text-primary me-3">
                            <i class="bi bi-bag-check fs-4"></i>
                        </div>
                        <div>
                            <p class="text-muted mb-1 small">Total Orders</p>
                            <h3 class="fw-bold mb-0">{{ $totalOrders }}</h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card h-100 shadow-sm border-0">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="p-3 bg-success bg-opacity-10 rounded-circle text-success me-3">
                            <i class="bi bi-currency-dollar fs-4"></i>
                        </div>
                        <div>
                            <p class="text-muted mb-1 small">Total Spent</p>
                            <h3 class="fw-bold mb-0">UGX {{ number_format($totalSpent, 2) }}</h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card h-100 shadow-sm border-0">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="p-3 bg-info bg-opacity-10 rounded-circle text-info me-3">
                            <i class="bi bi-heart fs-4"></i>
                        </div>
                        <div>
                            <p class="text-muted mb-1 small">Member Since</p>
                            <h3 class="fw-bold mb-0">{{ $customer->created_at->format('M Y') }}</h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4">
        <!-- Recent Orders -->
        <div class="col-lg-4">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h3 class="card-title h5 mb-0">Recent Orders</h3>
                        <a href="{{ route('customer.orders') }}" class="text-primary text-decoration-none small fw-medium">
                            View All
                        </a>
                    </div>

                    @if($recentOrders->count() > 0)
                        <div class="d-grid gap-3">
                            @foreach($recentOrders as $order)
                            <div class="border rounded-3 p-3">
                                <div class="d-flex justify-content-between align-items-start mb-2">
                                    <div>
                                        <p class="fw-semibold mb-1">#{{ $order->order_number }}</p>
                                        <p class="text-muted small mb-0">{{ $order->created_at->format('M d, Y') }}</p>
                                    </div>
                                    <span class="badge 
                                        @if($order->status === 'pending') bg-warning
                                        @elseif($order->status === 'processing') bg-primary
                                        @elseif($order->status === 'shipped') bg-info
                                        @elseif($order->status === 'delivered') bg-success
                                        @else bg-secondary
                                        @endif">
                                        {{ ucfirst($order->status) }}
                                    </span>
                                </div>
                                <p class="text-muted small mb-2">
                                    {{ $order->customerOrderItems->count() }} item(s) • UGX {{ number_format($order->total_amount, 2) }}
                                </p>
                                <a href="{{ route('customer.order.detail', $order->id) }}" 
                                   class="text-primary text-decoration-none small fw-medium">
                                    View Details <i class="bi bi-arrow-right"></i>
                                </a>
                            </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="bi bi-bag text-muted display-4 mb-3"></i>
                            <p class="text-muted mb-4">No orders yet</p>
                            <a href="{{ route('public.products') }}" 
                               class="btn btn-primary btn-sm">
                                <i class="bi bi-plus-circle me-1"></i> Start Shopping
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Recommended Products -->
        <div class="col-lg-8">
            @include('public.components.recommended-products')
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="card shadow-sm border-0 mt-5">
        <div class="card-body ">
            <h3 class="card-title h5 mb-4">Quick Actions</h3>
            <div class="row g-3 ">
                <div class="col-6 col-md-3">
                    <a href="{{ route('public.products') }}" 
                       class="btn btn-outline-primary w-100 d-flex flex-column align-items-center p-3 text-decoration-none">
                        <i class="bi bi-shop fs-3 text-primary mb-2"></i>
                        <span class="small fw-medium">Shop Products</span>
                    </a>
                </div>

                <div class="col-6 col-md-3">
                    <a href="{{ route('customer.orders') }}" 
                       class="btn btn-outline-success w-100 d-flex flex-column align-items-center p-3 text-decoration-none">
                        <i class="bi bi-bag-check fs-3 text-success mb-2"></i>
                        <span class="small fw-medium">My Orders</span>
                    </a>
                </div>

                <div class="col-6 col-md-3">
                    <a href="{{ route('customer.profile') }}" 
                       class="btn btn-outline-info w-100 d-flex flex-column align-items-center p-3 text-decoration-none">
                        <i class="bi bi-person fs-3 text-info mb-2"></i>
                        <span class="small fw-medium">Profile</span>
                    </a>
                </div>

                <div class="col-6 col-md-3">
                    <a href="{{ route('public.cart') }}" 
                       class="btn btn-outline-warning w-100 d-flex flex-column align-items-center p-3 text-decoration-none">
                        <i class="bi bi-cart3 fs-3 text-warning mb-2"></i>
                        <span class="small fw-medium">Shopping Cart</span>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Any dashboard-specific JavaScript can go here
    console.log('Customer dashboard loaded');
});
</script>
@endpush