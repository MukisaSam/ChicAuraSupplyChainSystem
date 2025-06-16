@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Supply Request Details</h2>
    <div class="card mb-3">
        <div class="card-body">
            <h5 class="card-title">Item: {{ $supplyRequest->item->name }}</h5>
            <p class="card-text">Quantity: {{ $supplyRequest->quantity }}</p>
            <p class="card-text">Due Date: {{ $supplyRequest->due_date->format('M d, Y') }}</p>
            <p class="card-text">Status: <span class="badge bg-{{ $supplyRequest->status === 'pending' ? 'warning' : ($supplyRequest->status === 'accepted' ? 'success' : 'danger') }}">{{ ucfirst($supplyRequest->status) }}</span></p>
            <p class="card-text">Notes: {{ $supplyRequest->notes }}</p>
        </div>
    </div>
    @if($supplyRequest->status === 'pending')
    <form method="POST" action="{{ route('supplier.supply-requests.update', $supplyRequest) }}">
        @csrf
        @method('PUT')
        <div class="mb-3">
            <label for="status" class="form-label">Update Status</label>
            <select name="status" id="status" class="form-select">
                <option value="accepted">Accept</option>
                <option value="declined">Decline</option>
            </select>
        </div>
        <div class="mb-3">
            <label for="notes" class="form-label">Notes</label>
            <textarea name="notes" id="notes" class="form-control"></textarea>
        </div>
        <button type="submit" class="btn btn-primary">Update Request</button>
    </form>
    @endif
    @if($supplyRequest->priceNegotiation)
    <hr>
    <h4>Price Negotiation</h4>
    <p>Initial Price: ${{ number_format($supplyRequest->priceNegotiation->initial_price, 2) }}</p>
    <p>Status: {{ ucfirst($supplyRequest->priceNegotiation->status) }}</p>
    <form method="POST" action="{{ route('supplier.supply-requests.negotiate', $supplyRequest) }}">
        @csrf
        <div class="mb-3">
            <label for="counter_price" class="form-label">Counter Price</label>
            <input type="number" step="0.01" name="counter_price" id="counter_price" class="form-control" required>
        </div>
        <div class="mb-3">
            <label for="notes" class="form-label">Notes</label>
            <textarea name="notes" id="notes" class="form-control"></textarea>
        </div>
        <button type="submit" class="btn btn-warning">Submit Counter Offer</button>
    </form>
    @endif
</div>
@endsection 