@extends('layouts.admin-dashboard')
@section('content')
@if (!isset($contact) || !isset($messages))
    <script>window.location = "{{ route('admin.chat.index') }}";</script>
@endif
<div class="flex h-full">
    <!-- Sidebar and header are handled by the admin-chat layout -->
    <div class="flex flex-col flex-1 w-full">
        <!-- Main Content -->
        <main class="flex-1 p-4 h-full">
            <div class="mb-6 relative">
                <a href="{{ route('admin.chat.index') }}" class="absolute top-0 right-0 inline-flex items-center px-4 py-2 bg-gray-200 dark:bg-gray-700 text-gray-800 dark:text-gray-200 rounded hover:bg-gray-300 dark:hover:bg-gray-600 transition text-sm font-semibold">
                    <i class="fas fa-arrow-left mr-2"></i> Back to Chat
                </a>
            </div>
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6 flex flex-col" style="height: calc(100vh - 200px);">
                <!-- Messages Container -->
                <div id="messages-container" class="flex-1 overflow-y-auto p-2 space-y-4 h-full">
                    @foreach($messages as $message)
                        @php
                            $isOwnMessage = $message->sender_id == $user->id;
                            $senderRole = $message->sender->role ?? null;
                            $senderName = $message->sender->name ?? '';
                            $avatar = $isOwnMessage
                                ? (Auth::user()->profile_picture ? Storage::disk('public')->url(Auth::user()->profile_picture) : asset('images/default-avatar.svg'))
                                : ($message->sender->profile_picture ? Storage::disk('public')->url($message->sender->profile_picture) : asset('images/default-avatar.svg'));
                        @endphp
                        <div class="flex {{ $isOwnMessage ? 'justify-end' : 'justify-start' }}">
                            <div class="flex {{ $isOwnMessage ? 'flex-row-reverse' : 'flex-row' }} items-end space-x-3 max-w-xs lg:max-w-md">
                                <img src="{{ $avatar }}" alt="{{ $senderName }}" class="w-8 h-8 rounded-full flex-shrink-0 border-2 border-blue-200">
                                <div class="message-bubble {{ $isOwnMessage ? 'own' : 'other' }}">
                                    <p class="text-sm">{{ $message->content }}</p>
                                    <p class="text-xs {{ $isOwnMessage ? 'text-blue-100' : 'text-gray-500 dark:text-gray-400' }} mt-1">
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
                            <input type="text" 
                                   id="message-input" 
                                   name="content" 
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
        const messageForm = document.getElementById('message-form');
        const messageInput = document.getElementById('message-input');
        const messagesContainer = document.getElementById('messages-container');
        const receiverId = messageForm.querySelector('input[name="receiver_id"]').value;
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