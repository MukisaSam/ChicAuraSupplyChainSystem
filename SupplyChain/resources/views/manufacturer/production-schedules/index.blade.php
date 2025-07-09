@extends('manufacturer.layouts.dashboard')
@section('content')
<div class="container mx-auto py-6">
    <h1 class="text-2xl font-bold mb-4">Production Schedules</h1>
    <a href="{{ route('manufacturer.production-schedules.create') }}" class="bg-indigo-600 text-white px-4 py-2 rounded mb-4 inline-block">Create New Schedule</a>
    <table class="min-w-full table-auto bg-white rounded shadow">
        <thead>
            <tr>
                <th class="px-4 py-2">#</th>
                <th class="px-4 py-2">Product</th>
                <th class="px-4 py-2">Start Date</th>
                <th class="px-4 py-2">End Date</th>
                <th class="px-4 py-2">Status</th>
                <th class="px-4 py-2">Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($schedules as $schedule)
            <tr>
                <td class="border px-4 py-2">{{ $schedule->id }}</td>
                <td class="border px-4 py-2">{{ $schedule->product->name ?? '-' }}</td>
                <td class="border px-4 py-2">{{ $schedule->start_date }}</td>
                <td class="border px-4 py-2">{{ $schedule->end_date }}</td>
                <td class="border px-4 py-2">{{ ucfirst($schedule->status) }}</td>
                <td class="border px-4 py-2">
                    <a href="{{ route('manufacturer.production-schedules.show', $schedule->id) }}" class="text-blue-600 hover:underline mr-2">View</a>
                    <a href="{{ route('manufacturer.production-schedules.edit', $schedule->id) }}" class="text-yellow-600 hover:underline mr-2">Edit</a>
                    <form action="{{ route('manufacturer.production-schedules.destroy', $schedule->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Are you sure you want to delete this schedule?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="text-red-600 hover:underline bg-transparent border-0 p-0 m-0 cursor-pointer">Delete</button>
                    </form>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="6" class="text-center text-gray-400 py-4">No production schedules found.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
    <div class="mt-4">{{ $schedules->links() }}</div>
</div>
@endsection 