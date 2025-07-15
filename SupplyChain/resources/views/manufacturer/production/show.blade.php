@extends('manufacturer.layouts.dashboard')
@section('content')
<div class="container mx-auto py-8 flex justify-center">
    <div class="w-full max-w-2xl bg-white rounded-lg shadow-lg p-8">
        <h1 class="text-3xl font-bold text-gray-800 mb-6 text-center">Work Order Details</h1>
        <div class="space-y-4">
            <div>
                <h2 class="text-lg font-semibold text-gray-700 mb-1">Product:</h2>
                <p class="text-gray-800">{{ $workOrder->product->name ?? '-' }}</p>
            </div>
            <div>
                <h2 class="text-lg font-semibold text-gray-700 mb-1">Quantity:</h2>
                <p class="text-gray-800">{{ $workOrder->quantity }}</p>
            </div>
            <div>
                <h2 class="text-lg font-semibold text-gray-700 mb-1">Status:</h2>
                <p class="text-gray-800">{{ $workOrder->status }}</p>
            </div>
            <div>
                <h2 class="text-lg font-semibold text-gray-700 mb-1">Scheduled:</h2>
                <p class="text-gray-800">{{ $workOrder->scheduled_start ? \Carbon\Carbon::parse($workOrder->scheduled_start)->format('Y-m-d H:i') : '-' }} to {{ $workOrder->scheduled_end ? \Carbon\Carbon::parse($workOrder->scheduled_end)->format('Y-m-d H:i') : '-' }}</p>
            </div>
            @if($workOrder->notes)
            <div>
                <h2 class="text-lg font-semibold text-gray-700 mb-1">Notes:</h2>
                <p class="text-gray-800">{{ $workOrder->notes }}</p>
            </div>
            @endif
            <div>
                <h2 class="text-lg font-semibold text-gray-700 mt-4 mb-1">Assignments:</h2>
                @if($workOrder->assignments->count())
                    <ul class="list-disc list-inside text-gray-800">
                        @foreach($workOrder->assignments as $assignment)
                            <li class="mb-1 flex items-center justify-between">
                                <span>{{ $assignment->workforce->fullname ?? '-' }} ({{ $assignment->role }}) <span class="text-gray-500">[Assigned: {{ $assignment->assigned_at ? \Carbon\Carbon::parse($assignment->assigned_at)->format('Y-m-d H:i') : '-' }}]</span></span>
                                <form action="{{ route('manufacturer.production.assignment.destroy', $assignment->id) }}" method="POST" onsubmit="return confirm('Remove this assignment?');" class="ml-4 inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded shadow">Remove</button>
                                </form>
                            </li>
                        @endforeach
                    </ul>
                @else
                    <p class="text-gray-500">No assignments.</p>
                @endif
                <div class="mt-3">
                    <a href="{{ route('manufacturer.production.assign-workforce', $workOrder->id) }}" class="inline-block bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-4 rounded-lg shadow transition">Assign Workforce</a>
                </div>
            </div>
            <div>
                <h2 class="text-lg font-semibold text-gray-700 mt-4 mb-1">Quality Checks:</h2>
                @if($workOrder->qualityChecks->count())
                    <ul class="list-disc list-inside text-gray-800">
                        @foreach($workOrder->qualityChecks as $qc)
                            <li class="mb-1">Stage: {{ $qc->stage }}, Result: {{ $qc->result }}, By: {{ $qc->checker->fullname ?? '-' }}, At: {{ $qc->checked_at ? \Carbon\Carbon::parse($qc->checked_at)->format('Y-m-d H:i') : '-' }}</li>
                        @endforeach
                    </ul>
                @else
                    <p class="text-gray-500">No quality checks.</p>
                @endif
            </div>
            <div>
                <h2 class="text-lg font-semibold text-gray-700 mt-4 mb-1">Downtime Logs:</h2>
                @if($workOrder->downtimeLogs->count())
                    <ul class="list-disc list-inside text-gray-800">
                        @foreach($workOrder->downtimeLogs as $log)
                            <li class="mb-1">{{ $log->reason }}: {{ $log->start_time ? \Carbon\Carbon::parse($log->start_time)->format('Y-m-d H:i') : '-' }} to {{ $log->end_time ? \Carbon\Carbon::parse($log->end_time)->format('Y-m-d H:i') : '-' }} @if($log->notes) ({{ $log->notes }}) @endif</li>
                        @endforeach
                    </ul>
                @else
                    <p class="text-gray-500">No downtime logs.</p>
                @endif
            </div>
            <div>
                <h2 class="text-lg font-semibold text-gray-700 mt-4 mb-1">Production Cost:</h2>
                @if($workOrder->productionCost)
                    <ul class="text-gray-800">
                        <li>Material: ${{ number_format($workOrder->productionCost->material_cost, 2) }}</li>
                        <li>Labor: ${{ number_format($workOrder->productionCost->labor_cost, 2) }}</li>
                        <li>Overhead: ${{ number_format($workOrder->productionCost->overhead_cost, 2) }}</li>
                        <li class="font-semibold">Total: ${{ number_format($workOrder->productionCost->total_cost, 2) }}</li>
                    </ul>
                @else
                    <p class="text-gray-500">No production cost recorded.</p>
                @endif
            </div>
        </div>
        <div class="mt-8 text-center">
            <a href="{{ route('manufacturer.production.index') }}" class="inline-block bg-indigo-600 hover:bg-indigo-700 text-white font-semibold py-2 px-6 rounded-lg shadow transition">&larr; Back to Work Orders</a>
        </div>
    </div>
</div>
@endsection 