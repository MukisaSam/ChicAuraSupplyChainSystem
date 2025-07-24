<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Admin Dashboard - ChicAura SCM</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <script src="{{ asset('js/theme-switcher.js') }}"></script>
    <style>
        body {
            background: #f4f4f5;
            box-shadow: 0 4px 12px rgba(0,0,0,0.05);
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
            min-height: 100vh;
        }
        .dark body {
            background: linear-gradient(135deg, rgba(0,0,0,0.9) 0%, rgba(0,0,0,0.8) 100%), url('{{ asset('images/black.jpeg') }}');
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
            background: linear-gradient(135deg, #1e293b 0%, #334155 100%);
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
        /* Badge Styles */
        .role-badge {
            display: inline-flex;
            align-items: center;
            padding: 4px 10px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 500;
        }
        .role-admin { background-color: #dbeafe; color: #2563eb; }
        .role-supplier { background-color: #dcfce7; color: #16a34a; }
        .role-manufacturer { background-color: #fef3c7; color: #d97706; }
        .role-wholesaler { background-color: #ede9fe; color: #7c3aed; }
        .dark .role-admin { background-color: rgba(37, 99, 235, 0.2); color: #93c5fd; }
        .dark .role-supplier { background-color: rgba(22, 163, 74, 0.2); color: #86efac; }
        .dark .role-manufacturer { background-color: rgba(217, 119, 6, 0.2); color: #fcd34d; }
        .dark .role-wholesaler { background-color: rgba(124, 58, 237, 0.2); color: #c4b5fd; }
        .status-badge {
            display: inline-flex;
            align-items: center;
            padding: 4px 10px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 500;
        }
        .status-active { background-color: #dcfce7; color: #16a34a; }
        .status-pending { background-color: #fef3c7; color: #d97706; }
        .status-suspended { background-color: #fee2e2; color: #dc2626; }
        .dark .status-active { background-color: rgba(22, 163, 74, 0.2); color: #86efac; }
        .dark .status-pending { background-color: rgba(217, 119, 6, 0.2); color: #fcd34d; }
        .dark .status-suspended { background-color: rgba(220, 38, 38, 0.2); color: #fca5a5; }
        /* Button Styles */
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
        .btn-secondary:hover { background: #e2e8f0; }
        .dark .btn-secondary { background: #334155; color: #f1f5f9; }
        .dark .btn-secondary:hover { background: #475569; }
        /* Modal Styles */
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
        .dark .modal-content { background: #1e293b; color: #f1f5f9; }
        @keyframes modalOpen {
            from { opacity: 0; transform: translateY(-50px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .scrollbar-hide::-webkit-scrollbar { display: none; }
        .scrollbar-hide { -ms-overflow-style: none; scrollbar-width: none; }
        @media (max-width: 768px) {
            .sidebar { transform: translateX(-100%); }
            .sidebar.open { transform: translateX(0); }
            .modal-content { width: 95%; }
        }
        .card-gradient {
            background: linear-gradient(135deg, #ffffff 0%, #f8fafc 100%);
            border: 1px solid rgba(255,255,255,0.2);
            box-shadow: 0 8px 32px rgba(0,0,0,0.1);
            backdrop-filter: blur(10px);
        }
        .stat-card {
            background: linear-gradient(135deg, #ffffff 0%, #f1f5f9 100%);
            border: 1px solid rgba(255,255,255,0.3);
            box-shadow: 0 4px 20px rgba(0,0,0,0.08);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 25px rgba(0,0,0,0.15);
        }
    </style>    
</head>
<body class="font-sans antialiased">
    <div class="flex h-screen">
        <!-- Sidebar -->
        <aside id="sidebar" class="sidebar absolute md:relative z-20 flex-shrink-0 w-64 md:block">
            <div class="flex flex-col h-full">
                <div class="flex items-center justify-center h-16 border-b border-gray-600">
                    <div>
                        <img src="{{ asset('images/logo.png') }}" alt="ChicAura Logo"
                            class="w-full h-auto object-contain max-w-[160px] max-h-[48px]">
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
                    <button id="menu-toggle" class="md:hidden p-3 text-gray-500 hover:text-gray-700"><i class="fas fa-bars text-lg"></i></button>
                    
                </div>
                <div class="flex items-center pr-4 space-x-3">
                     @include('admin.partials.index')                                         
                    <div class="relative">
                        <button class="flex items-center focus:outline-none bg-white rounded-full p-2 shadow-md hover:shadow-lg transition-shadow">
                            <span class="mr-2 text-gray-700 font-medium text-sm">{{ Auth::user()->name ?? 'Admin User' }}</span>
                            <img class="w-7 h-7 rounded-full border-2 border-purple-200 object-cover"
                                 src="{{ Auth::user()->profile_picture ? asset('storage/profile-pictures/' . basename(Auth::user()->profile_picture)) : asset('images/default-avatar.svg') }}"
                                 alt="User Avatar">
                        </button>
                    </div>
                    <div class="flex items-center space-x-2">
                        <button type="button" class="p-2 text-gray-500 hover:text-blue-600 hover:bg-blue-50 rounded-full transition-colors" title="Edit Profile" x-data x-on:click="$dispatch('open-modal', 'profile-editor-modal')">
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
            <main class="flex-1 p-4 overflow-y-auto">
                @yield('content')
            </main>
        </div>
    </div>
    <x-profile-editor-modal />
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const menuToggle = document.getElementById('menu-toggle');
            if (menuToggle) {
                menuToggle.addEventListener('click', function() {
                    document.getElementById('sidebar').classList.toggle('open');
                });
            }
        });
    </script>
    <script>
        // Highlight active sidebar link if not handled by Blade
        document.addEventListener('DOMContentLoaded', function () {
            const links = document.querySelectorAll('.sidebar .nav-link');
            const currentUrl = window.location.pathname;
            links.forEach(link => {
                // Compare the href without query params
                const linkUrl = link.getAttribute('href').split('?')[0];
                if (currentUrl === linkUrl) {
                    link.classList.add('text-white', 'bg-gradient-to-r', 'from-blue-600', 'to-blue-700', 'shadow-lg');
                    link.classList.remove('text-gray-300', 'hover:bg-gray-700', 'hover:text-white');
                }
            });
        });      
    </script>
</body>
</html>
