@extends('.layouts.suppliers')
@section('internal_css')
<style>
@media (max-width: 768px) {
            .sidebar {
                transform: translateX(-100%);
            }

            .sidebar.open {
                transform: translateX(0);
            }

            .content {
                margin-left: 0 !important;
            }
        }
        
        .supplier-header {
            background: linear-gradient(135deg, #4f46e5, #7c3aed);
        }
        
        .status-badge {
            padding: 2px 8px;
            border-radius: 12px;
            font-size: 12px;
            font-weight: 500;
        }
        
        .badge-pending {
            background-color: #fef3c7;
            color: #d97706;
        }
        
        .badge-shipped {
            background-color: #d1fae5;
            color: #059669;
        }
        
        .badge-delivered {
            background-color: #dbeafe;
            color: #2563eb;
        }
        
        .badge-canceled {
            background-color: #fee2e2;
            color: #dc2626;
        }
        
        .priority-high {
            color: #dc2626;
            font-weight: 600;
        }
        
        .priority-medium {
            color: #d97706;
            font-weight: 600;
        }
        
        .priority-low {
            color: #059669;
            font-weight: 600;
        }
        
        .chart-card {
            background: white;
            border-radius: 0.75rem;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        
        .chart-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 15px rgba(0, 0, 0, 0.1);
        }
        
        .analytics-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 1.5rem;
        }
        
        .metric-card {
            background: linear-gradient(135deg, rgb(250, 131, 131), rgb(255, 180, 180));
            color: white;
            border-radius: 0.75rem;
            padding: 1.5rem;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        
        .filter-bar {
            background: white;
            border-radius: 0.75rem;
            padding: 1rem;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
        }
</style>
@endsection
@section('supplier_analytics')
 flex items-center p-2 bg-gray-900 text-white rounded-lg
@endsection
@section('content')
<!-- Page Header -->
            <div class="mb-6">
                <h2 class="text-2xl font-semibold text-gray-800">Supplier Analytics Overview</h2>
                <p class="text-gray-600">Detailed insights and performance metrics for your operations</p>
            </div>
            
            <!-- Date Filter -->
            <div class="filter-bar mb-6 flex items-center">
                <span class="text-gray-600 mr-3">Filter by:</span>
                <select class="border border-gray-300 rounded px-3 py-2 mr-3">
                    <option>Last 7 Days</option>
                    <option selected>Last 30 Days</option>
                    <option>Last 90 Days</option>
                    <option>Last Year</option>
                </select>
                <button class="bg-indigo-600 text-white px-4 py-2 rounded-lg text-sm hover:bg-indigo-700">
                    <i class="fas fa-filter mr-2"></i>Apply Filters
                </button>
            </div>
            
            <!-- Summary Cards -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
                <div class="metric-card">
                    <div class="flex items-center">
                        <div class="w-12 h-12 rounded-full bg-white bg-opacity-20 flex items-center justify-center mr-3">
                            <i class="fas fa-file-invoice-dollar text-xl"></i>
                        </div>
                        <div>
                            <p class="text-sm">Total Orders</p>
                            <h3 class="text-2xl font-bold">142</h3>
                            <p class="text-sm opacity-80">+12% from last month</p>
                        </div>
                    </div>
                </div>
                <div class="metric-card">
                    <div class="flex items-center">
                        <div class="w-12 h-12 rounded-full bg-white bg-opacity-20 flex items-center justify-center mr-3">
                            <i class="fas fa-dollar-sign text-xl"></i>
                        </div>
                        <div>
                            <p class="text-sm">Revenue</p>
                            <h3 class="text-2xl font-bold">$86,450</h3>
                            <p class="text-sm opacity-80">+8.5% from last month</p>
                        </div>
                    </div>
                </div>
                <div class="metric-card">
                    <div class="flex items-center">
                        <div class="w-12 h-12 rounded-full bg-white bg-opacity-20 flex items-center justify-center mr-3">
                            <i class="fas fa-truck text-xl"></i>
                        </div>
                        <div>
                            <p class="text-sm">On-Time Delivery</p>
                            <h3 class="text-2xl font-bold">94%</h3>
                            <p class="text-sm opacity-80">+2% from last month</p>
                        </div>
                    </div>
                </div>
                <div class="metric-card">
                    <div class="flex items-center">
                        <div class="w-12 h-12 rounded-full bg-white bg-opacity-20 flex items-center justify-center mr-3">
                            <i class="fas fa-check-circle text-xl"></i>
                        </div>
                        <div>
                            <p class="text-sm">Order Accuracy</p>
                            <h3 class="text-2xl font-bold">98.2%</h3>
                            <p class="text-sm opacity-80">Consistent performance</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Charts Grid -->
            <div class="analytics-grid mb-6">
                <!-- Revenue Chart -->
                <div class="chart-card p-4">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg font-semibold">Monthly Revenue</h3>
                        <div class="flex items-center">
                            <span class="w-3 h-3 bg-indigo-500 rounded-full mr-1"></span>
                            <span class="text-sm mr-3">Current Year</span>
                            <span class="w-3 h-3 bg-gray-300 rounded-full mr-1"></span>
                            <span class="text-sm">Previous Year</span>
                        </div>
                    </div>
                    <canvas id="revenueChart" class="w-full h-64"></canvas>
                </div>
                
                <!-- Order Status Chart -->
                <div class="chart-card p-4">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg font-semibold">Order Status Distribution</h3>
                        <select class="border border-gray-300 rounded px-2 py-1 text-sm">
                            <option>Last 30 Days</option>
                            <option selected>Last 90 Days</option>
                            <option>Last Year</option>
                        </select>
                    </div>
                    <canvas id="orderStatusChart" class="w-full h-64"></canvas>
                </div>
                
                <!-- Top Products Chart -->
                <div class="chart-card p-4">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg font-semibold">Top Products by Revenue</h3>
                        <a href="#" class="text-indigo-600 text-sm">View Details</a>
                    </div>
                    <canvas id="topProductsChart" class="w-full h-64"></canvas>
                </div>
                
                <!-- Performance Chart -->
                <div class="chart-card p-4">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg font-semibold">Performance Metrics</h3>
                        <a href="#" class="text-indigo-600 text-sm">Download Report</a>
                    </div>
                    <canvas id="performanceChart" class="w-full h-64"></canvas>
                </div>
            </div>
            
            <!-- Detailed Analytics -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
                <!-- Customer Satisfaction -->
                <div class="chart-card p-4">
                    <h3 class="text-lg font-semibold mb-4">Customer Satisfaction</h3>
                    <div class="flex items-center mb-4">
                        <div class="text-4xl font-bold text-indigo-600 mr-4">4.8</div>
                        <div class="flex">
                            <i class="fas fa-star text-yellow-400"></i>
                            <i class="fas fa-star text-yellow-400"></i>
                            <i class="fas fa-star text-yellow-400"></i>
                            <i class="fas fa-star text-yellow-400"></i>
                            <i class="fas fa-star-half-alt text-yellow-400"></i>
                        </div>
                    </div>
                    <div class="space-y-3">
                        <div>
                            <div class="flex justify-between mb-1">
                                <span>Product Quality</span>
                                <span class="font-semibold">4.9</span>
                            </div>
                            <div class="w-full bg-gray-200 rounded-full h-2">
                                <div class="bg-green-500 h-2 rounded-full" style="width: 98%"></div>
                            </div>
                        </div>
                        <div>
                            <div class="flex justify-between mb-1">
                                <span>Delivery Speed</span>
                                <span class="font-semibold">4.7</span>
                            </div>
                            <div class="w-full bg-gray-200 rounded-full h-2">
                                <div class="bg-blue-500 h-2 rounded-full" style="width: 94%"></div>
                            </div>
                        </div>
                        <div>
                            <div class="flex justify-between mb-1">
                                <span>Communication</span>
                                <span class="font-semibold">4.6</span>
                            </div>
                            <div class="w-full bg-gray-200 rounded-full h-2">
                                <div class="bg-purple-500 h-2 rounded-full" style="width: 92%"></div>
                            </div>
                        </div>
                        <div>
                            <div class="flex justify-between mb-1">
                                <span>Problem Resolution</span>
                                <span class="font-semibold">4.8</span>
                            </div>
                            <div class="w-full bg-gray-200 rounded-full h-2">
                                <div class="bg-indigo-500 h-2 rounded-full" style="width: 96%"></div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Regional Performance -->
                <div class="chart-card p-4">
                    <h3 class="text-lg font-semibold mb-4">Regional Performance</h3>
                    <div class="overflow-x-auto">
                        <table class="w-full text-left">
                            <thead>
                                <tr class="text-gray-600 border-b">
                                    <th class="p-2">Region</th>
                                    <th class="p-2">Orders</th>
                                    <th class="p-2">Revenue</th>
                                    <th class="p-2">Growth</th>
                                    <th class="p-2">Satisfaction</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr class="border-t hover:bg-gray-50">
                                    <td class="p-2 font-medium">North America</td>
                                    <td class="p-2">42</td>
                                    <td class="p-2">$36,420</td>
                                    <td class="p-2"><span class="text-green-600">+15%</span></td>
                                    <td class="p-2">4.9</td>
                                </tr>
                                <tr class="border-t hover:bg-gray-50">
                                    <td class="p-2 font-medium">Europe</td>
                                    <td class="p-2">38</td>
                                    <td class="p-2">$28,150</td>
                                    <td class="p-2"><span class="text-green-600">+8%</span></td>
                                    <td class="p-2">4.7</td>
                                </tr>
                                <tr class="border-t hover:bg-gray-50">
                                    <td class="p-2 font-medium">Asia Pacific</td>
                                    <td class="p-2">35</td>
                                    <td class="p-2">$18,750</td>
                                    <td class="p-2"><span class="text-green-600">+22%</span></td>
                                    <td class="p-2">4.8</td>
                                </tr>
                                <tr class="border-t hover:bg-gray-50">
                                    <td class="p-2 font-medium">South America</td>
                                    <td class="p-2">17</td>
                                    <td class="p-2">$9,450</td>
                                    <td class="p-2"><span class="text-green-600">+5%</span></td>
                                    <td class="p-2">4.6</td>
                                </tr>
                                <tr class="border-t hover:bg-gray-50">
                                    <td class="p-2 font-medium">Africa</td>
                                    <td class="p-2">10</td>
                                    <td class="p-2">$3,680</td>
                                    <td class="p-2"><span class="text-green-600">+18%</span></td>
                                    <td class="p-2">4.5</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
@endsection
@section('js')
const revenueCtx = document.getElementById('revenueChart').getContext('2d');
        new Chart(revenueCtx, {
            type: 'line',
            data: {
                labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
                datasets: [
                    {
                        label: '2023',
                        data: [65000, 59000, 72000, 68000, 78000, 75000, 82000, 79000, 86000, 84000, 90000, 95000],
                        borderColor: '#4f46e5',
                        backgroundColor: 'rgba(79, 70, 229, 0.1)',
                        fill: true,
                        tension: 0.3
                    },
                    {
                        label: '2022',
                        data: [58000, 62000, 65000, 60000, 68000, 70000, 72000, 75000, 78000, 80000, 82000, 85000],
                        borderColor: '#c7d2fe',
                        backgroundColor: 'rgba(199, 210, 254, 0.1)',
                        fill: true,
                        tension: 0.3
                    }
                ]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        display: false
                    }
                },
                scales: {
                    y: {
                        beginAtZero: false,
                        grid: {
                            drawBorder: false
                        },
                        ticks: {
                            callback: function(value) {
                                return '$' + value.toLocaleString();
                            }
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

        // Order Status Chart
        const orderStatusCtx = document.getElementById('orderStatusChart').getContext('2d');
        new Chart(orderStatusCtx, {
            type: 'doughnut',
            data: {
                labels: ['Completed', 'In Progress', 'Pending', 'Cancelled'],
                datasets: [{
                    data: [65, 20, 10, 5],
                    backgroundColor: [
                        '#10b981',
                        '#3b82f6',
                        '#f59e0b',
                        '#ef4444'
                    ],
                    borderWidth: 0
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'right'
                    }
                },
                cutout: '70%'
            }
        });

        // Top Products Chart
        const topProductsCtx = document.getElementById('topProductsChart').getContext('2d');
        new Chart(topProductsCtx, {
            type: 'bar',
            data: {
                labels: ['Electronics', 'Industrial Parts', 'Packaging', 'Raw Materials', 'Automotive'],
                datasets: [{
                    label: 'Revenue ($)',
                    data: [24500, 18900, 15600, 13200, 9800],
                    backgroundColor: '#7c3aed',
                    borderRadius: 6
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        display: false
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: {
                            drawBorder: false
                        },
                        ticks: {
                            callback: function(value) {
                                return '$' + value.toLocaleString();
                            }
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

        // Performance Chart
        const performanceCtx = document.getElementById('performanceChart').getContext('2d');
        new Chart(performanceCtx, {
            type: 'radar',
            data: {
                labels: ['On-Time Delivery', 'Order Accuracy', 'Response Time', 'Quality Rating', 'Cost Efficiency', 'Communication'],
                datasets: [
                    {
                        label: 'Your Performance',
                        data: [94, 98, 90, 96, 88, 92],
                        fill: true,
                        backgroundColor: 'rgba(79, 70, 229, 0.2)',
                        borderColor: '#4f46e5',
                        pointBackgroundColor: '#4f46e5',
                        pointBorderColor: '#fff',
                        pointHoverBackgroundColor: '#fff',
                        pointHoverBorderColor: '#4f46e5'
                    },
                    {
                        label: 'Industry Average',
                        data: [86, 92, 85, 89, 82, 88],
                        fill: true,
                        backgroundColor: 'rgba(199, 210, 254, 0.2)',
                        borderColor: '#c7d2fe',
                        pointBackgroundColor: '#c7d2fe',
                        pointBorderColor: '#fff',
                        pointHoverBackgroundColor: '#fff',
                        pointHoverBorderColor: '#c7d2fe'
                    }
                ]
            },
            options: {
                responsive: true,
                scales: {
                    r: {
                        angleLines: {
                            display: true
                        },
                        suggestedMin: 70,
                        suggestedMax: 100
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


        
    