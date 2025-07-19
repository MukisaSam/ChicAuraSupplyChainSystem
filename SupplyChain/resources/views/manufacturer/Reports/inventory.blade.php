@extends('manufacturer.layouts.dashboard')
@section('content')
<div class="container mx-auto p-6">
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-bold text-black mb-6 flex items-center gap-3">Inventory Reports</h2>
        <a href="#" class="px-4 py-2 bg-indigo-600 text-white rounded hover:bg-indigo-700 opacity-50 cursor-not-allowed">
            <i class="fas fa-file-csv mr-2"></i>Export CSV (Coming Soon)
        </a>
    </div>

    <!-- Filters -->
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6 mb-6">
        <h3 class="text-lg font-semibold mb-4 text-gray-800 dark:text-white">Filters</h3>
        <form method="GET" class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Warehouse</label>
                <select name="warehouse_id" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500">
                    <option value="">All Warehouses</option>
                    @foreach($warehouses as $warehouse)
                        <option value="{{ $warehouse->id }}" {{ $warehouseFilter == $warehouse->id ? 'selected' : '' }}>
                            {{ $warehouse->location }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="flex items-end">
                <button type="submit" class="w-full px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700">
                    <i class="fas fa-filter mr-2"></i>Apply Filters
                </button>
            </div>
        </form>
    </div>

    <!-- Summary Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6">
            <div class="flex items-center">
                <div class="p-3 bg-blue-100 dark:bg-blue-900 rounded-full">
                    <i class="fas fa-boxes text-blue-600 dark:text-blue-400 text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Total Items</p>
                    <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $items->count() }}</p>
                </div>
            </div>
        </div>
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6">
            <div class="flex items-center">
                <div class="p-3 bg-green-100 dark:bg-green-900 rounded-full">
                    <i class="fas fa-warehouse text-green-600 dark:text-green-400 text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Total Stock</p>
                    <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $items->sum('stock_quantity') }}</p>
                </div>
            </div>
        </div>
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6">
            <div class="flex items-center">
                <div class="p-3 bg-yellow-100 dark:bg-yellow-900 rounded-full">
                    <i class="fas fa-exclamation-triangle text-yellow-600 dark:text-yellow-400 text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Low Stock Items</p>
                    <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $items->where('stock_quantity', '<=', 10)->count() }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6">
            <h3 class="text-lg font-semibold mb-4 text-gray-800 dark:text-white">Inventory by Warehouse</h3>
            <canvas id="inventoryChart" width="400" height="200"></canvas>
        </div>
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6">
            <h3 class="text-lg font-semibold mb-4 text-gray-800 dark:text-white">Stock Level Distribution</h3>
            <canvas id="stockLevelChart" width="400" height="200"></canvas>
        </div>
    </div>

    <!-- Inventory Table -->
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
            <h3 class="text-lg font-semibold text-gray-800 dark:text-white">Inventory Items</h3>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                <thead class="bg-gray-50 dark:bg-gray-700">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-black uppercase tracking-wider">Item Name</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-black uppercase tracking-wider">Stock Quantity</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-black uppercase tracking-wider">Warehouse</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-black uppercase tracking-wider">Status</th>
                    </tr>
                </thead>
                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                    @foreach($items as $item)
                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-black">{{ $item->name }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-black">{{ $item->stock_quantity }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300">{{ $item->warehouse->location ?? 'N/A' }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                @if($item->stock_quantity <= 10) bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200
                                @elseif($item->stock_quantity <= 50) bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200
                                @else bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200 @endif">
                                @if($item->stock_quantity <= 10) Low Stock
                                @elseif($item->stock_quantity <= 50) Medium Stock
                                @else Well Stocked
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
    // Inventory Chart
    const inventoryCtx = document.getElementById('inventoryChart').getContext('2d');
    const inventoryChart = new Chart(inventoryCtx, {
        type: 'doughnut',
        data: {
            labels: [],
            datasets: [{
                data: [],
                backgroundColor: [
                    'rgba(59, 130, 246, 0.8)',
                    'rgba(34, 197, 94, 0.8)',
                    'rgba(251, 191, 36, 0.8)',
                    'rgba(239, 68, 68, 0.8)',
                    'rgba(168, 85, 247, 0.8)'
                ],
                borderWidth: 2,
                borderColor: '#fff'
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    position: 'bottom',
                }
            }
        }
    });

    // Stock Level Chart
    const stockLevelCtx = document.getElementById('stockLevelChart').getContext('2d');
    const stockLevelChart = new Chart(stockLevelCtx, {
        type: 'bar',
        data: {
            labels: ['Low Stock (≤10)', 'Medium Stock (11-50)', 'Well Stocked (>50)'],
            datasets: [{
                label: 'Number of Items',
                data: [
                    {{ $items->where('stock_quantity', '<=', 10)->count() }},
                    {{ $items->where('stock_quantity', '>', 10)->where('stock_quantity', '<=', 50)->count() }},
                    {{ $items->where('stock_quantity', '>', 50)->count() }}
                ],
                backgroundColor: [
                    'rgba(239, 68, 68, 0.8)',
                    'rgba(251, 191, 36, 0.8)',
                    'rgba(34, 197, 94, 0.8)'
                ],
                borderColor: [
                    'rgb(239, 68, 68)',
                    'rgb(251, 191, 36)',
                    'rgb(34, 197, 94)'
                ],
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

    // Load inventory chart data
    function loadInventoryChartData() {
        const warehouseId = document.querySelector('select[name="warehouse_id"]').value;
        
        fetch(`{{ route('manufacturer.reports.chart.inventory') }}?warehouse_id=${warehouseId}`)
            .then(response => response.json())
            .then(data => {
                inventoryChart.data.labels = data.labels;
                inventoryChart.data.datasets[0].data = data.data;
                inventoryChart.update();
            })
            .catch(error => console.error('Error loading inventory chart data:', error));
    }

    // Load initial chart data
    loadInventoryChartData();

    // Reload chart data when filters are applied
    document.querySelector('form').addEventListener('submit', function(e) {
        e.preventDefault();
        loadInventoryChartData();
    });
});
</script>
@endsection 