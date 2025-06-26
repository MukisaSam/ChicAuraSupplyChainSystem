{{-- resources/views/admin/dashboard.blade.php --}}
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Administrator Dashboard - ChicAura SCM</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="{{ asset('js/theme-switcher.js') }}"></script>
    <style>
        body { 
            background: linear-gradient(135deg, rgba(0,0,0,0.8) 0%, rgba(0,0,0,0.6) 100%), url('{{ asset('images/black.jpeg') }}');
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
            min-height: 100vh;
        }
        
        /* Dark mode styles */
        .dark body {
            background: linear-gradient(135deg, rgba(0,0,0,0.9) 0%, rgba(0,0,0,0.8) 100%), url('{{ asset('images/black.jpeg') }}');
        }
        
        .sidebar { 
            transition: transform 0.3s ease-in-out;
            background: linear-gradient(180deg, #1e293b 0%, #334155 100%);
            box-shadow: 4px 0 15px rgba(0,0,0,0.1);
        }
        
        .dark .sidebar {
            background: linear-gradient(180deg, #0f172a 0%, #1e293b 100%);
        }
        
        .logo-container {
            background: rgba(255, 255, 255, 0.95);
            border-radius: 12px;
            padding: 8px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        }
        
        .dark .logo-container {
            background: rgba(255, 255, 255, 0.9);
        }
        
        .card-gradient {
            background: linear-gradient(135deg, #ffffff 0%, #f8fafc 100%);
            border: 1px solid rgba(255,255,255,0.2);
            box-shadow: 0 8px 32px rgba(0,0,0,0.1);
            backdrop-filter: blur(10px);
        }
        
        .dark .card-gradient {
            background: linear-gradient(135deg, #1e293b 0%, #334155 100%);
            border: 1px solid rgba(255,255,255,0.1);
            color: #f1f5f9;
        }
        
        .stat-card {
            background: linear-gradient(135deg, #ffffff 0%, #f1f5f9 100%);
            border: 1px solid rgba(255,255,255,0.3);
            box-shadow: 0 4px 20px rgba(0,0,0,0.08);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        
        .dark .stat-card {
            background: linear-gradient(135deg, #1e293b 0%, #334155 100%);
            border: 1px solid rgba(255,255,255,0.1);
            color: #f1f5f9;
        }
        
        .dark .stat-card p {
            color: #f1f5f9;
        }
        
        .dark .stat-card .text-gray-600 {
            color: #cbd5e1;
        }
        
        .dark .stat-card .text-gray-800 {
            color: #f1f5f9;
        }
        
        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 25px rgba(0,0,0,0.15);
        }
        
        .nav-link {
            transition: all 0.3s ease;
            border-radius: 12px;
            margin: 4px 0;
        }
        .nav-link:hover {
            transform: translateX(5px);
        }
        .header-gradient {
            background: linear-gradient(135deg, #ffffff 0%, #f8fafc 100%);
            box-shadow: 0 2px 20px rgba(0,0,0,0.1);
        }
        
        .dark .header-gradient {
            background: linear-gradient(135deg, #1e293b 0%, #334155 100%);
            border-color: #475569;
        }
        
        .dark .text-white {
            color: #f1f5f9;
        }
        
        .dark .text-gray-200 {
            color: #cbd5e1;
        }
        
        @media (max-width: 768px) {
            .sidebar { transform: translateX(-100%); }
            .sidebar.open { transform: translateX(0); }
        }
    </style>
</head>
<body class="font-sans antialiased">
    <div class="flex h-screen">
        <!-- Sidebar -->
        <aside id="sidebar" class="sidebar absolute md:relative z-20 flex-shrink-0 w-64 md:block">
            <div class="flex flex-col h-full">
                <div class="flex items-center justify-center h-16 border-b border-gray-600">
                    <div class="logo-container">
                        <img src="{{ asset('images/logo.png') }}" alt="ChicAura Logo" class="h-12 w-auto">
                    </div>
                </div>
                <div class="px-4 py-4">
                    <h3 class="text-white text-sm font-semibold mb-3 px-3">ADMINISTRATION</h3>
                </div>
                <nav class="flex-1 px-4 py-2 space-y-1">
                    <a href="{{route('admin.dashboard')}}" class="nav-link flex items-center px-3 py-2 text-white bg-gradient-to-r from-blue-600 to-blue-700 rounded-xl shadow-lg"><i class="fas fa-tachometer-alt w-5"></i><span class="ml-2 font-medium text-sm">Dashboard</span></a>
                    <a href="#" class="nav-link flex items-center px-3 py-2 text-gray-300 hover:bg-gray-700 hover:text-white rounded-xl"><i class="fas fa-shield-alt w-5"></i><span class="ml-2 text-sm">Roles & Permissions</span></a>
                    <a href="#" class="nav-link flex items-center px-3 py-2 text-gray-300 hover:bg-gray-700 hover:text-white rounded-xl"><i class="fas fa-cogs w-5"></i><span class="ml-2 text-sm">System Settings</span></a>
                    <a href="#" class="nav-link flex items-center px-3 py-2 text-gray-300 hover:bg-gray-700 hover:text-white rounded-xl"><i class="fas fa-chart-pie w-5"></i><span class="ml-2 text-sm">Analytics</span></a>
                    <a href="#" class="nav-link flex items-center px-3 py-2 text-gray-300 hover:bg-gray-700 hover:text-white rounded-xl"><i class="fas fa-history w-5"></i><span class="ml-2 text-sm">Audit Logs</span></a>
                    <a href="#" class="nav-link flex items-center px-3 py-2 text-gray-300 hover:bg-gray-700 hover:text-white rounded-xl"><i class="fas fa-bell w-5"></i><span class="ml-2 text-sm">Notifications</span></a>
                    <a href="{{route('admin.users')}}" class="nav-link flex items-center px-3 py-2 text-gray-300 hover:bg-gray-700 hover:text-white rounded-xl"><i class="fa-solid fa-user"></i><span class="ml-2 text-sm">Users Management</span></a>
                </nav>
                <div class="p-3 border-t border-gray-600">
                    <div class="text-center text-gray-400 text-xs">
                        <p>ChicAura SCM</p>
                        <p class="text-xs mt-1">v2.1.0</p>
                    </div>
                </div>
            </div>
        </aside>

        <div class="flex flex-col flex-1 w-full">
             <!-- Top Navigation Bar -->
            <header class="header-gradient relative z-10 flex items-center justify-between h-16 border-b">
                <div class="flex items-center">
                    <button id="menu-toggle" class="md:hidden p-3 text-gray-500 hover:text-gray-700"><i class="fas fa-bars text-lg"></i></button>
                    <div class="relative ml-3 hidden md:block">
                        <span class="absolute inset-y-0 left-0 flex items-center pl-3"><i class="fas fa-search text-gray-400"></i></span>
                        <input type="text" class="w-80 py-2 pl-10 pr-4 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent text-sm" placeholder="Search users, logs, or settings...">
                    </div>
                </div>
                <div class="flex items-center pr-4 space-x-3">
                    <button class="p-2 text-gray-500 hover:text-blue-600 hover:bg-blue-50 rounded-full transition-colors"><i class="fas fa-bell text-lg"></i></button>
                    <button data-theme-toggle class="p-2 text-gray-500 hover:text-blue-600 hover:bg-blue-50 rounded-full transition-colors" title="Switch Theme">
                        <i class="fas fa-moon text-lg"></i>
                    </button>
                    <div class="relative">
                        <button class="flex items-center focus:outline-none bg-white rounded-full p-2 shadow-md hover:shadow-lg transition-shadow">
                            <span class="mr-2 text-gray-700 font-medium text-sm">{{ Auth::user()->name ?? 'System Admin' }}</span>
                            <img class="w-7 h-7 rounded-full border-2 border-blue-200 object-cover" 
                                 src="{{ Auth::user()->profile_picture ? Storage::disk('public')->url(Auth::user()->profile_picture) : asset('images/default-avatar.svg') }}" 
                                 alt="User Avatar">
                        </button>
                    </div>
                    <div class="flex items-center space-x-2">
                        <button type="button" class="p-2 text-gray-500 hover:text-blue-600 hover:bg-blue-50 rounded-full transition-colors" title="Edit Profile" x-data x-on:click="$dispatch('open-modal', 'profile-editor-modal')">
                            <i class="fas fa-user-edit text-lg"></i>
                        </button>
                        <form method="POST" action="{{ route('logout') }}" class="inline">
                            @csrf
                            <button type="submit" class="p-2 text-gray-500 hover:text-red-600 hover:bg-red-50 rounded-full transition-colors" title="Logout">
                                <i class="fas fa-sign-out-alt text-lg"></i>
                            </button>
                        </form>
                    </div>
                </div>
            </header>

            <!-- Main Content -->
            <main class=" flex-1 overflow-y-auto p-6">
                <div class="mb-4">
                    <h2 class="text-2xl font-bold text-white mb-1">Administrator Dashboard</h2>
                    <p class="text-gray-200 text-sm">Welcome back! Here's your system overview.</p>
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
            </main>
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
                    data: [{{ $stats['admin_count'] ?? 5 }}, {{ $stats['supplier_count'] ?? 25 }}, {{ $stats['manufacturer_count'] ?? 15 }}, {{ $stats['wholesaler_count'] ?? 20 }}],
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

    {{-- Profile Editor Modal --}}
    <x-profile-editor-modal />
</body>
</html> 