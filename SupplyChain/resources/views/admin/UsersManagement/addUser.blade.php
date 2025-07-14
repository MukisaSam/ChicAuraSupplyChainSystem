<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Administrator Dashboard - ChicAura SCM</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
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
                    <a href="{{route('admin.dashboard')}}"
                        class="nav-link flex items-center px-3 py-2 text-gray-300 hover:bg-gray-700 hover:text-white rounded-xl"><i
                            class="fas fa-tachometer-alt w-5"></i><span
                            class="ml-2 font-medium text-sm">Dashboard</span></a>
                    <a href="#"
                        class="nav-link flex items-center px-3 py-2 text-gray-300 hover:bg-gray-700 hover:text-white rounded-xl"><i
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
                @if($record)
                <form action="{{ route('admin.users.add') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <!-- Primary Key -->
                <input type="text" name="id" class="hidden"  value="{{$record->id}}">  

                <!-- Page Header -->
                <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6">
                    <div>
                        <h1 class="text-2xl font-bold text-white">Add User</h1>
                        <p class="text-gray-300 mt-1">Enter the new user's personal and account information to create their profile.</p>
                    </div>
                    <div class="flex space-x-2 mt-4 md:mt-0">
                        <button type="submit" class="bg-gradient-to-r from-blue-500 to-blue-600 hover:from-blue-600 hover:to-blue-700 text-white px-4 py-2 rounded-lg font-semibold shadow-md hover:shadow-lg transition-all duration-300 flex items-center">
                            <i class="fas fa-save mr-2"></i> Add User
                        </button>
                    </div>
                </div>
                
                <!-- Profile Header -->
                <div class="bg-gradient-to-r from-blue-500 to-blue-600 rounded-2xl p-6 mb-6 shadow-xl">
                    <div class="flex flex-col md:flex-row items-center">
                        <div class="relative mb-4 md:mb-0 md:mr-6">
                            @if($record->role == 'supplier')
                                <div class="w-24 h-24 md:w-32 md:h-32 bg-green-100 rounded-full border-4 border-white shadow-lg overflow-hidden flex items-center justify-center">
                                    <i class="fas fa-truck-loading text-green-600 text-[70px]"></i>
                                </div>
                            @endif
                            @if($record->role == 'manufacturer')
                                <div class="w-24 h-24 md:w-32 md:h-32 bg-yellow-100 rounded-full border-4 border-white shadow-lg overflow-hidden flex items-center justify-center">
                                    <i class="fas fa-industry text-yellow-600 text-[70px]"></i>
                                </div>
                            @endif
                            @if($record->role == 'wholesaler')
                                <div class="w-24 h-24 md:w-32 md:h-32 bg-purple-100 rounded-full border-4 border-white shadow-lg overflow-hidden flex items-center justify-center">
                                    <i class="fas fa-boxes text-purple-600 text-[70px]"></i>
                                </div>
                            @endif
                        </div>
                        <div class="text-center md:text-left">
                            <h1 class="text-2xl md:text-3xl font-bold text-white">{{$record->name}}</h1>
                            <div class="flex flex-wrap items-center justify-center md:justify-start mt-2 gap-2">
                                <span class="bg-blue-200 text-blue-800 px-3 py-1 rounded-full text-xs md:text-sm font-medium">{{$record->role}}</span>
                                <span class="bg-[rgba(217,119,6,0.2)] text-[#fcd34d] px-3 py-1 rounded-full text-xs md:text-sm font-medium">Pending</span>
                            </div>
                            <p class="text-blue-100 mt-2">{{$record->email}}</p>
                        </div>
                    </div>
                </div>
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                    <!-- Users Table -->
                    <div class="lg:col-span-2 space-y-6">
                        <div class="card-gradient rounded-2xl p-4">
                            <h2 class="text-xl font-bold mb-6 pb-3 border-b border-gray-200">Personal Information</h2>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label class="block text-gray-700 font-medium mb-2">Company / Firm Name</label>
                                    <input type="text" name="name" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent bg-white outline-none" value="{{$record->name}}">
                                </div>
                                <div>
                                    <label class="block text-gray-700 font-medium mb-2">Business Email</label>
                                    <input type="email" name="email" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent bg-white outline-none" value="{{$record->email}}">
                                </div>
                                <div>
                                    <label class="block text-gray-700 font-medium mb-2">Phone Number</label>
                                    <input type="tel" name="phone" class="w-full px-4 py-3 border border-gray-300  rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent bg-white outline-none" value="{{$record->phone}}">
                                </div>
                                <div>
                                    <label class="block text-gray-700 font-medium mb-2">Role</label>
                                    <input type="text" name="role" class="w-full px-4 py-3 border border-gray-300  rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent bg-white outline-none" value="{{$record->role}}">
                                </div>
                                <div>
                                    <label class="block text-gray-700 font-medium mb-2">New Password</label>
                                    <div class="relative">
                                        <input type="password" id="password" name="password" class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent bg-white outline-none" placeholder="Change Old Password">
                                        <div class="absolute right-3 top-3.5 text-gray-400 hover:text-gray-600 cursor-pointer">
                                            <i class="fas fa-eye"></i>
                                        </div>
                                    </div>
                                </div>
                                <div>
                                    <label class="block text-gray-700 font-medium mb-2">Confirm Password</label>
                                    <div class="relative">
                                        <input type="password" id="password_confirmation" name="password_confirmation" class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent bg-white outline-none" placeholder="Confirm new password">
                                        <div class="absolute right-3 top-3.5 text-gray-400 hover:text-gray-600 cursor-pointer">
                                            <i class="fas fa-eye"></i>
                                        </div>
                                    </div>
                                </div>
                                <div>
                                    <label class="block text-gray-700 font-medium mb-2">Business Address</label>
                                    <input type="text" name="business_address" class="w-full px-4 py-3 border border-gray-300  rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent bg-white outline-none" value="{{$record->business_address}}">
                                </div>
                                @if($record->role == 'supplier')
                                <div>
                                    <label class="block text-gray-700 font-medium mb-2">Materials Supplied</label>
                                    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 px-4 py-3 border border-gray-300  rounded-lg hover:ring-2 hover:ring-blue-500 hover:border-transparent bg-white">
                                        <?php
                                          $allOptions = ['fabric','thread','buttons','zippers','dyes','other'];
                                        ?>
                                        @foreach($allOptions as $option)
                                            <div><input type="checkbox" name="materials_supplied[]" value="{{$option}}" @if(in_array($option, $materials_supplied)) checked @endif><label class="ml-1" for="">{{ ucfirst($option) }}</label></div>
                                        @endforeach
                                    </div>
                                </div>
                                @endif
                                @if($record->role == 'manufacturer')
                                <div>
                                    <label class="block text-gray-700 font-medium mb-2">Production Capacity</label>
                                    <input type="text" name="production_capacity" class="w-full px-4 py-3 border border-gray-300  rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent bg-white outline-none" value="{{ $record->production_capacity }}">
                                </div> 
                                <div>
                                    <label class="block text-gray-700 font-medium mb-2">Specialization</label>
                                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 px-4 py-3 border border-gray-300  rounded-lg hover:ring-2 hover:ring-blue-500 hover:border-transparent bg-white">
                                        <?php
                                          $allOptions2 = ['casual_wear','formal_wear','sports_wear','evening_wear','accessories','other'];
                                        ?>
                                        @foreach($allOptions2 as $option)
                                            <div><input type="checkbox" name="specialization[]" value="{{$option}}" @if(in_array($option, $specialization)) checked @endif><label class="ml-1" for="">{{ ucfirst(str_replace('_', ' ', $option)) }}</label></div>
                                        @endforeach
                                    </div>
                                </div> 
                                @endif
                                @if($record->role == 'wholesaler')
                                <div>
                                    <label class="block text-gray-700 font-medium mb-2">Business Type</label>
                                    <input type="text" name="business_type" class="w-full px-4 py-3 border border-gray-300  rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent bg-white outline-none" value="{{ ucfirst(str_replace('_', ' ', $record->business_type)) }}">
                                </div>
                                <div>
                                    <label class="block text-gray-700 font-medium mb-2">Preferred Categories</label>
                                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 px-4 py-3 border border-gray-300 rounded-lg hover:ring-2 hover:ring-blue-500 hover:border-transparent bg-white">
                                        <?php
                                          $allOptions3 = ['casual_wear','formal_wear','sports_wear','evening_wear','accessories','other'];
                                        ?>
                                        @foreach($allOptions3 as $option)
                                            <div><input type="checkbox" name="preferred_categories[]" value="{{$option}}" @if(in_array($option, $preferred_categories)) checked @endif><label class="ml-1" for="">{{ ucfirst(str_replace('_', ' ', $option)) }}</label></div>
                                        @endforeach
                                    </div>
                                </div>
                                <div>
                                    <label class="block text-gray-700 font-medium mb-2">Monthy Order Volume</label>
                                    <input type="text" name="monthly_order_volume" class="w-full px-4 py-3 border border-gray-300  rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent bg-white outline-none" value="{{ $record->monthly_order_volume }}">
                                </div>                                
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="lg:col-span-1 space-y-6">
                        <div class="card-gradient rounded-2xl p-4">
                            <h2 class="text-xl font-bold mb-6 pb-3 border-b">Profile Photo</h2>
                            <div class="flex flex-col items-center">                                
                                <div class="relative inline-block cursor-pointer mb-4">
                                    <div class="rounded-full overflow-hidden w-[120px] h-[120px] border-none bg-blue-100 flex items-center justify-center">
                                        <i class="fa-solid fa-user-tie text-blue-600 text-[80px]"></i>
                                    </div>
                                    <div class="absolute bottom-2.5 right-2.5 bg-white rounded-full w-9 h-9 flex items-center justify-center shadow-lg">
                                        <i class="fas fa-camera text-blue-600"></i>
                                    </div>
                                </div>
                                <p class="text-gray-600 text-sm mb-4 text-center">
                                    Recommended size: 300x300 pixels. JPG, GIF or PNG.
                                </p>
                                <label class="btn-secondary w-full cursor-pointer inline-flex items-center justify-center p-2 rounded">
                                     <i class="fas fa-upload mr-2"></i> Upload New Photo
                                    <input type="file" class="hidden" name="profile_picture" accept=".jpg,.jpeg,.gif,.png" id="photoInput" />
                                </label>
                                <p id="fileName" class="text-gray-600 text-sm mt-2 text-center"></p>
                            </div>
                        </div>
                        <div class="card-gradient rounded-2xl p-4">
                            <h2 class="text-xl font-bold mb-6 pb-3 border-b">Documentation</h2>
                            <div class="flex flex-col items-center">                                
                                <div class="relative inline-block cursor-pointer mb-4">
                                    <div class="rounded-full overflow-hidden w-[120px] h-[120px] border-none flex items-center justify-center">
                                        <i class="fa-regular fa-file-pdf text-[80px]" style="color: #b00b00;"></i>
                                    </div>
                                </div>
                                @if($record->document_path)
                                <p class="text-gray-600 text-sm mb-4 text-center">
                                    {{$record->license_document}}
                                </p>
                                <a href="{{ asset('storage/'.$record->document_path) }}" target="_blank" class="btn-secondary w-full text-center">
                                    View Document
                                </a>
                                @else
                                    <p class="text-gray-600 text-sm mb-4 text-center">
                                        No document uploaded.
                                    </p>
                                @endif
                            </div>
                        </div>
                    </div>

                </div>
                </form>
                @else
                <div class="max-w-4xl mx-auto py-12">
                    <div class="error-illustration flex flex-col items-center justify-center text-center p-8 md:p-12 rounded-3xl bg-gradient-to-br from-white/5 to-gray-900/10 backdrop-blur-sm border border-white/10 shadow-xl">
                        <div class="relative mb-10">
                            <div class="absolute -top-8 -left-8 w-24 h-24 bg-blue-500 rounded-full filter blur-2xl opacity-20 animate-pulse"></div>
                            <div class="absolute -bottom-8 -right-8 w-24 h-24 bg-red-500 rounded-full filter blur-2xl opacity-20 animate-pulse"></div>
                            
                            <div class="relative bg-gray-800 rounded-2xl p-6 md:p-10 border border-gray-700 shadow-2xl transform rotate-6">
                                <div class="bg-gray-900 rounded-full w-32 h-32 flex items-center justify-center mx-auto mb-6 border-4 border-gray-700 shadow-inner">
                                    <i class="fas fa-search text-5xl text-red-500"></i>
                                </div>
                                <div class="space-y-2">
                                    <div class="h-4 bg-gray-700 rounded w-40 mx-auto"></div>
                                    <div class="h-4 bg-gray-700 rounded w-32 mx-auto"></div>
                                    <div class="h-4 bg-gray-700 rounded w-28 mx-auto"></div>
                                </div>
                            </div>
                        </div>

                        <h1 class="text-4xl md:text-6xl font-bold text-white mb-6">
                            <span class="text-red-500">Info</span> Not Found
                        </h1>
                        
                        <p class="text-xl text-gray-300 max-w-2xl mb-10">
                            The information you're looking for doesn't exist or may have been moved. 
                            
                        </p>
                        <div class="flex flex-col sm:flex-row gap-4 mb-14">
                            <button class="px-8 py-4 bg-gradient-to-r from-blue-500 to-blue-600 hover:from-blue-600 hover:to-blue-700 text-white rounded-xl font-semibold shadow-lg hover:shadow-xl transition-all duration-300 flex items-center justify-center">
                                <i class="fa-solid fa-user mr-3"></i> Return to User Management
                            </button>
                            
                        </div>
                    </div>
                </div>
                @endif   
            </main>
        </div>
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

        // Password visibility toggle
        document.querySelectorAll('.relative input[type="password"]').forEach(input => {
            const toggleBtn = input.nextElementSibling;
            toggleBtn.addEventListener('click', () => {
                if (input.type === 'password') {
                    input.type = 'text';
                    toggleBtn.innerHTML = '<i class="fas fa-eye-slash"></i>';
                } else {
                    input.type = 'password';
                    toggleBtn.innerHTML = '<i class="fas fa-eye"></i>';
                }
            });
        });

        //upload name
        const input = document.getElementById('photoInput');
        const fileNameDisplay = document.getElementById('fileName');

        input.addEventListener('change', function() {
          if (this.files && this.files.length > 0) {
            fileNameDisplay.textContent = `Selected file: ${this.files[0].name}`;
          } else {
            fileNameDisplay.textContent = '';
          }
        });
    </script>
</body>

</html>