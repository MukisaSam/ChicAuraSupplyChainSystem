@extends('manufacturer.layouts.dashboard')
@section('content')
<div class="container mx-auto py-6">
    <h1 class="text-2xl font-bold text-white mb-4">Quality Check Details</h1>
    <div class="bg-white rounded shadow p-6 mb-4">
        <h2 class="text-lg font-semibold mb-2">Work Order:</h2>
        <p class="mb-2">{{ $qualityCheck->workOrder->id ?? '-' }}</p>
        <h2 class="text-lg font-semibold mb-2">Product:</h2>
        <p class="mb-2">{{ $qualityCheck->workOrder->product->name ?? '-' }}</p>
        <h2 class="text-lg font-semibold mb-2">Stage:</h2>
        <p class="mb-2">{{ $qualityCheck->stage }}</p>
        <h2 class="text-lg font-semibold mb-2">Result:</h2>
        <p class="mb-2">{{ $qualityCheck->result }}</p>
        <h2 class="text-lg font-semibold mb-2">Checked By:</h2>
        <p class="mb-2">{{ $qualityCheck->checker->fullname ?? '-' }}</p>
        <h2 class="text-lg font-semibold mb-2">Checked At:</h2>
        <p class="mb-2">{{ $qualityCheck->checked_at ? \Carbon\Carbon::parse($qualityCheck->checked_at)->format('Y-m-d H:i') : '-' }}</p>
        @if($qualityCheck->notes)
            <h2 class="text-lg font-semibold mb-2">Notes:</h2>
            <p>{{ $qualityCheck->notes }}</p>
        @endif
    </div>
    <a href="{{ route('manufacturer.quality.index') }}" class="text-white hover:underline">&larr; Back to Quality Checks</a>
</div>
@endsection 