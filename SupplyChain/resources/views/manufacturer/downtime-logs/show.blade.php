@extends('manufacturer.layouts.dashboard')
@section('content')
<div class="container mx-auto py-8 flex justify-center">
    <div class="w-full max-w-lg bg-white rounded-lg shadow-lg p-8">
        <h1 class="text-3xl font-bold text-gray-800 mb-6 text-center">Downtime Log Details</h1>
        <div class="mb-4">
            <span class="block text-gray-700 font-semibold mb-1">Reason:</span>
            <span class="text-gray-800">{{ $downtimeLog->reason }}</span>
        </div>
        <div class="mb-4">
            <span class="block text-gray-700 font-semibold mb-1">Start Time:</span>
            <span class="text-gray-800">{{ $downtimeLog->start_time }}</span>
        </div>
        <div class="mb-4">
            <span class="block text-gray-700 font-semibold mb-1">End Time:</span>
            <span class="text-gray-800">{{ $downtimeLog->end_time }}</span>
        </div>
        <div class="mb-4">
            <span class="block text-gray-700 font-semibold mb-1">Notes:</span>
            <span class="text-gray-800">{{ $downtimeLog->notes ?? '-' }}</span>
        </div>
        <div class="flex flex-wrap gap-4 mt-6">
            <a href="{{ route('manufacturer.downtime-logs.edit', $downtimeLog->id) }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded transition">Edit</a>
            <form action="{{ route('manufacturer.downtime-logs.destroy', $downtimeLog->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Are you sure you want to delete this log?');">
                @csrf
                @method('DELETE')
                <button type="submit" class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded transition">Delete</button>
            </form>
        </div>
    </div>
</div>
<div class="container mx-auto flex justify-center mt-6">
    <a href="{{ route('manufacturer.downtime-logs.index') }}" class="inline-block bg-gray-200 hover:bg-gray-300 text-gray-800 font-semibold py-2 px-4 rounded-lg shadow transition">&larr; Back to Logs</a>
</div>
@endsection 