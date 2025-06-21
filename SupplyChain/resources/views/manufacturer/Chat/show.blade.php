@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-gray-900 via-purple-900 to-gray-900">
    <!-- Header -->
    <header class="bg-white/10 backdrop-blur-lg border-b border-white/20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center py-4">
                <div class="flex items-center space-x-4">
                    <a href="{{ route('manufacturer.chat') }}" class="text-white hover:text-purple-300 transition-colors">
                        <i class="fas fa-arrow-left text-xl"></i>
                    </a>
                    <div class="flex items-center space-x-4">
                        <img src="{{ $contact->role === 'supplier' ? asset('images/supplier.jpg') : asset('images/wholesaler.jpg') }}" 
                             alt="{{ $contact->name }}" class="w-10 h-10 rounded-full border-2 border-purple-200">
                        <div>
                            <h1 class="text-xl font-bold text-white">{{ $contact->name }}</h1>
                            <p class="text-gray-300 text-sm">{{ ucfirst($contact->role) }}</p>
                        </div>
                    </div>
                </div>
                <div class="flex items-center space-x-2">
                    <a href="{{ route('user.profile.edit') }}" class="p-2 text-gray-500 hover:text-purple-600 hover:bg-purple-50 dark:text-gray-400 dark:hover:text-purple-400 dark:hover:bg-purple-900/20 rounded-full transition-colors" title="Edit Profile">
                        <i class="fas fa-user-edit text-lg"></i>
                    </a>
                    <form method="POST" action="{{ route('logout') }}" class="inline">
                        @csrf
                        <button type="submit" class="p-2 text-gray-500 hover:text-red-600 hover:bg-red-50 dark:text-gray-400 dark:hover:text-red-400 dark:hover:bg-red-900/20 rounded-full transition-colors" title="Logout">
                            <i class="fas fa-sign-out-alt text-lg"></i>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main class="flex-1 p-4">
        <div class="max-w-4xl mx-auto">
            <div class="card-gradient rounded-xl flex flex-col" style="height: calc(100vh - 200px);">
                <!-- Messages Container -->
                <div id="messages-container" class="flex-1 overflow-y-auto p-6 space-y-4">
                    @foreach($messages as $message)
                        @include('manufacturer.chat.partials.message', ['message' => $message, 'user' => $user])
                    @endforeach
                </div>

                <!-- Message Input -->
                <div class="p-6 border-t border-gray-200 dark:border-gray-600">
                    <form id="message-form" class="flex space-x-4">
                        <input type="hidden" name="receiver_id" value="{{ $contact->id }}">
                        <div class="flex-1">
                            <input type="text" 
                                   id="message-input" 
                                   name="content" 
                                   placeholder="Type your message..." 
                                   class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-purple-500 focus:border-transparent dark:bg-gray-700 dark:text-white dark:placeholder-gray-400">
                        </div>
                        <button type="submit" 
                                class="px-6 py-3 bg-purple-600 text-white rounded-xl hover:bg-purple-700 focus:ring-2 focus:ring-purple-500 focus:ring-offset-2 transition-colors">
                            <i class="fas fa-paper-plane"></i>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </main>
</div>

<style>
.card-gradient {
    background: linear-gradient(135deg, rgba(255, 255, 255, 0.1) 0%, rgba(255, 255, 255, 0.05) 100%);
    backdrop-filter: blur(10px);
    border: 1px solid rgba(255, 255, 255, 0.1);
}

.message-bubble {
    padding: 0.75rem 1rem;
    border-radius: 1rem;
    max-width: 100%;
    word-wrap: break-word;
}

.message-bubble.own {
    background: linear-gradient(135deg, #8b5cf6 0%, #7c3aed 100%);
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

    // Handle message form submission
    messageForm.addEventListener('submit', function(e) {
        e.preventDefault();
        
        const content = messageInput.value.trim();
        const receiverId = this.querySelector('input[name="receiver_id"]').value;

        if (!content || !receiverId) {
            return;
        }

        sendMessage(receiverId, content);
        messageInput.value = '';
    });

    // Send message function
    function sendMessage(receiverId, content) {
        const formData = new FormData();
        formData.append('receiver_id', receiverId);
        formData.append('content', content);
        formData.append('_token', '{{ csrf_token() }}');

        fetch('{{ route("manufacturer.chat.send") }}', {
            method: 'POST',
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                appendMessage(data.message);
                scrollToBottom();
            } else {
                console.error('Failed to send message:', data);
            }
        })
        .catch(error => {
            console.error('Error sending message:', error);
        });
    }

    // Append a message to the conversation
    function appendMessage(message) {
        const isOwnMessage = message.sender_id == {{ $user->id }};
        const messageHtml = `
            <div class="flex ${isOwnMessage ? 'justify-end' : 'justify-start'}">
                <div class="flex ${isOwnMessage ? 'flex-row-reverse' : 'flex-row'} items-end space-x-3 max-w-xs lg:max-w-md">
                    <img src="${isOwnMessage ? '{{ asset('images/manufacturer.png') }}' : '{{ $contact->role === 'supplier' ? asset('images/supplier.jpg') : asset('images/wholesaler.jpg') }}'}" 
                         alt="${message.sender.name}" class="w-8 h-8 rounded-full flex-shrink-0 border-2 border-purple-200">
                    <div class="message-bubble ${isOwnMessage ? 'own' : 'other'}">
                        <p class="text-sm">${message.content}</p>
                        <p class="text-xs ${isOwnMessage ? 'text-purple-100' : 'text-gray-500 dark:text-gray-400'} mt-1">
                            ${new Date(message.created_at).toLocaleTimeString([], {hour: '2-digit', minute:'2-digit'})}
                        </p>
                    </div>
                </div>
            </div>
        `;
        messagesContainer.insertAdjacentHTML('beforeend', messageHtml);
    }

    // Scroll to bottom of messages
    function scrollToBottom() {
        messagesContainer.scrollTop = messagesContainer.scrollHeight;
    }

    // Scroll to bottom on page load
    scrollToBottom();
});
</script>
@endsection 