@extends('layouts.admin-chat')
@section('content')
@if (!isset($contact) || !isset($messages))
<script>
    window.location = "{{ route('admin.chat.index') }}";
</script>
@endif
<div class="flex h-full">
    <!-- Sidebar and header are handled by the admin-chat layout -->
    <div class="flex flex-col flex-1 w-full">
        <!-- Main Content -->
        <main class="flex-1 p-4 h-full">
            <div class="mb-6 relative">

            </div>
            <div class="flex h-full gap-6">



                <div class="w-80 card-gradient rounded-xl flex flex-col">
                    <div class="p-6 border-b border-gray-200 dark:border-gray-600">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Contacts</h3>
                    </div>
                    <div class="flex-1 overflow-y-auto p-4 contacts-scroll"
                        style="height: calc(100vh - 300px); max-height: 420px;">
                        @foreach(['admin' => $admins, 'supplier' => $suppliers, 'manufacturer' => $manufacturers,
                        'wholesaler' => $wholesalers] as $role => $users)
                        @if($users->count() > 0)
                        <div class="mb-6">
                            <h4
                                class="text-sm font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wide mb-3 px-2">
                                {{ ucfirst($role) }}{{ $users->count() > 1 ? 's' : '' }}</h4>
                            @foreach($users as $userContact)
                            <a href="{{ route('admin.chat.show', ['contactId' => $userContact->id]) }}"
                                class="contact-item flex items-center p-4 rounded-xl hover:bg-blue-100 dark:hover:bg-blue-900/20 transition"
                                data-contact-id="{{ $userContact->id }}" data-contact-name="{{ $userContact->name }}">
                                <div class="relative">
                                    <img src="{{ $userContact->profile_picture ? Storage::disk('public')->url($userContact->profile_picture) : asset('images/default-avatar.svg') }}"
                                        alt="{{ $userContact->name }}"
                                        class="w-12 h-12 rounded-full border-2 border-blue-200">
                                    <span class="online-indicator absolute -bottom-1 -right-1"></span>
                                </div>
                                <div class="ml-4 flex-1">
                                    <h5 class="text-sm font-medium text-gray-900 dark:text-white">{{
                                        $userContact->name }}</h5>
                                    <p class="text-xs text-gray-500 dark:text-gray-400">{{ ucfirst($role) }}</p>
                                </div>
                                @if(isset($unreadCounts[$userContact->id]) && $unreadCounts[$userContact->id] > 0)
                                <span class="unread-badge">{{ $unreadCounts[$userContact->id] }}</span>
                                @endif
                            </a>
                            @endforeach
                        </div>
                        @endif
                        @endforeach
                        @if($admins->count() == 0 && $suppliers->count() == 0 && $manufacturers->count() == 0 &&
                        $wholesalers->count() == 0)
                        <div class="text-center py-12">
                            <div class="text-gray-500 dark:text-gray-400">
                                <i class="fas fa-users text-4xl mb-4"></i>
                                <p class="text-lg font-medium">No contacts available</p>
                                <p class="text-sm">No users found in the system</p>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>

                <div class="flex-1 card-gradient rounded-xl flex flex-col min-h-0 bg-white overflow-hidden">

                    <div class="flex items-center bg-blue-600 p-6">
                        <img class="w-7 h-7 rounded-full border-2 border-purple-200 object-cover"
                            src="{{ Auth::user()->profile_picture ? asset('storage/profile-pictures/' . basename(Auth::user()->profile_picture)) : asset('images/default-avatar.svg') }}"
                            alt="User Avatar">
                        <div class="ml-4">
                            <h3 class="text-lg font-semibold text-white dark:text-white">{{ $contact->name }}
                            </h3>
                            <p class="text-sm text-white dark:text-gray-400">{{ ucfirst($contact->role) }}
                                <span
                                    class="ml-2 text-xs {{ $contact->isOnline() ? 'text-green-500' : 'text-gray-400' }}">
                                    {{ $contact->isOnline() ? 'Online' : 'Offline' }}
                                </span>
                            </p>
                        </div>
                    </div>

                    <div class="flex-1 flex flex-col min-h-0 p-6" id="chat-conversation">
                        <!-- Messages Container -->
                        <div id="messages-container" class="flex-1 overflow-y-auto p-2 space-y-4 h-full">
                            @foreach($messages as $message)
                            @php
                            $isOwnMessage = $message->sender_id == $user->id;
                            $senderRole = $message->sender->role ?? null;
                            $senderName = $message->sender->name ?? '';
                            $avatar = $isOwnMessage
                            ? (Auth::user()->profile_picture ?
                            Storage::disk('public')->url(Auth::user()->profile_picture) :
                            asset('images/default-avatar.svg'))
                            : ($message->sender->profile_picture ?
                            Storage::disk('public')->url($message->sender->profile_picture) :
                            asset('images/default-avatar.svg'));
                            @endphp
                            <div class="flex {{ $isOwnMessage ? 'justify-end' : 'justify-start' }}">
                                <div
                                    class="flex {{ $isOwnMessage ? 'flex-row-reverse' : 'flex-row' }} items-end max-w-xs lg:max-w-md">
                                    <img src="{{ $avatar }}" alt="{{ $senderName }}"
                                        class="w-8 h-8 rounded-full flex-shrink-0 border-2 border-blue-200">
                                    <div class="message-bubble {{ $isOwnMessage ? 'own mr-3' : 'other ml-3' }}">
                                        <p class="text-sm">{{ $message->content }}</p>
                                        <p
                                            class="text-xs {{ $isOwnMessage ? 'text-blue-100' : 'text-gray-500 dark:text-gray-400' }} mt-1">
                                            {{ $message->created_at->format('H:i') }}
                                        </p>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                        <!-- Message Input -->
                        <div class="pt-4 border-t border-gray-200 dark:border-gray-600">
                            <form id="message-form" class="flex space-x-4">
                                <input type="hidden" name="receiver_id" value="{{ $contact->id }}">
                                <div class="flex-1">
                                    <input type="text" id="message-input" name="content"
                                        placeholder="Type your message..."
                                        class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-700 dark:text-white dark:placeholder-gray-400">
                                </div>
                                <button type="submit"
                                    class="px-6 py-3 bg-blue-600 text-white rounded-xl hover:bg-blue-700 focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-colors">
                                    <i class="fas fa-paper-plane"></i>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>
</div>
<style>
    .message-bubble {
        padding: 0.75rem 1rem;
        border-radius: 1rem;
        max-width: 100%;
        word-wrap: break-word;
    }

    .message-bubble.own {
        background: linear-gradient(135deg, #3B82F6 0%, #2563EB 100%);
        color: white;
        border-bottom-right-radius: 0.25rem;
    }

    .message-bubble.other {
        background: #f3f4f6;
        color: #374151;
        border-bottom-left-radius: 0.25rem;
    }

    .dark .message-bubble.other {
        background: #374151;
        color: #f9fafb;
    }
</style>
<script>
    document.addEventListener('DOMContentLoaded', function() {
            const searchInput = document.getElementById('search-contacts');
        if (searchInput) {
            const contactItems = document.querySelectorAll('.contact-item');
            searchInput.addEventListener('input', function() {
                const searchTerm = this.value.toLowerCase();
                contactItems.forEach(item => {
                    const name = item.dataset.contactName ? item.dataset.contactName.toLowerCase() : '';
                    if (name.includes(searchTerm)) {
                        item.style.display = 'flex';
                    } else {
                        item.style.display = 'none';
                    }
                });
                });
            }
        });
        const searchInput = document.getElementById('search-contacts');
        if (searchInput) {
            searchInput.addEventListener('input', function() {
                const searchTerm = this.value.toLowerCase();
                const event = new CustomEvent('search-contacts', { detail: { searchTerm } });
                document.dispatchEvent(event);
                console.log('search-contacts event dispatched:', searchTerm);
            });
        }
</script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const messageForm = document.getElementById('message-form');
        const messageInput = document.getElementById('message-input');
        const messagesContainer = document.getElementById('messages-container');
        const receiverId = messageForm.querySelector('input[name="receiver_id"]').value;
        const contactItems = document.querySelectorAll('.contact-item');
        const searchInput = document.getElementById('search-contacts');

        messageForm.addEventListener('submit', function(e) {
            e.preventDefault();
            const content = messageInput.value.trim();
            if (!content || !receiverId) {
                return;
            }
            // Optimistically append the message immediately
            const tempMessage = {
                sender_id: {{ $user->id }},
                sender: { name: '{{ $user->name }}', role: 'admin' },
                content: content,
                created_at: new Date().toISOString(),
                _optimistic: true // mark as optimistic
            };
            appendMessage(tempMessage);
            scrollToBottom();
            sendMessage(receiverId, content);
            messageInput.value = '';
        });

        // Search contacts
            searchInput.addEventListener('input', function () {
                const searchTerm = this.value.toLowerCase();
                contactItems.forEach(item => {
                    const name = item.dataset.contactName.toLowerCase();
                    if (name.includes(searchTerm)) {
                        item.style.display = 'flex';
                    } else {
                        item.style.display = 'none';
                    }
                });
            });

        function sendMessage(receiverId, content) {
            const formData = new FormData();
            formData.append('receiver_id', receiverId);
            formData.append('content', content);
            formData.append('_token', '{{ csrf_token() }}');
            fetch('{{ route("admin.chat.send") }}', {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (!data.success) {
                    console.error('Failed to send message:', data);
                }
            })
            .catch(error => {
                console.error('Error sending message:', error);
            });
        }
        function appendMessage(message) {
            const isOwnMessage = message.sender_id == {{ $user->id }};
            const avatar = isOwnMessage
                ? '{{ Auth::user()->profile_picture ? Storage::disk('public')->url(Auth::user()->profile_picture) : asset('images/default-avatar.svg') }}'
                : ('{{ $contact->profile_picture ? Storage::disk('public')->url($contact->profile_picture) : asset('images/default-avatar.svg') }}');
            const messageHtml = `
                <div class="flex ${isOwnMessage ? 'justify-end' : 'justify-start'}">
                    <div class="flex ${isOwnMessage ? 'flex-row-reverse' : 'flex-row'} items-end space-x-3 max-w-xs lg:max-w-md">
                        <img src="${avatar}" alt="${message.sender.name}" class="w-8 h-8 rounded-full flex-shrink-0 border-2 border-blue-200">
                        <div class="message-bubble ${isOwnMessage ? 'own' : 'other'}">
                            <p class="text-sm">${message.content}</p>
                            <p class="text-xs ${isOwnMessage ? 'text-blue-100' : 'text-gray-500 dark:text-gray-400'} mt-1">
                                ${new Date(message.created_at).toLocaleTimeString([], {hour: '2-digit', minute:'2-digit'})}
                            </p>
                        </div>
                    </div>
                </div>
            `;
            messagesContainer.insertAdjacentHTML('beforeend', messageHtml);
        }
        function scrollToBottom() {
            messagesContainer.scrollTop = messagesContainer.scrollHeight;
        }
        scrollToBottom();
        // Laravel Echo real-time listener (if enabled for admin)
        if (window.Echo) {
            window.Echo.private('chat.user.' + {{ $user->id }})
                .listen('.ChatMessageSent', (e) => {
                    if (e.sender_id == {{ $contact->id }}) {
                        appendMessage(e);
                        scrollToBottom();
                    }
                });
        }
    });
</script>
@endsection