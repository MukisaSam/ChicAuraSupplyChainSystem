@extends('.layouts.suppliers')
@section('dashboard')
 flex items-center p-2 bg-gray-900 text-white rounded-lg
@endsection
@section('content')
<div>
                <h2 class="text-2xl font-semibold text-gray-800">Welcome, {{ Auth::user()->name }}</h2>
                <p class="text-xl text-gray-600">Supplier Dashboard</p>
            </div>
            
            <!-- Summary Cards -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
                <div class="bg-white p-4 rounded-lg shadow-md">
                    <div class="flex items-center">
                        <div class="w-12 h-12 rounded-full bg-indigo-100 flex items-center justify-center mr-3">
                            <i class="fas fa-file-invoice-dollar text-indigo-600 text-xl"></i>
                        </div>
                        <div>
                            <p class="text-gray-600">Active Orders</p>
                            <h3 class="text-2xl font-bold">24</h3>
                            <p class="text-green-500">+3 this week</p>
                        </div>
                    </div>
                </div>
                <div class="bg-white p-4 rounded-lg shadow-md">
                    <div class="flex items-center">
                        <div class="w-12 h-12 rounded-full bg-amber-100 flex items-center justify-center mr-3">
                            <i class="fas fa-exclamation-circle text-amber-600 text-xl"></i>
                        </div>
                        <div>
                            <p class="text-gray-600">Pending Shipments</p>
                            <h3 class="text-2xl font-bold">8</h3>
                            <p class="text-red-500">-2 overdue</p>
                        </div>
                    </div>
                </div>
                <div class="bg-white p-4 rounded-lg shadow-md">
                    <div class="flex items-center">
                        <div class="w-12 h-12 rounded-full bg-green-100 flex items-center justify-center mr-3">
                            <i class="fas fa-check-circle text-green-600 text-xl"></i>
                        </div>
                        <div>
                            <p class="text-gray-600">Completed Orders</p>
                            <h3 class="text-2xl font-bold">142</h3>
                            <p class="text-green-500">97% success rate</p>
                        </div>
                    </div>
                </div>
                <div class="bg-white p-4 rounded-lg shadow-md">
                    <div class="flex items-center">
                        <div class="w-12 h-12 rounded-full bg-blue-100 flex items-center justify-center mr-3">
                            <i class="fas fa-dollar-sign text-blue-600 text-xl"></i>
                        </div>
                        <div>
                            <p class="text-gray-600">Revenue</p>
                            <h3 class="text-2xl font-bold">$86,450</h3>
                            <p class="text-green-500">+12.5% this quarter</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Charts and Notifications -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-4">
                <!-- Order Status Chart -->
                <div class="bg-white p-4 rounded-lg shadow-md col-span-2">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg font-semibold">Order Status Overview</h3>
                        <select class="border border-gray-300 rounded px-2 py-1 text-sm">
                            <option>Last 30 Days</option>
                            <option>Last 90 Days</option>
                            <option>Last Year</option>
                        </select>
                    </div>
                    <canvas id="orderStatusChart" class="w-full h-64"></canvas>
                </div>

                <!-- Performance Metrics -->
                <div class="bg-white p-4 rounded-lg shadow-md">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg font-semibold">Performance Metrics</h3>
                        <a href="#" class="text-indigo-500 text-sm">DETAILS</a>
                    </div>
                    <div class="space-y-4">
                        <div>
                            <div class="flex justify-between mb-1">
                                <span>On-Time Delivery</span>
                                <span class="font-semibold">94%</span>
                            </div>
                            <div class="w-full bg-gray-200 rounded-full h-2">
                                <div class="bg-green-500 h-2 rounded-full" style="width: 94%"></div>
                            </div>
                        </div>
                        <div>
                            <div class="flex justify-between mb-1">
                                <span>Order Accuracy</span>
                                <span class="font-semibold">98%</span>
                            </div>
                            <div class="w-full bg-gray-200 rounded-full h-2">
                                <div class="bg-blue-500 h-2 rounded-full" style="width: 98%"></div>
                            </div>
                        </div>
                        <div>
                            <div class="flex justify-between mb-1">
                                <span>Quality Rating</span>
                                <span class="font-semibold">96%</span>
                            </div>
                            <div class="w-full bg-gray-200 rounded-full h-2">
                                <div class="bg-purple-500 h-2 rounded-full" style="width: 96%"></div>
                            </div>
                        </div>
                        <div>
                            <div class="flex justify-between mb-1">
                                <span>Response Time</span>
                                <span class="font-semibold">4.2 hrs</span>
                            </div>
                            <div class="w-full bg-gray-200 rounded-full h-2">
                                <div class="bg-amber-500 h-2 rounded-full" style="width: 84%"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Recent Orders Table -->
            <div class="bg-white p-4 rounded-lg shadow-md mt-6">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-lg font-semibold">Recent Orders</h3>
                    <button class="bg-indigo-600 text-white px-4 py-2 rounded-lg text-sm hover:bg-indigo-700">
                        <i class="fas fa-plus mr-2"></i>New Order
                    </button>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-left">
                        <thead>
                            <tr class="text-gray-600 border-b">
                                <th class="p-2">Order ID</th>
                                <th class="p-2">Product</th>
                                <th class="p-2">Quantity</th>
                                <th class="p-2">Order Date</th>
                                <th class="p-2">Due Date</th>
                                <th class="p-2">Status</th>
                                <th class="p-2">Priority</th>
                                <th class="p-2">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr class="border-t hover:bg-gray-50">
                                <td class="p-2">#ORD-7842</td>
                                <td class="p-2">Electronics Components</td>
                                <td class="p-2">1,200 units</td>
                                <td class="p-2">Jun 5, 2023</td>
                                <td class="p-2">Jun 15, 2023</td>
                                <td class="p-2"><span class="status-badge badge-pending">Pending</span></td>
                                <td class="p-2 priority-high">High</td>
                                <td class="p-2">
                                    <button class="text-indigo-600 hover:text-indigo-800 mr-2">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                    <button class="text-green-600 hover:text-green-800">
                                        <i class="fas fa-shipping-fast"></i>
                                    </button>
                                </td>
                            </tr>
                            <tr class="border-t hover:bg-gray-50">
                                <td class="p-2">#ORD-7839</td>
                                <td class="p-2">Industrial Valves</td>
                                <td class="p-2">350 units</td>
                                <td class="p-2">Jun 3, 2023</td>
                                <td class="p-2">Jun 10, 2023</td>
                                <td class="p-2"><span class="status-badge badge-shipped">Shipped</span></td>
                                <td class="p-2 priority-medium">Medium</td>
                                <td class="p-2">
                                    <button class="text-indigo-600 hover:text-indigo-800 mr-2">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                    <button class="text-green-600 hover:text-green-800">
                                        <i class="fas fa-shipping-fast"></i>
                                    </button>
                                </td>
                            </tr>
                            <tr class="border-t hover:bg-gray-50">
                                <td class="p-2">#ORD-7835</td>
                                <td class="p-2">Packaging Materials</td>
                                <td class="p-2">5,000 units</td>
                                <td class="p-2">May 30, 2023</td>
                                <td class="p-2">Jun 8, 2023</td>
                                <td class="p-2"><span class="status-badge badge-delivered">Delivered</span></td>
                                <td class="p-2 priority-low">Low</td>
                                <td class="p-2">
                                    <button class="text-indigo-600 hover:text-indigo-800 mr-2">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                    <button class="text-green-600 hover:text-green-800">
                                        <i class="fas fa-file-invoice"></i>
                                    </button>
                                </td>
                            </tr>
                            <tr class="border-t hover:bg-gray-50">
                                <td class="p-2">#ORD-7828</td>
                                <td class="p-2">Raw Steel Materials</td>
                                <td class="p-2">15 tons</td>
                                <td class="p-2">May 28, 2023</td>
                                <td class="p-2">Jun 5, 2023</td>
                                <td class="p-2"><span class="status-badge badge-delivered">Delivered</span></td>
                                <td class="p-2 priority-high">High</td>
                                <td class="p-2">
                                    <button class="text-indigo-600 hover:text-indigo-800 mr-2">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                    <button class="text-green-600 hover:text-green-800">
                                        <i class="fas fa-file-invoice"></i>
                                    </button>
                                </td>
                            </tr>
                            <tr class="border-t hover:bg-gray-50">
                                <td class="p-2">#ORD-7821</td>
                                <td class="p-2">Plastic Resins</td>
                                <td class="p-2">8 tons</td>
                                <td class="p-2">May 25, 2023</td>
                                <td class="p-2">Jun 1, 2023</td>
                                <td class="p-2"><span class="status-badge badge-canceled">Canceled</span></td>
                                <td class="p-2 priority-medium">Medium</td>
                                <td class="p-2">
                                    <button class="text-indigo-600 hover:text-indigo-800 mr-2">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                    <button class="text-red-600 hover:text-red-800">
                                        <i class="fas fa-times"></i>
                                    </button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="mt-4 flex justify-between items-center">
                    <div class="text-sm text-gray-600">Showing 5 of 24 active orders</div>
                    <button class="text-indigo-600 hover:text-indigo-800 text-sm font-medium">
                        View All Orders <i class="fas fa-arrow-right ml-1"></i>
                    </button>
                </div>
            </div>
@endsection
@section('js')
const orderStatusCtx = document.getElementById('orderStatusChart').getContext('2d');
        new Chart(orderStatusCtx, {
            type: 'bar',
            data: {
                labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'],
                datasets: [
                    {
                        label: 'Pending',
                        data: [12, 15, 8, 10, 9, 8],
                        backgroundColor: 'rgba(245, 158, 11, 0.7)',
                        borderColor: 'rgba(245, 158, 11, 1)',
                        borderWidth: 1
                    },
                    {
                        label: 'Shipped',
                        data: [18, 20, 22, 25, 24, 20],
                        backgroundColor: 'rgba(16, 185, 129, 0.7)',
                        borderColor: 'rgba(16, 185, 129, 1)',
                        borderWidth: 1
                    },
                    {
                        label: 'Delivered',
                        data: [22, 25, 30, 28, 32, 35],
                        backgroundColor: 'rgba(59, 130, 246, 0.7)',
                        borderColor: 'rgba(59, 130, 246, 1)',
                        borderWidth: 1
                    }
                ]
            },
            options: {
                responsive: true,
                scales: {
                    y: {
                        beginAtZero: true,
                        title: {
                            display: true,
                            text: 'Number of Orders'
                        }
                    },
                    x: {
                        title: {
                            display: true,
                            text: 'Month'
                        }
                    }
                }
            }
        });

        // Dark Mode Toggle
        const darkModeToggle = document.getElementById('darkModeToggle');
        darkModeToggle.addEventListener('change', () => {
            document.body.classList.toggle('dark', darkModeToggle.checked);
            document.body.classList.toggle('bg-gray-100', !darkModeToggle.checked);
            document.body.classList.toggle('bg-gray-900', darkModeToggle.checked);
            document.body.classList.toggle('text-gray-800', !darkModeToggle.checked);
            document.body.classList.toggle('text-white', darkModeToggle.checked);
        });
        
        

@endsection


        
    