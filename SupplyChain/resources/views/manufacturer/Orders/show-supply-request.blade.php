@extends('manufacturer.layouts.dashboard')

@section('content')
<div class="container mx-auto px-4 py-8 max-w-3xl">
    <!-- Header -->
    <div class="mb-8 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <h1 class="text-3xl font-bold text-black mb-1">Supply Request #{{ $supplyRequest->id }}</h1>
            <div class="flex items-center gap-2">
                <span class="inline-flex px-3 py-1 text-sm font-semibold rounded-full
                    @if($supplyRequest->status === 'pending') bg-yellow-100 text-yellow-800
                    @elseif($supplyRequest->status === 'approved') bg-blue-100 text-blue-800
                    @elseif($supplyRequest->status === 'completed') bg-green-100 text-green-800
                    @else bg-red-100 text-red-800
                    @endif">
                    {{ ucfirst($supplyRequest->status) }}
                </span>
                <span class="text-xs text-gray-500 ml-2">Created: {{ $supplyRequest->created_at->format('M d, Y H:i') }}</span>
                <span class="text-xs text-gray-500 ml-2">Updated: {{ $supplyRequest->updated_at->format('M d, Y H:i') }}</span>
            </div>
        </div>
        <a href="{{ route('manufacturer.orders') }}"
           class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg flex items-center space-x-2 transition-colors">
            <i class="fas fa-arrow-left"></i>
            <span>Back to Orders</span>
        </a>
    </div>

    <!-- Supplier & Item Info -->
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6 mb-6">
        <h2 class="text-lg font-semibold text-black mb-4 flex items-center gap-2">
            <i class="fas fa-industry"></i> Supplier & Item
        </h2>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <p class="text-sm font-medium text-black">Supplier Name</p>
                <p class="text-lg text-black font-semibold">{{ $supplyRequest->supplier->user->name ?? 'N/A' }}</p>
                @if($supplyRequest->supplier)
                    <p class="text-sm text-gray-500 mt-1">Phone: {{ $supplyRequest->supplier->phone ?? 'N/A' }}</p>
                    <p class="text-sm text-gray-500">Address: {{ $supplyRequest->supplier->business_address ?? 'N/A' }}</p>
                @endif
            </div>
            <div>
                <p class="text-sm font-medium text-black">Item Name</p>
                <p class="text-lg text-black font-semibold">{{ $supplyRequest->item->name ?? 'N/A' }}</p>
                @if($supplyRequest->item)
                    <p class="text-sm text-gray-500 mt-1">Category: {{ $supplyRequest->item->category ?? 'N/A' }}</p>
                    <p class="text-sm text-gray-500">Description: {{ $supplyRequest->item->description ?? 'N/A' }}</p>
                @endif
            </div>
        </div>
    </div>

    <!-- Request Details -->
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6 mb-6">
        <h2 class="text-lg font-semibold text-black mb-4 flex items-center gap-2">
            <i class="fas fa-info-circle"></i> Request Details
        </h2>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <p class="text-sm font-medium text-black">Quantity Requested</p>
                <p class="text-lg text-black font-semibold">{{ $supplyRequest->quantity }}</p>
            </div>
            <div>
                <p class="text-sm font-medium text-black">Due Date</p>
                <p class="text-lg text-black font-semibold">{{ \Carbon\Carbon::parse($supplyRequest->due_date)->format('M d, Y') }}</p>
            </div>
        </div>
        @if($supplyRequest->notes)
        <div class="mt-6">
            <p class="text-sm font-medium text-black">Notes</p>
            <p class="text-black">{{ $supplyRequest->notes }}</p>
        </div>
        @endif
    </div>
</div>
@endsection 