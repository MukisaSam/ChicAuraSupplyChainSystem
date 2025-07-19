<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice Details - ChicAura SCM</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <script src="{{ asset('js/theme-switcher.js') }}"></script>
    <style>
        body {
            background: #f5f7fa;
            min-height: 100vh;
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
        .dark .sidebar {
            background: linear-gradient(180deg, #0f172a 0%, #1e293b 100%);
        }
        .header-gradient {
            background: #fff;
            box-shadow: 0 2px 20px rgba(0,0,0,0.06);
        }
        .dark .header-gradient {
            background: linear-gradient(135deg, #1e293b 0%, #334155 100%);
            border-color: #475569;
        }
        .nav-link {
            transition: all 0.3s ease;
            border-radius: 12px;
            margin: 4px 0;
        }
        .nav-link:hover {
            transform: translateX(5px);
        }
        @media (max-width: 768px) {
            .sidebar { transform: translateX(-100%); }
            .sidebar.open { transform: translateX(0); }
        }
    </style>
</head>
<body class="font-sans antialiased">
    <div id="app">
    <div class="flex h-full">
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
                    <a href="{{ route('wholesaler.reports.index') }}" class="nav-link flex items-center px-3 py-2 text-gray-300 hover:bg-gray-700 hover:text-white rounded-xl"><i class="fas fa-file-invoice-dollar w-5"></i><span class="ml-2 text-sm">Reports</span></a>
                    <a href="{{ route('wholesaler.invoices.index') }}" class="nav-link flex items-center px-3 py-2 text-white bg-gradient-to-r from-purple-600 to-purple-700 rounded-xl shadow-lg"><i class="fas fa-file-invoice w-5"></i><span class="ml-2 font-medium text-sm"">Invoices</span></a>
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
                        <input type="text" class="w-80 py-2 pl-10 pr-4 border border-gray-300 rounded-xl focus:ring-2 focus:ring-purple-500 focus:border-transparent text-sm" placeholder="Search products, orders...">
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
            <main class="flex-1 p-4">
                <!-- INVOICE CONTENT STARTS HERE -->
                <div class="flex justify-center py-8 bg-gray-100 min-h-screen">
                    <div class="w-full max-w-3xl">
                        <div class="flex justify-between items-center mb-6 print:hidden">
                            <a href="{{ route('wholesaler.invoices.index') }}" class="bg-gray-200 hover:bg-gray-300 text-gray-700 px-4 py-2 rounded"><i class="fas fa-arrow-left mr-2"></i> Back to Invoices</a>
                            <button onclick="window.print()" class="bg-purple-600 hover:bg-purple-700 text-white px-4 py-2 rounded">
                                <i class="fas fa-print mr-2"></i> Print
                            </button>
                        </div>
                            <div class="bg-white rounded-xl shadow-lg p-8 relative invoice-print-area">
                            <div class="flex flex-col items-center mb-8">
                                <img src="{{ asset('images/logo.png') }}" alt="ChicAura Logo" class="h-14 mb-2">
                                <h2 class="text-2xl font-bold text-gray-800">INVOICE</h2>
                                <p class="text-gray-700 dark:text-gray-300">ChicAura Supply Chain Management</p>
                            </div>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                                <div>
                                    <h4 class="font-semibold text-gray-700 mb-1">Billed To:</h4>
                                    <p class="text-gray-900 dark:text-white">{{ $invoice->order->wholesaler->user->name ?? '-' }}</p>
                                    <p class="text-gray-700 dark:text-gray-300 text-sm">{{ $invoice->order->delivery_address ?? '-' }}</p>
                                </div>
                                <div class="md:text-right">
                                    <h4 class="font-semibold text-gray-700 mb-1">Invoice Info:</h4>
                                    <p><span class="text-gray-700 dark:text-gray-300">Invoice #:</span> <span class="font-medium text-gray-900 dark:text-white">{{ $invoice->invoice_number }}</span></p>
                                    <p><span class="text-gray-700 dark:text-gray-300">Order #:</span> <span class="font-medium text-gray-900 dark:text-white">{{ $invoice->order->order_number ?? '-' }}</span></p>
                                    <p><span class="text-gray-700 dark:text-gray-300">Status:</span> <span class="font-medium text-gray-900 dark:text-white">{{ ucfirst($invoice->status) }}</span></p>
                                    <p><span class="text-gray-700 dark:text-gray-300">Due Date:</span> <span class="font-medium text-gray-900 dark:text-white">{{ $invoice->due_date->format('M d, Y') }}</span></p>
                                </div>
                            </div>
                            <div class="mb-8">
                                <h4 class="font-semibold text-gray-700 mb-3">Order Items</h4>
                                <div class="overflow-x-auto">
                                    <table class="min-w-full text-sm border rounded-lg">
                                        <thead class="bg-gray-50">
                                            <tr>
                                                <th class="px-4 py-2 text-left font-semibold text-gray-900 dark:text-white">Product</th>
                                                <th class="px-4 py-2 text-left font-semibold text-gray-900 dark:text-white">Description</th>
                                                <th class="px-4 py-2 text-center font-semibold text-gray-900 dark:text-white">Qty</th>
                                                <th class="px-4 py-2 text-right font-semibold text-gray-900 dark:text-white">Unit Price</th>
                                                <th class="px-4 py-2 text-right font-semibold text-gray-900 dark:text-white">Total</th>
                                            </tr>
                                        </thead>
                                        <tbody class="bg-white">
                                            @foreach($invoice->order->orderItems as $item)
                                            <tr class="border-b last:border-b-0">
                                                <td class="px-4 py-2 text-gray-900 dark:text-white">{{ $item->item->name }}</td>
                                                <td class="px-4 py-2 text-gray-700 dark:text-gray-300">{{ $item->item->description }}</td>
                                                <td class="px-4 py-2 text-center text-gray-900 dark:text-white">{{ $item->quantity }}</td>
                                                <td class="px-4 py-2 text-right text-gray-900 dark:text-white">UGX {{ number_format($item->unit_price, 2) }}</td>
                                                <td class="px-4 py-2 text-right font-semibold text-gray-900 dark:text-white">UGX {{ number_format($item->total_price, 2) }}</td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="flex flex-col md:flex-row justify-between items-end mb-8">
                                <div class="mb-4 md:mb-0">
                                    <h4 class="font-semibold text-gray-700 mb-1">Payment Method</h4>
                                    <p class="text-gray-900 dark:text-white">{{ ucfirst($invoice->order->payment_method) }}</p>
                                </div>
                                <div class="w-full md:w-1/3">
                                    <div class="flex justify-between text-gray-600 mb-2">
                                        <span class="text-gray-700 dark:text-gray-300">Subtotal:</span>
                                        <span class="text-gray-900 dark:text-white">UGX {{ number_format($invoice->amount, 2) }}</span>
                                    </div>
                                    <div class="flex justify-between text-gray-600 mb-2">
                                        <span class="text-gray-700 dark:text-gray-300">Tax (0%):</span>
                                        <span class="text-gray-900 dark:text-white">UGX 0.00</span>
                                    </div>
                                    <div class="flex justify-between text-lg font-bold border-t pt-2">
                                        <span class="text-gray-900 dark:text-white">Total:</span>
                                        <span class="text-gray-900 dark:text-white">UGX {{ number_format($invoice->amount, 2) }}</span>
                                    </div>
                                </div>
                            </div>
                            <div class="text-center text-gray-400 text-xs mt-8">
                                <p>Thank you for your business!</p>
                                <p>ChicAura Supply Chain Management &copy; {{ date('Y') }}</p>
                            </div>
                        </div>
                    </div>
                </div>
                <style>
                    @media print {
                            body, html {
                                background: white !important;
                            }
                            .sidebar, .header-gradient, .print\:hidden {
                                display: none !important;
                            }
                            .invoice-print-area {
                                box-shadow: none !important;
                                background: white !important;
                                margin: 0 !important;
                                padding: 0 !important;
                                display: block !important;
                            }
                    }
                </style>
                <!-- INVOICE CONTENT ENDS HERE -->
            </main>
            </div>
        </div>
    </div>
</body>
</html>