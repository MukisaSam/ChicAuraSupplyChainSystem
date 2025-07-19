@extends('manufacturer.layouts.dashboard')
@section('content')
<div class="container mx-auto py-8 flex justify-center">
    <div class="w-full max-w-lg bg-white rounded-lg shadow-lg p-8">
        <h1 class="text-2xl font-bold mb-6 text-black">Production Cost Details</h1>
        <div class="mb-4">
            <span class="block text-gray-700 font-semibold mb-1">Product:</span>
            <span class="text-gray-800">
                @if($productionCost->workOrder && $productionCost->workOrder->product)
                    {{ $productionCost->workOrder->product->name }}
                @else
                    <span class="text-red-500">Product not found</span>
                @endif
            </span>
        </div>
        <div class="mb-4">
            <span class="block text-gray-700 font-semibold mb-1">Amount:</span>
            <span class="text-gray-800">${{ number_format($productionCost->amount, 2) }}</span>
        </div>
        <div class="mb-4">
            <span class="block text-gray-700 font-semibold mb-1">Date:</span>
            <span class="text-gray-800">{{ $productionCost->created_at ? $productionCost->created_at->format('Y-m-d') : '-' }}</span>
        </div>
        <div class="mb-4">
            <span class="block text-gray-700 font-semibold mb-1">Notes:</span>
            <span class="text-gray-800">{{ $productionCost->notes ?? '-' }}</span>
        </div>
        <div class="flex flex-wrap gap-4 mt-6">
            <a href="{{ route('manufacturer.production-costs.edit', $productionCost->id) }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded transition">Edit</a>
            <form action="{{ route('manufacturer.production-costs.destroy', $productionCost->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Are you sure you want to delete this cost?');">
                @csrf
                @method('DELETE')
                <button type="submit" class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded transition">Delete</button>
            </form>
        </div>
    </div>
</div>
<div class="container mx-auto flex justify-center mt-6">
    <a href="{{ route('manufacturer.production-costs.index') }}" class="inline-block bg-gray-200 hover:bg-gray-300 text-gray-800 font-semibold py-2 px-4 rounded-lg shadow transition">&larr; Back to Costs</a>
</div>
@endsection 