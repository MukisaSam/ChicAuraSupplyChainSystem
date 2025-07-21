<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Supply Request - ChicAura SCM</title>
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
    <div>
        <!-- Sidebar -->
        <aside id="sidebar" class="sidebar" style="position: fixed; top: 0; left: 0; height: 100vh; width: 16rem; z-index: 30; overflow-y: auto; overflow-x: hidden; background: linear-gradient(180deg, #1a237e 0%, #283593 100%); box-shadow: 4px 0 15px rgba(0,0,0,0.12);">
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
                        <span class="ml-2 text-sm">Partners</span>
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
                        <i class="fas fa-dollar-sign w-5"></i>
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
                <!-- Production Section -->
                <div class="mt-6 mb-2">
                    <h4 class="text-gray-400 text-xs font-bold uppercase tracking-wider px-3 mb-1">Production</h4>
                </div>
                <a href="{{ route('manufacturer.work-orders.index') }}" class="nav-link flex items-center px-3 py-2 text-gray-300 hover:bg-indigo-700 hover:text-white rounded-xl">
                    <i class="fas fa-cogs w-5"></i>
                    <span class="ml-2 text-sm">Work Orders</span>
                </a>
                <a href="{{ route('manufacturer.bom.index') }}" class="nav-link flex items-center px-3 py-2 text-gray-300 hover:bg-indigo-700 hover:text-white rounded-xl">
                    <i class="fas fa-list-alt w-5"></i>
                    <span class="ml-2 text-sm">Bill of Materials</span>
                </a>
                <a href="{{ route('manufacturer.production-schedules.index') }}" class="nav-link flex items-center px-3 py-2 text-gray-300 hover:bg-indigo-700 hover:text-white rounded-xl">
                    <i class="fas fa-calendar-alt w-5"></i>
                    <span class="ml-2 text-sm">Production Schedules</span>
                </a>
                <a href="{{ route('manufacturer.quality-checks.index') }}" class="nav-link flex items-center px-3 py-2 text-gray-300 hover:bg-indigo-700 hover:text-white rounded-xl">
                    <i class="fas fa-clipboard-check w-5"></i>
                    <span class="ml-2 text-sm">Quality Checks</span>
                </a>
                <a href="{{ route('manufacturer.downtime-logs.index') }}" class="nav-link flex items-center px-3 py-2 text-gray-300 hover:bg-indigo-700 hover:text-white rounded-xl">
                    <i class="fas fa-stopwatch w-5"></i>
                    <span class="ml-2 text-sm">Downtime Logs</span>
                </a>
                <a href="{{ route('manufacturer.production-costs.index') }}" class="nav-link flex items-center px-3 py-2 text-gray-300 hover:bg-indigo-700 hover:text-white rounded-xl">
                    <i class="fas fa-coins w-5"></i>
                    <span class="ml-2 text-sm">Production Costs</span>
                </a>
                <div class="p-3 border-t border-gray-600">
                    <div class="text-center text-gray-400 text-xs">
                        <p>ChicAura SCM</p>
                        <p class="text-xs mt-1">v2.1.0</p>
                    </div>
                </div>
            </div>
        </aside>
        <div class="main-content-wrapper" style="margin-left: 16rem; min-height: 100vh; display: flex; flex-direction: column;">
            <!-- Top Navigation Bar -->
            <header class="header-gradient relative z-10 flex items-center justify-between h-16 border-b" style="position: fixed; left: 16rem; right: 0; top: 0; height: 4rem; background: #fff; box-shadow: 0 2px 20px rgba(0,0,0,0.04); display: flex; align-items: center;">
                <div class="flex items-center">
                    <!-- Mobile Menu Toggle -->
                    <button id="menu-toggle" class="md:hidden p-3 text-gray-500 hover:text-gray-700">
                        <i class="fas fa-bars text-lg"></i>
                    </button>
                    <!-- Breadcrumb -->
                    <div class="ml-3 flex items-center space-x-2">
                        <a href="{{ route('manufacturer.orders') }}" class="text-gray-500 hover:text-gray-700">
                            <i class="fas fa-box"></i>
                        </a>
                        <span class="text-gray-400">/</span>
                        <span class="text-black font-medium">Create Supply Request</span>
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
            <main class="main-content-scrollable" style="flex: 1 1 0%; overflow-y: auto; padding: 2rem 1.5rem; margin-top: 4rem; background: transparent;">
                <div class="bg-white rounded-xl shadow-lg p-8 max-w-2xl mx-auto">
                    <div class="mb-4">
                        <h2 class="text-2xl font-bold text-black mb-1">Create Supply Request</h2>
                        <p class="text-black text-sm">Request raw materials from suppliers.</p>
                    </div>
                    <form action="{{ route('supply-requests.store') }}" method="POST">
                        @csrf
                        <div class="grid grid-cols-1 gap-6">
                            <!-- Supplier Selection -->
                            <div>
                                <label for="supplier_id" class="block text-sm font-medium text-black mb-2">
                                    Supplier <span class="text-red-500">*</span>
                                </label>
                                <select name="supplier_id" id="supplier_id" required
                                        class="w-full border border-gray-300 rounded-md px-3 py-2 bg-white text-gray-900 focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                                    <option value="">Select a supplier</option>
                                    @foreach($suppliers as $supplier)
                                        <option value="{{ $supplier->id }}" {{ old('supplier_id') == $supplier->id ? 'selected' : '' }}>
                                            {{ $supplier->user->name ?? 'Supplier #'.$supplier->id }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('supplier_id')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            <!-- Item Selection -->
                            <div>
                                <label for="item_id" class="block text-sm font-medium text-black mb-2">
                                    Raw Material <span class="text-red-500">*</span>
                                </label>
                                <select name="item_id" id="item_id" required 
                                        class="w-full border border-gray-300 rounded-md px-3 py-2 bg-white text-gray-900 focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                                    <option value="">Select a raw material</option>
                                    @foreach($rawMaterials as $item)
                                        <option value="{{ $item->id }}" {{ old('item_id') == $item->id ? 'selected' : '' }}>
                                            {{ $item->name }} - {{ $item->category ?? 'N/A' }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('item_id')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            <!-- Quantity -->
                            <div>
                                <label for="quantity" class="block text-sm font-medium text-black mb-2">
                                    Quantity <span class="text-red-500">*</span>
                                </label>
                                <input type="number" name="quantity" id="quantity" min="1" required 
                                       value="{{ old('quantity') }}"
                                       class="w-full border border-gray-300 rounded-md px-3 py-2 bg-white text-gray-900 focus:ring-2 focus:ring-indigo-500 focus:border-transparent"
                                       placeholder="Enter quantity">
                                @error('quantity')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            <!-- Due Date -->
                            <div>
                                <label for="due_date" class="block text-sm font-medium text-black mb-2">
                                    Due Date <span class="text-red-500">*</span>
                                </label>
                                <input type="date" name="due_date" id="due_date" required 
                                       value="{{ old('due_date') }}"
                                       min="{{ date('Y-m-d', strtotime('+1 day')) }}"
                                       class="w-full border border-gray-300 rounded-md px-3 py-2 bg-white text-gray-900 focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                                @error('due_date')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            <!-- Notes -->
                            <div>
                                <label for="notes" class="block text-sm font-medium text-black mb-2">
                                    Notes
                                </label>
                                <textarea name="notes" id="notes" rows="4" 
                                          class="w-full border border-gray-300 rounded-md px-3 py-2 bg-white text-gray-900 focus:ring-2 focus:ring-indigo-500 focus:border-transparent"
                                          placeholder="Add any additional notes or special requirements...">{{ old('notes') }}</textarea>
                                @error('notes')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                        <!-- Form Actions -->
                        <div class="mt-8 flex justify-end space-x-3">
                            <a href="{{ route('manufacturer.orders') }}" 
                               class="px-6 py-2 border border-gray-300 text-black rounded-md hover:bg-gray-50 transition-colors">
                                Cancel
                            </a>
                            <button type="submit" 
                                    class="px-6 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700 transition-colors">
                                <i class="fas fa-plus mr-2"></i>Create Supply Request
                            </button>
                        </div>
                    </form>
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