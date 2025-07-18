<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Manufacturer Analytics - ChicAura SCM</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="{{ asset('js/theme-switcher.js') }}"></script>
    <style>
        body { 
            position: relative;
            min-height: 100vh;
        }
        .background-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100vw;
            height: 100vh;
            z-index: 0;
            background: linear-gradient(135deg, rgba(0,0,0,0.7) 0%, rgba(0,0,0,0.5) 100%), url('{{ asset('images/manufacturer.png') }}');
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
        }
        .main-content-container {
            position: relative;
            z-index: 1;
        }
        .dashboard-header {
            background: rgba(30, 41, 59, 0.85);
            border-radius: 1rem;
            padding: 1.5rem 2rem;
            margin-bottom: 1.5rem;
            box-shadow: 0 4px 32px rgba(0,0,0,0.15);
        }
        .dashboard-header h2, .dashboard-header p {
            color: #fff !important;
            text-shadow: 0 2px 8px rgba(0,0,0,0.25);
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
            .dashboard-header {
                padding: 1rem;
            }
        }
    </style>
</head>
<body class="font-sans antialiased">
    <div class="background-overlay"></div>
    <div class="flex h-full main-content-container">
        <!-- Sidebar -->
        <aside id="sidebar" class="sidebar absolute md:relative z-20 flex-shrink-0 w-64 md:block">
            <div class="flex flex-col h-full">
                <div class="flex items-center justify-center h-16 border-b border-gray-600">
                    <div class="logo-container">
                        <img src="{{ asset('images/logo.png') }}" alt="ChicAura Logo" class="w-full h-auto object-contain max-w-[160px] max-h-[48px]">
                    </div>
                </div>
                <div class="px-4 py-4">
                    <h3 class="text-white text-sm font-semibold mb-3 px-3">MANUFACTURER PORTAL</h3>
                </div>
                <nav class="flex-1 px-4 py-2 space-y-1">
                    <a href="{{route('manufacturer.dashboard')}}" class="nav-link flex items-center px-3 py-2 text-gray-300 hover:bg-gray-700 hover:text-white rounded-xl">
                        <i class="fas fa-home w-5"></i>
                        <span class="ml-2 text-sm">Home</span>
                    </a>
                    <a href="{{route('manufacturer.orders')}}" class="nav-link flex items-center px-3 py-2 text-gray-300 hover:bg-gray-700 hover:text-white rounded-xl">
                        <i class="fas fa-box w-5"></i>
                        <span class="ml-2 text-sm">Orders</span>
                    </a>
                    <a href="{{route('manufacturer.analytics')}}" class="nav-link flex items-center px-3 py-2 text-white bg-gradient-to-r from-indigo-600 to-indigo-700 rounded-xl shadow-lg">
                        <i class="fas fa-chart-pie w-5"></i>
                        <span class="ml-2 font-medium text-sm">Analytics</span>
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

                    <!-- Production Section -->
                    <div class="mt-6 mb-2">
                        <h4 class="text-gray-400 text-xs font-bold uppercase tracking-wider px-3 mb-1">Production</h4>
                    </div>
                    <a href="{{ route('manufacturer.work-orders.index') }}" class="nav-link flex items-center px-3 py-2 text-gray-300 hover:bg-indigo-700 hover:text-white rounded-xl">
                        <i class="fas fa-cogs w-5"></i>
                        <span class="ml-2 text-sm">Work Orders</span>
                    </a>
                    <a href="{{ route('manufacturer.bom.index') }}" class="nav-link flex items-center px-3 py-2 text-gray-300 hover:bg-indigo-700 hover:text-white rounded-xl">
                        <i class="fas fa-list-alt w-5"></i>
                        <span class="ml-2 text-sm">Bill of Materials</span>
                    </a>
                    <a href="{{ route('manufacturer.production-schedules.index') }}" class="nav-link flex items-center px-3 py-2 text-gray-300 hover:bg-indigo-700 hover:text-white rounded-xl">
                        <i class="fas fa-calendar-alt w-5"></i>
                        <span class="ml-2 text-sm">Production Schedules</span>
                    </a>
                    <a href="{{ route('manufacturer.quality-checks.index') }}" class="nav-link flex items-center px-3 py-2 text-gray-300 hover:bg-indigo-700 hover:text-white rounded-xl">
                        <i class="fas fa-clipboard-check w-5"></i>
                        <span class="ml-2 text-sm">Quality Checks</span>
                    </a>
                    <a href="{{ route('manufacturer.downtime-logs.index') }}" class="nav-link flex items-center px-3 py-2 text-gray-300 hover:bg-indigo-700 hover:text-white rounded-xl">
                        <i class="fas fa-stopwatch w-5"></i>
                        <span class="ml-2 text-sm">Downtime Logs</span>
                    </a>
                    <a href="{{ route('manufacturer.production-costs.index') }}" class="nav-link flex items-center px-3 py-2 text-gray-300 hover:bg-indigo-700 hover:text-white rounded-xl">
                        <i class="fas fa-coins w-5"></i>
                        <span class="ml-2 text-sm">Production Costs</span>
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
            <header class="header-gradient relative z-20 flex items-center justify-between h-16 border-b">
                <div class="flex items-center">
                    <button id="menu-toggle" class="md:hidden p-3 text-gray-500 hover:text-gray-700">
                        <i class="fas fa-bars text-lg"></i>
                    </button>
                    <div class="relative ml-3 hidden md:block z-30">
                        <span class="absolute inset-y-0 left-0 flex items-center pl-3">
                            <i class="fas fa-search text-gray-400"></i>
                        </span>
                        <input type="text" id="manufacturerUniversalSearch" class="w-80 py-2 pl-10 pr-4 border border-gray-300 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-transparent text-sm" placeholder="Search analytics, stats, activities, tables...">
                    </div>
                </div>
                <div class="flex items-center pr-4 space-x-3 z-30">
                    <div class="relative">
                        <x-notification-bell />
                    </div>
                    <button data-theme-toggle class="p-2 text-gray-500 hover:text-indigo-600 hover:bg-indigo-50 rounded-full transition-colors" title="Switch Theme">
                        <i class="fas fa-moon text-lg"></i>
                    </button>
                    <!-- Profile Dropdown -->
                    <div class="relative">
                        <button class="flex items-center focus:outline-none bg-white rounded-full p-2 shadow-md hover:shadow-lg transition-shadow">
                            <span class="mr-2 text-gray-700 font-medium text-sm">{{ Auth::user()->name ?? 'Admin User' }}</span>
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
            <main class="flex-1 p-4">
                <div class="dashboard-header">
                    <h2 class="text-2xl font-bold mb-1">Analytics Dashboard</h2>
                    <p class="text-sm">Comprehensive insights into your supply chain performance.</p>
                </div>

                @if(isset($analytics['error']))
                    <div class="bg-red-100 text-red-700 p-4 rounded mb-6">
                        {{ $analytics['error'] }}
                    </div>
                @endif

                <!-- Stats Cards -->
                <div class="grid grid-cols-1 gap-4 mt-4 sm:grid-cols-2 lg:grid-cols-4">
                    <div class="stat-card p-4 rounded-xl">
                        <div class="flex items-center">
                            <div class="p-3 bg-gradient-to-br from-indigo-500 to-indigo-600 rounded-xl shadow-lg">
                                <i class="fas fa-cogs text-white text-xl"></i>
                            </div>
                            <div class="ml-3">
                                <p class="text-xs font-medium text-gray-600">Raw Materials</p>
                                <p class="text-2xl font-bold text-gray-800">{{ $analytics['stats']['raw_materials'] }}</p>
                                <p class="text-xs text-indigo-600 mt-1">↗ +8% this month</p>
                            </div>
                        </div>
                    </div>
                    <div class="stat-card p-4 rounded-xl">
                        <div class="flex items-center">
                            <div class="p-3 bg-gradient-to-br from-green-500 to-green-600 rounded-xl shadow-lg">
                                <i class="fas fa-tshirt text-white text-xl"></i>
                            </div>
                            <div class="ml-3">
                                <p class="text-xs font-medium text-gray-600">Products</p>
                                <p class="text-2xl font-bold text-gray-800">{{ $analytics['stats']['products'] }}</p>
                                <p class="text-xs text-green-600 mt-1">↗ +12% this month</p>
                            </div>
                        </div>
                    </div>
                    <div class="stat-card p-4 rounded-xl">
                        <div class="flex items-center">
                            <div class="p-3 bg-gradient-to-br from-yellow-500 to-yellow-600 rounded-xl shadow-lg">
                                <i class="fas fa-truck text-white text-xl"></i>
                            </div>
                            <div class="ml-3">
                                <p class="text-xs font-medium text-gray-600">Suppliers</p>
                                <p class="text-2xl font-bold text-gray-800">{{ $analytics['stats']['suppliers'] }}</p>
                                <p class="text-xs text-yellow-600 mt-1">↗ +5% this month</p>
                            </div>
                        </div>
                    </div>
                    <div class="stat-card p-4 rounded-xl">
                        <div class="flex items-center">
                            <div class="p-3 bg-gradient-to-br from-purple-500 to-purple-600 rounded-xl shadow-lg">
                                <i class="fas fa-dollar-sign text-white text-xl"></i>
                            </div>
                            <div class="ml-3">
                                <p class="text-xs font-medium text-gray-600">Revenue</p>
                                <p class="text-2xl font-bold text-gray-800">{{ $analytics['stats']['revenue'] }}</p>
                                <p class="text-xs text-purple-600 mt-1">↗ +15% this month</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Additional Stats -->
                <div class="grid grid-cols-1 gap-4 mt-4 sm:grid-cols-3">
                    <div class="stat-card p-4 rounded-xl">
                        <div class="flex items-center">
                            <div class="p-3 bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl shadow-lg">
                                <i class="fas fa-users text-white text-xl"></i>
                            </div>
                            <div class="ml-3">
                                <p class="text-xs font-medium text-gray-600">Wholesalers</p>
                                <p class="text-2xl font-bold text-gray-800">{{ $analytics['stats']['wholesalers'] }}</p>
                                <p class="text-xs text-blue-600 mt-1">↗ +3% this month</p>
                            </div>
                        </div>
                    </div>
                    <div class="stat-card p-4 rounded-xl">
                        <div class="flex items-center">
                            <div class="p-3 bg-gradient-to-br from-red-500 to-red-600 rounded-xl shadow-lg">
                                <i class="fas fa-clock text-white text-xl"></i>
                            </div>
                            <div class="ml-3">
                                <p class="text-xs font-medium text-gray-600">Pending Orders</p>
                                <p class="text-2xl font-bold text-gray-800">{{ $analytics['stats']['pending_orders'] }}</p>
                                <p class="text-xs text-red-600 mt-1">↘ -2% this month</p>
                            </div>
                        </div>
                    </div>
                    <div class="stat-card p-4 rounded-xl">
                        <div class="flex items-center">
                            <div class="p-3 bg-gradient-to-br from-teal-500 to-teal-600 rounded-xl shadow-lg">
                                <i class="fas fa-truck-fast text-white text-xl"></i>
                            </div>
                            <div class="ml-3">
                                <p class="text-xs font-medium text-gray-600">Supply Requests</p>
                                <p class="text-2xl font-bold text-gray-800">{{ $analytics['stats']['active_supply_requests'] }}</p>
                                <p class="text-xs text-teal-600 mt-1">↗ +7% this month</p>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Charts Section -->
                <div class="grid grid-cols-1 gap-4 mt-4 lg:grid-cols-3">
                    <div class="card-gradient p-4 rounded-xl lg:col-span-2">
                        <h3 class="text-lg font-bold text-black mb-3">Production & Revenue Trends</h3>
                        <div class="relative" style="height: 300px;">
                            <canvas id="productionChart"></canvas>
                        </div>
                    </div>
                    <div class="card-gradient p-4 rounded-xl">
                        <h3 class="text-lg font-bold text-black mb-3">Recent Activities</h3>
                        <div class="space-y-2 max-h-64 overflow-y-auto">
                            @forelse ($analytics['recentActivities'] as $activity)
                                <div class="flex items-start p-3 bg-white rounded-lg shadow-sm hover:shadow-md transition-shadow">
                                    <div class="flex-shrink-0 w-10 h-10 flex items-center justify-center rounded-full {{ $activity['color'] ?? 'bg-blue-100' }} bg-opacity-10">
                                        <i class="fas {{ $activity['icon'] ?? 'fa-info' }} {{ $activity['color'] ?? 'text-blue-600' }} text-sm"></i>
                                    </div>
                                    <div class="ml-3 flex-1">
                                        <p class="text-xs font-medium text-gray-800">{{ $activity['description'] ?? 'No activity' }}</p>
                                        <p class="text-xs text-gray-500 mt-1">{{ $activity['time'] ?? 'N/A' }}</p>
                                    </div>
                                </div>
                            @empty 
                                <div class="text-center py-6">
                                    <i class="fas fa-inbox text-gray-400 text-2xl mb-2"></i>
                                    <p class="text-black text-sm">No recent activities found.</p>
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>

                <!-- Additional Charts -->
                <div class="grid grid-cols-1 gap-4 mt-4 lg:grid-cols-2">
                    <div class="card-gradient p-4 rounded-xl">
                        <h3 class="text-lg font-bold text-black mb-3">Revenue Analysis</h3>
                        <div class="relative" style="height: 300px;">
                            <canvas id="revenueChart"></canvas>
                        </div>
                    </div>
                    <div class="card-gradient p-4 rounded-xl">
                        <h3 class="text-lg font-bold text-black mb-3">Order Volume</h3>
                        <div class="relative" style="height: 300px;">
                            <canvas id="ordersChart"></canvas>
                        </div>
                    </div>
                </div>

                <!-- Demand Forecast Section -->
                <div class="card-gradient p-6 rounded-xl mt-4 shadow-lg">
                    <div class="flex items-center mb-4 gap-2">
                        <span class="text-2xl text-indigo-600"><i class="fas fa-chart-line"></i></span>
                        <h3 class="text-xl font-bold text-black">Demand Forecast</h3>
                        <span class="ml-2 text-gray-400 text-xs" title="Predict demand for a product at a location based on price."><i class="fas fa-info-circle"></i></span>
                    </div>
                    <div id="forecastContainer" class="flex flex-col gap-8 w-full">
                        <!-- Forecast Form -->
                        <div class="space-y-6 w-full">
                            <form id="forecastForm" class="space-y-5 w-full">
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 w-full">
                                    <div class="w-full">
                                        <label for="product_name" class="block text-sm font-medium text-gray-700 mb-1 flex items-center gap-1">
                                            <i class="fas fa-box"></i> Product Name
                                        </label>
                                        <select id="product_name" name="product_name" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500" required>
                                            <option value="">Select Product</option>
                                        </select>
                                    </div>
                                    <div class="w-full">
                                        <label for="unit_price" class="block text-sm font-medium text-gray-700 mb-1 flex items-center gap-1">
                                            <i class="fas fa-dollar-sign"></i> Unit Price ($)
                                            <span class="ml-1 text-gray-400" title="Enter the expected selling price."><i class="fas fa-question-circle"></i></span>
                                        </label>
                                        <input type="number" id="unit_price" name="unit_price" step="0.01" min="0" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500" required>
                                    </div>
                                </div>
                                <div class="w-full">
                                    <label for="location" class="block text-sm font-medium text-gray-700 mb-1 flex items-center gap-1">
                                        <i class="fas fa-map-marker-alt"></i> Location
                                    </label>
                                    <select id="location" name="location" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500" required>
                                        <option value="">Select Location</option>
                                    </select>
                                </div>
                                <button type="submit" id="generateForecastBtn" class="w-full bg-indigo-600 text-white py-3 px-4 rounded-md hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 transition-all flex items-center justify-center gap-2 text-lg font-semibold">
                                    <span id="btnText"><i class="fas fa-magic"></i> Generate Forecast</span>
                                    <span id="btnLoader" class="hidden">
                                        <i class="fas fa-spinner fa-spin mr-2"></i>
                                        Generating...
                                    </span>
                                </button>
                            </form>
                        </div>
                        <!-- Forecast Results and Error remain unchanged, but ensure they use w-full -->
                        <div id="forecastResults" class="hidden w-full">
                            <div class="space-y-4 w-full">
                                <!-- Forecast Details -->
                                <div class="border rounded-lg p-4 bg-white w-full">
                                    <h4 class="font-semibold text-gray-800 mb-2">Forecast Details</h4>
                                    <div id="forecastDetails" class="text-sm text-gray-600"></div>
                                </div>
                                <!-- Daily Forecast -->
                                <div class="border rounded-lg p-4 bg-white w-full">
                                    <h4 class="font-semibold text-gray-800 mb-2">
                                        <i class="fas fa-calendar-day text-blue-600 mr-2"></i>
                                        30-Day Demand Forecast
                                    </h4>
                                    <img id="dailyForecastChart" src="" alt="Daily Demand Forecast Chart" class="w-full h-auto rounded-lg shadow-sm">
                                </div>
                                <!-- Monthly Forecast -->
                                <div class="border rounded-lg p-4 bg-white w-full">
                                    <h4 class="font-semibold text-gray-800 mb-2">
                                        <i class="fas fa-calendar-alt text-green-600 mr-2"></i>
                                        12-Month Demand Forecast
                                    </h4>
                                    <img id="monthlyForecastChart" src="" alt="Monthly Demand Forecast Chart" class="w-full h-auto rounded-lg shadow-sm">
                                </div>
                            </div>
                        </div>
                        <div id="forecastError" class="hidden col-span-2 w-full">
                            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded w-full">
                                <span id="errorMessage"></span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Tables Section -->
                <div class="grid grid-cols-1 gap-4 mt-4 lg:grid-cols-2">
                    <!-- Supplier Performance Table -->
                    <div class="card-gradient rounded-xl p-4 ">
                        <h3 class="text-lg font-bold text-black mb-3">Supplier Performance (Last 6 Months)</h3>
                        <div class="overflow-x-auto">
                            <table class="min-w-full text-sm">
                                <thead>
                                    <tr class="bg-gray-100">
                                        <th class="py-2 px-3 text-left">Name</th>
                                        <th class="py-2 px-3 text-left">On-Time (%)</th>
                                        <th class="py-2 px-3 text-left">Avg. Days</th>
                                        <th class="py-2 px-3 text-left">Rating</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($analytics['suppliers']['suppliers'] as $supplier)
                                        <tr class="border-b">
                                            <td class="py-2 px-3 text-black">{{ $supplier['name'] }}</td>
                                            <td class="py-2 px-3 text-black">{{ $supplier['on_time_delivery_rate'] }}%</td>
                                            <td class="py-2 px-3 text-black">{{ $supplier['avg_delivery_time'] }}</td>
                                            <td class="py-2 px-3 text-black">{{ $supplier['quality_rating'] }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Customer Segmentation Table -->
                    <div class="card-gradient rounded-xl p-4 ">
                        <h3 class="text-lg font-bold text-black mb-3">Wholesaler Segmentation</h3>
                        <div class="overflow-x-auto">
                            <table class="min-w-full text-sm">
                                <thead>
                                    <tr class="bg-gray-100">
                                        <th class="py-2 px-3 text-left">Name</th>
                                        <th class="py-2 px-3 text-left">Segment</th>
                                        <th class="py-2 px-3 text-left">Orders</th>
                                        <th class="py-2 px-3 text-left">Value</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($analytics['customers']['segments'] as $wholesaler)
                                        <tr class="border-b">
                                            <td class="py-2 px-3">{{ $wholesaler['name'] }}</td>
                                            <td class="py-2 px-3"><span class="px-2 py-1 rounded text-xs {{ $wholesaler['color'] }}">{{ $wholesaler['segment'] }}</span></td>
                                            <td class="py-2 px-3">{{ $wholesaler['total_orders'] }}</td>
                                            <td class="py-2 px-3">${{ number_format($wholesaler['total_spent'], 0) }}</td>
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

    <script>
        // Mobile menu toggle
        document.getElementById('menu-toggle').addEventListener('click', function() {
            document.getElementById('sidebar').classList.toggle('open');
        });

        // Theme toggle
        document.querySelector('[data-theme-toggle]').addEventListener('click', function() {
            document.documentElement.classList.toggle('dark');
            const icon = this.querySelector('i');
            if (document.documentElement.classList.contains('dark')) {
                icon.classList.remove('fa-moon');
                icon.classList.add('fa-sun');
            } else {
                icon.classList.remove('fa-sun');
                icon.classList.add('fa-moon');
            }
        });

        // Charts
        const labels = @json($analytics['timeData']['labels']);
        const productionData = @json($analytics['timeData']['production_data']);
        const revenueData = @json($analytics['timeData']['revenue_data']);
        const ordersData = @json($analytics['timeData']['orders_data']);

        // Production Chart
        new Chart(document.getElementById('productionChart'), {
            type: 'line',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Production Volume',
                    data: productionData,
                    borderColor: '#6366f1',
                    backgroundColor: 'rgba(99,102,241,0.1)',
                    fill: true,
                    tension: 0.4,
                }, {
                    label: 'Revenue',
                    data: revenueData.map(val => val / 1000), // Scale down for better visualization
                    borderColor: '#10b981',
                    backgroundColor: 'rgba(16,185,129,0.1)',
                    fill: true,
                    tension: 0.4,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: true,
                        position: 'top'
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: {
                            color: 'rgba(0,0,0,0.1)'
                        }
                    },
                    x: {
                        grid: {
                            color: 'rgba(0,0,0,0.1)'
                        }
                    }
                }
            }
        });

        // Revenue Chart
        new Chart(document.getElementById('revenueChart'), {
            type: 'bar',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Revenue',
                    data: revenueData,
                    backgroundColor: '#10b981',
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
                            color: 'rgba(0,0,0,0.1)'
                        }
                    },
                    x: {
                        grid: {
                            color: 'rgba(0,0,0,0.1)'
                        }
                    }
                }
            }
        });

        // Orders Chart
        new Chart(document.getElementById('ordersChart'), {
            type: 'bar',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Orders',
                    data: ordersData,
                    backgroundColor: '#f59e42',
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
                            color: 'rgba(0,0,0,0.1)'
                        }
                    },
                    x: {
                        grid: {
                            color: 'rgba(0,0,0,0.1)'
                        }
                    }
                }
            }
        });

        // Forecast functionality
        document.addEventListener('DOMContentLoaded', function() {
            const forecastForm = document.getElementById('forecastForm');
            const productSelect = document.getElementById('product_name');
            const unitPriceInput = document.getElementById('unit_price');
            const locationSelect = document.getElementById('location');
            const generateBtn = document.getElementById('generateForecastBtn');
            const btnText = document.getElementById('btnText');
            const btnLoader = document.getElementById('btnLoader');
            const forecastResults = document.getElementById('forecastResults');
            const forecastError = document.getElementById('forecastError');
            const dailyForecastChart = document.getElementById('dailyForecastChart');
            const monthlyForecastChart = document.getElementById('monthlyForecastChart');
            const forecastContainer = document.getElementById('forecastContainer');
            const forecastDetails = document.getElementById('forecastDetails');
            const errorMessage = document.getElementById('errorMessage');

            // Load forecast options on page load
            loadForecastOptions();

            // Handle product selection change
            productSelect.addEventListener('change', function() {
                const selectedOption = this.options[this.selectedIndex];
                if (selectedOption.dataset.price) {
                    unitPriceInput.value = selectedOption.dataset.price;
                }
            });

            // Handle form submission
            forecastForm.addEventListener('submit', function(e) {
                e.preventDefault();
                generateForecast();
            });

            function loadForecastOptions() {
                fetch('/manufacturer/analytics/forecast/options')
                    .then(response => response.json())
                    .then(data => {
                        // Populate products
                        productSelect.innerHTML = '<option value="">Select Product</option>';
                        data.products.forEach(product => {
                            const option = document.createElement('option');
                            option.value = product.name;
                            option.textContent = product.name;
                            option.dataset.price = product.price;
                            productSelect.appendChild(option);
                        });

                        // Populate locations
                        locationSelect.innerHTML = '<option value="">Select Location</option>';
                        data.locations.forEach(location => {
                            const option = document.createElement('option');
                            option.value = location;
                            option.textContent = location;
                            locationSelect.appendChild(option);
                        });
                    })
                    .catch(error => {
                        console.error('Error loading forecast options:', error);
                    });
            }

            function generateForecast() {
                // Show loading state
                btnText.classList.add('hidden');
                btnLoader.classList.remove('hidden');
                generateBtn.disabled = true;
                
                // Hide previous results/errors and reset layout
                forecastResults.classList.add('hidden');
                forecastError.classList.add('hidden');
                forecastContainer.className = 'grid grid-cols-1 lg:grid-cols-2 gap-8';

                const formData = new FormData(forecastForm);
                
                fetch('/manufacturer/analytics/forecast/generate', {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '',
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Show forecast results
                        dailyForecastChart.src = data.daily_image_url;
                        monthlyForecastChart.src = data.monthly_image_url;
                        forecastDetails.innerHTML = `
                            <strong>Product:</strong> ${data.product_name}<br>
                            <strong>Location:</strong> ${data.location}<br>
                            <strong>Unit Price:</strong> $${data.unit_price}<br>
                            <strong>Generated:</strong> ${new Date().toLocaleString()}
                        `;
                        
                        // Change layout to full width for results
                        forecastContainer.className = 'grid grid-cols-1 gap-4';
                        forecastResults.classList.remove('hidden');
                    } else {
                        // Show error
                        errorMessage.textContent = data.error || 'Failed to generate forecast';
                        forecastError.classList.remove('hidden');
                    }
                })
                .catch(error => {
                    console.error('Error generating forecast:', error);
                    errorMessage.textContent = 'An error occurred while generating the forecast';
                    forecastError.classList.remove('hidden');
                })
                .finally(() => {
                    // Reset button state
                    btnText.classList.remove('hidden');
                    btnLoader.classList.add('hidden');
                    generateBtn.disabled = false;
                });
            }
        });

        // Universal search event for all manufacturer pages
        const searchInput = document.getElementById('manufacturerUniversalSearch');
        if (searchInput) {
            searchInput.addEventListener('input', function() {
                const searchTerm = this.value.toLowerCase();
                const event = new CustomEvent('manufacturerUniversalSearch', { detail: { searchTerm } });
                document.dispatchEvent(event);
                console.log('manufacturerUniversalSearch event dispatched:', searchTerm);
            });
        }
        // Enhanced universal search handler
        document.addEventListener('manufacturerUniversalSearch', function(e) {
            const searchTerm = e.detail.searchTerm;
            // Filter stat cards
            document.querySelectorAll('.stat-card').forEach(card => {
                card.style.display = card.textContent.toLowerCase().includes(searchTerm) ? '' : 'none';
            });
            // Filter recent activities
            document.querySelectorAll('.card-gradient .flex.items-start.p-3').forEach(activity => {
                activity.style.display = activity.textContent.toLowerCase().includes(searchTerm) ? '' : 'none';
            });
            // Filter table rows
            document.querySelectorAll('table tbody tr').forEach(row => {
                row.style.display = row.textContent.toLowerCase().includes(searchTerm) ? '' : 'none';
            });
            // Filter contact items (for chat pages)
            document.querySelectorAll('.contact-item').forEach(item => {
                const name = item.dataset.contactName ? item.dataset.contactName.toLowerCase() : '';
                item.style.display = name.includes(searchTerm) ? 'flex' : 'none';
            });
        });
    </script>
</body>
</html> 