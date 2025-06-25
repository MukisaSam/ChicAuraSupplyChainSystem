@extends('manufacturer.layouts.dashboard')

@section('content')
<div class="mb-6">
    <div class="bg-white/80 rounded-xl shadow p-6 flex items-center gap-4">
        <div class="flex-shrink-0">
            <div class="bg-gradient-to-br from-indigo-500 to-indigo-700 p-3 rounded-full shadow">
                <i class="fas fa-dollar-sign text-white text-2xl"></i>
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
                <i class="fas fa-dollar-sign text-white text-2xl"></i>
            </div>
            <div class="ml-4">
                <p class="text-sm font-medium text-gray-600">Total Revenue</p>
                <p class="text-2xl font-bold text-gray-800">{{ $stats['revenue'] ?? '$0' }}</p>
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
        new Chart(ctx, {
            type: 'line',
            data: {
                labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'],
                datasets: [{
                    label: 'Monthly Revenue',
                    data: [30000, 35000, 45000, 40000, 50000, 55000],
                    borderColor: 'rgb(124, 58, 237)',
                    backgroundColor: 'rgba(124, 58, 237, 0.1)',
                    tension: 0.4,
                    fill: true
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: function(value) {
                                return '$' + value.toLocaleString();
                            }
                        }
                    }
                }
            }
        });
    }
</script>
@endsection