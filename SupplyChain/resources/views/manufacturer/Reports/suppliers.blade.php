@extends('manufacturer.layouts.dashboard')
@section('content')
<div class="container mx-auto p-6">
    <x-notification-bell />
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-bold text-gray-800 dark:text-white">Supplier Performance Report</h2>
        <a href="#" class="px-4 py-2 bg-indigo-600 text-white rounded hover:bg-indigo-700 opacity-50 cursor-not-allowed">
            <i class="fas fa-file-csv mr-2"></i>Export CSV (Coming Soon)
        </a>
    </div>

    <!-- Filters -->
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6 mb-6">
        <h3 class="text-lg font-semibold mb-4 text-gray-800 dark:text-white">Filters</h3>
        <form method="GET" class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Start Date</label>
                <input type="date" name="start_date" value="{{ $startDate }}" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">End Date</label>
                <input type="date" name="end_date" value="{{ $endDate }}" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500">
            </div>
            <div class="flex items-end">
                <button type="submit" class="w-full px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700">
                    <i class="fas fa-filter mr-2"></i>Apply Filters
                </button>
            </div>
        </form>
    </div>

    <!-- Summary Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6">
            <div class="flex items-center">
                <div class="p-3 bg-blue-100 dark:bg-blue-900 rounded-full">
                    <i class="fas fa-truck text-blue-600 dark:text-blue-400 text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Total Suppliers</p>
                    <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $suppliers->count() }}</p>
                </div>
            </div>
        </div>
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6">
            <div class="flex items-center">
                <div class="p-3 bg-green-100 dark:bg-green-900 rounded-full">
                    <i class="fas fa-box text-green-600 dark:text-green-400 text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Total Supplied</p>
                    <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $suppliers->sum(function($s) { return $s->suppliedItems->sum('delivered_quantity'); }) }}</p>
                </div>
            </div>
        </div>
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6">
            <div class="flex items-center">
                <div class="p-3 bg-yellow-100 dark:bg-yellow-900 rounded-full">
                    <i class="fas fa-dollar-sign text-yellow-600 dark:text-yellow-400 text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Total Value</p>
                    <p class="text-2xl font-bold text-gray-900 dark:text-white">${{ number_format($suppliers->sum(function($s) { return $s->suppliedItems->sum(DB::raw('delivered_quantity * price')); }), 2) }}</p>
                </div>
            </div>
        </div>
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6">
            <div class="flex items-center">
                <div class="p-3 bg-purple-100 dark:bg-purple-900 rounded-full">
                    <i class="fas fa-chart-line text-purple-600 dark:text-purple-400 text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Active Suppliers</p>
                    <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $suppliers->filter(function($s) { return $s->suppliedItems->count() > 0; })->count() }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6">
            <h3 class="text-lg font-semibold mb-4 text-gray-800 dark:text-white">Top Suppliers by Quantity</h3>
            <canvas id="supplierQuantityChart" width="400" height="200"></canvas>
        </div>
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6">
            <h3 class="text-lg font-semibold mb-4 text-gray-800 dark:text-white">Top Suppliers by Value</h3>
            <canvas id="supplierValueChart" width="400" height="200"></canvas>
        </div>
    </div>

    <!-- Suppliers Table -->
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
            <h3 class="text-lg font-semibold text-gray-800 dark:text-white">Supplier Details</h3>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                <thead class="bg-gray-50 dark:bg-gray-700">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Supplier Name</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Email</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Total Supplied</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Total Value</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Last Supplied</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Performance</th>
                    </tr>
                </thead>
                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                    @foreach($suppliers as $supplier)
                    @php
                        $totalSupplied = $supplier->suppliedItems->sum('delivered_quantity');
                        $totalValue = $supplier->suppliedItems->sum(DB::raw('delivered_quantity * price'));
                        $lastSupplied = $supplier->suppliedItems->sortByDesc('delivery_date')->first();
                    @endphp
                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-white">{{ $supplier->user->full_name ?? 'N/A' }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300">{{ $supplier->user->email ?? 'N/A' }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">{{ $totalSupplied }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">${{ number_format($totalValue, 2) }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300">
                            {{ $lastSupplied ? $lastSupplied->delivery_date->format('M d, Y') : 'Never' }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                @if($totalSupplied > 100) bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200
                                @elseif($totalSupplied > 50) bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200
                                @else bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200 @endif">
                                @if($totalSupplied > 100) Excellent
                                @elseif($totalSupplied > 50) Good
                                @else Poor
                                @endif
                            </span>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Supplier Quantity Chart
    const supplierQuantityCtx = document.getElementById('supplierQuantityChart').getContext('2d');
    const supplierQuantityChart = new Chart(supplierQuantityCtx, {
        type: 'bar',
        data: {
            labels: [],
            datasets: [{
                label: 'Quantity Supplied',
                data: [],
                backgroundColor: 'rgba(59, 130, 246, 0.8)',
                borderColor: 'rgb(59, 130, 246)',
                borderWidth: 1
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
                    beginAtZero: true
                }
            }
        }
    });

    // Supplier Value Chart
    const supplierValueCtx = document.getElementById('supplierValueChart').getContext('2d');
    const supplierValueChart = new Chart(supplierValueCtx, {
        type: 'bar',
        data: {
            labels: [],
            datasets: [{
                label: 'Total Value ($)',
                data: [],
                backgroundColor: 'rgba(34, 197, 94, 0.8)',
                borderColor: 'rgb(34, 197, 94)',
                borderWidth: 1
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
                    beginAtZero: true
                }
            }
        }
    });

    // Load supplier chart data
    function loadSupplierChartData() {
        const startDate = document.querySelector('input[name="start_date"]').value;
        const endDate = document.querySelector('input[name="end_date"]').value;
        
        fetch(`{{ route('manufacturer.reports.chart.suppliers') }}?start_date=${startDate}&end_date=${endDate}`)
            .then(response => response.json())
            .then(data => {
                supplierQuantityChart.data.labels = data.labels;
                supplierQuantityChart.data.datasets[0].data = data.quantities;
                supplierQuantityChart.update();

                supplierValueChart.data.labels = data.labels;
                supplierValueChart.data.datasets[0].data = data.values;
                supplierValueChart.update();
            })
            .catch(error => console.error('Error loading supplier chart data:', error));
    }

    // Load initial chart data
    loadSupplierChartData();

    // Reload chart data when filters are applied
    document.querySelector('form').addEventListener('submit', function(e) {
        e.preventDefault();
        loadSupplierChartData();
    });
});
</script>
@endsection 