@extends('layouts.public')

@section('title', 'My Orders')

@section('content')
<div class="container py-5">
    <!-- Page Header -->
    <div class="mb-5">
        <h1 class="display-5 fw-bold text-primary mb-2">My Orders</h1>
        <p class="text-muted">View and track all your orders</p>
    </div>

    @if($orders->count() > 0)
        <!-- Orders List -->
        <div class="row g-4">
            @foreach($orders as $order)
            <div class="col-12">
                <div class="card shadow-sm border-0">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col-md-3">
                                <div class="d-flex flex-column">
                                    <h5 class="fw-bold mb-1">#{{ $order->order_number }}</h5>
                                    <p class="text-muted small mb-0">{{ $order->created_at->format('M d, Y \a\t g:i A') }}</p>
                                </div>
                            </div>
                            
                            <div class="col-md-2">
                                <div class="text-center">
                                    <p class="text-muted small mb-1">Items</p>
                                    <h6 class="fw-bold mb-0">{{ $order->customerOrderItems->count() }}</h6>
                                </div>
                            </div>
                            
                            <div class="col-md-2">
                                <div class="text-center">
                                    <p class="text-muted small mb-1">Total</p>
                                    <h6 class="fw-bold mb-0">UGX{{ number_format($order->total_amount, 2) }}</h6>
                                </div>
                            </div>
                            
                            <div class="col-md-2">
                                <div class="text-center">
                                    <span class="badge 
                                        @if($order->status === 'pending') bg-warning
                                        @elseif($order->status === 'processing') bg-primary
                                        @elseif($order->status === 'shipped') bg-info
                                        @elseif($order->status === 'delivered') bg-success
                                        @elseif($order->status === 'cancelled') bg-danger
                                        @else bg-secondary
                                        @endif">
                                        {{ ucfirst($order->status) }}
                                    </span>
                                </div>
                            </div>
                            
                            <div class="col-md-3">
                                <div class="text-end">
                                    <a href="{{ route('customer.order.detail', $order->id) }}" 
                                       class="btn btn-outline-primary btn-sm">
                                        <i class="bi bi-eye me-1"></i> View Details
                                    </a>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Order Items Preview -->
                        <div class="mt-3 pt-3 border-top">
                            <div class="d-flex flex-wrap gap-2">
                                @foreach($order->customerOrderItems->take(3) as $item)
                                    <div class="d-flex align-items-center bg-light rounded px-2 py-1">
                                        <small class="text-muted">{{ $item->item->name }}</small>
                                        <span class="badge bg-secondary ms-2">{{ $item->quantity }}</span>
                                    </div>
                                @endforeach
                                @if($order->customerOrderItems->count() > 3)
                                    <div class="d-flex align-items-center">
                                        <small class="text-muted">+{{ $order->customerOrderItems->count() - 3 }} more items</small>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        <!-- Pagination -->
        <div class="d-flex justify-content-center mt-5">
            {{ $orders->links() }}
        </div>
    @else
        <!-- Empty State -->
        <div class="text-center py-5">
            <div class="mb-4">
                <i class="bi bi-bag text-muted" style="font-size: 4rem;"></i>
            </div>
            <h3 class="text-muted mb-3">No orders yet</h3>
            <p class="text-muted mb-4">You haven't placed any orders yet. Start shopping to see your orders here.</p>
            <a href="{{ route('public.products') }}" class="btn btn-primary btn-lg">
                <i class="bi bi-shop me-2"></i> Start Shopping
            </a>
        </div>
    @endif
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    console.log('Customer orders page loaded');
});
</script>
@endpush