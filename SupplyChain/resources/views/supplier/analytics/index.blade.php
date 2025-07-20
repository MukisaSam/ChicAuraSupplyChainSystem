@extends('layouts.supplier-dashboard')

@section('content')
<div class="max-w-6xl mx-auto px-4 py-8">
    <h2 class="text-2xl font-bold mb-6 text-gray-900 dark:text-black">Supplier Analytics</h2>
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
            <p class="text-3xl font-bold text-purple-600 dark:text-purple-400">UGX{{ number_format($stats['total_revenue'], 2) }}</p>
        </div>
    </div>
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow p-6 mb-8">
        <h5 class="text-lg font-semibold mb-4 text-gray-900 dark:text-white">Supply Trends (Monthly)</h5>
        <canvas id="supplyTrendsChart" height="120"></canvas>
        <script>
            const ctx = document.getElementById('supplyTrendsChart').getContext('2d');
            new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: @json($months),
                    datasets: [{
                        label: 'Total Supplied',
                        data: @json($totals),
                        backgroundColor: 'rgba(16, 185, 129, 0.7)',
                        borderRadius: 8,
                    }]
                },
                options: {
                    responsive: true,
                    plugins: { legend: { display: false } },
                    scales: {
                        y: { beginAtZero: true }
                    }
                }
            });
        </script>
    </div>
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow p-6 mb-8">
        <h5 class="text-lg font-semibold mb-4 text-gray-900 dark:text-white">Monthly Revenue</h5>
        <canvas id="revenueChart" height="120"></canvas>
        <script>
            const ctxRevenue = document.getElementById('revenueChart').getContext('2d');
            new Chart(ctxRevenue, {
                type: 'bar',
                data: {
                    labels: @json($revenueMonths),
                    datasets: [{
                        label: 'Revenue',
                        data: @json($revenues),
                        backgroundColor: 'rgba(139, 92, 246, 0.7)',
                        borderRadius: 8,
                    }]
                },
                options: {
                    responsive: true,
                    plugins: { legend: { display: false } },
                    scales: {
                        y: { beginAtZero: true }
                    }
                }
            });
        </script>
    </div>
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow p-6 mb-8">
        <h5 class="text-lg font-semibold mb-4 text-gray-900 dark:text-white">Average Quality Rating (Monthly)</h5>
        <canvas id="ratingChart" height="120"></canvas>
        <script>
            const ctxRating = document.getElementById('ratingChart').getContext('2d');
            new Chart(ctxRating, {
                type: 'line',
                data: {
                    labels: @json($ratingMonths),
                    datasets: [{
                        label: 'Avg. Rating',
                        data: @json($ratings),
                        backgroundColor: 'rgba(253, 224, 71, 0.4)',
                        borderColor: 'rgba(253, 224, 71, 1)',
                        borderWidth: 2,
                        tension: 0.3,
                        fill: true,
                        pointBackgroundColor: 'rgba(253, 224, 71, 1)',
                        pointRadius: 4,
                    }]
                },
                options: {
                    responsive: true,
                    plugins: { legend: { display: false } },
                    scales: {
                        y: { beginAtZero: true, max: 5 }
                    }
                }
            });
        </script>
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