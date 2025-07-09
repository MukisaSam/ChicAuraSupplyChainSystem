@extends('manufacturer.layouts.dashboard')
@section('content')
<div class="container mx-auto py-6">
    <h1 class="text-2xl font-bold mb-4">Create Quality Check</h1>
    <form method="POST" action="{{ route('manufacturer.quality.store') }}">
        @csrf
        <div class="mb-4">
            <label class="block font-semibold mb-1">Work Order</label>
            <select name="work_order_id" class="w-full border rounded p-2" required>
                <option value="">Select work order</option>
                @foreach($workOrders as $order)
                    <option value="{{ $order->id }}">#{{ $order->id }} - {{ $order->product->name ?? 'Product' }}</option>
                @endforeach
            </select>
        </div>
        <div class="mb-4">
            <label class="block font-semibold mb-1">Stage</label>
            <input type="text" name="stage" class="w-full border rounded p-2" required>
        </div>
        <div class="mb-4">
            <label class="block font-semibold mb-1">Result</label>
            <select name="result" class="w-full border rounded p-2" required>
                <option value="Pass">Pass</option>
                <option value="Fail">Fail</option>
                <option value="Rework">Rework</option>
            </select>
        </div>
        <div class="mb-4">
            <label class="block font-semibold mb-1">Checked By</label>
            <select name="checked_by" class="w-full border rounded p-2" required>
                <option value="">Select employee</option>
                @foreach($workforce as $employee)
                    <option value="{{ $employee->id }}">{{ $employee->fullname }}</option>
                @endforeach
            </select>
        </div>
        <div class="mb-4">
            <label class="block font-semibold mb-1">Checked At</label>
            <input type="datetime-local" name="checked_at" class="w-full border rounded p-2" required>
        </div>
        <div class="mb-4">
            <label class="block font-semibold mb-1">Notes</label>
            <textarea name="notes" class="w-full border rounded p-2"></textarea>
        </div>
        <button type="submit" class="bg-indigo-600 text-white px-4 py-2 rounded">Create Quality Check</button>
    </form>
</div>
@endsection 