@extends('layouts.admin-dashboard')

@section('title', 'Notifications')

@section('content')
<div class="p-6">
    <h1 class="text-2xl font-bold mb-4">Notifications</h1>

    <div class="space-y-4">
        @forelse ($notifications as $notification)
            <div class="bg-white rounded-2xl shadow p-4 flex items-start space-x-3">
                <i class="fas fa-bell text-blue-500 mt-1"></i>
                <div>
                    <p class="font-semibold">
                        {{ $notification->data['message'] ?? ($notification->data['title'] ?? 'Notification') }}
                    </p>
                    @if(isset($notification->data['description']))
                        <p class="text-sm text-gray-600">{{ $notification->data['description'] }}</p>
                    @endif
                    @if(isset($notification->data['order_number']))
                        <p class="text-xs text-gray-500">Order #: {{ $notification->data['order_number'] }}</p>
                    @endif
                    @if(isset($notification->data['item_name']))
                        <p class="text-xs text-gray-500">Item: {{ $notification->data['item_name'] }} (Qty: {{ $notification->data['stock_quantity'] ?? '-' }})</p>
                    @endif
                    @if(isset($notification->data['sender_name']))
                        <p class="text-xs text-gray-500">From: {{ $notification->data['sender_name'] }}</p>
                    @endif
                    <span class="text-xs text-gray-400">{{ $notification->created_at->diffForHumans() }}</span>
                </div>
            </div>
        @empty
            <p class="text-gray-500">No new notifications.</p>
        @endforelse
    </div>
</div>
@endsection
