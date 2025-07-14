{{-- resources/views/wholesaler/dashboard.blade.php --}}
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Wholesaler Dashboard - ChicAura SCM</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="{{ asset('js/theme-switcher.js') }}"></script>
    <style>
        body { 
            background: linear-gradient(135deg, rgba(0,0,0,0.7) 0%, rgba(0,0,0,0.5) 100%), url('{{ asset('images/wholesaler.jpg') }}');
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
            min-height: 100vh;
        }
        
        /* Dark mode styles */
        .dark body {
            background: linear-gradient(135deg, rgba(0,0,0,0.8) 0%, rgba(0,0,0,0.7) 100%), url('{{ asset('images/wholesaler.jpg') }}');
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
                        <img src="{{ asset('images/CA-WORD2.png') }}" alt="ChicAura Logo" class="w-full h-auto object-contain max-w-[160px] max-h-[48px]">
                    </div>
                </div>
                <div class="px-4 py-4">
                    <h3 class="text-white text-sm font-semibold mb-3 px-3">WHOLESALER PORTAL</h3>
                </div>
                <nav class="flex-1 px-4 py-2 space-y-1">
                    <a href="#" class="nav-link flex items-center px-3 py-2 text-white bg-gradient-to-r from-purple-600 to-purple-700 rounded-xl shadow-lg"><i class="fas fa-home w-5"></i><span class="ml-2 font-medium text-sm">Home</span></a>
                    <a href="{{ route('wholesaler.orders.index') }}" class="nav-link flex items-center px-3 py-2 text-gray-300 hover:bg-gray-700 hover:text-white rounded-xl"><i class="fas fa-shopping-cart w-5"></i><span class="ml-2 text-sm">Orders</span></a>
                    <a href="{{ route('wholesaler.analytics.index') }}" class="nav-link flex items-center px-3 py-2 text-gray-300 hover:bg-gray-700 hover:text-white rounded-xl"><i class="fas fa-chart-line w-5"></i><span class="ml-2 text-sm">Analytics</span></a>
                    <a href="{{ route('wholesaler.chat.index') }}" class="nav-link flex items-center px-3 py-2 text-gray-300 hover:bg-gray-700 hover:text-white rounded-xl"><i class="fas fa-comments w-5"></i><span class="ml-2 text-sm">Chat</span></a>
                    <a href="{{ route('wholesaler.reports.index') }}" class="nav-link flex items-center px-3 py-2 text-gray-300 hover:bg-gray-700 hover:text-white rounded-xl"><i class="fas fa-file-invoice-dollar w-5"></i><span class="ml-2 text-sm">Reports</span></a>
                    <a href="{{ route('wholesaler.invoices.index') }}" class="nav-link flex items-center px-3 py-2 text-gray-300 hover:bg-gray-700 hover:text-white rounded-xl"><i class="fas fa-file-invoice w-5"></i><span class="ml-2 text-sm">Invoices</span></a>
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
                        <input type="text" id="dashboardSearchInput" class="w-80 py-2 pl-10 pr-4 border border-gray-300 rounded-xl focus:ring-2 focus:ring-purple-500 focus:border-transparent text-sm" placeholder="Search products, orders...">
                    </div>
                </div>
                <div class="flex items-center pr-4 space-x-3">
                    <div class="relative">
                        <x-wholesaler-notification-bell />
                    </div>
                    <button data-theme-toggle class="p-2 text-gray-500 hover:text-purple-600 hover:bg-purple-50 rounded-full transition-colors" title="Switch Theme">
                        <i class="fas fa-moon text-lg"></i>
                    </button>
                    <div class="relative">
                        <button class="flex items-center focus:outline-none bg-white rounded-full p-2 shadow-md hover:shadow-lg transition-shadow">
                            <span class="mr-2 text-gray-700 font-medium text-sm">{{ Auth::user()->name ?? 'Wholesaler User' }}</span>
                            <img class="w-7 h-7 rounded-full border-2 border-purple-200 object-cover" 
                                 src="{{ Auth::user()->profile_picture ? Storage::disk('public')->url(Auth::user()->profile_picture) : asset('images/default-avatar.svg') }}" 
                                 alt="User Avatar">
                        </button>
                    </div>
                    <div class="flex items-center space-x-2">
                        <button type="button" class="p-2 text-gray-500 hover:text-purple-600 hover:bg-purple-50 rounded-full transition-colors" title="Edit Profile" x-data x-on:click="$dispatch('open-modal', 'profile-editor-modal')">
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
            <main class="flex-1 p-4">
                <div class="mb-4">
                    <h2 class="text-2xl font-bold text-white mb-1">Wholesaler Dashboard</h2>
                    <p class="text-gray-200 text-sm">Track your orders and manage your wholesale operations.</p>
                </div>
                
                <div class="grid grid-cols-1 gap-4 mt-4 sm:grid-cols-2 lg:grid-cols-4">
                    <div class="p-6 rounded-xl bg-white dark:bg-slate-800 border border-gray-200 dark:border-slate-700 shadow">
                        <div class="flex items-center">
                            <div class="p-3 rounded-xl bg-indigo-500 dark:bg-indigo-700">
                                <i class="fas fa-receipt text-white text-xl"></i>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm text-gray-700 dark:text-gray-300">Total Orders</p>
                                <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $stats['total_orders'] ?? '0' }}</p>
                                <p class="text-sm text-indigo-600 dark:text-indigo-400 mt-1">↗ +18% this month</p>
                            </div>
                        </div>
                    </div>

                    <div class="p-6 rounded-xl bg-white dark:bg-slate-800 border border-gray-200 dark:border-slate-700 shadow">
                        <div class="flex items-center">
                            <div class="p-3 rounded-xl bg-green-500 dark:bg-green-700">
                                <i class="fas fa-dollar-sign text-white text-xl"></i>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm text-gray-700 dark:text-gray-300">Revenue</p>
                                <p class="text-2xl font-bold text-gray-900 dark:text-white">${{ number_format($stats['total_revenue'] ?? '0', 2) }}</p>
                                <p class="text-sm text-green-600 dark:text-green-400 mt-1">↗ +12% this month</p>
                            </div>
                        </div>
                    </div>

                    <div class="p-6 rounded-xl bg-white dark:bg-slate-800 border border-gray-200 dark:border-slate-700 shadow">
                        <div class="flex items-center">
                            <div class="p-3 rounded-xl bg-purple-500 dark:bg-purple-700">
                                <i class="fas fa-chart-line text-white text-xl"></i>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm text-gray-700 dark:text-gray-300">Avg Order Value</p>
                                <p class="text-2xl font-bold text-gray-900 dark:text-white">${{ number_format($stats['avg_order_value'] ?? '0', 2) }}</p>
                                <p class="text-sm text-purple-600 dark:text-purple-400 mt-1">↗ +5% this month</p>
                            </div>
                        </div>
                    </div>

                    <div class="p-6 rounded-xl bg-white dark:bg-slate-800 border border-gray-200 dark:border-slate-700 shadow">
                        <div class="flex items-center">
                            <div class="p-3 rounded-xl bg-orange-500 dark:bg-orange-700">
                                <i class="fas fa-clock text-white text-xl"></i>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm text-gray-700 dark:text-gray-300">Pending Orders</p>
                                <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $stats['pending_orders'] ?? 0 }}</p>
                                <p class="text-sm text-orange-600 dark:text-orange-400 mt-1">{{ $stats['pending_orders_change'] ?? 0 }}% from last month</p>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="grid grid-cols-1 gap-4 mt-4 lg:grid-cols-3 h-64">
                    <div class="card-gradient p-4 rounded-xl lg:col-span-2 overflow-hidden">
                        <h3 class="text-lg font-bold text-black mb-3">Purchase History ($)</h3>
                        <canvas id="purchaseChart" class="w-full h-48"></canvas>
                    </div>
                    <div class="card-gradient p-4 rounded-xl">
                        <h3 class="text-lg font-bold text-black mb-3">Recent Orders</h3>
                        <div class="space-y-2 h-48 overflow-y-auto">
                            @forelse ($recentOrders as $order)
                                <div class="flex items-center p-3 bg-white rounded-lg shadow-sm hover:shadow-md transition-shadow">
                                    <div class="flex-shrink-0">
                                        <div class="w-10 h-10 flex items-center justify-center rounded-full {{ $order['status_color'] }} bg-opacity-10">
                                            <i class="fas {{ $order['icon'] }} {{ $order['status_color'] }} text-sm"></i>
                                        </div>
                                    </div>
                                    <div class="ml-3 flex-1">
                                        <p class="text-xs font-medium text-gray-900">{{ $order['item_summary'] }}</p>
                                        <p class="text-xs text-gray-500">Order #{{ $order['id'] }}</p>
                                    </div>
                                    <div class="text-right">
                                        <p class="text-xs font-semibold text-gray-900">${{ number_format($order['amount'], 2) }}</p>
                                        <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full {{ $order['status_color'] }} text-white">{{ $order['status'] }}</span>
                                    </div>
                                </div>
                            @empty 
                                <div class="text-center py-6">
                                    <i class="fas fa-inbox text-gray-400 text-2xl mb-2"></i>
                                    <p class="text-gray-500 text-sm">No recent orders.</p>
                                </div>
                            @endforelse
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

        // Purchase History Chart
        const purchaseCtx = document.getElementById('purchaseChart').getContext('2d');
        new Chart(purchaseCtx, {
            type: 'line',
            data: {
                labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'],
                datasets: [{
                    label: 'Purchase Amount',
                    data: [15000, 25000, 18000, 32000, 28000, 35000],
                    borderColor: '#8B5CF6',
                    backgroundColor: 'rgba(139, 92, 246, 0.1)',
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

        // Notification dropdown logic
        const notificationBtn = document.getElementById('notificationDropdownBtn');
        const notificationDropdown = document.getElementById('notificationDropdown');
        const notificationBadge = document.getElementById('notificationBadge');
        const notificationList = document.getElementById('notificationList');
        const markAllReadBtn = document.getElementById('markAllReadBtn');

        notificationBtn.addEventListener('click', function(e) {
            notificationDropdown.classList.toggle('hidden');
            if (!notificationDropdown.classList.contains('hidden')) {
                loadNotifications();
            }
        });
        document.addEventListener('click', function(e) {
            if (!notificationBtn.contains(e.target) && !notificationDropdown.contains(e.target)) {
                notificationDropdown.classList.add('hidden');
            }
        });

        function loadNotifications() {
            fetch('/wholesaler/notifications')
                .then(response => response.json())
                .then(data => {
                    if (data.notifications && data.notifications.length > 0) {
                        notificationList.innerHTML = data.notifications.map(n => `
                            <div class="px-4 py-2 border-b last:border-b-0">
                                <div class="text-sm">${n.data.message}</div>
                                <div class="text-xs text-gray-400">${n.created_at_human}</div>
                            </div>
                        `).join('');
                    } else {
                        notificationList.innerHTML = '<div class="text-center text-gray-400 py-6">No new notifications.</div>';
                    }
                    // Update badge
                    if (data.unread_count > 0) {
                        notificationBadge.textContent = data.unread_count;
                        notificationBadge.style.display = 'inline-block';
                    } else {
                        notificationBadge.style.display = 'none';
                    }
                });
        }
        // Auto-refresh notifications every 30 seconds
        setInterval(loadNotifications, 30000);
        // Mark all as read
        markAllReadBtn.addEventListener('click', function() {
            fetch('/wholesaler/notifications/mark-all-read', { method: 'POST', headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' } })
                .then(() => {
                    loadNotifications();
                });
        });
        // Initial load
        loadNotifications();

        // Search functionality for dashboard cards and recent orders
        document.addEventListener('DOMContentLoaded', function() {
            const searchInput = document.getElementById('dashboardSearchInput');
            if (searchInput) {
                searchInput.addEventListener('input', function() {
                    const searchTerm = this.value.toLowerCase();
                    // Filter stat cards
                    document.querySelectorAll('.stat-card').forEach(card => {
                        card.style.display = card.textContent.toLowerCase().includes(searchTerm) ? '' : 'none';
                    });
                    // Filter recent orders
                    document.querySelectorAll('.card-gradient .flex.items-center.p-3').forEach(order => {
                        order.style.display = order.textContent.toLowerCase().includes(searchTerm) ? '' : 'none';
                    });
                });
            }
        });
    </script>

    {{-- Profile Editor Modal --}}
    <x-profile-editor-modal />
</body>
</html>