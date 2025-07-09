@extends('manufacturer.layouts.dashboard')
@section('content')
<div class="container mx-auto py-6">
    <h1 class="text-2xl font-bold mb-4">Production Work Orders</h1>
    <a href="{{ route('manufacturer.production.create') }}" class="bg-indigo-600 text-white px-4 py-2 rounded mb-4 inline-block">Create New Work Order</a>
    <table class="min-w-full table-auto bg-white rounded shadow">
        <thead>
            <tr>
                <th class="px-4 py-2">#</th>
                <th class="px-4 py-2">Product</th>
                <th class="px-4 py-2">Quantity</th>
                <th class="px-4 py-2">Status</th>
                <th class="px-4 py-2">Scheduled</th>
                <th class="px-4 py-2">Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($workOrders as $order)
            <tr>
                <td class="border px-4 py-2">{{ $order->id }}</td>
                <td class="border px-4 py-2">{{ $order->product->name ?? '-' }}</td>
                <td class="border px-4 py-2">{{ $order->quantity }}</td>
                <td class="border px-4 py-2">{{ $order->status }}</td>
                <td class="border px-4 py-2">{{ $order->scheduled_start ? \Carbon\Carbon::parse($order->scheduled_start)->format('Y-m-d H:i') : '-' }}</td>
                <td class="border px-4 py-2">
                    <a href="{{ route('manufacturer.production.show', $order->id) }}" class="text-blue-600 hover:underline">View</a>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="6" class="text-center text-gray-400 py-4">No work orders found.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
    <div class="mt-4">{{ $workOrders->links() }}</div>
</div>
@endsection 