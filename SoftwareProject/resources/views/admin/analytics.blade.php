@extends('.layouts.admin')
@section('admin_analytics')
flex items-center p-2 bg-gray-900 text-white rounded-lg
@endsection
@section('content')
<div class="mb-5">
    <h2 class="text-2xl font-semibold text-gray-800 ">Analytics</h2>
</div>
<!-- Analytics Sections -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-4">
                <!-- Inventory Turnover Chart -->
                <div class="bg-white p-4 rounded-lg shadow-md col-span-2">
                    <h3 class="text-lg font-semibold mb-4">Inventory Turnover</h3>
                    <p class="text-gray-600 mb-4">Monthly inventory turnover rates</p>
                    <canvas id="inventoryTurnoverChart" class="w-full h-64"></canvas>
                </div>

                <!-- Summary Card -->
                <div class="bg-white p-4 rounded-lg shadow-md">
                    <div class="flex items-center">
                        <i class="fas fa-check-circle text-green-500 mr-3"></i>
                        <div>
                            <p class="text-gray-600">Order Fulfillment Rate</p>
                            <h3 class="text-2xl font-bold">92%</h3>
                            <p class="text-green-500">+3.5% from last month</p>
                        </div>
                    </div>
                </div>

                <!-- Supplier Performance Chart -->
                <div class="bg-white p-4 rounded-lg shadow-md col-span-2">
                    <h3 class="text-lg font-semibold mb-4">Supplier Performance</h3>
                    <p class="text-gray-600 mb-4">On-time delivery by supplier</p>
                    <canvas id="supplierPerformanceChart" class="w-full h-64"></canvas>
                </div>

                <!-- Order Status -->
                <div class="bg-white p-4 rounded-lg shadow-md">
                    <h3 class="text-lg font-semibold mb-4">Order Status</h3>
                    <ul class="space-y-3">
                        <li class="flex items-center justify-between">
                            <span>Order #1001</span>
                            <span class="bg-blue-100 text-blue-600 px-2 py-1 rounded-full">In Transit</span>
                        </li>
                        <li class="flex items-center justify-between">
                            <span>Order #1002</span>
                            <span class="bg-green-100 text-green-600 px-2 py-1 rounded-full">Delivered</span>
                        </li>
                        <li class="flex items-center justify-between">
                            <span>Order #1003</span>
                            <span class="bg-yellow-100 text-yellow-600 px-2 py-1 rounded-full">Pending</span>
                        </li>
                    </ul>
                </div>

                <!-- Production Cycle Time Chart -->
                <div class="bg-white p-4 rounded-lg shadow-md col-span-2">
                    <h3 class="text-lg font-semibold mb-4">Production Cycle Time</h3>
                    <p class="text-gray-600 mb-4">Average production time by product line</p>
                    <canvas id="productionCycleChart" class="w-full h-64"></canvas>
                </div>

                <!-- Inventory Distribution by Category -->
                <div class="bg-white p-4 rounded-lg shadow-md">
                    <h3 class="text-lg font-semibold mb-4">Inventory Distribution</h3>
                    <p class="text-gray-600 mb-4">Stock by clothing category</p>
                    <canvas id="inventoryDistributionChart" class="w-full h-64"></canvas>
                </div>

                <!-- Supplier Lead Time Chart -->
                <div class="bg-white p-4 rounded-lg shadow-md col-span-2">
                    <h3 class="text-lg font-semibold mb-4">Supplier Lead Time</h3>
                    <p class="text-gray-600 mb-4">Average lead time by supplier</p>
                    <canvas id="supplierLeadTimeChart" class="w-full h-64"></canvas>
                </div>

                <!-- Order Fulfillment Over Time -->
                <div class="bg-white p-4 rounded-lg shadow-md col-span-2">
                    <h3 class="text-lg font-semibold mb-4">Order Fulfillment Over Time</h3>
                    <p class="text-gray-600 mb-4">Monthly fulfillment rates</p>
                    <canvas id="fulfillmentChart" class="w-full h-64"></canvas>
                </div>

                <!-- Returns Rate Chart -->
                <div class="bg-white p-4 rounded-lg shadow-md">
                    <h3 class="text-lg font-semibold mb-4">Returns Rate</h3>
                    <p class="text-gray-600 mb-4">Percentage of returns by category</p>
                    <canvas id="returnsRateChart" class="w-full h-64"></canvas>
                </div>
            </div>
@endsection

@section('js')
        // Inventory Turnover Chart
        const inventoryTurnoverCtx = document.getElementById('inventoryTurnoverChart').getContext('2d');
        new Chart(inventoryTurnoverCtx, {
            type: 'line',
            data: {
                labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'],
                datasets: [
                    {
                        label: 'Inventory Turnover Rate',
                        data: [4.5, 4.8, 5.0, 4.7, 5.2, 5.5],
                        fill: true,
                        backgroundColor: 'rgba(54, 162, 235, 0.1)',
                        borderColor: 'rgba(54, 162, 235, 1)',
                        tension: 0.4
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
                                return value + 'x';
                            }
                        }
                    }
                }
            }
        });

        // Supplier Performance Chart
        const supplierPerformanceCtx = document.getElementById('supplierPerformanceChart').getContext('2d');
        new Chart(supplierPerformanceCtx, {
            type: 'bar',
            data: {
                labels: ['Supplier A', 'Supplier B', 'Supplier C', 'Supplier D', 'Supplier E'],
                datasets: [
                    {
                        label: 'On-Time Delivery (%)',
                        data: [95, 88, 92, 85, 90],
                        backgroundColor: [
                            'rgba(54, 162, 235, 0.6)',
                            'rgba(255, 99, 132, 0.6)',
                            'rgba(75, 192, 192, 0.6)',
                            'rgba(153, 102, 255, 0.6)',
                            'rgba(255, 159, 64, 0.6)'
                        ],
                        borderColor: [
                            'rgba(54, 162, 235, 1)',
                            'rgba(255, 99, 132, 1)',
                            'rgba(75, 192, 192, 1)',
                            'rgba(153, 102, 255, 1)',
                            'rgba(255, 159, 64, 1)'
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
                }
            }
        });

        // Production Cycle Time Chart
        const productionCycleCtx = document.getElementById('productionCycleChart').getContext('2d');
        new Chart(productionCycleCtx, {
            type: 'line',
            data: {
                labels: ['T-Shirts', 'Jeans', 'Dresses', 'Jackets', 'Accessories'],
                datasets: [
                    {
                        label: 'Cycle Time (Days)',
                        data: [7, 10, 12, 15, 5],
                        fill: true,
                        backgroundColor: 'rgba(75, 192, 192, 0.1)',
                        borderColor: 'rgba(75, 192, 192, 1)',
                        tension: 0.4
                    }
                ]
            },
            options: {
                responsive: true,
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });

        // Inventory Distribution by Category Chart
        const inventoryDistributionCtx = document.getElementById('inventoryDistributionChart').getContext('2d');
        new Chart(inventoryDistributionCtx, {
            type: 'pie',
            data: {
                labels: ['T-Shirts', 'Jeans', 'Dresses', 'Jackets', 'Accessories'],
                datasets: [
                    {
                        label: 'Inventory Units',
                        data: [1200, 800, 600, 400, 200],
                        backgroundColor: [
                            'rgba(54, 162, 235, 0.6)',
                            'rgba(255, 99, 132, 0.6)',
                            'rgba(75, 192, 192, 0.6)',
                            'rgba(153, 102, 255, 0.6)',
                            'rgba(255, 159, 64, 0.6)'
                        ],
                        borderColor: [
                            'rgba(54, 162, 235, 1)',
                            'rgba(255, 99, 132, 1)',
                            'rgba(75, 192, 192, 1)',
                            'rgba(153, 102, 255, 1)',
                            'rgba(255, 159, 64, 1)'
                        ],
                        borderWidth: 1
                    }
                ]
            },
            options: {
                responsive: true
            }
        });

        // Supplier Lead Time Chart
        const supplierLeadTimeCtx = document.getElementById('supplierLeadTimeChart').getContext('2d');
        new Chart(supplierLeadTimeCtx, {
            type: 'bar',
            data: {
                labels: ['Supplier A', 'Supplier B', 'Supplier C', 'Supplier D'],
                datasets: [
                    {
                        label: 'Lead Time (Days)',
                        data: [10, 14, 12, 16],
                        backgroundColor: 'rgba(153, 102, 255, 0.6)',
                        borderColor: 'rgba(153, 102, 255, 1)',
                        borderWidth: 1
                    }
                ]
            },
            options: {
                indexAxis: 'y',
                responsive: true,
                scales: {
                    x: {
                        beginAtZero: true
                    }
                }
            }
        });

        // Order Fulfillment Over Time Chart
        const fulfillmentCtx = document.getElementById('fulfillmentChart').getContext('2d');
        new Chart(fulfillmentCtx, {
            type: 'bar',
            data: {
                labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'],
                datasets: [
                    {
                        label: 'Fulfillment Rate (%)',
                        data: [88, 90, 92, 89, 91, 94],
                        backgroundColor: 'rgba(54, 162, 235, 0.6)',
                        borderColor: 'rgba(54, 162, 235, 1)',
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
                }
            }
        });

        // Returns Rate Chart
        const returnsRateCtx = document.getElementById('returnsRateChart').getContext('2d');
        new Chart(returnsRateCtx, {
            type: 'doughnut',
            data: {
                labels: ['T-Shirts', 'Jeans', 'Dresses', 'Jackets', 'Accessories'],
                datasets: [
                    {
                        label: 'Returns Rate (%)',
                        data: [5, 3, 7, 4, 2],
                        backgroundColor: [
                            'rgba(255, 99, 132, 0.6)',
                            'rgba(54, 162, 235, 0.6)',
                            'rgba(75, 192, 192, 0.6)',
                            'rgba(153, 102, 255, 0.6)',
                            'rgba(255, 159, 64, 0.6)'
                        ],
                        borderColor: [
                            'rgba(255, 99, 132, 1)',
                            'rgba(54, 162, 235, 1)',
                            'rgba(75, 192, 192, 1)',
                            'rgba(153, 102, 255, 1)',
                            'rgba(255, 159, 64, 1)'
                        ],
                        borderWidth: 1
                    }
                ]
            },
            options: {
                responsive: true
            }
        });

        // Dark Mode Toggle
        const darkModeToggle = document.getElementById('darkModeToggle');
        darkModeToggle.addEventListener('change', () => {
            document.body.classList.toggle('dark', darkModeToggle.checked);
        });

@endsection      
    