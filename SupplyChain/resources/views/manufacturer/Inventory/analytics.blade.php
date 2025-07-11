<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inventory Analytics - ChicAura SCM</title>
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
        
        .chart-container {
            position: relative;
            height: 300px;
            width: 100%;
        }
        
        @media (max-width: 768px) {
            .sidebar { transform: translateX(-100%); }
            .sidebar.open { transform: translateX(0); }
        }
    </style>
</head>
<body class="font-sans antialiased">
    <div class="flex h-full">
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
                    <a href="{{route('manufacturer.partners.manage')}}" class="nav-link flex items-center px-3 py-2 text-gray-300 hover:bg-gray-700 hover:text-white rounded-xl">
                        <i class="fas fa-users w-5"></i>
                        <span class="ml-2 text-sm">Partners</span>
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
                        <input type="text" class="w-80 py-2 pl-10 pr-4 border border-gray-300 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-transparent text-sm" placeholder="Search analytics, reports...">
                    </div>
                </div>
                <div class="flex items-center pr-4 space-x-3">
                    <div class="relative">
                        <x-notification-bell />
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
            <main class="flex-1 p-4 overflow-y-auto">
                <div class="mb-4">
                    <h2 class="text-2xl font-bold text-white mb-1">Inventory Analytics</h2>
                    <p class="text-gray-200 text-sm">Comprehensive insights into your inventory performance and trends.</p>
                </div>

                <!-- Analytics Overview Cards -->
                <div class="grid grid-cols-1 gap-4 mb-6 sm:grid-cols-2 lg:grid-cols-4">
                    <div class="stat-card p-4 rounded-xl">
                        <div class="flex items-center">
                            <div class="p-3 bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl shadow-lg">
                                <i class="fas fa-chart-line text-white text-xl"></i>
                            </div>
                            <div class="ml-3">
                                <p class="text-xs font-medium text-gray-600">Total Categories</p>
                                <p class="text-2xl font-bold text-gray-800">{{ $analytics['categoryDistribution']->count() ?? '0' }}</p>
                                <p class="text-xs text-blue-600 mt-1">Active categories</p>
                            </div>
                        </div>
                    </div>
                    <div class="stat-card p-4 rounded-xl">
                        <div class="flex items-center">
                            <div class="p-3 bg-gradient-to-br from-green-500 to-green-600 rounded-xl shadow-lg">
                                <i class="fas fa-dollar-sign text-white text-xl"></i>
                            </div>
                            <div class="ml-3">
                                <p class="text-xs font-medium text-gray-600">Total Value</p>
                                <p class="text-2xl font-bold text-gray-800">${{ number_format($analytics['stockValueByCategory']->sum('total_value'), 2) ?? '0' }}</p>
                                <p class="text-xs text-green-600 mt-1">Inventory worth</p>
                            </div>
                        </div>
                    </div>
                    <div class="stat-card p-4 rounded-xl">
                        <div class="flex items-center">
                            <div class="p-3 bg-gradient-to-br from-yellow-500 to-yellow-600 rounded-xl shadow-lg">
                                <i class="fas fa-exclamation-triangle text-white text-xl"></i>
                            </div>
                            <div class="ml-3">
                                <p class="text-xs font-medium text-gray-600">Low Stock Items</p>
                                <p class="text-2xl font-bold text-gray-800">{{ $analytics['lowStockItems']->count() ?? '0' }}</p>
                                <p class="text-xs text-yellow-600 mt-1">Need attention</p>
                            </div>
                        </div>
                    </div>
                    <div class="stat-card p-4 rounded-xl">
                        <div class="flex items-center">
                            <div class="p-3 bg-gradient-to-br from-purple-500 to-purple-600 rounded-xl shadow-lg">
                                <i class="fas fa-chart-pie text-white text-xl"></i>
                            </div>
                            <div class="ml-3">
                                <p class="text-xs font-medium text-gray-600">Avg Stock Level</p>
                                <p class="text-2xl font-bold text-gray-800">{{ number_format($analytics['lowStockItems']->avg('stock_quantity') ?? 0, 0) }}</p>
                                <p class="text-xs text-purple-600 mt-1">Per item</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Charts Section -->
                <div class="grid grid-cols-1 gap-6 mb-6 lg:grid-cols-2">
                    <!-- Category Distribution Chart -->
                    <div class="card-gradient p-6 rounded-xl">
                        <h3 class="text-lg font-bold text-black mb-4">Inventory by Category</h3>
                        <div class="chart-container">
                            <canvas id="categoryChart"></canvas>
                        </div>
                    </div>

                    <!-- Stock Value by Category -->
                    <div class="card-gradient p-6 rounded-xl">
                        <h3 class="text-lg font-bold text-black mb-4">Stock Value by Category</h3>
                        <div class="chart-container">
                            <canvas id="valueChart"></canvas>
                        </div>
                    </div>
                </div>

                <!-- Stock Movement Chart -->
                <div class="card-gradient p-6 rounded-xl mb-6">
                    <h3 class="text-lg font-bold text-black mb-4">Stock Movement (Last 30 Days)</h3>
                    <div class="chart-container">
                        <canvas id="movementChart"></canvas>
                    </div>
                </div>

                <!-- Low Stock Items Table -->
                <div class="card-gradient rounded-xl">
                    <div class="p-4 border-b border-gray-200">
                        <h3 class="text-lg font-bold text-black">Low Stock Items</h3>
                        <p class="text-sm text-gray-600 mt-1">Items that need immediate attention</p>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Item</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Category</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Current Stock</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Price</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Value</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse($analytics['lowStockItems'] as $item)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <div class="flex-shrink-0 h-10 w-10">
                                                @if($item->image_url)
                                                    <img class="h-10 w-10 rounded-lg object-cover" src="{{ Storage::disk('public')->url($item->image_url) }}" alt="{{ $item->name }}">
                                                @else
                                                    <div class="h-10 w-10 rounded-lg bg-gray-200 flex items-center justify-center">
                                                        <i class="fas fa-box text-gray-400"></i>
                                                    </div>
                                                @endif
                                            </div>
                                            <div class="ml-4">
                                                <div class="text-sm font-medium text-gray-900">{{ $item->name }}</div>
                                                <div class="text-sm text-gray-500">{{ Str::limit($item->description, 30) }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-800">
                                            {{ $item->category }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900">{{ $item->stock_quantity }}</div>
                                        <div class="text-xs text-yellow-600">Critical level</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        ${{ number_format($item->base_price, 2) }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        ${{ number_format($item->stock_quantity * $item->base_price, 2) }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800">
                                            Low Stock
                                        </span>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="6" class="px-6 py-4 text-center text-gray-500">
                                        <div class="flex flex-col items-center py-8">
                                            <i class="fas fa-check-circle text-4xl text-green-300 mb-2"></i>
                                            <p class="text-lg font-medium text-gray-500">No low stock items</p>
                                            <p class="text-sm text-gray-400">All items are well stocked</p>
                                        </div>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
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

        // Category Distribution Chart
        const categoryCtx = document.getElementById('categoryChart');
        if (categoryCtx) {
            new Chart(categoryCtx, {
                type: 'doughnut',
                data: {
                    labels: {!! json_encode($analytics['categoryDistribution']->pluck('category')) !!},
                    datasets: [{
                        data: {!! json_encode($analytics['categoryDistribution']->pluck('count')) !!},
                        backgroundColor: [
                            '#3B82F6', '#10B981', '#F59E0B', '#EF4444', 
                            '#8B5CF6', '#06B6D4', '#84CC16', '#F97316'
                        ],
                        borderWidth: 2,
                        borderColor: '#ffffff'
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
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

        // Stock Value Chart
        const valueCtx = document.getElementById('valueChart');
        if (valueCtx) {
            new Chart(valueCtx, {
                type: 'bar',
                data: {
                    labels: {!! json_encode($analytics['stockValueByCategory']->pluck('category')) !!},
                    datasets: [{
                        label: 'Total Value ($)',
                        data: {!! json_encode($analytics['stockValueByCategory']->pluck('total_value')) !!},
                        backgroundColor: 'rgba(59, 130, 246, 0.8)',
                        borderColor: 'rgb(59, 130, 246)',
                        borderWidth: 1
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
                            ticks: {
                                callback: function(value) {
                                    return '$' + value.toLocaleString();
                                }
                            }
                        }
                    }
                }
            });
        }

        // Stock Movement Chart
        const movementCtx = document.getElementById('movementChart');
        if (movementCtx) {
            // Fetch movement data
            fetch('{{ route("manufacturer.inventory.chart-data") }}')
                .then(response => response.json())
                .then(data => {
                    const dates = data.stockMovement.map(item => item.date);
                    const stockIn = data.stockMovement.map(item => item.stock_in);
                    const stockOut = data.stockMovement.map(item => item.stock_out);

                    new Chart(movementCtx, {
                        type: 'line',
                        data: {
                            labels: dates,
                            datasets: [{
                                label: 'Stock In',
                                data: stockIn,
                                borderColor: 'rgb(34, 197, 94)',
                                backgroundColor: 'rgba(34, 197, 94, 0.1)',
                                tension: 0.4
                            }, {
                                label: 'Stock Out',
                                data: stockOut,
                                borderColor: 'rgb(239, 68, 68)',
                                backgroundColor: 'rgba(239, 68, 68, 0.1)',
                                tension: 0.4
                            }]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            plugins: {
                                legend: {
                                    position: 'top'
                                }
                            },
                            scales: {
                                y: {
                                    beginAtZero: true
                                }
                            }
                        }
                    });
                });
        }
    </script>
</body>
</html> 