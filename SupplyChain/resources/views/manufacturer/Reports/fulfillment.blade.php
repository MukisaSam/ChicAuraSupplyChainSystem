@extends('manufacturer.layouts.dashboard')
@section('content')
<div class="container mx-auto p-6">
    <h2 class="text-2xl font-bold mb-4">Order Fulfillment Report</h2>
    <div class="mb-4 flex flex-wrap gap-4">
        <a href="#" class="px-4 py-2 bg-indigo-600 text-white rounded hover:bg-indigo-700 self-end opacity-50 cursor-not-allowed"><i class="fas fa-file-csv mr-2"></i>Export CSV (Coming Soon)</a>
    </div>
    <div class="overflow-x-auto">
        <table class="min-w-full bg-white dark:bg-gray-900 rounded shadow">
            <thead>
                <tr>
                    <th class="px-4 py-2">Order ID</th>
                    <th class="px-4 py-2">Date</th>
                    <th class="px-4 py-2">Status</th>
                    <th class="px-4 py-2">Items Count</th>
                </tr>
            </thead>
            <tbody>
                @foreach($orders as $order)
                <tr>
                    <td class="border px-4 py-2">{{ $order->id }}</td>
                    <td class="border px-4 py-2">{{ $order->created_at->format('Y-m-d') }}</td>
                    <td class="border px-4 py-2">{{ ucfirst($order->status) }}</td>
                    <td class="border px-4 py-2">{{ $order->orderItems->count() }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection 