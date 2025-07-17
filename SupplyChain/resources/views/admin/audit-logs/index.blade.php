@extends('layouts.admin-dashboard')

@section('title', 'Audit Logs')

@section('content')
<div class="p-6">
    <h1 class="text-2xl font-bold mb-4">Audit Logs</h1>

    <!-- Filters -->
    <form method="GET" class="mb-4 flex flex-wrap gap-4 items-end">
        <div>
            <label class="block text-xs font-semibold mb-1">User</label>
            <select name="user_id" class="rounded border-gray-300">
                <option value="">All</option>
                @foreach($users as $user)
                    <option value="{{ $user->id }}" @if(request('user_id') == $user->id) selected @endif>
                        {{ $user->name }}
                    </option>
                @endforeach
            </select>
        </div>
        <div>
            <label class="block text-xs font-semibold mb-1">Action</label>
            <input type="text" name="action" value="{{ request('action') }}" class="rounded border-gray-300" placeholder="Action">
        </div>
        <div>
            <label class="block text-xs font-semibold mb-1">Date</label>
            <input type="date" name="date" value="{{ request('date') }}" class="rounded border-gray-300">
        </div>
        <div>
            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Filter</button>
            <a href="{{ route('admin.audit-logs.index') }}" class="ml-2 text-gray-600 underline">Reset</a>
        </div>
    </form>

    <div class="overflow-x-auto rounded-2xl shadow">
        <table class="w-full bg-white text-sm">
            <thead>
                <tr class="bg-gray-100 text-left">
                    <th class="p-3">Date</th>
                    <th class="p-3">User</th>
                    <th class="p-3">Action</th>
                    <th class="p-3">Details</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($logs as $log)
                <tr class="border-t hover:bg-gray-50">
                    <td class="p-3">{{ $log->created_at->format('Y-m-d H:i') }}</td>
                    <td class="p-3">{{ $log->user->name ?? 'N/A' }}</td>
                    <td class="p-3">{{ $log->action }}</td>
                    <td class="p-3">{{ $log->details }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" class="p-3 text-center text-gray-500">No audit logs found.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        {{ $logs->links() }}
    </div>
</div>
@endsection
