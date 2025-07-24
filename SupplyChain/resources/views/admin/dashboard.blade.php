@extends('layouts.admin-dashboard')
@section('content')
<div class="flex-1 p-4">
    <div class="mb-4">
        <h2 class="text-2xl font-bold text-black mb-1">Administrator Dashboard</h2>
        <p class="text-black text-sm">Welcome back! Here's your system overview.</p>
    </div>
    <div class="grid grid-cols-1 gap-6 mt-6 sm:grid-cols-2 lg:grid-cols-4">
        <div class="stat-card p-6 rounded-2xl shadow-lg bg-white flex items-center">
            <div class="p-4 bg-blue-500 rounded-xl shadow-md">
                <i class="fas fa-users text-white text-2xl"></i>
            </div>
            <div class="ml-4">
                <p class="text-sm font-medium text-gray-500">Total Users</p>
                <p class="text-3xl font-bold text-gray-900">{{ $stats['total_users'] ?? '0' }}</p>
            </div>
        </div>
        <div class="stat-card p-6 rounded-2xl shadow-lg bg-white flex items-center">
            <div class="p-4 bg-indigo-500 rounded-xl shadow-md">
                <i class="fas fa-user-shield text-white text-2xl"></i>
            </div>
            <div class="ml-4">
                <p class="text-sm font-medium text-gray-500">Admins</p>
                <p class="text-3xl font-bold text-gray-900">{{ $stats['admin_count'] ?? '0' }}</p>
            </div>
        </div>
        <div class="stat-card p-6 rounded-2xl shadow-lg bg-white flex items-center">
            <div class="p-4 bg-green-500 rounded-xl shadow-md">
                <i class="fas fa-truck text-white text-2xl"></i>
            </div>
            <div class="ml-4">
                <p class="text-sm font-medium text-gray-500">Suppliers</p>
                <p class="text-3xl font-bold text-gray-900">{{ $stats['supplier_count'] ?? '0' }}</p>
            </div>
        </div>
        <div class="stat-card p-6 rounded-2xl shadow-lg bg-white flex items-center">
            <div class="p-4 bg-yellow-500 rounded-xl shadow-md">
                <i class="fas fa-industry text-white text-2xl"></i>
            </div>
            <div class="ml-4">
                <p class="text-sm font-medium text-gray-500">Manufacturers</p>
                <p class="text-3xl font-bold text-gray-900">{{ $stats['manufacturer_count'] ?? '0' }}</p>
            </div>
        </div>
        <div class="stat-card p-6 rounded-2xl shadow-lg bg-white flex items-center">
            <div class="p-4 bg-purple-500 rounded-xl shadow-md">
                <i class="fas fa-store text-white text-2xl"></i>
            </div>
            <div class="ml-4">
                <p class="text-sm font-medium text-gray-500">Wholesalers</p>
                <p class="text-3xl font-bold text-gray-900">{{ $stats['wholesaler_count'] ?? '0' }}</p>
            </div>
        </div>
        <div class="stat-card p-6 rounded-2xl shadow-lg bg-white flex items-center">
            <div class="p-4 bg-gray-700 rounded-xl shadow-md">
                <i class="fas fa-globe-americas text-white text-2xl"></i>
            </div>
            <div class="ml-4">
                <p class="text-sm font-medium text-gray-500">Active Sessions</p>
                <p class="text-3xl font-bold text-gray-900">{{ $stats['active_sessions'] ?? '0' }}</p>
            </div>
        </div>
        <div class="stat-card p-6 rounded-2xl shadow-lg bg-white flex items-center min-w-[260px] max-w-full">
            <div class="p-4 bg-green-600 rounded-xl shadow-md">
                <i class="fas fa-server text-white text-2xl"></i>
            </div>
            <div class="ml-5 flex flex-col items-start">
                <p class="text-sm font-medium text-gray-500">System Status</p>
                <span class="inline-block px-4 py-1 mt-1 text-lg font-bold rounded-full bg-green-100 text-green-700 tracking-wide whitespace-nowrap">
                    {{ $stats['system_status'] ?? 'N/A' }}
                </span>
            </div>
        </div>
    </div>
    <div class="mt-8">
        <div class="bg-white rounded-2xl shadow-lg p-6 flex flex-col w-full">
            <h3 class="text-xl font-bold text-gray-800 mb-4">Recent System Activity</h3>
            <div class="space-y-3 h-56 overflow-y-auto">
                @forelse ($recentActivities as $activity)
                    <div class="flex items-start p-4 bg-gray-50 rounded-lg shadow-sm hover:shadow transition-shadow">
                        <div class="flex-shrink-0 w-12 h-12 flex items-center justify-center rounded-full {{ $activity['color'] }} bg-opacity-10">
                            <i class="fas {{ $activity['icon'] }} {{ $activity['color'] }} text-lg"></i>
                        </div>
                        <div class="ml-4 flex-1">
                            <p class="text-sm font-semibold text-gray-800">{{ $activity['description'] }}</p>
                            <p class="text-xs text-gray-500 mt-1">{{ $activity['time'] }}</p>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-8">
                        <i class="fas fa-inbox text-gray-400 text-3xl mb-2"></i>
                        <p class="text-gray-500 text-base">No recent activities found.</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</div>
<script>
    // Mobile menu toggle
    document.getElementById('menu-toggle').addEventListener('click', function() {
        document.getElementById('sidebar').classList.toggle('open');
    });
</script>
@endsection
