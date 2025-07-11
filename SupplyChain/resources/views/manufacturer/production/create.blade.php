@extends('manufacturer.layouts.dashboard')
@section('content')
<div class="container mx-auto py-6">
    <h1 class="text-2xl font-bold text-white mb-4">Create New Work Order</h1>
    <form method="POST" action="{{ route('manufacturer.production.store') }}">
        @csrf
        <div class="mb-4">
            <label class="block font-semibold text-white mb-1">Product</label>
            <select name="product_id" class="w-full border rounded p-2" required>
                <option value="">Select a product</option>
                @foreach($products as $product)
                    <option value="{{ $product->id }}">{{ $product->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="mb-4">
            <label class="block font-semibold text-white mb-1">Quantity</label>
            <input type="number" name="quantity" class="w-full border rounded p-2" min="1" required>
        </div>
        <div class="mb-4">
            <label class="block font-semibold text-white mb-1">Scheduled Start</label>
            <input type="datetime-local" name="scheduled_start" class="w-full border rounded p-2" required>
        </div>
        <div class="mb-4">
            <label class="block font-semibold text-white mb-1">Scheduled End</label>
            <input type="datetime-local" name="scheduled_end" class="w-full border rounded p-2" required>
        </div>
        <div class="mb-4">
            <label class="block font-semibold text-white mb-1">Notes</label>
            <textarea name="notes" class="w-full border rounded p-2"></textarea>
        </div>
        <button type="submit" class="bg-indigo-600 text-white px-4 py-2 rounded">Create Work Order</button>
    </form>
</div>
@endsection 