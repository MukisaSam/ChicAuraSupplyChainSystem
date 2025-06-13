{{-- resources/views/admin/dashboard.blade.php --}}
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Administrator Dashboard - ChicAura SCM</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        body { background-color: #f8fafc; }
        .sidebar { transition: transform 0.3s ease-in-out; }
        @media (max-width: 768px) {
            .sidebar { transform: translateX(-100%); }
            .sidebar.open { transform: translateX(0); }
        }
    </style>
</head>
<body class="font-sans antialiased">
    <div class="flex h-screen bg-gray-100">
        <!-- Sidebar -->
        <aside id="sidebar" class="sidebar absolute md:relative z-20 flex-shrink-0 w-64 bg-white border-r md:block">
            <div class="flex flex-col h-full">
                <div class="flex items-center justify-center h-20 border-b">
                    <h1 class="text-2xl font-bold text-gray-800">ChicAura</h1>
                </div>
                <nav class="flex-1 px-4 py-4 space-y-2">
                    <a href="#" class="flex items-center px-4 py-2 text-white bg-indigo-600 rounded-md"><i class="fas fa-tachometer-alt w-6"></i><span class="ml-3">Dashboard</span></a>
                    <a href="#" class="flex items-center px-4 py-2 text-gray-600 hover:bg-gray-200 rounded-md"><i class="fas fa-users-cog w-6"></i><span class="ml-3">User Management</span></a>
                    <a href="#" class="flex items-center px-4 py-2 text-gray-600 hover:bg-gray-200 rounded-md"><i class="fas fa-shield-alt w-6"></i><span class="ml-3">Roles & Permissions</span></a>
                    <a href="#" class="flex items-center px-4 py-2 text-gray-600 hover:bg-gray-200 rounded-md"><i class="fas fa-cogs w-6"></i><span class="ml-3">System Settings</span></a>
                    <a href="#" class="flex items-center px-4 py-2 text-gray-600 hover:bg-gray-200 rounded-md"><i class="fas fa-chart-pie w-6"></i><span class="ml-3">Analytics</span></a>
                    <a href="#" class="flex items-center px-4 py-2 text-gray-600 hover:bg-gray-200 rounded-md"><i class="fas fa-history w-6"></i><span class="ml-3">Audit Logs</span></a>
                    <a href="#" class="flex items-center px-4 py-2 text-gray-600 hover:bg-gray-200 rounded-md"><i class="fas fa-bell w-6"></i><span class="ml-3">Notifications</span></a>
                </nav>
            </div>
        </aside>

        <div class="flex flex-col flex-1 w-full">
             <!-- Top Navigation Bar -->
            <header class="relative z-10 flex items-center justify-between h-20 bg-white border-b">
                <div class="flex items-center">
                    <button id="menu-toggle" class="md:hidden p-4 text-gray-500 hover:text-gray-700"><i class="fas fa-bars text-xl"></i></button>
                    <div class="relative ml-4 hidden md:block"><span class="absolute inset-y-0 left-0 flex items-center pl-3"><i class="fas fa-search text-gray-400"></i></span><input type="text" class="w-full py-2 pl-10 pr-4 border rounded-md" placeholder="Search users or logs..."></div>
                </div>
                <div class="flex items-center pr-4">
                    <button class="p-2 text-gray-500 hover:text-gray-700"><i class="fas fa-bell text-xl"></i></button>
                    <div class="relative ml-3"><button class="flex items-center focus:outline-none"><span class="mr-2">{{ $user->name ?? 'System Admin' }}</span><img class="w-8 h-8 rounded-full" src="https://via.placeholder.com/32" alt="User Avatar"></button></div>
                </div>
            </header>

            <!-- Main Content -->
            <main class="flex-1 p-6 md:p-8">
                <h2 class="text-3xl font-semibold text-gray-800">Administrator Dashboard</h2>
                <div class="grid grid-cols-1 gap-6 mt-6 sm:grid-cols-2 lg:grid-cols-4">
                    <div class="p-6 bg-white rounded-lg shadow-md"><div class="flex items-center"><div class="p-3 bg-blue-100 rounded-full"><i class="fas fa-users text-blue-600 text-xl"></i></div><div class="ml-4"><p class="text-sm font-medium text-gray-500">Total Users</p><p class="text-2xl font-bold text-gray-800">{{ $stats['total_users'] ?? '0' }}</p></div></div></div>
                    <div class="p-6 bg-white rounded-lg shadow-md"><div class="flex items-center"><div class="p-3 bg-indigo-100 rounded-full"><i class="fas fa-globe-americas text-indigo-600 text-xl"></i></div><div class="ml-4"><p class="text-sm font-medium text-gray-500">Active Sessions</p><p class="text-2xl font-bold text-gray-800">{{ $stats['active_sessions'] ?? '0' }}</p></div></div></div>
                    <div class="p-6 bg-white rounded-lg shadow-md"><div class="flex items-center"><div class="p-3 bg-green-100 rounded-full"><i class="fas fa-server text-green-600 text-xl"></i></div><div class="ml-4"><p class="text-sm font-medium text-gray-500">System Status</p><p class="text-2xl font-bold text-green-600">{{ $stats['system_status'] ?? 'N/A' }}</p></div></div></div>
                    <div class="p-6 bg-white rounded-lg shadow-md"><div class="flex items-center"><div class="p-3 bg-red-100 rounded-full"><i class="fas fa-ticket-alt text-red-600 text-xl"></i></div><div class="ml-4"><p class="text-sm font-medium text-gray-500">Pending Issues</p><p class="text-2xl font-bold text-gray-800">{{ $stats['pending_issues'] ?? '0' }}</p></div></div></div>
                </div>
                <div class="grid grid-cols-1 gap-6 mt-8 lg:grid-cols-3">
                    <div class="p-6 bg-white rounded-lg shadow-md lg:col-span-2"><h3 class="text-lg font-semibold text-gray-800">Recent System Activity</h3><div class="mt-4 space-y-4">
                        @forelse ($recentActivities as $activity)
                            <div class="flex items-start"><div class="flex-shrink-0 w-10 text-center"><i class="fas {{ $activity['icon'] }} {{ $activity['color'] }} text-lg"></i></div><div class="ml-4 flex-1"><p class="text-sm text-gray-800">{{ $activity['description'] }}</p><p class="text-xs text-gray-500">{{ $activity['time'] }}</p></div></div>
                        @empty <p class="text-gray-500">No recent activities found.</p> @endforelse
                    </div></div>
                    <div class="p-6 bg-white rounded-lg shadow-md"><h3 class="text-lg font-semibold text-gray-800">User Roles</h3><canvas id="userRolesChart"></canvas></div>
                </div>
            </main>
        </div>
    </div>
    <script>
        const menuToggle = document.getElementById('menu-toggle');
        const sidebar = document.getElementById('sidebar');
        menuToggle.addEventListener('click', () => sidebar.classList.toggle('open'));

        const ctx = document.getElementById('userRolesChart').getContext('2d');
        new Chart(ctx, {
            type: 'doughnut',
            data: {
                labels: ['Manufacturers', 'Suppliers', 'Wholesalers', 'Admins'],
                datasets: [{
                    data: [1, 25, 42, 2], // Corresponds to total users
                    backgroundColor: ['#4f46e5', '#10b981', '#f59e0b', '#6b7280'],
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'top',
                    }
                }
            }
        });
    </script>
</body>
</html>