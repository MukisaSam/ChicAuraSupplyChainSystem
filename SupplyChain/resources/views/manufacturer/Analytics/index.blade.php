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
    <div>
        <!-- Sidebar -->
        <aside id="sidebar" class="sidebar" style="position: fixed; top: 0; left: 0; height: 100vh; width: 16rem; z-index: 30; overflow-y: auto; overflow-x: hidden; background: linear-gradient(180deg, #1a237e 0%, #283593 100%); box-shadow: 4px 0 15px rgba(0,0,0,0.12);">
            <div class="flex flex-col h-full">
            <div class="flex items-center justify-center h-16 border-b border-gray-600">
                    <div class="sidebar-logo-blend w-full h-16 flex items-center justify-center p-0 m-0" style="background:#fff;">
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
                        <i class="fas fa-coins w-5"></i>
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

        <div class="main-content-wrapper" style="margin-left: 16rem; min-height: 100vh; display: flex; flex-direction: column;">
            <!-- Top Navigation Bar -->
            <header class="header-gradient relative z-10 flex items-center justify-between h-16 border-b" style="position: fixed; left: 16rem; right: 0; top: 0; height: 4rem; background: #fff; box-shadow: 0 2px 20px rgba(0,0,0,0.04); display: flex; align-items: center;">
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
                    <!-- Profile Dropdown -->
                    <div class="relative">
                        <button class="flex items-center focus:outline-none bg-white rounded-full p-2 shadow-md hover:shadow-lg transition-shadow">
                            <span class="mr-2 text-gray-700 font-medium text-sm">{{ Auth::user()->name ?? 'Admin User' }}</span>
                            <img class="w-7 h-7 rounded-full border-2 border-purple-200 object-cover" 
                                 src="{{ Auth::user()->profile_picture ? asset('storage/profile-pictures/' . basename(Auth::user()->profile_picture)) : asset('images/default-avatar.svg') }}" 
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
            <main class="main-content-scrollable" style="flex: 1 1 0%; overflow-y: auto; padding: 2rem 1.5rem; margin-top: 4rem; background: transparent;">
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
                                <!-- <p class="text-xs text-indigo-600 mt-1">↗ +8% this month</p> -->
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
                                <!-- <p class="text-xs text-green-600 mt-1">↗ +12% this month</p> -->
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
                                <!-- <p class="text-xs text-yellow-600 mt-1">↗ +5% this month</p> -->
                            </div>
                        </div>
                    </div>
                    <div class="stat-card p-4 rounded-xl">
                        <div class="flex items-center">
                            <div class="p-3 bg-gradient-to-br from-purple-500 to-purple-600 rounded-xl shadow-lg">
                                <i class="fas fa-coins text-white text-xl"></i>
                            </div>
                            <div class="ml-3">
                                <p class="text-xs font-medium text-gray-600">Revenue</p>
                                <p class="text-2xl font-bold text-gray-800">UGX {{ $analytics['stats']['revenue'] }}</p>
                                <!-- <p class="text-xs text-purple-600 mt-1">↗ +15% this month</p> -->
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Additional Stats -->
                <div class="grid grid-cols-1 gap-4 mt-4 sm:grid-cols-3 mb-3">
                    <div class="stat-card p-4 rounded-xl">
                        <div class="flex items-center">
                            <div class="p-3 bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl shadow-lg">
                                <i class="fas fa-users text-white text-xl"></i>
                            </div>
                            <div class="ml-3">
                                <p class="text-xs font-medium text-gray-600">Wholesalers</p>
                                <p class="text-2xl font-bold text-gray-800">{{ $analytics['stats']['wholesalers'] }}</p>
                                <!-- <p class="text-xs text-blue-600 mt-1">↗ +3% this month</p> -->
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
                                <!-- <p class="text-xs text-red-600 mt-1">↘ -2% this month</p> -->
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
                                <!-- <p class="text-xs text-teal-600 mt-1">↗ +7% this month</p> -->
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Charts Section -->
                <div class="card-gradient p-4 rounded-xl lg:col-span-2">
                    <div class="flex items-center justify-between mb-3">
                        <h3 class="text-lg font-bold text-black">Purchase Quantity Trends</h3>
                        <div id="product-name" class="text-sm font-medium text-gray-600">Loading product data...</div>
                    </div>
                    <div class="relative" style="height: 300px;">
                        <!-- Navigation arrows -->
                        <button id="prev-product" class="absolute left-0 top-1/2 transform -translate-y-1/2 bg-white dark:bg-gray-700 rounded-full p-2 shadow-md z-10 hover:bg-gray-100 dark:hover:bg-gray-600 transition-colors">
                            <i class="fas fa-chevron-left text-gray-700 dark:text-gray-200"></i>
                        </button>
                        <button id="next-product" class="absolute right-0 top-1/2 transform -translate-y-1/2 bg-white dark:bg-gray-700 rounded-full p-2 shadow-md z-10 hover:bg-gray-100 dark:hover:bg-gray-600 transition-colors">
                            <i class="fas fa-chevron-right text-gray-700 dark:text-gray-200"></i>
                        </button>
                        <canvas id="productionChart"></canvas>
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
                        <h3 class="text-lg font-bold text-black mb-3">Orders per Month (wholesaler count)</h3>
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
                                            <i class="fas fa-coin"></i> Unit Price (UGX)
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
                <div class="grid grid-cols-1 gap-4 mt-4">
                    <!-- Enhanced ML Supplier Insights Section -->
                    <div class="card-gradient rounded-xl p-6 shadow-lg">
                        <div class="flex items-center justify-between mb-4">
                            <div class="flex items-center gap-3">
                                <div class="p-2 bg-indigo-100 rounded-lg">
                                    <i class="fas fa-brain text-indigo-600 text-xl"></i>
                                </div>
                                <div>
                                    <h3 class="text-xl font-bold text-black">AI-Powered Supplier Insights</h3>
                                    <p class="text-sm text-gray-600">Machine learning analysis of supplier performance</p>
                                </div>
                            </div>
                            <div class="flex items-center gap-2">
                                @if(isset($analytics['mlSupplierInsights']['last_updated']))
                                    <span class="text-xs text-gray-500">
                                        Last updated: {{ date('M j, Y g:i A', $analytics['mlSupplierInsights']['last_updated']) }}
                                    </span>
                                @endif
                                <button class="refresh-individual-btn bg-indigo-600 text-white px-4 py-2 rounded-lg hover:bg-indigo-700 transition-colors text-sm" data-model="supplier">
                                    <i class="fas fa-sync-alt mr-1"></i> Refresh
                                </button>
                                
                            </div>
                        </div>

                        @if(isset($analytics['mlSupplierInsights']['error']))
                            <div class="bg-yellow-100 border border-yellow-400 text-yellow-700 px-4 py-3 rounded mb-4">
                                <i class="fas fa-exclamation-triangle mr-2"></i>
                                {{ $analytics['mlSupplierInsights']['error'] }}
                                @if(isset($analytics['mlSupplierInsights']['message']))
                                    <br><small>{{ $analytics['mlSupplierInsights']['message'] }}</small>
                                @endif
                            </div>
                        @else
                            <!-- Summary Statistics -->
                            <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
                                <div class="bg-white rounded-lg p-4 shadow-sm">
                                    <div class="flex items-center justify-between">
                                        <div>
                                            <p class="text-sm font-medium text-gray-600">Total Suppliers</p>
                                            <p class="text-2xl font-bold text-gray-900">{{ $analytics['mlSupplierInsights']['summary']['total_suppliers'] ?? 0 }}</p>
                                        </div>
                                        <i class="fas fa-users text-blue-500 text-2xl"></i>
                                    </div>
                                </div>
                                <div class="bg-white rounded-lg p-4 shadow-sm">
                                    <div class="flex items-center justify-between">
                                        <div>
                                            <p class="text-sm font-medium text-gray-600">Avg Performance</p>
                                            <p class="text-2xl font-bold text-gray-900">{{ number_format($analytics['mlSupplierInsights']['summary']['avg_performance_score'] ?? 0, 1) }}%</p>
                                        </div>
                                        <i class="fas fa-chart-line text-green-500 text-2xl"></i>
                                    </div>
                                </div>
                                <div class="bg-white rounded-lg p-4 shadow-sm">
                                    <div class="flex items-center justify-between">
                                        <div>
                                            <p class="text-sm font-medium text-gray-600">Excellent</p>
                                            <p class="text-2xl font-bold text-green-600">{{ $analytics['mlSupplierInsights']['summary']['excellent_suppliers'] ?? 0 }}</p>
                                        </div>
                                        <i class="fas fa-star text-yellow-500 text-2xl"></i>
                                    </div>
                                </div>
                                <div class="bg-white rounded-lg p-4 shadow-sm">
                                    <div class="flex items-center justify-between">
                                        <div>
                                            <p class="text-sm font-medium text-gray-600">Poor</p>
                                            <p class="text-2xl font-bold text-red-600">{{ $analytics['mlSupplierInsights']['summary']['poor_suppliers'] ?? 0 }}</p>
                                        </div>
                                        <i class="fas fa-exclamation-triangle text-red-500 text-2xl"></i>
                                    </div>
                                </div>
                            </div>

                            <!-- Top Performers and Underperformers -->
                            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                                <!-- Top Performers -->
                                <div class="bg-white rounded-lg p-4 shadow-sm">
                                    <h4 class="font-semibold text-gray-800 mb-3 flex items-center">
                                        <i class="fas fa-trophy text-yellow-500 mr-2"></i>
                                        Top Performers
                                    </h4>
                                    <div class="space-y-2">
                                        @if(isset($analytics['mlSupplierInsights']['top_performers']['suppliers']))
                                            @foreach(array_slice($analytics['mlSupplierInsights']['top_performers']['suppliers'], 0, 5) as $supplier)
                                                <div class="flex items-center justify-between p-3 bg-green-50 rounded-lg">
                                                    <div>
                                                        <p class="font-medium text-sm text-gray-800">{{ $supplier['supplier_name'] }}</p>
                                                        <p class="text-xs text-gray-600">{{ $supplier['performance_tier'] }}</p>
                                                    </div>
                                                    <div class="text-right">
                                                        <p class="font-bold text-green-600">{{ number_format($supplier['overall_performance_score'], 1) }}%</p>
                                                    </div>
                                                </div>
                                            @endforeach
                                        @else
                                            <p class="text-gray-500 text-sm">No top performers data available</p>
                                        @endif
                                    </div>
                                </div>

                                <!-- Underperformers -->
                                <div class="bg-white rounded-lg p-4 shadow-sm">
                                    <h4 class="font-semibold text-gray-800 mb-3 flex items-center">
                                        <i class="fas fa-exclamation-triangle text-red-500 mr-2"></i>
                                        Needs Improvement
                                    </h4>
                                    <div class="space-y-2">
                                        @if(isset($analytics['mlSupplierInsights']['underperformers']['suppliers']))
                                            @foreach(array_slice($analytics['mlSupplierInsights']['underperformers']['suppliers'], 0, 5) as $supplier)
                                                <div class="flex items-center justify-between p-3 bg-red-50 rounded-lg">
                                                    <div>
                                                        <p class="font-medium text-sm text-gray-800">{{ $supplier['supplier_name'] }}</p>
                                                        <p class="text-xs text-gray-600">{{ $supplier['performance_tier'] }}</p>
                                                    </div>
                                                    <div class="text-right">
                                                        <p class="font-bold text-red-600">{{ number_format($supplier['overall_performance_score'], 1) }}%</p>
                                                    </div>
                                                </div>
                                            @endforeach
                                        @else
                                            <p class="text-gray-500 text-sm">No underperformers data available</p>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <!-- Recommendations -->
                            @if(isset($analytics['mlSupplierInsights']['recommendations']) && !empty($analytics['mlSupplierInsights']['recommendations']))
                                <div class="bg-white rounded-lg p-4 shadow-sm mt-4">
                                    <h4 class="font-semibold text-gray-800 mb-3 flex items-center">
                                        <i class="fas fa-lightbulb text-yellow-500 mr-2"></i>
                                        AI Recommendations
                                    </h4>
                                    <div class="space-y-2">
                                        @foreach($analytics['mlSupplierInsights']['recommendations'] as $recommendation)
                                            <div class="flex items-start p-3 bg-blue-50 rounded-lg">
                                                <i class="fas fa-arrow-right text-blue-500 mt-1 mr-2"></i>
                                                <p class="text-sm text-gray-700">{{ $recommendation }}</p>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endif

                            <!-- Alerts -->
                            @if(isset($analytics['mlSupplierInsights']['alerts']) && !empty($analytics['mlSupplierInsights']['alerts']))
                                <div class="bg-white rounded-lg p-4 shadow-sm mt-4">
                                    <h4 class="font-semibold text-gray-800 mb-3 flex items-center">
                                        <i class="fas fa-bell text-red-500 mr-2"></i>
                                        Critical Alerts
                                    </h4>
                                    <div class="space-y-2">
                                        @foreach($analytics['mlSupplierInsights']['alerts'] as $alert)
                                            <div class="flex items-start p-3 bg-red-50 rounded-lg border-l-4 border-red-500">
                                                <i class="fas fa-exclamation-circle text-red-500 mt-1 mr-2"></i>
                                                <p class="text-sm text-gray-700">{{ $alert }}</p>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endif
                        @endif
                    </div>

                    <!-- Traditional Supplier Performance Table (Optional - for comparison) -->
                    <div class="">

                    <!-- Wholesaler Segmentation Table (ML-Powered) -->
        <div class="card-gradient rounded-xl p-4">
            <div class="flex items-center justify-between mb-3">
                <div class="flex items-center gap-2">
                    <i class="fas fa-users text-indigo-600"></i>
                    <h3 class="text-lg font-bold text-black">AI-Powered Wholesaler Segmentation</h3>
                </div>
                <button id="refreshSegmentationBtn" class="bg-indigo-600 text-white px-3 py-1 rounded-lg hover:bg-indigo-700 transition-colors text-xs">
                    <i class="fas fa-sync-alt mr-1"></i> Refresh
                </button>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full text-sm">
                    <thead>
                        <tr class="bg-gray-100">
                            <th class="py-2 px-3 text-left">Wholesaler Name</th>
                            <th class="py-2 px-3 text-left">Segment</th>
                            <th class="py-2 px-3 text-left">Business Type</th>
                            <th class="py-2 px-3 text-left">Total Orders</th>
                            <th class="py-2 px-3 text-left">Total Spent (Ugx)</th>
                            <th class="py-2 px-3 text-left">Avg Order Value (Ugx)</th>
                            <th class="py-2 px-3 text-left">Recency (Days)</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if(isset($analytics['wholesalerSegmentation']['segments']) && !empty($analytics['wholesalerSegmentation']['segments']))
                            @foreach($analytics['wholesalerSegmentation']['segments'] as $segment)
                                @foreach($segment['wholesalers'] as $wholesaler)
                                    <tr class="border-b hover:bg-gray-50">
                                        <td class="py-2 px-3 text-black font-medium">{{ $wholesaler['customer_name'] }}</td>
                                        <td class="py-2 px-3">
                                            <span class="px-2 py-1 rounded text-xs font-medium
                                                @if($segment['name'] == 'Premium Wholesalers') bg-purple-100 text-purple-800
                                                @elseif($segment['name'] == 'Active Regular Buyers') bg-green-100 text-green-800
                                                @elseif($segment['name'] == 'At-Risk/Dormant') bg-red-100 text-red-800
                                                @else bg-yellow-100 text-yellow-800
                                                @endif">
                                                {{ $segment['name'] }}
                                            </span>
                                        </td>
                                        <td class="py-2 px-3 text-gray-700">{{ $wholesaler['business_type'] ?? 'N/A' }}</td>
                                        <td class="py-2 px-3 text-black">{{ number_format($wholesaler['total_orders']) }}</td>
                                        <td class="py-2 px-3 text-black">{{ number_format($wholesaler['total_spent'], 0) }}</td>
                                        <td class="py-2 px-3 text-black">{{ number_format($wholesaler['avg_order_value'], 0) }}</td>
                                        <td class="py-2 px-3">
                                            <span class="
                                                @if($wholesaler['recency'] <= 30) text-green-600
                                                @elseif($wholesaler['recency'] <= 90) text-yellow-600
                                                @else text-red-600
                                                @endif">
                                                {{ number_format($wholesaler['recency']) }}
                                            </span>
                                        </td>
                                    </tr>
                                @endforeach
                            @endforeach
                        @else
                            <tr class="border-b">
                                <td colspan="7" class="py-8 px-3 text-gray-500 text-center">
                                    <div class="flex flex-col items-center">
                                        <i class="fas fa-chart-pie text-gray-400 text-2xl mb-2"></i>
                                        <p class="text-sm">No wholesaler segmentation data available</p>
                                        <button onclick="refreshWholesalerSegmentation()" class="mt-2 bg-indigo-600 text-white px-4 py-2 rounded-lg hover:bg-indigo-700 transition-colors text-xs">
                                            <i class="fas fa-magic mr-1"></i> Generate Segmentation
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @endif
                    </tbody>
                </table>
            </div>
            
            <!-- Segment Summary -->
            @if(isset($analytics['wholesalerSegmentation']['summary']) && !empty($analytics['wholesalerSegmentation']['summary']))
                <div class="mt-4 grid grid-cols-1 md:grid-cols-4 gap-3">
                    @foreach($analytics['wholesalerSegmentation']['summary'] as $segment)
                        <div class="bg-white rounded-lg p-3 shadow-sm border">
                            <div class="flex items-center justify-between mb-1">
                                <span class="text-xs font-medium text-gray-600">{{ $segment['name'] }}</span>
                                <span class="text-xs px-2 py-1 rounded
                                    @if($segment['name'] == 'Premium Wholesalers') bg-purple-100 text-purple-600
                                    @elseif($segment['name'] == 'Active Regular Buyers') bg-green-100 text-green-600
                                    @elseif($segment['name'] == 'At-Risk/Dormant') bg-red-100 text-red-600
                                    @else bg-yellow-100 text-yellow-600
                                    @endif">
                                    {{ $segment['count'] }}
                                </span>
                            </div>
                            <p class="text-xs text-gray-500">{{ $segment['percentage'] }}% of total</p>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
        

                    <!-- Customer Segmentation Table (Keep existing) -->
                    </div>
                </div>

                <!-- ML System Refresh Section -->
                <div class="card-gradient p-6 rounded-xl mt-4 shadow-lg">
                            <div class="flex items-center justify-between mb-4">
                                <div class="flex items-center gap-3">
                            <div class="p-2 bg-purple-100 rounded-lg">
                                <i class="fas fa-cogs text-purple-600 text-xl"></i>
                                    </div>
                                    <div>
                                <h3 class="text-xl font-bold text-black">ML System Management</h3>
                                <p class="text-sm text-gray-600">Retrain models and refresh AI-powered insights</p>
                                    </div>
                                </div>
                            </div>
                            
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
                        <div class="bg-white rounded-lg p-4 shadow-sm">
                            <div class="flex items-center gap-3 mb-2">
                                <i class="fas fa-brain text-indigo-600"></i>
                                <span class="font-medium text-gray-800">Demand Model</span>
                                </div>
                            <p class="text-sm text-gray-600">Retrain demand forecasting model with latest data</p>
                                                    </div>
                        <div class="bg-white rounded-lg p-4 shadow-sm">
                            <div class="flex items-center gap-3 mb-2">
                                <i class="fas fa-chart-line text-green-600"></i>
                                <span class="font-medium text-gray-800">Supplier Performance</span>
                                                </div>
                            <p class="text-sm text-gray-600">Analyze supplier performance with ML algorithms</p>
                                            </div>
                        <div class="bg-white rounded-lg p-4 shadow-sm">
                            <div class="flex items-center gap-3 mb-2">
                                <i class="fas fa-lightbulb text-yellow-600"></i>
                                <span class="font-medium text-gray-800">Recommendations</span>
                                </div>
                            <p class="text-sm text-gray-600">Generate AI-powered business recommendations</p>
                                                                </div>
                                                                </div>
                    
                    <div class="flex flex-col sm:flex-row gap-4">
                        <button id="refreshAllModelsBtn" class="flex-1 bg-gradient-to-r from-purple-600 to-indigo-600 text-white py-3 px-6 rounded-lg hover:from-purple-700 hover:to-indigo-700 transition-all duration-300 flex items-center justify-center gap-2 font-semibold">
                            <span id="refreshAllBtnText">
                                <i class="fas fa-sync-alt mr-2"></i>
                                Refresh All ML Systems
                                                            </span>
                            <span id="refreshAllBtnLoader" class="hidden">
                                <i class="fas fa-spinner fa-spin mr-2"></i>
                                Processing...
                                                            </span>
                                                                </button>
                        <button id="refreshIndividualBtn" class="bg-gray-600 text-white py-3 px-6 rounded-lg hover:bg-gray-700 transition-colors flex items-center justify-center gap-2">
                            <i class="fas fa-cog"></i>
                            Individual Refresh
                                                                </button>
                    </div>
                    
                    <!-- Individual Refresh Options (Initially Hidden) -->
                    <div id="individualRefreshOptions" class="hidden mt-4 grid grid-cols-1 md:grid-cols-3 gap-4">
                        <button class="refresh-individual-btn bg-indigo-600 text-white py-2 px-4 rounded-lg hover:bg-indigo-700 transition-colors text-sm" data-model="demand">
                            <i class="fas fa-brain mr-2"></i>
                            Retrain Demand Model
                                                                    </button>
                        <button class="refresh-individual-btn bg-green-600 text-white py-2 px-4 rounded-lg hover:bg-green-700 transition-colors text-sm" data-model="supplier">
                            <i class="fas fa-chart-line mr-2"></i>
                            Refresh Supplier Analysis
                        </button>
                        <!-- <button class="refresh-individual-btn bg-yellow-600 text-white py-2 px-4 rounded-lg hover:bg-yellow-700 transition-colors text-sm" data-model="recommendations">
                            <i class="fas fa-lightbulb mr-2"></i>
                            Generate Recommendations
                        </button> -->
                        <button class="refresh-individual-btn bg-yellow-600 text-white py-2 px-4 rounded-lg hover:bg-yellow-700 transition-colors text-sm" data-model="wholesaler">
                            <i class="fas fa-users mr-2"></i>
                             Wholesaler Segmentation
                        </button>
                                                            </div>
                    
                    <!-- Progress Section -->
                    <div id="mlRefreshProgress" class="hidden mt-4">
                        <div class="bg-white rounded-lg p-4 shadow-sm">
                            <h4 class="font-semibold text-gray-800 mb-3">Processing Status</h4>
                            <div id="progressSteps" class="space-y-2"></div>
                            <div class="mt-4">
                                <div class="bg-gray-200 rounded-full h-2">
                                    <div id="progressBar" class="bg-gradient-to-r from-purple-600 to-indigo-600 h-2 rounded-full transition-all duration-500" style="width: 0%"></div>
                                                        </div>
                                <div class="flex justify-between text-xs text-gray-600 mt-1">
                                    <span>Starting...</span>
                                    <span id="progressPercent">0%</span>
                                </div>
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

        // Theme toggle
        // document.querySelector('[data-theme-toggle]').addEventListener('click', function() {
        //     document.documentElement.classList.toggle('dark');
        //     const icon = this.querySelector('i');
        //     if (document.documentElement.classList.contains('dark')) {
        //         icon.classList.remove('fa-moon');
        //         icon.classList.add('fa-sun');
        //     } else {
        //         icon.classList.remove('fa-sun');
        //         icon.classList.add('fa-moon');
        //     }
        // });

        // Fetch and render charts with real data
function renderAnalyticsCharts() {
    // Product navigation state
    let productData = [];
    let currentProductIndex = 0;
    let productionChart = null;

    // Fetch product data specifically
    fetch("{{ route('manufacturer.analytics.chart-data') }}?type=products")
        .then(response => response.json())
        .then(data => {
            // Store product data
            productData = data.products || [];
            
            if (productData.length > 0) {
                // Display first product
                updateProductChart(currentProductIndex);
                document.getElementById('product-name').textContent = productData[currentProductIndex].name;
            } else {
                document.getElementById('product-name').textContent = "No product data available";
            }
        })
        .catch(error => {
            console.error("Error fetching product data:", error);
            document.getElementById('product-name').textContent = "Error loading product data";
        });

    // Fetch time series data for revenue and orders charts
    fetch("{{ route('manufacturer.analytics.chart-data') }}")
        .then(response => response.json())
        .then(data => {
            // Revenue Chart
            new Chart(document.getElementById('revenueChart'), {
                // Existing revenue chart configuration
                type: 'bar',
                data: {
                    labels: data.timeData.labels,
                    datasets: [{
                        label: 'Revenue',
                        data: data.timeData.revenue_data,
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
                // Existing orders chart configuration
                type: 'bar',
                data: {
                    labels: data.timeData.labels,
                    datasets: [{
                        label: 'Orders',
                        data: data.timeData.orders_data,
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
        })
        .catch(error => {
            console.error("Error fetching time series data:", error);
        });

    // Function to update the product chart
    // function updateProductChart(index) {
    //     if (productData.length === 0) return;
        
    //     // Ensure index is within bounds
    //     if (index < 0) index = productData.length - 1;
    //     if (index >= productData.length) index = 0;
        
    //     currentProductIndex = index;
    //     const product = productData[currentProductIndex];
        
    //     // Update product name display
    //     document.getElementById('product-name').textContent = product.name;
        
    //     // Create or update chart
    //     const ctx = document.getElementById('productionChart').getContext('2d');
        
    //     const chartConfig = {
    //         type: 'line',
    //         data: {
    //             labels: product.dates,
    //             datasets: [{
    //                 label: 'Quantity',
    //                 data: product.quantities,
    //                 borderColor: '#6366f1',
    //                 backgroundColor: 'rgba(99,102,241,0.1)',
    //                 fill: true,
    //                 tension: 0.4,
    //             }]
    //         },
    //         options: {
    //             responsive: true,
    //             maintainAspectRatio: false,
    //             animation: {
    //                 duration: 800
    //             },
    //             plugins: {
    //                 legend: {
    //                     display: false
    //                 },
    //                 tooltip: {
    //                     callbacks: {
    //                         title: function(tooltipItems) {
    //                             return `${product.name} - ${tooltipItems[0].label}`;
    //                         }
    //                     }
    //                 }
    //             },
    //             scales: {
    //                 y: {
    //                     beginAtZero: true,
    //                     title: {
    //                         display: true,
    //                         text: 'Quantity'
    //                     },
    //                     grid: {
    //                         color: 'rgba(0,0,0,0.1)'
    //                     }
    //                 },
    //                 x: {
    //                     title: {
    //                         display: true,
    //                         text: 'Date'
    //                     },
    //                     grid: {
    //                         color: 'rgba(0,0,0,0.1)'
    //                     }
    //                 }
    //             }
    //         }
    //     };
        
    //     if (productionChart) {
    //         productionChart.data = chartConfig.data;
    //         productionChart.update();
    //     } else {
    //         productionChart = new Chart(ctx, chartConfig);
    //     }
    // }
    function updateProductChart(index) {
    if (productData.length === 0) return;
    
    // Ensure index is within bounds
    if (index < 0) index = productData.length - 1;
    if (index >= productData.length) index = 0;
    
    currentProductIndex = index;
    const product = productData[currentProductIndex];
    
    // Update product name display
    document.getElementById('product-name').textContent = product.name;
    
    // Create or update chart
    const ctx = document.getElementById('productionChart').getContext('2d');
    
    const chartConfig = {
        type: 'line',
        data: {
            labels: product.dates,
            datasets: [{
                label: 'Quantity Ordered',
                data: product.quantities,
                borderColor: '#6366f1',
                backgroundColor: 'rgba(99,102,241,0.1)',
                fill: true,
                tension: 0.4,
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            animation: {
                duration: 800
            },
            plugins: {
                legend: {
                    display: false
                },
                tooltip: {
                    callbacks: {
                        title: function(tooltipItems) {
                            return `${product.name} - ${tooltipItems[0].label}`;
                        }
                    }
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    title: {
                        display: true,
                        text: 'Quantity Ordered'
                    },
                    grid: {
                        color: 'rgba(0,0,0,0.1)'
                    }
                },
                x: {
                    title: {
                        display: true,
                        text: 'Date'
                    },
                    grid: {
                        color: 'rgba(0,0,0,0.1)'
                    }
                }
            }
        }
    };
    
    if (productionChart) {
        productionChart.data = chartConfig.data;
        productionChart.options = chartConfig.options;
        productionChart.update();
    } else {
        productionChart = new Chart(ctx, chartConfig);
    }
}

    // Set up navigation button event listeners
    document.getElementById('prev-product').addEventListener('click', function() {
        updateProductChart(currentProductIndex - 1);
    });
    
    document.getElementById('next-product').addEventListener('click', function() {
        updateProductChart(currentProductIndex + 1);
    });
}

// Make sure to execute when DOM is loaded
document.addEventListener('DOMContentLoaded', renderAnalyticsCharts);

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
                            if (location === 'Countrywide') {
                               option.selected = true; // Set Countrywide as selected by default
                            }
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
                forecastContainer.className = 'flex flex-col gap-8 w-full';
                // forecastContainer.className = 'grid grid-cols-1 lg:grid-cols-2 gap-8';

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

        // Refresh supplier insights functionality
        document.addEventListener('DOMContentLoaded', function() {
            const refreshInsightsBtn = document.getElementById('refreshInsightsBtn');
            
            if (refreshInsightsBtn) {
                refreshInsightsBtn.addEventListener('click', function() {
                    refreshSupplierInsights();
                });
            }
            
            function refreshSupplierInsights() {
                const btn = document.getElementById('refreshInsightsBtn');
                const originalContent = btn.innerHTML;
                
                // Show loading state
                btn.innerHTML = '<i class="fas fa-spinner fa-spin mr-1"></i> Refreshing...';
                btn.disabled = true;
                
                fetch('/manufacturer/analytics/refresh-supplier-insights', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Show success message
                        showNotification('Supplier insights refreshed successfully!', 'success');
                        
                        // Reload the page after a short delay to show updated insights
                        setTimeout(() => {
                            window.location.reload();
                        }, 2000);
                    } else {
                        showNotification(data.message || 'Failed to refresh supplier insights', 'error');
                    }
                })
                .catch(error => {
                    console.error('Error refreshing supplier insights:', error);
                    showNotification('An error occurred while refreshing supplier insights', 'error');
                })
                .finally(() => {
                    // Reset button state
                    btn.innerHTML = originalContent;
                    btn.disabled = false;
                });
            }
            
            function showNotification(message, type) {
                const alertClass = type === 'success' ? 'bg-green-100 border-green-400 text-green-700' : 'bg-red-100 border-red-400 text-red-700';
                const icon = type === 'success' ? 'fa-check-circle' : 'fa-exclamation-triangle';
                
                const notification = document.createElement('div');
                notification.className = `fixed top-4 right-4 z-50 ${alertClass} px-4 py-3 rounded border max-w-md shadow-lg`;
                notification.innerHTML = `
                    <div class="flex items-center">
                        <i class="fas ${icon} mr-2"></i>
                        <span>${message}</span>
                    </div>
                `;
                
                document.body.appendChild(notification);
                
                // Remove notification after 5 seconds
                setTimeout(() => {
                    notification.remove();
                }, 5000);
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

        // ML System Refresh functionality
        document.addEventListener('DOMContentLoaded', function() {
            const refreshAllBtn = document.getElementById('refreshAllModelsBtn');
            const refreshIndividualBtn = document.getElementById('refreshIndividualBtn');
            const individualOptions = document.getElementById('individualRefreshOptions');
            const refreshAllBtnText = document.getElementById('refreshAllBtnText');
            const refreshAllBtnLoader = document.getElementById('refreshAllBtnLoader');
            const mlRefreshProgress = document.getElementById('mlRefreshProgress');
            const progressSteps = document.getElementById('progressSteps');
            const progressBar = document.getElementById('progressBar');
            const progressPercent = document.getElementById('progressPercent');
            
            // Toggle individual refresh options
            refreshIndividualBtn.addEventListener('click', function() {
                individualOptions.classList.toggle('hidden');
                const icon = this.querySelector('i');
                if (individualOptions.classList.contains('hidden')) {
                    icon.className = 'fas fa-cog';
                } else {
                    icon.className = 'fas fa-times';
                }
            });
            
            // Handle individual model refresh
            document.querySelectorAll('.refresh-individual-btn').forEach(btn => {
                btn.addEventListener('click', function() {
                    const model = this.dataset.model;
                    refreshIndividualModel(model, this);
                });
            });
            
            // Handle refresh all models
            refreshAllBtn.addEventListener('click', function() {
                refreshAllModels();
            });
            
            function refreshAllModels() {
                // Show loading state
                refreshAllBtnText.classList.add('hidden');
                refreshAllBtnLoader.classList.remove('hidden');
                refreshAllBtn.disabled = true;
                mlRefreshProgress.classList.remove('hidden');
                individualOptions.classList.add('hidden');
                
                // Initialize progress
                updateProgress(0, 'Initializing ML system refresh...');
                
                const steps = [
                    { name: 'Retraining Demand Model', model: 'demand', progress: 33 },
                    { name: 'Analyzing Supplier Performance', model: 'supplier', progress: 66 },
                    { name: 'Updating Wholesaler Segmentation', model: 'wholesaler', progress: 100 }
                ];
                
                let currentStep = 0;
                
                function processNextStep() {
                    if (currentStep >= steps.length) {
                        // All steps completed
                        updateProgress(100, 'All ML systems refreshed successfully!');
                        showNotification('All ML systems have been refreshed successfully!', 'success');
                        setTimeout(() => {
                            window.location.reload();
                        }, 3000);
                        return;
                    }
                    
                    const step = steps[currentStep];
                    updateProgress(step.progress, step.name);
                    
                    fetch('/manufacturer/analytics/refresh-ml-system', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        },
                        body: JSON.stringify({ 
                            model: step.model,
                            step: currentStep + 1,
                            total_steps: steps.length
                        })
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            addProgressStep(step.name, 'completed');
                            currentStep++;
                            setTimeout(processNextStep, 1000); // Wait 1 second between steps
                        } else {
                            addProgressStep(step.name, 'failed');
                            throw new Error(data.message || 'Failed to process step');
                        }
                    })
                    .catch(error => {
                        console.error('Error refreshing ML system:', error);
                        addProgressStep(step.name, 'failed');
                        showNotification(`Failed to refresh ${step.name}: ${error.message}`, 'error');
                        resetRefreshButton();
                    });
                }
                
                // Start processing
                processNextStep();
            }
            
            function refreshIndividualModel(model, button) {
                const originalContent = button.innerHTML;
                const modelNames = {
                    'demand': 'Demand Model',
                    'supplier': 'Supplier Analysis',
                    'wholesaler': 'Wholesaler Segmentation',
                    // 'recommendations': 'Recommendations'
                };
                
                // Show loading state
                button.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Processing...';
                button.disabled = true;
                
                fetch('/manufacturer/analytics/refresh-ml-system', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({ 
                        model: model,
                        individual: true
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        showNotification(`${modelNames[model]} refreshed successfully!`, 'success');
                        setTimeout(() => {
                            window.location.reload();
                        }, 2000);
                    } else {
                        showNotification(data.message || `Failed to refresh ${modelNames[model]}`, 'error');
                    }
                })
                .catch(error => {
                    console.error('Error refreshing individual model:', error);
                    showNotification(`An error occurred while refreshing ${modelNames[model]}`, 'error');
                })
                .finally(() => {
                    // Reset button state
                    button.innerHTML = originalContent;
                    button.disabled = false;
                });
            }
            
            function updateProgress(percent, message) {
                progressBar.style.width = percent + '%';
                progressPercent.textContent = percent + '%';
                
                const statusText = progressBar.parentElement.parentElement.querySelector('span');
                if (statusText) {
                    statusText.textContent = message;
                }
            }
            
            function addProgressStep(stepName, status) {
                const stepElement = document.createElement('div');
                const iconClass = status === 'completed' ? 'fa-check-circle text-green-600' : 
                                 status === 'failed' ? 'fa-times-circle text-red-600' : 
                                 'fa-spinner fa-spin text-blue-600';
                
                stepElement.className = 'flex items-center gap-2 text-sm';
                stepElement.innerHTML = `
                    <i class="fas ${iconClass}"></i>
                    <span class="text-gray-700">${stepName}</span>
                `;
                
                progressSteps.appendChild(stepElement);
            }
            
            function resetRefreshButton() {
                refreshAllBtnText.classList.remove('hidden');
                refreshAllBtnLoader.classList.add('hidden');
                refreshAllBtn.disabled = false;
            }
            
            function showNotification(message, type) {
                const alertClass = type === 'success' ? 'bg-green-100 border-green-400 text-green-700' : 'bg-red-100 border-red-400 text-red-700';
                const icon = type === 'success' ? 'fa-check-circle' : 'fa-exclamation-triangle';
                
                const notification = document.createElement('div');
                notification.className = `fixed top-4 right-4 z-50 ${alertClass} px-4 py-3 rounded border max-w-md shadow-lg`;
                notification.innerHTML = `
                    <div class="flex items-center">
                        <i class="fas ${icon} mr-2"></i>
                        <span>${message}</span>
                        <button class="ml-4 text-lg" onclick="this.parentElement.parentElement.remove()">×</button>
                    </div>
                `;
                
                document.body.appendChild(notification);
                
                // Remove notification after 10 seconds
                setTimeout(() => {
                    if (notification.parentElement) {
                        notification.remove();
                    }
                }, 10000);
            }
        });

        document.addEventListener('DOMContentLoaded', function() {
            // Base URL for the application
            const baseUrl = window.location.origin + '/ChicAuraSupplyChainSystem/SupplyChain/public';
            const apiUrl = baseUrl + '/manufacturer/analytics/refresh-ml-system';
            
            console.log('API URL:', apiUrl);
        });
        // Add to your existing JavaScript section
        document.addEventListener('DOMContentLoaded', function() {
            // Set up event listener for the refresh segmentation button
            const refreshSegmentationBtn = document.getElementById('refreshSegmentationBtn');
            if (refreshSegmentationBtn) {
                refreshSegmentationBtn.addEventListener('click', function() {
                    refreshWholesalerSegmentation();
                });
            }
        });

        // Function to refresh wholesaler segmentation
        function refreshWholesalerSegmentation() {
            const btn = document.getElementById('refreshSegmentationBtn');
            const originalContent = btn.innerHTML;
            
            // Show loading state
            btn.innerHTML = '<i class="fas fa-spinner fa-spin mr-1"></i> Processing...';
            btn.disabled = true;
            
            fetch('/manufacturer/analytics/refresh-wholesaler-segmentation', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Show success message
                    showNotification('Wholesaler segmentation refreshed successfully!', 'success');
                    
                    // Reload the page after a short delay to show updated segments
                    setTimeout(() => {
                        window.location.reload();
                    }, 2000);
                } else {
                    showNotification(data.message || 'Failed to refresh wholesaler segmentation', 'error');
                }
            })
            .catch(error => {
                console.error('Error refreshing wholesaler segmentation:', error);
                showNotification('An error occurred while refreshing wholesaler segmentation', 'error');
            })
            .finally(() => {
                // Reset button state
                btn.innerHTML = originalContent;
                btn.disabled = false;
            });
        }

        // Helper function to show notifications
        function showNotification(message, type) {
            const alertClass = type === 'success' ? 'bg-green-100 border-green-400 text-green-700' : 'bg-red-100 border-red-400 text-red-700';
            const icon = type === 'success' ? 'fa-check-circle' : 'fa-exclamation-triangle';
            
            const notification = document.createElement('div');
            notification.className = `fixed top-4 right-4 z-50 ${alertClass} px-4 py-3 rounded border max-w-md shadow-lg`;
            notification.innerHTML = `
                <div class="flex items-center">
                    <i class="fas ${icon} mr-2"></i>
                    <span>${message}</span>
                </div>
            `;
            
            document.body.appendChild(notification);
            
            // Remove notification after 5 seconds
            setTimeout(() => {
                notification.remove();
            }, 5000);
        }
    </script>
</body>
</html>