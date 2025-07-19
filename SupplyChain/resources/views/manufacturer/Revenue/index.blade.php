@extends('manufacturer.layouts.dashboard')

@section('content')
<div class="mb-6">
    <div class="bg-white/80 rounded-xl shadow p-6 flex items-center gap-4">
        <div class="flex-shrink-0">
            <div class="bg-gradient-to-br from-indigo-500 to-indigo-700 p-3 rounded-full shadow">
                <i class="fas fa-coins text-yellow-500 text-2xl"></i>
            </div>
        </div>
        <div>
            <h2 class="text-2xl font-bold text-gray-800 mb-1">Revenue Overview</h2>
            <p class="text-gray-500 text-sm">Track your financial performance and revenue metrics</p>
        </div>
    </div>
</div>

<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
    <div class="stat-card p-6 rounded-xl bg-white/90">
        <div class="flex items-center">
            <div class="p-4 bg-gradient-to-br from-purple-500 to-purple-600 rounded-xl shadow-lg">
                <i class="fas fa-coins text-white text-2xl"></i>
            </div>
            <div class="ml-4">
                <p class="text-sm font-medium text-gray-600">Total Revenue</p>
                <p class="text-2xl font-bold text-gray-800">UGX {{ number_format($stats['revenue'] ?? 0, 2) }}</p>
            </div>
        </div>
    </div>
    <div class="stat-card p-6 rounded-xl bg-white/90">
        <div class="flex items-center">
            <div class="p-4 bg-gradient-to-br from-green-500 to-green-600 rounded-xl shadow-lg">
                <i class="fas fa-shopping-cart text-white text-2xl"></i>
            </div>
            <div class="ml-4">
                <p class="text-sm font-medium text-gray-600">Total Products</p>
                <p class="text-2xl font-bold text-gray-800">{{ $stats['products'] ?? '0' }}</p>
            </div>
        </div>
    </div>
    <div class="stat-card p-6 rounded-xl bg-white/90">
        <div class="flex items-center">
            <div class="p-4 bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl shadow-lg">
                <i class="fas fa-boxes text-white text-2xl"></i>
            </div>
            <div class="ml-4">
                <p class="text-sm font-medium text-gray-600">Raw Materials</p>
                <p class="text-2xl font-bold text-gray-800">{{ $stats['raw_materials'] ?? '0' }}</p>
            </div>
        </div>
    </div>
    <div class="stat-card p-6 rounded-xl bg-white/90">
        <div class="flex items-center">
            <div class="p-4 bg-gradient-to-br from-yellow-500 to-yellow-600 rounded-xl shadow-lg">
                <i class="fas fa-truck text-white text-2xl"></i>
            </div>
            <div class="ml-4">
                <p class="text-sm font-medium text-gray-600">Active Suppliers</p>
                <p class="text-2xl font-bold text-gray-800">{{ $stats['suppliers'] ?? '0' }}</p>
            </div>
        </div>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <div class="card-gradient p-6 rounded-xl bg-white/90 lg:col-span-2">
        <h3 class="text-lg font-bold text-gray-800 mb-4">Revenue Trend</h3>
        <div class="h-80">
            <canvas id="revenueChart"></canvas>
        </div>
    </div>
    <div class="card-gradient p-6 rounded-xl bg-white/90">
        <h3 class="text-lg font-bold text-gray-800 mb-4">Recent Activities</h3>
        <div class="space-y-4 h-80 overflow-y-auto">
            @forelse ($recentActivities ?? [] as $activity)
                <div class="flex items-start p-4 bg-white rounded-lg shadow-sm">
                    <div class="flex-shrink-0 w-10 h-10 flex items-center justify-center rounded-full {{ $activity['color'] ?? 'bg-blue-100' }} bg-opacity-10">
                        <i class="fas {{ $activity['icon'] ?? 'fa-info' }} {{ $activity['color'] ?? 'text-blue-600' }}"></i>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-medium text-gray-800">{{ $activity['description'] }}</p>
                        <p class="text-xs text-gray-500 mt-1">{{ $activity['time'] }}</p>
                    </div>
                </div>
            @empty
                <div class="text-center py-6">
                    <i class="fas fa-inbox text-gray-400 text-2xl mb-2"></i>
                    <p class="text-gray-500">No recent activities</p>
                </div>
            @endforelse
        </div>
    </div>
</div>

<script>
    // Revenue Chart
    const ctx = document.getElementById('revenueChart');
    if (ctx) {
        fetch("{{ route('manufacturer.revenue.chart-data') }}")
            .then(response => response.json())
            .then(data => {
                // Create a gradient fill for the chart
                const chartCtx = ctx.getContext('2d');
                const gradient = chartCtx.createLinearGradient(0, 0, 0, ctx.height);
                gradient.addColorStop(0, 'rgba(124, 58, 237, 0.4)');
                gradient.addColorStop(1, 'rgba(124, 58, 237, 0.05)');

                new Chart(ctx, {
                    type: 'line',
                    data: {
                        labels: data.labels,
                        datasets: [{
                            label: 'Monthly Revenue',
                            data: data.data,
                            borderColor: 'rgb(124, 58, 237)',
                            backgroundColor: gradient,
                            tension: 0.4,
                            fill: true,
                            pointBackgroundColor: 'rgb(124, 58, 237)',
                            pointRadius: 5,
                            pointHoverRadius: 8,
                            pointBorderWidth: 2,
                            pointBorderColor: '#fff',
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                display: true,
                                labels: {
                                    color: '#7c3aed',
                                    font: { weight: 'bold' }
                                }
                            },
                            tooltip: {
                                enabled: true,
                                callbacks: {
                                    label: function(context) {
                                        return 'Revenue: UGX ' + context.parsed.y.toLocaleString();
                                    }
                                },
                                backgroundColor: 'rgba(124, 58, 237, 0.9)',
                                titleColor: '#fff',
                                bodyColor: '#fff',
                                borderColor: '#fff',
                                borderWidth: 1,
                                padding: 12,
                                cornerRadius: 8,
                            }
                        },
                        scales: {
                            y: {
                                beginAtZero: true,
                                ticks: {
                                    callback: function(value) {
                                        return 'UGX ' + value.toLocaleString();
                                    },
                                    color: '#7c3aed',
                                    font: { weight: 'bold' }
                                },
                                grid: {
                                    color: 'rgba(124, 58, 237, 0.08)'
                                }
                            },
                            x: {
                                ticks: {
                                    color: '#7c3aed',
                                    font: { weight: 'bold' }
                                },
                                grid: {
                                    color: 'rgba(124, 58, 237, 0.08)'
                                }
                            }
                        }
                    }
                });
            });
    }
</script>
@endsection