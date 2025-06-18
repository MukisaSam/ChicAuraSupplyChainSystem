{{-- resources/views/wholesaler/analytics/index.blade.php --}}
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Analytics - ChicAura SCM</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <script src="{{ asset('js/theme-switcher.js') }}"></script>
    <style>
        body { 
            background: linear-gradient(135deg, rgba(0,0,0,0.7) 0%, rgba(0,0,0,0.5) 100%), url('{{ asset('images/wholesaler.jpg') }}');
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
            min-height: 100vh;
        }
        
        /* Dark mode styles */
        .dark body {
            background: linear-gradient(135deg, rgba(0,0,0,0.8) 0%, rgba(0,0,0,0.7) 100%), url('{{ asset('images/wholesaler.jpg') }}');
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
        
        .dark .text-gray-900 {
            color: #f1f5f9;
        }
        
        .dark .text-gray-600 {
            color: #cbd5e1;
        }
        
        .dark .text-gray-500 {
            color: #94a3b8;
        }
        
        .dark .bg-white {
            background-color: #1e293b;
        }
        
        .dark .border-gray-200 {
            border-color: #475569;
        }
        
        .dark .hover\:bg-gray-700:hover {
            background-color: #475569;
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
                    <h3 class="text-white text-sm font-semibold mb-3 px-3">WHOLESALER PORTAL</h3>
                </div>
                <nav class="flex-1 px-4 py-2 space-y-1">
                    <a href="{{ route('wholesaler.dashboard') }}" class="nav-link flex items-center px-3 py-2 text-gray-300 hover:bg-gray-700 hover:text-white rounded-xl"><i class="fas fa-home w-5"></i><span class="ml-2 text-sm">Home</span></a>
                    <a href="{{ route('wholesaler.orders.index') }}" class="nav-link flex items-center px-3 py-2 text-gray-300 hover:bg-gray-700 hover:text-white rounded-xl"><i class="fas fa-shopping-cart w-5"></i><span class="ml-2 text-sm">Orders</span></a>
                    <a href="{{ route('wholesaler.analytics.index') }}" class="nav-link flex items-center px-3 py-2 text-white bg-gradient-to-r from-purple-600 to-purple-700 rounded-xl shadow-lg"><i class="fas fa-chart-line w-5"></i><span class="ml-2 font-medium text-sm">Analytics</span></a>
                    <a href="{{ route('wholesaler.chat.index') }}" class="nav-link flex items-center px-3 py-2 text-gray-300 hover:bg-gray-700 hover:text-white rounded-xl"><i class="fas fa-comments w-5"></i><span class="ml-2 text-sm">Chat</span></a>
                    <a href="#" class="nav-link flex items-center px-3 py-2 text-gray-300 hover:bg-gray-700 hover:text-white rounded-xl"><i class="fas fa-file-invoice-dollar w-5"></i><span class="ml-2 text-sm">Reports</span></a>
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
                    <button id="menu-toggle" class="md:hidden p-3 text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200"><i class="fas fa-bars text-lg"></i></button>
                    <div class="relative ml-3 hidden md:block">
                        <span class="absolute inset-y-0 left-0 flex items-center pl-3"><i class="fas fa-search text-gray-400"></i></span>
                        <input type="text" class="w-80 py-2 pl-10 pr-4 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-purple-500 focus:border-transparent text-sm dark:bg-gray-700 dark:text-white" placeholder="Search analytics...">
                    </div>
                </div>
                <div class="flex items-center pr-4 space-x-3">
                    <button class="p-2 text-gray-500 hover:text-purple-600 hover:bg-purple-50 dark:text-gray-400 dark:hover:text-purple-400 dark:hover:bg-purple-900/20 rounded-full transition-colors"><i class="fas fa-bell text-lg"></i></button>
                    <button data-theme-toggle class="p-2 text-gray-500 hover:text-purple-600 hover:bg-purple-50 dark:text-gray-400 dark:hover:text-purple-400 dark:hover:bg-purple-900/20 rounded-full transition-colors" title="Switch Theme">
                        <i class="fas fa-moon text-lg"></i>
                    </button>
                    <div class="relative">
                        <button class="flex items-center focus:outline-none bg-white dark:bg-gray-700 rounded-full p-2 shadow-md hover:shadow-lg transition-shadow">
                            <span class="mr-2 text-gray-700 dark:text-gray-200 font-medium text-sm">{{ $user->name ?? 'Wholesaler User' }}</span>
                            <img class="w-7 h-7 rounded-full border-2 border-purple-200 object-cover" 
                                 src="{{ $user->profile_picture ? Storage::disk('public')->url($user->profile_picture) : asset('images/default-avatar.svg') }}" 
                                 alt="User Avatar">
                        </button>
                    </div>
                    <div class="flex items-center space-x-2">
                        <a href="{{ route('user.profile.edit') }}" class="p-2 text-gray-500 hover:text-purple-600 hover:bg-purple-50 dark:text-gray-400 dark:hover:text-purple-400 dark:hover:bg-purple-900/20 rounded-full transition-colors" title="Edit Profile">
                            <i class="fas fa-user-edit text-lg"></i>
                        </a>
                        <form method="POST" action="{{ route('logout') }}" class="inline">
                            @csrf
                            <button type="submit" class="p-2 text-gray-500 hover:text-red-600 hover:bg-red-50 dark:text-gray-400 dark:hover:text-red-400 dark:hover:bg-red-900/20 rounded-full transition-colors" title="Logout">
                                <i class="fas fa-sign-out-alt text-lg"></i>
                            </button>
                        </form>
                    </div>
                </div>
            </header>

            <!-- Main Content -->
            <main class="flex-1 p-4 overflow-auto">
                <div class="mb-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <h2 class="text-2xl font-bold text-white mb-1">Analytics Dashboard</h2>
                            <p class="text-gray-200 text-sm">Track your business performance and insights</p>
                        </div>
                        <div class="flex space-x-3">
                            <select class="border border-gray-300 dark:border-gray-600 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-purple-500 focus:border-transparent dark:bg-gray-700 dark:text-white">
                                <option value="12">Last 12 Months</option>
                                <option value="6">Last 6 Months</option>
                                <option value="3">Last 3 Months</option>
                                <option value="1">Last Month</option>
                            </select>
                            <button class="bg-gradient-to-r from-purple-600 to-purple-700 text-white px-4 py-2 rounded-lg shadow-lg hover:from-purple-700 hover:to-purple-800 transition-all duration-300 flex items-center">
                                <i class="fas fa-download mr-2"></i>
                                Export Report
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Summary Statistics -->
                <div class="grid grid-cols-1 gap-4 mb-6 sm:grid-cols-2 lg:grid-cols-4">
                    <div class="card-gradient rounded-xl p-6">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <div class="w-12 h-12 bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl flex items-center justify-center shadow-lg">
                                    <i class="fas fa-shopping-cart text-white text-xl"></i>
                                </div>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Total Orders</p>
                                <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $totalOrders ?? 0 }}</p>
                                <p class="text-xs text-blue-600 dark:text-blue-400 mt-1">
                                    @if(isset($orderGrowth) && $orderGrowth > 0)
                                        ↗ +{{ number_format($orderGrowth, 1) }}% this month
                                    @elseif(isset($orderGrowth) && $orderGrowth < 0)
                                        ↘ {{ number_format($orderGrowth, 1) }}% this month
                                    @else
                                        → No change
                                    @endif
                                </p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="card-gradient rounded-xl p-6">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <div class="w-12 h-12 bg-gradient-to-br from-green-500 to-green-600 rounded-xl flex items-center justify-center shadow-lg">
                                    <i class="fas fa-dollar-sign text-white text-xl"></i>
                                </div>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Total Spent</p>
                                <p class="text-2xl font-bold text-gray-900 dark:text-white">${{ number_format($totalSpent ?? 0, 2) }}</p>
                                <p class="text-xs text-green-600 dark:text-green-400 mt-1">Lifetime spending</p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="card-gradient rounded-xl p-6">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <div class="w-12 h-12 bg-gradient-to-br from-purple-500 to-purple-600 rounded-xl flex items-center justify-center shadow-lg">
                                    <i class="fas fa-chart-line text-white text-xl"></i>
                                </div>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Avg Order Value</p>
                                <p class="text-2xl font-bold text-gray-900 dark:text-white">${{ number_format($averageOrderValue ?? 0, 2) }}</p>
                                <p class="text-xs text-purple-600 dark:text-purple-400 mt-1">Per order average</p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="card-gradient rounded-xl p-6">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <div class="w-12 h-12 bg-gradient-to-br from-yellow-500 to-yellow-600 rounded-xl flex items-center justify-center shadow-lg">
                                    <i class="fas fa-clock text-white text-xl"></i>
                                </div>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Pending Orders</p>
                                <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $pendingOrders ?? 0 }}</p>
                                <p class="text-xs text-yellow-600 dark:text-yellow-400 mt-1">Awaiting fulfillment</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Charts Section -->
                <div class="grid grid-cols-1 gap-6 mb-6 lg:grid-cols-2">
                    <!-- Order Trends Chart -->
                    <div class="card-gradient rounded-xl p-6">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-200">Order Trends</h3>
                            <div class="flex space-x-2">
                                <button class="px-3 py-1 text-xs bg-purple-100 dark:bg-purple-900/20 text-purple-700 dark:text-purple-400 rounded-lg">Orders</button>
                                <button class="px-3 py-1 text-xs bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-400 rounded-lg">Revenue</button>
                            </div>
                        </div>
                        <div class="h-64">
                            <canvas id="orderTrendsChart" width="400" height="200"></canvas>
                        </div>
                    </div>
                    
                    <!-- Revenue Chart -->
                    <div class="card-gradient rounded-xl p-6">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-200">Revenue Trends</h3>
                            <div class="flex space-x-2">
                                <button class="px-3 py-1 text-xs bg-green-100 dark:bg-green-900/20 text-green-700 dark:text-green-400 rounded-lg">Monthly</button>
                                <button class="px-3 py-1 text-xs bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-400 rounded-lg">Quarterly</button>
                            </div>
                        </div>
                        <div class="h-64">
                            <canvas id="revenueChart" width="400" height="200"></canvas>
                        </div>
                    </div>
                </div>

                <!-- Additional Analytics -->
                <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">
                    <!-- Top Products -->
                    <div class="card-gradient rounded-xl p-6">
                        <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-200 mb-4">Top Products</h3>
                        @if(isset($topProducts) && $topProducts->count() > 0)
                            <div class="space-y-3">
                                @foreach($topProducts as $product)
                                    <div class="flex items-center justify-between">
                                        <div class="flex items-center space-x-3">
                                            <div class="w-8 h-8 bg-gradient-to-br from-blue-500 to-blue-600 rounded-lg flex items-center justify-center">
                                                <i class="fas fa-box text-white text-xs"></i>
                                            </div>
                                            <div>
                                                <p class="text-sm font-medium text-gray-900 dark:text-gray-100">{{ $product->name }}</p>
                                                <p class="text-xs text-gray-500 dark:text-gray-400">{{ $product->total_quantity }} units</p>
                                            </div>
                                        </div>
                                        <p class="text-sm font-semibold text-gray-900 dark:text-gray-100">${{ number_format($product->total_revenue, 2) }}</p>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center py-8">
                                <i class="fas fa-chart-bar text-gray-400 dark:text-gray-500 text-3xl mb-2"></i>
                                <p class="text-sm text-gray-500 dark:text-gray-400">No product data available</p>
                            </div>
                        @endif
                    </div>

                    <!-- Order Status Distribution -->
                    <div class="card-gradient rounded-xl p-6">
                        <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-200 mb-4">Order Status</h3>
                        @if(isset($statusDistribution) && $statusDistribution->count() > 0)
                            <div class="space-y-3">
                                @foreach($statusDistribution as $status)
                                    <div class="flex items-center justify-between">
                                        <div class="flex items-center space-x-3">
                                            <div class="w-3 h-3 rounded-full 
                                                @if($status->status === 'pending') bg-yellow-500
                                                @elseif($status->status === 'confirmed') bg-blue-500
                                                @elseif($status->status === 'in_production') bg-purple-500
                                                @elseif($status->status === 'shipped') bg-indigo-500
                                                @elseif($status->status === 'delivered') bg-green-500
                                                @else bg-red-500
                                                @endif">
                                            </div>
                                            <span class="text-sm text-gray-900 dark:text-gray-100 capitalize">{{ str_replace('_', ' ', $status->status) }}</span>
                                        </div>
                                        <span class="text-sm font-semibold text-gray-900 dark:text-gray-100">{{ $status->count }}</span>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center py-8">
                                <i class="fas fa-chart-pie text-gray-400 dark:text-gray-500 text-3xl mb-2"></i>
                                <p class="text-sm text-gray-500 dark:text-gray-400">No status data available</p>
                            </div>
                        @endif
                    </div>

                    <!-- Recent Activity -->
                    <div class="card-gradient rounded-xl p-6">
                        <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-200 mb-4">Recent Activity</h3>
                        @if(isset($recentActivity) && $recentActivity->count() > 0)
                            <div class="space-y-3">
                                @foreach($recentActivity->take(5) as $activity)
                                    <div class="flex items-center space-x-3">
                                        <div class="w-8 h-8 bg-gradient-to-br from-purple-500 to-purple-600 rounded-lg flex items-center justify-center">
                                            <i class="fas fa-shopping-cart text-white text-xs"></i>
                                        </div>
                                        <div class="flex-1">
                                            <p class="text-sm font-medium text-gray-900 dark:text-gray-100">{{ $activity->order_number }}</p>
                                            <p class="text-xs text-gray-500 dark:text-gray-400">{{ $activity->order_date->format('M d, Y') }}</p>
                                        </div>
                                        <span class="text-sm font-semibold text-gray-900 dark:text-gray-100">${{ number_format($activity->total_amount, 2) }}</span>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center py-8">
                                <i class="fas fa-clock text-gray-400 dark:text-gray-500 text-3xl mb-2"></i>
                                <p class="text-sm text-gray-500 dark:text-gray-400">No recent activity</p>
                            </div>
                        @endif
                    </div>
                </div>
            </main>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        // Mobile menu toggle
        document.getElementById('menu-toggle').addEventListener('click', function() {
            document.getElementById('sidebar').classList.toggle('open');
        });

        // Chart data from PHP
        const chartLabels = {!! json_encode($chartLabels ?? ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec']) !!};
        const orderCounts = {!! json_encode($orderCounts ?? [0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0]) !!};
        const orderAmounts = {!! json_encode($orderAmounts ?? [0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0]) !!};

        // Order Trends Chart
        const orderTrendsCtx = document.getElementById('orderTrendsChart');
        if (orderTrendsCtx) {
            new Chart(orderTrendsCtx.getContext('2d'), {
                type: 'line',
                data: {
                    labels: chartLabels,
                    datasets: [{
                        label: 'Orders',
                        data: orderCounts,
                        borderColor: '#8B5CF6',
                        backgroundColor: 'rgba(139, 92, 246, 0.1)',
                        borderWidth: 3,
                        fill: true,
                        tension: 0.4,
                        pointBackgroundColor: '#8B5CF6',
                        pointBorderColor: '#ffffff',
                        pointBorderWidth: 2,
                        pointRadius: 6
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
                            grid: {
                                color: 'rgba(156, 163, 175, 0.1)'
                            },
                            ticks: {
                                color: '#6B7280'
                            }
                        },
                        x: {
                            grid: {
                                color: 'rgba(156, 163, 175, 0.1)'
                            },
                            ticks: {
                                color: '#6B7280'
                            }
                        }
                    }
                }
            });
        }

        // Revenue Chart
        const revenueCtx = document.getElementById('revenueChart');
        if (revenueCtx) {
            new Chart(revenueCtx.getContext('2d'), {
                type: 'bar',
                data: {
                    labels: chartLabels,
                    datasets: [{
                        label: 'Revenue',
                        data: orderAmounts,
                        backgroundColor: 'rgba(34, 197, 94, 0.8)',
                        borderColor: '#22C55E',
                        borderWidth: 2,
                        borderRadius: 8,
                        borderSkipped: false
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
                            grid: {
                                color: 'rgba(156, 163, 175, 0.1)'
                            },
                            ticks: {
                                color: '#6B7280',
                                callback: function(value) {
                                    return '$' + value.toLocaleString();
                                }
                            }
                        },
                        x: {
                            grid: {
                                color: 'rgba(156, 163, 175, 0.1)'
                            },
                            ticks: {
                                color: '#6B7280'
                            }
                        }
                    }
                }
            });
        }
    </script>
</body>
</html> 