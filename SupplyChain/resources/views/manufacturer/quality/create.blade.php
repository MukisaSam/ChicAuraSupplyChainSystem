@extends('manufacturer.layouts.dashboard')
@section('content')
<div class="container mx-auto py-8 flex justify-center">
    <div class="w-full max-w-lg bg-white rounded-lg shadow-lg p-8">
        <h1 class="text-3xl font-bold text-black mb-6 text-center">Create Quality Check</h1>
        <form method="POST" action="{{ route('manufacturer.quality.store') }}">
            @csrf
            <div class="mb-5">
                <label class="block font-semibold text-gray-700 mb-2">Work Order</label>
                <select name="work_order_id" class="w-full border border-gray-300 rounded-lg p-2 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition" required>
                    <option value="">Select work order</option>
                    @foreach($workOrders as $order)
                        <option value="{{ $order->id }}">{{ $order->id }} - {{ $order->product->name ?? 'Product' }}</option>
                    @endforeach
                </select>
            </div>
            <div class="mb-5">
                <label class="block font-semibold text-gray-700 mb-2">Stage</label>
                <input type="text" name="stage" class="w-full border border-gray-300 rounded-lg p-2 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition" required>
            </div>
            <div class="mb-5">
                <label class="block font-semibold text-gray-700 mb-2">Result</label>
                <select name="result" class="w-full border border-gray-300 rounded-lg p-2 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition" required>
                    <option value="Pass">Pass</option>
                    <option value="Fail">Fail</option>
                    <option value="Rework">Rework</option>
                </select>
            </div>
            <div class="mb-5">
                <label class="block font-semibold text-gray-700 mb-2">Checked By</label>
                <select name="checked_by" class="w-full border border-gray-300 rounded-lg p-2 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition" required>
                    <option value="">Select employee</option>
                    @foreach($workforce as $employee)
                        <option value="{{ $employee->id }}">{{ $employee->fullname }}</option>
                    @endforeach
                </select>
            </div>
            <div class="mb-5">
                <label class="block font-semibold text-gray-700 mb-2">Checked At</label>
                <input type="datetime-local" name="checked_at" class="w-full border border-gray-300 rounded-lg p-2 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition" required>
            </div>
            <div class="mb-5">
                <label class="block font-semibold text-gray-700 mb-2">Notes</label>
                <textarea name="notes" class="w-full border border-gray-300 rounded-lg p-2 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition"></textarea>
            </div>
            <button type="submit" class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-semibold py-2 px-4 rounded-lg transition">Create Quality Check</button>
        </form>
    </div>
</div>
<div class="container mx-auto flex justify-center mt-6">
    <a href="{{ route('manufacturer.quality.index') }}" class="inline-block bg-gray-200 hover:bg-gray-300 text-gray-800 font-semibold py-2 px-4 rounded-lg shadow transition">&larr; Back to Quality Checks</a>
</div>
@endsection 