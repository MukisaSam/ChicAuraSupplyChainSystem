{{-- resources/views/supplier/dashboard.blade.php --}}
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Supplier Dashboard - ChicAura SCM</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="{{ asset('js/theme-switcher.js') }}"></script>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <style>
        body {
            background: linear-gradient(135deg, rgba(0,0,0,0.7) 0%, rgba(0,0,0,0.5) 100%), url('{{ asset('images/supplier.jpg') }}');
            background-size: cover;
            background-position: center;
            background-attachmecnt: fixed;
            min-height: 100vh;
            overflow: hidden;
        }

        /* Dark mode styles */
        .dark body {
            background: linear-gradient(135deg, rgba(0,0,0,0.8) 0%, rgba(0,0,0,0.7) 100%), url('{{ asset('images/supplier.jpg') }}');
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
                    <h3 class="text-white text-sm font-semibold mb-3 px-3">SUPPLIER PORTAL</h3>
                </div>
                <nav class="flex-1 px-4 py-2 space-y-1">
                    <a href="{{ route('supplier.dashboard') }}" class="nav-link flex items-center px-3 py-2 {{ request()->routeIs('supplier.dashboard') ? 'text-white bg-gradient-to-r from-green-600 to-green-700 shadow-lg' : 'text-gray-300 hover:bg-gray-700 hover:text-white' }} rounded-xl">
                        <i class="fas fa-home w-5"></i><span class="ml-2 font-medium text-sm">Home</span>
                    </a>
                    <a href="{{ route('supplier.supply-requests.index') }}" class="nav-link flex items-center px-3 py-2 {{ request()->routeIs('supplier.supply-requests.index') ? 'text-white bg-gradient-to-r from-green-600 to-green-700 shadow-lg' : 'text-gray-300 hover:bg-gray-700 hover:text-white' }} rounded-xl">
                        <i class="fas fa-dolly w-5"></i><span class="ml-2 text-sm">Supply Requests</span>
                    </a>
                    <a href="{{ route('supplier.supplied-items.index') }}" class="nav-link flex items-center px-3 py-2 {{ request()->routeIs('supplier.supplied-items.index') || request()->routeIs('supplier.supplied-items.show') ? 'text-white bg-gradient-to-r from-green-600 to-green-700 shadow-lg' : 'text-gray-300 hover:bg-gray-700 hover:text-white' }} rounded-xl">
                        <i class="fas fa-box w-5"></i><span class="ml-2 text-sm">Supplied Items</span>
                    </a>
                    <a href="{{ route('supplier.analytics.index') }}" class="nav-link flex items-center px-3 py-2 {{ request()->routeIs('supplier.analytics.index') ? 'text-white bg-gradient-to-r from-green-600 to-green-700 shadow-lg' : 'text-gray-300 hover:bg-gray-700 hover:text-white' }} rounded-xl">
                        <i class="fas fa-chart-bar w-5"></i><span class="ml-2 text-sm">Analytics</span>
                    </a>
                    <a href="{{ route('supplier.chat.index') }}" class="nav-link flex items-center px-3 py-2 {{ request()->routeIs('supplier.chat.index') ? 'text-white bg-gradient-to-r from-green-600 to-green-700 shadow-lg' : 'text-gray-300 hover:bg-gray-700 hover:text-white' }} rounded-xl">
                        <i class="fas fa-comments w-5"></i><span class="ml-2 text-sm">Chat</span>
                    </a>
                    <a href="{{ route('supplier.reports.index') }}" class="nav-link flex items-center px-3 py-2 {{ request()->routeIs('supplier.reports.index') ? 'text-white bg-gradient-to-r from-green-600 to-green-700 shadow-lg' : 'text-gray-300 hover:bg-gray-700 hover:text-white' }} rounded-xl">
                        <i class="fas fa-file-alt w-5"></i><span class="ml-2 text-sm">Reports</span>
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
                    <button id="menu-toggle" class="md:hidden p-3 text-gray-500 hover:text-gray-700"><i class="fas fa-bars text-lg"></i></button>
                    <div class="relative ml-3 hidden md:block">
                        <span class="absolute inset-y-0 left-0 flex items-center pl-3"><i class="fas fa-search text-gray-400"></i></span>
                        <input type="text" class="w-80 py-2 pl-10 pr-4 border border-gray-300 rounded-xl focus:ring-2 focus:ring-green-500 focus:border-transparent text-sm" placeholder="Search supply requests, items...">
                    </div>
                </div>
                <div class="flex items-center pr-4 space-x-3">
                    <i class="fas fa-bell"></i>
                    <button data-theme-toggle class="p-2 text-gray-500 hover:text-green-600 hover:bg-green-50 rounded-full transition-colors" title="Switch Theme">
                        <i class="fas fa-moon text-lg"></i>
                    </button>
                    <div class="relative">
                        <button class="flex items-center focus:outline-none bg-white rounded-full p-2 shadow-md hover:shadow-lg transition-shadow">
                            <span class="mr-2 text-gray-700 font-medium text-sm">{{ Auth::user()->name ?? 'Supplier User' }}</span>
                            <img class="w-7 h-7 rounded-full border-2 border-green-200 object-cover"
                                 src="{{ Auth::user()->profile_picture ? Storage::disk('public')->url(Auth::user()->profile_picture) : asset('images/default-avatar.svg') }}"
                                 alt="User Avatar">
                        </button>
                    </div>
                    <div class="flex items-center space-x-2">
                        <button type="button" class="p-2 text-gray-500 hover:text-green-600 hover:bg-green-50 rounded-full transition-colors" title="Edit Profile" x-data x-on:click="$dispatch('open-modal', 'profile-editor-modal')">
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
            <main class="flex-1 p-4 overflow-hidden">
                <div id="home" class="mb-4 dashboard-section">
                    <h2 class="text-2xl font-bold text-white mb-1">Supplier Dashboard</h2>
                    <p class="text-gray-200 text-sm">Manage your supply chain operations efficiently.</p>
                    <div class="grid grid-cols-1 gap-4 mt-4 sm:grid-cols-2 lg:grid-cols-4">
                        <div class="stat-card p-4 rounded-xl">
                            <div class="flex items-center">
                                <div class="p-3 bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl shadow-lg">
                                    <i class="fas fa-box-open text-white text-xl"></i>
                                </div>
                                <div class="ml-3">
                                    <p class="text-xs font-medium text-gray-600">Total Supplied</p>
                                    <p class="text-2xl font-bold text-gray-800">{{ $stats['total_supplied'] ?? '0' }}</p>
                                    <p class="text-xs text-green-600 mt-1">↗ +15% this month</p>
                                </div>
                            </div>
                        </div>
                        <div class="stat-card p-4 rounded-xl">
                            <div class="flex items-center">
                                <div class="p-3 bg-gradient-to-br from-green-500 to-green-600 rounded-xl shadow-lg">
                                    <i class="fas fa-star text-white text-xl"></i>
                                </div>
                                <div class="ml-3">
                                    <p class="text-xs font-medium text-gray-600">Quality Rating</p>
                                    <p class="text-2xl font-bold text-gray-800">{{ $stats['rating'] ?? '0' }}</p>
                                    <p class="text-xs text-green-600 mt-1">⭐ Excellent</p>
                                </div>
                            </div>
                        </div>
                        <div class="stat-card p-4 rounded-xl">
                            <div class="flex items-center">
                                <div class="p-3 bg-gradient-to-br from-yellow-500 to-yellow-600 rounded-xl shadow-lg">
                                    <i class="fas fa-inbox text-white text-xl"></i>
                                </div>
                                <div class="ml-3">
                                    <p class="text-xs font-medium text-gray-600">Active Requests</p>
                                    <p class="text-2xl font-bold text-gray-800">{{ $stats['active_requests'] ?? '0' }}</p>
                                    <p class="text-xs text-yellow-600 mt-1">⏳ Processing</p>
                                </div>
                            </div>
                        </div>
                        <div class="stat-card p-4 rounded-xl">
                            <div class="flex items-center">
                                <div class="p-3 bg-gradient-to-br from-purple-500 to-purple-600 rounded-xl shadow-lg">
                                    <i class="fas fa-calendar-alt text-white text-xl"></i>
                                </div>
                                <div class="ml-3">
                                    <p class="text-xs font-medium text-gray-600">Last Supply</p>
                                    <p class="text-2xl font-bold text-gray-800">{{ $stats['last_supply'] ?? 'N/A' }}</p>
                                    <p class="text-xs text-purple-600 mt-1">📅 Recent</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="grid grid-cols-1 gap-4 mt-4 lg:grid-cols-3 h-64">
                        <div class="card-gradient p-4 rounded-xl lg:col-span-2 overflow-hidden">
                            <h3 class="text-lg font-bold text-gray-800 mb-3">Supply Volume (Units)</h3>
                            <canvas id="supplyChart" class="w-full h-48"></canvas>
                        </div>
                        <div class="card-gradient p-4 rounded-xl overflow-hidden">
                            <h3 class="text-lg font-bold text-gray-800 mb-3">Active Supply Requests</h3>
                            <div class="space-y-2 h-48 overflow-y-auto">
                                @forelse ($supplyRequests as $request)
                                    <div class="flex items-center p-3 bg-white rounded-lg shadow-sm hover:shadow-md transition-shadow">
                                        <div class="flex-shrink-0">
                                            <div class="w-10 h-10 flex items-center justify-center rounded-full {{ $request['status_color'] }} bg-opacity-10">
                                                <i class="fas {{ $request['icon'] }} {{ $request['status_color'] }} text-sm"></i>
                                            </div>
                                        </div>
                                        <div class="ml-3 flex-1">
                                            <p class="text-xs font-medium text-gray-900">{{ $request->item->name }}</p>
                                            <p class="text-xs text-gray-500">Request #{{ $request['id'] }}</p>
                                        </div>
                                        <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full {{ $request['status_color'] }} text-white">{{ $request['status'] }}</span>
                                    </div>
                                @empty
                                    <div class="text-center py-6">
                                        <i class="fas fa-inbox text-gray-400 text-2xl mb-2"></i>
                                        <p class="text-gray-500 text-sm">No active requests.</p>
                                    </div>
                                @endforelse
                            </div>
                        </div>
                    </div>
                </div>
                <section id="supply-request" class="mt-10 dashboard-section hidden overflow-y-auto max-h-[80vh]">
                    <div class="w-full px-0 overflow-y-auto">
                        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6 w-full">
                            <div class="flex justify-between flex-wrap items-center border-b border-gray-200 dark:border-gray-700 pb-2 mb-3">
                                <h1 class="text-2xl font-bold">Supply Requests</h1>
                            </div>
                            @if(session('success'))
                                <div class="alert alert-success alert-dismissible fade show" role="alert">
                                    {{ session('success') }}
                                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                                </div>
                            @endif
                            <form method="GET" class="flex flex-wrap gap-4 mb-4">
                                <div>
                                    <label for="status" class="block text-sm font-medium text-gray-700 dark:text-gray-200">Status</label>
                                    <select name="status" id="status" class="form-select mt-1 block w-full">
                                        <option value="">All Status</option>
                                        <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                                        <option value="accepted" {{ request('status') == 'accepted' ? 'selected' : '' }}>Accepted</option>
                                        <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Rejected</option>
                                        <option value="in_progress" {{ request('status') == 'in_progress' ? 'selected' : '' }}>In Progress</option>
                                        <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Completed</option>
                                    </select>
                                </div>
                                <div>
                                    <label for="due_date" class="block text-sm font-medium text-gray-700 dark:text-gray-200">Due Date</label>
                                    <input type="date" name="due_date" id="due_date" class="form-input mt-1 block w-full" value="{{ request('due_date') }}">
                                </div>
                                <div class="flex items-end gap-2">
                                    <button type="submit" class="btn btn-primary">Filter</button>
                                    <a href="{{ route('supplier.supply-requests.index') }}" class="btn btn-secondary">Clear</a>
                                </div>
                            </form>
                            <div class="overflow-x-auto">
                                <div class="overflow-y-auto" style="max-height: 500px;">
                                    <table class="min-w-full divide-y divide-gray-200">
                                        <thead class="bg-gray-50 dark:bg-gray-900">
                                            <tr>
                                                <th class="px-4 py-2">Request ID</th>
                                                <th class="px-4 py-2">Item</th>
                                                <th class="px-4 py-2">Quantity</th>
                                                <th class="px-4 py-2">Due Date</th>
                                                <th class="px-4 py-2">Status</th>
                                                <th class="px-4 py-2">Payment Type</th>
                                                <th class="px-4 py-2">Delivery Method</th>
                                                <th class="px-4 py-2">Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200">
                                            @forelse($supplyRequests as $request)
                                            <tr>
                                                <td class="px-4 py-2">#{{ $request->id }}</td>
                                                <td class="px-4 py-2">
                                                    <strong>{{ $request->item->name }}</strong>
                                                    <br>
                                                    <span class="text-xs text-gray-500">{{ $request->item->description }}</span>
                                                </td>
                                                <td class="px-4 py-2">{{ number_format($request->quantity) }}</td>
                                                <td class="px-4 py-2">
                                                    <span class="{{ $request->due_date->isPast() ? 'text-red-500' : '' }}">
                                                        {{ $request->due_date->format('M d, Y') }}
                                                    </span>
                                                </td>
                                                <td class="px-4 py-2">
                                                    <span class="inline-block px-2 py-1 rounded text-xs font-semibold
                                                        @if($request->status === 'pending') bg-yellow-200 text-yellow-800
                                                        @elseif($request->status === 'accepted' || $request->status === 'approved') bg-green-200 text-green-800
                                                        @elseif($request->status === 'rejected' || $request->status === 'declined') bg-red-200 text-red-800
                                                        @elseif($request->status === 'in_progress') bg-blue-200 text-blue-800
                                                        @elseif($request->status === 'completed') bg-purple-200 text-purple-800
                                                        @else bg-gray-200 text-gray-800 @endif">
                                                        {{ ucfirst(str_replace('_', ' ', $request->status)) }}
                                                    </span>
                                                </td>
                                                <td class="px-4 py-2">
                                                    <span class="inline-block px-2 py-1 rounded text-xs bg-gray-100 text-gray-800">
                                                        {{ ucfirst($request->payment_type) }}
                                                    </span>
                                                </td>
                                                <td class="px-4 py-2">
                                                    <span class="inline-block px-2 py-1 rounded text-xs bg-gray-100 text-gray-800">
                                                        {{ ucfirst($request->delivery_method) }}
                                                    </span>
                                                </td>
                                                <td class="px-4 py-2">
                                                    <a href="{{ route('supplier.supply-requests.show', $request) }}" class="text-blue-600 hover:underline text-sm view-request-btn"><i class="fas fa-eye"></i> View</a>
                                                </td>
                                            </tr>
                                            @empty
                                            <tr>
                                                <td colspan="8" class="text-center py-4 text-gray-500">No supply requests found.</td>
                                            </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            @if($supplyRequests->hasPages())
                                <div class="flex justify-center mt-4">
                                    {{ $supplyRequests->links() }}
                                </div>
                            @endif
                        </div>
                    </div>
                </section>
                <section id="analytics" class="mt-10 dashboard-section hidden overflow-y-auto max-h-[80vh]">
                    <div class="w-full px-0">
                        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6 w-full">
                            <h2 class="text-xl font-bold mb-4">Supplier Analytics</h2>
                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-4 w-full">
                                <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-4 w-full">
                                    <h5 class="font-semibold">Total Supplied</h5>
                                    <p class="text-2xl font-bold">{{ $stats['total_supplied'] ?? 0 }}</p>
                                </div>
                                <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-4 w-full">
                                    <h5 class="font-semibold">Average Rating</h5>
                                    <p class="text-2xl font-bold">{{ number_format($stats['average_rating'], 1) }}</p>
                                </div>
                                <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-4 w-full">
                                    <h5 class="font-semibold">Active Requests</h5>
                                    <p class="text-2xl font-bold">{{ $stats['active_requests'] }}</p>
                                </div>
                                <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-4 w-full">
                                    <h5 class="font-semibold">Total Revenue</h5>
                                    <p class="text-2xl font-bold">${{ number_format($stats['total_revenue'], 2) }}</p>
                                </div>
                            </div>
                            
                            <!-- Charts Section -->
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-8 w-full">
                                <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-4 w-full">
                                    <h5 class="font-semibold mb-2">Revenue Trend</h5>
                                    <canvas id="revenueChart" width="400" height="200"></canvas>
                                </div>
                                <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-4 w-full">
                                    <h5 class="font-semibold mb-2">Quality Rating Trend</h5>
                                    <canvas id="ratingChart" width="400" height="200"></canvas>
                                </div>
                            </div>
                            
                            <!-- Supply Trends Table -->
                            <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-4 overflow-y-auto w-full">
                                <h5 class="mb-0 font-semibold">Supply Trends (Monthly)</h5>
                                <div class="overflow-x-auto">
                                    <table class="min-w-full divide-y divide-gray-200">
                                        <thead>
                                            <tr>
                                                <th class="px-4 py-2">Month</th>
                                                <th class="px-4 py-2">Total Supplied</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($supplyTrends as $trend)
                                            <tr>
                                                <td class="px-4 py-2">{{ DateTime::createFromFormat('!m', $trend->month)->format('F') }}</td>
                                                <td class="px-4 py-2">{{ $trend->total }}</td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
                <section id="reports" class="mt-10 dashboard-section hidden overflow-y-auto max-h-[80vh]">
                    <div class="w-full px-0">
                        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6 w-full">
                            <div class="flex justify-between flex-wrap items-center border-b border-gray-200 dark:border-gray-700 pb-2 mb-3">
                                <h1 class="text-2xl font-bold">Reports</h1>
                                <div class="flex gap-2">
                                    <button type="button" class="btn btn-sm btn-outline-secondary" onclick="window.print()">
                                        <i class="fas fa-print"></i> Print
                                    </button>
                                    <button type="button" class="btn btn-sm btn-outline-secondary">
                                        <i class="fas fa-download"></i> Export PDF
                                    </button>
                                </div>
                            </div>
                            <form method="GET" class="flex flex-wrap gap-4 mb-4">
                                <div>
                                    <label for="report_type" class="block text-sm font-medium text-gray-700 dark:text-gray-200">Report Type</label>
                                    <select name="report_type" id="report_type" class="form-select mt-1 block w-full">
                                        <option value="monthly" {{ request('report_type') == 'monthly' ? 'selected' : '' }}>Monthly Report</option>
                                        <option value="quarterly" {{ request('report_type') == 'quarterly' ? 'selected' : '' }}>Quarterly Report</option>
                                        <option value="yearly" {{ request('report_type') == 'yearly' ? 'selected' : '' }}>Yearly Report</option>
                                    </select>
                                </div>
                                <div>
                                    <label for="start_date" class="block text-sm font-medium text-gray-700 dark:text-gray-200">Start Date</label>
                                    <input type="date" name="start_date" id="start_date" class="form-input mt-1 block w-full" value="{{ request('start_date') }}">
                                </div>
                                <div>
                                    <label for="end_date" class="block text-sm font-medium text-gray-700 dark:text-gray-200">End Date</label>
                                    <input type="date" name="end_date" id="end_date" class="form-input mt-1 block w-full" value="{{ request('end_date') }}">
                                </div>
                                <div class="flex items-end gap-2">
                                    <button type="submit" class="btn btn-primary">Generate Report</button>
                                </div>
                            </form>
                            <div class="overflow-x-auto mb-8">
                                <h5 class="font-semibold mb-2">Monthly Performance Report ({{ date('Y') }})</h5>
                                <table class="min-w-full divide-y divide-gray-200">
                                    <thead>
                                        <tr>
                                            <th class="px-4 py-2">Month</th>
                                            <th class="px-4 py-2">Quantity Supplied</th>
                                            <th class="px-4 py-2">Revenue</th>
                                            <th class="px-4 py-2">Average Rating</th>
                                            <th class="px-4 py-2">Performance</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($monthlyReport as $report)
                                        <tr>
                                            <td class="px-4 py-2">{{ date('F', mktime(0, 0, 0, $report->month, 1)) }}</td>
                                            <td class="px-4 py-2">{{ number_format($report->quantity) }}</td>
                                            <td class="px-4 py-2">${{ number_format($report->revenue, 2) }}</td>
                                            <td class="px-4 py-2">
                                                <div class="flex items-center">
                                                    @for($i = 1; $i <= 5; $i++)
                                                        <i class="fas fa-star {{ $i <= $report->avg_rating ? 'text-yellow-400' : 'text-gray-300' }}"></i>
                                                    @endfor
                                                    <span class="ml-2 text-xs">({{ number_format($report->avg_rating, 1) }})</span>
                                                </div>
                                            </td>
                                            <td class="px-4 py-2">
                                                @php
                                                    $performance = ($report->quantity > 1000) ? 'Excellent' : 
                                                                (($report->quantity > 500) ? 'Good' : 'Average');
                                                    $badgeClass = ($performance == 'Excellent') ? 'bg-green-200 text-green-800' : 
                                                                (($performance == 'Good') ? 'bg-blue-200 text-blue-800' : 'bg-yellow-200 text-yellow-800');
                                                @endphp
                                                <span class="inline-block px-2 py-1 rounded text-xs font-semibold {{ $badgeClass }}">{{ $performance }}</span>
                                            </td>
                                        </tr>
                                        @empty
                                        <tr>
                                            <td colspan="5" class="text-center py-4 text-gray-500">No data available</td>
                                        </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                            <div class="overflow-x-auto mb-8">
                                <h5 class="font-semibold mb-2">Item Performance Report</h5>
                                <table class="min-w-full divide-y divide-gray-200">
                                    <thead>
                                        <tr>
                                            <th class="px-4 py-2">Item</th>
                                            <th class="px-4 py-2">Total Quantity</th>
                                            <th class="px-4 py-2">Average Price</th>
                                            <th class="px-4 py-2">Average Rating</th>
                                            <th class="px-4 py-2">Total Revenue</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($itemReport as $report)
                                        <tr>
                                            <td class="px-4 py-2">
                                                <strong>{{ $report->item->name }}</strong>
                                                <br>
                                                <span class="text-xs text-gray-500">{{ $report->item->description }}</span>
                                            </td>
                                            <td class="px-4 py-2">{{ number_format($report->total_quantity) }}</td>
                                            <td class="px-4 py-2">${{ number_format($report->avg_price, 2) }}</td>
                                            <td class="px-4 py-2">
                                                <div class="flex items-center">
                                                    @for($i = 1; $i <= 5; $i++)
                                                        <i class="fas fa-star {{ $i <= $report->avg_rating ? 'text-yellow-400' : 'text-gray-300' }}"></i>
                                                    @endfor
                                                    <span class="ml-2 text-xs">({{ number_format($report->avg_rating, 1) }})</span>
                                                </div>
                                            </td>
                                            <td class="px-4 py-2">${{ number_format($report->total_quantity * $report->avg_price, 2) }}</td>
                                        </tr>
                                        @empty
                                        <tr>
                                            <td colspan="5" class="text-center py-4 text-gray-500">No data available</td>
                                        </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>                        
                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                                <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-4 text-center">
                                    <h3 class="text-primary text-2xl font-bold">{{ number_format($monthlyReport->sum('quantity')) }}</h3>
                                    <p class="text-gray-600 dark:text-gray-300">Total Items Supplied</p>
                                </div>
                                <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-4 text-center">
                                    <h3 class="text-success text-2xl font-bold">${{ number_format($monthlyReport->sum('revenue'), 2) }}</h3>
                                    <p class="text-gray-600 dark:text-gray-300">Total Revenue</p>
                                </div>
                                <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-4 text-center">
                                    <h3 class="text-warning text-2xl font-bold">{{ number_format($monthlyReport->avg('avg_rating'), 1) }}</h3>
                                    <p class="text-gray-600 dark:text-gray-300">Average Rating</p>
                                </div>
                                <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-4 text-center">
                                    <h3 class="text-info text-2xl font-bold">{{ $itemReport->count() }}</h3>
                                    <p class="text-gray-600 dark:text-gray-300">Items Supplied</p>
                                </div>
                            </div>
                            <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
                            <script>
                            document.addEventListener('DOMContentLoaded', function() {
                                // Revenue Chart
                                const revenueCtx = document.getElementById('revenueChart').getContext('2d');
                                new Chart(revenueCtx, {
                                    type: 'line',
                                    data: {
                                        labels: {!! json_encode($monthlyReport->pluck('month')->map(function($month) { return date('F', mktime(0, 0, 0, $month, 1)); })) !!},
                                        datasets: [{
                                            label: 'Revenue ($)',
                                            data: {!! json_encode($monthlyReport->pluck('revenue')) !!},
                                            borderColor: 'rgb(75, 192, 192)',
                                            backgroundColor: 'rgba(75, 192, 192, 0.2)',
                                            tension: 0.1
                                        }]
                                    },
                                    options: {
                                        responsive: true,
                                        scales: {
                                            y: {
                                                beginAtZero: true
                                            }
                                        }
                                    }
                                });

                                // Rating Chart
                                const ratingCtx = document.getElementById('ratingChart').getContext('2d');
                                new Chart(ratingCtx, {
                                    type: 'bar',
                                    data: {
                                        labels: {!! json_encode($monthlyReport->pluck('month')->map(function($month) { return date('F', mktime(0, 0, 0, $month, 1)); })) !!},
                                        datasets: [{
                                            label: 'Average Rating',
                                            data: {!! json_encode($monthlyReport->pluck('avg_rating')) !!},
                                            backgroundColor: 'rgba(255, 205, 86, 0.8)',
                                            borderColor: 'rgb(255, 205, 86)',
                                            borderWidth: 1
                                        }]
                                    },
                                    options: {
                                        responsive: true,
                                        scales: {
                                            y: {
                                                beginAtZero: true,
                                                max: 5
                                            }
                                        }
                                    }
                                });
                            });
                            </script>
                        </div>
                    </div>
                </section>
                <section id="chat" class="mt-10 dashboard-section hidden overflow-y-auto max-h-[80vh]">
                    <div class="w-full px-0">
                        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6 w-full">
                        <main class="flex-1 p-4">
                <div class="mb-6">
                    <h2 class="text-2xl font-bold text-white mb-1">Chat</h2>
                    <p class="text-gray-200 text-sm">Communicate with manufacturers and support team</p>
                </div>

                <div class="flex h-full gap-6">
                    <!-- Contacts Sidebar -->
                    <div class="w-80 card-gradient rounded-xl flex flex-col">
                        <div class="p-6 border-b border-gray-200 dark:border-gray-600">
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Contacts</h3>
                        </div>

                        <div class="flex-1 overflow-y-auto p-4 contacts-scroll" style="height: calc(100vh - 300px); max-height: 420px;">
                          
                            <!-- Admins -->
                            @if($admins->count() > 0)
                            <div class="mb-6">
                                <h4 class="text-sm font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wide mb-3 px-2">Support Team</h4>
                                @foreach($admins as $admin)
                                <div class="contact-item flex items-center p-4 rounded-xl" 
                                     data-contact-id="{{ $admin->id }}" 
                                     data-contact-name="{{ $admin->name }}"
                                     data-chat-url="{{ route('supplier.chat.show', ['contactId' => $admin->id]) }}">
                                    <div class="relative">
                                        <img src="{{ asset('images/default-avatar.svg') }}" alt="{{ $admin->name }}" class="w-12 h-12 rounded-full border-2 border-purple-200">
                                        <span class="online-indicator absolute -bottom-1 -right-1"></span>
                                    </div>
                                    <div class="ml-4 flex-1">
                                        <h5 class="text-sm font-medium text-gray-900 dark:text-white">{{ $admin->name }}</h5>
                                        <p class="text-xs text-gray-500 dark:text-gray-400">Support Team</p>
                                    </div>
                                    @if(isset($unreadCounts[$admin->id]) && $unreadCounts[$admin->id] > 0)
                                    <span class="unread-badge">{{ $unreadCounts[$admin->id] }}</span>
                                    @endif
                                </div>
                                @endforeach
                            </div>
                            @endif

                            <!-- Manufacturers -->
                            @if($manufacturers->count() > 0)
                            <div class="mb-6">
                                <h4 class="text-sm font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wide mb-3 px-2">Manufacturers</h4>
                                @foreach($manufacturers as $manufacturer)
                                <div class="contact-item flex items-center p-4 rounded-xl" 
                                     data-contact-id="{{ $manufacturer->id }}" 
                                     data-contact-name="{{ $manufacturer->name }}"
                                     data-chat-url="{{ route('supplier.chat.show', ['contactId' => $manufacturer->id]) }}">
                                    <div class="relative">
                                        <img src="{{ asset('images/manufacturer.jpg') }}" alt="{{ $manufacturer->name }}" class="w-12 h-12 rounded-full border-2 border-purple-200">
                                        <span class="online-indicator absolute -bottom-1 -right-1"></span>
                                    </div>
                                    <div class="ml-4 flex-1">
                                        <h5 class="text-sm font-medium text-gray-900 dark:text-white">{{ $manufacturer->name }}</h5>
                                        <p class="text-xs text-gray-500 dark:text-gray-400">Manufacturer</p>
                                    </div>
                                    @if(isset($unreadCounts[$manufacturer->id]) && $unreadCounts[$manufacturer->id] > 0)
                                    <span class="unread-badge">{{ $unreadCounts[$manufacturer->id] }}</span>
                                    @endif
                                </div>
                                @endforeach
                            </div>
                            @endif

                        </div>
                    </div>

                    <!-- Chat Area -->
                    <div class="flex-1 card-gradient rounded-xl flex flex-col">
                        <div id="chat-welcome" class="flex-1 flex items-center justify-center">
                            <div class="text-center">
                                <div class="w-24 h-24 bg-gradient-to-br from-purple-100 to-purple-200 dark:from-purple-900 dark:to-purple-800 rounded-full flex items-center justify-center mx-auto mb-6">
                                    <i class="fas fa-comments text-4xl text-purple-600 dark:text-purple-300"></i>
                                </div>
                                <h3 class="text-xl font-semibold text-gray-900 dark:text-white mb-2">Welcome to Chat</h3>
                                <p class="text-gray-600 dark:text-gray-400">Select a contact from the sidebar to start a conversation</p>
                            </div>
                        </div>

                        <div id="chat-conversation" class="flex-1 flex flex-col" style="display: none;">
                            <!-- Conversation Header -->
                            <div class="p-6 border-b border-gray-200 dark:border-gray-600">
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center">
                                        <img id="contact-avatar" src="" alt="" class="w-12 h-12 rounded-full border-2 border-purple-200">
                                        <div class="ml-4">
                                            <h3 id="contact-name" class="text-lg font-semibold text-gray-900 dark:text-white"></h3>
                                            <p id="contact-role" class="text-sm text-gray-500 dark:text-gray-400"></p>
                                        </div>
                                    </div>
                                    <div class="flex items-center space-x-2">
                                        <span class="online-indicator"></span>
                                        <span class="text-sm text-gray-500 dark:text-gray-400">Online</span>
                                    </div>
                                </div>
                            </div>

                            <!-- Messages Area -->
                            <div id="messages-container" class="flex-1 overflow-y-auto p-6 space-y-4 messages-scroll" style="max-height: 420px; min-height: 200px;">
                                <!-- Messages will be loaded here -->
                            </div>

                            <!-- Message Input -->
                            <div class="p-6 border-t border-gray-200 dark:border-gray-600">
                                <form id="message-form" class="flex items-center space-x-4">
                                    <input type="hidden" id="receiver-id" name="receiver_id">
                                    <div class="flex-1 relative">
                                        <input type="text" id="message-input" name="content" 
                                               placeholder="Type your message..." 
                                               class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-purple-500 focus:border-transparent dark:bg-gray-700 dark:text-white">
                                    </div>
                                    <button type="submit" class="bg-gradient-to-r from-purple-600 to-purple-700 text-white px-6 py-3 rounded-xl hover:from-purple-700 hover:to-purple-800 transition-all duration-300 shadow-lg">
                                        <i class="fas fa-paper-plane"></i>
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>
                </div>
            </div>
        </section>

    <script>
        // Mobile menu toggle
        document.getElementById('menu-toggle').addEventListener('click', function() {
            document.getElementById('sidebar').classList.toggle('open');
        });

        // Supply Volume Chart
        const supplyCtx = document.getElementById('supplyChart').getContext('2d');
        new Chart(supplyCtx, {
            type: 'line',
            data: {
                labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'],
                datasets: [{
                    label: 'Supply Volume',
                    data: [1200, 1900, 3000, 5000, 2000, 3000],
                    borderColor: '#10B981',
                    backgroundColor: 'rgba(16, 185, 129, 0.1)',
                    borderWidth: 3,
                    fill: true,
                    tension: 0.4
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
                            display: false
                        }
                    }
                }
            }
        });

        // SPA-like navigation for dashboard sections
        const sectionIds = ['home', 'supply-request', 'analytics', 'chat', 'reports'];
        document.querySelectorAll('.sidebar .nav-link').forEach(link => {
            link.addEventListener('click', function(e) {
                const hash = this.getAttribute('href').replace('#', '');
                if (sectionIds.includes(hash)) {
                    e.preventDefault();
                    sectionIds.forEach(id => {
                        const el = document.getElementById(id);
                        if (el) el.classList.add('hidden');
                    });
                    const target = document.getElementById(hash);
                    if (target) target.classList.remove('hidden');
                }
            });
        });
        
        document.addEventListener('DOMContentLoaded', function() {
            console.log('Chat page loaded');
            
            const contactItems = document.querySelectorAll('.contact-item');
            const chatWelcome = document.getElementById('chat-welcome');
            const chatConversation = document.getElementById('chat-conversation');
            const messagesContainer = document.getElementById('messages-container');
            const messageForm = document.getElementById('message-form');
            const messageInput = document.getElementById('message-input');
            const receiverIdInput = document.getElementById('receiver-id');
            const contactName = document.getElementById('contact-name');
            const contactRole = document.getElementById('contact-role');
            const contactAvatar = document.getElementById('contact-avatar');
            const searchInput = document.getElementById('search-contacts');

            // CSRF token for AJAX requests
            const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
            console.log('CSRF Token:', csrfToken);

            // Mobile menu toggle
            document.getElementById('menu-toggle').addEventListener('click', function() {
                document.getElementById('sidebar').classList.toggle('open');
            });

            // Handle contact selection
            contactItems.forEach(item => {
                item.addEventListener('click', function() {
                    const chatUrl = this.dataset.chatUrl;
                    if (chatUrl) {
                        window.location.href = chatUrl;
                    }
                });
            });

            // Handle message form submission
            messageForm.addEventListener('submit', function(e) {
                e.preventDefault();
                console.log('Message form submitted');
                
                const content = messageInput.value.trim();
                const receiverId = receiverIdInput.value;

                if (!content || !receiverId) {
                    console.log('No content or receiver ID');
                    return;
                }

                sendMessage(receiverId, content);
                messageInput.value = '';
            });

            // Search contacts
            searchInput.addEventListener('input', function() {
                const searchTerm = this.value.toLowerCase();
                contactItems.forEach(item => {
                    const name = item.dataset.contactName.toLowerCase();
                    if (name.includes(searchTerm)) {
                        item.style.display = 'flex';
                    } else {
                        item.style.display = 'none';
                    }
                });
            });

            // Load messages for a conversation
            function loadMessages(contactId) {
                console.log('Loading messages for contact:', contactId);
                
                fetch(`/supplier/chat/${contactId}/messages`, {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                    .then(response => {
                        console.log('Response status:', response.status);
                        return response.json();
                    })
                    .then(data => {
                        console.log('Messages loaded:', data);
                        messagesContainer.innerHTML = '';
                        if (data.messages && data.messages.length > 0) {
                            data.messages.forEach(message => {
                                appendMessage(message);
                            });
                        } else {
                            messagesContainer.innerHTML = '<div class="text-center text-gray-500 dark:text-gray-400 py-8">No messages yet. Start the conversation!</div>';
                        }
                        scrollToBottom();
                    })
                    .catch(error => {
                        console.error('Error loading messages:', error);
                        messagesContainer.innerHTML = '<div class="text-center text-red-500 py-8">Error loading messages</div>';
                    });
            }

            // Send a message
            function sendMessage(receiverId, content) {
                console.log('Sending message to:', receiverId, 'Content:', content);
                
                fetch('/supplier/chat/send', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken,
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    body: JSON.stringify({
                        receiver_id: receiverId,
                        content: content
                    })
                })
                .then(response => {
                    console.log('Send response status:', response.status);
                    return response.json();
                })
                .then(data => {
                    console.log('Send response:', data);
                    if (data.success) {
                        appendMessage(data.message);
                        scrollToBottom();
                    } else {
                        alert('Failed to send message');
                    }
                })
                .catch(error => {
                    console.error('Error sending message:', error);
                    alert('Error sending message');
                });
            }

            // Mark messages as read
            function markMessagesAsRead(senderId) {
                console.log('Marking messages as read from:', senderId);
                
                fetch('/supplier/chat/mark-read', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken
                    },
                    body: JSON.stringify({
                        sender_id: senderId
                    })
                })
                .then(response => response.json())
                .then(data => {
                    console.log('Mark as read response:', data);
                    if (data.success) {
                        // Remove unread badge
                        const contactItem = document.querySelector(`[data-contact-id="${senderId}"]`);
                        if (contactItem) {
                            const badge = contactItem.querySelector('.unread-badge');
                            if (badge) {
                                badge.remove();
                            }
                        }
                    }
                })
                .catch(error => console.error('Error marking messages as read:', error));
            }

            // Append a message to the conversation
            function appendMessage(message) {
                console.log('Appending message:', message);
                
                const isOwnMessage = message.sender_id == {{ $user->id }};
                const messageHtml = `
                    <div class="flex ${isOwnMessage ? 'justify-end' : 'justify-start'}">
                        <div class="flex ${isOwnMessage ? 'flex-row-reverse' : 'flex-row'} items-end space-x-3 max-w-xs lg:max-w-md">
                            <img src="${isOwnMessage ? '{{ asset('images/default-avatar.svg') }}' : message.sender.role === 'manufacturer' ? '{{ asset('images/manufacturer.png') }}' : '{{ asset('images/default-avatar.svg') }}'}" 
                                 alt="${message.sender.name}" class="w-8 h-8 rounded-full flex-shrink-0 border-2 border-purple-200">
                            <div class="message-bubble ${isOwnMessage ? 'own' : 'other'}">
                                <p class="text-sm">${message.content}</p>
                                <p class="text-xs ${isOwnMessage ? 'text-purple-100' : 'text-gray-500 dark:text-gray-400'} mt-1">
                                    ${new Date(message.created_at).toLocaleTimeString([], {hour: '2-digit', minute:'2-digit'})}
                                </p>
                            </div>
                        </div>
                    </div>
                `;
                messagesContainer.insertAdjacentHTML('beforeend', messageHtml);
            }

            // Scroll to bottom of messages
            function scrollToBottom() {
                messagesContainer.scrollTop = messagesContainer.scrollHeight;
            }

            // Load unread count on page load
            loadUnreadCount();

            // Load unread count
            function loadUnreadCount() {
                fetch('/supplier/chat/unread-count')
                    .then(response => response.json())
                    .then(data => {
                        console.log('Unread count:', data);
                        const badge = document.getElementById('unread-count');
                        if (data.count > 0) {
                            badge.textContent = data.count;
                            badge.style.display = 'inline-block';
                        } else {
                            badge.style.display = 'none';
                        }
                    })
                    .catch(error => console.error('Error loading unread count:', error));
            }

            // Auto-refresh unread count every 30 seconds
            setInterval(loadUnreadCount, 30000);
        });

    </script>


    {{-- Profile Editor Modal --}}
    <x-profile-editor-modal />

    <!-- Modal for SPA Supply Request Details -->
    <div id="supply-request-modal" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50 hidden">
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg w-full max-w-2xl p-6 relative">
            <button id="close-modal-btn" class="absolute top-2 right-2 text-gray-500 hover:text-red-600 text-xl">&times;</button>
            <div id="modal-content">
                <!-- AJAX-loaded content goes here -->
                <div class="text-center text-gray-400">Loading...</div>
            </div>
        </div>
    </div>
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const modal = document.getElementById('supply-request-modal');
        const modalContent = document.getElementById('modal-content');
        const closeModalBtn = document.getElementById('close-modal-btn');
        // Open modal and load details
        document.querySelectorAll('.view-request-btn').forEach(btn => {
            btn.addEventListener('click', function(e) {
                e.preventDefault();
                const requestId = this.dataset.requestId;
                modal.classList.remove('hidden');
                modalContent.innerHTML = '<div class="text-center text-gray-400">Loading...</div>';
                fetch(`/supplier/supply-requests/${requestId}`, {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                    .then(res => res.text())
                    .then(html => {
                        // Extract only the content section if full HTML is returned
                        const parser = new DOMParser();
                        const doc = parser.parseFromString(html, 'text/html');
                        const content = doc.querySelector('#supply-request-details') || doc.body;
                        modalContent.innerHTML = content.innerHTML;
                        attachStatusFormHandler(requestId);
                    });
            });
        });
        // Close modal
        closeModalBtn.addEventListener('click', function() {
            modal.classList.add('hidden');
        });
        // Attach AJAX handler for status form
        function attachStatusFormHandler(requestId) {
            const form = modalContent.querySelector('#status-update-form');
            if (form) {
                form.addEventListener('submit', function(e) {
                    e.preventDefault();
                    const formData = new FormData(form);
                    fetch(`/supplier/supply-requests/${requestId}/status`, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                            'Accept': 'application/json',
                            'Content-Type': 'application/json'
                        },
                        body: JSON.stringify({
                            status: formData.get('status'),
                            notes: formData.get('notes') || ''
                        })
                    })
                    .then(res => res.json())
                    .then(data => {
                        if (data.success) {
                            modalContent.innerHTML = `<div class='text-green-600 text-center p-4'>Status updated to <b>${data.status}</b>!</div>`;
                            setTimeout(() => { modal.classList.add('hidden'); window.location.reload(); }, 1200);
                        } else {
                            alert('Failed to update status');
                        }
                    });
                });
            }
        }
    });
    </script>
</body>
</html>
