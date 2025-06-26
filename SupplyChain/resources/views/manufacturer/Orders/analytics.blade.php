<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Analytics - Manufacturer Portal - ChicAura SCM</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="{{ asset('js/theme-switcher.js') }}"></script>
    <style>
        body { 
            background: linear-gradient(135deg, rgba(0,0,0,0.7) 0%, rgba(0,0,0,0.5) 100%), url('{{ asset('images/manufacturer.png') }}');
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
            min-height: 100vh;
        }
        
        .dark body {
            background: linear-gradient(135deg, rgba(0,0,0,0.8) 0%, rgba(0,0,0,0.7) 100%), url('{{ asset('images/manufacturer.png') }}');
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
                <!-- Sidebar Header -->
                <div class="flex items-center justify-center h-16 border-b border-gray-600">
                    <div class="logo-container">
                        <img src="{{ asset('images/CA-WORD2.png') }}" alt="ChicAura Logo" class="w-full h-auto object-contain max-w-[160px] max-h-[48px]">
                    </div>
                </div>
                <div class="px-4 py-4">
                    <h3 class="text-white text-sm font-semibold mb-3 px-3">MANUFACTURER PORTAL</h3>
                </div>
                <!-- Sidebar Navigation -->
                <nav class="flex-1 px-4 py-2 space-y-1">
                    <a href="{{route('manufacturer.dashboard')}}" class="nav-link flex items-center px-3 py-2 text-gray-300 hover:bg-gray-700 hover:text-white rounded-xl">
                        <i class="fas fa-home w-5"></i>
                        <span class="ml-2 text-sm">Home</span>
                    </a>
                    <a href="{{route('manufacturer.orders')}}" class="nav-link flex items-center px-3 py-2 text-gray-300 hover:bg-gray-700 hover:text-white rounded-xl">
                        <i class="fas fa-box w-5"></i>
                        <span class="ml-2 text-sm">Orders</span>
                    </a>
                    <a href="{{route('manufacturer.analytics')}}" class="nav-link flex items-center px-3 py-2 text-gray-300 hover:bg-gray-700 hover:text-white rounded-xl">
                        <i class="fas fa-chart-pie w-5"></i>
                        <span class="ml-2 text-sm">Analytics</span>
                    </a>
                    <a href="{{route('manufacturer.inventory')}}" class="nav-link flex items-center px-3 py-2 text-gray-300 hover:bg-gray-700 hover:text-white rounded-xl">
                        <i class="fas fa-warehouse w-5"></i>
                        <span class="ml-2 text-sm">Inventory</span>
                    </a>
                    <a href="{{route('manufacturer.workforce.index')}}" class="nav-link flex items-center px-3 py-2 text-gray-300 hover:bg-gray-700 hover:text-white rounded-xl">
                        <i class="fas fa-user-tie w-5"></i>
                        <span class="ml-2 text-sm">Workforce</span>
                    </a>
                    <a href="{{route('manufacturer.warehouse.index')}}" class="nav-link flex items-center px-3 py-2 text-gray-300 hover:bg-gray-700 hover:text-white rounded-xl">
                        <i class="fas fa-warehouse w-5"></i>
                        <span class="ml-2 text-sm">Warehouses</span>
                    </a>
                    <a href="{{route('manufacturer.wholesalers')}}" class="nav-link flex items-center px-3 py-2 text-gray-300 hover:bg-gray-700 hover:text-white rounded-xl">
                        <i class="fas fa-users w-5"></i>
                        <span class="ml-2 text-sm">Wholesalers</span>
                    </a>
                    <a href="{{route('manufacturer.suppliers')}}" class="nav-link flex items-center px-3 py-2 text-gray-300 hover:bg-gray-700 hover:text-white rounded-xl">
                        <i class="fas fa-truck-fast w-5"></i>
                        <span class="ml-2 text-sm">Suppliers</span>
                    </a>
                    <a href="{{route('manufacturer.chat')}}" class="nav-link flex items-center px-3 py-2 text-gray-300 hover:bg-gray-700 hover:text-white rounded-xl">
                        <i class="fas fa-comments w-5"></i>
                        <span class="ml-2 text-sm">Chat</span>
                    </a>
                    <a href="{{route('manufacturer.reports')}}" class="nav-link flex items-center px-3 py-2 text-gray-300 hover:bg-gray-700 hover:text-white rounded-xl">
                        <i class="fas fa-file-alt w-5"></i>
                        <span class="ml-2 text-sm">Reports</span>
                    </a>
                    <a href="{{route('manufacturer.revenue')}}" class="nav-link flex items-center px-3 py-2 text-gray-300 hover:bg-gray-700 hover:text-white rounded-xl">
                        <i class="fas fa-dollar-sign w-5"></i>
                        <span class="ml-2 text-sm">Revenue</span>
                    </a>
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
                    <!-- Mobile Menu Toggle -->
                    <button id="menu-toggle" class="md:hidden p-3 text-gray-500 hover:text-gray-700">
                        <i class="fas fa-bars text-lg"></i>
                    </button>
                    <!-- Search Bar -->
                    <div class="relative ml-3 hidden md:block">
                        <span class="absolute inset-y-0 left-0 flex items-center pl-3">
                            <i class="fas fa-search text-gray-400"></i>
                        </span>
                        <input type="text" class="w-80 py-2 pl-10 pr-4 border border-gray-300 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-transparent text-sm" placeholder="Search orders, inventory, suppliers...">
                    </div>
                </div>
                <div class="flex items-center pr-4 space-x-3">
                    <div class="relative">
                        <button id="notificationDropdownBtn" class="p-2 text-gray-500 hover:text-indigo-600 hover:bg-indigo-50 rounded-full transition-colors focus:outline-none relative">
                            <i class="fas fa-bell text-lg"></i>
                            @php $unreadCount = auth()->user()->unreadNotifications->count(); @endphp
                            @if($unreadCount > 0)
                                <span class="absolute -top-1 -right-1 inline-flex items-center justify-center px-1.5 py-0.5 text-xs font-bold leading-none text-white bg-red-600 rounded-full min-w-[18px] min-h-[18px]">{{ $unreadCount }}</span>
                            @endif
                        </button>
                        <div id="notificationDropdown" class="hidden absolute right-0 mt-2 w-80 bg-white border border-gray-200 rounded-lg shadow-lg z-50">
                            <div class="p-4 border-b font-semibold">Notifications</div>
                            <ul class="max-h-64 overflow-y-auto">
                                @forelse(auth()->user()->unreadNotifications as $notification)
                                    <li class="px-4 py-2 border-b hover:bg-gray-50">
                                        <div class="text-sm">{{ $notification->data['message'] ?? 'You have a new notification.' }}</div>
                                        <div class="text-xs text-gray-400">{{ \Carbon\Carbon::parse($notification->created_at)->diffForHumans() }}</div>
                                    </li>
                                @empty
                                    <li class="px-4 py-2 text-gray-500 text-sm">No new notifications.</li>
                                @endforelse
                            </ul>
                            @if(auth()->user()->unreadNotifications->count() > 0)
                                <form method="POST" action="{{ route('manufacturer.notifications.markAsRead') }}" class="p-2 text-center">
                                    @csrf
                                    <button type="submit" class="text-xs text-indigo-600 hover:underline">Mark all as read</button>
                                </form>
                            @endif
                        </div>
                    </div>
                    <button data-theme-toggle class="p-2 text-gray-500 hover:text-indigo-600 hover:bg-indigo-50 rounded-full transition-colors" title="Switch Theme">
                        <i class="fas fa-moon text-lg"></i>
                    </button>
                    <!-- Profile Dropdown -->
                    <div class="relative">
                        <button class="flex items-center focus:outline-none bg-white rounded-full p-2 shadow-md hover:shadow-lg transition-shadow">
                            <span class="mr-2 text-gray-700 font-medium text-sm">{{ Auth::user()->name ?? 'Manufacturer User' }}</span>
                            <img class="w-7 h-7 rounded-full border-2 border-indigo-200 object-cover" 
                                 src="{{ Auth::user()->profile_picture ? Storage::disk('public')->url(Auth::user()->profile_picture) : asset('images/default-avatar.svg') }}" 
                                 alt="User Avatar">
                        </button>
                    </div>
                    <div class="flex items-center space-x-2">
                        <a href="{{ route('user.profile.edit') }}" class="p-2 text-gray-500 hover:text-indigo-600 hover:bg-indigo-50 rounded-full transition-colors" title="Edit Profile">
                            <i class="fas fa-user-edit text-lg"></i>
                        </a>
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
            <main class="flex-1 p-4">
                <div class="mb-4">
                    <h2 class="text-2xl font-bold text-white mb-1">Order Analytics</h2>
                    <p class="text-gray-200 text-sm">Comprehensive insights into your order management and supply chain performance.</p>
                </div>

                <!-- Order Statistics Cards -->
                <div class="grid grid-cols-1 gap-4 mt-4 sm:grid-cols-2 lg:grid-cols-4">
                    <div class="stat-card p-4 rounded-xl">
                        <div class="flex items-center">
                            <div class="p-3 bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl shadow-lg">
                                <i class="fas fa-shopping-cart text-white text-xl"></i>
                            </div>
                            <div class="ml-3">
                                <p class="text-xs font-medium text-gray-600">Total Orders</p>
                                <p class="text-2xl font-bold text-gray-800">{{ $orderStats['total_orders'] ?? '0' }}</p>
                                <p class="text-xs text-blue-600 mt-1">↗ +12% this month</p>
                            </div>
                        </div>
                    </div>
                    <div class="stat-card p-4 rounded-xl">
                        <div class="flex items-center">
                            <div class="p-3 bg-gradient-to-br from-yellow-500 to-yellow-600 rounded-xl shadow-lg">
                                <i class="fas fa-clock text-white text-xl"></i>
                            </div>
                            <div class="ml-3">
                                <p class="text-xs font-medium text-gray-600">Pending Orders</p>
                                <p class="text-2xl font-bold text-gray-800">{{ $orderStats['pending_orders'] ?? '0' }}</p>
                                <p class="text-xs text-yellow-600 mt-1">↗ +5% this week</p>
                            </div>
                        </div>
                    </div>
                    <div class="stat-card p-4 rounded-xl">
                        <div class="flex items-center">
                            <div class="p-3 bg-gradient-to-br from-green-500 to-green-600 rounded-xl shadow-lg">
                                <i class="fas fa-check-circle text-white text-xl"></i>
                            </div>
                            <div class="ml-3">
                                <p class="text-xs font-medium text-gray-600">Completed Orders</p>
                                <p class="text-2xl font-bold text-gray-800">{{ $orderStats['completed_orders'] ?? '0' }}</p>
                                <p class="text-xs text-green-600 mt-1">↗ +18% this month</p>
                            </div>
                        </div>
                    </div>
                    <div class="stat-card p-4 rounded-xl">
                        <div class="flex items-center">
                            <div class="p-3 bg-gradient-to-br from-purple-500 to-purple-600 rounded-xl shadow-lg">
                                <i class="fas fa-dollar-sign text-white text-xl"></i>
                            </div>
                            <div class="ml-3">
                                <p class="text-xs font-medium text-gray-600">Total Revenue</p>
                                <p class="text-2xl font-bold text-gray-800">${{ number_format($orderStats['total_revenue'] ?? 0, 2) }}</p>
                                <p class="text-xs text-purple-600 mt-1">↗ +22% this month</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Charts Section -->
                <div class="grid grid-cols-1 gap-4 mt-6 lg:grid-cols-2">
                    <!-- Monthly Orders Chart -->
                    <div class="card-gradient p-4 rounded-xl">
                        <h3 class="text-lg font-bold text-gray-800 mb-3">Monthly Order Trends</h3>
                        <div class="relative" style="height: 300px;">
                            <canvas id="monthlyOrdersChart"></canvas>
                        </div>
                    </div>

                    <!-- Order Status Distribution -->
                    <div class="card-gradient p-4 rounded-xl">
                        <h3 class="text-lg font-bold text-gray-800 mb-3">Order Status Distribution</h3>
                        <div class="relative" style="height: 300px;">
                            <canvas id="orderStatusChart"></canvas>
                        </div>
                    </div>
                </div>

                <!-- Supply Request Analytics -->
                <div class="mt-6">
                    <h3 class="text-xl font-bold text-white mb-4">Supply Request Analytics</h3>
                    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-3">
                        <div class="stat-card p-4 rounded-xl">
                            <div class="flex items-center">
                                <div class="p-3 bg-gradient-to-br from-orange-500 to-orange-600 rounded-xl shadow-lg">
                                    <i class="fas fa-truck text-white text-xl"></i>
                                </div>
                                <div class="ml-3">
                                    <p class="text-xs font-medium text-gray-600">Total Supply Requests</p>
                                    <p class="text-2xl font-bold text-gray-800">{{ $supplyStats['total_requests'] ?? '0' }}</p>
                                    <p class="text-xs text-orange-600 mt-1">↗ +8% this month</p>
                                </div>
                            </div>
                        </div>
                        <div class="stat-card p-4 rounded-xl">
                            <div class="flex items-center">
                                <div class="p-3 bg-gradient-to-br from-red-500 to-red-600 rounded-xl shadow-lg">
                                    <i class="fas fa-exclamation-triangle text-white text-xl"></i>
                                </div>
                                <div class="ml-3">
                                    <p class="text-xs font-medium text-gray-600">Pending Requests</p>
                                    <p class="text-2xl font-bold text-gray-800">{{ $supplyStats['pending_requests'] ?? '0' }}</p>
                                    <p class="text-xs text-red-600 mt-1">↗ +3% this week</p>
                                </div>
                            </div>
                        </div>
                        <div class="stat-card p-4 rounded-xl">
                            <div class="flex items-center">
                                <div class="p-3 bg-gradient-to-br from-teal-500 to-teal-600 rounded-xl shadow-lg">
                                    <i class="fas fa-percentage text-white text-xl"></i>
                                </div>
                                <div class="ml-3">
                                    <p class="text-xs font-medium text-gray-600">Completion Rate</p>
                                    <p class="text-2xl font-bold text-gray-800">{{ $supplyStats['completion_rate'] ?? '0' }}%</p>
                                    <p class="text-xs text-teal-600 mt-1">↗ +5% this month</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Top Performers Section -->
                <div class="grid grid-cols-1 gap-4 mt-6 lg:grid-cols-2">
                    <!-- Top Wholesalers -->
                    <div class="card-gradient p-4 rounded-xl">
                        <h3 class="text-lg font-bold text-gray-800 mb-3">Top Wholesalers by Revenue</h3>
                        <div class="space-y-3">
                            @forelse($orderStats['top_wholesalers'] ?? [] as $wholesaler)
                                <div class="flex items-center justify-between p-3 bg-white rounded-lg shadow-sm">
                                    <div class="flex items-center">
                                        <div class="w-10 h-10 bg-gradient-to-br from-blue-500 to-blue-600 rounded-full flex items-center justify-center">
                                            <span class="text-white font-bold text-sm">{{ substr($wholesaler->wholesaler->user->name ?? 'N/A', 0, 1) }}</span>
                                        </div>
                                        <div class="ml-3">
                                            <p class="text-sm font-medium text-gray-800">{{ $wholesaler->wholesaler->user->name ?? 'N/A' }}</p>
                                            <p class="text-xs text-gray-500">{{ $wholesaler->order_count }} orders</p>
                                        </div>
                                    </div>
                                    <div class="text-right">
                                        <p class="text-sm font-bold text-gray-800">${{ number_format($wholesaler->total_revenue, 2) }}</p>
                                    </div>
                                </div>
                            @empty
                                <div class="text-center py-6">
                                    <i class="fas fa-users text-gray-400 text-2xl mb-2"></i>
                                    <p class="text-gray-500 text-sm">No wholesaler data available.</p>
                                </div>
                            @endforelse
                        </div>
                    </div>

                    <!-- Top Suppliers -->
                    <div class="card-gradient p-4 rounded-xl">
                        <h3 class="text-lg font-bold text-gray-800 mb-3">Top Suppliers by Performance</h3>
                        <div class="space-y-3">
                            @forelse($supplyStats['supplier_performance'] ?? [] as $supplier)
                                <div class="flex items-center justify-between p-3 bg-white rounded-lg shadow-sm">
                                    <div class="flex items-center">
                                        <div class="w-10 h-10 bg-gradient-to-br from-green-500 to-green-600 rounded-full flex items-center justify-center">
                                            <span class="text-white font-bold text-sm">{{ substr($supplier->supplier->user->name ?? 'N/A', 0, 1) }}</span>
                                        </div>
                                        <div class="ml-3">
                                            <p class="text-sm font-medium text-gray-800">{{ $supplier->supplier->user->name ?? 'N/A' }}</p>
                                            <p class="text-xs text-gray-500">{{ $supplier->request_count }} requests</p>
                                        </div>
                                    </div>
                                    <div class="text-right">
                                        <p class="text-sm font-bold text-gray-800">{{ number_format($supplier->completion_rate * 100, 1) }}%</p>
                                        <p class="text-xs text-gray-500">completion</p>
                                    </div>
                                </div>
                            @empty
                                <div class="text-center py-6">
                                    <i class="fas fa-truck text-gray-400 text-2xl mb-2"></i>
                                    <p class="text-gray-500 text-sm">No supplier data available.</p>
                                </div>
                            @endforelse
                        </div>
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

        // Monthly Orders Chart
        const monthlyOrdersCtx = document.getElementById('monthlyOrdersChart');
        if (monthlyOrdersCtx) {
            const monthlyData = @json($orderStats['monthly_orders'] ?? []);
            const labels = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
            const data = labels.map((_, index) => monthlyData[index + 1] || 0);

            new Chart(monthlyOrdersCtx, {
                type: 'line',
                data: {
                    labels: labels,
                    datasets: [{
                        label: 'Orders',
                        data: data,
                        borderColor: 'rgb(59, 130, 246)',
                        backgroundColor: 'rgba(59, 130, 246, 0.1)',
                        tension: 0.4,
                        fill: true
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: true,
                    aspectRatio: 2,
                    plugins: {
                        legend: {
                            display: false
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            grid: {
                                color: 'rgba(0, 0, 0, 0.1)'
                            }
                        },
                        x: {
                            grid: {
                                color: 'rgba(0, 0, 0, 0.1)'
                            }
                        }
                    }
                }
            });
        }

        // Order Status Distribution Chart
        const orderStatusCtx = document.getElementById('orderStatusChart');
        if (orderStatusCtx) {
            const statusData = @json($orderStats['status_distribution'] ?? []);
            const statusLabels = Object.keys(statusData);
            const statusValues = Object.values(statusData);
            const colors = ['#3B82F6', '#F59E0B', '#10B981', '#EF4444', '#8B5CF6', '#6B7280'];

            new Chart(orderStatusCtx, {
                type: 'doughnut',
                data: {
                    labels: statusLabels.map(label => label.charAt(0).toUpperCase() + label.slice(1).replace('_', ' ')),
                    datasets: [{
                        data: statusValues,
                        backgroundColor: colors.slice(0, statusValues.length),
                        borderWidth: 2,
                        borderColor: '#ffffff'
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: true,
                    aspectRatio: 1.5,
                    plugins: {
                        legend: {
                            position: 'bottom',
                            labels: {
                                padding: 20,
                                usePointStyle: true
                            }
                        }
                    }
                }
            });
        }
    </script>
</body>
</html>
