@extends('manufacturer.layouts.dashboard')
@section('content')
<div class="container mx-auto py-8 flex justify-center">
    <div class="w-full max-w-lg bg-white rounded-lg shadow-lg p-8">
        <h1 class="text-3xl font-bold text-black mb-6 text-center">Create New Work Order</h1>
        <form method="POST" action="{{ route('manufacturer.production.store') }}">
            @csrf
            <div class="mb-5">
                <label class="block font-semibold text-black mb-2">Quantity</label>
                <input type="number" name="quantity" class="w-full border border-gray-300 rounded-lg p-2 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition" min="1" required>
            </div>
            <div class="mb-5">
                <label class="block font-semibold text-black mb-2">Scheduled Start</label>
                <input type="datetime-local" name="scheduled_start" class="w-full border border-gray-300 rounded-lg p-2 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition" required>
            </div>
            <div class="mb-5">
                <label class="block font-semibold text-black mb-2">Scheduled End</label>
                <input type="datetime-local" name="scheduled_end" class="w-full border border-gray-300 rounded-lg p-2 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition" required>
            </div>
            <div class="mb-5">
                <label class="block font-semibold text-black mb-2">Notes</label>
                <textarea name="notes" class="w-full border border-gray-300 rounded-lg p-2 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition"></textarea>
            </div>
            <button type="submit" class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-semibold py-2 px-4 rounded-lg transition">Create Work Order</button>
        </form>
    </div>
</div>
<div class="container mx-auto flex justify-center mt-6">
    <a href="{{ route('manufacturer.production.index') }}" class="inline-block bg-gray-200 hover:bg-gray-300 text-gray-800 font-semibold py-2 px-4 rounded-lg shadow transition">&larr; Back to Work Orders</a>
</div>
@endsection 