@extends('manufacturer.layouts.dashboard')
@section('content')
<div class="container mx-auto py-6">
    <h1 class="text-2xl font-bold mb-4">Create Downtime Log</h1>
    <form method="POST" action="{{ route('manufacturer.downtime-logs.store') }}" class="max-w-lg bg-white p-6 rounded shadow">
        @csrf
        <div class="mb-4">
            <label class="block font-semibold mb-1">Reason</label>
            <input type="text" name="reason" class="w-full border rounded p-2" required>
        </div>
        <div class="mb-4">
            <label class="block font-semibold mb-1">Start Time</label>
            <input type="datetime-local" name="start_time" class="w-full border rounded p-2" required>
        </div>
        <div class="mb-4">
            <label class="block font-semibold mb-1">End Time</label>
            <input type="datetime-local" name="end_time" class="w-full border rounded p-2" required>
        </div>
        <div class="mb-4">
            <label class="block font-semibold mb-1">Notes</label>
            <textarea name="notes" class="w-full border rounded p-2"></textarea>
        </div>
        <button type="submit" class="bg-indigo-600 text-white px-4 py-2 rounded">Create Log</button>
    </form>
</div>
@endsection 