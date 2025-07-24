@extends('layouts.supplier-dashboard')

@section('title', 'Notifications')

@section('content')
<div class="p-6">
    <h1 class="text-2xl font-bold mb-6">Your Notifications</h1>
    @if(session('success'))
        <div class="mb-4 p-3 bg-green-100 text-green-800 rounded">{{ session('success') }}</div>
    @endif
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow p-4">
        <ul class="divide-y divide-gray-200 dark:divide-gray-700">
            @forelse($notifications as $notification)
                <li class="py-4 flex items-start gap-2 {{ $notification->read_at ? 'bg-white dark:bg-gray-800' : 'bg-blue-50 dark:bg-blue-900/20' }}">
                    <div class="flex-1">
                        <div class="text-sm text-gray-800 dark:text-gray-100 font-medium">
                            {{ $notification->data['message'] ?? 'New notification' }}
                        </div>
                        <div class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                            {{ $notification->created_at->diffForHumans() }}
                        </div>
                    </div>
                    @if(!$notification->read_at)
                        <form method="POST" action="{{ route('supplier.notifications.markRead', $notification->id) }}">
                            @csrf
                            <button type="submit" class="ml-2 text-xs text-blue-600 hover:underline">Mark as read</button>
                        </form>
                    @endif
                </li>
            @empty
                <li class="py-8 text-center text-gray-400">No notifications found.</li>
            @endforelse
        </ul>
        <div class="mt-4">
            {{ $notifications->links() }}
        </div>
    </div>
</div>
@endsection
