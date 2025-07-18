<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Details - ChicAura SCM</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <script src="{{ asset('js/theme-switcher.js') }}"></script>
    <style>
        body {
            background: #f4f6fa;
            min-height: 100vh;
            margin: 0;
            padding: 0;
        }
        .sidebar {
            position: fixed;
            top: 0;
            left: 0;
            height: 100vh;
            width: 16rem;
            z-index: 30;
            transition: transform 0.3s ease-in-out;
            background: linear-gradient(180deg, #1a237e 0%, #283593 100%);
            box-shadow: 4px 0 15px rgba(0,0,0,0.12);
            overflow-y: auto;
            overflow-x: hidden;
        }
        .main-content-wrapper {
            margin-left: 16rem;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }
        .header-gradient {
            position: fixed;
            top: 0;
            left: 16rem;
            right: 0;
            height: 4rem;
            z-index: 40;
            background: #fff;
            box-shadow: 0 2px 20px rgba(0,0,0,0.04);
            display: flex;
            align-items: center;
        }
        .main-content-scrollable {
            flex: 1 1 0%;
            overflow-y: auto;
            padding: 2rem 1.5rem;
            margin-top: 4rem;
            background: transparent;
        }
        .main-content-scrollable, .main-content-scrollable * {
            color: #1a237e;
        }
        .main-content-scrollable h1, .main-content-scrollable h2, .main-content-scrollable h3, .main-content-scrollable h4, .main-content-scrollable h5, .main-content-scrollable h6 {
            color: #111827;
        }
        .main-content-scrollable p, .main-content-scrollable span, .main-content-scrollable td, .main-content-scrollable th, .main-content-scrollable div, .main-content-scrollable li {
            color: #232e3c;
        }
        .dark .main-content-scrollable, .dark .main-content-scrollable * {
            color: #f1f5f9;
        }
        .dark .main-content-scrollable h1, .dark .main-content-scrollable h2, .dark .main-content-scrollable h3, .dark .main-content-scrollable h4, .dark .main-content-scrollable h5, .dark .main-content-scrollable h6 {
            color: #fff;
        }
        .dark .main-content-scrollable p, .dark .main-content-scrollable span, .dark .main-content-scrollable td, .dark .main-content-scrollable th, .dark .main-content-scrollable div, .dark .main-content-scrollable li {
            color: #f1f5f9;
        }
        .logo-container {
            background: #fff;
            border-bottom-right-radius: 2.5rem;
            border-top-left-radius: 0;
            border-top-right-radius: 0;
            border-bottom-left-radius: 0;
            width: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px 0 16px 0;
            margin: 0;
            box-shadow: none;
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
        .dark .text-white {
            color: #f1f5f9;
        }
        .dark .text-gray-200 {
            color: #cbd5e1;
        }
        @media (max-width: 1024px) {
            .main-content-wrapper {
                margin-left: 0;
            }
            .sidebar {
                position: absolute;
                left: 0;
                top: 0;
                width: 16rem;
                z-index: 30;
            }
            .header-gradient {
                left: 0;
            }
        }
        @media (max-width: 768px) {
            .sidebar { transform: translateX(-100%); }
            .sidebar.open { transform: translateX(0); }
            .main-content-wrapper { margin-left: 0; }
            .header-gradient { left: 0; }
        }
    </style>
</head>
<body class="font-sans antialiased">
    <div>
        <!-- Sidebar -->
        <aside id="sidebar" class="sidebar">
            <div class="flex flex-col h-full">
                <!-- Sidebar Header -->
                <div class="flex items-center justify-center h-16 border-b border-gray-600">
                    <div class="sidebar-logo-blend w-full h-16 flex items-center justify-center p-0 m-0" style="background:#fff;">
                        <img src="{{ asset('images/logo.png') }}" alt="ChicAura Logo" class="w-full h-auto object-contain max-w-[160px] max-h-[48px]">
                    </div>
                </div>
                <div class="px-4 py-4">
                        <h3 class="text-white text-sm font-semibold mb-3 px-3">MANUFACTURER PORTAL</h3>
                </div>
                <!-- Sidebar Navigation -->
                <nav class="flex-1 px-4 py-2 space-y-1">
                    <a href="{{route('manufacturer.dashboard')}}" class="nav-link flex items-center px-3 py-2 text-gray-300 hover:bg-gray-700 hover:text-white rounded-xl">
                        <i class="fas fa-home w-5"></i>
                        <span class="ml-2 font-medium text-sm">Home</span>
                    </a>
                    <a href="{{route('manufacturer.orders')}}" class="nav-link flex items-center px-3 py-2 text-white bg-gradient-to-r from-indigo-600 to-indigo-700 rounded-xl shadow-lg">
                        <i class="fas fa-box w-5"></i>
                        <span class="ml-2 text-sm">Orders</span>
                    </a>
                    <a href="{{route('manufacturer.analytics')}}" class="nav-link flex items-center px-3 py-2 text-gray-300 hover:bg-gray-700 hover:text-white rounded-xl">
                        <i class="fas fa-chart-pie w-5"></i>
                        <span class="ml-2 text-sm">Analytics</span>
                    </a>
                    <a href="{{ route('manufacturer.orders.analytics') }}" class="nav-link flex items-center px-3 py-2 text-gray-300 hover:bg-gray-700 hover:text-white rounded-xl">
                        <i class="fas fa-chart-line w-5"></i>
                        <span class="ml-2 text-sm">Order Analytics</span>
                    </a>
                    <a href="{{route('manufacturer.inventory')}}" class="nav-link flex items-center px-3 py-2 text-gray-300 hover:bg-gray-700 hover:text-white rounded-xl">
                        <i class="fas fa-warehouse w-5"></i>
                        <span class="ml-2 text-sm">Inventory</span>
                    </a>
                    <a href="{{route('manufacturer.partners.manage')}}" class="nav-link flex items-center px-3 py-2 text-gray-300 hover:bg-gray-700 hover:text-white rounded-xl">
                        <i class="fas fa-users w-5"></i>
                        <span class="ml-2 text-sm">Wholesalers</span>
                    </a>
                    <a href="{{route('manufacturer.partners.manage')}}" class="nav-link flex items-center px-3 py-2 text-gray-300 hover:bg-gray-700 hover:text-white rounded-xl">
                        <i class="fas fa-truck-fast w-5"></i>
                        <span class="ml-2 text-sm">Suppliers</span>
                    </a>
                    <a href="{{route('manufacturer.chat')}}" class="nav-link flex items-center px-3 py-2 text-gray-300 hover:bg-gray-700 hover:text-white rounded-xl">
                        <i class="fas fa-comments w-5"></i>
                        <span class="ml-2 text-sm">Chat</span>
                    </a>
                    <a href="{{route('manufacturer.reports')}}" class="nav-link flex items-center px-3 py-2 text-gray-300 hover:bg-gray-700 hover:text-white rounded-xl">
                        <i class="fas fa-file-alt w-5"></i>
                        <span class="ml-2 text-sm">Reports</span>
                    </a>
                     <a href="{{route('manufacturer.revenue')}}" class="nav-link flex items-center px-3 py-2 text-gray-300 hover:bg-gray-700 hover:text-white rounded-xl">
                        <i class="fas fa-coins text-green-600 dark:text-green-400 text-xl"></i>
                        <span class="ml-2 text-sm">Revenue</span>
                    </a>
                    <a href="{{ route('manufacturer.workforce.index') }}" class="nav-link flex items-center px-3 py-2 text-gray-300 hover:bg-gray-700 hover:text-white rounded-xl">
                        <i class="fas fa-user-tie w-5"></i>
                        <span class="ml-2 text-sm">Workforce</span>
                    </a>
                    <a href="{{ route('manufacturer.warehouse.index') }}" class="nav-link flex items-center px-3 py-2 text-gray-300 hover:bg-gray-700 hover:text-white rounded-xl">
                        <i class="fas fa-warehouse w-5"></i>
                        <span class="ml-2 text-sm">Warehouses</span>
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

        <div class="main-content-wrapper">
            <!-- Top Navigation Bar -->
            <header class="header-gradient flex items-center justify-between border-b">
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
                        <input type="text" class="w-80 py-2 pl-10 pr-4 border border-gray-300 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-transparent text-sm" placeholder="Search orders, inventory, suppliers...">
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
                            <img class="w-7 h-7 rounded-full border-2 border-purple-200 object-cover" 
                                 src="{{ Auth::user()->profile_picture ? asset('storage/profile-pictures/' . basename(Auth::user()->profile_picture)) : asset('images/default-avatar.svg') }}" 
                                 alt="User Avatar">
                        </button>
                    </div>
                    <div class="flex items-center space-x-2">
                        <a href="{{ route('user.profile.edit') }}" class="p-2 text-gray-500 hover:text-indigo-600 hover:bg-indigo-50 rounded-full transition-colors" title="Edit Profile">
                            <i class="fas fa-user-edit text-lg"></i>
                        </a>
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
            <main class="main-content-scrollable">
                <!-- Header Section -->
                <div class="mb-8">
                    <div class="flex justify-between items-center">
                        <div>
                            <h1 class="text-3xl font-bold text-black mb-2">Order Details</h1>
                            <p class="text-gray-200 text-sm">Order #{{ $order->order_number }}</p>
                        </div>
                        <div class="flex space-x-3">
                            <a href="{{ route('manufacturer.orders') }}" 
                               class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg flex items-center space-x-2 transition-colors">
                                <i class="fas fa-arrow-left"></i>
                                <span>Back to Orders</span>
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Order Status -->
                <div class="mb-8">
                    <div class="card-gradient p-6 rounded-xl">
                        <div class="flex justify-between items-center">
                            <div>
                                <h2 class="text-xl font-semibold text-black mb-2">Order Status</h2>
                                <span class="inline-flex px-3 py-1 text-sm font-semibold rounded-full 
                                    @if($order->status === 'pending') bg-yellow-100 text-yellow-800
                                    @elseif($order->status === 'confirmed') bg-blue-100 text-blue-800
                                    @elseif($order->status === 'in_production') bg-purple-100 text-purple-800
                                    @elseif($order->status === 'shipped') bg-indigo-100 text-indigo-800
                                    @elseif($order->status === 'delivered') bg-green-100 text-green-800
                                    @else bg-red-100 text-red-800
                                    @endif">
                                    {{ ucfirst(str_replace('_', ' ', $order->status)) }}
                                </span>
                            </div>
                            <div class="text-right">
                                <p class="text-sm text-black">Order Date</p>
                                <p class="text-lg font-semibold text-black">{{ $order->created_at->format('M d, Y H:i') }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                    <!-- Order Information -->
                    <div class="lg:col-span-2">
                        <!-- Wholesaler Information -->
                        <div class="card-gradient p-6 rounded-xl mb-6">
                            <h3 class="text-lg font-semibold text-black mb-4">Wholesaler Information</h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <p class="text-sm font-medium text-black">Name</p>
                                    <p class="text-lg text-black">{{ $order->wholesaler->user->name ?? 'N/A' }}</p>
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-black">Business Type</p>
                                    <p class="text-lg text-black">{{ $order->wholesaler->business_type ?? 'N/A' }}</p>
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-black">Email</p>
                                    <p class="text-lg text-black">{{ $order->wholesaler->user->email ?? 'N/A' }}</p>
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-black">Phone</p>
                                    <p class="text-lg text-black">{{ $order->wholesaler->phone ?? 'N/A' }}</p>
                                </div>
                                <div class="md:col-span-2">
                                    <p class="text-sm font-medium text-black">Address</p>
                                    <p class="text-lg text-black">{{ $order->wholesaler->business_address ?? 'N/A' }}</p>
                                </div>
                            </div>
                        </div>

                        <!-- Order Items -->
                        <div class="card-gradient p-6 rounded-xl">
                            <h3 class="text-lg font-semibold text-black mb-4">Order Items</h3>
                            <div class="overflow-x-auto">
                                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                                    <thead class="bg-gray-50 dark:bg-gray-700">
                                        <tr>
                                            <th class="px-6 py-3 text-left text-xs font-medium gray-500 dark:text-gray-300 uppercase tracking-wider">Item</th>
                                            <th class="px-6 py-3 text-left text-xs font-medigray-500 dark:text-gray-300 uppercase tracking-wider">Description</th>
                                            <th class="px-6 py-3 text-left text-xs font-medium gray-500 dark:text-gray-300 uppercase tracking-wider">Quantity</th>
                                            <th class="px-6 py-3 text-left text-xs font-medium gray-500 dark:text-gray-300 uppercase tracking-wider">Unit Price</th>
                                            <th class="px-6 py-3 text-left text-xs font-medium gray-500 dark:text-gray-300 uppercase tracking-wider">Total</th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                        @foreach($order->orderItems as $item)
                                        <tr>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="text-sm font-medium gray-500 dark:text-gray-300">{{ $item->item->name ?? 'N/A' }}</div>
                                            </td>
                                            <td class="px-6 py-4">
                                                <div class="text-sm gray-500 dark:text-gray-300">{{ $item->item->description ?? 'N/A' }}</div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="text-sm gray-500 dark:text-gray-300">{{ $item->quantity }}</div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="text-sm gray-500 dark:text-gray-300">UGX {{ number_format($item->unit_price, 2) }}</div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="text-sm font-medium gray-500 dark:text-gray-300">UGX {{ number_format($item->total_price, 2) }}</div>
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    <!-- Order Summary & Actions -->
                    <div class="lg:col-span-1">
                        <!-- Order Summary -->
                        <div class="card-gradient p-6 rounded-xl mb-6">
                            <h3 class="text-lg font-semibold text-black mb-4">Order Summary</h3>
                            <div class="space-y-3">
                                <div class="flex justify-between">
                                    <span class="text-black">Subtotal:</span>
                                    <span class="text-black">UGX {{ number_format((float) $order->total_amount, 2) }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-black">Shipping:</span>
                                    <span class="text-black">UGX 0.00</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-black">Tax:</span>
                                    <span class="text-black">UGX 0.00</span>
                                </div>
                                <hr class="border-gray-200 dark:border-gray-700">
                                <div class="flex justify-between">
                                    <span class="text-lg font-semibold text-black">Total:</span>
                                    <span class="text-lg font-semibold text-black">UGX {{ number_format((float) $order->total_amount, 2) }}</span>
                                </div>
                            </div>
                        </div>

                        <!-- Update Status -->
                        <div class="card-gradient p-6 rounded-xl mb-6">
                            <h3 class="text-lg font-semibold text-black mb-4">Update Status</h3>
                            <form action="{{ route('manufacturer.orders.update-status', $order) }}" method="POST">
                                @csrf
                                @method('PUT')
                                <div class="mb-4">
                                    <label for="status" class="block text-sm font-medium text-black mb-2">Status</label>
                                    <select name="status" id="status" 
                                            class="w-full border border-gray-300 dark:border-gray-600 rounded-lg px-3 py-2 bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                                        <option value="pending" {{ $order->status === 'pending' ? 'selected' : '' }}>Pending</option>
                                        <option value="confirmed" {{ $order->status === 'confirmed' ? 'selected' : '' }}>Confirmed</option>
                                        <option value="in_production" {{ $order->status === 'in_production' ? 'selected' : '' }}>In Production</option>
                                        <option value="shipped" {{ $order->status === 'shipped' ? 'selected' : '' }}>Shipped</option>
                                        <option value="delivered" {{ $order->status === 'delivered' ? 'selected' : '' }}>Delivered</option>
                                        <option value="cancelled" {{ $order->status === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                                    </select>
                                </div>
                                <div class="mb-4">
                                    <label for="notes" class="block text-sm font-medium text-black mb-2">Notes</label>
                                    <textarea name="notes" id="notes" rows="3" 
                                              class="w-full border border-gray-300 dark:border-gray-600 rounded-lg px-3 py-2 bg-white dark:bg-gray-700 text-white focus:ring-2 focus:ring-indigo-500 focus:border-transparent"
                                              placeholder="Add any notes about this order...">{{ $order->notes }}</textarea>
                                </div>
                                <button type="submit" class="w-full bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-lg transition-colors">
                                    Update Status
                                </button>
                            </form>
                        </div>

                        <!-- Order Details -->
                        <div class="card-gradient p-6 rounded-xl">
                            <h3 class="text-lg font-semibold text-black mb-4">Order Details</h3>
                            <div class="space-y-3">
                                <div>
                                    <p class="text-sm font-medium text-black">Payment Method</p>
                                    <p class="text-black">{{ $order->payment_method ?? 'N/A' }}</p>
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-black">Shipping Address</p>
                                    <p class="text-black">{{ $order->shipping_address ?? 'N/A' }}</p>
                                </div>
                                @if($order->estimated_delivery)
                                <div>
                                    <p class="text-sm font-medium text-black">Estimated Delivery</p>
                                    <p class="text-black">{{ $order->estimated_delivery->format('M d, Y') }}</p>
                                </div>
                                @endif
                                @if($order->actual_delivery)
                                <div>
                                    <p class="text-sm font-medium text-black">Actual Delivery</p>
                                    <p class="text-black">{{ $order->actual_delivery->format('M d, Y') }}</p>
                                </div>
                                @endif
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

    @if(session('success'))
    <div id="success-message" class="fixed top-4 right-4 bg-green-500 text-white px-6 py-3 rounded-lg shadow-lg z-50">
        {{ session('success') }}
        <button onclick="document.getElementById('success-message').style.display='none'" class="ml-4 text-white hover:text-gray-200">
            <i class="fas fa-times"></i>
        </button>
    </div>
    <script>
        setTimeout(function() {
            document.getElementById('success-message').style.display = 'none';
        }, 5000);
    </script>
    @endif
</body>
</html> 