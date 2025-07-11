@extends('manufacturer.layouts.dashboard')
@section('content')
<div class="container mx-auto py-8 flex justify-center">
    <div class="w-full max-w-lg bg-white rounded-lg shadow-lg p-8">
        <h1 class="text-3xl font-bold text-gray-800 mb-6 text-center">Edit Production Schedule</h1>
        <form method="POST" action="{{ route('manufacturer.production-schedules.update', $productionSchedule->id) }}">
            @csrf
            @method('PUT')
            <div class="mb-5">
                <label class="block font-semibold text-gray-700 mb-2">Product</label>
                <select name="product_id" class="w-full border border-gray-300 rounded-lg p-2 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition" required>
                    <option value="">Select a product</option>
                    @foreach(App\Models\Item::where('type', 'finished_product')->get() as $product)
                        <option value="{{ $product->id }}" @if($product->id == $productionSchedule->product_id) selected @endif>{{ $product->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="mb-5">
                <label class="block font-semibold text-gray-700 mb-2">Start Date</label>
                <input type="date" name="start_date" class="w-full border border-gray-300 rounded-lg p-2 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition" value="{{ $productionSchedule->start_date }}" required>
            </div>
            <div class="mb-5">
                <label class="block font-semibold text-gray-700 mb-2">End Date</label>
                <input type="date" name="end_date" class="w-full border border-gray-300 rounded-lg p-2 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition" value="{{ $productionSchedule->end_date }}" required>
            </div>
            <div class="mb-5">
                <label class="block font-semibold text-gray-700 mb-2">Status</label>
                <select name="status" class="w-full border border-gray-300 rounded-lg p-2 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition" required>
                    <option value="planned" @if($productionSchedule->status == 'planned') selected @endif>Planned</option>
                    <option value="in_progress" @if($productionSchedule->status == 'in_progress') selected @endif>In Progress</option>
                    <option value="completed" @if($productionSchedule->status == 'completed') selected @endif>Completed</option>
                </select>
            </div>
            <div class="flex justify-between mt-6">
                <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white font-semibold py-2 px-4 rounded-lg transition">Update Schedule</button>
                <a href="{{ route('manufacturer.production-schedules.index') }}" class="bg-gray-200 hover:bg-gray-300 text-gray-800 font-semibold py-2 px-4 rounded-lg transition">Cancel</a>
            </div>
        </form>
    </div>
</div>
<div class="container mx-auto flex justify-center mt-6">
    <a href="{{ route('manufacturer.production-schedules.index') }}" class="inline-block bg-gray-200 hover:bg-gray-300 text-gray-800 font-semibold py-2 px-4 rounded-lg shadow transition">&larr; Back to Schedules</a>
</div>
@endsection 