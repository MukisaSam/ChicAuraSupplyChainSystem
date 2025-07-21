@extends('layouts.admin-dashboard')
@section('content')
<div class="flex-1 p-4">
    <div class="mb-4">
        <h2 class="text-2xl font-bold text-black mb-1">Administrator Dashboard</h2>
        <p class="text-black text-sm">Welcome back! Here's your system overview.</p>
    </div>
    <div class="grid grid-cols-1 gap-4 mt-4 sm:grid-cols-2 lg:grid-cols-4">
        <div class="stat-card p-4 rounded-xl">
            <div class="flex items-center">
                <div class="p-3 bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl shadow-lg">
                    <i class="fas fa-users text-white text-xl"></i>
                </div>
                <div class="ml-3">
                    <p class="text-xs font-medium text-gray-600">Total Users</p>
                    <p class="text-2xl font-bold text-gray-800">{{ $stats['total_users'] ?? '0' }}</p>
                    <p class="text-xs text-green-600 mt-1">↗ +12% this month</p>
                </div>
            </div>
        </div>
        <div class="stat-card p-4 rounded-xl">
            <div class="flex items-center">
                <div class="p-3 bg-gradient-to-br from-indigo-500 to-indigo-600 rounded-xl shadow-lg">
                    <i class="fas fa-globe-americas text-white text-xl"></i>
                </div>
                <div class="ml-3">
                    <p class="text-xs font-medium text-gray-600">Active Sessions</p>
                    <p class="text-2xl font-bold text-gray-800">{{ $stats['active_sessions'] ?? '0' }}</p>
                    <p class="text-xs text-blue-600 mt-1">↗ +8% this week</p>
                </div>
            </div>
        </div>
        <div class="stat-card p-4 rounded-xl">
            <div class="flex items-center">
                <div class="p-3 bg-gradient-to-br from-green-500 to-green-600 rounded-xl shadow-lg">
                    <i class="fas fa-server text-white text-xl"></i>
                </div>
                <div class="ml-3">
                    <p class="text-xs font-medium text-gray-600">System Status</p>
                    <p class="text-2xl font-bold text-green-600">{{ $stats['system_status'] ?? 'N/A' }}</p>
                    <p class="text-xs text-green-600 mt-1">✓ All systems operational</p>
                </div>
            </div>
        </div>
        <div class="stat-card p-4 rounded-xl">
            <div class="flex items-center">
                <div class="p-3 bg-gradient-to-br from-red-500 to-red-600 rounded-xl shadow-lg">
                    <i class="fas fa-ticket-alt text-white text-xl"></i>
                </div>
                <div class="ml-3">
                    <p class="text-xs font-medium text-gray-600">Pending Issues</p>
                    <p class="text-2xl font-bold text-gray-800">{{ $stats['pending_issues'] ?? '0' }}</p>
                    <p class="text-xs text-red-600 mt-1">↘ -5% this week</p>
                </div>
            </div>
        </div>
    </div>
    <div class="grid grid-cols-1 gap-4 mt-4 lg:grid-cols-3 h-64">
        <div class="card-gradient p-4 rounded-xl lg:col-span-2 overflow-hidden">
            <h3 class="text-lg font-bold text-gray-800 mb-3">Recent System Activity</h3>
            <div class="space-y-2 h-48 overflow-y-auto">
                @forelse ($recentActivities as $activity)
                    <div class="flex items-start p-3 bg-white rounded-lg shadow-sm hover:shadow-md transition-shadow">
                        <div class="flex-shrink-0 w-10 h-10 flex items-center justify-center rounded-full {{ $activity['color'] }} bg-opacity-10">
                            <i class="fas {{ $activity['icon'] }} {{ $activity['color'] }} text-sm"></i>
                        </div>
                        <div class="ml-3 flex-1">
                            <p class="text-xs font-medium text-gray-800">{{ $activity['description'] }}</p>
                            <p class="text-xs text-gray-500 mt-1">{{ $activity['time'] }}</p>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-6">
                        <i class="fas fa-inbox text-gray-400 text-2xl mb-2"></i>
                        <p class="text-gray-500 text-sm">No recent activities found.</p>
                    </div>
                @endforelse
            </div>
        </div>
        <div class="card-gradient p-4 rounded-xl overflow-hidden">
            <h3 class="text-lg font-bold text-gray-800 mb-3">User Roles Distribution</h3>
            <canvas id="userRolesChart" class="w-full h-48"></canvas>
        </div>
    </div>
</div>
<script>
    // Mobile menu toggle
    document.getElementById('menu-toggle').addEventListener('click', function() {
        document.getElementById('sidebar').classList.toggle('open');
    });
    // User Roles Chart
    const userRolesCtx = document.getElementById('userRolesChart').getContext('2d');
    new Chart(userRolesCtx, {
        type: 'doughnut',
        data: {
            labels: ['Admins', 'Suppliers', 'Manufacturers', 'Wholesalers'],
            datasets: [{
                data: [{{ $stats['admin_count'] ?? 0 }}, {{ $stats['supplier_count'] ?? 0 }}, {{ $stats['manufacturer_count'] ?? 0 }}, {{ $stats['wholesaler_count'] ?? 0 }}],
                backgroundColor: [
                    '#3B82F6',
                    '#10B981',
                    '#F59E0B',
                    '#8B5CF6'
                ],
                borderWidth: 0,
                hoverOffset: 4
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'bottom',
                    labels: {
                        padding: 10,
                        usePointStyle: true,
                        font: {
                            size: 10
                        }
                    }
                }
            },
            cutout: '60%'
        }
    });
</script>
@endsection