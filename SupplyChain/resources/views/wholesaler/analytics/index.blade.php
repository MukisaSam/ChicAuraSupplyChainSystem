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
            background: rgba(255, 255, 255, 0.98);
            border-radius: 12px;
            padding: 8px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        }
        
        .dark .logo-container {
            background: rgba(255, 255, 255, 0.95);
        }
        
        .card-gradient {
            background: linear-gradient(135deg, #ffffff 0%, #f8fafc 100%);
            border: 1px solid rgba(255,255,255,0.2);
            box-shadow: 0 8px 32px rgba(0,0,0,0.1);
            backdrop-filter: blur(10px);
        }
        
        .dark .card-gradient {
            background: linear-gradient(135deg, #1e293b 0%, #0f172a 100%);
            border: 1px solid rgba(255,255,255,0.1);
            color: #f8fafc;
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
            background: linear-gradient(135deg, #1e293b 0%, #0f172a 100%);
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
        
        /* Chart container styles */
        .chart-container {
            position: relative;
            height: 300px;
            width: 100%;
            @apply bg-white/5 dark:bg-black/5 rounded-lg p-4;
        }
        
        /* Dark mode chart adjustments */
        .dark .chart-container canvas {
            filter: brightness(1.1) contrast(1.2);
        }
        
        /* Enhanced card content visibility */
        .card-gradient .text-gray-600 {
            @apply text-gray-700 dark:text-gray-300;
        }
        
        .card-gradient .text-gray-900 {
            @apply text-gray-900 dark:text-gray-100;
        }
        
        /* Status badges with enhanced visibility */
        .status-badge {
            @apply px-2 py-1 text-xs rounded-full font-medium;
        }
        
        .status-badge-completed {
            @apply bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200;
        }
        
        .status-badge-pending {
            @apply bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200;
        }
        
        .status-badge-cancelled {
            @apply bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200;
        }
        
        /* Enhanced table styles for better visibility */
        table thead tr {
            @apply bg-gray-50 dark:bg-gray-800;
        }
        
        table th {
            @apply text-gray-700 dark:text-gray-200 font-semibold;
        }
        
        table td {
            @apply text-gray-800 dark:text-gray-300;
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
                        <img src="{{ asset('images/CA-WORD2.png') }}" alt="ChicAura Logo" class="w-full h-auto object-contain max-w-[160px] max-h-[48px]">
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
                    <a href="{{ route('wholesaler.reports.index') }}" class="nav-link flex items-center px-3 py-2 text-gray-300 hover:bg-gray-700 hover:text-white rounded-xl"><i class="fas fa-file-invoice-dollar w-5"></i><span class="ml-2 text-sm">Reports</span></a>
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
                    <x-wholesaler-notification-bell />
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
                <div class="container mx-auto px-2 sm:px-4 md:px-8 py-6">
                    <!-- Header -->
                    <div class="mb-6">
                        <h1 class="text-3xl font-bold text-white mb-2">Analytics Dashboard</h1>
                        <p class="text-gray-200">Track your order performance and business insights</p>
                    </div>

                    <!-- Summary Cards -->
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                        <div class="p-6 rounded-xl bg-white dark:bg-slate-800 border border-gray-200 dark:border-slate-700 shadow">
                            <div class="flex items-center">
                                <div class="p-3 rounded-xl bg-blue-500 dark:bg-blue-700">
                                    <i class="fas fa-shopping-cart text-white text-xl"></i>
                                </div>
                                <div class="ml-4">
                                    <p class="text-sm text-gray-700 dark:text-gray-300">Total Orders</p>
                                    <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $totalOrders }}</p>
                                </div>
                            </div>
                        </div>

                        <div class="p-6 rounded-xl bg-white dark:bg-slate-800 border border-gray-200 dark:border-slate-700 shadow">
                            <div class="flex items-center">
                                <div class="p-3 rounded-xl bg-green-500 dark:bg-green-700">
                                    <i class="fas fa-dollar-sign text-white text-xl"></i>
                                </div>
                                <div class="ml-4">
                                    <p class="text-sm text-gray-700 dark:text-gray-300">Total Spent</p>
                                    <p class="text-2xl font-bold text-gray-900 dark:text-white">${{ number_format($totalSpent, 2) }}</p>
                                </div>
                            </div>
                        </div>

                        <div class="p-6 rounded-xl bg-white dark:bg-slate-800 border border-gray-200 dark:border-slate-700 shadow">
                            <div class="flex items-center">
                                <div class="p-3 rounded-xl bg-purple-500 dark:bg-purple-700">
                                    <i class="fas fa-chart-line text-white text-xl"></i>
                                </div>
                                <div class="ml-4">
                                    <p class="text-sm text-gray-700 dark:text-gray-300">Avg Order Value</p>
                                    <p class="text-2xl font-bold text-gray-900 dark:text-white">${{ number_format($averageOrderValue, 2) }}</p>
                                </div>
                            </div>
                        </div>

                        <div class="p-6 rounded-xl bg-white dark:bg-slate-800 border border-gray-200 dark:border-slate-700 shadow">
                            <div class="flex items-center">
                                <div class="p-3 rounded-xl bg-orange-500 dark:bg-orange-700">
                                    <i class="fas fa-clock text-white text-xl"></i>
                                </div>
                                <div class="ml-4">
                                    <p class="text-sm text-gray-700 dark:text-gray-300">Pending Orders</p>
                                    <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $pendingOrders }}</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Charts Section -->
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
                        <!-- Order Trends Chart -->
                        <div class="p-6 rounded-xl bg-white dark:bg-slate-800 border border-gray-200 dark:border-slate-700 shadow">
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Monthly Order Trends</h3>
                            <div class="chart-container">
                                <canvas id="orderTrendsChart"></canvas>
                            </div>
                        </div>

                        <!-- Order Amounts Chart -->
                        <div class="p-6 rounded-xl bg-white dark:bg-slate-800 border border-gray-200 dark:border-slate-700 shadow">
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Monthly Order Amounts</h3>
                            <div class="chart-container">
                                <canvas id="orderAmountsChart"></canvas>
                            </div>
                        </div>
                    </div>

                    <!-- Data Tables -->
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
                        <!-- Top Products -->
                        <div class="p-6 rounded-xl bg-white dark:bg-slate-800 border border-gray-200 dark:border-slate-700 shadow">
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Top Products by Quantity</h3>
                            <div class="overflow-x-auto">
                                <table class="min-w-full">
                                    <thead>
                                        <tr class="border-b border-gray-200 dark:border-gray-600">
                                            <th class="text-left py-2 text-sm font-medium text-gray-600 dark:text-gray-400">Product</th>
                                            <th class="text-left py-2 text-sm font-medium text-gray-600 dark:text-gray-400">Quantity</th>
                                            <th class="text-left py-2 text-sm font-medium text-gray-600 dark:text-gray-400">Revenue</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($topProducts as $product)
                                        <tr class="border-b border-gray-100 dark:border-gray-700">
                                            <td class="py-2 text-sm text-gray-900 dark:text-white">{{ $product->name }}</td>
                                            <td class="py-2 text-sm text-gray-600 dark:text-gray-400">{{ $product->total_quantity }}</td>
                                            <td class="py-2 text-sm text-gray-600 dark:text-gray-400">${{ number_format($product->total_revenue, 2) }}</td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <!-- Order Status Distribution -->
                        <div class="p-6 rounded-xl bg-white dark:bg-slate-800 border border-gray-200 dark:border-slate-700 shadow">
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Order Status Distribution</h3>
                            <div class="space-y-3">
                                @foreach($statusDistribution as $status)
                                <div class="flex items-center justify-between">
                                    <span class="text-sm text-gray-600 dark:text-gray-400 capitalize">{{ $status->status }}</span>
                                    <span class="text-sm font-medium text-gray-900 dark:text-white">{{ $status->count }}</span>
                                </div>
                                @endforeach
                            </div>
                        </div>
                    </div>

                    <!-- Recent Activity -->
                    <div class="p-6 rounded-xl bg-white dark:bg-slate-800 border border-gray-200 dark:border-slate-700 shadow">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Recent Activity</h3>
                        <div class="overflow-x-auto">
                            <table class="min-w-full">
                                <thead>
                                    <tr class="border-b border-gray-200 dark:border-gray-600">
                                        <th class="text-left py-2 text-sm font-medium text-gray-600 dark:text-gray-400">Order ID</th>
                                        <th class="text-left py-2 text-sm font-medium text-gray-600 dark:text-gray-400">Date</th>
                                        <th class="text-left py-2 text-sm font-medium text-gray-600 dark:text-gray-400">Status</th>
                                        <th class="text-left py-2 text-sm font-medium text-gray-600 dark:text-gray-400">Amount</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($recentActivity as $order)
                                    <tr class="border-b border-gray-100 dark:border-gray-700">
                                        <td class="py-2 text-sm text-gray-900 dark:text-white">#{{ $order->id }}</td>
                                        <td class="py-2 text-sm text-gray-600 dark:text-gray-400">{{ $order->order_date->format('M d, Y') }}</td>
                                        <td class="py-2 text-sm">
                                            <span class="status-badge {{ $order->status === 'completed' ? 'status-badge-completed' : ($order->status === 'pending' ? 'status-badge-pending' : ($order->status === 'cancelled' ? 'status-badge-cancelled' : '')) }}">
                                                {{ ucfirst($order->status) }}
                                            </span>
                                        </td>
                                        <td class="py-2 text-sm text-gray-600 dark:text-gray-400">${{ number_format($order->total_amount, 2) }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
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
        const orderTrendsCtx = document.getElementById('orderTrendsChart').getContext('2d');
        new Chart(orderTrendsCtx, {
                type: 'line',
                data: {
                    labels: chartLabels,
                    datasets: [{
                        label: 'Orders',
                        data: orderCounts,
                        borderColor: 'rgb(99, 102, 241)',
                        backgroundColor: 'rgba(99, 102, 241, 0.2)',
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
                        },
                        tooltip: {
                            backgroundColor: 'rgba(0, 0, 0, 0.8)',
                            titleColor: '#fff',
                            bodyColor: '#fff',
                            padding: 12,
                            displayColors: false
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            grid: {
                                color: document.documentElement.classList.contains('dark') ? 'rgba(255, 255, 255, 0.1)' : 'rgba(0, 0, 0, 0.1)'
                            },
                            ticks: {
                                color: document.documentElement.classList.contains('dark') ? '#cbd5e1' : '#475569'
                            }
                        },
                        x: {
                            grid: {
                                display: false
                            },
                            ticks: {
                                color: document.documentElement.classList.contains('dark') ? '#cbd5e1' : '#475569'
                            }
                        }
                    }
                }
            });

        // Order Amounts Chart
        const orderAmountsCtx = document.getElementById('orderAmountsChart').getContext('2d');
        new Chart(orderAmountsCtx, {
                type: 'bar',
                data: {
                    labels: chartLabels,
                    datasets: [{
                        label: 'Amount ($)',
                        data: orderAmounts,
                        backgroundColor: 'rgba(168, 85, 247, 0.8)',
                        borderColor: 'rgb(168, 85, 247)',
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: false
                        },
                        tooltip: {
                            backgroundColor: 'rgba(0, 0, 0, 0.8)',
                            titleColor: '#fff',
                            bodyColor: '#fff',
                            padding: 12,
                            displayColors: false
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            grid: {
                                color: document.documentElement.classList.contains('dark') ? 'rgba(255, 255, 255, 0.1)' : 'rgba(0, 0, 0, 0.1)'
                            },
                            ticks: {
                                color: document.documentElement.classList.contains('dark') ? '#cbd5e1' : '#475569'
                            }
                        },
                        x: {
                            grid: {
                                display: false
                            },
                            ticks: {
                                color: document.documentElement.classList.contains('dark') ? '#cbd5e1' : '#475569'
                            }
                        }
                    }
                }
            });

        // Update chart colors when theme changes
        document.querySelector('[data-theme-toggle]').addEventListener('click', function() {
            setTimeout(() => {
                const isDark = document.documentElement.classList.contains('dark');
                const tickColor = isDark ? '#cbd5e1' : '#475569';
                const gridColor = isDark ? 'rgba(255, 255, 255, 0.1)' : 'rgba(0, 0, 0, 0.1)';
                
                [orderTrendsChart, orderAmountsChart].forEach(chart => {
                    chart.options.scales.y.grid.color = gridColor;
                    chart.options.scales.y.ticks.color = tickColor;
                    chart.options.scales.x.ticks.color = tickColor;
                    chart.update();
                });
            }, 100);
        });
    </script>
</body>
</html> 