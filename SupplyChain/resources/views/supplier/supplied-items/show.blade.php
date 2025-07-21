@extends('layouts.supplier-dashboard')

@section('content')
<div class="container mx-auto max-w-xl mt-8">
    <h2 class="text-2xl font-bold mb-4">Supplied Item Details</h2>
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow p-6 mb-6">
        <h5 class="text-lg font-semibold mb-2">Item: {{ $suppliedItem->item->name }}</h5>
        <ul class="text-sm space-y-2">
            <li><strong>Quantity:</strong> {{ $suppliedItem->quantity }}</li>
            <li><strong>Due Date:</strong> {{ $suppliedItem->due_date->format('M d, Y') }}</li>
            <li><strong>Status:</strong> <span class="inline-block px-2 py-1 rounded text-xs font-semibold bg-blue-200 text-blue-800">{{ ucfirst($suppliedItem->status) }}</span></li>
            <li><strong>Notes:</strong> {{ $suppliedItem->notes }}</li>
        </ul>
    </div>
</div>
@endsection 