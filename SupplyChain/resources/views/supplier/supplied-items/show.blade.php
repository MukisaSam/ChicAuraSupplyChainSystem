@extends('layouts.supplier-dashboard')

@section('content')
<div class="container">
    <h2>Supplied Item Details</h2>
    <div class="card mb-3">
        <div class="card-body">
            <h5 class="card-title">Item: {{ $suppliedItem->item->name }}</h5>
            <p class="card-text">Delivered Quantity: {{ $suppliedItem->delivered_quantity }}</p>
            <p class="card-text">Delivery Date: {{ $suppliedItem->delivery_date->format('M d, Y') }}</p>
            <p class="card-text">Quality Rating: {{ $suppliedItem->quality_rating }}</p>
            <p class="card-text">Status: <span class="badge bg-{{ $suppliedItem->status === 'delivered' ? 'success' : 'info' }}">{{ ucfirst($suppliedItem->status) }}</span></p>
        </div>
    </div>
    <form method="POST" action="{{ route('supplier.supplied-items.update', $suppliedItem) }}">
        @csrf
        @method('PUT')
        <div class="mb-3">
            <label for="delivered_quantity" class="form-label">Delivered Quantity</label>
            <input type="number" name="delivered_quantity" id="delivered_quantity" class="form-control" value="{{ $suppliedItem->delivered_quantity }}" required>
        </div>
        <div class="mb-3">
            <label for="delivery_date" class="form-label">Delivery Date</label>
            <input type="date" name="delivery_date" id="delivery_date" class="form-control" value="{{ $suppliedItem->delivery_date->format('Y-m-d') }}" required>
        </div>
        <div class="mb-3">
            <label for="notes" class="form-label">Notes</label>
            <textarea name="notes" id="notes" class="form-control"></textarea>
        </div>
        <button type="submit" class="btn btn-primary">Update Supplied Item</button>
    </form>
</div>
@endsection 