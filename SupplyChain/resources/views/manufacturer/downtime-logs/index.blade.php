@extends('manufacturer.layouts.dashboard')
@section('content')
<div class="container mx-auto py-6">
    <h1 class="text-2xl font-bold mb-4">Downtime Logs</h1>
    <a href="{{ route('manufacturer.downtime-logs.create') }}" class="bg-indigo-600 text-white px-4 py-2 rounded mb-4 inline-block">Create New Log</a>
    <table class="min-w-full table-auto bg-white rounded shadow">
        <thead>
            <tr>
                <th class="px-4 py-2">#</th>
                <th class="px-4 py-2">Reason</th>
                <th class="px-4 py-2">Start Time</th>
                <th class="px-4 py-2">End Time</th>
                <th class="px-4 py-2">Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($logs as $log)
            <tr>
                <td class="border px-4 py-2">{{ $log->id }}</td>
                <td class="border px-4 py-2">{{ $log->reason }}</td>
                <td class="border px-4 py-2">{{ $log->start_time }}</td>
                <td class="border px-4 py-2">{{ $log->end_time }}</td>
                <td class="border px-4 py-2">
                    <a href="{{ route('manufacturer.downtime-logs.show', $log->id) }}" class="text-blue-600 hover:underline mr-2">View</a>
                    <a href="{{ route('manufacturer.downtime-logs.edit', $log->id) }}" class="text-yellow-600 hover:underline mr-2">Edit</a>
                    <form action="{{ route('manufacturer.downtime-logs.destroy', $log->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Are you sure you want to delete this log?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="text-red-600 hover:underline bg-transparent border-0 p-0 m-0 cursor-pointer">Delete</button>
                    </form>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="5" class="text-center text-gray-400 py-4">No downtime logs found.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
    <div class="mt-4">{{ $logs->links() }}</div>
</div>
@endsection 