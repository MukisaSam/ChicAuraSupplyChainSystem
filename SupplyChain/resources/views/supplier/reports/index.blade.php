@extends('layouts.supplier')

@section('content')
<div class="p-6 bg-gray-100 min-h-screen">
    <h1 class="text-2xl font-bold mb-4">Your Weekly Report</h1>
    <p class="mb-2"><strong>Period:</strong> {{ $period_start->toDayDateTimeString() }} - {{ $period_end->toDayDateTimeString() }}</p>
    <p class="mb-4"><strong>Total Sales:</strong> ${{ number_format($totalSales, 2) }} | <strong>Number of Orders:</strong> {{ $salesCount }}</p>
    <form action="{{ route('supplier.reports.download') }}" method="POST" class="mb-6">
        @csrf
        <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white py-2 px-4 rounded">Download Report (HTML)</button>
    </form>
    <h2 class="text-lg font-semibold mt-6 mb-2">Orders</h2>
    <table class="min-w-full bg-white border border-gray-300 mb-6">
        <thead>
            <tr>
                <th class="px-4 py-2 border">Order #</th>
                <th class="px-4 py-2 border">Total</th>
                <th class="px-4 py-2 border">Date</th>
            </tr>
        </thead>
        <tbody>
            @forelse($orders as $order)
                <tr>
                    <td class="border px-4 py-2">{{ $order->id }}</td>
                    <td class="border px-4 py-2">${{ number_format($order->total_amount, 2) }}</td>
                    <td class="border px-4 py-2">{{ $order->created_at->toDayDateTimeString() }}</td>
                </tr>
            @empty
                <tr><td colspan="3" class="text-center py-4">No orders in the last 7 days.</td></tr>
            @endforelse
        </tbody>
    </table>
    <h2 class="text-lg font-semibold mt-6 mb-2">Inventory</h2>
    <table class="min-w-full bg-white border border-gray-300 mb-6">
        <thead>
            <tr>
                <th class="px-4 py-2 border">Item Name</th>
                <th class="px-4 py-2 border">Quantity</th>
            </tr>
        </thead>
        <tbody>
            @forelse($suppliedItems as $item)
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
</div>
@endsection 