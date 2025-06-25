{{-- resources/views/supplier/dashboard.blade.php --}}
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Supplier Dashboard - ChicAura SCM</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="{{ asset('js/theme-switcher.js') }}"></script>
    <style>
        body {
            background: linear-gradient(135deg, rgba(0,0,0,0.7) 0%, rgba(0,0,0,0.5) 100%), url('{{ asset('images/supplier.jpg') }}');
            background-size: cover;
            background-position: center;
            background-attachmecnt: fixed;
            min-height: 100vh;
            overflow: hidden;
        }

        /* Dark mode styles */
        .dark body {
            background: linear-gradient(135deg, rgba(0,0,0,0.8) 0%, rgba(0,0,0,0.7) 100%), url('{{ asset('images/supplier.jpg') }}');
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
    <div class="flex h-screen">
        <!-- Sidebar -->
        <aside id="sidebar" class="sidebar absolute md:relative z-20 flex-shrink-0 w-64 md:block">
            <div class="flex flex-col h-full">
                <div class="flex items-center justify-center h-16 border-b border-gray-600">
                    <div class="logo-container">
                        <img src="{{ asset('images/logo.png') }}" alt="ChicAura Logo" class="h-12 w-auto">
                    </div>
                </div>
                <div class="px-4 py-4">
                    <h3 class="text-white text-sm font-semibold mb-3 px-3">SUPPLIER PORTAL</h3>
                </div>
                <nav class="flex-1 px-4 py-2 space-y-1">
                    <a href="#home" class="nav-link flex items-center px-3 py-2 text-white bg-gradient-to-r from-green-600 to-green-700 rounded-xl shadow-lg"><i class="fas fa-home w-5"></i><span class="ml-2 font-medium text-sm">Home</span></a>
                    <a href="#supply-request" class="nav-link flex items-center px-3 py-2 text-gray-300 hover:bg-gray-700 hover:text-white rounded-xl"><i class="fas fa-dolly w-5"></i><span class="ml-2 text-sm">Supply Request</span></a>
                    <a href="#analytics" class="nav-link flex items-center px-3 py-2 text-gray-300 hover:bg-gray-700 hover:text-white rounded-xl"><i class="fas fa-chart-bar w-5"></i><span class="ml-2 text-sm">Analytics</span></a>
                    <a href="#chat" class="nav-link flex items-center px-3 py-2 text-gray-300 hover:bg-gray-700 hover:text-white rounded-xl"><i class="fas fa-comments w-5"></i><span class="ml-2 text-sm">Chat</span></a>
                    <a href="#reports" class="nav-link flex items-center px-3 py-2 text-gray-300 hover:bg-gray-700 hover:text-white rounded-xl"><i class="fas fa-file-alt w-5"></i><span class="ml-2 text-sm">Reports</span></a>
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
                        <input type="text" class="w-80 py-2 pl-10 pr-4 border border-gray-300 rounded-xl focus:ring-2 focus:ring-green-500 focus:border-transparent text-sm" placeholder="Search supply requests, items...">
                    </div>
                </div>
                <div class="flex items-center pr-4 space-x-3">
                    <button class="p-2 text-gray-500 hover:text-green-600 hover:bg-green-50 rounded-full transition-colors"><i class="fas fa-bell text-lg"></i></button>
                    <button data-theme-toggle class="p-2 text-gray-500 hover:text-green-600 hover:bg-green-50 rounded-full transition-colors" title="Switch Theme">
                        <i class="fas fa-moon text-lg"></i>
                    </button>
                    <div class="relative">
                        <button class="flex items-center focus:outline-none bg-white rounded-full p-2 shadow-md hover:shadow-lg transition-shadow">
                            <span class="mr-2 text-gray-700 font-medium text-sm">{{ Auth::user()->name ?? 'Supplier User' }}</span>
                            <img class="w-7 h-7 rounded-full border-2 border-green-200 object-cover"
                                 src="{{ Auth::user()->profile_picture ? Storage::disk('public')->url(Auth::user()->profile_picture) : asset('images/default-avatar.svg') }}"
                                 alt="User Avatar">
                        </button>
                    </div>
                    <div class="flex items-center space-x-2">
                        <a href="{{ route('user.profile.edit') }}" class="p-2 text-gray-500 hover:text-green-600 hover:bg-green-50 rounded-full transition-colors" title="Edit Profile">
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
            <main class="flex-1 p-4 overflow-hidden">
                <div id="home" class="mb-4 dashboard-section">
                    <h2 class="text-2xl font-bold text-white mb-1">Supplier Dashboard</h2>
                    <p class="text-gray-200 text-sm">Manage your supply chain operations efficiently.</p>
                    <div class="grid grid-cols-1 gap-4 mt-4 sm:grid-cols-2 lg:grid-cols-4">
                        <div class="stat-card p-4 rounded-xl">
                            <div class="flex items-center">
                                <div class="p-3 bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl shadow-lg">
                                    <i class="fas fa-box-open text-white text-xl"></i>
                                </div>
                                <div class="ml-3">
                                    <p class="text-xs font-medium text-gray-600">Total Supplied</p>
                                    <p class="text-2xl font-bold text-gray-800">{{ $stats['total_supplied'] ?? '0' }}</p>
                                    <p class="text-xs text-green-600 mt-1">↗ +15% this month</p>
                                </div>
                            </div>
                        </div>
                        <div class="stat-card p-4 rounded-xl">
                            <div class="flex items-center">
                                <div class="p-3 bg-gradient-to-br from-green-500 to-green-600 rounded-xl shadow-lg">
                                    <i class="fas fa-star text-white text-xl"></i>
                                </div>
                                <div class="ml-3">
                                    <p class="text-xs font-medium text-gray-600">Quality Rating</p>
                                    <p class="text-2xl font-bold text-gray-800">{{ $stats['rating'] ?? '0' }}</p>
                                    <p class="text-xs text-green-600 mt-1">⭐ Excellent</p>
                                </div>
                            </div>
                        </div>
                        <div class="stat-card p-4 rounded-xl">
                            <div class="flex items-center">
                                <div class="p-3 bg-gradient-to-br from-yellow-500 to-yellow-600 rounded-xl shadow-lg">
                                    <i class="fas fa-inbox text-white text-xl"></i>
                                </div>
                                <div class="ml-3">
                                    <p class="text-xs font-medium text-gray-600">Active Requests</p>
                                    <p class="text-2xl font-bold text-gray-800">{{ $stats['active_requests'] ?? '0' }}</p>
                                    <p class="text-xs text-yellow-600 mt-1">⏳ Processing</p>
                                </div>
                            </div>
                        </div>
                        <div class="stat-card p-4 rounded-xl">
                            <div class="flex items-center">
                                <div class="p-3 bg-gradient-to-br from-purple-500 to-purple-600 rounded-xl shadow-lg">
                                    <i class="fas fa-calendar-alt text-white text-xl"></i>
                                </div>
                                <div class="ml-3">
                                    <p class="text-xs font-medium text-gray-600">Last Supply</p>
                                    <p class="text-2xl font-bold text-gray-800">{{ $stats['last_supply'] ?? 'N/A' }}</p>
                                    <p class="text-xs text-purple-600 mt-1">📅 Recent</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="grid grid-cols-1 gap-4 mt-4 lg:grid-cols-3 h-64">
                        <div class="card-gradient p-4 rounded-xl lg:col-span-2 overflow-hidden">
                            <h3 class="text-lg font-bold text-gray-800 mb-3">Supply Volume (Units)</h3>
                            <canvas id="supplyChart" class="w-full h-48"></canvas>
                        </div>
                        <div class="card-gradient p-4 rounded-xl overflow-hidden">
                            <h3 class="text-lg font-bold text-gray-800 mb-3">Active Supply Requests</h3>
                            <div class="space-y-2 h-48 overflow-y-auto">
                                @forelse ($supplyRequests as $request)
                                    <div class="flex items-center p-3 bg-white rounded-lg shadow-sm hover:shadow-md transition-shadow">
                                        <div class="flex-shrink-0">
                                            <div class="w-10 h-10 flex items-center justify-center rounded-full {{ $request['status_color'] }} bg-opacity-10">
                                                <i class="fas {{ $request['icon'] }} {{ $request['status_color'] }} text-sm"></i>
                                            </div>
                                        </div>
                                        <div class="ml-3 flex-1">
                                            <p class="text-xs font-medium text-gray-900">{{ $request->item->name }}</p>
                                            <p class="text-xs text-gray-500">Request #{{ $request['id'] }}</p>
                                        </div>
                                        <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full {{ $request['status_color'] }} text-white">{{ $request['status'] }}</span>
                                    </div>
                                @empty
                                    <div class="text-center py-6">
                                        <i class="fas fa-inbox text-gray-400 text-2xl mb-2"></i>
                                        <p class="text-gray-500 text-sm">No active requests.</p>
                                    </div>
                                @endforelse
                            </div>
                        </div>
                    </div>
                </div>
                <section id="supply-request" class="mt-10 dashboard-section hidden">
                    <h3 class="text-xl font-bold text-white mb-3">Supply Request</h3>
                    <div class="card-gradient p-6 rounded-xl mb-6">
                        <button id="createSupplyRequestBtn" class="mb-4 px-4 py-2 bg-green-600 text-white rounded hover:bg-green-700 transition">+ Create Supply Request</button>
                        <div class="overflow-x-auto">
                            <table id="supplyRequestsTable" class="min-w-full divide-y divide-gray-200">
                                <thead>
                                    <tr>
                                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">ID</th>
                                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Item</th>
                                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Quantity</th>
                                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Due Date</th>
                                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Payment Type</th>
                                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Delivery Method</th>
                                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @forelse($supplyRequests as $request)
                                    <tr data-id="{{ $request->id }}">
                                        <td class="px-4 py-2">#{{ $request->id }}</td>
                                        <td class="px-4 py-2">
                                            <span class="font-semibold">{{ $request->item->name }}</span>
                                            <br>
                                            <span class="text-xs text-gray-500">{{ $request->item->description }}</span>
                                        </td>
                                        <td class="px-4 py-2">{{ number_format($request->quantity) }}</td>
                                        <td class="px-4 py-2">
                                            <span class="{{ $request->due_date && $request->due_date->isPast() ? 'text-red-500' : '' }}">
                                                {{ $request->due_date ? $request->due_date->format('M d, Y') : 'N/A' }}
                                            </span>
                                        </td>
                                        <td class="px-4 py-2">
                                            <span class="inline-block px-2 py-1 rounded text-xs font-semibold
                                                @if($request->status === 'pending') bg-yellow-200 text-yellow-800
                                                @elseif($request->status === 'accepted' || $request->status === 'approved') bg-green-200 text-green-800
                                                @elseif($request->status === 'rejected' || $request->status === 'declined') bg-red-200 text-red-800
                                                @elseif($request->status === 'in_progress') bg-blue-200 text-blue-800
                                                @elseif($request->status === 'completed') bg-purple-200 text-purple-800
                                                @else bg-gray-200 text-gray-800 @endif">
                                                {{ ucfirst(str_replace('_', ' ', $request->status)) }}
                                            </span>
                                        </td>
                                        <td class="px-4 py-2">{{ ucfirst($request->payment_type) }}</td>
                                        <td class="px-4 py-2">{{ ucfirst($request->delivery_method) }}</td>
                                        <td class="px-4 py-2 space-x-2">
                                            <a href="{{ route('supplier.supply-requests.show', $request) }}" class="text-blue-600 hover:underline text-sm"><i class="fas fa-eye"></i> View</a>
                                            <a href="#" class="text-yellow-600 hover:underline text-sm"><i class="fas fa-edit"></i> Edit</a>
                                            <a href="#" class="text-red-600 hover:underline text-sm delete-supply-request" data-id="{{ $request->id }}"><i class="fas fa-trash"></i> Delete</a>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="8" class="text-center py-4 text-gray-500">No supply requests found.</td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <!-- Modal (hidden by default) -->
                    <div id="createSupplyRequestModal" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50 hidden">
                        <div class="bg-white rounded-lg shadow-lg w-full max-w-lg p-6 relative">
                            <button id="closeSupplyRequestModal" class="absolute top-2 right-2 text-gray-500 hover:text-red-600 text-xl">&times;</button>
                            <h4 class="text-lg font-bold mb-4">Create Supply Request</h4>
                            <form id="supplyRequestForm">
                                @csrf
                                <div class="mb-4">
                                    <label class="block text-gray-700 mb-1">Item</label>
                                    <select name="item_id" class="w-full border rounded px-3 py-2" required>
                                        <option value="">Select Item</option>
                                        @foreach($items as $item)
                                            <option value="{{ $item->id }}">{{ $item->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="mb-4">
                                    <label class="block text-gray-700 mb-1">Quantity</label>
                                    <input type="number" name="quantity" class="w-full border rounded px-3 py-2" placeholder="Quantity" min="1" required>
                                </div>
                                <div class="mb-4">
                                    <label class="block text-gray-700 mb-1">Due Date</label>
                                    <input type="date" name="due_date" class="w-full border rounded px-3 py-2" required>
                                </div>
                                <div class="mb-4">
                                    <label class="block text-gray-700 mb-1">Payment Type</label>
                                    <select name="payment_type" class="w-full border rounded px-3 py-2" required>
                                        <option value="">Select Payment Type</option>
                                        @foreach($paymentTypes as $type)
                                            <option value="{{ $type }}">{{ ucfirst($type) }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="mb-4">
                                    <label class="block text-gray-700 mb-1">Delivery Method</label>
                                    <select name="delivery_method" class="w-full border rounded px-3 py-2" required>
                                        <option value="">Select Delivery Method</option>
                                        @foreach($deliveryMethods as $method)
                                            <option value="{{ $method }}">{{ ucfirst($method) }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="mb-4">
                                    <label class="block text-gray-700 mb-1">Notes</label>
                                    <textarea name="notes" class="w-full border rounded px-3 py-2" placeholder="Notes (optional)"></textarea>
                                </div>
                                <div class="flex justify-end">
                                    <button type="button" class="px-4 py-2 bg-gray-300 rounded mr-2" id="cancelSupplyRequestBtn">Cancel</button>
                                    <button type="submit" class="px-4 py-2 bg-green-600 text-white rounded">Create</button>
                                </div>
                            </form>
                            <div id="supplyRequestFormMsg" class="mt-2 text-sm"></div>
                        </div>
                    </div>
                    <script>
                    // Modal open/close logic
                    document.getElementById('createSupplyRequestBtn').onclick = function() {
                        document.getElementById('createSupplyRequestModal').classList.remove('hidden');
                    };
                    document.getElementById('closeSupplyRequestModal').onclick = function() {
                        document.getElementById('createSupplyRequestModal').classList.add('hidden');
                    };
                    document.getElementById('cancelSupplyRequestBtn').onclick = function() {
                        document.getElementById('createSupplyRequestModal').classList.add('hidden');
                    };

                    // AJAX create supply request
                    document.getElementById('supplyRequestForm').onsubmit = async function(e) {
                        e.preventDefault();
                        const form = e.target;
                        const formData = new FormData(form);
                        const msgDiv = document.getElementById('supplyRequestFormMsg');
                        msgDiv.textContent = '';
                        try {
                            const res = await fetch("{{ route('supplier.supply-requests.store') }}", {
                                method: 'POST',
                                headers: {
                                    'X-CSRF-TOKEN': document.querySelector('input[name=_token]')?.value || document.querySelector('meta[name=csrf-token]')?.content,
                                    'Accept': 'application/json',
                                },
                                body: formData
                            });
                            const data = await res.json();
                            if (data.success) {
                                msgDiv.textContent = 'Supply request created!';
                                msgDiv.className = 'mt-2 text-green-600';
                                // Add new row to table
                                const table = document.getElementById('supplyRequestsTable').querySelector('tbody');
                                const req = data.supplyRequest;
                                const newRow = document.createElement('tr');
                                newRow.setAttribute('data-id', req.id);
                                newRow.innerHTML = `
                                    <td class="px-4 py-2">#${req.id}</td>
                                    <td class="px-4 py-2"><span class="font-semibold">${req.item.name}</span><br><span class="text-xs text-gray-500">${req.item.description || ''}</span></td>
                                    <td class="px-4 py-2">${req.quantity}</td>
                                    <td class="px-4 py-2">${req.due_date ? (new Date(req.due_date)).toLocaleDateString() : 'N/A'}</td>
                                    <td class="px-4 py-2"><span class="inline-block px-2 py-1 rounded text-xs font-semibold bg-yellow-200 text-yellow-800">Pending</span></td>
                                    <td class="px-4 py-2">${req.payment_type.charAt(0).toUpperCase() + req.payment_type.slice(1)}</td>
                                    <td class="px-4 py-2">${req.delivery_method.charAt(0).toUpperCase() + req.delivery_method.slice(1)}</td>
                                    <td class="px-4 py-2 space-x-2">
                                        <a href="/supply-requests/${req.id}" class="text-blue-600 hover:underline text-sm"><i class="fas fa-eye"></i> View</a>
                                        <a href="#" class="text-yellow-600 hover:underline text-sm"><i class="fas fa-edit"></i> Edit</a>
                                        <a href="#" class="text-red-600 hover:underline text-sm delete-supply-request" data-id="${req.id}"><i class="fas fa-trash"></i> Delete</a>
                                    </td>
                                `;
                                table.prepend(newRow);
                                form.reset();
                                setTimeout(() => {
                                    document.getElementById('createSupplyRequestModal').classList.add('hidden');
                                    msgDiv.textContent = '';
                                }, 1000);
                            } else {
                                msgDiv.textContent = data.error || 'Failed to create supply request.';
                                msgDiv.className = 'mt-2 text-red-600';
                            }
                        } catch (err) {
                            msgDiv.textContent = 'Error creating supply request.';
                            msgDiv.className = 'mt-2 text-red-600';
                        }
                    };

                    // AJAX delete supply request
                    document.querySelectorAll('.delete-supply-request').forEach(btn => {
                        btn.onclick = async function(e) {
                            e.preventDefault();
                            if (!confirm('Are you sure you want to delete this supply request?')) return;
                            const id = this.getAttribute('data-id');
                            try {
                                const res = await fetch(`/supply-requests/${id}`, {
                                    method: 'DELETE',
                                    headers: {
                                        'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]')?.content,
                                        'Accept': 'application/json',
                                    },
                                });
                                const data = await res.json();
                                if (data.success) {
                                    const row = document.querySelector(`tr[data-id='${id}']`);
                                    if (row) row.remove();
                                } else {
                                    alert(data.error || 'Failed to delete supply request.');
                                }
                            } catch (err) {
                                alert('Error deleting supply request.');
                            }
                        };
                    });
                    </script>
                </section>
                <section id="analytics" class="mt-10 dashboard-section hidden">
                    <h3 class="text-xl font-bold text-white mb-3">Analytics</h3>
                    <div class="grid grid-cols-1 gap-4 mb-6 sm:grid-cols-2 lg:grid-cols-4">
                        <div class="stat-card p-4 rounded-xl">
                            <div class="flex items-center">
                                <div class="p-3 bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl shadow-lg">
                                    <i class="fas fa-box-open text-white text-xl"></i>
                                </div>
                                <div class="ml-3">
                                    <p class="text-xs font-medium text-gray-600">Total Supplied</p>
                                    <p class="text-2xl font-bold text-gray-800">{{ $stats['total_supplied'] ?? 0 }}</p>
                                </div>
                            </div>
                        </div>
                        <div class="stat-card p-4 rounded-xl">
                            <div class="flex items-center">
                                <div class="p-3 bg-gradient-to-br from-green-500 to-green-600 rounded-xl shadow-lg">
                                    <i class="fas fa-star text-white text-xl"></i>
                                </div>
                                <div class="ml-3">
                                    <p class="text-xs font-medium text-gray-600">Average Rating</p>
                                    <p class="text-2xl font-bold text-gray-800">{{ number_format($stats['average_rating'] ?? 0, 1) }}</p>
                                </div>
                            </div>
                        </div>
                        <div class="stat-card p-4 rounded-xl">
                            <div class="flex items-center">
                                <div class="p-3 bg-gradient-to-br from-yellow-500 to-yellow-600 rounded-xl shadow-lg">
                                    <i class="fas fa-inbox text-white text-xl"></i>
                                </div>
                                <div class="ml-3">
                                    <p class="text-xs font-medium text-gray-600">Active Requests</p>
                                    <p class="text-2xl font-bold text-gray-800">{{ $stats['active_requests'] ?? 0 }}</p>
                                </div>
                            </div>
                        </div>
                        <div class="stat-card p-4 rounded-xl">
                            <div class="flex items-center">
                                <div class="p-3 bg-gradient-to-br from-purple-500 to-purple-600 rounded-xl shadow-lg">
                                    <i class="fas fa-dollar-sign text-white text-xl"></i>
                                </div>
                                <div class="ml-3">
                                    <p class="text-xs font-medium text-gray-600">Total Revenue</p>
                                    <p class="text-2xl font-bold text-gray-800">${{ number_format($stats['total_revenue'] ?? 0, 2) }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-gradient p-6 rounded-xl mb-6" style="max-width: 100%; overflow-x: auto;">
    <h4 class="text-lg font-bold text-gray-800 mb-3">Supply Trends (Monthly)</h4>
    <div style="width:100%; max-width:700px; margin:auto;">
        <canvas id="supplyTrendsChart" style="width:100%; height:300px; max-width:100%;"></canvas>
    </div>
</div>
                    <script>
                    document.addEventListener('DOMContentLoaded', function() {
                        if (document.getElementById('supplyTrendsChart')) {
                            const ctx = document.getElementById('supplyTrendsChart').getContext('2d');
                            const months = @json($supplyTrends->map(fn($t) => DateTime::createFromFormat('!m', $t->month)->format('F')));
                            const totals = @json($supplyTrends->map(fn($t) => $t->total));
                            new Chart(ctx, {
                                type: 'line',
                                data: {
                                    labels: months,
                                    datasets: [{
                                        label: 'Total Supplied',
                                        data: totals,
                                        borderColor: '#10B981',
                                        backgroundColor: 'rgba(16, 185, 129, 0.1)',
                                        borderWidth: 3,
                                        fill: true,
                                        tension: 0.4
                                    }]
                                },
                                options: {
                                    responsive: true,
                                    maintainAspectRatio: false,
                                    plugins: { legend: { display: false } },
                                    scales: {
                                        y: { beginAtZero: true, grid: { color: 'rgba(0,0,0,0.1)' } },
                                        x: { grid: { display: false } }
                                    }
                                }
                            });
                        }
                    });
                    </script>
                </section>
                <section id="chat" class="mt-10 dashboard-section hidden">
                    <h3 class="text-xl font-bold text-white mb-3">Chat</h3>
                    <div class="grid grid-cols-1 lg:grid-cols-4 gap-6">
                        <!-- Contacts List -->
                        <div class="lg:col-span-1">
                            <div class="card-gradient p-4 rounded-xl mb-4">
                                <h4 class="font-semibold text-gray-800 mb-3">Contacts</h4>
                                <ul class="space-y-2">
                                    <li>
                                        <a href="#" class="flex items-center space-x-3 p-2 rounded-lg bg-green-100 text-green-900 font-medium contact-link" data-contact="manufacturer">
                                            <img src="https://via.placeholder.com/40" class="rounded-full" alt="Manufacturer">
                                            <span>Manufacturer Team</span>
                                            <span class="ml-auto text-xs text-green-600">Online</span>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="#" class="flex items-center space-x-3 p-2 rounded-lg hover:bg-gray-100 contact-link" data-contact="procurement">
                                            <img src="https://via.placeholder.com/40" class="rounded-full" alt="Procurement">
                                            <span>Procurement Team</span>
                                            <span class="ml-auto text-xs text-gray-500">2h ago</span>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="#" class="flex items-center space-x-3 p-2 rounded-lg hover:bg-gray-100 contact-link" data-contact="quality">
                                            <img src="https://via.placeholder.com/40" class="rounded-full" alt="Quality">
                                            <span>Quality Control</span>
                                            <span class="ml-auto text-xs text-gray-500">1d ago</span>
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                        <!-- Chat Messages Area -->
                        <div class="lg:col-span-3">
                            <div class="card-gradient p-4 rounded-xl flex flex-col h-[500px]">
                                <div class="flex items-center mb-4">
                                    <img src="https://via.placeholder.com/32" class="rounded-full mr-2" alt="Manufacturer">
                                    <h5 class="font-semibold text-gray-800 mb-0">Manufacturer Team</h5>
                                    <span class="ml-2 px-2 py-1 bg-green-200 text-green-800 rounded text-xs">Online</span>
                                </div>
                                <div id="chat-messages" class="flex-1 overflow-y-auto space-y-4 mb-4 bg-white rounded p-3">
                                    <!-- Demo messages -->
                                    <div class="flex items-start space-x-2">
                                        <img src="https://via.placeholder.com/32" class="rounded-full" alt="Manufacturer">
                                        <div class="bg-gray-100 p-3 rounded-xl">
                                            <div class="text-xs text-gray-500 mb-1">Manufacturer Team • 10:30 AM</div>
                                            <div>Hello! We have a new supply request for cotton fabric. Can you check your availability?</div>
                                        </div>
                                    </div>
                                    <div class="flex items-end justify-end space-x-2">
                                        <div class="bg-green-600 text-white p-3 rounded-xl">
                                            <div class="text-xs text-green-200 mb-1">You • 10:32 AM</div>
                                            <div>Hi! Yes, I can check. What quantity do you need?</div>
                                        </div>
                                        <img src="https://via.placeholder.com/32" class="rounded-full" alt="You">
                                    </div>
                                    <div class="flex items-start space-x-2">
                                        <img src="https://via.placeholder.com/32" class="rounded-full" alt="Manufacturer">
                                        <div class="bg-gray-100 p-3 rounded-xl">
                                            <div class="text-xs text-gray-500 mb-1">Manufacturer Team • 10:33 AM</div>
                                            <div>We need 5000 meters of premium cotton fabric. What's your best price?</div>
                                        </div>
                                    </div>
                                    <div class="flex items-end justify-end space-x-2">
                                        <div class="bg-green-600 text-white p-3 rounded-xl">
                                            <div class="text-xs text-green-200 mb-1">You • 10:35 AM</div>
                                            <div>For that quantity, I can offer $2.50 per meter. Delivery within 2 weeks.</div>
                                        </div>
                                        <img src="https://via.placeholder.com/32" class="rounded-full" alt="You">
                                    </div>
                                </div>
                                <form id="chat-form" class="flex mt-2">
                                    <input type="text" id="message-input" class="flex-1 border rounded-l px-3 py-2 focus:outline-none" placeholder="Type your message...">
                                    <button type="submit" class="bg-green-600 text-white px-4 py-2 rounded-r hover:bg-green-700"><i class="fas fa-paper-plane"></i></button>
                                </form>
                            </div>
                        </div>
                    </div>
                    <script>
                    document.addEventListener('DOMContentLoaded', function() {
                        const chatForm = document.getElementById('chat-form');
                        const messageInput = document.getElementById('message-input');
                        const chatMessages = document.getElementById('chat-messages');
                        chatForm.addEventListener('submit', function(e) {
                            e.preventDefault();
                            const message = messageInput.value.trim();
                            if (!message) return;
                            // Add message to chat
                            const messageElement = document.createElement('div');
                            messageElement.className = 'flex items-end justify-end space-x-2';
                            messageElement.innerHTML = `
                                <div class="bg-green-600 text-white p-3 rounded-xl">
                                    <div class="text-xs text-green-200 mb-1">You • ${new Date().toLocaleTimeString([], {hour: '2-digit', minute:'2-digit'})}</div>
                                    <div>${message}</div>
                                </div>
                                <img src="https://via.placeholder.com/32" class="rounded-full" alt="You">
                            `;
                            chatMessages.appendChild(messageElement);
                            chatMessages.scrollTop = chatMessages.scrollHeight;
                            messageInput.value = '';
                            // Simulate response
                            setTimeout(() => {
                                const responseElement = document.createElement('div');
                                responseElement.className = 'flex items-start space-x-2';
                                responseElement.innerHTML = `
                                    <img src="https://via.placeholder.com/32" class="rounded-full" alt="Manufacturer">
                                    <div class="bg-gray-100 p-3 rounded-xl">
                                        <div class="text-xs text-gray-500 mb-1">Manufacturer Team • ${new Date().toLocaleTimeString([], {hour: '2-digit', minute:'2-digit'})}</div>
                                        <div>Thank you for the quick response. We'll review and get back to you shortly.</div>
                                    </div>
                                `;
                                chatMessages.appendChild(responseElement);
                                chatMessages.scrollTop = chatMessages.scrollHeight;
                            }, 1000);
                        });
                    });
                    </script>
                </section>
                <section id="reports" class="mt-10 mb-10 dashboard-section hidden">
                    <h3 class="text-xl font-bold text-white mb-3">Reports</h3>
                    <div class="card-gradient p-6 rounded-xl mb-6">
                        <h4 class="text-lg font-bold text-gray-800 mb-3">Monthly Reports</h4>
                        @if(isset($reports) && count($reports) > 0)
                            <div class="overflow-x-auto">
                                <table class="min-w-full divide-y divide-gray-200">
                                    <thead>
                                        <tr>
                                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Month</th>
                                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Download</th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white divide-y divide-gray-200">
                                        @foreach($reports as $report)
                                        <tr>
                                            <td class="px-4 py-2">{{ $report['month'] }}</td>
                                            <td class="px-4 py-2">
                                                <a href="{{ $report['url'] }}" class="text-green-600 hover:underline" download>
                                                    <i class="fas fa-file-download"></i> Download
                                                </a>
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <div class="text-gray-600 text-center py-8">
                                <i class="fas fa-file-alt fa-2x mb-2"></i>
                                <div>No reports available yet. Reports will appear here as they are generated each month.</div>
                            </div>
                        @endif
                    </div>
                </section>
            </main>
        </div>
    </div>

    <script>
        // Mobile menu toggle
        document.getElementById('menu-toggle').addEventListener('click', function() {
            document.getElementById('sidebar').classList.toggle('open');
        });

        // Supply Volume Chart
        const supplyCtx = document.getElementById('supplyChart').getContext('2d');
        new Chart(supplyCtx, {
            type: 'line',
            data: {
                labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'],
                datasets: [{
                    label: 'Supply Volume',
                    data: [1200, 1900, 3000, 5000, 2000, 3000],
                    borderColor: '#10B981',
                    backgroundColor: 'rgba(16, 185, 129, 0.1)',
                    borderWidth: 3,
                    fill: true,
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
                        beginAtZero: true,
                        grid: {
                            color: 'rgba(0,0,0,0.1)'
                        }
                    },
                    x: {
                        grid: {
                            display: false
                        }
                    }
                }
            }
        });

        // SPA-like navigation for dashboard sections
        const sectionIds = ['home', 'supply-request', 'analytics', 'chat', 'reports'];
        document.querySelectorAll('.sidebar .nav-link').forEach(link => {
            link.addEventListener('click', function(e) {
                const hash = this.getAttribute('href').replace('#', '');
                if (sectionIds.includes(hash)) {
                    e.preventDefault();
                    sectionIds.forEach(id => {
                        const el = document.getElementById(id);
                        if (el) el.classList.add('hidden');
                    });
                    const target = document.getElementById(hash);
                    if (target) target.classList.remove('hidden');
                }
            });
        });
    </script>
</body>
</html>
