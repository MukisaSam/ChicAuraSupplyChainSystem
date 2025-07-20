@extends('layouts.admin-dashboard')

@section('title', 'Analytics')

@section('content')
<div class="p-6">
    <h1 class="text-2xl font-bold mb-6">Admin Analytics</h1>

    <!-- Metrics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
        <div class="stat-card p-6 rounded-xl shadow text-center">
            <div class="text-gray-500 text-sm mb-2">Total Users</div>
            <div class="text-3xl font-bold">{{ $totalUsers ?? 0 }}</div>
        </div>
        <div class="stat-card p-6 rounded-xl shadow text-center">
            <div class="text-gray-500 text-sm mb-2">Total Orders</div>
            <div class="text-3xl font-bold">{{ $totalOrders ?? 0 }}</div>
        </div>
        <div class="stat-card p-6 rounded-xl shadow text-center">
            <div class="text-gray-500 text-sm mb-2">Revenue</div>
            <div class="text-3xl font-bold">UGX{{ number_format($totalRevenue ?? 0, 2) }}</div>
        </div>
        <div class="stat-card p-6 rounded-xl shadow text-center">
            <div class="text-gray-500 text-sm mb-2">Active Suppliers</div>
            <div class="text-3xl font-bold">{{ $activeSuppliers ?? 0 }}</div>
        </div>
    </div>

    <!-- Charts -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
        <div class="card-gradient p-6 rounded-xl shadow">
            <h2 class="text-lg font-semibold mb-4">Orders Over Time</h2>
            <canvas id="ordersChart" height="120"></canvas>
        </div>
        <div class="card-gradient p-6 rounded-xl shadow">
            <h2 class="text-lg font-semibold mb-4">User Registrations</h2>
            <canvas id="usersChart" height="120"></canvas>
        </div>
    </div>

    <!-- Recent Activity -->
    <div class="card-gradient p-6 rounded-xl shadow mb-8">
        <h2 class="text-lg font-semibold mb-4">Recent Orders</h2>
        <table class="w-full text-sm">
            <thead>
                <tr class="bg-gray-100">
                    <th class="p-2">Order #</th>
                    <th class="p-2">Customer</th>
                    <th class="p-2">Amount</th>
                    <th class="p-2">Status</th>
                    <th class="p-2">Date</th>
                </tr>
            </thead>
            <tbody>
                @forelse($recentOrders ?? [] as $order)
                <tr>
                    <td class="p-2">{{ $order->id }}</td>
                    <td class="p-2">{{ $order->customer->name ?? 'N/A' }}</td>
                    <td class="p-2">UGX{{ number_format($order->total, 2) }}</td>
                    <td class="p-2">{{ ucfirst($order->status) }}</td>
                    <td class="p-2">{{ $order->created_at->format('Y-m-d') }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="p-2 text-center text-gray-400">No recent orders.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- Chart.js CDN -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    // Orders Chart
    var ctxOrders = document.getElementById('ordersChart').getContext('2d');
    new Chart(ctxOrders, {
        type: 'line',
        data: {
            labels: {!! json_encode($ordersChartLabels ?? []) !!},
            datasets: [{
                label: 'Orders',
                data: {!! json_encode($ordersChartData ?? []) !!},
                backgroundColor: 'rgba(59, 130, 246, 0.2)',
                borderColor: 'rgba(59, 130, 246, 1)',
                borderWidth: 2,
                fill: true,
                tension: 0.4
            }]
        },
        options: { responsive: true, plugins: { legend: { display: false } } }
    });

    // Users Chart
    var ctxUsers = document.getElementById('usersChart').getContext('2d');
    new Chart(ctxUsers, {
        type: 'bar',
        data: {
            labels: {!! json_encode($usersChartLabels ?? []) !!},
            datasets: [{
                label: 'Users',
                data: {!! json_encode($usersChartData ?? []) !!},
                backgroundColor: 'rgba(16, 185, 129, 0.7)'
            }]
        },
        options: { responsive: true, plugins: { legend: { display: false } } }
    });
});
</script>
@endsection
