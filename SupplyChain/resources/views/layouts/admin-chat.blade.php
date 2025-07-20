<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Chat - ChicAura SCM</title>
    <script src="https://cdn.tailwindcss.com"></script>
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
            background: linear-gradient(135deg, rgba(0,0,0,0.8) 0%, rgba(0,0,0,0.7) 100%), url('{{ asset('images/black.jpeg') }}');
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
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
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
                <div class="flex items-center justify-center h-16 border-b border-blue-900">
                    <div class="logo-container">
                        <img src="{{ asset('images/logo.png') }}" alt="ChicAura Logo" class="w-full h-auto object-contain max-w-[160px] max-h-[48px]">
                    </div>
                </div>
                <div class="px-4 py-4">
                    <h3 class="text-white text-sm font-semibold mb-3 px-3">ADMIN PORTAL</h3>
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
                <div class="p-3 border-t border-blue-900">
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
                <img class="w-7 h-7 rounded-full border-2 border-purple-200 object-cover" 
                                 src="{{ Auth::user()->profile_picture ? asset('storage/profile-pictures/' . basename(Auth::user()->profile_picture)) : asset('images/default-avatar.svg') }}" 
                                 alt="User Avatar"> 
                    <span class="online-indicator ml-2 {{ $contact->isOnline() ? 'bg-green-500' : 'bg-gray-400' }}"></span>
                    <div class="ml-4">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white">{{ $contact->name }}</h3>
                        <p class="text-sm text-gray-500 dark:text-gray-400">{{ ucfirst($contact->role) }}
                            <span class="ml-2 text-xs {{ $contact->isOnline() ? 'text-green-500' : 'text-gray-400' }}">
                                {{ $contact->isOnline() ? 'Online' : 'Offline' }}
                            </span>
                        </p>
                    </div>
                </div>
                <div class="flex items-center pr-4 space-x-3">
                    <button data-theme-toggle class="p-2 text-gray-500 hover:text-blue-600 hover:bg-blue-50 rounded-full transition-colors" title="Switch Theme">
                        <i class="fas fa-moon text-lg"></i>
                    </button>
                    <div class="relative">
                        <button class="flex items-center focus:outline-none bg-white rounded-full p-2 shadow-md hover:shadow-lg transition-shadow">
                            <span class="mr-2 text-gray-700 font-medium text-sm">{{ Auth::user()->name ?? 'Admin User' }}</span>
                            <img class="w-7 h-7 rounded-full border-2 border-blue-200 object-cover"
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
            <main class="flex-1 p-4 h-full">
                @yield('content')
            </main>
        </div>
    </div>
</body>
</html>
