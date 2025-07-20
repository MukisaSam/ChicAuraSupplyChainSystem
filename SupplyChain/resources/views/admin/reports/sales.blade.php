@extends('layouts.admin-dashboard')

@section('content')
<div class="p-6 bg-gray-100 min-h-screen">
    <h1 class="text-2xl font-bold mb-4">Sales Report (Last 7 Days)</h1>
    <p class="mb-2">Period: {{ $weekAgo->toDayDateTimeString() }} - {{ $now->toDayDateTimeString() }}</p>
    <p class="mb-4"><strong>Total Sales:</strong> ${{ number_format($totalSales, 2) }} | <strong>Number of Sales:</strong> {{ $salesCount }}</p>
    <table class="min-w-full bg-white border border-gray-300 mb-6">
        <thead>
            <tr>
                <th class="px-4 py-2 border">Order #</th>
                <th class="px-4 py-2 border">Customer</th>
                <th class="px-4 py-2 border">Total</th>
                <th class="px-4 py-2 border">Date</th>
            </tr>
        </thead>
        <tbody>
            @forelse($sales as $order)
                <tr>
                    <td class="border px-4 py-2">{{ $order->id }}</td>
                    <td class="border px-4 py-2">{{ $order->user->name ?? 'N/A' }}</td>
                    <td class="border px-4 py-2">${{ number_format($order->total_amount, 2) }}</td>
                    <td class="border px-4 py-2">{{ $order->created_at->toDayDateTimeString() }}</td>
                </tr>
            @empty
                <tr><td colspan="4" class="text-center py-4">No sales in the last 7 days.</td></tr>
            @endforelse
        </tbody>
    </table>
    <a href="{{ route('admin.reports.index') }}" class="text-blue-600 hover:underline">&larr; Back to Reports Center</a>
</div>
@endsection 