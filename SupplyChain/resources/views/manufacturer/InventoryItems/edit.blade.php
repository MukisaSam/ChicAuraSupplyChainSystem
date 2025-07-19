@extends('manufacturer.layouts.dashboard')
@section('content')
<div class="container mx-auto py-6">
    <h1 class="text-2xl font-bold mb-6 text-black">Edit Inventory Item</h1>
    <form action="{{ route('warehouses.inventory-items.update', [$warehouse, $inventory_item]) }}" method="POST" class="bg-white p-6 rounded-lg shadow-lg max-w-xl mx-auto">
        @csrf
        @method('PUT')
        <div class="mb-4">
            <label class="block mb-1 font-semibold">Name</label>
            <input type="text" name="name" class="w-full border px-3 py-2 rounded" value="{{ old('name', $inventory_item->name) }}" required>
        </div>
        <div class="mb-4">
            <label class="block mb-1 font-semibold">SKU</label>
            <input type="text" name="sku" class="w-full border px-3 py-2 rounded" value="{{ old('sku', $inventory_item->sku) }}" required>
        </div>
        <div class="mb-4">
            <label class="block mb-1 font-semibold">Quantity</label>
            <input type="number" name="quantity" class="w-full border px-3 py-2 rounded" value="{{ old('quantity', $inventory_item->quantity) }}" required>
        </div>
        <div class="mb-4 flex gap-4">
            <div class="w-1/2">
                <label class="block mb-1 font-semibold">Min Stock</label>
                <input type="number" name="min_stock" class="w-full border px-3 py-2 rounded" value="{{ old('min_stock', $inventory_item->min_stock) }}">
            </div>
            <div class="w-1/2">
                <label class="block mb-1 font-semibold">Max Stock</label>
                <input type="number" name="max_stock" class="w-full border px-3 py-2 rounded" value="{{ old('max_stock', $inventory_item->max_stock) }}">
            </div>
        </div>
        <div class="mb-4">
            <label class="block mb-1 font-semibold">Batch Number</label>
            <input type="text" name="batch_number" class="w-full border px-3 py-2 rounded" value="{{ old('batch_number', $inventory_item->batch_number) }}">
        </div>
        <div class="mb-4">
            <label class="block mb-1 font-semibold">Expiry Date</label>
            <input type="date" name="expiry_date" class="w-full border px-3 py-2 rounded" value="{{ old('expiry_date', $inventory_item->expiry_date ? $inventory_item->expiry_date->format('Y-m-d') : null) }}">
        </div>
        <div class="flex items-center mt-6">
            <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-lg shadow transition">Update Item</button>
            <a href="{{ route('warehouses.inventory-items.index', $warehouse) }}" class="ml-4 text-gray-600 hover:underline">Cancel</a>
        </div>
    </form>
</div>
@endsection 