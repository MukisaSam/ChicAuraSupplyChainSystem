@extends('manufacturer.layouts.dashboard')
@section('content')
<div class="container mx-auto py-6">
    <h1 class="text-2xl font-bold mb-4">Quality Checks</h1>
    <a href="{{ route('manufacturer.quality.create') }}" class="bg-indigo-600 text-white px-4 py-2 rounded mb-4 inline-block">Create New Quality Check</a>
    <table class="min-w-full table-auto bg-white rounded shadow">
        <thead>
            <tr>
                <th class="px-4 py-2">#</th>
                <th class="px-4 py-2">Work Order</th>
                <th class="px-4 py-2">Product</th>
                <th class="px-4 py-2">Stage</th>
                <th class="px-4 py-2">Result</th>
                <th class="px-4 py-2">Checked By</th>
                <th class="px-4 py-2">Checked At</th>
                <th class="px-4 py-2">Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($qualityChecks as $qc)
            <tr>
                <td class="border px-4 py-2">{{ $qc->id }}</td>
                <td class="border px-4 py-2">#{{ $qc->workOrder->id ?? '-' }}</td>
                <td class="border px-4 py-2">{{ $qc->workOrder->product->name ?? '-' }}</td>
                <td class="border px-4 py-2">{{ $qc->stage }}</td>
                <td class="border px-4 py-2">{{ $qc->result }}</td>
                <td class="border px-4 py-2">{{ $qc->checker->fullname ?? '-' }}</td>
                <td class="border px-4 py-2">{{ $qc->checked_at ? \Carbon\Carbon::parse($qc->checked_at)->format('Y-m-d H:i') : '-' }}</td>
                <td class="border px-4 py-2">
                    <a href="{{ route('manufacturer.quality.show', $qc->id) }}" class="text-blue-600 hover:underline">View</a>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="8" class="text-center text-gray-400 py-4">No quality checks found.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
    <div class="mt-4">{{ $qualityChecks->links() }}</div>
</div>
@endsection 