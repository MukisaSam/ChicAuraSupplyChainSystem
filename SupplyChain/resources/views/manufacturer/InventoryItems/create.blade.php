@extends('manufacturer.layouts.dashboard')
@section('content')
<div class="container mx-auto py-8">
    <div class="max-w-xl mx-auto bg-white rounded-lg shadow-lg p-8">
        <h1 class="text-2xl font-bold mb-6 text-indigo-700">Add Inventory Item</h1>
        <form action="{{ route('warehouses.inventory-items.store', $warehouse) }}" method="POST">
            @csrf
            <div class="mb-4">
                <label class="block mb-1 font-semibold">Name</label>
                <input type="text" name="name" class="w-full border px-3 py-2 rounded" required value="{{ old('name') }}">
            </div>
            <div class="mb-4">
                <label class="block mb-1 font-semibold">SKU</label>
                <input type="text" name="sku" class="w-full border px-3 py-2 rounded" value="{{ old('sku') }}">
            </div>
            <div class="mb-4">
                <label class="block mb-1 font-semibold">Quantity</label>
                <input type="number" name="quantity" class="w-full border px-3 py-2 rounded" min="0" required value="{{ old('quantity', 0) }}">
            </div>
            <div class="mb-4">
                <label class="block mb-1 font-semibold">Min Stock</label>
                <input type="number" name="min_stock" class="w-full border px-3 py-2 rounded" min="0" value="{{ old('min_stock') }}">
            </div>
            <div class="mb-4">
                <label class="block mb-1 font-semibold">Max Stock</label>
                <input type="number" name="max_stock" class="w-full border px-3 py-2 rounded" min="0" value="{{ old('max_stock') }}">
            </div>
            <div class="mb-4">
                <label class="block mb-1 font-semibold">Batch Number</label>
                <input type="text" name="batch_number" class="w-full border px-3 py-2 rounded" value="{{ old('batch_number') }}">
            </div>
            <div class="mb-6">
                <label class="block mb-1 font-semibold">Expiry Date</label>
                <input type="date" name="expiry_date" class="w-full border px-3 py-2 rounded" value="{{ old('expiry_date') }}">
            </div>
            <div class="flex justify-end gap-4">
                <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white font-semibold py-2 px-6 rounded-lg shadow transition">Add Item</button>
                <a href="{{ route('warehouses.inventory-items.index', $warehouse) }}" class="bg-gray-200 hover:bg-gray-300 text-gray-800 font-semibold py-2 px-6 rounded-lg shadow transition">Cancel</a>
            </div>
        </form>
    </div>
</div>
@endsection 