<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manufacturer Portal - ChicAura SCM</title>
    <!-- Tailwind CSS via CDN for standalone use, but it's already included in Laravel/Breeze setup -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- FontAwesome for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <!-- Chart.js for graphs -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="{{ asset('js/theme-switcher.js') }}"></script>
    <style>
        /* Custom styles for a better look and feel */
        body { 
            background: linear-gradient(135deg, rgba(0,0,0,0.7) 0%, rgba(0,0,0,0.5) 100%), url('{{ asset('images/manufacturer.png') }}');
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
            min-height: 100vh;
        }
        
        /* Dark mode styles */
        .dark body {
            background: linear-gradient(135deg, rgba(0,0,0,0.8) 0%, rgba(0,0,0,0.7) 100%), url('{{ asset('images/manufacturer.png') }}');
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
    <div class="flex h-full">
        <!-- Sidebar -->
        <aside id="sidebar" class="sidebar absolute md:relative z-20 flex-shrink-0 w-64 md:block">
            <div class="flex flex-col h-full">
                <!-- Sidebar Header -->
                <div class="flex items-center justify-center h-16 border-b border-gray-600">
                    <div class="logo-container">
                        <img src="{{ asset('images/CA-WORD2.png') }}" alt="ChicAura Logo" class="w-full h-auto object-contain max-w-[160px] max-h-[48px]">
                    </div>
                </div>
                <div class="px-4 py-4">
                    <h3 class="text-white text-sm font-semibold mb-3 px-3">MANUFACTURER PORTAL</h3>
                </div>
                <!-- Sidebar Navigation -->
                <nav class="flex-1 px-4 py-2 space-y-1">
                    <a href="{{route('manufacturer.dashboard')}}" class="nav-link flex items-center px-3 py-2 text-gray-300 hover:bg-gray-700 hover:text-white rounded-xl">
                        <i class="fas fa-home w-5"></i>
                        <span class="ml-2 text-sm">Home</span>
                    </a>
                    <a href="{{route('manufacturer.orders')}}" class="nav-link flex items-center px-3 py-2 text-gray-300 hover:bg-gray-700 hover:text-white rounded-xl">
                        <i class="fas fa-box w-5"></i>
                        <span class="ml-2 text-sm">Orders</span>
                    </a>
                    <a href="{{route('manufacturer.analytics')}}" class="nav-link flex items-center px-3 py-2 text-gray-300 hover:bg-gray-700 hover:text-white rounded-xl">
                        <i class="fas fa-chart-pie w-5"></i>
                        <span class="ml-2 text-sm">Analytics</span>
                    </a>
                    <a href="{{route('manufacturer.inventory')}}" class="nav-link flex items-center px-3 py-2 text-gray-300 hover:bg-gray-700 hover:text-white rounded-xl">
                        <i class="fas fa-warehouse w-5"></i>
                        <span class="ml-2 text-sm">Inventory</span>
                    </a>
                    <a href="{{route('manufacturer.workforce.index')}}" class="nav-link flex items-center px-3 py-2 text-gray-300 hover:bg-gray-700 hover:text-white rounded-xl">
                        <i class="fas fa-user-tie w-5"></i>
                        <span class="ml-2 text-sm">Workforce</span>
                    </a>
                    <a href="{{route('manufacturer.warehouse.index')}}" class="nav-link flex items-center px-3 py-2 text-gray-300 hover:bg-gray-700 hover:text-white rounded-xl">
                        <i class="fas fa-warehouse w-5"></i>
                        <span class="ml-2 text-sm">Warehouses</span>
                    </a>
                    <a href="{{route('manufacturer.partners.manage')}}" class="nav-link flex items-center px-3 py-2 text-gray-300 hover:bg-gray-700 hover:text-white rounded-xl">
                        <i class="fas fa-users w-5"></i>
                        <span class="ml-2 text-sm">Partners</span>
                    </a>
                    <a href="{{route('manufacturer.chat')}}" class="nav-link flex items-center px-3 py-2 text-gray-300 hover:bg-gray-700 hover:text-white rounded-xl">
                        <i class="fas fa-comments w-5"></i>
                        <span class="ml-2 text-sm">Chat</span>
                    </a>
                    <a href="{{route('manufacturer.reports')}}" class="nav-link flex items-center px-3 py-2 text-white bg-gradient-to-r from-indigo-600 to-indigo-700 rounded-xl shadow-lg">
                        <i class="fas fa-file-alt w-5"></i>
                        <span class="ml-2 text-sm">Reports</span>
                    </a>
                    <a href="{{route('manufacturer.revenue')}}" class="nav-link flex items-center px-3 py-2 text-gray-300 hover:bg-gray-700 hover:text-white rounded-xl">
                        <i class="fas fa-dollar-sign w-5"></i>
                        <span class="ml-2 text-sm">Revenue</span>
                    </a>
                    <!-- Production Section Heading -->
                    <div class="mt-6 mb-2">
                        <h4 class="text-gray-400 text-xs font-bold uppercase tracking-wider px-3 mb-1">Production</h4>
                    </div>
                    <a href="{{ route('manufacturer.work-orders.index') }}" class="nav-link flex items-center px-3 py-2 text-gray-300 hover:bg-gray-700 hover:text-white rounded-xl">
                        <i class="fas fa-cogs w-5"></i>
                        <span class="ml-2 text-sm">Work Orders</span>
                    </a>
                    <a href="{{ route('manufacturer.bom.index') }}" class="nav-link flex items-center px-3 py-2 text-gray-300 hover:bg-gray-700 hover:text-white rounded-xl">
                        <i class="fas fa-list-alt w-5"></i>
                        <span class="ml-2 text-sm">Bill of Materials</span>
                    </a>
                    <a href="{{ route('manufacturer.production-schedules.index') }}" class="nav-link flex items-center px-3 py-2 text-gray-300 hover:bg-gray-700 hover:text-white rounded-xl">
                        <i class="fas fa-calendar-alt w-5"></i>
                        <span class="ml-2 text-sm">Production Schedules</span>
                    </a>
                    <a href="{{ route('manufacturer.quality-checks.index') }}" class="nav-link flex items-center px-3 py-2 text-gray-300 hover:bg-gray-700 hover:text-white rounded-xl">
                        <i class="fas fa-clipboard-check w-5"></i>
                        <span class="ml-2 text-sm">Quality Checks</span>
                    </a>
                    <a href="{{ route('manufacturer.downtime-logs.index') }}" class="nav-link flex items-center px-3 py-2 text-gray-300 hover:bg-gray-700 hover:text-white rounded-xl">
                        <i class="fas fa-stopwatch w-5"></i>
                        <span class="ml-2 text-sm">Downtime Logs</span>
                    </a>
                    <a href="{{ route('manufacturer.production-costs.index') }}" class="nav-link flex items-center px-3 py-2 text-gray-300 hover:bg-gray-700 hover:text-white rounded-xl">
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

        <div class="flex flex-col flex-1 w-full">
            <!-- Top Navigation Bar -->
            <header class="header-gradient relative z-10 flex items-center justify-between h-16 border-b">
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
                        <input type="text" id="manufacturerUniversalSearch" class="w-80 py-2 pl-10 pr-4 border border-gray-300 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-transparent text-sm" placeholder="Search orders, inventory, suppliers...">
                    </div>
                </div>
                <div class="flex items-center pr-4 space-x-3">
                    <x-notification-bell />
                    <button data-theme-toggle class="p-2 text-gray-500 hover:text-indigo-600 hover:bg-indigo-50 rounded-full transition-colors" title="Switch Theme">
                        <i class="fas fa-moon text-lg"></i>
                    </button>
                    <!-- Profile Dropdown -->
                    <div class="relative">
                        <button class="flex items-center focus:outline-none bg-white rounded-full p-2 shadow-md hover:shadow-lg transition-shadow">
                            <span class="mr-2 text-gray-700 font-medium text-sm">{{ Auth::user()->name ?? 'Manufacturer User' }}</span>
                            <img class="w-7 h-7 rounded-full border-2 border-indigo-200 object-cover" 
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
            <main class="flex-1 overflow-y-auto p-8">
                <h1 class="text-3xl font-bold mb-2 text-indigo-800 dark:text-white flex items-center gap-3">
                    <i class="fas fa-file-alt text-indigo-600"></i> Manufacturer Portal Reports
                </h1>
                <p class="text-white mb-8 text-lg">Access and export detailed reports on sales, inventory, suppliers, and order fulfillment to power your business decisions.</p>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                    <a href="{{ route('manufacturer.reports.sales') }}" class="stat-card p-6 flex flex-col items-center hover:shadow-2xl hover:scale-105 transition-transform group">
                        <i class="fas fa-chart-line text-4xl text-indigo-600 mb-3 group-hover:text-indigo-800 transition-colors"></i>
                        <span class="font-semibold text-lg mb-1">Sales Report</span>
                        <span class="text-gray-500 text-sm">Track revenue and sales trends</span>
                    </a>
                    <a href="{{ route('manufacturer.reports.inventory') }}" class="stat-card p-6 flex flex-col items-center hover:shadow-2xl hover:scale-105 transition-transform group">
                        <i class="fas fa-boxes text-4xl text-green-600 mb-3 group-hover:text-green-800 transition-colors"></i>
                        <span class="font-semibold text-lg mb-1">Inventory Report</span>
                        <span class="text-gray-500 text-sm">Monitor stock levels and movement</span>
                    </a>
                    <a href="{{ route('manufacturer.reports.suppliers') }}" class="stat-card p-6 flex flex-col items-center hover:shadow-2xl hover:scale-105 transition-transform group">
                        <i class="fas fa-truck text-4xl text-yellow-600 mb-3 group-hover:text-yellow-800 transition-colors"></i>
                        <span class="font-semibold text-lg mb-1">Supplier Performance</span>
                        <span class="text-gray-500 text-sm">Evaluate supplier reliability</span>
                    </a>
                    <a href="{{ route('manufacturer.reports.fulfillment') }}" class="stat-card p-6 flex flex-col items-center hover:shadow-2xl hover:scale-105 transition-transform group">
                        <i class="fas fa-shipping-fast text-4xl text-blue-600 mb-3 group-hover:text-blue-800 transition-colors"></i>
                        <span class="font-semibold text-lg mb-1">Order Fulfillment</span>
                        <span class="text-gray-500 text-sm">Analyze delivery and fulfillment</span>
                    </a>
                </div>
                <div class="mt-12">
                    <h2 class="text-xl font-semibold mb-4 flex items-center gap-2">
                        <i class="fas fa-file-export text-indigo-500"></i> Export Reports
                    </h2>
                    <div class="flex flex-wrap gap-4">
                        <a href="{{ route('manufacturer.reports.export', ['type' => 'sales']) }}" class="flex items-center gap-2 px-5 py-3 bg-indigo-600 text-white rounded-lg font-semibold shadow hover:bg-indigo-700 transition-colors">
                            <i class="fas fa-file-csv"></i> Export Sales (CSV)
                        </a>
                        <!-- Add more export buttons as needed, following the same style -->
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

        // Production Chart
        const productionCtx = document.getElementById('productionChart');
        if (productionCtx) {
            new Chart(productionCtx, {
                type: 'line',
                data: {
                    labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'],
                    datasets: [{
                        label: 'Production Volume',
                        data: [65, 59, 80, 81, 56, 55],
                        borderColor: 'rgb(99, 102, 241)',
                        backgroundColor: 'rgba(99, 102, 241, 0.1)',
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
                            beginAtZero: true
                        }
                    }
                }
            });
        }
    </script>
    <script>
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
    </script>
</body>
</html>