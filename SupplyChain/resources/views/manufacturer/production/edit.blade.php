@extends('manufacturer.layouts.dashboard')
@section('content')
<div class="container mx-auto py-8 flex justify-center">
    <div class="w-full max-w-lg bg-white rounded-lg shadow-lg p-8">
        <div class="flex items-center justify-between mb-6">
            <h1 class="text-3xl font-bold text-gray-800">Edit Work Order</h1>
        </div>
        <form action="{{ route('manufacturer.production.update', ['production' => $workOrder->id]) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="mb-5">
                <label for="product_id" class="block font-semibold text-gray-700 mb-2">Product</label>
                <select name="product_id" id="product_id" class="w-full border border-gray-300 rounded-lg p-2 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition" required>
                    <option value="">Select Product</option>
                    @foreach($products as $product)
                        <option value="{{ $product->id }}" {{ $workOrder->product_id == $product->id ? 'selected' : '' }}>{{ $product->name }}</option>
                    @endforeach
                </select>
                @error('product_id')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                @enderror
            </div>
            <div class="mb-5">
                <label for="quantity" class="block font-semibold text-gray-700 mb-2">Quantity</label>
                <input type="number" name="quantity" id="quantity" class="w-full border border-gray-300 rounded-lg p-2 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition" value="{{ old('quantity', $workOrder->quantity) }}" min="1" required>
                @error('quantity')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                @enderror
            </div>
            <div class="mb-5">
                <label for="scheduled_start" class="block font-semibold text-gray-700 mb-2">Scheduled Start</label>
                <input type="datetime-local" name="scheduled_start" id="scheduled_start" class="w-full border border-gray-300 rounded-lg p-2 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition" value="{{ old('scheduled_start', $workOrder->scheduled_start ? date('Y-m-d\TH:i', strtotime($workOrder->scheduled_start)) : '') }}" required>
                @error('scheduled_start')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                @enderror
            </div>
            <div class="mb-5">
                <label for="scheduled_end" class="block font-semibold text-gray-700 mb-2">Scheduled End</label>
                <input type="datetime-local" name="scheduled_end" id="scheduled_end" class="w-full border border-gray-300 rounded-lg p-2 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition" value="{{ old('scheduled_end', $workOrder->scheduled_end ? date('Y-m-d\TH:i', strtotime($workOrder->scheduled_end)) : '') }}" required>
                @error('scheduled_end')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                @enderror
            </div>
            <div class="mb-5">
                <label for="status" class="block font-semibold text-gray-700 mb-2">Status</label>
                <select name="status" id="status" class="w-full border border-gray-300 rounded-lg p-2 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition" required>
                    @foreach(['Planned', 'InProgress', 'Completed', 'Cancelled'] as $status)
                        <option value="{{ $status }}" {{ $workOrder->status == $status ? 'selected' : '' }}>{{ $status }}</option>
                    @endforeach
                </select>
                @error('status')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                @enderror
            </div>
            <div class="mb-5">
                <label for="notes" class="block font-semibold text-gray-700 mb-2">Notes</label>
                <textarea name="notes" id="notes" class="w-full border border-gray-300 rounded-lg p-2 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition" rows="3">{{ old('notes', $workOrder->notes) }}</textarea>
                @error('notes')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                @enderror
            </div>
            <div class="flex justify-between mt-6">
                <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white font-semibold py-2 px-4 rounded-lg transition">Save</button>
                <a href="{{ route('manufacturer.production.index') }}" class="bg-gray-200 hover:bg-gray-300 text-gray-800 font-semibold py-2 px-4 rounded-lg transition">Cancel</a>
            </div>
        </form>
    </div>
</div>
<div class="container mx-auto flex justify-center mt-6">
    <a href="{{ route('manufacturer.production.index') }}" class="inline-block bg-gray-200 hover:bg-gray-300 text-gray-800 font-semibold py-2 px-4 rounded-lg shadow transition">&larr; Back to Work Orders</a>
</div>
@endsection 