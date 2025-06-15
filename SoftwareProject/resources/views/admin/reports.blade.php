@extends('.layouts.admin')
@section('admin_reports')
 flex items-center p-2 bg-gray-900 text-white rounded-lg
@endsection
@section('content')
<!-- Main Content -->
            <div class="mb-6">
                <h3 class="text-2xl font-semibold text-gray-800">Report Management</h3>
                <p class="text-lg text-gray-500">Generate and view supply chain reports</p>
            </div>

            <!-- Report Generation Buttons -->
            <div class="mb-6 flex flex-wrap gap-4">
                <button id="inventoryReportBtn" class="inline-flex items-center px-4 py-2 bg-blue-200 dark:bg-blue-700 text-blue-800 dark:text-blue-100 rounded-full hover:bg-blue-300 dark:hover:bg-blue-800 transition-colors duration-200">
                    <i class="fas fa-box mr-2"></i>
                    Inventory Summary
                </button>
                <button id="orderReportBtn" class="inline-flex items-center px-4 py-2 bg-green-200 dark:bg-green-700 text-green-800 dark:text-green-100 rounded-full hover:bg-green-300 dark:hover:bg-green-800 transition-colors duration-200">
                    <i class="fas fa-shopping-cart mr-2"></i>
                    Order Status
                </button>
                <button id="supplierReportBtn" class="inline-flex items-center px-4 py-2 bg-purple-200 dark:bg-purple-700 text-purple-800 dark:text-purple-100 rounded-full hover:bg-purple-300 dark:hover:bg-purple-800 transition-colors duration-200">
                    <i class="fas fa-truck mr-2"></i>
                    Supplier Performance
                </button>
                <button id="productionReportBtn" class="inline-flex items-center px-4 py-2 bg-yellow-200 dark:bg-yellow-700 text-yellow-800 dark:text-yellow-100 rounded-full hover:bg-yellow-300 dark:hover:bg-yellow-800 transition-colors duration-200">
                    <i class="fas fa-industry mr-2"></i>
                    Production Efficiency
                </button>
                <button id="customReportBtn" class="inline-flex items-center px-4 py-2 bg-gray-200 dark:bg-gray-600 text-gray-800 dark:text-gray-100 rounded-full hover:bg-gray-300 dark:hover:bg-gray-500 transition-colors duration-200">
                    <i class="fas fa-cog mr-2"></i>
                    Custom Report
                </button>
            </div>

            <!-- Report Visualization Chart -->
            <div class="bg-white p-4 rounded-lg shadow-md mb-6">
                <h3 class="text-lg font-semibold mb-4 text-gray-800 ">Inventory Levels by Category</h3>
                <p class="text-gray-600 mb-4">Stock levels for clothing categories</p>
                <canvas id="reportChart" class="w-full h-64"></canvas>
            </div>

            <!-- Report Table -->
            <div class="bg-white p-6 rounded-lg shadow-md">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-lg font-semibold text-gray-800 ">Inventory Summary Report</h3>
                    <button id="exportReportBtn" class="inline-flex items-center px-4 py-2 bg-blue-500 dark:bg-blue-600 text-white rounded-lg hover:bg-blue-600 dark:hover:bg-blue-700">
                        <i class="fas fa-download mr-2"></i>
                        Export as PDF
                    </button>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-left">
                        <thead>
                            <tr class="text-gray-600 dark:text-gray-400">
                                <th class="p-3">Category</th>
                                <th class="p-3">Item Name</th>
                                <th class="p-3">Stock Quantity</th>
                                <th class="p-3">Reorder Level</th>
                                <th class="p-3">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr class="border-t border-gray-200 dark:border-gray-700">
                                <td class="p-3">T-Shirts</td>
                                <td class="p-3">Cotton Basic Tee</td>
                                <td class="p-3">500</td>
                                <td class="p-3">200</td>
                                <td class="p-3"><span class="bg-green-100 text-green-600 px-2 py-1 rounded-full text-sm">In Stock</span></td>
                            </tr>
                            <tr class="border-t border-gray-200 dark:border-gray-700">
                                <td class="p-3">Jeans</td>
                                <td class="p-3">Slim Fit Denim</td>
                                <td class="p-3">150</td>
                                <td class="p-3">100</td>
                                <td class="p-3"><span class="bg-yellow-100 text-yellow-600 px-2 py-1 rounded-full text-sm">Low Stock</span></td>
                            </tr>
                            <tr class="border-t border-gray-200 dark:border-gray-700">
                                <td class="p-3">Dresses</td>
                                <td class="p-3">Summer Maxi Dress</td>
                                <td class="p-3">300</td>
                                <td class="p-3">150</td>
                                <td class="p-3"><span class="bg-green-100 text-green-600 px-2 py-1 rounded-full text-sm">In Stock</span></td>
                            </tr>
                            <tr class="border-t border-gray-200 dark:border-gray-700">
                                <td class="p-3">Jackets</td>
                                <td class="p-3">Leather Jacket</td>
                                <td class="p-3">50</td>
                                <td class="p-3">100</td>
                                <td class="p-3"><span class="bg-red-100 text-red-600 px-2 py-1 rounded-full text-sm">Out of Stock</span></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Custom Report Modal -->
            <div id="customReportModal" class="fixed top-0 left-64 right-0 bottom-0 bg-gray-600 bg-opacity-50 flex items-center justify-center z-50 hidden modal">
                <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-lg w-full max-w-md mx-4 sm:mx-auto modal-content">
                    <h3 class="text-lg font-semibold mb-4 text-gray-800 dark:text-gray-200">Generate Custom Report</h3>
                    <div class="space-y-4">
                        <div>
                            <label class="block text-gray-600 dark:text-gray-300 mb-1">Report Type</label>
                            <select class="w-full p-2 border rounded-lg dark:bg-gray-700 dark:border-gray-600 dark:text-gray-200">
                                <option>Inventory Summary</option>
                                <option>Order Status</option>
                                <option>Supplier Performance</option>
                                <option>Production Efficiency</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-gray-600 dark:text-gray-300 mb-1">Start Date</label>
                            <input type="date" class="w-full p-2 border rounded-lg dark:bg-gray-700 dark:border-gray-600 dark:text-gray-200">
                        </div>
                        <div>
                            <label class="block text-gray-600 dark:text-gray-300 mb-1">End Date</label>
                            <input type="date" class="w-full p-2 border rounded-lg dark:bg-gray-700 dark:border-gray-600 dark:text-gray-200">
                        </div>
                        <div>
                            <label class="block text-gray-600 dark:text-gray-300 mb-1">Department</label>
                            <select class="w-full p-2 border rounded-lg dark:bg-gray-700 dark:border-gray-600 dark:text-gray-200">
                                <option>All</option>
                                <option>Warehouse</option>
                                <option>Procurement</option>
                                <option>Logistics</option>
                                <option>Production</option>
                            </select>
                        </div>
                    </div>
                    <div class="flex justify-end mt-6 space-x-2">
                        <button id="cancelCustomReport" class="px-4 py-2 bg-gray-200 dark:bg-gray-600 rounded-lg hover:bg-gray-300 dark:hover:bg-gray-500 dark:text-gray-200">Cancel</button>
                        <button id="generateReport" class="px-4 py-2 bg-blue-500 dark:bg-blue-600 text-white rounded-lg hover:bg-blue-600 dark:hover:bg-blue-700">Generate</button>
                    </div>
                </div>
            </div>
@endsection
@section('js')
const reportChartCtx = document.getElementById('reportChart').getContext('2d');
        new Chart(reportChartCtx, {
            type: 'bar',
            data: {
                labels: ['T-Shirts', 'Jeans', 'Dresses', 'Jackets'],
                datasets: [
                    {
                        label: 'Stock Quantity',
                        data: [500, 150, 300, 50],
                        backgroundColor: 'rgba(54, 162, 235, 0.6)',
                        borderColor: 'rgba(54, 162, 235, 1)',
                        borderWidth: 1
                    },
                    {
                        label: 'Reorder Level',
                        data: [200, 100, 150, 100],
                        backgroundColor: 'rgba(255, 99, 132, 0.6)',
                        borderColor: 'rgba(255, 99, 132, 1)',
                        borderWidth: 1
                    }
                ]
            },
            options: {
                responsive: true,
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: function(value) {
                                return value + ' units';
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
        const customReportButton = document.getElementById('customReportBtn');
        const customReportModal = document.getElementById('customReportModal');
        const cancelCustomReport = document.getElementById('cancelCustomReport');
        const generateReport = document.getElementById('generateReport');

        customReportButton.addEventListener('click', () => {
            customReportModal.classList.remove('hidden'); 
            document.body.classList.add('modal-open');
        });

        cancelCustomReport.addEventListener('click', () => {
            customReportModal.classList.add('hidden');
            document.body.classList.remove('modal-open');
        });

        generateReport.addEventListener('click', () => {
            // Placeholder for generating report (requires backend integration)
            alert('Report generated (backend integration needed)');
            customReportModal.classList.add('hidden');
            document.body.classList.remove('modal-open');
        });

        // Close modal when clicking outside
        customReportModal.addEventListener('click', (e) => {
            if (e.target === customReportModal) {
                customReportModal.classList.add('hidden');
                document.body.classList.remove('modal-open');
            }
        });

        // Report Generation Buttons (placeholders)
        const reportButtons = [
            document.getElementById('inventoryReportBtn'),
            document.getElementById('orderReportBtn'),
            document.getElementById('supplierReportBtn'),
            document.getElementById('productionReportBtn')
        ];
        reportButtons.forEach(btn => {
            btn.addEventListener('click', () => {
                alert(`Generating ${btn.textContent.trim()} report (backend integration needed)`);
            });
        });

        // Export Report Button
        const exportReportButton = document.getElementById('exportReportBtn');
        exportReportButton.addEventListener('click', () => {
            alert('Exporting report as PDF (backend integration needed)');
        });
@endsection


        
    