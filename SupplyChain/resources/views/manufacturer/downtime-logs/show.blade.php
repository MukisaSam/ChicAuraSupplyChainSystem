@extends('manufacturer.layouts.dashboard')
@section('content')
<div class="container mx-auto py-6">
    <h1 class="text-2xl font-bold text-white mb-4">Downtime Log Details</h1>
    <div class="text-white mb-4">
        <strong>Reason:</strong> {{ $downtimeLog->reason }}
    </div>
    <div class="text-white mb-4">
        <strong>Start Time:</strong> {{ $downtimeLog->start_time }}
    </div>
    <div class="text-white mb-4">
        <strong>End Time:</strong> {{ $downtimeLog->end_time }}
    </div>
    <div class="text-white mb-4">
        <strong>Notes:</strong> {{ $downtimeLog->notes ?? '-' }}
    </div>
    <a href="{{ route('manufacturer.downtime-logs.edit', $downtimeLog->id) }}" class="bg-blue-600 text-white px-4 py-2 rounded">Edit</a>
    <form action="{{ route('manufacturer.downtime-logs.destroy', $downtimeLog->id) }}" method="POST" class="inline-block ml-2" onsubmit="return confirm('Are you sure you want to delete this log?');">
        @csrf
        @method('DELETE')
        <button type="submit" class="bg-red-600 text-white px-4 py-2 rounded">Delete</button>
    </form>
    <a href="{{ route('manufacturer.downtime-logs.index') }}" class="ml-4 text-gray-600 hover:underline">Back to List</a>
</div>
@endsection 