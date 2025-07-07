@extends('manufacturer.layouts.dashboard')

@section('content')
<div class="container mx-auto px-4 py-8 max-w-3xl">
    <div class="mb-8">
        <div class="flex justify-between items-center">
            <div>
                <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Supply Request #{{ $supplyRequest->id }}</h1>
                <p class="text-gray-600 dark:text-gray-400 mt-1">Status:
                    <span class="inline-flex px-3 py-1 text-sm font-semibold rounded-full 
                        @if($supplyRequest->status === 'pending') bg-yellow-100 text-yellow-800
                        @elseif($supplyRequest->status === 'approved') bg-blue-100 text-blue-800
                        @elseif($supplyRequest->status === 'completed') bg-green-100 text-green-800
                        @else bg-red-100 text-red-800
                        @endif">
                        {{ ucfirst($supplyRequest->status) }}
                    </span>
                </p>
            </div>
            <a href="{{ route('manufacturer.orders') }}" class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg flex items-center space-x-2 transition-colors">
                <i class="fas fa-arrow-left"></i>
                <span>Back to Orders</span>
            </a>
        </div>
    </div>

    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6 mb-6">
        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Supplier & Item</h3>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Supplier Name</p>
                <p class="text-lg text-gray-900 dark:text-white">{{ $supplyRequest->supplier->user->name ?? 'N/A' }}</p>
            </div>
            <div>
                <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Item Name</p>
                <p class="text-lg text-gray-900 dark:text-white">{{ $supplyRequest->item->name ?? 'N/A' }}</p>
            </div>
        </div>
    </div>

    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6 mb-6">
        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Request Details</h3>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Quantity Requested</p>
                <p class="text-lg text-gray-900 dark:text-white">{{ $supplyRequest->quantity }}</p>
            </div>
            <div>
                <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Due Date</p>
                <p class="text-lg text-gray-900 dark:text-white">{{ \Carbon\Carbon::parse($supplyRequest->due_date)->format('M d, Y') }}</p>
            </div>
        </div>
        @if($supplyRequest->notes)
        <div class="mt-4">
            <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Notes</p>
            <p class="text-gray-900 dark:text-white">{{ $supplyRequest->notes }}</p>
        </div>
        @endif
    </div>
</div>
@endsection 