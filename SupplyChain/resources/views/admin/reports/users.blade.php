@extends('layouts.admin-dashboard')

@section('content')
<div class="p-6 bg-gray-100 min-h-screen">
    <h1 class="text-2xl font-bold mb-4">User Activity Report (Last 7 Days)</h1>
    <p class="mb-2">Period: {{ $weekAgo->toDayDateTimeString() }} - {{ $now->toDayDateTimeString() }}</p>
    <p class="mb-4">
        <strong>New Users:</strong> {{ $newUsersCount }} |
        <strong>Active Wholesalers:</strong> {{ $activeWholesalers }} |
        <strong>Active Manufacturers:</strong> {{ $activeManufacturers }}
    </p>
    <h2 class="text-lg font-semibold mt-6 mb-2">New Users</h2>
    <table class="min-w-full bg-white border border-gray-300 mb-6">
        <thead>
            <tr>
                <th class="px-4 py-2 border">Name</th>
                <th class="px-4 py-2 border">Email</th>
                <th class="px-4 py-2 border">Registered At</th>
            </tr>
        </thead>
        <tbody>
            @forelse($newUsers as $user)
                <tr>
                    <td class="border px-4 py-2">{{ $user->name }}</td>
                    <td class="border px-4 py-2">{{ $user->email }}</td>
                    <td class="border px-4 py-2">{{ $user->created_at->toDayDateTimeString() }}</td>
                </tr>
            @empty
                <tr><td colspan="3" class="text-center py-4">No new users in the last 7 days.</td></tr>
            @endforelse
        </tbody>
    </table>
    <a href="{{ route('admin.reports.index') }}" class="text-blue-600 hover:underline">&larr; Back to Reports Center</a>
</div>
@endsection 