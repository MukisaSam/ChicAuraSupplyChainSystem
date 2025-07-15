@extends('layouts.admin-chat')
@section('content')
@if (!isset($contact) || !isset($messages))
    <script>window.location = "{{ route('admin.chat.index') }}";</script>
@endif
<!-- Banner and Top Bar -->
<div class="relative w-full h-40 md:h-48 lg:h-56 bg-cover bg-center rounded-b-3xl shadow-lg" style="background-image: url('{{ asset('images/black.jpeg') }}');">
    <div class="absolute inset-0 bg-black bg-opacity-40 rounded-b-3xl"></div>
    <div class="absolute left-8 bottom-4 flex items-center space-x-4 z-10">
        <img src="{{ $contact->profile_picture ? Storage::disk('public')->url($contact->profile_picture) : asset('images/default-avatar.svg') }}" alt="{{ $contact->name }}" class="w-20 h-20 rounded-full border-4 border-white shadow-lg">
        <div>
            <h2 class="text-2xl md:text-3xl font-bold text-white">{{ $contact->name }}</h2>
            <div class="flex items-center space-x-2 mt-1">
                <span class="text-white text-base font-medium">{{ ucfirst($contact->role) }}</span>
                <span class="w-2 h-2 rounded-full {{ $contact->isOnline() ? 'bg-green-400' : 'bg-gray-400' }} border border-white"></span>
                <span class="text-xs {{ $contact->isOnline() ? 'text-green-300' : 'text-gray-300' }}">{{ $contact->isOnline() ? 'Online' : 'Offline' }}</span>
            </div>
        </div>
    </div>
    <a href="{{ route('admin.chat.index') }}" class="absolute top-4 right-8 inline-flex items-center px-4 py-2 bg-white bg-opacity-80 text-gray-800 rounded-xl shadow hover:bg-opacity-100 transition text-sm font-semibold z-10">
        <i class="fas fa-arrow-left mr-2"></i> Back to Chat
    </a>
</div>
<!-- Chat Card Overlapping Banner -->
<div class="relative z-20 -mt-16 md:-mt-20 lg:-mt-24 px-2 md:px-8">
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6 flex flex-col" style="height: calc(100vh - 240px);">
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
        let lastMessageId = @json($messages->last() ? $messages->last()->id : 0);
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
        // Poll for new messages every 3 seconds
        setInterval(function() {
            fetch(`/admin/chat/{{ $contact->id }}/messages`)
                .then(response => response.json())
                .then(data => {
                    if (data.messages && data.messages.length) {
                        // Only append new messages
                        const newMessages = data.messages.filter(m => m.id > lastMessageId);
                        newMessages.forEach(function(message) {
                            appendMessage(message);
                            lastMessageId = message.id;
                        });
                        if (newMessages.length) scrollToBottom();
                    }
                });
        }, 3000);
        scrollToBottom();
    });
</script>
@endsection 