<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Supplier Portal - ChicAura SCM</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <style>
        body { background: #f4f4f5; min-height: 100vh; }
        .sidebar { transition: transform 0.3s; background: linear-gradient(135deg, #ffffff 0%, #f8fafc 100%); box-shadow: 4px 0 15px rgba(0,0,0,0.1); color: #000; }
        .sidebar .nav-link, .sidebar h3, .sidebar p { color: #000 !important; }
        .logo-container { background: rgba(255,255,255,0.95); border-radius: 12px; padding: 8px; box-shadow: 0 4px 15px rgba(0,0,0,0.1); }
        .nav-link { transition: all 0.3s; border-radius: 12px; margin: 4px 0; }
        .nav-link:hover { transform: translateX(5px); }
        .header-gradient { background: linear-gradient(135deg, #ffffff 0%, #f8fafc 100%); box-shadow: 0 2px 20px rgba(0,0,0,0.1); }
        @media (max-width: 768px) { .sidebar { transform: translateX(-100%); } .sidebar.open { transform: translateX(0); } }
    </style>
</head>
<body class="font-sans antialiased">
<div class="flex min-h-screen">
    <!-- Sidebar -->
    <aside id="sidebar" class="sidebar absolute md:relative z-20 flex-shrink-0 w-64 md:block">
        <div class="flex flex-col h-full">
            <div class="flex items-center justify-center h-16 border-b border-gray-600">
                <div class="logo-container">
                    <img src="{{ asset('images/logo.png') }}" alt="ChicAura Logo" class="h-12 w-auto">
                </div>
            </div>
            <div class="px-4 py-4">
                <h3 class="text-black text-sm font-semibold mb-3 px-3">SUPPLIER PORTAL</h3>
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
            @yield('content')
        </main>
    </div>
</div>
<script>
    // Mobile menu toggle
    document.getElementById('menu-toggle').addEventListener('click', function() {
        document.getElementById('sidebar').classList.toggle('open');
    });
</script>
</body>
</html> 