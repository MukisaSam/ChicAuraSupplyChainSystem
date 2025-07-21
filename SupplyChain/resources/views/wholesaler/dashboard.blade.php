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
            background: #f5f7fa; 
            min-height: 100vh;
        }
        .sidebar { 
            transition: transform 0.3s ease-in-out;
            background: #1a237e; /* Solid blue for most of the sidebar */
            box-shadow: 4px 0 15px rgba(0,0,0,0.08);
        }
        .sidebar .sidebar-logo-blend {
            background: #fff; /* Solid white for logo area */
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
        .card-gradient {
            background: #fff;
            border: 1px solid #e3e8ee;
            box-shadow: 0 8px 32px rgba(0,0,0,0.06);
        }
        .dark .card-gradient {
            background: linear-gradient(135deg, #1e293b 0%, #334155 100%);
            border: 1px solid #475569;
        }
        .stat-card {
            background: #f5f7fa;
            border: 1px solid #e3e8ee;
            box-shadow: 0 4px 20px rgba(0,0,0,0.04);
            transition: transform 0.3s, box-shadow 0.3s;
        }
        .dark .stat-card {
            background: linear-gradient(135deg, #1e293b 0%, #334155 100%);
            border: 1px solid #475569;
        }
        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 25px rgba(0,0,0,0.10);
        }
        .nav-link {
            transition: all 0.3s ease;
            border-radius: 12px;
            margin: 4px 0;
        }
        .nav-link:hover {
            background: #ede7f6; /* Light purple accent on hover */
            color: #512da8;
            transform: translateX(5px);
        }
        .header-gradient {
            background: #fff;
            box-shadow: 0 2px 20px rgba(0,0,0,0.06);
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
                    <div class="sidebar-logo-blend w-full h-16 flex items-center justify-center p-0 m-0" style="background:#fff;">
                        <img src="{{ asset('images/logo.png') }}" alt="ChicAura Logo" class="w-full h-auto object-contain max-w-[160px] max-h-[48px]">
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
                        <input type="text" id="wholesalerUniversalSearch" class="w-80 py-2 pl-10 pr-4 border border-gray-300 rounded-xl focus:ring-2 focus:ring-purple-500 focus:border-transparent text-sm" placeholder="Search orders, products, invoices, reports, chat...">
                    </div>
                </div>
                <div class="flex items-center pr-4 space-x-3">
                    <div class="relative">
                        <x-wholesaler-notification-bell />
                    </div>
                    {{-- Theme Toggle --}}
                    <button data-theme-toggle class="p-2 text-gray-500 hover:text-purple-600 hover:bg-purple-50 dark:text-gray-400 dark:hover:text-purple-400 dark:hover:bg-purple-900/20 rounded-full transition-colors" title="Switch Theme">
                        <i class="fas fa-moon text-lg"></i>
                    </button>
                    <div class="relative">
                        <button class="flex items-center focus:outline-none bg-white rounded-full p-2 shadow-md hover:shadow-lg transition-shadow">
                            <span class="mr-2 text-gray-700 font-medium text-sm">{{ Auth::user()->name ?? 'Wholesaler User' }}</span>
                            <img class="w-7 h-7 rounded-full border-2 border-purple-200 object-cover" 
                                 src="{{ Auth::user()->profile_picture ? asset('storage/profile-pictures/' . basename(Auth::user()->profile_picture)) : asset('images/default-avatar.svg') }}" 
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
                    <h2 class="text-2xl font-bold text-gray-900 mb-1">Wholesaler Dashboard</h2>
                    <p class="text-gray-700 text-sm">Track your orders and manage your wholesale operations.</p>
                </div>
                
                <div class="grid grid-cols-1 gap-4 mt-4 sm:grid-cols-2 lg:grid-cols-4">
                    <div class="p-6 rounded-xl bg-white border border-gray-200 shadow stat-card dark:bg-gray-800 dark:border-gray-700">
                        <div class="flex items-center">
                            <div class="p-3 rounded-xl bg-purple-600">
                                <i class="fas fa-shopping-cart text-white text-xl"></i>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm text-gray-900 dark:text-white">Total Orders</p>
                                <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $stats['total_orders'] ?? 0 }}</p>
                                <p class="text-sm text-purple-600 dark:text-purple-300 mt-1">↗ +10% this month</p>
                            </div>
                        </div>
                    </div>
                    <div class="p-6 rounded-xl bg-white border border-gray-200 shadow stat-card dark:bg-gray-800 dark:border-gray-700">
                        <div class="flex items-center">
                            <div class="p-3 rounded-xl bg-green-600">
                                <i class="fas fa-money-bill-wave text-white text-xl"></i>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm text-gray-900 dark:text-white">Revenue</p>
                                <p class="text-2xl font-bold text-gray-900 dark:text-white">UGX {{ number_format($stats['revenue'] ?? '0', 2) }}</p>
                                <p class="text-sm text-green-600 dark:text-green-300 mt-1">↗ +8% this month</p>
                            </div>
                        </div>
                    </div>
                    <div class="p-6 rounded-xl bg-white border border-gray-200 shadow stat-card dark:bg-gray-800 dark:border-gray-700">
                        <div class="flex items-center">
                            <div class="p-3 rounded-xl bg-blue-600">
                                <i class="fas fa-chart-line text-white text-xl"></i>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm text-gray-900 dark:text-white">Avg Order Value</p>
                                <p class="text-2xl font-bold text-gray-900 dark:text-white">UGX {{ number_format($stats['avg_order_value'] ?? '0', 2) }}</p>
                                <p class="text-sm text-blue-600 dark:text-blue-300 mt-1">↗ +5% this month</p>
                            </div>
                        </div>
                    </div>
                    <div class="p-6 rounded-xl bg-white border border-gray-200 shadow stat-card dark:bg-gray-800 dark:border-gray-700">
                        <div class="flex items-center">
                            <div class="p-3 rounded-xl bg-orange-500">
                                <i class="fas fa-clock text-white text-xl"></i>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm text-gray-900 dark:text-white">Pending Orders</p>
                                <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $stats['pending_orders'] ?? 0 }}</p>
                                <p class="text-sm text-orange-600 dark:text-orange-300 mt-1">{{ $stats['pending_orders_change'] ?? 0 }}% from last month</p>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="grid grid-cols-1 gap-4 mt-4 lg:grid-cols-3 h-64">
                    <div class="card-gradient p-4 rounded-xl lg:col-span-2 overflow-hidden bg-white border border-gray-200 shadow dark:bg-gray-800 dark:border-gray-700">
                        <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-3">Purchase History (UGX)</h3>
                        <canvas id="purchaseChart" class="w-full h-48"></canvas>
                    </div>
                    <div class="card-gradient p-4 rounded-xl bg-white border border-gray-200 shadow dark:bg-gray-800 dark:border-gray-700">
                        <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-3">Recent Orders</h3>
                        <div class="space-y-2 h-48 overflow-y-auto">
                            @forelse ($recentOrders as $order)
                                <div class="flex items-center p-3 bg-gray-50 rounded-lg shadow-sm hover:shadow-md transition-shadow recent-order-row border border-gray-100 dark:bg-gray-900 dark:border-gray-800">
                                    <div class="flex-shrink-0">
                                        <div class="w-10 h-10 flex items-center justify-center rounded-full {{ $order['status_color'] }} bg-opacity-10">
                                            <i class="fas {{ $order['icon'] }} {{ $order['status_color'] }} text-sm"></i>
                                        </div>
                                    </div>
                                    <div class="ml-3 flex-1">
                                        <p class="text-xs font-medium text-gray-900 dark:text-white">{{ $order['item_summary'] }}</p>
                                        <p class="text-xs text-gray-500 dark:text-gray-300">Order #{{ $order['id'] }}</p>
                                    </div>
                                    <div class="text-right">
                                        <p class="text-xs font-semibold text-gray-900 dark:text-white">UGX {{ number_format($order['amount'], 2) }}</p>
                                        <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full {{ $order['status_color'] }} text-white">{{ $order['status'] }}</span>
                                    </div>
                                </div>
                            @empty 
                                <div class="text-center py-6">
                                    <i class="fas fa-inbox text-gray-400 dark:text-gray-600 text-2xl mb-2"></i>
                                    <p class="text-gray-500 dark:text-gray-300 text-sm">No recent orders.</p>
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Mobile menu toggle
            const menuToggle = document.getElementById('menu-toggle');
            if (menuToggle) {
                menuToggle.addEventListener('click', function() {
                    document.getElementById('sidebar').classList.toggle('open');
                });
            }

            // Purchase History Chart
            const purchaseCtx = document.getElementById('purchaseChart').getContext('2d');
            new Chart(purchaseCtx, {
                type: 'line',
                data: {
                    labels: @json($purchaseHistoryLabels),
                    datasets: [{
                        label: 'Purchase Amount (UGX)',
                        data: @json($purchaseHistoryData),
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
                            },
                            ticks: {
                                callback: function(value) {
                                    return 'UGX ' + value.toLocaleString();
                                }
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

            if (notificationBtn && notificationDropdown) {
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
            }
            if (markAllReadBtn) {
                markAllReadBtn.addEventListener('click', function() {
                    fetch('/wholesaler/notifications/mark-all-read', { method: 'POST', headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' } })
                        .then(() => {
                            loadNotifications();
                        });
                });
            }

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
            // Initial load
            loadNotifications();

            // Universal search
            const searchInput = document.getElementById('wholesalerUniversalSearch');
            console.log('Universal search script loaded.');
            if (searchInput) {
                console.log('Search input found.');
                // Log the actual elements found
                const statCards = document.querySelectorAll('.stat-card');
                const recentOrders = document.querySelectorAll('.recent-order-row');
                console.log('Stat cards:', statCards);
                console.log('Recent order rows:', recentOrders);
                searchInput.addEventListener('input', function() {
                    const searchTerm = this.value.toLowerCase();
                    console.log('Search term:', searchTerm);
                    // Filter stat cards
                    statCards.forEach(card => {
                        card.style.display = card.textContent.toLowerCase().includes(searchTerm) ? '' : 'none';
                    });
                    // Filter recent orders
                    recentOrders.forEach(order => {
                        order.style.display = order.textContent.toLowerCase().includes(searchTerm) ? '' : 'none';
                    });
                });
            } else {
                console.log('Search input NOT found.');
            }
        });
    </script>

    {{-- Profile Editor Modal --}}
    <x-profile-editor-modal />
</body>
</html>