@extends('manufacturer.layouts.dashboard')
@section('content')
<div class="container mx-auto py-8 flex justify-center">
    <div class="w-full max-w-lg bg-white rounded-lg shadow-lg p-8">
        <h1 class="text-3xl font-bold text-gray-800 mb-6 text-center">Production Schedule Details</h1>
        <div class="mb-4">
            <span class="block text-gray-700 font-semibold mb-1">Product:</span>
            <span class="text-gray-800">
                @if($productionSchedule->workOrder && $productionSchedule->workOrder->product)
                    {{ $productionSchedule->workOrder->product->name }}
                @else
                    <span class="text-red-500">Product not found</span>
                @endif
            </span>
        </div>
        <div class="mb-4">
            <span class="block text-gray-700 font-semibold mb-1">Start Date:</span>
            <span class="text-gray-800">{{ $productionSchedule->start_date }}</span>
        </div>
        <div class="mb-4">
            <span class="block text-gray-700 font-semibold mb-1">End Date:</span>
            <span class="text-gray-800">{{ $productionSchedule->end_date }}</span>
        </div>
        <div class="mb-4">
            <span class="block text-gray-700 font-semibold mb-1">Status:</span>
            <span class="text-gray-800">{{ ucfirst($productionSchedule->status) }}</span>
        </div>
        <div class="flex flex-wrap gap-4 mt-6">
            <a href="{{ route('manufacturer.production-schedules.edit', $productionSchedule->id) }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded transition">Edit</a>
            <form action="{{ route('manufacturer.production-schedules.destroy', $productionSchedule->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Are you sure you want to delete this schedule?');">
                @csrf
                @method('DELETE')
                <button type="submit" class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded transition">Delete</button>
            </form>
        </div>
    </div>
</div>
<div class="container mx-auto flex justify-center mt-6">
    <a href="{{ route('manufacturer.production-schedules.index') }}" class="inline-block bg-gray-200 hover:bg-gray-300 text-gray-800 font-semibold py-2 px-4 rounded-lg shadow transition">&larr; Back to Schedules</a>
</div>
@endsection 