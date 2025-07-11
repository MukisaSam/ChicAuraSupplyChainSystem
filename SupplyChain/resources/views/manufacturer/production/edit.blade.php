@extends('manufacturer.layouts.dashboard')
@section('content')
<div class="container mx-auto py-6 max-w-2xl">
  <div class="flex items-center justify-between mb-4">
    <h1 class="text-2xl font-bold text-white mb-4">Edit Work Order</h1>
  </div>
    <form action="{{ route('manufacturer.production.update', ['production' => $workOrder->id]) }}" method="POST" class="bg-white p-6 rounded shadow-md max-w-lg mx-auto">
        @csrf
        @method('PUT')
        <div class="mb-4">
            <label for="product_id" class="block font-semibold mb-1">Product</label>
            <select name="product_id" id="product_id" class="w-full border rounded px-3 py-2" required>
                <option value="">Select Product</option>
                @foreach($products as $product)
                    <option value="{{ $product->id }}" {{ $workOrder->product_id == $product->id ? 'selected' : '' }}>{{ $product->name }}</option>
                @endforeach
            </select>
            @error('product_id')
                <span class="text-red-500 text-sm">{{ $message }}</span>
            @enderror
        </div>
        <div class="mb-4">
            <label for="quantity" class="block font-semibold mb-1">Quantity</label>
            <input type="number" name="quantity" id="quantity" class="w-full border rounded px-3 py-2" value="{{ old('quantity', $workOrder->quantity) }}" min="1" required>
            @error('quantity')
                <span class="text-red-500 text-sm">{{ $message }}</span>
            @enderror
        </div>
        <div class="mb-4">
            <label for="scheduled_start" class="block font-semibold mb-1">Scheduled Start</label>
            <input type="datetime-local" name="scheduled_start" id="scheduled_start" class="w-full border rounded px-3 py-2" value="{{ old('scheduled_start', $workOrder->scheduled_start ? date('Y-m-d\TH:i', strtotime($workOrder->scheduled_start)) : '') }}" required>
            @error('scheduled_start')
                <span class="text-red-500 text-sm">{{ $message }}</span>
            @enderror
        </div>
        <div class="mb-4">
            <label for="scheduled_end" class="block font-semibold mb-1">Scheduled End</label>
            <input type="datetime-local" name="scheduled_end" id="scheduled_end" class="w-full border rounded px-3 py-2" value="{{ old('scheduled_end', $workOrder->scheduled_end ? date('Y-m-d\TH:i', strtotime($workOrder->scheduled_end)) : '') }}" required>
            @error('scheduled_end')
                <span class="text-red-500 text-sm">{{ $message }}</span>
            @enderror
        </div>
        <div class="mb-4">
            <label for="status" class="block font-semibold mb-1">Status</label>
            <select name="status" id="status" class="w-full border rounded px-3 py-2" required>
                @foreach(['Planned', 'InProgress', 'Completed', 'Cancelled'] as $status)
                    <option value="{{ $status }}" {{ $workOrder->status == $status ? 'selected' : '' }}>{{ $status }}</option>
                @endforeach
            </select>
            @error('status')
                <span class="text-red-500 text-sm">{{ $message }}</span>
            @enderror
        </div>
        <div class="mb-4">
            <label for="notes" class="block font-semibold mb-1">Notes</label>
            <textarea name="notes" id="notes" class="w-full border rounded px-3 py-2" rows="3">{{ old('notes', $workOrder->notes) }}</textarea>
            @error('notes')
                <span class="text-red-500 text-sm">{{ $message }}</span>
            @enderror
        </div>
        <div class="flex justify-between">
            <button type="submit" class="bg-indigo-600 text-white px-4 py-2 rounded hover:bg-indigo-700">Save</button>
            <a href="{{ route('manufacturer.production.index') }}" class="bg-gray-300 text-gray-800 px-4 py-2 rounded hover:bg-gray-400">Cancel</a>
        </div>
    </form>
</div>
<div class="container mx-auto py-6 max-w-2xl">
    <a href="{{ route('manufacturer.production.index') }}" class="bg-gray-200 text-gray-800 px-3 py-1 rounded hover:bg-gray-300">&larr; Back</a>
  </div>
@endsection 