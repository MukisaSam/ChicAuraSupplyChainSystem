@extends('manufacturer.layouts.dashboard')
@section('content')
<div class="container mx-auto py-6">
    <h1 class="text-2xl font-bold mb-4">Work Order Details</h1>
    <div class="bg-white rounded shadow p-6 mb-4">
        <h2 class="text-lg font-semibold mb-2">Product:</h2>
        <p class="mb-2">{{ $workOrder->product->name ?? '-' }}</p>
        <h2 class="text-lg font-semibold mb-2">Quantity:</h2>
        <p class="mb-2">{{ $workOrder->quantity }}</p>
        <h2 class="text-lg font-semibold mb-2">Status:</h2>
        <p class="mb-2">{{ $workOrder->status }}</p>
        <h2 class="text-lg font-semibold mb-2">Scheduled:</h2>
        <p class="mb-2">{{ $workOrder->scheduled_start ? \Carbon\Carbon::parse($workOrder->scheduled_start)->format('Y-m-d H:i') : '-' }} to {{ $workOrder->scheduled_end ? \Carbon\Carbon::parse($workOrder->scheduled_end)->format('Y-m-d H:i') : '-' }}</p>
        <h2 class="text-lg font-semibold mb-2">Actual:</h2>
        <p class="mb-2">{{ $workOrder->actual_start ? \Carbon\Carbon::parse($workOrder->actual_start)->format('Y-m-d H:i') : '-' }} to {{ $workOrder->actual_end ? \Carbon\Carbon::parse($workOrder->actual_end)->format('Y-m-d H:i') : '-' }}</p>
        @if($workOrder->notes)
            <h2 class="text-lg font-semibold mb-2">Notes:</h2>
            <p class="mb-2">{{ $workOrder->notes }}</p>
        @endif
        <h2 class="text-lg font-semibold mt-4 mb-2">Assignments:</h2>
        @if($workOrder->assignments->count())
            <ul class="mb-2">
                @foreach($workOrder->assignments as $assignment)
                    <li class="mb-1">- {{ $assignment->workforce->fullname ?? '-' }} ({{ $assignment->role }}) [Assigned: {{ $assignment->assigned_at ? \Carbon\Carbon::parse($assignment->assigned_at)->format('Y-m-d H:i') : '-' }}]</li>
                @endforeach
            </ul>
        @else
            <p class="mb-2 text-gray-500">No assignments.</p>
        @endif
        <h2 class="text-lg font-semibold mt-4 mb-2">Quality Checks:</h2>
        @if($workOrder->qualityChecks->count())
            <ul class="mb-2">
                @foreach($workOrder->qualityChecks as $qc)
                    <li class="mb-1">- Stage: {{ $qc->stage }}, Result: {{ $qc->result }}, By: {{ $qc->checker->fullname ?? '-' }}, At: {{ $qc->checked_at ? \Carbon\Carbon::parse($qc->checked_at)->format('Y-m-d H:i') : '-' }}</li>
                @endforeach
            </ul>
        @else
            <p class="mb-2 text-gray-500">No quality checks.</p>
        @endif
        <h2 class="text-lg font-semibold mt-4 mb-2">Downtime Logs:</h2>
        @if($workOrder->downtimeLogs->count())
            <ul class="mb-2">
                @foreach($workOrder->downtimeLogs as $log)
                    <li class="mb-1">- {{ $log->reason }}: {{ $log->start_time ? \Carbon\Carbon::parse($log->start_time)->format('Y-m-d H:i') : '-' }} to {{ $log->end_time ? \Carbon\Carbon::parse($log->end_time)->format('Y-m-d H:i') : '-' }} @if($log->notes) ({{ $log->notes }}) @endif</li>
                @endforeach
            </ul>
        @else
            <p class="mb-2 text-gray-500">No downtime logs.</p>
        @endif
        <h2 class="text-lg font-semibold mt-4 mb-2">Production Cost:</h2>
        @if($workOrder->productionCost)
            <ul class="mb-2">
                <li>Material: ${{ number_format($workOrder->productionCost->material_cost, 2) }}</li>
                <li>Labor: ${{ number_format($workOrder->productionCost->labor_cost, 2) }}</li>
                <li>Overhead: ${{ number_format($workOrder->productionCost->overhead_cost, 2) }}</li>
                <li class="font-semibold">Total: ${{ number_format($workOrder->productionCost->total_cost, 2) }}</li>
            </ul>
        @else
            <p class="mb-2 text-gray-500">No production cost recorded.</p>
        @endif
    </div>
    <a href="{{ route('manufacturer.production.index') }}" class="text-indigo-600 hover:underline">&larr; Back to Work Orders</a>
</div>
@endsection 