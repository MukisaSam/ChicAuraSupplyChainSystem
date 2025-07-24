<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Administrator Dashboard - ChicAura SCM</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    <script src="{{ asset('js/theme-switcher.js') }}"></script>
    <style>
        body {
            background: #f4f4f5;
            box-shadow: 0 4px 12px rgba(0,0,0,0.05);
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
            min-height: 100vh;
            margin: 0;
            padding: 0;
            font-family: 'FigTree', Tahoma, Geneva, Verdana, sans-serif;
        }

        /* Main content container */
        #app-container {
            transition: filter 0.3s ease, opacity 0.3s ease;
        }

        #app-container.blurred {
            filter: blur(4px);
            opacity: 0.7;
            pointer-events: none;
        }

        /* Dark mode styles */
        .dark body {
            background: linear-gradient(135deg, rgba(0, 0, 0, 0.9) 0%, rgba(0, 0, 0, 0.8) 100%), url('https://images.unsplash.com/photo-1519751138087-5bf79df62d5b?q=80&w=2070');
        }
        .sidebar {
            background: linear-gradient(135deg, #ffffff 0%, #f8fafc 100%);
            box-shadow: 4px 0 15px rgba(0,0,0,0.1);
            color: #000;
        }
        .sidebar .nav-link,
        .sidebar h3,
        .sidebar p {
            color: #000 !important;
        }
      
        .dark .sidebar {
            background: linear-gradient(180deg, #0f172a 0%, #1e293b 100%);
        }

        .logo-container {
            background: rgba(255, 255, 255, 0.95);
            border-radius: 12px;
            padding: 8px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        }

        .dark .logo-container {
            background: rgba(255, 255, 255, 0.9);
        }

        .card-gradient {
            background: linear-gradient(135deg, #ffffff 0%, #f8fafc 100%);
            border: 1px solid rgba(255, 255, 255, 0.2);
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
            backdrop-filter: blur(10px);
        }

        .dark .card-gradient {
            background: linear-gradient(135deg, #1e293b 0%, #334155 100%);
            border: 1px solid rgba(255, 255, 255, 0.1);
            color: #f1f5f9;
        }

        .stat-card {
            background: linear-gradient(135deg, #ffffff 0%, #f1f5f9 100%);
            border: 1px solid rgba(255, 255, 255, 0.3);
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .dark .stat-card {
            background: linear-gradient(135deg, #1e293b 0%, #334155 100%);
            border: 1px solid rgba(255, 255, 255, 0.1);
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
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
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
            box-shadow: 0 2px 20px rgba(0, 0, 0, 0.1);
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

        .user-table {
            border-collapse: separate;
            border-spacing: 0;
            width: 100%;
        }

        .user-table th {
            background-color: #f1f5f9;
            position: sticky;
            top: 0;
        }

        .dark .user-table th {
            background-color: #1e293b;
        }

        .user-table tr:last-child td {
            border-bottom: none;
        }

        .modal-overlay {
            background: rgba(0, 0, 0, 0.7);
            backdrop-filter: blur(10px);
            display: flex;
            align-items: center;
            justify-content: center;
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            z-index: 100;
        }

        .modal-content {
            animation: modalOpen 0.4s ease-out;
            max-height: 90vh;
            overflow-y: auto;
            width: 90%;
            max-width: 800px;
            background: white;
            border-radius: 16px;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
        }

        .dark .modal-content {
            background: #1e293b;
            color: #f1f5f9;
        }

        @keyframes modalOpen {
            from {
                opacity: 0;
                transform: translateY(-50px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .permission-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
            gap: 10px;
        }

        .permission-card {
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            padding: 12px;
            transition: all 0.3s ease;
        }

        .permission-card:hover {
            background-color: #f1f5f9;
            transform: translateY(-2px);
        }

        .dark .permission-card {
            border-color: #334155;
        }

        .dark .permission-card:hover {
            background-color: #1e293b;
        }

        .form-input:focus {
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.3);
            outline: none;
        }

        .form-error {
            animation: shake 0.5s;
        }

        @keyframes shake {

            0%,
            100% {
                transform: translateX(0);
            }

            25% {
                transform: translateX(-5px);
            }

            75% {
                transform: translateX(5px);
            }
        }

        .role-badge {
            display: inline-flex;
            align-items: center;
            padding: 4px 10px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 500;
        }

        .role-admin {
            background-color: #dbeafe;
            color: #2563eb;
        }

        .role-supplier {
            background-color: #dcfce7;
            color: #16a34a;
        }

        .role-manufacturer {
            background-color: #fef3c7;
            color: #d97706;
        }

        .role-wholesaler {
            background-color: #ede9fe;
            color: #7c3aed;
        }

        .dark .role-admin {
            background-color: rgba(37, 99, 235, 0.2);
            color: #93c5fd;
        }

        .dark .role-supplier {
            background-color: rgba(22, 163, 74, 0.2);
            color: #86efac;
        }

        .dark .role-manufacturer {
            background-color: rgba(217, 119, 6, 0.2);
            color: #fcd34d;
        }

        .dark .role-wholesaler {
            background-color: rgba(124, 58, 237, 0.2);
            color: #c4b5fd;
        }

        .status-badge {
            display: inline-flex;
            align-items: center;
            padding: 4px 10px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 500;
        }

        .status-active {
            background-color: #dcfce7;
            color: #16a34a;
        }

        .status-pending {
            background-color: #fef3c7;
            color: #d97706;
        }

        .status-suspended {
            background-color: #fee2e2;
            color: #dc2626;
        }

        .dark .status-active {
            background-color: rgba(22, 163, 74, 0.2);
            color: #86efac;
        }

        .dark .status-pending {
            background-color: rgba(217, 119, 6, 0.2);
            color: #fcd34d;
        }

        .dark .status-suspended {
            background-color: rgba(220, 38, 38, 0.2);
            color: #fca5a5;
        }

        .btn-primary {
            background: linear-gradient(135deg, #3B82F6 0%, #2563EB 100%);
            color: white;
            padding: 10px 20px;
            border-radius: 12px;
            font-weight: 600;
            transition: all 0.3s ease;
            box-shadow: 0 4px 6px rgba(37, 99, 235, 0.3);
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 8px rgba(37, 99, 235, 0.4);
        }

        .btn-secondary {
            background: #f1f5f9;
            color: #334155;
            padding: 10px 20px;
            border-radius: 12px;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .btn-secondary:hover {
            background: #e2e8f0;
        }

        .dark .btn-secondary {
            background: #334155;
            color: #f1f5f9;
        }

        .dark .btn-secondary:hover {
            background: #475569;
        }

        .scrollbar-hide::-webkit-scrollbar {
            display: none;
        }

        .scrollbar-hide {
            -ms-overflow-style: none;
            scrollbar-width: none;
        }

        @media (max-width: 768px) {
            .sidebar {
                transform: translateX(-100%);
            }

            .sidebar.open {
                transform: translateX(0);
            }

            .permission-grid {
                grid-template-columns: 1fr;
            }

            .modal-content {
                width: 95%;
            }
        }
    </style>
</head>

<body>
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
                    <a href="{{ route('admin.dashboard') }}" class="nav-link flex items-center px-3 py-2 {{ request()->routeIs('admin.dashboard') ? 'text-white bg-gradient-to-r from-blue-600 to-blue-700 shadow-lg' : 'text-gray-300 hover:bg-blue-700 hover:text-white' }} rounded-xl">
                        <i class="fas fa-tachometer-alt w-5"></i><span class="ml-2 font-medium text-sm">Dashboard</span>
                    </a>
                    <a href="{{ route('admin.users') }}" class="nav-link flex items-center px-3 py-2 {{ request()->routeIs('admin.users') ? 'text-white bg-gradient-to-r from-blue-600 to-blue-700 shadow-lg' : 'text-gray-300 hover:bg-blue-700 hover:text-white' }} rounded-xl">
                        <i class="fas fa-users-cog w-5"></i><span class="ml-2 text-sm">Users Management</span>
                    </a>
                    <a href="{{ route('admin.user_roles.index') }}" class="nav-link flex items-center px-3 py-2 {{ request()->routeIs('admin.user_roles.*') ? 'text-white bg-gradient-to-r from-blue-600 to-blue-700 shadow-lg' : 'text-gray-300 hover:bg-blue-700 hover:text-white' }} rounded-xl">
                        <i class="fas fa-shield-alt w-5"></i><span class="ml-2 text-sm">Roles & Permissions</span>
                    </a>
                    <a href="{{ route('admin.analytics.index') }}" class="nav-link flex items-center px-3 py-2 {{ request()->routeIs('admin.analytics.*') ? 'text-white bg-gradient-to-r from-blue-600 to-blue-700 shadow-lg' : 'text-gray-300 hover:bg-blue-700 hover:text-white' }} rounded-xl">
                        <i class="fas fa-chart-pie w-5"></i><span class="ml-2 text-sm">Analytics</span>
                    </a>
                    <a href="{{ route('admin.audit-logs.index') }}" class="nav-link flex items-center px-3 py-2 {{ request()->routeIs('admin.audit-logs.*') ? 'text-white bg-gradient-to-r from-blue-600 to-blue-700 shadow-lg' : 'text-gray-300 hover:bg-blue-700 hover:text-white' }} rounded-xl">
                        <i class="fas fa-history w-5"></i><span class="ml-2 text-sm">Audit Logs</span>
                    </a>
                    <a href="{{ route('admin.notifications.index') }}" class="nav-link flex items-center px-3 py-2 {{ request()->routeIs('admin.notifications.*') ? 'text-white bg-gradient-to-r from-blue-600 to-blue-700 shadow-lg' : 'text-gray-300 hover:bg-blue-700 hover:text-white' }} rounded-xl">
                        <i class="fas fa-bell w-5"></i><span class="ml-2 text-sm">Notifications</span>
                    </a>
                    <a href="{{ route('admin.settings.index') }}" class="nav-link flex items-center px-3 py-2 {{ request()->routeIs('admin.settings.*') ? 'text-white bg-gradient-to-r from-blue-600 to-blue-700 shadow-lg' : 'text-gray-300 hover:bg-blue-700 hover:text-white' }} rounded-xl">
                        <i class="fas fa-cogs w-5"></i><span class="ml-2 text-sm">System Settings</span>
                    </a>
                    <a href="{{ route('admin.reports.index') }}" class="nav-link flex items-center px-3 py-2 {{ request()->routeIs('admin.reports.*') ? 'text-white bg-gradient-to-r from-blue-600 to-blue-700 shadow-lg' : 'text-gray-300 hover:bg-blue-700 hover:text-white' }} rounded-xl">
                        <i class="fas fa-file-alt w-5"></i><span class="ml-2 text-sm">Reports</span>
                    </a>
                    <a href="{{ route('admin.chat.index') }}" class="nav-link flex items-center px-3 py-2 {{ request()->routeIs('admin.chat.index') || request()->routeIs('admin.chat.show') ? 'text-white bg-gradient-to-r from-blue-600 to-blue-700 shadow-lg' : 'text-gray-300 hover:bg-blue-700 hover:text-white' }} rounded-xl">
                        <i class="fas fa-comments w-5"></i><span class="ml-2 text-sm">Chat</span>
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
                    <button id="menu-toggle" class="md:hidden p-3 text-gray-500 hover:text-gray-700"><i
                            class="fas fa-bars text-lg"></i></button>
                    <div class="relative ml-3 hidden md:block">
                        <span class="absolute inset-y-0 left-0 flex items-center pl-3"><i
                                class="fas fa-search text-gray-400"></i></span>
                        <input type="text"
                            class="w-80 py-2 pl-10 pr-4 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent text-sm"
                            placeholder="Search users, logs, or settings...">
                    </div>
                </div>
                <div class="flex items-center pr-4 space-x-3">
                <div class="flex items-center pr-4 space-x-3">
                    <div class="relative">
                        <a href="{{ route('admin.notifications.index') }}">
                            <i class="fas fa-bell"></i>
                            @php
                                $unreadCount = isset($unreadCount) ? $unreadCount : (Auth::check() ? Auth::user()->unreadNotifications()->count() : 0);
                            @endphp
                            @if($unreadCount > 0)
                                <span class="absolute -top-1 -right-1 bg-red-600 text-white text-xs rounded-full px-1.5 py-0.5">
                                    {{ $unreadCount }}
                                </span>
                            @endif
                        </a>
                    </div>
                                      
                    <div class="relative">
                        <button
                            class="flex items-center focus:outline-none bg-white rounded-full p-2 shadow-md hover:shadow-lg transition-shadow">
                            <span class="mr-2 text-gray-700 font-medium text-sm">{{ Auth::user()->name ?? 'System Admin'
                                }}</span>
                            <img class="w-7 h-7 rounded-full border-2 border-blue-200 object-cover"
                                src="{{ Auth::user()->profile_picture ? Storage::disk('public')->url(Auth::user()->profile_picture) : asset('images/default-avatar.svg') }}"
                                alt="User Avatar">
                        </button>
                    </div>
                    <div class="flex items-center space-x-2">
                        <a href="{{ route('user.profile.edit') }}"
                            class="p-2 text-gray-500 hover:text-blue-600 hover:bg-blue-50 rounded-full transition-colors"
                            title="Edit Profile">
                            <i class="fas fa-user-edit text-lg"></i>
                        </a>
                        <form method="POST" action="{{ route('logout') }}" class="inline">
                            @csrf
                            <button type="submit"
                                class="p-2 text-gray-500 hover:text-red-600 hover:bg-red-50 rounded-full transition-colors"
                                title="Logout">
                                <i class="fas fa-sign-out-alt text-lg"></i>
                            </button>
                        </form>
                    </div>
                </div>
            </header>

            <!-- Main Content -->
            <main class=" flex-1 overflow-y-auto p-6">
                <!-- Page Header -->
                <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6">
                    <div>
                        <h1 class="text-2xl font-bold text-black">User Management</h1>
                        <p class="text-black mt-1">Manage all user accounts and permissions</p>
                    </div>
                    <div class="flex flex-wrap gap-2 mt-4 md:mt-0">
                        <a id="addUserBtn" href="{{route('register.admin')}}" class="btn-primary flex items-center">
                            <i class="fas fa-plus mr-2"></i> Add New User
                        </a>
                        <button class="btn-secondary flex items-center">
                            <i class="fas fa-file-export mr-2"></i> Export
                        </button>
                        <button class="btn-secondary flex items-center">
                            <i class="fas fa-filter mr-2"></i> Filters
                        </button>
                    </div>
                </div>

                <!-- Stats Cards -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-4 mb-6">
                    <div class="stat-card rounded-2xl p-4">
                        <div class="flex justify-between items-center">
                            <div>
                                <p class="text-gray-600 text-sm">Total Users</p>
                                <h3 class="text-2xl font-bold text-gray-800 mt-1">{{$numberofUsers}}</h3>
                            </div>
                            <div class="w-12 h-12 rounded-full bg-indigo-100 flex items-center justify-center">
                                <i class="fas fa-users text-indigo-600"></i>
                            </div>
                        </div>                        
                    </div>

                    <div class="stat-card rounded-2xl p-4">
                        <div class="flex justify-between items-center">
                            <div>
                                <p class="text-gray-600 text-sm">Admins</p>
                                <h3 class="text-2xl font-bold text-gray-800 mt-1">{{$admin}}</h3>
                            </div>
                            <div class="w-12 h-12 rounded-full bg-blue-100 flex items-center justify-center">
                                <i class="fa-solid fa-user-tie text-blue-600"></i>
                            </div>
                        </div>                        
                    </div>

                    <div class="stat-card rounded-2xl p-4">
                        <div class="flex justify-between items-center">
                            <div>
                                <p class="text-gray-600 text-sm">Suppliers</p>
                                <h3 class="text-2xl font-bold text-gray-800 mt-1">{{$supplier}}</h3>
                            </div>
                            <div class="w-12 h-12 rounded-full bg-green-100 flex items-center justify-center">
                                <i class="fas fa-truck-loading text-green-600"></i>
                            </div>
                        </div>        
                    </div>

                    <div class="stat-card rounded-2xl p-4">
                        <div class="flex justify-between items-center">
                            <div>
                                <p class="text-gray-600 text-sm">Manufacturers</p>
                                <h3 class="text-2xl font-bold text-gray-800 mt-1">{{$manufacturer}}</h3>
                            </div>
                            <div class="w-12 h-12 rounded-full bg-yellow-100 flex items-center justify-center">
                                <i class="fas fa-industry text-yellow-600"></i>
                            </div>
                        </div>                        
                    </div>

                    <div class="stat-card rounded-2xl p-4">
                        <div class="flex justify-between items-center">
                            <div>
                                <p class="text-gray-600 text-sm">Wholesalers</p>
                                <h3 class="text-2xl font-bold text-gray-800 mt-1">{{$wholesaler}}</h3>
                            </div>
                            <div class="w-12 h-12 rounded-full bg-purple-100 flex items-center justify-center">
                                <i class="fas fa-boxes text-purple-600"></i>
                            </div>
                        </div>                        
                    </div>
                </div>

                <!-- Charts and Table Section -->
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                    <!-- User Roles Chart -->
                    <div class="lg:col-span-1">
                        <div class="card-gradient rounded-2xl p-4 h-full">
                            <div class="flex justify-between items-center mb-4">
                                <h2 class="text-lg font-semibold">User Distribution</h2>
                                <button class="text-gray-500 hover:text-blue-600">
                                    <i class="fas fa-ellipsis-h"></i>
                                </button>
                            </div>
                            <div class="h-64">
                                <canvas id="userRolesChart"></canvas>
                            </div>
                        </div>
                    </div>

                    <!-- Users Table -->
                    <div class="lg:col-span-2">
                        <div class="card-gradient rounded-2xl p-4">
                            <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-4">
                                <h2 class="text-lg font-semibold">Active Users</h2>
                                <div class="relative mt-2 md:mt-0">
                                    <span class="absolute inset-y-0 left-0 flex items-center pl-3"><i
                                            class="fas fa-search text-gray-400"></i></span>
                                    <input type="text" id="search-activeusers"
                                        class="py-2 pl-10 pr-4 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent text-sm w-full md:w-64"
                                        placeholder="Search users...">
                                </div>
                            </div>

                            <div class="overflow-x-auto">
                                <table class="w-full user-table">
                                    <thead>
                                        <tr class="text-left">
                                            <th class="px-4 py-3 rounded-tl-xl">User</th>
                                            <th class="px-4 py-3">Role</th>
                                            <th class="px-4 py-3">Last Active</th>
                                            <th class="px-4 py-3 rounded-tr-xl">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody id="active-users-body">
                                        <!-- Users will be populated by JavaScript -->
                                    </tbody>
                                </table>
                            </div>

                            <!-- Pagination -->
                            <div class="flex flex-col md:flex-row justify-between items-center mt-6">
                                <p id="activeuser-pagination-info" class="text-sm text-gray-600 mb-4 md:mb-0">Showing 1 to 5 of 65 results</p>
                                <div class="flex space-x-2" id="active-users-pagination">
                                    <!-- Will be populated by JavaScript -->
                                </div>
                            </div>
                        </div>
                    </div>
                    <!--Pending Users-->
                    <div class="lg:col-span-3">
                        <div class="card-gradient rounded-2xl p-4">
                            <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-4">
                                <h2 class="text-lg font-semibold">Pending Users</h2>
                                <div class="relative mt-2 md:mt-0">
                                    <span class="absolute inset-y-0 left-0 flex items-center pl-3"><i
                                            class="fas fa-search text-gray-400"></i></span>
                                    <input type="text" id="search-users"
                                        class="py-2 pl-10 pr-4 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent text-sm w-full md:w-64"
                                        placeholder="Search users...">
                                </div>
                            </div>

                            <div class="overflow-x-auto">
                                <table class="w-full user-table">
                                    <thead>
                                        <tr class="text-left">
                                            <th class="px-4 py-3 rounded-tl-xl">User</th>
                                            <th class="px-4 py-3">Role</th>
                                            <th class="px-4 py-3">Visit Date</th>
                                            <th class="px-4 py-3">Address</th>
                                            <th class="px-4 py-3">Date Created</th>
                                            <th class="px-4 py-3 rounded-tr-xl">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody id="pending-users-body">
                                        <!-- Pending users will be populated by JavaScript -->
                                    </tbody>
                                </table>
                            </div>

                            <!-- Pagination -->
                            <div class="flex flex-col md:flex-row justify-between items-center mt-6">
                                <p id="pagination-info" class="text-sm text-gray-600 mb-4 md:mb-0">Showing 1 to 5 of 65 results</p>
                                <div class="flex space-x-2" id="pagination-controls">
                                    <!-- Pagination buttons will be populated by JavaScript -->
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>
    </div>

    <script>
        // Mobile menu toggle
        document.getElementById('menu-toggle').addEventListener('click', function () {
            document.getElementById('sidebar').classList.toggle('open');
        });

        // // Theme toggle
        // document.querySelector('[data-theme-toggle]').addEventListener('click', function () {
        //     document.documentElement.classList.toggle('dark');
        //     const icon = this.querySelector('i');
        //     if (document.documentElement.classList.contains('dark')) {
        //         icon.classList.replace('fa-moon', 'fa-sun');
        //     } else {
        //         icon.classList.replace('fa-sun', 'fa-moon');
        //     }
        // });

        // User Roles Chart
        const userRolesCtx = document.getElementById('userRolesChart').getContext('2d');
        new Chart(userRolesCtx, {
            type: 'doughnut',
            data: {
                labels: ['Admins', 'Suppliers', 'Manufacturers', 'Wholesalers'],
                datasets: [{
                    data: [{{$admin}}, {{$supplier}}, {{$manufacturer}}, {{$wholesaler}}],
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

        //Loading pending users
        const users =[
            @foreach ($pendingUsers as $pending )
            {
                id: {{ $pending->id }},
                name: "{{ $pending->name }}",
                email: "{{ $pending->email }}",
                role: "{{ $pending->role }}",
                visitDate: "{{ $pending->visitDate }}",
                business_address: "{{ $pending->business_address }}",
                created_at: "{{ $pending->created_at }}"
            },
            @endforeach            
        ];       

        let currentPage = 1;
        const usersPerPage = 10;
        let filteredUsers = [...users];
        let searchTerm = '';

        // DOM elements
        const tableBody = document.getElementById('pending-users-body');
        const paginationInfo = document.getElementById('pagination-info');
        const paginationControls = document.getElementById('pagination-controls');
        const searchInput = document.getElementById('search-users');

        // Initialize
        document.addEventListener('DOMContentLoaded', () => {
            renderUsers();
            renderActiveusers();
            setupEventListeners();
            setupActiveEventListeners();
        });

        // Render users for current page
        function renderUsers() {
            tableBody.innerHTML = '';

            const startIndex = (currentPage - 1) * usersPerPage;
            const endIndex = startIndex + usersPerPage;
            const usersToShow = filteredUsers.slice(startIndex, endIndex);

            if (usersToShow.length === 0) {
                tableBody.innerHTML = `
                    <tr>
                        <td colspan="5" class="text-center py-8 text-gray-500">
                            No users found matching your criteria.
                        </td>
                    </tr>
                `;
                return;
            }

            usersToShow.forEach((user, index) => {
                const isLastRow = index === usersToShow.length - 1;
                const row = document.createElement('tr');
                row.className = isLastRow ? '' : 'border-b border-gray-200 dark:border-gray-700';

                //Image Allocation
                let image = "";
                if(user.role === 'supplier'){
                    image = "<i class=\"fas fa-truck-loading text-green-600\"></i>"
                }else if (user.role === 'manufacturer'){
                    image = "<i class=\"fas fa-industry text-yellow-600\"></i>"
                }else {
                     image = "<i class=\"fas fa-boxes text-purple-600\"></i>"
                }

                // Highlight row if it matches search term
                const highlightClass = searchTerm && (
                    user.name.toLowerCase().includes(searchTerm) ||
                    user.email.toLowerCase().includes(searchTerm)
                );

                row.innerHTML = `
                    <td class="px-4 py-3 ${isLastRow ? 'rounded-bl-xl' : ''}">
                        <div class="flex items-center">
                            <div class="w-8 h-8 mr-3 rounded-full bg-blue-100 flex items-center justify-center">
                                ${image}
                            </div>
                            <div>
                                <p class="font-medium">${user.name}</p>
                                <p class="text-gray-500 text-sm">${user.email}</p>
                            </div>
                        </div>
                    </td>
                    <td class="px-4 py-3">
                        <span class="role-badge role-${user.role.toLowerCase()}">${user.role}</span>
                    </td>
                    <td class="px-4 py-3 text-sm">${user.visitDate}</td>
                    <td class="px-4 py-3 text-sm">${user.business_address}</td>
                    <td class="px-4 py-3 text-sm">${user.created_at}</td>
                    <td class="px-4 py-3 ${isLastRow ? 'rounded-br-xl' : ''}">
                        <div class="flex space-x-2">
                            <form action="{{ route('admin.users.addview') }}" method="POST">
                                @csrf
                                <button type="submit" class="action-btn text-blue-600">
                                    <input type="text" name="id" class="hidden" value="${user.id}">
                                    <i class="fas fa-user-plus"></i>
                                </button>
                            </form>
                            <form action="{{ route('admin.users.remove') }}" method="POST">
                                @csrf
                                <button type="submit" class="action-btn text-red-600 hover:bg-red-50">
                                    <input type="text" name="id" class="hidden" value="${user.id}">
                                    <i class="fas fa-trash-alt"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                `;
                tableBody.appendChild(row);
            });

            updatePagination();
        }

        // Update pagination controls
        function updatePagination() {
            const totalPages = Math.ceil(filteredUsers.length / usersPerPage);
            const startUser = (currentPage - 1) * usersPerPage + 1;
            const endUser = Math.min(currentPage * usersPerPage, filteredUsers.length);

            paginationInfo.textContent = `Showing ${startUser} to ${endUser} of ${filteredUsers.length} users`;

            paginationControls.innerHTML = '';

            // Previous button
            const prevButton = document.createElement('button');
            prevButton.className = 'px-3 py-1 rounded-lg bg-gray-200 text-gray-700 hover:bg-gray-300';
            prevButton.disabled = currentPage === 1;
            prevButton.innerHTML = '<i class="fas fa-chevron-left"></i>';
            prevButton.addEventListener('click', () => {
                if (currentPage > 1) {
                    currentPage--;
                    renderUsers();
                }
            });
            paginationControls.appendChild(prevButton);

            // Page numbers
            const maxVisiblePages = 5;
            let startPage = Math.max(1, currentPage - Math.floor(maxVisiblePages / 2));
            let endPage = startPage + maxVisiblePages - 1;

            if (endPage > totalPages) {
                endPage = totalPages;
                startPage = Math.max(1, endPage - maxVisiblePages + 1);
            }

            if (startPage > 1) {
                const firstPageButton = createPageButton(1);
                paginationControls.appendChild(firstPageButton);

                if (startPage > 2) {
                    const ellipsis = document.createElement('span');
                    ellipsis.className = 'px-3 py-1';
                    ellipsis.textContent = '...';
                    paginationControls.appendChild(ellipsis);
                }
            }

            for (let i = startPage; i <= endPage; i++) {
                const pageButton = createPageButton(i);
                paginationControls.appendChild(pageButton);
            }

            if (endPage < totalPages) {
                if (endPage < totalPages - 1) {
                    const ellipsis = document.createElement('span');
                    ellipsis.className = 'px-3 py-1';
                    ellipsis.textContent = '...';
                    paginationControls.appendChild(ellipsis);
                }

                const lastPageButton = createPageButton(totalPages);
                paginationControls.appendChild(lastPageButton);
            }

            // Next button
            const nextButton = document.createElement('button');
            nextButton.className = 'px-3 py-1 rounded-lg bg-gray-200 text-gray-700 hover:bg-gray-300';
            nextButton.disabled = currentPage === totalPages;
            nextButton.innerHTML = '<i class="fas fa-chevron-right"></i>';
            nextButton.addEventListener('click', () => {
                if (currentPage < totalPages) {
                    currentPage++;
                    renderUsers();
                }
            });
            paginationControls.appendChild(nextButton);
        }

        // Create a page button
        function createPageButton(pageNumber) {
            const button = document.createElement('button');
            button.className = `${pageNumber === currentPage ? 'bg-blue-600 text-white' : ''} px-3 py-1 rounded-lg bg-gray-200 text-gray-700 hover:bg-gray-300`;
            button.textContent = pageNumber;
            button.addEventListener('click', () => {
                currentPage = pageNumber;
                renderUsers();
            });
            return button;
        }

        // Setup event listeners
        function setupEventListeners() {
            searchInput.addEventListener('input', () => {
                searchTerm = searchInput.value.trim().toLowerCase();
                currentPage = 1;

                if (searchTerm === '') {
                    filteredUsers = [...users];
                } else {
                    filteredUsers = users.filter(user =>
                        user.name.toLowerCase().includes(searchTerm)
                    );
                }

                renderUsers();
            });
        }        
        
        //Loading active users
        const Activeusers =[
            @foreach ($activeUsers as $users )
            {
                id: {{ $users->id }},
                name: "{{ $users->name }}",
                email: "{{ $users->email }}",
                role: "{{ $users->role }}",
                created_at: "{{ $users->created_at }}"
            },
            @endforeach            
        ];       

        let current_ActiveUser_Page = 1;
        const activeUsersPerPage = 5;
        let filteredActiveusers = [...Activeusers];
        let searchTerm_Active = '';

        // DOM elements
        const activetableBody = document.getElementById('active-users-body');
        const activepaginationInfo = document.getElementById('activeuser-pagination-info');
        const activepaginationControls = document.getElementById('active-users-pagination');
        const activesearchInput = document.getElementById('search-activeusers');

        
        // Render users for current page
        function renderActiveusers() {
            activetableBody.innerHTML = '';

            const startIndex = (current_ActiveUser_Page - 1) * activeUsersPerPage;
            const endIndex = startIndex + activeUsersPerPage;
            const usersToShow = filteredActiveusers.slice(startIndex, endIndex);

            if (usersToShow.length === 0) {
                activetableBody.innerHTML = `
                    <tr>
                        <td colspan="5" class="text-center py-8 text-gray-500">
                            No users found matching your criteria.
                        </td>
                    </tr>
                `;
                return;
            }

            usersToShow.forEach((user, index) => {
                const isLastRow = index === usersToShow.length - 1;
                const row = document.createElement('tr');
                row.className = isLastRow ? '' : 'border-b border-gray-200 dark:border-gray-700';

                //Image Allocation
                let image = "";
                if(user.role === 'admin'){
                    image = "<i class=\"fa-solid fa-user-tie text-blue-600\"></i>"
                }else if(user.role === 'supplier'){
                    image = "<i class=\"fas fa-truck-loading text-green-600\"></i>"
                }else if (user.role === 'manufacturer'){
                    image = "<i class=\"fas fa-industry text-yellow-600\"></i>"
                }else {
                     image = "<i class=\"fas fa-boxes text-purple-600\"></i>"
                }

                // Highlight row if it matches search term
                const highlightClass = searchTerm_Active && (
                    user.name.toLowerCase().includes(searchTerm_Active) ||
                    user.email.toLowerCase().includes(searchTerm_Active)
                );

                row.innerHTML = `
                    <td class="px-4 py-3 ${isLastRow ? 'rounded-bl-xl' : ''}">
                        <div class="flex items-center">
                            <div class="w-8 h-8 mr-3 rounded-full bg-blue-100 flex items-center justify-center">
                                ${image}
                            </div>
                            <div>
                                <p class="font-medium">${user.name}</p>
                                <p class="text-gray-500 text-sm">${user.email}</p>
                            </div>
                        </div>
                    </td>
                    <td class="px-4 py-3">
                        <span class="role-badge role-${user.role.toLowerCase()}">${user.role}</span>
                    </td>
                    <td class="px-4 py-3 text-sm">${user.created_at}</td>
                    <td class="px-4 py-3 ${isLastRow ? 'rounded-br-xl' : ''}">
                        <div class="flex space-x-2">
                            <form action="{{route('admin.users.editview')}}" method="POST">
                                @csrf
                                <button type="submit" class="action-btn text-blue-600">
                                    <input type="text" name="id" class="hidden" value="${user.id}">
                                    <i class="fas fa-edit"></i>
                                </button>
                            </form>
                            <form action="{{route('admin.users.remove')}}" method="POST">
                                @csrf
                                <button class="action-btn text-red-600 hover:bg-red-50">
                                    <input type="text" name="id" class="hidden" value="${user.id}">
                                    <input type="text" name="database" class="hidden" value="users">
                                    <i class="fas fa-trash-alt"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                `;
                activetableBody.appendChild(row);
            });

            updateActivepagination();
        }

        // Update pagination controls
        function updateActivepagination() {
            const totalPages = Math.ceil(filteredActiveusers.length / activeUsersPerPage);
            const startUser = (current_ActiveUser_Page - 1) * activeUsersPerPage + 1;
            const endUser = Math.min(current_ActiveUser_Page * activeUsersPerPage, filteredActiveusers.length);

            activepaginationInfo.textContent = `Showing ${startUser} to ${endUser} of ${filteredActiveusers.length} users`;

            activepaginationControls.innerHTML = '';

            // Previous button
            const prevButton = document.createElement('button');
            prevButton.className = 'px-3 py-1 rounded-lg bg-gray-200 text-gray-700 hover:bg-gray-300';
            prevButton.disabled = current_ActiveUser_Page === 1;
            prevButton.innerHTML = '<i class="fas fa-chevron-left"></i>';
            prevButton.addEventListener('click', () => {
                if (current_ActiveUser_Page > 1) {
                    current_ActiveUser_Page--;
                    renderActiveusers();
                }
            });
            activepaginationControls.appendChild(prevButton);

            // Page numbers
            const maxVisiblePages = 5;
            let startPage = Math.max(1, current_ActiveUser_Page - Math.floor(maxVisiblePages / 2));
            let endPage = startPage + maxVisiblePages - 1;

            if (endPage > totalPages) {
                endPage = totalPages;
                startPage = Math.max(1, endPage - maxVisiblePages + 1);
            }

            if (startPage > 1) {
                const firstPageButton = createPageButton(1);
                activepaginationControls.appendChild(firstPageButton);

                if (startPage > 2) {
                    const ellipsis = document.createElement('span');
                    ellipsis.className = 'px-3 py-1';
                    ellipsis.textContent = '...';
                    activepaginationControls.appendChild(ellipsis);
                }
            }

            for (let i = startPage; i <= endPage; i++) {
                const pageButton = createPageButton(i);
                activepaginationControls.appendChild(pageButton);
            }

            if (endPage < totalPages) {
                if (endPage < totalPages - 1) {
                    const ellipsis = document.createElement('span');
                    ellipsis.className = 'px-3 py-1';
                    ellipsis.textContent = '...';
                    activepaginationControls.appendChild(ellipsis);
                }

                const lastPageButton = createPageButton(totalPages);
                activepaginationControls.appendChild(lastPageButton);
            }

            // Next button
            const nextButton = document.createElement('button');
            nextButton.className = 'px-3 py-1 rounded-lg bg-gray-200 text-gray-700 hover:bg-gray-300';
            nextButton.disabled = current_ActiveUser_Page === totalPages;
            nextButton.innerHTML = '<i class="fas fa-chevron-right"></i>';
            nextButton.addEventListener('click', () => {
                if (current_ActiveUser_Page < totalPages) {
                    current_ActiveUser_Page++;
                    renderActiveusers();
                }
            });
            activepaginationControls.appendChild(nextButton);
        }

        // Create a page button
        function createPageButton(pageNumber) {
            const button = document.createElement('button');
            button.className = `${pageNumber === current_ActiveUser_Page ? 'bg-blue-600 text-white' : ''} px-3 py-1 rounded-lg bg-gray-200 text-gray-700 hover:bg-gray-300`;
            button.textContent = pageNumber;
            button.addEventListener('click', () => {
                current_ActiveUser_Page = pageNumber;
                renderActiveusers();
            });
            return button;
        }

        // Setup event listeners
        function setupActiveEventListeners() {
            activesearchInput.addEventListener('input', () => {
                searchTerm_Active = activesearchInput.value.trim().toLowerCase();
                current_ActiveUser_Page = 1;

                if (searchTerm_Active === '') {
                    filteredActiveusers = [...users];
                } else {
                    filteredActiveusers = users.filter(user =>
                        user.name.toLowerCase().includes(searchTerm_Active)
                    );
                }

                renderActiveusers();
            });
        }        
        
        document.addEventListener('DOMContentLoaded', function() {
    // Add User AJAX (already present)
    document.querySelectorAll('#addUserForm').forEach(function(form) {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            const formData = new FormData(form);
            fetch(form.action, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('input[name=_token]').value,
                    'Accept': 'application/json'
                },
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                alert('User added successfully!');
            })
            .catch(error => {
                alert('Error adding user.');
            });
        });
    });
    // Remove User AJAX
    document.querySelectorAll('.removeUserForm').forEach(function(form) {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            const userId = form.getAttribute('data-user-id');
            const url = `/admin/users/${userId}/ajax`;
            const formData = new FormData(form);
            fetch(url, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('input[name=_token]').value,
                    'Accept': 'application/json'
                },
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                alert('User removed successfully!');
                const rowToRemove = form.closest('tr');
                if (rowToRemove) {
                    rowToRemove.remove();
                }
                updatePagination();
                updateActivepagination();
            })
            .catch(error => {
                alert('Error removing user.');
            });
        });
    });
});


    </script>
</body>

</html>