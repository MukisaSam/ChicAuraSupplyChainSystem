@extends('manufacturer.layouts.dashboard')
@section('content')
<div class="container mx-auto py-6">
    <h1 class="text-2xl font-bold text-white mb-4">Edit Downtime Log</h1>
    <form method="POST" action="{{ route('manufacturer.downtime-logs.update', $downtimeLog->id) }}" class="max-w-lg bg-white p-6 rounded shadow">
        @csrf
        @method('PUT')
        <div class="mb-4">
            <label class="block font-semibold mb-1">Reason</label>
            <input type="text" name="reason" class="w-full border rounded p-2" value="{{ $downtimeLog->reason }}" required>
        </div>
        <div class="mb-4">
            <label class="block font-semibold mb-1">Start Time</label>
            <input type="datetime-local" name="start_time" class="w-full border rounded p-2" value="{{ date('Y-m-d\TH:i', strtotime($downtimeLog->start_time)) }}" required>
        </div>
        <div class="mb-4">
            <label class="block font-semibold mb-1">End Time</label>
            <input type="datetime-local" name="end_time" class="w-full border rounded p-2" value="{{ date('Y-m-d\TH:i', strtotime($downtimeLog->end_time)) }}" required>
        </div>
        <div class="mb-4">
            <label class="block font-semibold mb-1">Notes</label>
            <textarea name="notes" class="w-full border rounded p-2">{{ $downtimeLog->notes }}</textarea>
        </div>
        <button type="submit" class="bg-indigo-600 text-white px-4 py-2 rounded">Update Log</button>
        <a href="{{ route('manufacturer.downtime-logs.index') }}" class="ml-4 text-gray-600 hover:underline">Cancel</a>
    </form>
</div>
@endsection 