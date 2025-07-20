@extends('layouts.supplier-dashboard')

@section('content')
<div class="max-w-2xl mx-auto mt-8">
  <div class="bg-white dark:bg-gray-800 rounded-xl shadow p-6 space-y-6 relative">
    <a href="{{ route('supplier.supply-requests.index') }}" class="absolute top-6 right-6 inline-flex items-center px-4 py-2 bg-gray-200 dark:bg-gray-700 text-gray-800 dark:text-gray-200 rounded hover:bg-gray-300 dark:hover:bg-gray-600 transition text-sm font-semibold">
      <i class="fas fa-arrow-left mr-2"></i> Back to Supply Requests
    </a>
    <h2 class="text-xl font-bold mb-2">Supply Request Details</h2>
    <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-4 shadow">
        <div class="mb-2">
            <span class="font-semibold">Item:</span> {{ $supplyRequest->item->name }}
        </div>
        <div class="mb-2">
            <span class="font-semibold">Quantity:</span> {{ $supplyRequest->quantity }}
        </div>
        <div class="mb-2">
            <span class="font-semibold">Due Date:</span> {{ $supplyRequest->due_date->format('M d, Y') }}
        </div>
        <div class="mb-2">
            <span class="font-semibold">Status:</span>
            <span class="inline-block px-2 py-1 rounded text-xs font-semibold
                @if($supplyRequest->status === 'pending') bg-yellow-200 text-yellow-800
                @elseif($supplyRequest->status === 'approved') bg-green-200 text-green-800
                @elseif($supplyRequest->status === 'rejected') bg-red-200 text-red-800
                @elseif($supplyRequest->status === 'in_progress') bg-blue-200 text-blue-800
                @elseif($supplyRequest->status === 'completed') bg-purple-200 text-purple-800
                @else bg-gray-200 text-gray-800 @endif">
                {{ ucfirst($supplyRequest->status) }}
            </span>
        </div>
        <div class="mb-2">
            <span class="font-semibold">Notes:</span> {{ $supplyRequest->notes }}
        </div>
    </div>

    @if(in_array($supplyRequest->status, ['pending', 'in_progress', 'approved', 'rejected']))
    <form id="status-update-form" class="space-y-4 mt-4">
        <div>
            <label for="status" class="block text-sm font-medium mb-1">Update Status</label>
            <select name="status" id="status" class="form-select w-full rounded border-gray-300">
                <option value="approved" {{ $supplyRequest->status == 'approved' ? 'selected' : '' }}>Approve</option>
                <option value="rejected" {{ $supplyRequest->status == 'rejected' ? 'selected' : '' }}>Reject</option>
                <option value="pending" {{ $supplyRequest->status == 'pending' ? 'selected' : '' }}>Pending</option>
                <option value="in_progress" {{ $supplyRequest->status == 'in_progress' ? 'selected' : '' }}>In Progress</option>
                <option value="completed" {{ $supplyRequest->status == 'completed' ? 'selected' : '' }}>Completed</option>
            </select>
        </div>
        <div>
            <label for="notes" class="block text-sm font-medium mb-1">Notes</label>
            <textarea name="notes" id="notes" class="form-textarea w-full rounded border-gray-300"></textarea>
        </div>
        <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 transition">Update Request</button>
    </form>
    @else
    <div class="bg-blue-100 text-blue-800 rounded p-3 mt-3">Status changes are not allowed for this request.</div>
    @endif

    @if($supplyRequest->priceNegotiation)
    <hr class="my-4">
    <h4 class="text-lg font-semibold mb-2">Price Negotiation</h4>
    <div class="mb-2">
        <span class="font-semibold">Initial Price:</span> UGX{{ number_format($supplyRequest->priceNegotiation->initial_price, 2) }}
    </div>
    <div class="mb-2">
        <span class="font-semibold">Status:</span> {{ ucfirst($supplyRequest->priceNegotiation->status) }}
    </div>
    <form method="POST" action="{{ route('supplier.supply-requests.negotiate', $supplyRequest) }}" class="space-y-2">
        @csrf
        <div>
            <label for="counter_price" class="block text-sm font-medium mb-1">Counter Price</label>
            <input type="number" step="0.01" name="counter_price" id="counter_price" class="form-input w-full rounded border-gray-300" required>
        </div>
        <div>
            <label for="notes" class="block text-sm font-medium mb-1">Notes</label>
            <textarea name="notes" id="notes" class="form-textarea w-full rounded border-gray-300"></textarea>
        </div>
        <button type="submit" class="bg-yellow-500 text-white px-4 py-2 rounded hover:bg-yellow-600 transition">Submit Counter Offer</button>
    </form>
    @endif
  </div>
</div>
@endsection 