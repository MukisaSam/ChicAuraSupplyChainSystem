@extends('layouts.public')

@section('title', 'Order Details')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <!-- Order Summary -->
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-light">
                    <h4 class="mb-0">Order #{{ $order->order_number }}</h4>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h5 class="mb-3">Order Details</h5>
                            <p><strong>Date:</strong> {{ $order->created_at->format('M d, Y \a\t g:i A') }}</p>
                            <p><strong>Status:</strong> 
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
                            </p>
                            <p><strong>Payment Method:</strong> {{ ucfirst(str_replace('_', ' ', $order->payment_method)) }}</p>
                            <p><strong>Total Amount:</strong> UGX {{ number_format($order->total_amount, 2) }}</p>
                        </div>
                        <div class="col-md-6">
                            <h5 class="mb-3">Shipping Information</h5>
                            @if(isset($order->shipping_address['name']))
                                {{-- New format --}}
                                <p><strong>Name:</strong> {{ $order->shipping_address['name'] }}</p>
                                <p><strong>Phone:</strong> {{ $order->shipping_address['phone'] }}</p>
                                <p><strong>Address:</strong> {{ $order->shipping_address['address'] }}</p>
                                <p><strong>City:</strong> {{ $order->shipping_address['city'] }}</p>
                                @isset($order->shipping_address['postal_code'])
                                    <p><strong>Postal Code:</strong> {{ $order->shipping_address['postal_code'] }}</p>
                                @endisset
                            @elseif(isset($order->shipping_address['district']) || isset($order->shipping_address['area']))
                                {{-- Old format --}}
                                <p><strong>District:</strong> {{ $order->shipping_address['district'] ?? 'N/A' }}</p>
                                <p><strong>Area:</strong> {{ $order->shipping_address['area'] ?? 'N/A' }}</p>
                                <p><strong>Street:</strong> {{ $order->shipping_address['street'] ?? 'N/A' }}</p>
                                @if(isset($order->shipping_address['landmark']) && $order->shipping_address['landmark'])
                                    <p><strong>Landmark:</strong> {{ $order->shipping_address['landmark'] }}</p>
                                @endif
                            @else
                                <p>Address information unavailable or in unsupported format.</p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Order Items -->
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-light">
                    <h5 class="mb-0">Order Items</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Product</th>
                                    <th>Price</th>
                                    <th>Quantity</th>
                                    <th>Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($order->customerOrderItems as $item)
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                @if($item->item->image)
                                                    <img src="{{ asset($item->item->image) }}" alt="{{ $item->item->name }}" width="60" class="me-3">
                                                @endif
                                                <div>
                                                    <h6 class="mb-1">{{ $item->item->name }}</h6>
                                                    @if($item->size || $item->color)
                                                        <small class="text-muted">
                                                            @if($item->size) Size: {{ $item->size }} @endif
                                                            @if($item->color) Color: {{ $item->color }} @endif
                                                        </small>
                                                    @endif
                                                </div>
                                            </div>
                                        </td>
                                        <td>Ugx{{ number_format($item->unit_price, 2) }}</td>
                                        <td>{{ $item->quantity }}</td>
                                        <td>UGX {{ number_format($item->total_price, 2) }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr>
                                    <td colspan="3" class="text-end"><strong>Subtotal:</strong></td>
                                    <td>UGX {{ number_format($order->total_amount / 1.1, 2) }}</td>
                                </tr>
                                <tr>
                                    <td colspan="3" class="text-end"><strong>Tax (10%):</strong></td>
                                    <td>UGX {{ number_format($order->total_amount - ($order->total_amount / 1.1), 2) }}</td>
                                </tr>
                                <tr>
                                    <td colspan="3" class="text-end"><strong>Total:</strong></td>
                                    <td>UGX {{ number_format($order->total_amount, 2) }}</td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Order Actions -->
            <div class="d-flex justify-content-between">
                @if(in_array($order->status, ['pending', 'confirmed']))
                    <form action="{{ route('customer.order.cancel', $order->id) }}" method="POST">
                        @csrf
                        <button type="submit" class="btn btn-danger" onclick="return confirm('Are you sure you want to cancel this order?')">
                            Cancel Order
                        </button>
                    </form>
                @else
                    <div></div>
                @endif

                <div class="d-flex gap-2">
                    <a href="{{ route('customer.orders') }}" class="btn btn-outline-secondary">
                        Back to Orders
                    </a>
                    <!-- <a href="{{ route('customer.order.reorder', $order->id) }}" class="btn btn-primary">
                        Reorder
                    </a> -->
                </div>
            </div>
        </div>
    </div>
</div>
@endsection