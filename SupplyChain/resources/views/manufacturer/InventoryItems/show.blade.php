@extends('manufacturer.layouts.dashboard')
@section('content')
<div class="container mx-auto py-8">
    <div class="max-w-xl mx-auto bg-white rounded-lg shadow-lg p-8">
        <h1 class="text-2xl font-bold mb-6 text-indigo-700">Inventory Item Details</h1>
        <div class="mb-4">
            <span class="font-semibold text-gray-700">Name:</span>
            <span class="ml-2 text-gray-900">{{ $inventory_item->name }}</span>
        </div>
        <div class="mb-4">
            <span class="font-semibold text-gray-700">SKU:</span>
            <span class="ml-2 text-gray-900">{{ $inventory_item->sku }}</span>
        </div>
        <div class="mb-4">
            <span class="font-semibold text-gray-700">Quantity:</span>
            <span class="ml-2 text-gray-900">{{ $inventory_item->quantity }}</span>
        </div>
        <div class="mb-4 flex gap-4">
            <div>
                <span class="font-semibold text-gray-700">Min Stock:</span>
                <span class="ml-2 text-gray-900">{{ $inventory_item->min_stock }}</span>
            </div>
            <div>
                <span class="font-semibold text-gray-700">Max Stock:</span>
                <span class="ml-2 text-gray-900">{{ $inventory_item->max_stock }}</span>
            </div>
        </div>
        <div class="mb-4">
            <span class="font-semibold text-gray-700">Batch Number:</span>
            <span class="ml-2 text-gray-900">{{ $inventory_item->batch_number }}</span>
        </div>
        <div class="mb-4">
            <span class="font-semibold text-gray-700">Expiry Date:</span>
            <span class="ml-2 text-gray-900">{{ $inventory_item->expiry_date ? $inventory_item->expiry_date->format('Y-m-d') : '-' }}</span>
        </div>
        <div class="mt-8 flex flex-wrap gap-4 justify-end">
            <a href="{{ route('warehouses.inventory-items.index', $warehouse) }}" class="bg-gray-200 hover:bg-gray-300 text-gray-800 font-semibold py-2 px-4 rounded-lg shadow transition">Back</a>
            <a href="{{ route('warehouses.inventory-items.edit', [$warehouse, $inventory_item]) }}" class="bg-indigo-600 hover:bg-indigo-700 text-white font-semibold py-2 px-4 rounded-lg shadow transition">Edit</a>
            <form action="{{ route('warehouses.inventory-items.destroy', [$warehouse, $inventory_item]) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this item?');" class="inline">
                @csrf
                @method('DELETE')
                <button type="submit" class="bg-red-600 hover:bg-red-700 text-white font-semibold py-2 px-4 rounded-lg shadow transition">Delete</button>
            </form>
        </div>
    </div>
</div>
@endsection 