@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <!-- Header -->
    <div class="mb-8">
        <div class="flex justify-between items-center">
            <div>
                <h1 class="text-3xl font-bold text-gray-900 dark:text-white">Supply Request Details</h1>
                <p class="text-gray-600 dark:text-gray-400 mt-2">Request #{{ $supplyRequest->id }}</p>
            </div>
            <div class="flex space-x-3">
                <a href="{{ route('manufacturer.orders') }}" 
                   class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg flex items-center space-x-2 transition-colors">
                    <i class="fas fa-arrow-left"></i>
                    <span>Back to Orders</span>
                </a>
            </div>
        </div>
    </div>

    <!-- Request Status -->
    <div class="mb-8">
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6">
            <div class="flex justify-between items-center">
                <div>
                    <h2 class="text-xl font-semibold text-gray-900 dark:text-white mb-2">Request Status</h2>
                    <span class="inline-flex px-3 py-1 text-sm font-semibold rounded-full 
                        @if($supplyRequest->status === 'pending') bg-yellow-100 text-yellow-800
                        @elseif($supplyRequest->status === 'approved') bg-blue-100 text-blue-800
                        @elseif($supplyRequest->status === 'completed') bg-green-100 text-green-800
                        @else bg-red-100 text-red-800
                        @endif">
                        {{ ucfirst($supplyRequest->status) }}
                    </span>
                </div>
                <div class="text-right">
                    <p class="text-sm text-gray-600 dark:text-gray-400">Request Date</p>
                    <p class="text-lg font-semibold text-gray-900 dark:text-white">{{ $supplyRequest->created_at->format('M d, Y H:i') }}</p>
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Request Information -->
        <div class="lg:col-span-2">
            <!-- Supplier Information -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6 mb-6">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Supplier Information</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Name</p>
                        <p class="text-lg text-gray-900 dark:text-white">{{ $supplyRequest->supplier->user->name ?? 'N/A' }}</p>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Business Type</p>
                        <p class="text-lg text-gray-900 dark:text-white">{{ $supplyRequest->supplier->business_type ?? 'N/A' }}</p>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Email</p>
                        <p class="text-lg text-gray-900 dark:text-white">{{ $supplyRequest->supplier->user->email ?? 'N/A' }}</p>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Phone</p>
                        <p class="text-lg text-gray-900 dark:text-white">{{ $supplyRequest->supplier->phone ?? 'N/A' }}</p>
                    </div>
                    <div class="md:col-span-2">
                        <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Address</p>
                        <p class="text-lg text-gray-900 dark:text-white">{{ $supplyRequest->supplier->business_address ?? 'N/A' }}</p>
                    </div>
                </div>
            </div>

            <!-- Item Information -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Item Information</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Item Name</p>
                        <p class="text-lg text-gray-900 dark:text-white">{{ $supplyRequest->item->name ?? 'N/A' }}</p>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Category</p>
                        <p class="text-lg text-gray-900 dark:text-white">{{ $supplyRequest->item->category ?? 'N/A' }}</p>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Material</p>
                        <p class="text-lg text-gray-900 dark:text-white">{{ $supplyRequest->item->material ?? 'N/A' }}</p>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Base Price</p>
                        <p class="text-lg text-gray-900 dark:text-white">${{ number_format($supplyRequest->item->base_price ?? 0, 2) }}</p>
                    </div>
                    <div class="md:col-span-2">
                        <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Description</p>
                        <p class="text-lg text-gray-900 dark:text-white">{{ $supplyRequest->item->description ?? 'N/A' }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Request Summary & Actions -->
        <div class="lg:col-span-1">
            <!-- Request Summary -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6 mb-6">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Request Summary</h3>
                <div class="space-y-3">
                    <div class="flex justify-between">
                        <span class="text-gray-600 dark:text-gray-400">Quantity Requested:</span>
                        <span class="text-gray-900 dark:text-white font-semibold">{{ $supplyRequest->quantity }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600 dark:text-gray-400">Due Date:</span>
                        <span class="text-gray-900 dark:text-white">{{ \Carbon\Carbon::parse($supplyRequest->due_date)->format('M d, Y') }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600 dark:text-gray-400">Days Remaining:</span>
                        <span class="text-gray-900 dark:text-white">
                            @php
                                $daysRemaining = \Carbon\Carbon::parse($supplyRequest->due_date)->diffInDays(now(), false);
                            @endphp
                            @if($daysRemaining > 0)
                                <span class="text-green-600">{{ $daysRemaining }} days</span>
                            @elseif($daysRemaining == 0)
                                <span class="text-yellow-600">Due today</span>
                            @else
                                <span class="text-red-600">{{ abs($daysRemaining) }} days overdue</span>
                            @endif
                        </span>
                    </div>
                </div>
            </div>

            <!-- Update Status -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6 mb-6">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Update Status</h3>
                <form action="{{ route('manufacturer.orders.update-supply-request-status', $supplyRequest) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="mb-4">
                        <label for="status" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Status</label>
                        <select name="status" id="status" 
                                class="w-full border border-gray-300 dark:border-gray-600 rounded-lg px-3 py-2 bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                            <option value="pending" {{ $supplyRequest->status === 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="approved" {{ $supplyRequest->status === 'approved' ? 'selected' : '' }}>Approved</option>
                            <option value="completed" {{ $supplyRequest->status === 'completed' ? 'selected' : '' }}>Completed</option>
                            <option value="rejected" {{ $supplyRequest->status === 'rejected' ? 'selected' : '' }}>Rejected</option>
                        </select>
                    </div>
                    <div class="mb-4">
                        <label for="notes" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Notes</label>
                        <textarea name="notes" id="notes" rows="3" 
                                  class="w-full border border-gray-300 dark:border-gray-600 rounded-lg px-3 py-2 bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-indigo-500 focus:border-transparent"
                                  placeholder="Add any notes about this request...">{{ $supplyRequest->notes }}</textarea>
                    </div>
                    <button type="submit" class="w-full bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-lg transition-colors">
                        Update Status
                    </button>
                </form>
            </div>

            <!-- Request Details -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Request Details</h3>
                <div class="space-y-3">
                    @if($supplyRequest->notes)
                    <div>
                        <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Notes</p>
                        <p class="text-gray-900 dark:text-white">{{ $supplyRequest->notes }}</p>
                    </div>
                    @endif
                    <div>
                        <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Created At</p>
                        <p class="text-gray-900 dark:text-white">{{ $supplyRequest->created_at->format('M d, Y H:i') }}</p>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Updated At</p>
                        <p class="text-gray-900 dark:text-white">{{ $supplyRequest->updated_at->format('M d, Y H:i') }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@if(session('success'))
<div id="success-message" class="fixed top-4 right-4 bg-green-500 text-white px-6 py-3 rounded-lg shadow-lg z-50">
    {{ session('success') }}
    <button onclick="document.getElementById('success-message').style.display='none'" class="ml-4 text-white hover:text-gray-200">
        <i class="fas fa-times"></i>
    </button>
</div>
<script>
    setTimeout(function() {
        document.getElementById('success-message').style.display = 'none';
    }, 5000);
</script>
@endif
@endsection 