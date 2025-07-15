@extends('layouts.admin-dashboard')

@section('title', 'Admin Analytics')

@section('content')
<div class="flex-1 p-4">
    <div class="mb-4">
        <h2 class="text-2xl font-bold text-white mb-1">Analytics Dashboard</h2>
        <p class="text-gray-200 text-sm">Key metrics and trends for your supply chain operations.</p>
    </div>
    <div class="grid grid-cols-1 gap-4 mt-4 sm:grid-cols-2 lg:grid-cols-3">
        <div class="stat-card p-4 rounded-xl">
            <div class="flex items-center">
                <div class="p-3 bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl shadow-lg">
                    <i class="fas fa-boxes text-white text-xl"></i>
                </div>
                <div class="ml-3">
                    <p class="text-xs font-medium text-gray-600">Orders Processed</p>
                    <p class="text-2xl font-bold text-gray-800">{{ $ordersCount }}</p>
                </div>
            </div>
        </div>
        <div class="stat-card p-4 rounded-xl">
            <div class="flex items-center">
                <div class="p-3 bg-gradient-to-br from-green-500 to-green-600 rounded-xl shadow-lg">
                    <i class="fas fa-users text-white text-xl"></i>
                </div>
                <div class="ml-3">
                    <p class="text-xs font-medium text-gray-600">Vendors Registered</p>
                    <p class="text-2xl font-bold text-gray-800">{{ $vendorsCount }}</p>
                </div>
            </div>
        </div>
        <div class="stat-card p-4 rounded-xl">
            <div class="flex items-center">
                <div class="p-3 bg-gradient-to-br from-yellow-500 to-yellow-600 rounded-xl shadow-lg">
                    <i class="fas fa-dollar-sign text-white text-xl"></i>
                </div>
                <div class="ml-3">
                    <p class="text-xs font-medium text-gray-600">Revenue (This Month)</p>
                    <p class="text-2xl font-bold text-gray-800">${{ number_format($monthlyRevenue, 2) }}</p>
                </div>
            </div>
        </div>
    </div>
    <div class="grid grid-cols-1 gap-4 mt-4 lg:grid-cols-3">
        <div class="card-gradient p-4 rounded-xl lg:col-span-3 overflow-hidden">
            <h3 class="text-lg font-bold text-gray-800 mb-3">Recent Orders Overview</h3>
            <canvas id="ordersChart" class="w-full h-64"></canvas>
        </div>
    </div>
</div>
@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
const ctx = document.getElementById('ordersChart').getContext('2d');
new Chart(ctx, {
    type: 'line',
    data: {!! json_encode($chartData) !!},
    options: {
        responsive: true,
        plugins: {
            legend: {
                display: true,
                position: 'bottom',
                labels: {
                    usePointStyle: true,
                    font: { size: 12 }
                }
            }
        },
        scales: {
            x: { title: { display: true, text: 'Date' } },
            y: { title: { display: true, text: 'Orders' }, beginAtZero: true }
        }
    }
});
</script>
@endpush
@endsection
