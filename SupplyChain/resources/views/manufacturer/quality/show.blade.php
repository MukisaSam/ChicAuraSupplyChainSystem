@extends('manufacturer.layouts.dashboard')
@section('content')
<div class="container mx-auto py-8 flex justify-center">
    <div class="w-full max-w-lg bg-white rounded-lg shadow-lg p-8">
        <h1 class="text-3xl font-bold text-gray-800 mb-6 text-center">Quality Check Details</h1>
        <div class="mb-4">
            <span class="block text-gray-700 font-semibold mb-1">Work Order:</span>
            <span class="text-gray-800">{{ $qualityCheck->workOrder->id ?? '-' }}</span>
        </div>
        <div class="mb-4">
            <span class="block text-gray-700 font-semibold mb-1">Product:</span>
            <span class="text-gray-800">{{ $qualityCheck->workOrder->product->name ?? '-' }}</span>
        </div>
        <div class="mb-4">
            <span class="block text-gray-700 font-semibold mb-1">Stage:</span>
            <span class="text-gray-800">{{ $qualityCheck->stage }}</span>
        </div>
        <div class="mb-4">
            <span class="block text-gray-700 font-semibold mb-1">Result:</span>
            <span class="text-gray-800">{{ $qualityCheck->result }}</span>
        </div>
        <div class="mb-4">
            <span class="block text-gray-700 font-semibold mb-1">Checked By:</span>
            <span class="text-gray-800">{{ $qualityCheck->checker->fullname ?? '-' }}</span>
        </div>
        <div class="mb-4">
            <span class="block text-gray-700 font-semibold mb-1">Checked At:</span>
            <span class="text-gray-800">{{ $qualityCheck->checked_at ? \Carbon\Carbon::parse($qualityCheck->checked_at)->format('Y-m-d H:i') : '-' }}</span>
        </div>
        @if($qualityCheck->notes)
        <div class="mb-4">
            <span class="block text-gray-700 font-semibold mb-1">Notes:</span>
            <span class="text-gray-800">{{ $qualityCheck->notes }}</span>
        </div>
        @endif
    </div>
</div>
<div class="container mx-auto flex justify-center mt-6">
    <a href="{{ route('manufacturer.quality.index') }}" class="inline-block bg-gray-200 hover:bg-gray-300 text-gray-800 font-semibold py-2 px-4 rounded-lg shadow transition">&larr; Back to Quality Checks</a>
</div>
@endsection 