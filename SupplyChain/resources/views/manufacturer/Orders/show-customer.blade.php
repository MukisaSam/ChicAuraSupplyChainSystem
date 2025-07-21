<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Customer Order #{{ $order->order_number }} - ChicAura SCM</title>
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
        
        .nav-link {
            transition: all 0.3s ease;
            border-radius: 12px;
            margin: 4px 0;
        }
        .nav-link:hover {
            transform: translateX(5px);
        }
        
        .status-badge {
            padding: 0.25rem 0.75rem;
            border-radius: 9999px;
            font-size: 0.75rem;
            font-weight: 600;
            text-transform: uppercase;
        }
        
        .status-pending { background-color: #fef3c7; color: #92400e; }
        .status-processing { background-color: #dbeafe; color: #1e40af; }
        .status-shipped { background-color: #c7d2fe; color: #4338ca; }
        .status-delivered { background-color: #d1fae5; color: #065f46; }
        .status-cancelled { background-color: #fee2e2; color: #dc2626; }
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
                    <!-- Other navigation links same as index.blade.php -->
                    <!-- Include all the same menu items as your index.blade.php -->
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
                        <input type="text" id="manufacturerUniversalSearch" class="w-80 py-2 pl-10 pr-4 border border-gray-300 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-transparent text-sm" placeholder="Search orders, inventory, suppliers...">
                    </div>
                </div>
                <div class="flex items-center pr-4 space-x-3">
                    <x-notification-bell />
                    <button data-theme-toggle class="p-2 text-gray-500 hover:text-indigo-600 hover:bg-indigo-50 rounded-full transition-colors" title="Switch Theme">
                        <i class="fas fa-moon text-lg"></i>
                    </button>
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

            <!-- Main Content -->
            <main class="main-content-scrollable">
                <div class="mb-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <h1 class="text-2xl font-bold text-black dark:text-white">Customer Order #{{ $order->order_number }}</h1>
                            <p class="text-gray-600 dark:text-gray-400">Placed on {{ $order->created_at->format('M d, Y') }}</p>
                        </div>
                        <div>
                            <a href="{{ route('manufacturer.orders') }}" class="bg-gray-200 hover:bg-gray-300 text-gray-700 px-4 py-2 rounded-lg flex items-center space-x-2 transition-colors">
                                <i class="fas fa-arrow-left"></i>
                                <span>Back to Orders</span>
                            </a>
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">
                    <!-- Order Details -->
                    <div class="lg:col-span-2">
                        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md">
                            <div class="p-6 border-b border-gray-200 dark:border-gray-700">
                                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Order Details</h3>
                            </div>
                            <div class="p-6">
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <!-- Customer Info -->
                                    <div>
                                        <h4 class="font-medium text-gray-900 dark:text-white mb-3">Customer Information</h4>
                                        <p class="text-gray-800 dark:text-gray-300">{{ $order->customer->user->name ?? 'N/A' }}</p>
                                        <p class="text-gray-600 dark:text-gray-400">{{ is_array($order->customer) ? ($order->customer['email'] ?? 'N/A') : ($order->customer->email ?? 'N/A') }}</p>
                                        <p class="text-gray-600 dark:text-gray-400">{{ is_array($order->customer) ? ($order->customer['phone'] ?? 'N/A') : ($order->customer->phone ?? 'N/A') }}</p>
                                    </div>
                                    
                                    <!-- Shipping Info -->
                                    <div>
                                        <h4 class="font-medium text-gray-900 dark:text-white mb-3">Shipping Address</h4>
                                        <p class="text-gray-800 dark:text-gray-300">{{ is_array($order->shipping_address) ? ($order->shipping_address['address'] ?? 'N/A') : ($order->shipping_address->address ?? 'N/A') }}</p>
                                        <p class="text-gray-600 dark:text-gray-400">{{ is_array($order->shipping_address) ? ($order->shipping_address['city'] ?? 'N/A') : ($order->shipping_address->city ?? 'N/A') }}, {{ is_array($order->shipping_address) ? ($order->shipping_address['country'] ?? 'Uganda') : ($order->shipping_address->country ?? 'Uganda') }}</p>
                                    </div>
                                </div>
                                
                                <!-- Order Items -->
                                <div class="mt-8">
                                    <h4 class="font-medium text-gray-900 dark:text-white mb-3">Order Items</h4>
                                    <div class="overflow-x-auto">
                                        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                                            <thead class="bg-gray-50 dark:bg-gray-700">
                                                <tr>
                                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Item</th>
                                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Price</th>
                                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Quantity</th>
                                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Total</th>
                                                </tr>
                                            </thead>
                                            <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                                @foreach($order->customerOrderItems as $item)
                                                <tr>
                                                    <td class="px-6 py-4 whitespace-nowrap">
                                                        <div class="flex items-center">
                                                            <div class="flex-shrink-0 h-10 w-10">
                                                                @if($item->item && $item->item->image)
                                                                <img class="h-10 w-10 rounded-md object-cover" src="{{ asset('storage/items/' . $item->item->image) }}" alt="{{ $item->item->name }}">
                                                                @else
                                                                <div class="h-10 w-10 rounded-md bg-gray-200 dark:bg-gray-700 flex items-center justify-center">
                                                                    <i class="fas fa-box text-gray-400 dark:text-gray-500"></i>
                                                                </div>
                                                                @endif
                                                            </div>
                                                            <div class="ml-4">
                                                                <div class="text-sm font-medium text-gray-900 dark:text-white">{{ $item->item->name ?? 'Unknown Item' }}</div>
                                                                <div class="text-sm text-gray-500 dark:text-gray-400">SKU: {{ $item->item->sku ?? 'N/A' }}</div>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">
                                                        UGX {{ number_format($item->unit_price, 2) }}
                                                    </td>
                                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">
                                                        {{ $item->quantity }}
                                                    </td>
                                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">
                                                        UGX {{ number_format($item->quantity * $item->unit_price, 2) }}
                                                    </td>
                                                </tr>
                                                @endforeach
                                            </tbody>
                                            <tfoot class="bg-gray-50 dark:bg-gray-700">
                                                <tr>
                                                    <td colspan="3" class="px-6 py-4 text-right text-sm font-medium text-gray-900 dark:text-white">Subtotal:</td>
                                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-white">UGX {{ number_format($order->total_amount - ($order->shipping_cost ?? 0), 2) }}</td>
                                                </tr>
                                                <tr>
                                                    <td colspan="3" class="px-6 py-4 text-right text-sm font-medium text-gray-900 dark:text-white">Shipping:</td>
                                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-white">UGX {{ number_format($order->shipping_cost ?? 0, 2) }}</td>
                                                </tr>
                                                <tr>
                                                    <td colspan="3" class="px-6 py-4 text-right text-sm font-bold text-gray-900 dark:text-white">Total:</td>
                                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-gray-900 dark:text-white">UGX {{ number_format($order->total_amount, 2) }}</td>
                                                </tr>
                                            </tfoot>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Order Status and Actions -->
                    <div>
                        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md">
                            <div class="p-6 border-b border-gray-200 dark:border-gray-700">
                                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Order Status</h3>
                            </div>
                            <div class="p-6">
                                <div class="mb-6">
                                    <span class="inline-flex px-3 py-1 text-sm font-semibold rounded-full 
                                        @if($order->status === 'pending') bg-yellow-100 text-yellow-800
                                        @elseif($order->status === 'processing') bg-blue-100 text-blue-800
                                        @elseif($order->status === 'shipped') bg-indigo-100 text-indigo-800
                                        @elseif($order->status === 'delivered') bg-green-100 text-green-800
                                        @else bg-red-100 text-red-800
                                        @endif">
                                        {{ ucfirst($order->status) }}
                                    </span>
                                </div>
                                
                                <form action="{{ route('manufacturer.orders.update-customer-status', $order) }}" method="POST">
                                    @csrf
                                    @method('PUT')
                                    <div class="mb-4">
                                        <label for="status" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Update Status</label>
                                        <select name="status" id="status" class="block w-full py-2 px-3 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 text-sm dark:bg-gray-700 dark:text-white">
                                            <option value="pending" {{ $order->status === 'pending' ? 'selected' : '' }}>Pending</option>
                                            <option value="processing" {{ $order->status === 'processing' ? 'selected' : '' }}>Processing</option>
                                            <option value="shipped" {{ $order->status === 'shipped' ? 'selected' : '' }}>Shipped</option>
                                            <option value="delivered" {{ $order->status === 'delivered' ? 'selected' : '' }}>Delivered</option>
                                            <option value="cancelled" {{ $order->status === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                                        </select>
                                    </div>
                                    <div class="mb-4">
                                        <label for="notes" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Notes</label>
                                        <textarea name="notes" id="notes" rows="3" class="block w-full py-2 px-3 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 text-sm dark:bg-gray-700 dark:text-white">{{ $order->notes }}</textarea>
                                    </div>
                                    <button type="submit" class="w-full bg-indigo-600 hover:bg-indigo-700 text-white py-2 px-4 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                        Update Order
                                    </button>
                                </form>
                                
                                <div class="mt-6 pt-6 border-t border-gray-200 dark:border-gray-700">
                                    <h4 class="font-medium text-gray-900 dark:text-white mb-3">Order Timeline</h4>
                                    <div class="space-y-4">
                                        <div class="flex items-start">
                                            <div class="flex-shrink-0">
                                                <div class="flex items-center justify-center h-8 w-8 rounded-full bg-green-100">
                                                    <i class="fas fa-check text-green-600"></i>
                                                </div>
                                            </div>
                                            <div class="ml-3">
                                                <p class="text-sm font-medium text-gray-900 dark:text-white">Order Placed</p>
                                                <p class="text-sm text-gray-500 dark:text-gray-400">{{ $order->created_at->format('M d, Y H:i') }}</p>
                                            </div>
                                        </div>
                                        
                                        @if($order->status !== 'pending')
                                        <div class="flex items-start">
                                            <div class="flex-shrink-0">
                                                <div class="flex items-center justify-center h-8 w-8 rounded-full bg-blue-100">
                                                    <i class="fas fa-cog text-blue-600"></i>
                                                </div>
                                            </div>
                                            <div class="ml-3">
                                                <p class="text-sm font-medium text-gray-900 dark:text-white">Order Processing</p>
                                                <p class="text-sm text-gray-500 dark:text-gray-400">{{ $order->updated_at->format('M d, Y H:i') }}</p>
                                            </div>
                                        </div>
                                        @endif
                                        
                                        @if(in_array($order->status, ['shipped', 'delivered']))
                                        <div class="flex items-start">
                                            <div class="flex-shrink-0">
                                                <div class="flex items-center justify-center h-8 w-8 rounded-full bg-indigo-100">
                                                    <i class="fas fa-shipping-fast text-indigo-600"></i>
                                                </div>
                                            </div>
                                            <div class="ml-3">
                                                <p class="text-sm font-medium text-gray-900 dark:text-white">Order Shipped</p>
                                                <p class="text-sm text-gray-500 dark:text-gray-400">{{ $order->updated_at->format('M d, Y H:i') }}</p>
                                            </div>
                                        </div>
                                        @endif
                                        
                                        @if($order->status === 'delivered')
                                        <div class="flex items-start">
                                            <div class="flex-shrink-0">
                                                <div class="flex items-center justify-center h-8 w-8 rounded-full bg-green-100">
                                                    <i class="fas fa-box-open text-green-600"></i>
                                                </div>
                                            </div>
                                            <div class="ml-3">
                                                <p class="text-sm font-medium text-gray-900 dark:text-white">Order Delivered</p>
                                                <p class="text-sm text-gray-500 dark:text-gray-400">{{ $order->updated_at->format('M d, Y H:i') }}</p>
                                            </div>
                                        </div>
                                        @endif
                                        
                                        @if($order->status === 'cancelled')
                                        <div class="flex items-start">
                                            <div class="flex-shrink-0">
                                                <div class="flex items-center justify-center h-8 w-8 rounded-full bg-red-100">
                                                    <i class="fas fa-times text-red-600"></i>
                                                </div>
                                            </div>
                                            <div class="ml-3">
                                                <p class="text-sm font-medium text-gray-900 dark:text-white">Order Cancelled</p>
                                                <p class="text-sm text-gray-500 dark:text-gray-400">{{ $order->updated_at->format('M d, Y H:i') }}</p>
                                            </div>
                                        </div>
                                        @endif
                                    </div>
                                </div>
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
</body>
</html>