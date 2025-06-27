{{-- resources/views/admin/dashboard.blade.php --}}
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Administrator Dashboard - ChicAura SCM</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="{{ asset('js/theme-switcher.js') }}"></script>
    <style>
        body {
            background: linear-gradient(135deg, rgba(0, 0, 0, 0.8) 0%, rgba(0, 0, 0, 0.6) 100%),
            url('{{ asset('images/black.jpeg') }}');
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
            min-height: 100vh;
            margin: 0;
            padding: 0;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
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
            transition: transform 0.3s ease-in-out;
            background: linear-gradient(180deg, #1e293b 0%, #334155 100%);
            box-shadow: 4px 0 15px rgba(0, 0, 0, 0.1);
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
                    <h3 class="text-white text-sm font-semibold mb-3 px-3">ADMINISTRATION</h3>
                </div>
                <nav class="flex-1 px-4 py-2 space-y-1">
                    <a href="{{route('admin.dashboard')}}"
                        class="nav-link flex items-center px-3 py-2 text-gray-300 hover:bg-gray-700 hover:text-white rounded-xl"><i
                            class="fas fa-tachometer-alt w-5"></i><span
                            class="ml-2 font-medium text-sm">Dashboard</span></a>
                    <a href="#"
                        class="nav-link flex items-center px-3 py-2 text-g  ray-300 hover:bg-gray-700 hover:text-white rounded-xl"><i
                            class="fas fa-shield-alt w-5"></i><span class="ml-2 text-sm">Roles & Permissions</span></a>
                    <a href="#"
                        class="nav-link flex items-center px-3 py-2 text-gray-300 hover:bg-gray-700 hover:text-white rounded-xl"><i
                            class="fas fa-cogs w-5"></i><span class="ml-2 text-sm">System Settings</span></a>
                    <a href="#"
                        class="nav-link flex items-center px-3 py-2 text-gray-300 hover:bg-gray-700 hover:text-white rounded-xl"><i
                            class="fas fa-chart-pie w-5"></i><span class="ml-2 text-sm">Analytics</span></a>
                    <a href="#"
                        class="nav-link flex items-center px-3 py-2 text-gray-300 hover:bg-gray-700 hover:text-white rounded-xl"><i
                            class="fas fa-history w-5"></i><span class="ml-2 text-sm">Audit Logs</span></a>
                    <a href="#"
                        class="nav-link flex items-center px-3 py-2 text-gray-300 hover:bg-gray-700 hover:text-white rounded-xl"><i
                            class="fas fa-bell w-5"></i><span class="ml-2 text-sm">Notifications</span></a>
                    <a href="{{route('admin.users')}}"
                        class="nav-link flex items-center px-3 py-2 text-white bg-gradient-to-r from-blue-600 to-blue-700 rounded-xl shadow-lg"><i
                            class="fa-solid fa-user"></i><span class="ml-2 text-sm">Users Management</span></a>
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
                    <button
                        class="p-2 text-gray-500 hover:text-blue-600 hover:bg-blue-50 rounded-full transition-colors"><i
                            class="fas fa-bell text-lg"></i></button>
                    <button data-theme-toggle
                        class="p-2 text-gray-500 hover:text-blue-600 hover:bg-blue-50 rounded-full transition-colors"
                        title="Switch Theme">
                        <i class="fas fa-moon text-lg"></i>
                    </button>
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
                        <h1 class="text-2xl font-bold text-white">User Management</h1>
                        <p class="text-gray-300 mt-1">Manage all user accounts and permissions</p>
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
                                <h3 class="text-2xl font-bold text-gray-800 mt-1">65</h3>
                            </div>
                            <div class="w-12 h-12 rounded-full bg-blue-100 flex items-center justify-center">
                                <i class="fas fa-users text-blue-600"></i>
                            </div>
                        </div>
                        <div class="flex flex-wrap gap-2 mt-4 md:mt-0">
                            <button id="addUserBtn" class="btn-primary flex items-center" onclick="openAddUserModal()">
                                <i class="fas fa-plus mr-2"></i> Add New User
                            </button>
                            <button class="btn-secondary flex items-center">
                                <i class="fas fa-file-export mr-2"></i> Export
                            </button>
                            <div class="relative">
                                <button id="filterBtn" class="btn-secondary flex items-center">
                                    <i class="fas fa-filter mr-2"></i> Filters
                                </button>
                                <div id="filterDropdown" class="hidden absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-xl z-50">
                                    <div class="p-4">
                                        <h3 class="font-semibold mb-2">Filter by Role</h3>
                                        <select id="roleFilter" class="w-full p-2 border rounded">
                                            <option value="all">All Roles</option>
                                            <option value="admin">Admin</option>
                                            <option value="supplier">Supplier</option>
                                            <option value="manufacturer">Manufacturer</option>
                                            <option value="wholesaler">Wholesaler</option>
                                        </select>
                                        
                                        <h3 class="font-semibold mb-2 mt-4">Filter by Status</h3>
                                        <select id="statusFilter" class="w-full p-2 border rounded">
                                            <option value="all">All Status</option>
                                            <option value="active">Active</option>
                                            <option value="inactive">Inactive</option>
                                            <option value="suspended">Suspended</option>
                                            <option value="pending">Pending</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Stats Cards -->
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
                        <div class="stat-card rounded-2xl p-4">
                            <div class="flex justify-between items-center">
                                <div>
                                    <p class="text-gray-600 text-sm">Total Users</p>
                                    <h3 id="totalUsers" class="text-2xl font-bold text-gray-800">0</h3>
                                </div>
                                <div class="w-12 h-12 rounded-full bg-blue-100 flex items-center justify-center">
                                    <i class="fas fa-users text-blue-600"></i>
                                </div>
                            </div>
                        </div>
                        
                        <div class="stat-card rounded-2xl p-4">
                            <div class="flex justify-between items-center">
                                <div>
                                    <p class="text-gray-600 text-sm">Active Users</p>
                                    <h3 id="activeUsers" class="text-2xl font-bold text-gray-800">0</h3>
                                </div>
                                <div class="w-12 h-12 rounded-full bg-green-100 flex items-center justify-center">
                                    <i class="fas fa-user-check text-green-600"></i>
                                </div>
                            </div>
                        </div>
                        
                        <div class="stat-card rounded-2xl p-4">
                            <div class="flex justify-between items-center">
                                <div>
                                    <p class="text-gray-600 text-sm">Pending Users</p>
                                    <h3 id="pendingUsers" class="text-2xl font-bold text-gray-800">0</h3>
                                </div>
                                <div class="w-12 h-12 rounded-full bg-yellow-100 flex items-center justify-center">
                                    <i class="fas fa-user-clock text-yellow-600"></i>
                                </div>
                            </div>
                        </div>
                        
                        <div class="stat-card rounded-2xl p-4">
                            <div class="flex justify-between items-center">
                                <div>
                                    <p class="text-gray-600 text-sm">Suspended Users</p>
                                    <h3 id="suspendedUsers" class="text-2xl font-bold text-gray-800">0</h3>
                                </div>
                                <div class="w-12 h-12 rounded-full bg-red-100 flex items-center justify-center">
                                    <i class="fas fa-user-slash text-red-600"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Users Table -->
                    <div class="card-gradient rounded-2xl p-4">
                        <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-4">
                            <h2 class="text-lg font-semibold">All Users</h2>
                            <div class="relative mt-2 md:mt-0">
                                <span class="absolute inset-y-0 left-0 flex items-center pl-3">
                                    <i class="fas fa-search text-gray-400"></i>
                                </span>
                                <input type="text" 
                                       id="searchInput" 
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
                                        <th class="px-4 py-3">Status</th>
                                        <th class="px-4 py-3">Last Active</th>
                                        <th class="px-4 py-3 rounded-tr-xl">Actions</th>
                                    </tr>
                                </thead>
                                <tbody id="usersTableBody">
                                    <!-- Table content will be loaded via AJAX -->
                                </tbody>
                            </table>
                        </div>
                        
                        <!-- Pagination -->
                        <div id="pagination" class="mt-4 flex justify-between items-center">
                            <!-- Pagination content will be loaded via AJAX -->
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>
    </div>

    <!-- Add/Edit User Modal -->
    <div id="userModal" class="modal-overlay hidden">
        <div class="modal-content p-6">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-xl font-bold" id="modalTitle">Add New User</h3>
                <button onclick="closeUserModal()" class="text-gray-500 hover:text-gray-700">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            
            <form id="userForm" class="space-y-4">
                @csrf
                <input type="hidden" id="userId">
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Name</label>
                    <input type="text" id="userName" name="name" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Email</label>
                    <input type="email" id="userEmail" name="email" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                </div>
                
                <div id="passwordField">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Password</label>
                    <input type="password" id="userPassword" name="password" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Role</label>
                    <select id="userRole" name="role" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        <option value="admin">Admin</option>
                        <option value="supplier">Supplier</option>
                        <option value="manufacturer">Manufacturer</option>
                        <option value="wholesaler">Wholesaler</option>
                    </select>
                </div>
                
                <div id="statusField" class="hidden">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Status</label>
                    <select id="userStatus" name="status" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        <option value="active">Active</option>
                        <option value="inactive">Inactive</option>
                        <option value="suspended">Suspended</option>
                        <option value="pending">Pending</option>
                    </select>
                </div>
                
                <div class="flex justify-end space-x-3 mt-6">
                    <button type="button" onclick="closeUserModal()" class="btn-secondary">Cancel</button>
                    <button type="submit" class="btn-primary">Save</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        // Mobile menu toggle
        document.getElementById('menu-toggle').addEventListener('click', function () {
            document.getElementById('sidebar').classList.toggle('open');
        });

        // Theme toggle
        document.querySelector('[data-theme-toggle]').addEventListener('click', function () {
            document.documentElement.classList.toggle('dark');
            const icon = this.querySelector('i');
            if (document.documentElement.classList.contains('dark')) {
                icon.classList.replace('fa-moon', 'fa-sun');
            } else {
                icon.classList.replace('fa-sun', 'fa-moon');
            }
        });

        // User Roles Chart
        const userRolesCtx = document.getElementById('userRolesChart').getContext('2d');
        new Chart(userRolesCtx, {
            type: 'doughnut',
            data: {
                labels: ['Admins', 'Suppliers', 'Manufacturers', 'Wholesalers'],
                datasets: [{
                    data: [5, 25, 15, 20],
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
        //Loading Users
        //Pending Users

        const users = [
            // Admins
            { id: 1, name: "Sarah Johnson", email: "sarah@chicaura.com", image: "https://randomuser.me/api/portraits/women/44.jpg", role: "Admin", status: "Active", lastActive: "Today, 09:24 AM" },
            { id: 2, name: "Sophia Williams", email: "sophia@chicaura.com", image: "https://randomuser.me/api/portraits/women/26.jpg", role: "Admin", status: "Suspended", lastActive: "April 28, 2024" },
            { id: 3, name: "James Wilson", email: "james@chicaura.com", image: "https://randomuser.me/api/portraits/men/22.jpg", role: "Admin", status: "Active", lastActive: "Today, 08:15 AM" },
            { id: 4, name: "Olivia Brown", email: "olivia@chicaura.com", image: "https://randomuser.me/api/portraits/women/65.jpg", role: "Admin", status: "Active", lastActive: "Today, 11:30 AM" },
            
            // Suppliers
            { id: 5, name: "Michael Chen", email: "michael@supplier.com", image: "https://randomuser.me/api/portraits/men/32.jpg", role: "Supplier", status: "Active", lastActive: "Yesterday, 04:15 PM" },
            { id: 6, name: "Robert Garcia", email: "robert@supplier.com", image: "https://randomuser.me/api/portraits/men/44.jpg", role: "Supplier", status: "Pending", lastActive: "June 25, 2024" },
            { id: 7, name: "Jennifer Lee", email: "jennifer@supplier.com", image: "https://randomuser.me/api/portraits/women/33.jpg", role: "Supplier", status: "Active", lastActive: "Today, 10:20 AM" },
            { id: 8, name: "Daniel Martinez", email: "daniel@supplier.com", image: "https://randomuser.me/api/portraits/men/55.jpg", role: "Supplier", status: "Pending", lastActive: "June 20, 2024" },
            
            // Manufacturers
            { id: 9, name: "Emma Rodriguez", email: "emma@manufacturer.com", image: "https://randomuser.me/api/portraits/women/68.jpg", role: "Manufacturer", status: "Pending", lastActive: "May 12, 2024" },
            { id: 10, name: "Thomas Smith", email: "thomas@manufacturer.com", image: "https://randomuser.me/api/portraits/men/66.jpg", role: "Manufacturer", status: "Active", lastActive: "Today, 09:45 AM" },
            { id: 11, name: "Emily Davis", email: "emily@manufacturer.com", image: "https://randomuser.me/api/portraits/women/70.jpg", role: "Manufacturer", status: "Pending", lastActive: "June 22, 2024" },
            { id: 12, name: "William Johnson", email: "william@manufacturer.com", image: "https://randomuser.me/api/portraits/men/77.jpg", role: "Manufacturer", status: "Active", lastActive: "Today, 08:30 AM" },
            
            // Wholesalers
            { id: 13, name: "David Kim", email: "david@wholesaler.com", image: "https://randomuser.me/api/portraits/men/67.jpg", role: "Wholesaler", status: "Active", lastActive: "Today, 10:45 AM" },
            { id: 14, name: "Elizabeth Taylor", email: "elizabeth@wholesaler.com", image: "https://randomuser.me/api/portraits/women/50.jpg", role: "Wholesaler", status: "Pending", lastActive: "June 24, 2024" },
            { id: 15, name: "Christopher Moore", email: "christopher@wholesaler.com", image: "https://randomuser.me/api/portraits/men/88.jpg", role: "Wholesaler", status: "Suspended", lastActive: "June 10, 2024" },
            { id: 16, name: "Amanda Jackson", email: "amanda@wholesaler.com", image: "https://randomuser.me/api/portraits/women/45.jpg", role: "Wholesaler", status: "Active", lastActive: "Today, 11:15 AM" },
            
            // Additional users for pagination demo
            { id: 17, name: "Matthew White", email: "matthew@chicaura.com", image: "https://randomuser.me/api/portraits/men/28.jpg", role: "Admin", status: "Pending", lastActive: "June 23, 2024" },
            { id: 18, name: "Jessica Harris", email: "jessica@supplier.com", image: "https://randomuser.me/api/portraits/women/29.jpg", role: "Supplier", status: "Active", lastActive: "Today, 09:10 AM" },
            { id: 19, name: "Andrew Clark", email: "andrew@manufacturer.com", image: "https://randomuser.me/api/portraits/men/30.jpg", role: "Manufacturer", status: "Pending", lastActive: "June 25, 2024" },
            { id: 20, name: "Samantha Lewis", email: "samantha@wholesaler.com", image: "https://randomuser.me/api/portraits/women/31.jpg", role: "Wholesaler", status: "Active", lastActive: "Today, 10:05 AM" },
            { id: 21, name: "Kevin Walker", email: "kevin@chicaura.com", image: "https://randomuser.me/api/portraits/men/32.jpg", role: "Admin", status: "Pending", lastActive: "June 24, 2024" },
            { id: 22, name: "Laura Hall", email: "laura@supplier.com", image: "https://randomuser.me/api/portraits/women/33.jpg", role: "Supplier", status: "Suspended", lastActive: "June 15, 2024" },
            { id: 23, name: "Ryan Allen", email: "ryan@manufacturer.com", image: "https://randomuser.me/api/portraits/men/34.jpg", role: "Manufacturer", status: "Active", lastActive: "Today, 08:45 AM" },
            { id: 24, name: "Michelle Young", email: "michelle@wholesaler.com", image: "https://randomuser.me/api/portraits/women/35.jpg", role: "Wholesaler", status: "Pending", lastActive: "June 22, 2024" }
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
            setupEventListeners();
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
                }else if (user.role === 'manufactuerer'){
                    image = "<i class=\"fas fa-industry text-yellow-600\"></i>"
                }else{
                     image = "<i class=\"fas fa-boxes text-purple-600\"></i>"
                }

                // Highlight row if it matches search term
                const highlightClass = searchTerm && (
                    user.name.toLowerCase().includes(searchTerm) ||
                    user.email.toLowerCase().includes(searchTerm)
                ) ? 'highlight' : '';

                row.innerHTML = `
                    <td class="px-4 py-3 ${isLastRow ? 'rounded-bl-xl' : ''} ${highlightClass}">
                        <div class="flex items-center">
                            <div class="w-8 h-8 mr-3 rounded-full bg-blue-100 flex items-center justify-center">
                                <i class="fas fa-users text-blue-600"></i>
                            </div>
                            <div>
                                <p class="font-medium">${user.name}</p>
                                <p class="text-gray-500 text-sm">${user.email}</p>
                            </div>
                        </div>
                    </td>
                    <td class="px-4 py-3 ${highlightClass}">
                        <span class="role-badge role-${user.role.toLowerCase()}">${user.role}</span>
                    </td>
                    <td class="px-4 py-3 ${highlightClass}">
                        <span class="status-badge status-${user.status.toLowerCase()}">${user.status}</span>
                    </td>
                    <td class="px-4 py-3 text-sm ${highlightClass}">${user.lastActive}</td>
                    <td class="px-4 py-3 ${isLastRow ? 'rounded-br-xl' : ''}">
                        <div class="flex space-x-2">
                            <button class="action-btn text-blue-600 hover:bg-blue-50">
                                <i class="fas fa-edit"></i>
                            </button>
                            <button class="action-btn text-gray-600 hover:bg-gray-100">
                                <i class="fas fa-eye"></i>
                            </button>
                            <button class="action-btn text-red-600 hover:bg-red-50">
                                <i class="fas fa-trash-alt"></i>
                            </button>
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
        
        // Modal functionality
        const modal = document.getElementById('userModal');
        const addUserBtn = document.getElementById('addUserBtn');
        const closeModal = document.getElementById('closeModal');
        const cancelBtn = document.getElementById('cancelBtn');
        const form = document.getElementById('userForm');
        const appContainer = document.getElementById('app-container');

        // Open modal
        addUserBtn.addEventListener('click', () => {
            modal.classList.remove('hidden');
            appContainer.classList.add('blurred');
            document.body.style.overflow = 'hidden'; // Prevent scrolling
        });

        // Close modal
        function closeModalFunc() {
            modal.classList.add('hidden');
            appContainer.classList.remove('blurred');
            document.body.style.overflow = 'auto'; // Enable scrolling
            resetForm();
        }

        closeModal.addEventListener('click', closeModalFunc);
        cancelBtn.addEventListener('click', closeModalFunc);

        // Close modal when clicking outside
        document.addEventListener('click', (e) => {
            if (e.target.classList.contains('modal-overlay')) {
                closeModalFunc();
            }
        });

        // Close modal with Escape key
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape' && !modal.classList.contains('hidden')) {
                closeModalFunc();
            }
        });
        
        let currentPage = 1;
        let searchTimer = null;

        // Initial load
        document.addEventListener('DOMContentLoaded', function() {
            loadUsers();
            setupEventListeners();
        });

        function setupEventListeners() {
            // Search input
            document.getElementById('searchInput').addEventListener('input', function(e) {
                clearTimeout(searchTimer);
                searchTimer = setTimeout(() => {
                    currentPage = 1;
                    loadUsers();
                }, 500);
            });

            // Filter button
            document.getElementById('filterBtn').addEventListener('click', function() {
                document.getElementById('filterDropdown').classList.toggle('hidden');
            });

            // Role and Status filters
            ['roleFilter', 'statusFilter'].forEach(id => {
                document.getElementById(id).addEventListener('change', function() {
                    currentPage = 1;
                    loadUsers();
                });
            });

            // User form submission
            document.getElementById('userForm').addEventListener('submit', function(e) {
                e.preventDefault();
                const userId = document.getElementById('userId').value;
                userId ? updateUser(userId) : createUser();
            });

            // Close dropdown when clicking outside
            document.addEventListener('click', function(e) {
                if (!e.target.closest('#filterBtn') && !e.target.closest('#filterDropdown')) {
                    document.getElementById('filterDropdown').classList.add('hidden');
                }
            });
        }

        function loadUsers() {
            const search = document.getElementById('searchInput').value;
            const role = document.getElementById('roleFilter').value;
            const status = document.getElementById('statusFilter').value;

            fetch(`/admin/users/list?page=${currentPage}&search=${search}&role=${role}&status=${status}`)
                .then(response => response.json())
                .then(data => {
                    updateTable(data.users);
                    updateStats(data.stats);
                    updatePagination(data.users);
                })
                .catch(error => console.error('Error:', error));
        }

        function updateTable(data) {
            const tbody = document.getElementById('usersTableBody');
            tbody.innerHTML = '';

            data.data.forEach(user => {
                const row = `
                    <tr class="border-b border-gray-200 dark:border-gray-700">
                        <td class="px-4 py-3">
                            <div class="flex items-center">
                                <img class="w-8 h-8 rounded-full mr-3" 
                                     src="${user.profile_picture || '/images/default-avatar.svg'}" 
                                     alt="${user.name}">
                                <div>
                                    <p class="font-medium">${user.name}</p>
                                    <p class="text-gray-500 text-sm">${user.email}</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-4 py-3">
                            <span class="role-badge role-${user.role.toLowerCase()}">${user.role}</span>
                        </td>
                        <td class="px-4 py-3">
                            <span class="status-badge status-${user.status.toLowerCase()}">${user.status}</span>
                        </td>
                        <td class="px-4 py-3 text-sm">${user.last_active || 'Never'}</td>
                        <td class="px-4 py-3">
                            <div class="flex space-x-2">
                                <button onclick="editUser(${user.id})" class="p-1 text-blue-600 hover:text-blue-800">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button onclick="viewUser(${user.id})" class="p-1 text-gray-600 hover:text-gray-800">
                                    <i class="fas fa-eye"></i>
                                </button>
                                <button onclick="deleteUser(${user.id})" class="p-1 text-red-600 hover:text-red-800">
                                    <i class="fas fa-trash-alt"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                `;
                tbody.insertAdjacentHTML('beforeend', row);
            });
        }

        function updateStats(stats) {
            document.getElementById('totalUsers').textContent = stats.total_users;
            document.getElementById('activeUsers').textContent = stats.active_users || '0';
            document.getElementById('pendingUsers').textContent = stats.pending_users || '0';
            document.getElementById('suspendedUsers').textContent = stats.suspended_users || '0';
        }

        function updatePagination(data) {
            const pagination = document.getElementById('pagination');
            pagination.innerHTML = '';

            if (data.total > data.per_page) {
                let paginationHtml = `
                    <div class="flex items-center space-x-2">
                        <button onclick="changePage(${data.current_page - 1})" 
                                class="btn-secondary ${data.current_page === 1 ? 'opacity-50 cursor-not-allowed' : ''}"
                                ${data.current_page === 1 ? 'disabled' : ''}>
                            Previous
                        </button>
                        <span class="text-sm text-gray-600">
                            Page ${data.current_page} of ${data.last_page}
                        </span>
                        <button onclick="changePage(${data.current_page + 1})"
                                class="btn-secondary ${data.current_page === data.last_page ? 'opacity-50 cursor-not-allowed' : ''}"
                                ${data.current_page === data.last_page ? 'disabled' : ''}>
                            Next
                        </button>
                    </div>
                `;
                pagination.innerHTML = paginationHtml;
            }
        }

        function changePage(page) {
            if (page > 0) {
                currentPage = page;
                loadUsers();
            }
        }

        function openAddUserModal() {
            document.getElementById('modalTitle').textContent = 'Add New User';
            document.getElementById('userForm').reset();
            document.getElementById('userId').value = '';
            document.getElementById('passwordField').classList.remove('hidden');
            document.getElementById('statusField').classList.add('hidden');
            document.getElementById('userModal').classList.remove('hidden');
        }

        function closeUserModal() {
            document.getElementById('userModal').classList.add('hidden');
            document.getElementById('userForm').reset();
        }

        function createUser() {
            const formData = new FormData(document.getElementById('userForm'));
            
            fetch('/admin/users', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify(Object.fromEntries(formData))
            })
            .then(response => response.json())
            .then(data => {
                if (data.errors) {
                    // Handle validation errors
                    alert('Please check the form for errors');
                } else {
                    closeUserModal();
                    loadUsers();
                }
            })
            .catch(error => console.error('Error:', error));
        }

        function editUser(id) {
            fetch(`/admin/users/${id}`)
                .then(response => response.json())
                .then(user => {
                    document.getElementById('modalTitle').textContent = 'Edit User';
                    document.getElementById('userId').value = user.id;
                    document.getElementById('userName').value = user.name;
                    document.getElementById('userEmail').value = user.email;
                    document.getElementById('userRole').value = user.role;
                    document.getElementById('userStatus').value = user.status;
                    document.getElementById('passwordField').classList.add('hidden');
                    document.getElementById('statusField').classList.remove('hidden');
                    document.getElementById('userModal').classList.remove('hidden');
                })
                .catch(error => console.error('Error:', error));
        }

        function updateUser(id) {
            const formData = new FormData(document.getElementById('userForm'));
            
            fetch(`/admin/users/${id}`, {
                method: 'PUT',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify(Object.fromEntries(formData))
            })
            .then(response => response.json())
            .then(data => {
                if (data.errors) {
                    // Handle validation errors
                    alert('Please check the form for errors');
                } else {
                    closeUserModal();
                    loadUsers();
                }
            })
            .catch(error => console.error('Error:', error));
        }

        function deleteUser(id) {
            if (confirm('Are you sure you want to delete this user?')) {
                fetch(`/admin/users/${id}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.message === 'You cannot delete your own account') {
                        alert(data.message);
                    } else {
                        loadUsers();
                    }
                })
                .catch(error => console.error('Error:', error));
            }
        }

        function viewUser(id) {
            // Implement view user functionality if needed
            window.location.href = `/admin/users/${id}`;
        }
    </script>
</body>

</html>