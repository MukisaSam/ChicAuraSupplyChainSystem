{{-- resources/views/wholesaler/orders/show.blade.php --}}
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
            background: linear-gradient(135deg, rgba(0,0,0,0.7) 0%, rgba(0,0,0,0.5) 100%), url('{{ asset('images/wholesaler.jpg') }}');
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
            min-height: 100vh;
        }
        
        .sidebar { 
            transition: transform 0.3s ease-in-out;
            background: linear-gradient(180deg, #1e293b 0%, #334155 100%);
            box-shadow: 4px 0 15px rgba(0,0,0,0.1);
        }
        
        .card-gradient {
            background: linear-gradient(135deg, #ffffff 0%, #f8fafc 100%);
            border: 1px solid rgba(255,255,255,0.2);
            box-shadow: 0 8px 32px rgba(0,0,0,0.1);
            backdrop-filter: blur(10px);
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
                    <a href="{{ route('wholesaler.dashboard') }}" class="nav-link flex items-center px-3 py-2 text-gray-300 hover:bg-gray-700 hover:text-white rounded-xl"><i class="fas fa-home w-5"></i><span class="ml-2 text-sm">Home</span></a>
                    <a href="{{ route('wholesaler.orders.index') }}" class="nav-link flex items-center px-3 py-2 text-white bg-gradient-to-r from-purple-600 to-purple-700 rounded-xl shadow-lg"><i class="fas fa-shopping-cart w-5"></i><span class="ml-2 font-medium text-sm">Orders</span></a>
                    <a href="#" class="nav-link flex items-center px-3 py-2 text-gray-300 hover:bg-gray-700 hover:text-white rounded-xl"><i class="fas fa-chart-line w-5"></i><span class="ml-2 text-sm">Analytics</span></a>
                    <a href="#" class="nav-link flex items-center px-3 py-2 text-gray-300 hover:bg-gray-700 hover:text-white rounded-xl"><i class="fas fa-comments w-5"></i><span class="ml-2 text-sm">Chat</span></a>
                    <a href="#" class="nav-link flex items-center px-3 py-2 text-gray-300 hover:bg-gray-700 hover:text-white rounded-xl"><i class="fas fa-file-invoice-dollar w-5"></i><span class="ml-2 text-sm">Reports</span></a>
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
                        <input type="text" class="w-80 py-2 pl-10 pr-4 border border-gray-300 rounded-xl focus:ring-2 focus:ring-purple-500 focus:border-transparent text-sm" placeholder="Search orders...">
                    </div>
                </div>
                <div class="flex items-center pr-4 space-x-3">
                    <button class="p-2 text-gray-500 hover:text-purple-600 hover:bg-purple-50 rounded-full transition-colors"><i class="fas fa-bell text-lg"></i></button>
                    <button data-theme-toggle class="p-2 text-gray-500 hover:text-purple-600 hover:bg-purple-50 rounded-full transition-colors" title="Switch Theme">
                        <i class="fas fa-moon text-lg"></i>
                    </button>
                    <div class="relative">
                        <button class="flex items-center focus:outline-none bg-white rounded-full p-2 shadow-md hover:shadow-lg transition-shadow">
                            <span class="mr-2 text-gray-700 font-medium text-sm">{{ $user->name ?? 'Wholesaler User' }}</span>
                            <img class="w-7 h-7 rounded-full border-2 border-purple-200 object-cover" 
                                 src="{{ $user->profile_picture ? Storage::disk('public')->url($user->profile_picture) : asset('images/default-avatar.svg') }}" 
                                 alt="User Avatar">
                        </button>
                    </div>
                    <div class="flex items-center space-x-2">
                        <a href="{{ route('user.profile.edit') }}" class="p-2 text-gray-500 hover:text-purple-600 hover:bg-purple-50 rounded-full transition-colors" title="Edit Profile">
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
            <main class="flex-1 p-4 overflow-auto">
                <div class="container mx-auto px-4 py-8">
                    <div class="mb-6">
                        <div class="flex items-center justify-between">
                            <div>
                                <h2 class="text-2xl font-bold text-gray-900 dark:text-gray-100">Order Details</h2>
                                <p class="text-gray-600 dark:text-gray-400">Order #{{ $order->order_number }}</p>
                            </div>
                            <div class="flex space-x-3">
                                <a href="{{ route('wholesaler.orders.index') }}" class="bg-gray-600 text-white px-4 py-2 rounded-lg hover:bg-gray-700 dark:bg-gray-700 dark:hover:bg-gray-600">
                                    Back to Orders
                                </a>
                                @if(in_array($order->status, ['pending', 'confirmed']))
                                    <form method="POST" action="{{ route('wholesaler.orders.cancel', $order) }}" class="inline">
                                        @csrf
                                        <button type="submit" class="bg-red-600 text-white px-4 py-2 rounded-lg hover:bg-red-700 dark:bg-red-700 dark:hover:bg-red-600">
                                            Cancel Order
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                        <div class="lg:col-span-2">
                            <div class="bg-white dark:bg-slate-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6 mb-6">
                                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Order Items</h3>
                                @foreach($order->orderItems as $orderItem)
                                    <div class="border-b border-gray-200 dark:border-gray-700 py-4 last:border-b-0">
                                        <div class="flex justify-between items-center">
                                            <div>
                                                <h4 class="font-medium text-gray-900 dark:text-white">{{ $orderItem->item->name }}</h4>
                                                <p class="text-sm text-gray-600 dark:text-gray-400">{{ $orderItem->item->description }}</p>
                                                <div class="mt-2 flex items-center space-x-4">
                                                    <span class="text-sm text-gray-500 dark:text-gray-400">Category: {{ $orderItem->item->category }}</span>
                                                    <span class="text-sm text-gray-500 dark:text-gray-400">Material: {{ $orderItem->item->material }}</span>
                                                </div>
                                            </div>
                                            <div class="text-right">
                                                <p class="font-medium text-gray-900 dark:text-white">Qty: {{ $orderItem->quantity }}</p>
                                                <p class="text-sm text-gray-600 dark:text-gray-400">${{ number_format($orderItem->unit_price, 2) }} each</p>
                                                <p class="font-bold text-gray-900 dark:text-white mt-1">${{ number_format($orderItem->total_price, 2) }}</p>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>

                            <!-- Order Timeline -->
                            <div class="bg-white dark:bg-slate-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Order Timeline</h3>
                                <div class="space-y-6">
                                    @foreach($timeline as $event)
                                    <div class="flex items-start">
                                        <div class="flex-shrink-0">
                                            <div class="w-8 h-8 rounded-full flex items-center justify-center 
                                                @if($event['type'] === 'success') bg-green-100 dark:bg-green-900
                                                @elseif($event['type'] === 'warning') bg-yellow-100 dark:bg-yellow-900
                                                @elseif($event['type'] === 'error') bg-red-100 dark:bg-red-900
                                                @else bg-blue-100 dark:bg-blue-900 @endif">
                                                <i class="fas {{ $event['icon'] }} 
                                                    @if($event['type'] === 'success') text-green-600 dark:text-green-400
                                                    @elseif($event['type'] === 'warning') text-yellow-600 dark:text-yellow-400
                                                    @elseif($event['type'] === 'error') text-red-600 dark:text-red-400
                                                    @else text-blue-600 dark:text-blue-400 @endif"></i>
                                            </div>
                                        </div>
                                        <div class="ml-4">
                                            <p class="text-sm font-medium text-gray-900 dark:text-white">{{ $event['title'] }}</p>
                                            <p class="text-sm text-gray-600 dark:text-gray-400">{{ $event['description'] }}</p>
                                            <p class="text-xs text-gray-500 dark:text-gray-500 mt-1">{{ \Carbon\Carbon::parse($event['timestamp'])->format('M d, Y H:i') }}</p>
                                        </div>
                                    </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>

                        <div class="lg:col-span-1">
                            <div class="bg-white dark:bg-slate-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Order Information</h3>
                                <div class="space-y-4">
                                    <div>
                                        <span class="text-sm font-medium text-gray-500 dark:text-gray-400">Status:</span>
                                        <span class="ml-2 px-2 py-1 text-xs font-semibold rounded-full 
                                            @if($order->status === 'completed') bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200
                                            @elseif($order->status === 'pending') bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200
                                            @elseif($order->status === 'cancelled') bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200
                                            @else bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-200 @endif">
                                            {{ ucfirst($order->status) }}
                                        </span>
                                    </div>
                                    <div>
                                        <span class="text-sm font-medium text-gray-500 dark:text-gray-400">Total Amount:</span>
                                        <span class="ml-2 font-bold text-gray-900 dark:text-white">${{ number_format($order->total_amount, 2) }}</span>
                                    </div>
                                    <div>
                                        <span class="text-sm font-medium text-gray-500 dark:text-gray-400">Payment Method:</span>
                                        <span class="ml-2 text-gray-900 dark:text-white">{{ ucfirst($order->payment_method) }}</span>
                                    </div>
                                    <div>
                                        <span class="text-sm font-medium text-gray-500 dark:text-gray-400">Order Date:</span>
                                        <span class="ml-2 text-gray-900 dark:text-white">{{ $order->created_at->format('M d, Y') }}</span>
                                    </div>
                                    <div>
                                        <span class="text-sm font-medium text-gray-500 dark:text-gray-400">Estimated Delivery:</span>
                                        <span class="ml-2 text-gray-900 dark:text-white">{{ $order->estimated_delivery ? $order->estimated_delivery->format('M d, Y') : 'TBD' }}</span>
                                    </div>
                                    <div>
                                        <span class="text-sm font-medium text-gray-500 dark:text-gray-400">Delivery Address:</span>
                                        <span class="ml-2 text-gray-900 dark:text-white">{{ $order->delivery_address }}</span>
                                    </div>
                                    <div>
                                        <span class="text-sm font-medium text-gray-500 dark:text-gray-400">Manufacturer:</span>
                                        <span class="ml-2 text-gray-900 dark:text-white">{{ $order->manufacturer && $order->manufacturer->user ? $order->manufacturer->user->name : 'N/A' }}</span>
                                    </div>
                                    @if($order->notes)
                                    <div>
                                        <span class="text-sm font-medium text-gray-500 dark:text-gray-400">Notes:</span>
                                        <span class="ml-2 text-gray-900 dark:text-white">{{ $order->notes }}</span>
                                    </div>
                                    @endif
                                </div>

                                <div class="mt-6 space-y-3">
                                    <a href="{{ route('wholesaler.orders.index') }}" 
                                       class="block w-full text-center px-4 py-2 bg-gray-100 hover:bg-gray-200 dark:bg-gray-700 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-200 font-medium rounded-lg transition-colors">
                                        Back to Orders
                                    </a>
                                    @if(in_array($order->status, ['pending', 'processing']))
                                    <button type="button" 
                                            class="block w-full px-4 py-2 bg-red-100 hover:bg-red-200 dark:bg-red-900/20 dark:hover:bg-red-900/30 text-red-700 dark:text-red-400 font-medium rounded-lg transition-colors"
                                            onclick="confirmCancelOrder()">
                                        Cancel Order
                                    </button>
                                    @endif
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