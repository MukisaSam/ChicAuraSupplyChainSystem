@extends('layouts.admin-dashboard')

@section('content')
<div class="p-6 bg-gray-100 min-h-screen">
    <h1 class="text-2xl font-bold mb-4">Inventory Report (Last 7 Days)</h1>
    <p class="mb-2">Period: {{ $weekAgo->toDayDateTimeString() }} - {{ $now->toDayDateTimeString() }}</p>
    <p class="mb-4"><strong>Total Inventory Items:</strong> {{ $totalInventory }} | <strong>Low Stock Items (&lt; 10):</strong> {{ $lowStockItems->count() }}</p>
    <h2 class="text-lg font-semibold mt-6 mb-2">All Inventory Items</h2>
    <table class="min-w-full bg-white border border-gray-300 mb-6">
        <thead>
            <tr>
                <th class="px-4 py-2 border">Item Name</th>
                <th class="px-4 py-2 border">Quantity</th>
            </tr>
        </thead>
        <tbody>
            @forelse($inventoryItems as $item)
                <tr>
                    <td class="border px-4 py-2">{{ $item->name }}</td>
                    <td class="border px-4 py-2">{{ $item->quantity }}</td>
                </tr>
            @empty
                <tr><td colspan="2" class="text-center py-4">No inventory items found.</td></tr>
            @endforelse
        </tbody>
    </table>
    <h2 class="text-lg font-semibold mt-6 mb-2">Low Stock Items (&lt; 10)</h2>
    <table class="min-w-full bg-white border border-gray-300 mb-6">
        <thead>
            <tr>
                <th class="px-4 py-2 border">Item Name</th>
                <th class="px-4 py-2 border">Quantity</th>
            </tr>
        </thead>
        <tbody>
            @forelse($lowStockItems as $item)
                <tr>
                    <td class="border px-4 py-2">{{ $item->name }}</td>
                    <td class="border px-4 py-2">{{ $item->quantity }}</td>
                </tr>
            @empty
                <tr><td colspan="2" class="text-center py-4">No low stock items.</td></tr>
            @endforelse
        </tbody>
    </table>
    <a href="{{ route('admin.reports.index') }}" class="text-blue-600 hover:underline">&larr; Back to Reports Center</a>
</div>
@endsection 