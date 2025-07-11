@extends('manufacturer.layouts.dashboard')
@section('content')
<div class="container mx-auto py-6">
    <h1 class="text-2xl font-bold text-white mb-4">Production Cost Details</h1>
    <div class="text-white mb-4">
        <strong>Product:</strong> {{ $productionCost->item->name ?? '-' }}
    </div>
    <div class="text-white mb-4">
        <strong>Amount:</strong> ${{ number_format($productionCost->amount, 2) }}
    </div>
    <div class="text-white mb-4">
        <strong>Date:</strong> {{ $productionCost->date }}
    </div>
    <div class="text-white mb-4">
        <strong>Notes:</strong> {{ $productionCost->notes ?? '-' }}
    </div>
    <a href="{{ route('manufacturer.production-costs.edit', $productionCost->id) }}" class="bg-blue-600 text-white px-4 py-2 rounded">Edit</a>
    <form action="{{ route('manufacturer.production-costs.destroy', $productionCost->id) }}" method="POST" class="inline-block ml-2" onsubmit="return confirm('Are you sure you want to delete this cost?');">
        @csrf
        @method('DELETE')
        <button type="submit" class="bg-red-600 text-white px-4 py-2 rounded">Delete</button>
    </form>
    <a href="{{ route('manufacturer.production-costs.index') }}" class="ml-4 text-gray-600 hover:underline">Back to List</a>
</div>
@endsection 