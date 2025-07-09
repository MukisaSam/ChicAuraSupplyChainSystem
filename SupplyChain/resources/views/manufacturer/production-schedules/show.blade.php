@extends('manufacturer.layouts.dashboard')
@section('content')
<div class="container mx-auto py-6">
    <h1 class="text-2xl font-bold mb-4">Production Schedule Details</h1>
    <div class="mb-4">
        <strong>Product:</strong> {{ $productionSchedule->product->name ?? '-' }}
    </div>
    <div class="mb-4">
        <strong>Start Date:</strong> {{ $productionSchedule->start_date }}
    </div>
    <div class="mb-4">
        <strong>End Date:</strong> {{ $productionSchedule->end_date }}
    </div>
    <div class="mb-4">
        <strong>Status:</strong> {{ ucfirst($productionSchedule->status) }}
    </div>
    <a href="{{ route('manufacturer.production-schedules.edit', $productionSchedule->id) }}" class="bg-blue-600 text-white px-4 py-2 rounded">Edit</a>
    <form action="{{ route('manufacturer.production-schedules.destroy', $productionSchedule->id) }}" method="POST" class="inline-block ml-2" onsubmit="return confirm('Are you sure you want to delete this schedule?');">
        @csrf
        @method('DELETE')
        <button type="submit" class="bg-red-600 text-white px-4 py-2 rounded">Delete</button>
    </form>
    <a href="{{ route('manufacturer.production-schedules.index') }}" class="ml-4 text-gray-600 hover:underline">Back to List</a>
</div>
@endsection 