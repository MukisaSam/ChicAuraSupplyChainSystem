<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Inventory Item - ChicAura SCM</title>
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
                    <a href="{{route('manufacturer.inventory')}}" class="nav-link flex items-center px-3 py-2 text-white bg-gradient-to-r from-indigo-600 to-indigo-700 rounded-xl shadow-lg">
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
                    <a href="{{route('manufacturer.reports')}}" class="nav-link flex items-center px-3 py-2 text-gray-300 hover:bg-gray-700 hover:text-white rounded-xl">
                        <i class="fas fa-file-alt w-5"></i>
                        <span class="ml-2 text-sm">Reports</span>
                    </a>
                    <a href="{{route('manufacturer.revenue')}}" class="nav-link flex items-center px-3 py-2 text-gray-300 hover:bg-gray-700 hover:text-white rounded-xl">
                        <i class="fas fa-dollar-sign w-5"></i>
                        <span class="ml-2 text-sm">Revenue</span>
                    </a>
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
                </nav>
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
                        <a href="{{ route('manufacturer.inventory') }}" class="text-gray-500 hover:text-gray-700">
                            <i class="fas fa-warehouse"></i>
                        </a>
                        <span class="text-gray-400">/</span>
                        <span class="text-black font-medium">Edit Item</span>
                    </div>
                </div>
                <div class="flex items-center pr-4 space-x-3">
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
                </div>
            </header>

            <!-- Main Content -->
            <main class="main-content-scrollable" style="flex: 1 1 0%; overflow-y: auto; padding: 2rem 1.5rem; margin-top: 4rem; background: transparent;">
                <div class="bg-white rounded-xl shadow-lg p-8">
                <div class="mb-4">
                        <h2 class="text-2xl font-bold text-black mb-1">Edit Inventory Item</h2>
                        <p class="text-black text-sm">Update the details of your inventory item below.</p>
                </div>

                <div class="card-gradient rounded-xl p-6 max-w-4xl mx-auto">
                    <form method="POST" action="{{ route('manufacturer.inventory.update', $item) }}" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Basic Information -->
                            <div class="space-y-4">
                                <h3 class="text-lg font-semibold text-black border-b pb-2">Basic Information</h3>
                                
                                <div>
                                    <label for="name" class="block text-sm font-medium text-black mb-2">Item Name *</label>
                                    <input type="text" id="name" name="name" value="{{ old('name', $item->name) }}" required
                                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500">
                                    @error('name')
                                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label for="category" class="block text-sm font-medium text-black mb-2">Category *</label>
                                    <select id="category" name="category" required
                                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500">
                                        <option value="">Select Category</option>
                                        <option value="Raw Materials" {{ old('category', $item->category) == 'Raw Materials' ? 'selected' : '' }}>Raw Materials</option>
                                        <option value="Fabrics" {{ old('category', $item->category) == 'Fabrics' ? 'selected' : '' }}>Fabrics</option>
                                        <option value="Threads" {{ old('category', $item->category) == 'Threads' ? 'selected' : '' }}>Threads</option>
                                        <option value="Dyes" {{ old('category', $item->category) == 'Dyes' ? 'selected' : '' }}>Dyes</option>
                                        <option value="Accessories" {{ old('category', $item->category) == 'Accessories' ? 'selected' : '' }}>Accessories</option>
                                        <option value="Finished Products" {{ old('category', $item->category) == 'Finished Products' ? 'selected' : '' }}>Finished Products</option>
                                        <option value="Clothing" {{ old('category', $item->category) == 'Clothing' ? 'selected' : '' }}>Clothing</option>
                                        <option value="Other" {{ old('category', $item->category) == 'Other' ? 'selected' : '' }}>Other</option>
                                    </select>
                                    @error('category')
                                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label for="material" class="block text-sm font-medium text-black mb-2">Material *</label>
                                    <input type="text" id="material" name="material" value="{{ old('material', $item->material) }}" required
                                           placeholder="e.g., Cotton, Silk, Polyester"
                                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500">
                                    @error('material')
                                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label for="description" class="block text-sm font-medium text-black mb-2">Description *</label>
                                    <textarea id="description" name="description" rows="3" required
                                              placeholder="Describe the item, its specifications, and any important details"
                                              class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500">{{ old('description', $item->description) }}</textarea>
                                    @error('description')
                                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>

                            <!-- Pricing & Stock -->
                            <div class="space-y-4">
                                <h3 class="text-lg font-semibold text-black border-b pb-2">Pricing & Stock</h3>
                                
                                <div>
                                    <label for="base_price" class="block text-sm font-medium text-black mb-2">Base Price ($) *</label>
                                    <input type="number" id="base_price" name="base_price" value="{{ old('base_price', $item->base_price) }}" step="0.01" min="0" required
                                           placeholder="0.00"
                                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500">
                                    @error('base_price')
                                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label for="stock_quantity" class="block text-sm font-medium text-black mb-2">Current Stock Quantity *</label>
                                    <input type="number" id="stock_quantity" name="stock_quantity" value="{{ old('stock_quantity', $item->stock_quantity) }}" min="0" required
                                           placeholder="0"
                                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500">
                                    @error('stock_quantity')
                                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label for="size_range" class="block text-sm font-medium text-black mb-2">Size Range</label>
                                    <input type="text" id="size_range" name="size_range" value="{{ old('size_range', $item->size_range) }}"
                                           placeholder="e.g., S, M, L, XL or 36-42"
                                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500">
                                    @error('size_range')
                                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label for="color_options" class="block text-sm font-medium text-black mb-2">Color Options</label>
                                    <input type="text" id="color_options" name="color_options" value="{{ old('color_options', $item->color_options) }}"
                                           placeholder="e.g., Red, Blue, Green, Black"
                                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500">
                                    @error('color_options')
                                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Image Upload -->
                        <div class="mt-6">
                            <h3 class="text-lg font-semibold text-black border-b pb-2 mb-4">Item Image</h3>
                            <div class="flex items-center space-x-4">
                                <div class="flex-shrink-0">
                                    <div id="imagePreview" class="w-32 h-32 border-2 border-dashed border-gray-300 rounded-lg flex items-center justify-center bg-gray-50 overflow-hidden">
                                        @if($item->image_url)
                                            @if(Str::startsWith($item->image_url, ['http://', 'https://']))
                                                <img src="{{ $item->image_url }}" alt="{{ $item->name }}" class="w-32 h-32 object-cover rounded-lg">
                                            @else
                                                <img src="{{ asset('storage/' . $item->image_url) }}" alt="{{ $item->name }}" class="w-32 h-32 object-cover rounded-lg">
                                            @endif
                                        @else
                                            <div class="w-32 h-32 bg-gray-200 flex items-center justify-center rounded-lg">
                                                <i class="fas fa-image text-gray-400"></i>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                                <div class="flex-1">
                                    <label for="image" class="block text-sm font-medium text-black mb-2">Update Image</label>
                                    <input type="file" id="image" name="image" accept="image/*"
                                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500">
                                    <p class="text-xs text-gray-500 mt-1">Leave empty to keep current image. Supported formats: JPEG, PNG, JPG, GIF (Max: 2MB)</p>
                                    @error('image')
                                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Form Actions -->
                        <div class="mt-8 flex justify-end space-x-3">
                            <a href="{{ route('manufacturer.inventory') }}" 
                               class="px-6 py-2 border border-gray-300 text-black rounded-md hover:bg-gray-50 transition-colors">
                                Cancel
                            </a>
                            <button type="submit" 
                                    class="px-6 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700 transition-colors">
                                <i class="fas fa-save mr-2"></i>Update Item
                            </button>
                        </div>
                    </form>
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

        // Image preview functionality
        document.getElementById('image').addEventListener('change', function(e) {
            const file = e.target.files[0];
            const preview = document.getElementById('imagePreview');
            
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    preview.innerHTML = `<img src="${e.target.result}" class="w-full h-full object-cover">`;
                };
                reader.readAsDataURL(file);
            } else {
                // Reset to original image or placeholder
                @if($item->image_url)
                    preview.innerHTML = `
                        @if(Str::startsWith($item->image_url, ['http://', 'https://']))
                            <img src="{{ $item->image_url }}" alt="{{ $item->name }}" class="w-32 h-32 object-cover rounded-lg">
                        @else
                            <img src="{{ asset('storage/' . $item->image_url) }}" alt="{{ $item->name }}" class="w-32 h-32 object-cover rounded-lg">
                        @endif
                    `;
                @else
                    preview.innerHTML = `
                        <div class="w-32 h-32 bg-gray-200 flex items-center justify-center rounded-lg">
                            <i class="fas fa-image text-gray-400"></i>
                        </div>
                    `;
                @endif
            }
        });

        // Auto-format price input
        document.getElementById('base_price').addEventListener('input', function(e) {
            let value = e.target.value;
            if (value && !isNaN(value)) {
                e.target.value = parseFloat(value).toFixed(2);
            }
        });
    </script>
</body>
</html> 