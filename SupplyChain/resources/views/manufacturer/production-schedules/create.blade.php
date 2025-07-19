@extends('manufacturer.layouts.dashboard')
@section('content')
<div class="container mx-auto py-8 flex justify-center">
    <div class="w-full max-w-lg bg-white rounded-lg shadow-lg p-8">
        <h1 class="text-2xl font-bold mb-6 text-black">Create Production Schedule</h1>
        <form method="POST" action="{{ route('manufacturer.production-schedules.store') }}">
            @csrf
            <div class="mb-5">
                <label class="block font-semibold text-gray-700 mb-2">Work Order</label>
                <select name="work_order_id" class="w-full border border-gray-300 rounded-lg p-2 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition" required>
                    <option value="">Select a work order</option>
                    @foreach($workOrders as $workOrder)
                        <option value="{{ $workOrder->id }}">#{{ $workOrder->id }} - {{ $workOrder->product->name ?? 'Product' }}</option>
                    @endforeach
                </select>
            </div>
            <div class="mb-5">
                <label class="block font-semibold text-gray-700 mb-2">Start Date</label>
                <input type="date" name="start_date" class="w-full border border-gray-300 rounded-lg p-2 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition" required>
            </div>
            <div class="mb-5">
                <label class="block font-semibold text-gray-700 mb-2">End Date</label>
                <input type="date" name="end_date" class="w-full border border-gray-300 rounded-lg p-2 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition" required>
            </div>
            <div class="mb-5">
                <label class="block font-semibold text-gray-700 mb-2">Status</label>
                <select name="status" class="w-full border border-gray-300 rounded-lg p-2 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition" required>
                    <option value="planned">Planned</option>
                    <option value="in_progress">In Progress</option>
                    <option value="completed">Completed</option>
                </select>
            </div>
            <button type="submit" class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-semibold py-2 px-4 rounded-lg transition">Create Schedule</button>
        </form>
    </div>
</div>
<div class="container mx-auto flex justify-center mt-6">
    <a href="{{ route('manufacturer.production-schedules.index') }}" class="inline-block bg-gray-200 hover:bg-gray-300 text-gray-800 font-semibold py-2 px-4 rounded-lg shadow transition">&larr; Back to Schedules</a>
</div>
@endsection 