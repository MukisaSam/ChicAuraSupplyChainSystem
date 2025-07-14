@extends('layouts.supplier-dashboard')

@section('content')     
        <div class="flex flex-col flex-1 w-full">

<div class="max-w-6xl mx-auto px-4 py-8">
    <h2 class="text-2xl font-bold mb-6 text-gray-900 dark:text-white">Supplier Reports</h2>
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow p-6 mb-8">
        <h5 class="text-lg font-semibold mb-4 text-gray-900 dark:text-white">Monthly Performance Report ({{ date('Y') }})</h5>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                <thead class="bg-gray-50 dark:bg-gray-900">
                    <tr>
                        <th class="px-4 py-2 text-left text-xs font-semibold text-gray-700 dark:text-gray-200 uppercase">Month</th>
                        <th class="px-4 py-2 text-left text-xs font-semibold text-gray-700 dark:text-gray-200 uppercase">Quantity Supplied</th>
                        <th class="px-4 py-2 text-left text-xs font-semibold text-gray-700 dark:text-gray-200 uppercase">Revenue</th>
                        <th class="px-4 py-2 text-left text-xs font-semibold text-gray-700 dark:text-gray-200 uppercase">Average Rating</th>
                        <th class="px-4 py-2 text-left text-xs font-semibold text-gray-700 dark:text-gray-200 uppercase">Performance</th>
                    </tr>
                </thead>
                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                    @forelse($monthlyReport as $report)
                    <tr>
                        <td class="px-4 py-2">{{ date('F', mktime(0, 0, 0, $report->month, 1)) }}</td>
                        <td class="px-4 py-2">{{ number_format($report->quantity) }}</td>
                        <td class="px-4 py-2">${{ number_format($report->revenue, 2) }}</td>
                        <td class="px-4 py-2">
                            <div class="flex items-center">
                                @for($i = 1; $i <= 5; $i++)
                                    <i class="fas fa-star {{ $i <= $report->avg_rating ? 'text-yellow-400' : 'text-gray-300' }}"></i>
                                @endfor
                                <span class="ml-2 text-xs">({{ number_format($report->avg_rating, 1) }})</span>
                            </div>
                        </td>
                        <td class="px-4 py-2">
                            @php
                                $performance = ($report->quantity > 1000) ? 'Excellent' : 
                                            (($report->quantity > 500) ? 'Good' : 'Average');
                                $badgeClass = ($performance == 'Excellent') ? 'bg-green-200 text-green-800' : 
                                            (($performance == 'Good') ? 'bg-blue-200 text-blue-800' : 'bg-yellow-200 text-yellow-800');
                            @endphp
                            <span class="inline-block px-2 py-1 rounded text-xs font-semibold {{ $badgeClass }}">{{ $performance }}</span>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="text-center py-4 text-gray-500">No data available</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="bg-white dark:bg-gray-800 rounded-xl shadow p-6">
        <h5 class="text-lg font-semibold mb-4 text-gray-900 dark:text-white">Item Performance Report</h5>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                <thead class="bg-gray-50 dark:bg-gray-900">
                    <tr>
                        <th class="px-4 py-2 text-left text-xs font-semibold text-gray-700 dark:text-gray-200 uppercase">Item</th>
                        <th class="px-4 py-2 text-left text-xs font-semibold text-gray-700 dark:text-gray-200 uppercase">Total Quantity</th>
                        <th class="px-4 py-2 text-left text-xs font-semibold text-gray-700 dark:text-gray-200 uppercase">Average Price</th>
                        <th class="px-4 py-2 text-left text-xs font-semibold text-gray-700 dark:text-gray-200 uppercase">Average Rating</th>
                        <th class="px-4 py-2 text-left text-xs font-semibold text-gray-700 dark:text-gray-200 uppercase">Total Revenue</th>
                    </tr>
                </thead>
                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                    @forelse($itemReport as $report)
                    <tr>
                        <td class="px-4 py-2">
                            <strong>{{ $report->item->name }}</strong>
                            <br>
                            <span class="text-xs text-gray-500">{{ $report->item->description }}</span>
                        </td>
                        <td class="px-4 py-2">{{ number_format($report->total_quantity) }}</td>
                        <td class="px-4 py-2">${{ number_format($report->avg_price, 2) }}</td>
                        <td class="px-4 py-2">
                            <div class="flex items-center">
                                @for($i = 1; $i <= 5; $i++)
                                    <i class="fas fa-star {{ $i <= $report->avg_rating ? 'text-yellow-400' : 'text-gray-300' }}"></i>
                                @endfor
                                <span class="ml-2 text-xs">({{ number_format($report->avg_rating, 1) }})</span>
                            </div>
                        </td>
                        <td class="px-4 py-2">${{ number_format($report->total_quantity * $report->avg_price, 2) }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="text-center py-4 text-gray-500">No data available</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection 