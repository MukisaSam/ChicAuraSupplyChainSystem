@extends('.layouts.admin')
@section('admin_orders')
flex items-center p-2 bg-gray-900 text-white rounded-lg
@endsection
@section('content')
<!-- Main Content -->
            <div class="mb-6">
                <h3 class="text-2xl font-semibold text-gray-800">Order Management</h3>
                <p class="text-lg text-gray-500">Track and manage orders across the supply chain</p>
            </div>

            <!-- Add Order Button -->
            <div class="mb-4">
                <button id="addOrderBtn" class="inline-flex items-center px-4 py-2 bg-blue-200 dark:bg-blue-700 text-blue-800 dark:text-blue-100 rounded-full hover:bg-blue-300 dark:hover:bg-blue-800 transition-colors duration-200">
                    <i class="fas fa-plus mr-2"></i>
                    New Order
                </button>
            </div>

            <!-- Order Status Chart -->
            <div class="bg-white p-4 rounded-lg shadow-md mb-6">
                <h3 class="text-lg font-semibold mb-4 text-gray-800 ">Order Status Distribution</h3>
                <p class="text-gray-600  mb-4">Current status of all orders</p>
                <canvas id="orderStatusChart" class="w-full h-64"></canvas>
            </div>

            <!-- Orders Table -->
            <div class="bg-white p-6 rounded-lg shadow-md">
                <h3 class="text-lg font-semibold mb-4 text-gray-800 ">Orders List</h3>
                <div class="overflow-x-auto">
                    <table class="w-full text-left">
                        <thead>
                            <tr class="text-gray-600 ">
                                <th class="p-3">Order ID</th>
                                <th class="p-3">Customer/Supplier</th>
                                <th class="p-3">Status</th>
                                <th class="p-3">Date</th>
                                <th class="p-3">Total Amount</th>
                                <th class="p-3">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr class="border-t border-gray-200 ">
                                <td class="p-3">ORD1001</td>
                                <td class="p-3">Supplier A</td>
                                <td class="p-3"><span class="bg-blue-100 text-blue-600 px-2 py-1 rounded-full text-sm">In Transit</span></td>
                                <td class="p-3">2025-06-01</td>
                                <td class="p-3">$5,000</td>
                                <td class="p-3">
                                    <button class="text-blue-500 dark:text-blue-400 hover:text-blue-700 dark:hover:text-blue-300 mr-2"><i class="fas fa-edit"></i></button>
                                    <button class="text-green-500 dark:text-green-400 hover:text-green-700 dark:hover:text-green-300"><i class="fas fa-eye"></i></button>
                                </td>
                            </tr>
                            <tr class="border-t border-gray-200 dark:border-gray-700">
                                <td class="p-3">ORD1002</td>
                                <td class="p-3">Retailer B</td>
                                <td class="p-3"><span class="bg-green-100 text-green-600 px-2 py-1 rounded-full text-sm">Delivered</span></td>
                                <td class="p-3">2025-06-02</td>
                                <td class="p-3">$3,200</td>
                                <td class="p-3">
                                    <button class="text-blue-500 dark:text-blue-400 hover:text-blue-700 dark:hover:text-blue-300 mr-2"><i class="fas fa-edit"></i></button>
                                    <button class="text-green-500 dark:text-green-400 hover:text-green-700 dark:hover:text-green-300"><i class="fas fa-eye"></i></button>
                                </td>
                            </tr>
                            <tr class="border-t border-gray-200 dark:border-gray-700">
                                <td class="p-3">ORD1003</td>
                                <td class="p-3">Wholesaler C</td>
                                <td class="p-3"><span class="bg-yellow-100 dtext-yellow-600 px-2 py-1 rounded-full text-sm">Pending</span></td>
                                <td class="p-3">2025-06-03</td>
                                <td class="p-3">$4,800</td>
                                <td class="p-3">
                                    <button class="text-blue-500 dark:text-blue-400 hover:text-blue-700 dark:hover:text-blue-300 mr-2"><i class="fas fa-edit"></i></button>
                                    <button class="text-green-500 dark:text-green-400 hover:text-green-700 dark:hover:text-green-300"><i class="fas fa-eye"></i></button>
                                </td>
                            </tr>
                            <tr class="border-t border-gray-200 dark:border-gray-700">
                                <td class="p-3">ORD1004</td>
                                <td class="p-3">Supplier D</td>
                                <td class="p-3"><span class="bg-red-100 text-red-600 px-2 py-1 rounded-full text-sm">Cancelled</span></td>
                                <td class="p-3">2025-06-04</td>
                                <td class="p-3">$2,500</td>
                                <td class="p-3">
                                    <button class="text-blue-500 dark:text-blue-400 hover:text-blue-700 dark:hover:text-blue-300 mr-2"><i class="fas fa-edit"></i></button>
                                    <button class="text-green-500 dark:text-green-400 hover:text-green-700 dark:hover:text-green-300"><i class="fas fa-eye"></i></button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Add Order Modal -->
            <div id="addOrderModal" class="fixed top-0 left-64 right-0 bottom-0 bg-gray-600 bg-opacity-50 flex items-center justify-center z-50 hidden modal">
                <div class="bg-white p-6 rounded-lg shadow-lg w-full max-w-md mx-4 sm:mx-auto modal-content">
                    <h3 class="text-lg font-semibold mb-4 text-gray-800 ">Add New Order</h3>
                    <div class="space-y-4">
                        <div>
                            <label class="block text-gray-600 mb-1">Order ID</label>
                            <input type="text" class="w-full p-2 border rounded-lg  dark:border-gray-600" placeholder="e.g., ORD1005">
                        </div>
                        <div>
                            <label class="block text-gray-600 mb-1">Customer/Supplier</label>
                            <select class="w-full p-2 border rounded-lg dark:border-gray-600 ">
                                <option>Supplier A</option>
                                <option>Retailer B</option>
                                <option>Wholesaler C</option>
                                <option>Supplier D</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-gray-600 mb-1">Item</label>
                            <select class="w-full p-2 border rounded-lg dark:border-gray-600">
                                <option>T-Shirts</option>
                                <option>Jeans</option>
                                <option>Dresses</option>
                                <option>Jackets</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-gray-600 mb-1">Quantity</label>
                            <input type="number" class="w-full p-2 border rounded-lg dark:border-gray-600" placeholder="e.g., 100">
                        </div>
                        <div>
                            <label class="block text-gray-600 mb-1">Total Amount</label>
                            <input type="text" class="w-full p-2 border rounded-lg dark:border-gray-600" placeholder="e.g., $5000">
                        </div>
                    </div>
                    <div class="flex justify-end mt-6 space-x-2">
                        <button id="cancelAddOrder" class="px-4 py-2 bg-gray-200 rounded-lg hover:bg-gray-300 dark:hover:bg-gray-500">Cancel</button>
                        <button id="saveOrder" class="px-4 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600 dark:hover:bg-blue-700">Save</button>
                    </div>
                </div>
            </div>
@endsection

@section('js')
// Order Status Chart
        const orderStatusCtx = document.getElementById('orderStatusChart').getContext('2d');
        new Chart(orderStatusCtx, {
            type: 'doughnut',
            data: {
                labels: ['Pending', 'In Transit', 'Delivered', 'Cancelled'],
                datasets: [
                    {
                        label: 'Orders',
                        data: [25, 30, 35, 10],
                        backgroundColor: [
                            'rgba(255, 193, 7, 0.6)',   // Yellow for Pending
                            'rgba(54, 162, 235, 0.6)',  // Blue for In Transit
                            'rgba(75, 192, 192, 0.6)',  // Green for Delivered
                            'rgba(255, 99, 132, 0.6)'   // Red for Cancelled
                        ],
                        borderColor: [
                            'rgba(255, 193, 7, 1)',
                            'rgba(54, 162, 235, 1)',
                            'rgba(75, 192, 192, 1)',
                            'rgba(255, 99, 132, 1)'
                        ],
                        borderWidth: 1
                    }
                ]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'top',
                        labels: {
                            color: '#4B5563' // gray-600
                        }
                    }
                }
            }
        });

        // Dark Mode Toggle
        const darkModeToggle = document.getElementById('darkModeToggle');
        darkModeToggle.addEventListener('change', () => {
            document.body.classList.toggle('dark', darkModeToggle.checked);
        });

        // Modal Toggle
        const addOrderButton = document.getElementById('addOrderBtn');
        const addOrderModal = document.getElementById('addOrderModal');
        const cancelAddOrder = document.getElementById('cancelAddOrder');
        const saveOrder = document.getElementById('saveOrder');

        addOrderButton.addEventListener('click', () => {
            addOrderModal.classList.remove('hidden');
            document.body.classList.add('modal-open');
        });

        cancelAddOrder.addEventListener('click', () => {
            addOrderModal.classList.add('hidden');
            document.body.classList.remove('modal-open');
        });

        saveOrder.addEventListener('click', () => {
            // Placeholder for saving order data (requires backend integration)
            alert('Order saved (backend integration needed)');
            addOrderModal.classList.add('hidden');
            document.body.classList.remove('modal-open');
        });

        // Close modal when clicking outside
        addOrderModal.addEventListener('click', (e) => {
            if (e.target === addOrderModal) {
                addOrderModal.classList.add('hidden');
                document.body.classList.remove('modal-open');
            }
        });
@endsection      
    