<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manufacturer Portal - ChicAura SCM</title>
    <!-- Tailwind CSS via CDN for standalone use, but it's already included in Laravel/Breeze setup -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
    <!-- FontAwesome for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <!-- Chart.js for graphs -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="{{ asset('js/theme-switcher.js') }}"></script>
    <style>
        /* Professional, clean background */
        body { 
            background: #f4f6fa;
            min-height: 100vh;
            margin: 0;
            padding: 0;
        }
        .dark body {
            background: #181f2a;
        }
        /* Sidebar: deep indigo/blue */
        .sidebar { 
            position: fixed;
            top: 0;
            left: 0;
            height: 100vh;
            width: 16rem; /* 64px * 4 = 256px */
            z-index: 30;
            transition: transform 0.3s ease-in-out;
            background: linear-gradient(180deg, #1a237e 0%, #283593 100%);
            box-shadow: 4px 0 15px rgba(0,0,0,0.12);
            overflow-y: auto;
            overflow-x: hidden;
        }
        .dark .sidebar {
            background: linear-gradient(180deg, #0d1333 0%, #1a237e 100%);
        }
        .logo-container {
            background: #fff;
            border-radius: 12px;
            padding: 8px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.06);
        }
        
        .card-gradient, .stat-card {
            background: #fff;
            border: 1px solid #e5e7eb;
            box-shadow: 0 4px 20px rgba(0,0,0,0.04);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        .dark .card-gradient, .dark .stat-card {
            background: #232e3c;
            border: 1px solid #334155;
            color: #f1f5f9;
        }
        .stat-card:hover, .card-gradient:hover {
            transform: translateY(-3px) scale(1.01);
            box-shadow: 0 8px 25px rgba(0,0,0,0.10);
        }
        .nav-link {
            transition: all 0.3s cubic-bezier(.4,0,.2,1);
            border-radius: 12px;
            margin: 4px 0;
        }
        .nav-link:hover {
            background: #e0e7ef;
            color: #232e3c !important;
            transform: translateX(5px);
        }
        .header-gradient {
            position: fixed;
            top: 0;
            left: 16rem;
            right: 0;
            height: 4rem;
            z-index: 40;
            background: #fff;
            box-shadow: 0 2px 20px rgba(0,0,0,0.04);
            display: flex;
            align-items: center;
        }
        .dark .header-gradient {
            background: #232e3c;
            border-color: #334155;
        }
        .main-content-wrapper {
            margin-left: 16rem;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }
        .main-content-scrollable {
            flex: 1 1 0%;
            overflow-y: auto;
            padding: 2rem 1.5rem;
            margin-top: 4rem;
            background: transparent;
        }
        .main-content-visible {
            background: #fff;
            border-radius: 18px;
            box-shadow: 0 6px 32px rgba(40, 53, 147, 0.10);
            padding: 2rem 1.5rem;
            margin: 2rem 0;
            color: #1a237e;
        }
        .dark .main-content-visible {
            background: #232e3c;
            box-shadow: 0 6px 32px rgba(26, 35, 126, 0.18);
            color: #f1f5f9;
        }
        .main-content-visible h1, .main-content-visible h2, .main-content-visible h3, .main-content-visible h4, .main-content-visible h5, .main-content-visible h6 {
            color: #111827;
        }
        .dark .main-content-visible h1, .dark .main-content-visible h2, .dark .main-content-visible h3, .dark .main-content-visible h4, .dark .main-content-visible h5, .dark .main-content-visible h6 {
            color: #fff;
        }
        .main-content-visible p, .main-content-visible span, .main-content-visible td, .main-content-visible th, .main-content-visible div, .main-content-visible li {
            color: #232e3c;
        }
        .dark .main-content-visible p, .dark .main-content-visible span, .dark .main-content-visible td, .dark .main-content-visible th, .dark .main-content-visible div, .dark .main-content-visible li {
            color: #f1f5f9;
        }
        .dark .text-white { color: #f1f5f9; }
        .dark .text-gray-200 { color: #cbd5e1; }
        @media (max-width: 1024px) {
            .main-content-wrapper {
                margin-left: 0;
            }
            .sidebar {
                position: absolute;
                left: 0;
                top: 0;
                width: 16rem;
                z-index: 30;
            }
            .header-gradient {
                left: 0;
            }
            .main-content-visible {
                padding: 1rem 0.5rem;
                margin: 1rem 0;
        }
        }
        @media (max-width: 768px) {
            .sidebar { transform: translateX(-100%); }
            .sidebar.open { transform: translateX(0); }
            .main-content-wrapper { margin-left: 0; }
            .header-gradient { left: 0; }
            .main-content-visible {
                border-radius: 0;
                box-shadow: none;
                margin: 0;
            }
        }
    </style>
</head>
<body class="font-sans antialiased">
    <div>
        <!-- Sidebar -->
        <aside id="sidebar" class="sidebar">
            <div class="flex flex-col h-full">
                <!-- Sidebar Header -->
                <div class="flex items-center justify-center h-16 border-b border-gray-600">
                    <div class="sidebar-logo-blend w-full h-16 flex items-center justify-center p-0 m-0" style="background:#fff;">
                        <img src="{{ asset('images/logo.png') }}" alt="ChicAura Logo" class="w-full h-auto object-contain max-w-[160px] max-h-[48px]">
                    </div>
                </div>
                <div class="px-4 py-4">
                    <h3 class="text-white text-sm font-semibold mb-3 px-3">MANUFACTURER PORTAL</h3>
                </div>
                <!-- Sidebar Navigation -->
                <nav class="flex-1 px-4 py-2 space-y-1">
                    <a href="{{route('manufacturer.dashboard')}}" class="nav-link flex items-center px-3 py-2 text-white bg-gradient-to-r from-indigo-600 to-indigo-700 rounded-xl shadow-lg">
                        <i class="fas fa-home w-5"></i>
                        <span class="ml-2 font-medium text-sm">Home</span>
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

        <div class="main-content-wrapper">
            <!-- Top Navigation Bar -->
            <header class="header-gradient flex items-center justify-between border-b">
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
                        <input type="text" id="manufacturerUniversalSearch" class="w-80 py-2 pl-10 pr-4 border border-gray-300 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-transparent text-sm" placeholder="Search dashboard, stats, activities...">
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
                            <span class="mr-2 text-gray-700 font-medium text-sm">{{ Auth::user()->name ?? 'Admin User' }}</span>
                            <img class="w-7 h-7 rounded-full border-2 border-purple-200 object-cover" 
                                 src="{{ Auth::user()->profile_picture ? asset('storage/profile-pictures/' . basename(Auth::user()->profile_picture)) : asset('images/default-avatar.svg') }}" 
                                 alt="User Avatar">
                        </button>
                    </div>
                    <div class="flex items-center space-x-2">
                        <button type="button" class="p-2 text-gray-500 hover:text-indigo-600 hover:bg-indigo-50 rounded-full transition-colors" title="Edit Profile" x-data x-on:click="$dispatch('open-modal', 'profile-editor-modal')">
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
            <main class="main-content-scrollable">
                <div class="main-content-visible">
                <div class="mb-4">
                    <h2 class="text-2xl font-bold text-white mb-1">Manufacturer Portal</h2>
                    <p class="text-gray-200 text-sm">Welcome back! Here's an overview of your supply chain.</p>
                </div>

                <!-- Quick Stats Row -->
            <div class="flex flex-col md:flex-row gap-4 mb-6 mt-8">
                <div class="flex-1 bg-white rounded-lg shadow p-5 flex items-center gap-4 stat-card">
                        <span class="text-3xl"><i class="fas fa-cubes text-blue-600"></i></span>
                    <div>
                        <div class="text-gray-500 text-sm">Total Raw Materials</div>
                        <div class="text-2xl font-bold">{{ $totalRawMaterials ?? 0 }}</div>
                    </div>
                </div>
                <div class="flex-1 bg-white rounded-lg shadow p-5 flex items-center gap-4 stat-card">
                        <span class="text-3xl"><i class="fas fa-tshirt text-teal-600"></i></span>
                    <div>
                        <div class="text-gray-500 text-sm">Total Products</div>
                        <div class="text-2xl font-bold">{{ $totalProducts ?? 0 }}</div>
                    </div>
                </div>
                <div class="flex-1 bg-white rounded-lg shadow p-5 flex items-center gap-4 stat-card">
                        <span class="text-3xl"><i class="fas fa-users text-amber-600"></i></span>
                    <div>
                        <div class="text-gray-500 text-sm">Total Suppliers</div>
                        <div class="text-2xl font-bold">{{ $totalSuppliers ?? 0 }}</div>
                    </div>
                </div>
                <div class="flex-1 bg-white rounded-lg shadow p-5 flex items-center gap-4 stat-card">
                        <span class="text-3xl"><i class="fas fa-coins text-purple-700"></i></span>
                    <div>
                        <div class="text-gray-500 text-sm">Revenue</div>
                            <div class="text-2xl font-bold">UGX {{ number_format((float)($revenue ?? 0)) }}</div>
                    </div>
                </div>
            </div>
                
                <div class="grid grid-cols-1 gap-4 mt-4 lg:grid-cols-3 h-64">
                    <div class="card-gradient p-4 rounded-xl overflow-hidden lg:col-span-3">
                        <h3 class="text-lg font-bold text-black mb-3">Recent Activities</h3>
                        <div class="space-y-2 h-48 overflow-y-auto">
                            @forelse ($recentActivities ?? [] as $activity)
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
                                    <p class="text-gray-500 text-sm">No recent activities found.</p>
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>

                <!-- Production Overview Section -->
                <div class="mt-8">
                    <h2 class="text-xl font-bold mb-4 text-white">Production Overview</h2>
                    <!-- Modernized Quick Stats Cards -->
                    <div class="flex flex-col md:flex-row gap-4 mb-6">
                        <div class="flex-1 bg-white rounded-lg shadow p-5 flex items-center gap-4">
                                <span class="text-3xl"><i class="fas fa-clipboard-list text-cyan-700"></i></span>
                            <div>
                                <div class="text-gray-500 text-sm">Active Work Orders</div>
                                <div class="text-2xl font-bold">{{ $activeWorkOrders ?? 0 }}</div>
                            </div>
                        </div>
                        <div class="flex-1 bg-white rounded-lg shadow p-5 flex items-center gap-4">
                                <span class="text-3xl"><i class="fas fa-hourglass-half text-amber-700"></i></span>
                            <div>
                                <div class="text-gray-500 text-sm">In Progress</div>
                                <div class="text-2xl font-bold">{{ $inProgress ?? 0 }}</div>
                            </div>
                        </div>
                        <div class="flex-1 bg-white rounded-lg shadow p-5 flex items-center gap-4">
                                <span class="text-3xl"><i class="fas fa-check-circle text-emerald-700"></i></span>
                            <div>
                                <div class="text-gray-500 text-sm">Completed This Month</div>
                                <div class="text-2xl font-bold">{{ $completedThisMonth ?? 0 }}</div>
                            </div>
                        </div>
                    </div>
                    <!-- Modernized Recent Work Orders Table -->
                    <div class="bg-white rounded-lg shadow p-6">
                        <div class="flex justify-between items-center mb-4">
                            <h3 class="text-lg font-semibold">Recent Work Orders</h3>
                            <a href="{{ route('manufacturer.work-orders.index') }}" class="text-blue-600 hover:underline">View All</a>
                        </div>
                        <div class="overflow-x-auto">
                            <table class="min-w-full text-sm">
                                <thead>
                                    <tr>
                                        <th class="font-bold">Order</th>
                                        <th class="font-bold">Product</th>
                                        <th class="font-bold">Quantity</th>
                                        <th class="font-bold">Status</th>
                                        <th class="font-bold">Scheduled</th>
                                        <th class="font-bold">Progress</th>
                                        <th class="font-bold">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($workOrders ?? [] as $order)
                                    <tr class="hover:bg-gray-50 cursor-pointer" onclick="window.location='{{ route('manufacturer.production.show', ['production' => $order->id]) }}'">
                                        <td>{{ $order->id }}</td>
                                        <td>{{ $order->product->name ?? '-' }}</td>
                                        <td>{{ $order->quantity }}</td>
                                        <td>
                                            <span class="px-2 py-1 rounded-full text-xs font-semibold
                                                @if($order->status == 'Planned') bg-blue-100 text-blue-700
                                                @elseif($order->status == 'InProgress') bg-orange-100 text-orange-700
                                                @elseif($order->status == 'Completed') bg-green-100 text-green-700
                                                @else bg-gray-200 text-gray-700
                                                @endif">
                                                {{ $order->status }}
                                            </span>
                                        </td>
                                        <td>{{ optional($order->scheduled_start)->format('M d, Y') }}</td>
                                        <td>
                                            <div class="w-24 bg-gray-200 rounded-full h-3">
                                                <div class="h-3 rounded-full
                                                    @if(($order->progress ?? 0) == 0) bg-gray-400
                                                    @elseif(($order->progress ?? 0) < 100) bg-blue-500
                                                    @else bg-green-500
                                                    @endif"
                                                    style="width: {{ $order->progress ?? 0 }}%">
                                                </div>
                                            </div>
                                            <span class="text-xs text-gray-600 ml-1">{{ $order->progress ?? 0 }}%</span>
                                        </td>
                                        <td>
                                            <a href="{{ route('manufacturer.production.show', ['production' => $order->id]) }}" class="text-blue-600 hover:underline flex items-center gap-1">
                                                <i class="fas fa-eye"></i> Details
                                            </a>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="7" class="text-center text-gray-400 py-4">No work orders found.</td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <!-- Modernized Quick Links -->
                    <div class="flex flex-col md:flex-row gap-4 mt-6">
                        <a href="{{ route('manufacturer.production.create') }}" class="flex-1 bg-indigo-600 hover:bg-indigo-700 text-white font-semibold py-3 rounded-lg flex items-center justify-center gap-2">
                                <i class="fas fa-plus text-blue-600 bg-white rounded-full p-1"></i> New Work Order
                        </a>
                        <a href="{{ route('manufacturer.bom.index') }}" class="flex-1 bg-green-600 hover:bg-green-700 text-white font-semibold py-3 rounded-lg flex items-center justify-center gap-2">
                                <i class="fas fa-cogs text-teal-600 bg-white rounded-full p-1"></i> Bill of Materials
                        </a>
                        <a href="{{ route('manufacturer.quality.index') }}" class="flex-1 bg-yellow-500 hover:bg-yellow-600 text-white font-semibold py-3 rounded-lg flex items-center justify-center gap-2">
                                <i class="fas fa-clipboard-check text-amber-600 bg-white rounded-full p-1"></i> Quality Checks
                        </a>
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

        // Removed Production Chart JS

        // Search functionality for dashboard stat cards and recent activities
    </script>

    {{-- Profile Editor Modal --}}
    <x-profile-editor-modal />

    <script>
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