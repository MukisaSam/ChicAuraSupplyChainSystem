{{-- resources/views/wholesaler/reports/orders.blade.php --}}
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Report - ChicAura SCM</title>
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
                        <img src="{{ asset('images/CA-WORD2.png') }}" alt="ChicAura Logo" class="w-full h-auto object-contain max-w-[160px] max-h-[48px]">
                    </div>
                </div>
                <div class="px-4 py-4">
                    <h3 class="text-white text-sm font-semibold mb-3 px-3">WHOLESALER PORTAL</h3>
                </div>
                <nav class="flex-1 px-4 py-2 space-y-1">
                    <a href="{{ route('wholesaler.dashboard') }}" class="nav-link flex items-center px-3 py-2 text-gray-300 hover:bg-gray-700 hover:text-white rounded-xl"><i class="fas fa-home w-5"></i><span class="ml-2 text-sm">Home</span></a>
                    <a href="{{ route('wholesaler.orders.index') }}" class="nav-link flex items-center px-3 py-2 text-gray-300 hover:bg-gray-700 hover:text-white rounded-xl"><i class="fas fa-shopping-cart w-5"></i><span class="ml-2 text-sm">Orders</span></a>
                    <a href="{{ route('wholesaler.analytics.index') }}" class="nav-link flex items-center px-3 py-2 text-gray-300 hover:bg-gray-700 hover:text-white rounded-xl"><i class="fas fa-chart-line w-5"></i><span class="ml-2 text-sm">Analytics</span></a>
                    <a href="{{ route('wholesaler.chat.index') }}" class="nav-link flex items-center px-3 py-2 text-gray-300 hover:bg-gray-700 hover:text-white rounded-xl"><i class="fas fa-comments w-5"></i><span class="ml-2 text-sm">Chat</span></a>
                    <a href="{{ route('wholesaler.reports.index') }}" class="nav-link flex items-center px-3 py-2 text-white bg-gradient-to-r from-purple-600 to-purple-700 rounded-xl shadow-lg"><i class="fas fa-file-invoice-dollar w-5"></i><span class="ml-2 font-medium text-sm">Reports</span></a>
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
                        <input type="text" class="w-80 py-2 pl-10 pr-4 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-purple-500 focus:border-transparent text-sm dark:bg-gray-700 dark:text-white" placeholder="Search orders...">
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
                <div class="container mx-auto px-2 sm:px-4 md:px-8 py-6">
                    <!-- Header -->
                    <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-6">
                        <div>
                            <h1 class="text-3xl font-bold text-white mb-2">Order Report</h1>
                            <p class="text-gray-200">Comprehensive order analysis from {{ $startDate }} to {{ $endDate }}</p>
                        </div>
                        <div class="flex space-x-2 mt-4 md:mt-0">
                            <a href="{{ route('wholesaler.reports.index') }}" class="px-4 py-2 bg-gray-600 text-white rounded-md hover:bg-gray-700">
                                <i class="fas fa-arrow-left mr-2"></i>Back to Reports
                            </a>
                            <a href="{{ route('wholesaler.reports.export', ['type' => 'orders', 'start_date' => $startDate, 'end_date' => $endDate]) }}" 
                               class="px-4 py-2 bg-purple-600 text-white rounded-md hover:bg-purple-700">
                                <i class="fas fa-download mr-2"></i>Export CSV
                            </a>
                        </div>
                    </div>

                    <!-- Date Filter -->
                    <div class="card-gradient p-6 rounded-xl mb-6">
                        <form method="GET" action="{{ route('wholesaler.reports.orders') }}" class="flex flex-col md:flex-row gap-4">
                            <div class="flex-1">
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Start Date</label>
                                <input type="date" name="start_date" value="{{ $startDate }}" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md focus:ring-2 focus:ring-purple-500 focus:border-transparent dark:bg-gray-700 dark:text-white">
                            </div>
                            <div class="flex-1">
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">End Date</label>
                                <input type="date" name="end_date" value="{{ $endDate }}" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md focus:ring-2 focus:ring-purple-500 focus:border-transparent dark:bg-gray-700 dark:text-white">
                            </div>
                            <div class="flex items-end">
                                <button type="submit" class="px-6 py-2 bg-purple-600 text-white rounded-md hover:bg-purple-700">
                                    <i class="fas fa-filter mr-2"></i>Filter
                                </button>
                            </div>
                        </form>
                    </div>

                    <!-- Order Metrics Summary -->
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
                        <div class="card-gradient p-6 rounded-xl">
                            <div class="flex items-center">
                                <div class="p-3 bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl">
                                    <i class="fas fa-shopping-cart text-white text-xl"></i>
                                </div>
                                <div class="ml-4">
                                    <p class="text-sm text-gray-600 dark:text-gray-400">Total Orders</p>
                                    <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $orderMetrics['total_orders'] }}</p>
                                </div>
                            </div>
                        </div>

                        <div class="card-gradient p-6 rounded-xl">
                            <div class="flex items-center">
                                <div class="p-3 bg-gradient-to-br from-green-500 to-green-600 rounded-xl">
                                    <i class="fas fa-dollar-sign text-white text-xl"></i>
                                </div>
                                <div class="ml-4">
                                    <p class="text-sm text-gray-600 dark:text-gray-400">Total Revenue</p>
                                    <p class="text-2xl font-bold text-gray-900 dark:text-white">${{ number_format($orderMetrics['total_revenue'], 2) }}</p>
                                </div>
                            </div>
                        </div>

                        <div class="card-gradient p-6 rounded-xl">
                            <div class="flex items-center">
                                <div class="p-3 bg-gradient-to-br from-purple-500 to-purple-600 rounded-xl">
                                    <i class="fas fa-chart-line text-white text-xl"></i>
                                </div>
                                <div class="ml-4">
                                    <p class="text-sm text-gray-600 dark:text-gray-400">Avg Order Value</p>
                                    <p class="text-2xl font-bold text-gray-900 dark:text-white">${{ number_format($orderMetrics['avg_order_value'], 2) }}</p>
                                </div>
                            </div>
                        </div>

                        <div class="card-gradient p-6 rounded-xl">
                            <div class="flex items-center">
                                <div class="p-3 bg-gradient-to-br from-orange-500 to-orange-600 rounded-xl">
                                    <i class="fas fa-truck text-white text-xl"></i>
                                </div>
                                <div class="ml-4">
                                    <p class="text-sm text-gray-600 dark:text-gray-400">Delivery Rate</p>
                                    <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ number_format($orderMetrics['delivery_rate'], 1) }}%</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Order Details Table -->
                    <div class="card-gradient p-6 rounded-xl">
                        <div class="flex items-center justify-between mb-6">
                            <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Order Details</h2>
                            <div class="text-sm text-gray-600 dark:text-gray-400">
                                Showing {{ $orders->firstItem() ?? 0 }} to {{ $orders->lastItem() ?? 0 }} of {{ $orders->total() }} orders
                            </div>
                        </div>
                        
                        <div class="overflow-x-auto">
                            <table class="min-w-full">
                                <thead>
                                    <tr class="border-b border-gray-200 dark:border-gray-600">
                                        <th class="text-left py-3 px-4 text-sm font-medium text-gray-600 dark:text-gray-400">Order ID</th>
                                        <th class="text-left py-3 px-4 text-sm font-medium text-gray-600 dark:text-gray-400">Date</th>
                                        <th class="text-left py-3 px-4 text-sm font-medium text-gray-600 dark:text-gray-400">Status</th>
                                        <th class="text-left py-3 px-4 text-sm font-medium text-gray-600 dark:text-gray-400">Items</th>
                                        <th class="text-left py-3 px-4 text-sm font-medium text-gray-600 dark:text-gray-400">Payment Method</th>
                                        <th class="text-left py-3 px-4 text-sm font-medium text-gray-600 dark:text-gray-400">Amount</th>
                                        <th class="text-left py-3 px-4 text-sm font-medium text-gray-600 dark:text-gray-400">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($orders as $order)
                                    <tr class="border-b border-gray-100 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-700/50">
                                        <td class="py-3 px-4 text-sm text-gray-900 dark:text-white font-medium">#{{ $order->id }}</td>
                                        <td class="py-3 px-4 text-sm text-gray-600 dark:text-gray-400">{{ $order->order_date->format('M d, Y') }}</td>
                                        <td class="py-3 px-4 text-sm">
                                            <span class="px-3 py-1 text-xs rounded-full font-medium
                                                @if($order->status === 'delivered') bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200
                                                @elseif($order->status === 'pending') bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200
                                                @elseif($order->status === 'confirmed') bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200
                                                @elseif($order->status === 'in_production') bg-purple-100 text-purple-800 dark:bg-purple-900 dark:text-purple-200
                                                @elseif($order->status === 'shipped') bg-indigo-100 text-indigo-800 dark:bg-indigo-900 dark:text-indigo-200
                                                @elseif($order->status === 'cancelled') bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200
                                                @else bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-200 @endif">
                                                {{ ucfirst(str_replace('_', ' ', $order->status)) }}
                                            </span>
                                        </td>
                                        <td class="py-3 px-4 text-sm text-gray-600 dark:text-gray-400">
                                            {{ $order->orderItems->count() }} items
                                        </td>
                                        <td class="py-3 px-4 text-sm text-gray-600 dark:text-gray-400 capitalize">{{ $order->payment_method }}</td>
                                        <td class="py-3 px-4 text-sm text-gray-900 dark:text-white font-medium">${{ number_format($order->total_amount, 2) }}</td>
                                        <td class="py-3 px-4 text-sm">
                                            <a href="{{ route('wholesaler.orders.show', $order->id) }}" class="text-purple-600 hover:text-purple-800 dark:text-purple-400 dark:hover:text-purple-300">
                                                <i class="fas fa-eye mr-1"></i>View
                                            </a>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="7" class="py-8 text-center text-gray-500 dark:text-gray-400">
                                            <i class="fas fa-inbox text-4xl mb-4 block"></i>
                                            <p>No orders found for the selected date range.</p>
                                        </td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                        <!-- Pagination -->
                        @if($orders->hasPages())
                        <div class="mt-6">
                            {{ $orders->appends(request()->query())->links() }}
                        </div>
                        @endif
                    </div>

                    <!-- Order Analytics Charts -->
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mt-8">
                        <!-- Order Status Distribution -->
                        <div class="card-gradient p-6 rounded-xl">
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Order Status Distribution</h3>
                            <div class="space-y-3">
                                @php
                                    $statusColors = [
                                        'pending' => 'bg-yellow-500',
                                        'confirmed' => 'bg-blue-500',
                                        'in_production' => 'bg-purple-500',
                                        'shipped' => 'bg-indigo-500',
                                        'delivered' => 'bg-green-500',
                                        'cancelled' => 'bg-red-500'
                                    ];
                                @endphp
                                @foreach($orders->groupBy('status') as $status => $statusOrders)
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center">
                                        <div class="w-3 h-3 rounded-full {{ $statusColors[$status] ?? 'bg-gray-500' }} mr-3"></div>
                                        <span class="text-sm text-gray-600 dark:text-gray-400 capitalize">{{ str_replace('_', ' ', $status) }}</span>
                                    </div>
                                    <div class="flex items-center space-x-2">
                                        <span class="text-sm font-medium text-gray-900 dark:text-white">{{ $statusOrders->count() }}</span>
                                        <span class="text-xs text-gray-500 dark:text-gray-400">({{ number_format(($statusOrders->count() / $orders->count()) * 100, 1) }}%)</span>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        </div>

                        <!-- Payment Method Distribution -->
                        <div class="card-gradient p-6 rounded-xl">
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Payment Method Distribution</h3>
                            <div class="space-y-3">
                                @foreach($orders->groupBy('payment_method') as $method => $methodOrders)
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center">
                                        <div class="w-3 h-3 rounded-full bg-purple-500 mr-3"></div>
                                        <span class="text-sm text-gray-600 dark:text-gray-400 capitalize">{{ $method }}</span>
                                    </div>
                                    <div class="flex items-center space-x-2">
                                        <span class="text-sm font-medium text-gray-900 dark:text-white">{{ $methodOrders->count() }}</span>
                                        <span class="text-xs text-gray-500 dark:text-gray-400">({{ number_format(($methodOrders->count() / $orders->count()) * 100, 1) }}%)</span>
                                    </div>
                                </div>
                                @endforeach
                            </div>
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

        // Close sidebar when clicking outside on mobile
        document.addEventListener('click', function(event) {
            const sidebar = document.getElementById('sidebar');
            const menuToggle = document.getElementById('menu-toggle');
            
            if (window.innerWidth < 768 && 
                !sidebar.contains(event.target) && 
                !menuToggle.contains(event.target)) {
                sidebar.classList.remove('open');
            }
        });
    </script>
</body>
</html> 