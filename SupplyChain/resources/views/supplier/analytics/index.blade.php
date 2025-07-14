@extends('layouts.supplier-dashboard')

@section('content')
<div class="max-w-6xl mx-auto px-4 py-8">
    <h2 class="text-2xl font-bold mb-6 text-gray-900 dark:text-white">Supplier Analytics</h2>
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow p-6 flex flex-col items-center">
            <h5 class="text-sm font-semibold text-gray-500 dark:text-gray-300 mb-2">Total Supplied</h5>
            <p class="text-3xl font-bold text-green-600 dark:text-green-400">{{ $stats['total_supplied'] ?? 0 }}</p>
        </div>
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow p-6 flex flex-col items-center">
            <h5 class="text-sm font-semibold text-gray-500 dark:text-gray-300 mb-2">Average Rating</h5>
            <p class="text-3xl font-bold text-yellow-500 dark:text-yellow-400">{{ number_format($stats['average_rating'], 1) }}</p>
        </div>
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow p-6 flex flex-col items-center">
            <h5 class="text-sm font-semibold text-gray-500 dark:text-gray-300 mb-2">Active Requests</h5>
            <p class="text-3xl font-bold text-blue-600 dark:text-blue-400">{{ $stats['active_requests'] }}</p>
        </div>
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow p-6 flex flex-col items-center">
            <h5 class="text-sm font-semibold text-gray-500 dark:text-gray-300 mb-2">Total Revenue</h5>
            <p class="text-3xl font-bold text-purple-600 dark:text-purple-400">${{ number_format($stats['total_revenue'], 2) }}</p>
        </div>
    </div>
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow p-6">
        <h5 class="text-lg font-semibold mb-4 text-gray-900 dark:text-white">Supply Trends (Monthly)</h5>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                <thead class="bg-gray-50 dark:bg-gray-900">
                    <tr>
                        <th class="px-4 py-2 text-left text-xs font-semibold text-gray-700 dark:text-gray-200 uppercase">Month</th>
                        <th class="px-4 py-2 text-left text-xs font-semibold text-gray-700 dark:text-gray-200 uppercase">Total Supplied</th>
                    </tr>
                </thead>
                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                    @foreach($supplyTrends as $trend)
                    <tr>
                        <td class="px-4 py-2">{{ DateTime::createFromFormat('!m', $trend->month)->format('F') }}</td>
                        <td class="px-4 py-2">{{ $trend->total }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection 