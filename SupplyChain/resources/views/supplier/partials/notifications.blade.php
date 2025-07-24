<div x-data="{ open: false }" class="relative">
    <button @click="open = !open" class="relative focus:outline-none">
        <i class="fas fa-bell text-xl"></i>
        @if(Auth::user()->unreadNotifications->count() > 0)
            <span class="absolute -top-2 -right-2 bg-red-500 text-white rounded-full px-2 py-0.5 text-xs font-bold">
                {{ Auth::user()->unreadNotifications->count() }}
            </span>
        @endif
    </button>
    <div x-show="open" @click.away="open = false" class="absolute right-0 mt-2 w-80 bg-white dark:bg-gray-800 rounded-xl shadow-lg z-50 border border-gray-200 dark:border-gray-700" style="display: none;" x-transition>
        <div class="p-4 border-b border-gray-100 dark:border-gray-700 font-semibold text-gray-700 dark:text-gray-200">Notifications</div>
        <ul class="max-h-80 overflow-y-auto">
            @forelse(Auth::user()->notifications->take(10) as $notification)
                <li class="px-4 py-3 border-b border-gray-100 dark:border-gray-700 flex items-start gap-2">
                    <div class="flex-1">
                        <div class="text-sm {{ !$notification->read_at ? 'font-bold' : 'font-normal' }} text-gray-800 dark:text-gray-100">
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
                <li class="px-4 py-6 text-center text-gray-400">No notifications found.</li>
            @endforelse
            <a href="{{ route('supplier.notifications.index') }}" class="ml-2 text-xs text-green-600 hover:underline font-semibold flex items-center">
                View All
            </a>
        </ul>
    </div>
</div>
