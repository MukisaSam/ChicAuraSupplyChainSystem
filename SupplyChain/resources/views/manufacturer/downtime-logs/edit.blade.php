@extends('manufacturer.layouts.dashboard')
@section('content')
<div class="container mx-auto py-8 flex justify-center">
    <div class="w-full max-w-lg bg-white rounded-lg shadow-lg p-8">
        <h1 class="text-2xl font-bold mb-6 text-black">Edit Downtime Log</h1>
        <form method="POST" action="{{ route('manufacturer.downtime-logs.update', $downtimeLog->id) }}">
            @csrf
            @method('PUT')
            <div class="mb-5">
                <label class="block font-semibold text-gray-700 mb-2">Reason</label>
                <input type="text" name="reason" class="w-full border border-gray-300 rounded-lg p-2 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition" value="{{ $downtimeLog->reason }}" required>
            </div>
            <div class="mb-5">
                <label class="block font-semibold text-gray-700 mb-2">Start Time</label>
                <input type="datetime-local" name="start_time" class="w-full border border-gray-300 rounded-lg p-2 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition" value="{{ date('Y-m-d\TH:i', strtotime($downtimeLog->start_time)) }}" required>
            </div>
            <div class="mb-5">
                <label class="block font-semibold text-gray-700 mb-2">End Time</label>
                <input type="datetime-local" name="end_time" class="w-full border border-gray-300 rounded-lg p-2 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition" value="{{ date('Y-m-d\TH:i', strtotime($downtimeLog->end_time)) }}" required>
            </div>
            <div class="mb-5">
                <label class="block font-semibold text-gray-700 mb-2">Notes</label>
                <textarea name="notes" class="w-full border border-gray-300 rounded-lg p-2 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition">{{ $downtimeLog->notes }}</textarea>
            </div>
            <div class="flex justify-between mt-6">
                <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white font-semibold py-2 px-4 rounded-lg transition">Update Log</button>
                <a href="{{ route('manufacturer.downtime-logs.index') }}" class="bg-gray-200 hover:bg-gray-300 text-gray-800 font-semibold py-2 px-4 rounded-lg transition">Cancel</a>
            </div>
        </form>
    </div>
</div>
<div class="container mx-auto flex justify-center mt-6">
    <a href="{{ route('manufacturer.downtime-logs.index') }}" class="inline-block bg-gray-200 hover:bg-gray-300 text-gray-800 font-semibold py-2 px-4 rounded-lg shadow transition">&larr; Back to Logs</a>
</div>
@endsection 