@extends('manufacturer.layouts.dashboard')
@section('content')

<div class="container mx-auto py-6 max-w-2xl">
    <div class="flex items-center justify-between mb-4">
        <h1 class="text-2xl font-bold text-white">Edit Quality Check</h1>
        <a href="{{ route('manufacturer.quality.index') }}" class="bg-gray-200 text-gray-800 px-3 py-1 rounded hover:bg-gray-300">&larr; Back</a>
    </div>

    @if ($errors->any())
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
            <strong>Whoops!</strong> There were some problems with your input.<br><br>
            <ul class="list-disc pl-5">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ (isset($qualityCheck) && $qualityCheck->id) ? route('manufacturer.quality.update', ['quality' => $qualityCheck->id]) : '#' }}" class="bg-white rounded shadow p-6">
        @csrf
        @method('PUT')
        <div class="mb-4">
            <label class="block font-semibold mb-1" for="work_order_id">Work Order</label>
            <select id="work_order_id" name="work_order_id" class="w-full border rounded p-2" required>
                <option value="">Select work order</option>
                @foreach($workOrders as $order)
                    <option value="{{ $order->id }}" {{ old('work_order_id', $qualityCheck->work_order_id ?? '') == $order->id ? 'selected' : '' }}>
                        {{ $order->id }} - {{ $order->product->name ?? 'Product' }}
                    </option>
                @endforeach
            </select>
        </div>
        <div class="mb-4">
            <label class="block font-semibold mb-1" for="stage">Stage</label>
            <input id="stage" type="text" name="stage" class="w-full border rounded p-2" value="{{ old('stage', $qualityCheck->stage ?? '') }}" required placeholder="e.g. Assembly, Inspection">
        </div>
        <div class="mb-4">
            <label class="block font-semibold mb-1" for="result">Result</label>
            <select id="result" name="result" class="w-full border rounded p-2" required>
                <option value="Pass" {{ old('result', $qualityCheck->result ?? '') == 'Pass' ? 'selected' : '' }}>Pass</option>
                <option value="Fail" {{ old('result', $qualityCheck->result ?? '') == 'Fail' ? 'selected' : '' }}>Fail</option>
                <option value="Rework" {{ old('result', $qualityCheck->result ?? '') == 'Rework' ? 'selected' : '' }}>Rework</option>
            </select>
        </div>
        <div class="mb-4">
            <label class="block font-semibold mb-1" for="checked_by">Checked By</label>
            <select id="checked_by" name="checked_by" class="w-full border rounded p-2" required>
                <option value="">Select employee</option>
                @foreach($workforce as $employee)
                    <option value="{{ $employee->id }}" {{ old('checked_by', $qualityCheck->checked_by ?? '') == $employee->id ? 'selected' : '' }}>
                        {{ $employee->fullname }}
                    </option>
                @endforeach
            </select>
        </div>
        <div class="mb-4">
            <label class="block font-semibold mb-1" for="checked_at">Checked At</label>
            <input id="checked_at" type="datetime-local" name="checked_at" class="w-full border rounded p-2" value="{{ old('checked_at', isset($qualityCheck->checked_at) ? \Carbon\Carbon::parse($qualityCheck->checked_at)->format('Y-m-d\TH:i') : '' ) }}" required>
        </div>
        <div class="mb-4">
            <label class="block font-semibold mb-1" for="notes">Notes</label>
            <textarea id="notes" name="notes" class="w-full border rounded p-2" rows="3" placeholder="Additional notes (optional)">{{ old('notes', $qualityCheck->notes ?? '') }}</textarea>
        </div>
        <div class="flex justify-end">
            <button type="submit" class="bg-indigo-600 text-white px-6 py-2 rounded hover:bg-indigo-700">Update Quality Check</button>
        </div>
    </form>
</div>
@endsection 