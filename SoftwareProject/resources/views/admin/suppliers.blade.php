@extends('.layouts.admin')
@section('admin_suppliers')
flex items-center p-2 bg-gray-900 text-white rounded-lg
@endsection
@section('content')
 <!-- Main Content -->
            <div class="mb-6">
                <h3 class="text-2xl font-semibold text-gray-800">Supplier Management</h3>
                <p class="text-lg text-gray-500">Manage suppliers for the clothing supply chain</p>
            </div>

            <!-- Add Supplier Button -->
            <div class="mb-4">
                <button id="addSupplierBtn" class="inline-flex items-center px-4 py-2 bg-blue-200 dark:bg-blue-700 text-blue-800 dark:text-blue-100 rounded-full hover:bg-blue-300 dark:hover:bg-blue-800 transition-colors duration-200">
                    <i class="fas fa-plus mr-2"></i>
                    New Supplier
                </button>
            </div>

            <!-- Supplier Performance Chart -->
            <div class="bg-white p-4 rounded-lg shadow-md mb-6">
                <h3 class="text-lg font-semibold mb-4 text-gray-800 ">Supplier On-Time Delivery</h3>
                <p class="text-gray-600 mb-4">Percentage of on-time deliveries by supplier</p>
                <canvas id="supplierPerformanceChart" class="w-full h-64"></canvas>
            </div>

            <!-- Suppliers Table -->
            <div class="bg-white p-6 rounded-lg shadow-md">
                <h3 class="text-lg font-semibold mb-4 text-gray-800">Suppliers List</h3>
                <div class="overflow-x-auto">
                    <table class="w-full text-left">
                        <thead>
                            <tr class="text-gray-600 ">
                                <th class="p-3">Supplier Name</th>
                                <th class="p-3">Supplier ID</th>
                                <th class="p-3">Contact</th>
                                <th class="p-3">Performance</th>
                                <th class="p-3">Validation Status</th>
                                <th class="p-3">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr class="border-t border-gray-200 dark:border-gray-700">
                                <td class="p-3">FabricCorp</td>
                                <td class="p-3">SUP001</td>
                                <td class="p-3">contact@fabriccorp.com</td>
                                <td class="p-3"><span class="bg-green-100 text-green-600 px-2 py-1 rounded-full text-sm">95%</span></td>
                                <td class="p-3"><span class="bg-blue-100 text-blue-600 px-2 py-1 rounded-full text-sm">Validated</span></td>
                                <td class="p-3">
                                    <button class="text-blue-500 dark:text-blue-400 hover:text-blue-700 dark:hover:text-blue-300 mr-2"><i class="fas fa-edit"></i></button>
                                    <button class="text-green-500 dark:text-green-400 hover:text-green-700 dark:hover:text-green-300 mr-2"><i class="fas fa-eye"></i></button>
                                    <button class="text-purple-500 dark:text-purple-400 hover:text-purple-700 dark:hover:text-purple-300"><i class="fas fa-check-circle"></i></button>
                                </td>
                            </tr>
                            <tr class="border-t border-gray-200 dark:border-gray-700">
                                <td class="p-3">ThreadWorks</td>
                                <td class="p-3">SUP002</td>
                                <td class="p-3">info@threadworks.com</td>
                                <td class="p-3"><span class="bg-yellow-100 text-yellow-600 px-2 py-1 rounded-full text-sm">88%</span></td>
                                <td class="p-3"><span class="bg-yellow-100 text-yellow-600 px-2 py-1 rounded-full text-sm">Pending</span></td>
                                <td class="p-3">
                                    <button class="text-blue-500 dark:text-blue-400 hover:text-blue-700 dark:hover:text-blue-300 mr-2"><i class="fas fa-edit"></i></button>
                                    <button class="text-green-500 dark:text-green-400 hover:text-green-700 dark:hover:text-green-300 mr-2"><i class="fas fa-eye"></i></button>
                                    <button class="text-purple-500 dark:text-purple-400 hover:text-purple-700 dark:hover:text-purple-300"><i class="fas fa-check-circle"></i></button>
                                </td>
                            </tr>
                            <tr class="border-t border-gray-200 dark:border-gray-700">
                                <td class="p-3">DyeMasters</td>
                                <td class="p-3">SUP003</td>
                                <td class="p-3">sales@dyemasters.com</td>
                                <td class="p-3"><span class="bg-green-100  text-green-600 px-2 py-1 rounded-full text-sm">92%</span></td>
                                <td class="p-3"><span class="bg-blue-100 text-blue-600 px-2 py-1 rounded-full text-sm">Validated</span></td>
                                <td class="p-3">
                                    <button class="text-blue-500 dark:text-blue-400 hover:text-blue-700 dark:hover:text-blue-300 mr-2"><i class="fas fa-edit"></i></button>
                                    <button class="text-green-500 dark:text-green-400 hover:text-green-700 dark:hover:text-green-300 mr-2"><i class="fas fa-eye"></i></button>
                                    <button class="text-purple-500 dark:text-purple-400 hover:text-purple-700 dark:hover:text-purple-300"><i class="fas fa-check-circle"></i></button>
                                </td>
                            </tr>
                            <tr class="border-t border-gray-200 dark:border-gray-700">
                                <td class="p-3">ZipperZone</td>
                                <td class="p-3">SUP004</td>
                                <td class="p-3">support@zipperzone.com</td>
                                <td class="p-3"><span class="bg-red-100 text-red-600 px-2 py-1 rounded-full text-sm">85%</span></td>
                                <td class="p-3"><span class="bg-red-100 text-red-600 px-2 py-1 rounded-full text-sm">Not Validated</span></td>
                                <td class="p-3">
                                    <button class="text-blue-500 dark:text-blue-400 hover:text-blue-700 dark:hover:text-blue-300 mr-2"><i class="fas fa-edit"></i></button>
                                    <button class="text-green-500 dark:text-green-400 hover:text-green-700 dark:hover:text-green-300 mr-2"><i class="fas fa-eye"></i></button>
                                    <button class="text-purple-500 dark:text-purple-400 hover:text-purple-700 dark:hover:text-purple-300"><i class="fas fa-check-circle"></i></button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Add Supplier Modal -->
            <div id="addSupplierModal" class="fixed top-0 left-64 right-0 bottom-0 bg-gray-600 bg-opacity-50 flex items-center justify-center z-50 hidden modal">
                <div class="bg-white p-6 rounded-lg shadow-lg w-full max-w-md mx-4 sm:mx-auto modal-content">
                    <h3 class="text-lg font-semibold mb-4 text-gray-800 ">Add New Supplier</h3>
                    <div class="space-y-4">
                        <div>
                            <label class="block text-gray-600 mb-1">Supplier Name</label>
                            <input type="text" class="w-full p-2 border rounded-lg " placeholder="e.g., FabricCorp">
                        </div>
                        <div>
                            <label class="block text-gray-600 dark:text-gray-300 mb-1">Supplier ID</label>
                            <input type="text" class="w-full p-2 border rounded-lg dark:border-gray-600" placeholder="e.g., SUP005">
                        </div>
                        <div>
                            <label class="block text-gray-600 dark:text-gray-300 mb-1">Contact Email</label>
                            <input type="email" class="w-full p-2 border rounded-lg dark:border-gray-600" placeholder="e.g., contact@supplier.com">
                        </div>
                        <div>
                            <label class="block text-gray-600 dark:text-gray-300 mb-1">Address</label>
                            <input type="text" class="w-full p-2 border rounded-lg dark:border-gray-600" placeholder="e.g., 123 Textile Lane">
                        </div>
                        <div>
                            <label class="block text-gray-600 mb-1">Upload Application (PDF)</label>
                            <input type="file" accept=".pdf" class="w-full p-2 border rounded-lg dark:border-gray-600">
                        </div>
                    </div>
                    <div class="flex justify-end mt-6 space-x-2">
                        <button id="cancelAddSupplier" class="px-4 py-2 bg-gray-200 rounded-lg hover:bg-gray-300 dark:hover:bg-gray-500 ">Cancel</button>
                        <button id="saveSupplier" class="px-4 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600 ">Save</button>
                    </div>
                </div>
            </div>
@endsection

@section('js')
// Supplier Performance Chart
        const supplierPerformanceCtx = document.getElementById('supplierPerformanceChart').getContext('2d');
        new Chart(supplierPerformanceCtx, {
            type: 'bar',
            data: {
                labels: ['FabricCorp', 'ThreadWorks', 'DyeMasters', 'ZipperZone'],
                datasets: [
                    {
                        label: 'On-Time Delivery (%)',
                        data: [95, 88, 92, 85],
                        backgroundColor: [
                            'rgba(75, 192, 192, 0.6)',
                            'rgba(255, 193, 7, 0.6)',
                            'rgba(54, 162, 235, 0.6)',
                            'rgba(255, 99, 132, 0.6)'
                        ],
                        borderColor: [
                            'rgba(75, 192, 192, 1)',
                            'rgba(255, 193, 7, 1)',
                            'rgba(54, 162, 235, 1)',
                            'rgba(255, 99, 132, 1)'
                        ],
                        borderWidth: 1
                    }
                ]
            },
            options: {
                responsive: true,
                scales: {
                    y: {
                        beginAtZero: true,
                        max: 100,
                        ticks: {
                            callback: function(value) {
                                return value + '%';
                            }
                        }
                    }
                },
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
        const addSupplierButton = document.getElementById('addSupplierBtn');
        const addSupplierModal = document.getElementById('addSupplierModal');
        const cancelAddSupplier = document.getElementById('cancelAddSupplier');
        const saveSupplier = document.getElementById('saveSupplier');

        addSupplierButton.addEventListener('click', () => {
            addSupplierModal.classList.remove('hidden');
            document.body.classList.add('modal-open');
        });

        cancelAddSupplier.addEventListener('click', () => {
            addSupplierModal.classList.add('hidden');
            document.body.classList.remove('modal-open');
        });

        saveSupplier.addEventListener('click', () => {
            // Placeholder for saving supplier data (requires backend integration)
            alert('Supplier saved (backend integration needed)');
            addSupplierModal.classList.add('hidden');
            document.body.classList.remove('modal-open');
        });

        // Close modal when clicking outside
        addSupplierModal.addEventListener('click', (e) => {
            if (e.target === addSupplierModal) {
                addSupplierModal.classList.add('hidden');
                document.body.classList.remove('modal-open');
            }
        });
@endsection      
    