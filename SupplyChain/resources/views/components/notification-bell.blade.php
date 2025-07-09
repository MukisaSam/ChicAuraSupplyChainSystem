@php $bellId = uniqid('notificationBell_'); @endphp
<div class="relative">
    <button id="{{ $bellId }}Btn" class="p-2 text-gray-500 hover:text-indigo-600 hover:bg-indigo-50 rounded-full transition-colors focus:outline-none relative">
        <i class="fas fa-bell text-lg"></i>
        @php $unreadCount = auth()->user()->unreadNotifications->count(); @endphp
        @if($unreadCount > 0)
            <span class="absolute -top-1 -right-1 inline-flex items-center justify-center px-1.5 py-0.5 text-xs font-bold leading-none text-white bg-red-600 rounded-full min-w-[18px] min-h-[18px]">{{ $unreadCount }}</span>
        @endif
    </button>
    <div id="{{ $bellId }}Dropdown" class="hidden absolute right-0 mt-2 w-80 bg-white border border-gray-200 rounded-lg shadow-lg z-50">
        <div class="p-4 border-b font-semibold">Notifications</div>
        <ul class="max-h-64 overflow-y-auto">
            @forelse(auth()->user()->unreadNotifications as $notification)
                <li class="px-4 py-2 border-b hover:bg-gray-50">
                    <div class="text-sm">{{ $notification->data['message'] ?? 'You have a new notification.' }}</div>
                    <div class="text-xs text-gray-400">{{ $notification->created_at ? \Carbon\Carbon::parse($notification->created_at)->diffForHumans() : 'N/A' }}</div>
                </li>
            @empty
                <li class="px-4 py-2 text-gray-500 text-sm">No new notifications.</li>
            @endforelse
        </ul>
        @if(auth()->user()->unreadNotifications->count() > 0)
            <form id="markAllReadForm" class="p-2 text-center">
                @csrf
                <button type="submit" class="text-xs text-indigo-600 hover:underline">Mark all as read</button>
            </form>
            <script>
            document.addEventListener('DOMContentLoaded', function() {
                const form = document.getElementById('markAllReadForm');
                if (form) {
                    form.addEventListener('submit', function(e) {
                        e.preventDefault();
                        fetch('{{ route('manufacturer.notifications.markAsRead') }}', {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                'X-Requested-With': 'XMLHttpRequest'
                            }
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                form.innerHTML = '<span class="text-xs text-green-600">All marked as read!</span>';
                                // Hide the red badge
                                const bellBtn = document.getElementById('{{ $bellId }}Btn');
                                const badge = bellBtn.querySelector('span.bg-red-600');
                                if (badge) badge.style.display = 'none';
                                // Optionally clear the notifications list
                                const dropdown = document.getElementById('{{ $bellId }}Dropdown');
                                if (dropdown) {
                                    const ul = dropdown.querySelector('ul');
                                    if (ul) ul.innerHTML = '<li class="px-4 py-2 text-gray-500 text-sm">No new notifications.</li>';
                                }
                            }
                        });
                    });
                }
            });
            </script>
        @endif
    </div>
</div>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const btn = document.getElementById('{{ $bellId }}Btn');
        const dropdown = document.getElementById('{{ $bellId }}Dropdown');
        if (btn && dropdown) {
            btn.addEventListener('click', function(e) {
                e.stopPropagation();
                dropdown.classList.toggle('hidden');
            });
            document.addEventListener('click', function() {
                dropdown.classList.add('hidden');
            });
        }
    });
</script> 