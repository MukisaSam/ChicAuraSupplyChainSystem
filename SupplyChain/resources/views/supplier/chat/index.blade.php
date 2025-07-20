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
            background: #f4f4f5;
            box-shadow: 0 4px 12px rgba(0,0,0,0.05);
            background-position: center;
            background-attachmecnt: fixed;
            min-height: 100vh;
            overflow: hidden;
        }

        /* Dark mode styles */
        .dark body {
            background: linear-gradient(135deg, rgba(15, 3, 3, 0.8) 0%, rgba(0,0,0,0.7) 100%), url('{{ asset('images/supplier.jpg') }}');
        }

        .sidebar {
            transition: transform 0.3s ease-in-out;
            background: linear-gradient(180deg, #ffffff 0%, #f8fafc 100%);
            box-shadow: 4px 0 15px rgba(0,0,0,0.1);
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
        .unread-badge {
            background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
            color: white;
            border-radius: 50%;
            padding: 2px 6px;
            font-size: 0.75rem;
            min-width: 18px;
            text-align: center;
            box-shadow: 0 2px 4px rgba(239, 68, 68, 0.3);
        }

        .online-indicator {
            width: 8px;
            height: 8px;
            background: #10b981;
            border-radius: 50%;
            display: inline-block;
            margin-right: 8px;
            box-shadow: 0 0 0 2px rgba(16, 185, 129, 0.2);
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
                    <div class="">
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
                    <button id="menu-toggle" class="md:hidden p-3 text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200">
                        <i class="fas fa-bars text-lg"></i>
                    </button>
                    <div class="relative ml-3 hidden md:block">
                        <span class="absolute inset-y-0 left-0 flex items-center pl-3">
                            <i class="fas fa-search text-gray-400"></i>
                        </span>
                        <input type="text" id="search-contacts" placeholder="Search contacts..." 
                               class="w-80 py-2 pl-10 pr-4 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-purple-500 focus:border-transparent text-sm dark:bg-gray-700 dark:text-white">
                    </div>
                </div>
                <div class="flex items-center pr-4 space-x-3">
                <i class="fas fa-bell"></i>
                    <button data-theme-toggle class="p-2 text-gray-500 hover:text-purple-600 hover:bg-purple-50 dark:text-gray-400 dark:hover:text-purple-400 dark:hover:bg-purple-900/20 rounded-full transition-colors" title="Switch Theme">
                        <i class="fas fa-moon text-lg"></i>
                    </button>
                    <div class="relative">
                        <button class="flex items-center focus:outline-none bg-white dark:bg-gray-700 rounded-full p-2 shadow-md hover:shadow-lg transition-shadow">
                            <span class="mr-2 text-gray-700 dark:text-gray-200 font-medium text-sm">{{ $user->name ?? 'Wholesaler User' }}</span>
                            <img class="w-7 h-7 rounded-full border-2 border-purple-200 object-cover" 
                                 src="{{ $user->profile_picture ? Storage::disk('public')->url($user->profile_picture) : asset('images/default-avatar.svg') }}" 
                                 alt="User Avatar">
                        </button>
                    </div>
                    <div class="flex items-center space-x-2">
                        <form method="POST" action="{{ route('logout') }}" class="inline">
                            @csrf
                            <button type="submit" class="p-2 text-gray-500 hover:text-red-600 hover:bg-red-50 dark:text-gray-400 dark:hover:text-red-400 dark:hover:bg-red-900/20 rounded-full transition-colors" title="Logout">
                                <i class="fas fa-sign-out-alt text-lg"></i>
                            </button>
                        </form>
                    </div>
                </div>
            </header>

            <!-- Main Content -->
            <main class="flex-1 p-4 overflow-y-auto min-h-0">
                <div class="mb-6">
                    <h2 class="text-2xl font-bold text-black mb-1">Chat</h2>
                    <p class="text-black text-sm">Communicate with manufacturers and support team</p>
                </div>

                <div class="flex h-full gap-6">
                    <!-- Contacts Sidebar -->
                    <div class="w-80 card-gradient rounded-xl flex flex-col">
                        <div class="p-6 border-b border-gray-200 dark:border-gray-600">
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Contacts</h3>
                        </div>

                        <div class="flex-1 overflow-y-auto p-4 contacts-scroll" style="height: calc(100vh - 300px); max-height: 420px;">
                            <!-- Manufacturers -->
                            @if($manufacturers->count() > 0)
                            <div class="mb-6">
                                <h4 class="text-sm font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wide mb-3 px-2">Manufacturers</h4>
                                @foreach($manufacturers as $manufacturer)
                                <a href="{{ route('supplier.chat.show', ['contactId' => $manufacturer->id]) }}" class="contact-item flex items-center p-4 rounded-xl hover:bg-green-100 dark:hover:bg-green-900/20 transition">
                                    <div class="relative">
                                        <img src="{{ $manufacturer->profile_picture ? Storage::disk('public')->url($manufacturer->profile_picture) : asset('images/manufacturer.png') }}" alt="{{ $manufacturer->name }}" class="w-12 h-12 rounded-full border-2 border-purple-200">
                                        <span class="online-indicator absolute -bottom-1 -right-1 {{ $manufacturer->isOnline() ? 'bg-green-500' : 'bg-gray-400' }}"></span>
                                    </div>
                                    <div class="ml-4 flex-1">
                                        <h5 class="text-sm font-medium text-gray-900 dark:text-white">{{ $manufacturer->name }}</h5>
                                        <p class="text-xs text-gray-500 dark:text-gray-400">Manufacturer</p>
                                    </div>
                                    @if(isset($unreadCounts[$manufacturer->id]) && $unreadCounts[$manufacturer->id] > 0)
                                    <span class="unread-badge">{{ $unreadCounts[$manufacturer->id] }}</span>
                                    @endif
                                </a>
                                @endforeach
                            </div>
                            @endif

                            <!-- Admins -->
                            @if($admins->count() > 0)
                            <div class="mb-6">
                                <h4 class="text-sm font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wide mb-3 px-2">Support Team</h4>
                                @foreach($admins as $admin)
                                <a href="{{ route('supplier.chat.show', ['contactId' => $admin->id]) }}" class="contact-item flex items-center p-4 rounded-xl hover:bg-green-100 dark:hover:bg-green-900/20 transition">
                                    <div class="relative">
                                        <img src="{{ asset('images/default-avatar.svg') }}" alt="{{ $admin->name }}" class="w-12 h-12 rounded-full border-2 border-purple-200">
                                        <span class="online-indicator absolute -bottom-1 -right-1 {{ $admin->isOnline() ? 'bg-green-500' : 'bg-gray-400' }}"></span>
                                    </div>
                                    <div class="ml-4 flex-1">
                                        <h5 class="text-sm font-medium text-gray-900 dark:text-white">{{ $admin->name }}</h5>
                                        <p class="text-xs text-gray-500 dark:text-gray-400">Support Team</p>
                                    </div>
                                    @if(isset($unreadCounts[$admin->id]) && $unreadCounts[$admin->id] > 0)
                                    <span class="unread-badge">{{ $unreadCounts[$admin->id] }}</span>
                                    @endif
                                </a>
                                @endforeach
                            </div>
                            @endif

                            <!-- No contacts message -->
                            @if($manufacturers->count() == 0 && $admins->count() == 0)
                            <div class="text-center py-12">
                                <div class="text-gray-500 dark:text-gray-400">
                                    <i class="fas fa-users text-4xl mb-4"></i>
                                    <p class="text-lg font-medium">No contacts available</p>
                                    <p class="text-sm">Contact support to add manufacturers or admins</p>
                                </div>
                            </div>
                            @endif
                        </div>
                    </div>

                    <!-- Chat Area -->
                    <div class="flex-1 card-gradient rounded-xl flex flex-col min-h-0" id="chat-conversation">
                        <div id="chat-welcome" class="flex-1 flex items-center justify-center">
                            <div class="text-center">
                                <div class="w-24 h-24 bg-gradient-to-br from-green-100 to-green-200 dark:from-green-900 dark:to-green-800 rounded-full flex items-center justify-center mx-auto mb-6">
                                    <i class="fas fa-comments text-4xl text-green-600 dark:text-green-300"></i>
                                </div>
                                <h3 class="text-xl font-semibold text-gray-900 dark:text-white mb-2">Welcome to Chat</h3>
                                <p class="text-gray-600 dark:text-gray-400">Select a contact from the sidebar to start a conversation</p>
                            </div>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Only keep search and mobile menu toggle
            const searchInput = document.getElementById('search-contacts');
            const contactItems = document.querySelectorAll('.contact-item');
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
            const menuToggle = document.getElementById('menu-toggle');
            if (menuToggle) {
                menuToggle.addEventListener('click', function() {
                    document.getElementById('sidebar').classList.toggle('open');
                });
            }
        });
    </script>
</body>
</html> 