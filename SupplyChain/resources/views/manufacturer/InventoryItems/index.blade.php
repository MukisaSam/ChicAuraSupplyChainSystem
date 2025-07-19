@extends('manufacturer.layouts.dashboard')
@section('content')
<div class="container mx-auto py-8">
    <h1 class="text-3xl font-bold text-black mb-6">Inventory for {{ $warehouse->location }}</h1>
    <div class="flex justify-end mb-6">
        <a href="{{ route('warehouses.inventory-items.create', $warehouse) }}" class="bg-indigo-600 hover:bg-indigo-700 text-white font-semibold py-2 px-4 rounded-lg shadow transition">Add Inventory Item</a>
    </div>
    @if(session('success'))
        <div class="bg-green-100 text-green-800 p-2 rounded mb-4">{{ session('success') }}</div>
    @endif
    <div class="bg-white rounded-lg shadow-lg overflow-x-auto">
        <table class="min-w-full table-auto">
            <thead class="bg-gray-100">
                <tr>
                    <th class="px-4 py-3 text-left font-semibold text-gray-700">Name</th>
                    <th class="px-4 py-3 text-left font-semibold text-gray-700">SKU</th>
                    <th class="px-4 py-3 text-left font-semibold text-gray-700">Quantity</th>
                    <th class="px-4 py-3 text-left font-semibold text-gray-700">Min Stock</th>
                    <th class="px-4 py-3 text-left font-semibold text-gray-700">Max Stock</th>
                    <th class="px-4 py-3 text-left font-semibold text-gray-700">Batch #</th>
                    <th class="px-4 py-3 text-left font-semibold text-gray-700">Expiry Date</th>
                    <th class="px-4 py-3 text-left font-semibold text-gray-700">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($items as $item)
                <tr class="even:bg-gray-50 hover:bg-indigo-50 transition @if($item->min_stock && $item->quantity < $item->min_stock) bg-red-50 @endif">
                    <td class="border-t px-4 py-2">{{ $item->name }}</td>
                    <td class="border-t px-4 py-2">{{ $item->sku }}</td>
                    <td class="border-t px-4 py-2 font-bold @if($item->min_stock && $item->quantity < $item->min_stock) text-red-600 @endif">{{ $item->quantity }}</td>
                    <td class="border-t px-4 py-2">{{ $item->min_stock }}</td>
                    <td class="border-t px-4 py-2">{{ $item->max_stock }}</td>
                    <td class="border-t px-4 py-2">{{ $item->batch_number }}</td>
                    <td class="border-t px-4 py-2">{{ $item->expiry_date }}</td>
                    <td class="border-t px-4 py-2">
                        <div class="flex flex-wrap gap-2">
                            <a href="{{ route('warehouses.inventory-items.show', [$warehouse, $item]) }}" class="bg-blue-600 hover:bg-blue-700 text-white font-semibold py-1 px-3 rounded-lg shadow transition">View</a>
                            <a href="{{ route('warehouses.inventory-items.edit', [$warehouse, $item]) }}" class="bg-yellow-500 hover:bg-yellow-600 text-white font-semibold py-1 px-3 rounded-lg shadow transition">Edit</a>
                            <form action="{{ route('warehouses.inventory-items.destroy', [$warehouse, $item]) }}" method="POST" class="inline-block" onsubmit="return confirm('Are you sure you want to delete this item?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="bg-red-600 hover:bg-red-700 text-white font-semibold py-1 px-3 rounded-lg shadow transition focus:outline-none focus:ring-2 focus:ring-red-400">Delete</button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" class="text-center text-gray-400 py-8">No inventory items found.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="mt-6 flex justify-center">
        {{ $items->links() }}
    </div>
</div>
@endsection 