<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manufacturer Dashboard - ChicAura SCM</title>
    <!-- Tailwind CSS via CDN for standalone use, but it's already included in Laravel/Breeze setup -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- FontAwesome for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <!-- Chart.js for graphs -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        /* Custom styles for a better look and feel */
        body {
            background-color: #f8fafc;
        }
        .sidebar {
            transition: transform 0.3s ease-in-out;
        }
        @media (max-width: 768px) {
            .sidebar {
                transform: translateX(-100%);
            }
            .sidebar.open {
                transform: translateX(0);
            }
        }
    </style>
</head>
<body class="font-sans antialiased">
    <div class="flex h-screen bg-gray-100">
        <!-- Sidebar -->
        <aside id="sidebar" class="sidebar absolute md:relative z-20 flex-shrink-0 w-64 bg-white border-r md:block">
            <div class="flex flex-col h-full">
                <!-- Sidebar Header -->
                <div class="flex items-center justify-center h-20 border-b">
                    <h1 class="text-2xl font-bold text-gray-800">ChicAura</h1>
                </div>
                <!-- Sidebar Navigation -->
                <nav class="flex-1 px-4 py-4 space-y-2">
                    <a href="#" class="flex items-center px-4 py-2 text-white bg-indigo-600 rounded-md">
                        <i class="fas fa-home w-6"></i>
                        <span class="ml-3">Home</span>
                    </a>
                    <a href="#" class="flex items-center px-4 py-2 text-gray-600 hover:bg-gray-200 rounded-md">
                        <i class="fas fa-box w-6"></i>
                        <span class="ml-3">Orders</span>
                    </a>
                    <a href="#" class="flex items-center px-4 py-2 text-gray-600 hover:bg-gray-200 rounded-md">
                        <i class="fas fa-chart-pie w-6"></i>
                        <span class="ml-3">Analytics</span>
                    </a>
                    <a href="#" class="flex items-center px-4 py-2 text-gray-600 hover:bg-gray-200 rounded-md">
                        <i class="fas fa-warehouse w-6"></i>
                        <span class="ml-3">Inventory</span>
                    </a>
                    <a href="#" class="flex items-center px-4 py-2 text-gray-600 hover:bg-gray-200 rounded-md">
                        <i class="fas fa-users w-6"></i>
                        <span class="ml-3">Wholesalers</span>
                    </a>
                    <a href="#" class="flex items-center px-4 py-2 text-gray-600 hover:bg-gray-200 rounded-md">
                        <i class="fas fa-truck-fast w-6"></i>
                        <span class="ml-3">Suppliers</span>
                    </a>
                    <a href="#" class="flex items-center px-4 py-2 text-gray-600 hover:bg-gray-200 rounded-md">
                        <i class="fas fa-comments w-6"></i>
                        <span class="ml-3">Chat</span>
                    </a>
                    <a href="#" class="flex items-center px-4 py-2 text-gray-600 hover:bg-gray-200 rounded-md">
                        <i class="fas fa-file-alt w-6"></i>
                        <span class="ml-3">Reports</span>
                    </a>
                     <a href="#" class="flex items-center px-4 py-2 text-gray-600 hover:bg-gray-200 rounded-md">
                        <i class="fas fa-dollar-sign w-6"></i>
                        <span class="ml-3">Revenue</span>
                    </a>
                </nav>
            </div>
        </aside>

        <div class="flex flex-col flex-1 w-full">
            <!-- Top Navigation Bar -->
            <header class="relative z-10 flex items-center justify-between h-20 bg-white border-b">
                <div class="flex items-center">
                    <!-- Mobile Menu Toggle -->
                    <button id="menu-toggle" class="md:hidden p-4 text-gray-500 hover:text-gray-700">
                        <i class="fas fa-bars text-xl"></i>
                    </button>
                    <!-- Search Bar -->
                    <div class="relative ml-4 hidden md:block">
                        <span class="absolute inset-y-0 left-0 flex items-center pl-3">
                            <i class="fas fa-search text-gray-400"></i>
                        </span>
                        <input type="text" class="w-full py-2 pl-10 pr-4 border rounded-md" placeholder="Search...">
                    </div>
                </div>
                <div class="flex items-center pr-4">
                    <button class="p-2 text-gray-500 hover:text-gray-700">
                        <i class="fas fa-bell text-xl"></i>
                    </button>
                    <!-- Profile Dropdown -->
                    <div class="relative ml-3">
                        <button class="flex items-center focus:outline-none">
                            <span class="mr-2">{{ $user->name ?? 'Admin User' }}</span>
                            <img class="w-8 h-8 rounded-full" src="https://via.placeholder.com/32" alt="User Avatar">
                        </button>
                    </div>
                </div>
            </header>

            <!-- Main Content -->
            <main class="flex-1 p-6 md:p-8">
                <h2 class="text-3xl font-semibold text-gray-800">Manufacturer Dashboard</h2>
                <p class="mt-2 text-gray-600">Welcome back! Here's an overview of your supply chain.</p>

                <!-- Stats Cards -->
                <div class="grid grid-cols-1 gap-6 mt-6 sm:grid-cols-2 lg:grid-cols-4">
                    <div class="p-6 bg-white rounded-lg shadow-md">
                        <div class="flex items-center">
                            <div class="p-3 bg-indigo-100 rounded-full">
                                <i class="fas fa-cogs text-indigo-600 text-xl"></i>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium text-gray-500">Total Raw Materials</p>
                                <p class="text-2xl font-bold text-gray-800">{{ $stats['raw_materials'] ?? '0' }}</p>
                            </div>
                        </div>
                    </div>
                    <div class="p-6 bg-white rounded-lg shadow-md">
                        <div class="flex items-center">
                            <div class="p-3 bg-green-100 rounded-full">
                                <i class="fas fa-tshirt text-green-600 text-xl"></i>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium text-gray-500">Total Products</p>
                                <p class="text-2xl font-bold text-gray-800">{{ $stats['products'] ?? '0' }}</p>
                            </div>
                        </div>
                    </div>
                    <div class="p-6 bg-white rounded-lg shadow-md">
                        <div class="flex items-center">
                            <div class="p-3 bg-yellow-100 rounded-full">
                                <i class="fas fa-truck text-yellow-600 text-xl"></i>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium text-gray-500">Total Suppliers</p>
                                <p class="text-2xl font-bold text-gray-800">{{ $stats['suppliers'] ?? '0' }}</p>
                            </div>
                        </div>
                    </div>
                    <div class="p-6 bg-white rounded-lg shadow-md">
                        <div class="flex items-center">
                            <div class="p-3 bg-red-100 rounded-full">
                                <i class="fas fa-store text-red-600 text-xl"></i>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium text-gray-500">Total Wholesalers</p>
                                <p class="text-2xl font-bold text-gray-800">{{ $stats['wholesalers'] ?? '0' }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Charts and Recent Orders -->
                <div class="grid grid-cols-1 gap-6 mt-8 lg:grid-cols-3">
                    <!-- Sales Trend Chart -->
                    <div class="p-6 bg-white rounded-lg shadow-md lg:col-span-2">
                        <h3 class="text-lg font-semibold text-gray-800">Sales Trends</h3>
                        <canvas id="salesChart"></canvas>
                    </div>
                    <!-- Recent Orders -->
                    <div class="p-6 bg-white rounded-lg shadow-md">
                        <h3 class="text-lg font-semibold text-gray-800">Recent Orders</h3>
                        <div class="mt-4 space-y-4">
                            @forelse ($recentOrders as $order)
                                <div class="flex items-center">
                                    <div class="flex-shrink-0">
                                        <div class="w-10 h-10 flex items-center justify-center rounded-full {{ $order['status_color'] }}">
                                            <i class="fas {{ $order['icon'] }} text-white"></i>
                                        </div>
                                    </div>
                                    <div class="ml-4 flex-1">
                                        <p class="text-sm font-medium text-gray-900">{{ $order['product_name'] }}</p>
                                        <p class="text-sm text-gray-500">Order #{{ $order['id'] }} - {{ $order['wholesaler_name'] }}</p>
                                    </div>
                                    <div class="text-right">
                                        <p class="text-sm font-semibold text-gray-900">${{ number_format($order['amount'], 2) }}</p>
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $order['status_color'] }} text-white">{{ $order['status'] }}</span>
                                    </div>
                                </div>
                            @empty
                                <p class="text-gray-500">No recent orders found.</p>
                            @endforelse
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>

    <script>
        // JavaScript for mobile menu toggle
        const menuToggle = document.getElementById('menu-toggle');
        const sidebar = document.getElementById('sidebar');
        menuToggle.addEventListener('click', () => {
            sidebar.classList.toggle('open');
        });

        // JavaScript for Chart.js
        const ctx = document.getElementById('salesChart').getContext('2d');
        const salesChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: ['January', 'February', 'March', 'April', 'May', 'June', 'July'],
                datasets: [{
                    label: 'Sales ($)',
                    data: [12000, 19000, 15000, 21000, 18000, 24000, 22000], // Placeholder data
                    borderColor: 'rgba(79, 70, 229, 1)',
                    backgroundColor: 'rgba(79, 70, 229, 0.1)',
                    tension: 0.4,
                    fill: true
                }]
            },
            options: {
                responsive: true,
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
    </script>
</body>
</html>