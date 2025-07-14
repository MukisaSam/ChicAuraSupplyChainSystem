@extends('manufacturer.layouts.dashboard')
@section('content')
<div class="container mx-auto py-8 flex justify-center">
    <div class="w-full max-w-lg bg-white rounded-lg shadow-lg p-8">
        <h1 class="text-3xl font-bold text-gray-800 mb-6 text-center">Add Production Cost</h1>
        <form method="POST" action="{{ route('manufacturer.production-costs.store') }}">
            @csrf
            <div class="mb-5">
                <label class="block font-semibold text-gray-700 mb-2">Work Order</label>
                <select name="work_order_id" class="w-full border border-gray-300 rounded-lg p-2 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition" required>
                    <option value="">Select a work order</option>
                    @foreach(App\Models\WorkOrder::with('product')->get() as $workOrder)
                        <option value="{{ $workOrder->id }}">
                            #{{ $workOrder->id }} - {{ $workOrder->product->name ?? 'Product not found' }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="mb-5">
                <label class="block font-semibold text-gray-700 mb-2">Material Cost</label>
                <input type="number" name="material_cost" class="w-full border border-gray-300 rounded-lg p-2 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition" min="0" step="0.01" required>
            </div>
            <div class="mb-5">
                <label class="block font-semibold text-gray-700 mb-2">Labor Cost</label>
                <input type="number" name="labor_cost" class="w-full border border-gray-300 rounded-lg p-2 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition" min="0" step="0.01" required>
            </div>
            <div class="mb-5">
                <label class="block font-semibold text-gray-700 mb-2">Overhead Cost</label>
                <input type="number" name="overhead_cost" class="w-full border border-gray-300 rounded-lg p-2 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition" min="0" step="0.01" required>
            </div>
            <div class="mb-5">
                <label class="block font-semibold text-gray-700 mb-2">Total Cost</label>
                <input type="number" name="total_cost" class="w-full border border-gray-300 rounded-lg p-2 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition" min="0" step="0.01" required>
            </div>
            <div class="mb-5">
                <label class="block font-semibold text-gray-700 mb-2">Date</label>
                <input type="date" name="date" class="w-full border border-gray-300 rounded-lg p-2 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition" required>
            </div>
            <div class="mb-5">
                <label class="block font-semibold text-gray-700 mb-2">Notes</label>
                <textarea name="notes" class="w-full border border-gray-300 rounded-lg p-2 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition"></textarea>
            </div>
            <button type="submit" class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-semibold py-2 px-4 rounded-lg transition">Add Cost</button>
        </form>
    </div>
</div>
<div class="container mx-auto flex justify-center mt-6">
    <a href="{{ route('manufacturer.production-costs.index') }}" class="inline-block bg-gray-200 hover:bg-gray-300 text-gray-800 font-semibold py-2 px-4 rounded-lg shadow transition">&larr; Back to Costs</a>
</div>
@endsection 