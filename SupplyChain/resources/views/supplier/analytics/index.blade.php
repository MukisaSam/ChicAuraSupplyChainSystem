@extends('layouts.supplier-dashboard')

@section('content')
<div class="max-w-6xl mx-auto px-4 py-8">
    <h2 class="text-2xl font-bold mb-6 text-gray-900">Supplier Analytics</h2>
    
    <!-- Stats Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <div class="bg-white rounded-xl shadow p-6 flex flex-col items-center">
            <h5 class="text-sm font-semibold text-gray-500 mb-2">Total Supplied</h5>
            <p class="text-3xl font-bold text-green-600">{{ number_format($stats['total_supplied']) }}</p>
            <p class="text-xs text-gray-500 mt-1">items delivered</p>
        </div>
        
        <div class="bg-white rounded-xl shadow p-6 flex flex-col items-center">
            <h5 class="text-sm font-semibold text-gray-500 mb-2">Average Rating</h5>
            <p class="text-3xl font-bold text-yellow-500">{{ number_format($stats['average_rating'], 1) }}</p>
            <div class="flex mt-1">
                @for($i = 1; $i <= 5; $i++)
                    <svg class="w-4 h-4 {{ $i <= round($stats['average_rating']) ? 'text-yellow-400' : 'text-gray-300' }}" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                    </svg>
                @endfor
            </div>
        </div>
        
        <div class="bg-white rounded-xl shadow p-6 flex flex-col items-center">
            <h5 class="text-sm font-semibold text-gray-500 mb-2">Active Requests</h5>
            <p class="text-3xl font-bold text-blue-600">{{ $stats['active_requests'] }}</p>
            <p class="text-xs text-gray-500 mt-1">pending orders</p>
        </div>
        
        <div class="bg-white rounded-xl shadow p-6 flex flex-col items-center">
            <h5 class="text-sm font-semibold text-gray-500 mb-2">Total Revenue</h5>
            <p class="text-3xl font-bold text-purple-600">UGX{{ number_format($stats['total_revenue'], 2) }}</p>
            <p class="text-xs text-gray-500 mt-1">last 12 months</p>
        </div>
    </div>

    <!-- Charts Section -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
        <!-- Supply Trends Chart -->
        <div class="bg-white rounded-xl shadow p-6">
            <h5 class="text-lg font-semibold mb-4">Supply Trends</h5>
            <canvas id="supplyTrendsChart" height="250"></canvas>
        </div>
        
        <!-- Revenue Chart -->
        <div class="bg-white rounded-xl shadow p-6">
            <h5 class="text-lg font-semibold mb-4">Monthly Revenue</h5>
            <canvas id="revenueChart" height="250"></canvas>
        </div>
        
        <!-- Ratings Chart -->
        <div class="bg-white rounded-xl shadow p-6">
            <h5 class="text-lg font-semibold mb-4">Quality Ratings</h5>
            <canvas id="ratingChart" height="250"></canvas>
        </div>
        
        <!-- Top Items Chart -->
        <div class="bg-white rounded-xl shadow p-6">
            <h5 class="text-lg font-semibold mb-4">Top Items by Volume</h5>
            <canvas id="topItemsChart" height="250"></canvas>
        </div>
    </div>

    <!-- Data Tables -->
    <div class="grid grid-cols-1 gap-6">
        <!-- Monthly Data Table -->
        <div class="bg-white rounded-xl shadow overflow-hidden">
            <div class="p-6">
                <h5 class="text-lg font-semibold mb-4">Monthly Performance</h5>
                <div class="overflow-x-auto">
                    <table class="min-w-full">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-4 py-2 text-left text-xs font-semibold text-gray-700 uppercase">Month</th>
                                <th class="px-4 py-2 text-left text-xs font-semibold text-gray-700 uppercase">Quantity</th>
                                <th class="px-4 py-2 text-left text-xs font-semibold text-gray-700 uppercase">Revenue</th>
                                <th class="px-4 py-2 text-left text-xs font-semibold text-gray-700 uppercase">Avg. Rating</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @foreach($mergedData as $data)
                            <tr class="hover:bg-gray-50">
                                <td class="px-4 py-2">{{ $data['month_name'] }} {{ $data['year'] }}</td>
                                <td class="px-4 py-2">{{ number_format($data['total']) }}</td>
                                <td class="px-4 py-2">UGX{{ number_format($data['revenue'], 2) }}</td>
                                <td class="px-4 py-2">
                                    <div class="flex items-center">
                                        <span class="mr-2">{{ $data['avg_rating'] }}</span>
                                        <div class="flex">
                                            @for($i = 1; $i <= 5; $i++)
                                                <svg class="w-3 h-3 {{ $i <= $data['avg_rating'] ? 'text-yellow-400' : 'text-gray-300' }}" fill="currentColor" viewBox="0 0 20 20">
                                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                                                </svg>
                                            @endfor
                                        </div>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Chart.js Scripts -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Supply Trends Chart
    new Chart(document.getElementById('supplyTrendsChart'), {
        type: 'bar',
        data: {
            labels: @json($chartData['months']),
            datasets: [{
                label: 'Quantity Supplied',
                data: @json($chartData['totals']),
                backgroundColor: 'rgba(16, 185, 129, 0.7)',
                borderRadius: 6
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: { display: false },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            return context.parsed.y.toLocaleString() + ' items';
                        }
                    }
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        callback: function(value) {
                            return value.toLocaleString();
                        }
                    }
                }
            }
        }
    });

    // Revenue Chart
    new Chart(document.getElementById('revenueChart'), {
        type: 'line',
        data: {
            labels: @json($chartData['months']),
            datasets: [{
                label: 'Revenue',
                data: @json($chartData['revenues']),
                borderColor: 'rgba(139, 92, 246, 1)',
                backgroundColor: 'rgba(139, 92, 246, 0.1)',
                borderWidth: 2,
                tension: 0.3,
                fill: true
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: { display: false },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            return 'UGX' + context.parsed.y.toLocaleString(undefined, {minimumFractionDigits: 2});
                        }
                    }
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        callback: function(value) {
                            return 'UGX' + value.toLocaleString();
                        }
                    }
                }
            }
        }
    });

    // Ratings Chart
    new Chart(document.getElementById('ratingChart'), {
        type: 'line',
        data: {
            labels: @json($chartData['months']),
            datasets: [{
                label: 'Avg. Rating',
                data: @json($chartData['ratings']),
                borderColor: 'rgba(253, 224, 71, 1)',
                backgroundColor: 'rgba(253, 224, 71, 0.2)',
                borderWidth: 2,
                tension: 0.3,
                pointBackgroundColor: 'rgba(253, 224, 71, 1)',
                pointRadius: 4
            }]
        },
        options: {
            responsive: true,
            plugins: { legend: { display: false } },
            scales: {
                y: {
                    beginAtZero: true,
                    max: 5,
                    ticks: {
                        stepSize: 1
                    }
                }
            }
        }
    });

    // Top Items Chart (if data exists)
    @if(isset($topItems) && $topItems->count() > 0)
    new Chart(document.getElementById('topItemsChart'), {
        type: 'doughnut',
        data: {
            labels: @json($topItems->pluck('item.name')),
            datasets: [{
                data: @json($topItems->pluck('total_quantity')),
                backgroundColor: [
                    'rgba(16, 185, 129, 0.7)',
                    'rgba(59, 130, 246, 0.7)',
                    'rgba(245, 158, 11, 0.7)',
                    'rgba(139, 92, 246, 0.7)',
                    'rgba(239, 68, 68, 0.7)'
                ],
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: { position: 'right' },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            return context.label + ': ' + context.raw.toLocaleString() + ' items';
                        }
                    }
                }
            }
        }
    });
    @endif
});
</script>
@endsection