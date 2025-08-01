{{-- resources/views/wholesaler/orders/create.blade.php --}}
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Place New Order - ChicAura SCM</title>
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
            background: rgba(255, 255, 255, 0.95);
            border-radius: 12px;
            padding: 8px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        }
        
        .dark .logo-container {
            background: rgba(255, 255, 255, 0.9);
        }
        
        .card-gradient, .bg-white.card, .order-card, .summary-card {
            background: #fff;
            border: 1.5px solid #e0e7ef;
            box-shadow: 0 8px 32px rgba(80, 80, 160, 0.12), 0 1.5px 8px rgba(80,80,160,0.08);
        }
        .dark .card-gradient, .dark .bg-white.card, .dark .order-card, .dark .summary-card {
            background: #232e3c;
            border: 1.5px solid #3b4860;
            box-shadow: 0 8px 32px rgba(40, 60, 120, 0.18), 0 1.5px 8px rgba(40,60,120,0.12);
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
                    <a href="{{ route('wholesaler.orders.index') }}" class="nav-link flex items-center px-3 py-2 text-white bg-gradient-to-r from-purple-600 to-purple-700 rounded-xl shadow-lg"><i class="fas fa-shopping-cart w-5"></i><span class="ml-2 font-medium text-sm">Orders</span></a>
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
                <div class="mb-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-1">Place New Order</h2>
                            <p class="text-gray-700 dark:text-gray-300 text-sm">Select clothing items and place your wholesale order</p>
                        </div>
                        <a href="{{ route('wholesaler.orders.index') }}" class="bg-purple-600 text-white px-6 py-2 rounded-xl shadow hover:bg-purple-700 dark:bg-purple-700 dark:hover:bg-purple-800 transition-all duration-300 flex items-center font-semibold">
                            <i class="fas fa-arrow-left mr-2"></i>
                            Back to Orders
                        </a>
                    </div>
                </div>

                @if($errors->any())
                    <div class="mb-4 bg-red-100 dark:bg-red-900/20 border border-red-400 dark:border-red-600 text-red-700 dark:text-red-400 px-4 py-3 rounded">
                        <ul class="list-disc list-inside">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form method="POST" action="{{ route('wholesaler.orders.store') }}" id="orderForm">
                    @csrf
                    
                    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                        <!-- Product Selection -->
                        <div class="lg:col-span-2">
                            <div class="card-gradient rounded-xl p-6">
                                <h3 class="text-lg font-semibold text-black mb-4">Select Products</h3>
                                
                                @if($finishedProducts->count() > 0)
                                    <div class="space-y-4" id="itemsContainer">
                                        @foreach($finishedProducts as $item)
                                            <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 p-4 hover:shadow-md transition-shadow">
                                                <div class="flex items-center space-x-4">
                                                    <div class="flex-shrink-0">
                                                        <img src="{{ $item->image_url ? asset($item->image_url) : asset('images/default-product.jpg') }}" 
                                                             alt="{{ $item->name }}" 
                                                             class="w-16 h-16 object-cover rounded-lg">
                                                    </div>
                                                    <div class="flex-1">
                                                        <h4 class="text-lg font-semibold text-gray-900 dark:text-gray-100">{{ $item->name }}</h4>
                                                        <p class="text-sm text-gray-600 dark:text-gray-400">{{ $item->description }}</p>
                                                        <div class="flex items-center space-x-4 mt-2">
                                                            <span class="text-sm text-gray-500 dark:text-gray-400">Category: {{ $item->category }}</span>
                                                            <span class="text-sm text-gray-500 dark:text-gray-400">Material: {{ $item->material }}</span>
                                                            <span class="text-sm text-gray-500 dark:text-gray-400">Stock: {{ $item->stock_quantity }}</span>
                                                        </div>
                                                    </div>
                                                    <div class="text-right">
                                                        <p class="text-xl font-bold text-gray-900 dark:text-gray-100">UGX {{ number_format($item->base_price, 2) }}</p>
                                                        <div class="flex items-center space-x-2 mt-2">
                                                            <input type="checkbox" id="select_item_{{ $item->id }}" name="items[{{ $item->id }}][selected]" value="1" onchange="toggleQuantityInput({{ $item->id }})">
                                                            <label for="select_item_{{ $item->id }}" class="text-sm font-medium text-black">Select</label>
                                                            <label class="text-sm font-medium text-gray-700 dark:text-black ml-2">Qty:</label>
                                                            <input type="number" 
                                                                   name="items[{{ $item->id }}][quantity]" 
                                                                   id="quantity_input_{{ $item->id }}"
                                                                   min="1" 
                                                                   max="{{ $item->stock_quantity }}"
                                                                   class="w-20 border border-gray-300 dark:border-gray-600 rounded-lg px-2 py-1 text-sm focus:ring-2 focus:ring-purple-500 focus:border-transparent dark:bg-gray-700 dark:text-white"
                                                                   style="display:none;"
                                                                   disabled
                                                                   onchange="updateTotal()">
                                                            <input type="hidden" name="items[{{ $item->id }}][item_id]" id="item_id_input_{{ $item->id }}" value="{{ $item->id }}" disabled>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                @else
                                    <div class="text-center py-8">
                                        <i class="fas fa-box-open text-gray-400 dark:text-gray-500 text-4xl mb-4"></i>
                                        <p class="text-black">No products available at the moment.</p>
                                    </div>
                                @endif
                            </div>
                        </div>

                        <!-- Order Summary -->
                        <div class="lg:col-span-1">
                            <div class="card-gradient rounded-xl p-6 sticky top-4">
                                <h3 class="text-lg font-semibold text-gray-800 dark:text-black mb-4">Order Summary</h3>
                                
                                <div class="space-y-4">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 dark:text-black mb-2">Select Manufacturer</label>
                                        <select name="manufacturer_id" class="w-full border border-gray-300 dark:border-gray-600 rounded-lg px-3 py-2 focus:ring-2 focus:ring-purple-500 focus:border-transparent dark:bg-gray-700 dark:text-white" required>
                                            <option value="">-- Select Manufacturer --</option>
                                            @foreach($manufacturers as $manufacturer)
                                                <option value="{{ $manufacturer->id }}">{{ $manufacturer->user->name ?? 'Manufacturer #'.$manufacturer->id }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 dark:text-black mb-2">Payment Method</label>
                                        <select name="payment_method" class="w-full border border-gray-300 dark:border-gray-600 rounded-lg px-3 py-2 focus:ring-2 focus:ring-purple-500 focus:border-transparent dark:bg-gray-700 dark:text-white">
                                            <option value="cash on delivery">Cash on Delivery</option>
                                            <option value="mobile money">Mobile Money</option>
                                            <option value="bank_transfer">Bank Transfer</option>
                                        </select>
                                    </div>
                                    
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 dark:text-black mb-2">Delivery Address</label>
                                        <textarea name="delivery_address" rows="3" class="w-full border border-gray-300 dark:border-gray-600 rounded-lg px-3 py-2 focus:ring-2 focus:ring-purple-500 focus:border-transparent dark:bg-gray-700 dark:text-white" placeholder="Enter your delivery address">{{ old('delivery_address') }}</textarea>
                                    </div>
                                    
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 dark:text-black mb-2">Notes (Optional)</label>
                                        <textarea name="notes" rows="3" class="w-full border border-gray-300 dark:border-gray-600 rounded-lg px-3 py-2 focus:ring-2 focus:ring-purple-500 focus:border-transparent dark:bg-gray-700 dark:text-white" placeholder="Any special instructions...">{{ old('notes') }}</textarea>
                                    </div>
                                    
                                    <div class="border-t border-gray-200 dark:border-gray-700 pt-4">
                                        <div class="flex justify-between text-sm mb-2">
                                            <span class="text-gray-600 dark:text-black">Subtotal:</span>
                                            <span class="font-medium dark:text-black" id="subtotal">UGX 0.00</span>
                                        </div>
                                        <div class="flex justify-between text-sm mb-2">
                                            <span class="text-gray-600 dark:text-black">Tax (10%):</span>
                                            <span class="font-medium dark:text-black" id="tax">UGX 0.00</span>
                                        </div>
                                        <div class="flex justify-between text-lg font-bold border-t border-gray-200 dark:border-gray-700 pt-2">
                                            <span class="dark:text-black">Total:</span>
                                            <span class="dark:text-black" id="total">UGX 0.00</span>
                                        </div>
                                    </div>
                                    
                                    <button type="submit" class="w-full bg-gradient-to-r from-purple-600 to-purple-700 text-white py-3 rounded-xl shadow-lg hover:from-purple-700 hover:to-purple-800 transition-all duration-300 font-semibold">
                                        <i class="fas fa-shopping-cart mr-2"></i>
                                        Place Order
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </main>
        </div>
    </div>

    <script>
        // Mobile menu toggle
        document.getElementById('menu-toggle').addEventListener('click', function() {
            document.getElementById('sidebar').classList.toggle('open');
        });

        // Update total calculation
        function updateTotal() {
            let subtotal = 0;
            const quantityInputs = document.querySelectorAll('input[name*="[quantity]"]');
            
            quantityInputs.forEach(input => {
                const quantity = parseInt(input.value) || 0;
                const itemCard = input.closest('.bg-white, .dark\\:bg-gray-800');
                const priceElement = itemCard.querySelector('.text-xl');
                const price = parseFloat(priceElement.textContent.replace('UGX', '').replace(',', '')) || 0;
                
                subtotal += quantity * price;
            });
            
            const tax = subtotal * 0.1; // 10% tax
            const total = subtotal + tax;
            
            document.getElementById('subtotal').textContent = 'UGX ' + subtotal.toFixed(2);
            document.getElementById('tax').textContent = 'UGX ' + tax.toFixed(2);
            document.getElementById('total').textContent = 'UGX ' + total.toFixed(2);
        }

        // Add event listeners to quantity inputs
        document.addEventListener('DOMContentLoaded', function() {
            const quantityInputs = document.querySelectorAll('input[name*="[quantity]"]');
            quantityInputs.forEach(input => {
                input.addEventListener('input', updateTotal);
            });
        });

        function toggleQuantityInput(itemId) {
            const checkbox = document.getElementById('select_item_' + itemId);
            const quantityInput = document.getElementById('quantity_input_' + itemId);
            const itemIdInput = document.getElementById('item_id_input_' + itemId);
            if (checkbox.checked) {
                quantityInput.style.display = '';
                quantityInput.required = true;
                quantityInput.disabled = false;
                itemIdInput.disabled = false;
            } else {
                quantityInput.style.display = 'none';
                quantityInput.required = false;
                quantityInput.value = '';
                quantityInput.disabled = true;
                itemIdInput.disabled = true;
                updateTotal();
            }
        }
    </script>
</body>
</html> 