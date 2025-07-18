<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manufacturer Portal - ChicAura SCM</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="{{ asset('js/theme-switcher.js') }}"></script>
    <style>
        body { 
            background: #f4f6fa;
            min-height: 100vh;
            margin: 0;
            padding: 0;
        }
        .dark body {
            background: #181f2a;
        }
        .sidebar { 
            position: fixed;
            top: 0;
            left: 0;
            height: 100vh;
            width: 16rem;
            z-index: 30;
            overflow-y: auto;
            overflow-x: hidden;
            background: linear-gradient(180deg, #1a237e 0%, #283593 100%);
            box-shadow: 4px 0 15px rgba(0,0,0,0.12);
        }
        .dark .sidebar {
            background: linear-gradient(180deg, #0f172a 0%, #1e293b 100%);
        }
        .logo-container {
            background: #fff;
            border-radius: 12px;
            padding: 8px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.06);
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
        @media (max-width: 768px) {
            .sidebar { transform: translateX(-100%); }
            .sidebar.open { transform: translateX(0); }
        }
    </style>
</head>
<body class="font-sans antialiased">
    <div>
        <!-- Sidebar -->
        <aside id="sidebar" class="sidebar">
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
                    <!-- All links, including Production, are direct children of nav for full background coverage -->
                    <a href="{{route('manufacturer.dashboard')}}" class="nav-link flex items-center px-3 py-2 text-gray-300 hover:bg-indigo-700 hover:text-white rounded-xl">
                        <i class="fas fa-home w-5"></i>
                        <span class="ml-2 font-medium text-sm">Home</span>
                    </a>
                    <a href="{{route('manufacturer.orders')}}" class="nav-link flex items-center px-3 py-2 text-gray-300 hover:bg-indigo-700 hover:text-white rounded-xl">
                        <i class="fas fa-box w-5"></i>
                        <span class="ml-2 text-sm">Orders</span>
                    </a>
                    <a href="{{route('manufacturer.analytics')}}" class="nav-link flex items-center px-3 py-2 text-gray-300 hover:bg-indigo-700 hover:text-white rounded-xl">
                        <i class="fas fa-chart-pie w-5"></i>
                        <span class="ml-2 text-sm">Analytics</span>
                    </a>
                    <a href="{{route('manufacturer.inventory')}}" class="nav-link flex items-center px-3 py-2 text-gray-300 hover:bg-indigo-700 hover:text-white rounded-xl">
                        <i class="fas fa-warehouse w-5"></i>
                        <span class="ml-2 text-sm">Inventory</span>
                    </a>
                    <a href="{{route('manufacturer.partners.manage')}}" class="nav-link flex items-center px-3 py-2 text-gray-300 hover:bg-indigo-700 hover:text-white rounded-xl">
                        <i class="fas fa-users w-5"></i>
                        <span class="ml-2 text-sm">Partners</span>
                    </a>
                    <a href="{{route('manufacturer.workforce.index')}}" class="nav-link flex items-center px-3 py-2 text-gray-300 hover:bg-indigo-700 hover:text-white rounded-xl">
                        <i class="fas fa-user-tie w-5"></i>
                        <span class="ml-2 text-sm">Workforce</span>
                    </a>
                    <a href="{{route('manufacturer.warehouse.index')}}" class="nav-link flex items-center px-3 py-2 text-gray-300 hover:bg-indigo-700 hover:text-white rounded-xl">
                        <i class="fas fa-warehouse w-5"></i>
                        <span class="ml-2 text-sm">Warehouses</span>
                    </a>
                    <a href="{{route('manufacturer.chat')}}" class="nav-link flex items-center px-3 py-2 text-gray-300 hover:bg-indigo-700 hover:text-white rounded-xl">
                        <i class="fas fa-comments w-5"></i>
                        <span class="ml-2 text-sm">Chat</span>
                    </a>
                    <a href="{{route('manufacturer.reports')}}" class="nav-link flex items-center px-3 py-2 text-gray-300 hover:bg-indigo-700 hover:text-white rounded-xl">
                        <i class="fas fa-file-alt w-5"></i>
                        <span class="ml-2 text-sm">Reports</span>
                    </a>
                    <a href="{{route('manufacturer.revenue')}}" class="nav-link flex items-center px-3 py-2 text-gray-300 hover:bg-indigo-700 hover:text-white rounded-xl">
                        <i class="fas fa-coins w-5 text-yellow-500"></i>
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
                    <!-- Mobile Menu Toggle -->
                    <button id="menu-toggle" class="md:hidden p-3 text-gray-500 hover:text-gray-700">
                        <i class="fas fa-bars text-lg"></i>
                    </button>
                    <!-- Search Bar -->
                    <div class="relative ml-3 hidden md:block">
                        <span class="absolute inset-y-0 left-0 flex items-center pl-3">
                            <i class="fas fa-search text-gray-400"></i>
                        </span>
                        <input type="text" id="manufacturerUniversalSearch" class="w-80 py-2 pl-10 pr-4 border border-gray-300 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-transparent text-sm" placeholder="Search...">
                    </div>
                </div>
                <div class="flex items-center pr-4 space-x-3">
                    <!-- Notifications Dropdown -->
                    <div class="relative">
                        <button id="notificationDropdownBtn" class="p-2 text-gray-500 hover:text-indigo-600 hover:bg-indigo-50 rounded-full transition-colors focus:outline-none relative">
                            <i class="fas fa-bell text-lg"></i>
                            @php $unreadCount = auth()->user()->unreadNotifications->count(); @endphp
                            @if($unreadCount > 0)
                                <span class="absolute -top-1 -right-1 inline-flex items-center justify-center px-1.5 py-0.5 text-xs font-bold leading-none text-white bg-red-600 rounded-full min-w-[18px] min-h-[18px]">{{ $unreadCount }}</span>
                            @endif
                        </button>
                        <div id="notificationDropdown" class="hidden absolute right-0 mt-2 w-80 bg-white border border-gray-200 rounded-lg shadow-lg z-50">
                            <div class="p-4 border-b font-semibold">Notifications</div>
                            <ul class="max-h-64 overflow-y-auto">
                                @forelse(auth()->user()->unreadNotifications as $notification)
                                    <li class="px-4 py-2 border-b hover:bg-gray-50">
                                        <div class="text-sm">{{ $notification->data['message'] ?? 'You have a new notification.' }}</div>
                                        <div class="text-xs text-gray-400">{{ $notification->created_at ? \Carbon\Carbon::parse($notification->created_at)->diffForHumans() : 'N/A' }}</div>
                                    </li>
                                @empty
                                    <li class="px-4 py-2 text-gray-500 text-sm">No new notifications.</li>
                                @endforelse
                            </ul>
                            @if(auth()->user()->unreadNotifications->count() > 0)
                                <form id="markAllReadForm" class="p-2 text-center">
                                    @csrf
                                    <button type="submit" class="text-xs text-indigo-600 hover:underline">Mark all as read</button>
                                </form>
                                <script>
                                document.addEventListener('DOMContentLoaded', function() {
                                    const form = document.getElementById('markAllReadForm');
                                    if (form) {
                                        form.addEventListener('submit', function(e) {
                                            e.preventDefault();
                                            fetch('{{ route('manufacturer.notifications.markAsRead') }}', {
                                                method: 'POST',
                                                headers: {
                                                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                                    'X-Requested-With': 'XMLHttpRequest'
                                                }
                                            })
                                            .then(response => response.json())
                                            .then(data => {
                                                if (data.success) {
                                                    form.innerHTML = '<span class="text-xs text-green-600">All marked as read!</span>';
                                                    // Hide the red badge
                                                    const bellBtn = document.getElementById('notificationDropdownBtn');
                                                    const badge = bellBtn.querySelector('span.bg-red-600');
                                                    if (badge) badge.style.display = 'none';
                                                    // Optionally clear the notifications list
                                                    const dropdown = document.getElementById('notificationDropdown');
                                                    if (dropdown) {
                                                        const ul = dropdown.querySelector('ul');
                                                        if (ul) ul.innerHTML = '<li class="px-4 py-2 text-gray-500 text-sm">No new notifications.</li>';
                                                    }
                                                }
                                            });
                                        });
                                    }
                                });
                                </script>
                            @endif
                        </div>
                    </div>
                    <button data-theme-toggle class="p-2 text-gray-500 hover:text-indigo-600 hover:bg-indigo-50 rounded-full transition-colors" title="Switch Theme">
                        <i class="fas fa-moon text-lg"></i>
                    </button>
                    <!-- Profile Dropdown -->
                    <div class="relative">
                        <button class="flex items-center focus:outline-none bg-white rounded-full p-2 shadow-md hover:shadow-lg transition-shadow">
                            <span class="mr-2 text-gray-700 font-medium text-sm">{{ Auth::user()->name ?? 'Manufacturer User' }}</span>
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
            <main class="main-content-scrollable" style="flex: 1 1 0%; overflow-y: auto; padding: 2rem 1.5rem; margin-top: 4rem; background: transparent;">
                @yield('content')
            </main>
        </div>
    </div>
    <script>
        // Simple dropdown toggle for notifications
        document.addEventListener('DOMContentLoaded', function() {
            const btn = document.getElementById('notificationDropdownBtn');
            const dropdown = document.getElementById('notificationDropdown');
            if (btn && dropdown) {
                btn.addEventListener('click', function(e) {
                    e.stopPropagation();
                    dropdown.classList.toggle('hidden');
                });
                document.addEventListener('click', function() {
                    dropdown.classList.add('hidden');
                });
            }

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
        });
    </script>
    {{-- Profile Editor Modal --}}
    <x-profile-editor-modal />
</body>
</html> 