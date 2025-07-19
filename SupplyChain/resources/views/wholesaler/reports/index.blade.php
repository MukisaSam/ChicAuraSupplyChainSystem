{{-- resources/views/wholesaler/reports/index.blade.php --}}
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reports - ChicAura SCM</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <script src="{{ asset('js/theme-switcher.js') }}"></script>
    <style>
        body {
            background: #f5f7fa;
            min-height: 100vh;
        }
        
        /* Dark mode styles */
        .dark body {
            background: linear-gradient(135deg, rgba(0,0,0,0.8) 0%, rgba(0,0,0,0.7) 100%), url('{{ asset('images/wholesaler.jpg') }}');
        }
        
        .sidebar {
            transition: transform 0.3s ease-in-out;
            background: #1a237e;
            box-shadow: 4px 0 15px rgba(0,0,0,0.08);
        }
        .sidebar .sidebar-logo-blend {
            background: #fff;
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
        
        .nav-link {
            transition: all 0.3s ease;
            border-radius: 12px;
            margin: 4px 0;
        }
        .nav-link:hover {
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
        
        .dark .text-gray-900 {
            color: #f1f5f9;
        }
        
        .dark .text-gray-600 {
            color: #cbd5e1;
        }
        
        .dark .text-gray-500 {
            color: #94a3b8;
        }
        
        .dark .bg-white {
            background-color: #1e293b;
        }
        
        .dark .border-gray-200 {
            border-color: #475569;
        }
        
        .dark .hover\:bg-gray-700:hover {
            background-color: #475569;
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
                    <a href="{{ route('wholesaler.dashboard') }}" class="nav-link flex items-center px-3 py-2 text-gray-300 hover:bg-gray-700 hover:text-white rounded-xl"><i class="fas fa-home w-5"></i><span class="ml-2 text-sm">Home</span></a>
                    <a href="{{ route('wholesaler.orders.index') }}" class="nav-link flex items-center px-3 py-2 text-gray-300 hover:bg-gray-700 hover:text-white rounded-xl"><i class="fas fa-shopping-cart w-5"></i><span class="ml-2 text-sm">Orders</span></a>
                    <a href="{{ route('wholesaler.analytics.index') }}" class="nav-link flex items-center px-3 py-2 text-gray-300 hover:bg-gray-700 hover:text-white rounded-xl"><i class="fas fa-chart-line w-5"></i><span class="ml-2 text-sm">Analytics</span></a>
                    <a href="{{ route('wholesaler.chat.index') }}" class="nav-link flex items-center px-3 py-2 text-gray-300 hover:bg-gray-700 hover:text-white rounded-xl"><i class="fas fa-comments w-5"></i><span class="ml-2 text-sm">Chat</span></a>
                    <a href="{{ route('wholesaler.reports.index') }}" class="nav-link flex items-center px-3 py-2 text-white bg-gradient-to-r from-purple-600 to-purple-700 rounded-xl shadow-lg"><i class="fas fa-file-invoice-dollar w-5"></i><span class="ml-2 font-medium text-sm">Reports</span></a>
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
                    <button id="menu-toggle" class="md:hidden p-3 text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200"><i class="fas fa-bars text-lg"></i></button>
                    <div class="relative ml-3 hidden md:block">
                        <span class="absolute inset-y-0 left-0 flex items-center pl-3"><i class="fas fa-search text-gray-400"></i></span>
                        <input type="text" id="wholesalerUniversalSearch" class="w-80 py-2 pl-10 pr-4 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-purple-500 focus:border-transparent text-sm dark:bg-gray-700 dark:text-white" placeholder="Search orders, products, invoices, reports, chat...">
                    </div>
                </div>
                <div class="flex items-center pr-4 space-x-3">
                    <x-wholesaler-notification-bell />
                    <button data-theme-toggle class="p-2 text-gray-500 hover:text-purple-600 hover:bg-purple-50 dark:text-gray-400 dark:hover:text-purple-400 dark:hover:bg-purple-900/20 rounded-full transition-colors" title="Switch Theme">
                        <i class="fas fa-moon text-lg"></i>
                    </button>
                    <div class="relative">
                        <button class="flex items-center focus:outline-none bg-white dark:bg-gray-700 rounded-full p-2 shadow-md hover:shadow-lg transition-shadow">
                            <span class="mr-2 text-gray-700 dark:text-gray-200 font-medium text-sm">{{ $user->name ?? 'Wholesaler User' }}</span>
                            <img class="w-7 h-7 rounded-full border-2 border-purple-200 object-cover" 
                                 src="{{ $user->profile_picture ? asset('storage/profile-pictures/' . basename($user->profile_picture)) : asset('images/default-avatar.svg') }}" 
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
            <main class="flex-1 p-4 overflow-auto">
                <div class="container mx-auto px-2 sm:px-4 md:px-8 py-6">
                    <!-- Header -->
                    <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-6">
                        <div>
                            <h1 class="text-3xl font-bold text-gray-900 dark:text-white mb-2">Reports Dashboard</h1>
                            <p class="text-gray-700 dark:text-gray-300">Overview of your sales, orders, and performance metrics</p>
                        </div>
                        <form method="GET" class="flex flex-col sm:flex-row items-center gap-2 mt-4 md:mt-0">
                            <input type="date" name="start_date" value="{{ $startDate }}" class="form-input rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white">
                            <span class="mx-1 text-white">to</span>
                            <input type="date" name="end_date" value="{{ $endDate }}" class="form-input rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white">
                            <button type="submit" class="ml-2 px-4 py-2 bg-purple-600 text-white rounded-md hover:bg-purple-700">Filter</button>
                        </form>
                    </div>

                    <div class="flex justify-end mb-4">
                        <a href="{{ route('wholesaler.invoices.index') }}" class="inline-block px-4 py-2 bg-purple-600 text-white rounded-md hover:bg-purple-700 font-semibold">
                            <i class="fas fa-file-invoice mr-2"></i>View Invoices
                        </a>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6 mb-8">
                        <!-- Sales Overview Card -->
                        <div class="p-6 rounded-xl bg-white dark:bg-slate-800 border border-gray-200 dark:border-slate-700 shadow">
                            <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Sales Overview</h2>
                            <div class="flex flex-col gap-3">
                                <div class="flex justify-between text-sm">
                                    <span class="text-gray-600 dark:text-gray-400">Total Sales</span>
                                    <span class="font-bold text-gray-900 dark:text-white">UGX {{ number_format($salesOverview['current']['total_sales'], 2) }}</span>
                                </div>
                                <div class="flex justify-between text-sm">
                                    <span class="text-gray-600 dark:text-gray-400">Orders</span>
                                    <span class="font-bold text-gray-900 dark:text-white">{{ $salesOverview['current']['order_count'] }}</span>
                                </div>
                                <div class="flex justify-between text-sm">
                                    <span class="text-gray-600 dark:text-gray-400">Avg. Order Value</span>
                                    <span class="font-bold text-gray-900 dark:text-white">UGX {{ number_format($salesOverview['current']['avg_order_value'], 2) }}</span>
                                </div>
                                <div class="flex justify-between text-xs text-green-600 dark:text-green-400 mt-2">
                                    <span>Sales Growth</span>
                                    <span>{{ number_format($salesOverview['growth']['sales_growth'], 1) }}%</span>
                                </div>
                            </div>
                        </div>
                        <!-- Order Analytics Card -->
                        <div class="card-gradient p-6 rounded-xl">
                            <h2 class="text-lg font-semibold text-black mb-4">Order Analytics</h2>
                            <div class="flex flex-col gap-3">
                                <div class="flex justify-between text-sm">
                                    <span class="text-black">Total Orders</span>
                                    <span class="font-bold text-black">{{ $orderAnalytics['total_orders'] }}</span>
                                </div>
                                <div class="flex justify-between text-sm">
                                    <span class="text-black">Completed</span>
                                    <span class="font-bold text-black">{{ $orderAnalytics['completed_orders'] }}</span>
                                </div>
                                <div class="flex justify-between text-sm">
                                    <span class="text-black">Pending</span>
                                    <span class="font-bold text-black">{{ $orderAnalytics['pending_orders'] }}</span>
                                </div>
                                <div class="flex justify-between text-sm">
                                    <span class="text-black">Cancelled</span>
                                    <span class="font-bold text-black">{{ $orderAnalytics['cancelled_orders'] }}</span>
                                </div>
                            </div>
                        </div>
                        <!-- Top Products Card -->
                        <div class="p-6 rounded-xl bg-white dark:bg-slate-800 border border-gray-200 dark:border-slate-700 shadow">
                            <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Top Products</h2>
                            <div class="space-y-4">
                                @foreach($topProducts as $product)
                                <div class="flex items-center">
                                    <div class="flex-shrink-0">
                                        <img src="{{ $product->image_url ?? asset('images/default-product.jpg') }}" 
                                             alt="{{ $product->name }}" 
                                             class="w-10 h-10 rounded-lg object-cover">
                                    </div>
                                    <div class="ml-4 flex-1">
                                        <div class="text-sm font-medium text-gray-900 dark:text-white">
                                            {{ $product->name }}
                                        </div>
                                        <div class="text-sm text-gray-600 dark:text-gray-400">
                                            {{ $product->total_quantity }} units
                                        </div>
                                    </div>
                                    <div class="ml-4 text-right">
                                        <div class="text-sm font-medium text-gray-900 dark:text-white">
                                            UGX {{ number_format($product->total_revenue, 2) }}
                                        </div>
                                        <div class="text-xs text-gray-600 dark:text-gray-400">
                                            Revenue
                                        </div>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 gap-6 mb-8">
                        <!-- Export Options Card -->
                        <div class="card-gradient p-6 w-full rounded-xl">
                            <h2 class="text-lg font-semibold text-black mb-4">Export Reports</h2>
                            <div class="space-y-3">
                                <a href="{{ route('wholesaler.reports.sales', ['start_date' => $startDate, 'end_date' => $endDate]) }}" 
                                   class="block w-full px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 text-center">
                                    <i class="fas fa-chart-bar mr-2"></i>Sales Report
                                </a>
                                <a href="{{ route('wholesaler.reports.orders', ['start_date' => $startDate, 'end_date' => $endDate]) }}" 
                                   class="block w-full px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700 text-center">
                                    <i class="fas fa-list mr-2"></i>Order Report
                                </a>
                                <a href="{{ route('wholesaler.reports.export', ['type' => 'sales', 'start_date' => $startDate, 'end_date' => $endDate]) }}" 
                                   class="block w-full px-4 py-2 bg-purple-600 text-white rounded-md hover:bg-purple-700 text-center">
                                    <i class="fas fa-download mr-2"></i>Export CSV
                                </a>
                            </div>
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
    </script>
    <script>
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('wholesalerUniversalSearch');
    if (searchInput) {
        searchInput.addEventListener('input', function() {
            const searchTerm = this.value.toLowerCase();
            // Filter report tables
            document.querySelectorAll('table tbody tr').forEach(row => {
                row.style.display = row.textContent.toLowerCase().includes(searchTerm) ? '' : 'none';
            });
            // Filter report cards
            document.querySelectorAll('.card-gradient').forEach(card => {
                card.style.display = card.textContent.toLowerCase().includes(searchTerm) ? '' : 'none';
            });
        });
    }
});
</script>
</body>
</html>