@if (!isset($contact) || !isset($messages))
    <script>window.location = "{{ route('supplier.chat.index') }}";</script>
@endif
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chat - ChicAura SCM</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <script src="{{ asset('js/theme-switcher.js') }}"></script>
    <style>
        body { 
            background: linear-gradient(135deg, rgba(0,0,0,0.7) 0%, rgba(0,0,0,0.5) 100%), url('{{ asset('images/supplier.jpg') }}');
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
            min-height: 100vh;
        }
        .dark body {
            background: linear-gradient(135deg, rgba(0,0,0,0.8) 0%, rgba(0,0,0,0.7) 100%), url('{{ asset('images/supplier.jpg') }}');
        }
        .sidebar { 
            transition: transform 0.3s ease-in-out;
            background: linear-gradient(180deg, #1e293b 0%, #334155 100%);
            box-shadow: 4px 0 15px rgba(0,0,0,0.1);
        }
        .dark .sidebar {
            background: linear-gradient(180deg, #0f172a 0%, #1e293b 100%);
        }
        .logo-container {
            background: rgba(255, 255, 255, 0.95);
            border-radius: 12px;
            padding: 8px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        }
        .nav-link {
            transition: all 0.3s ease;
            border-radius: 12px;
            margin: 4px 0;
        }
        .nav-link:hover {
            transform: translateX(5px);
        }
        .header-gradient {
            background: linear-gradient(135deg, #ffffff 0%, #f8fafc 100%);
            box-shadow: 0 2px 20px rgba(0,0,0,0.1);
        }
        .dark .header-gradient {
            background: linear-gradient(135deg, #1e293b 0%, #334155 100%);
            border-color: #475569;
        }
        @media (max-width: 768px) {
            .sidebar { transform: translateX(-100%); }
            .sidebar.open { transform: translateX(0); }
        }
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
            background: linear-gradient(135deg, #10B981 0%, #059669 100%);
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
</head>
<body class="font-sans antialiased">
    <div class="flex h-full">
        <!-- Sidebar -->
        <aside id="sidebar" class="sidebar absolute md:relative z-20 flex-shrink-0 w-64 md:block">
            <div class="flex flex-col h-full">
                <div class="flex items-center justify-center h-16 border-b border-green-900">
                    <div class="logo-container">
                        <img src="{{ asset('images/logo.png') }}" alt="ChicAura Logo" class="w-full h-auto object-contain max-w-[160px] max-h-[48px]">
                    </div>
                </div>
                <div class="px-4 py-4">
                    <h3 class="text-white text-sm font-semibold mb-3 px-3">SUPPLIER PORTAL</h3>
                </div>
                <nav class="flex-1 px-4 py-2 space-y-1">
                    <a href="{{ route('supplier.dashboard') }}" class="nav-link flex items-center px-3 py-2 text-gray-300 hover:bg-green-700 hover:text-white rounded-xl">
                        <i class="fas fa-home w-5"></i>
                        <span class="ml-2 text-sm">Home</span>
                    </a>
                    <a href="{{ route('supplier.supply-requests.index') }}" class="nav-link flex items-center px-3 py-2 text-gray-300 hover:bg-green-700 hover:text-white rounded-xl">
                        <i class="fas fa-dolly w-5"></i>
                        <span class="ml-2 text-sm">Supply Request</span>
                    </a>
                    <a href="{{ route('supplier.analytics.index') }}" class="nav-link flex items-center px-3 py-2 text-gray-300 hover:bg-green-700 hover:text-white rounded-xl">
                        <i class="fas fa-chart-bar w-5"></i>
                        <span class="ml-2 text-sm">Analytics</span>
                    </a>
                    <a href="{{ route('supplier.chat.index') }}" class="nav-link flex items-center px-3 py-2 text-white bg-gradient-to-r from-green-600 to-green-700 rounded-xl shadow-lg">
                        <i class="fas fa-comments w-5"></i>
                        <span class="ml-2 text-sm">Chat</span>
                    </a>
                    <a href="{{ route('supplier.reports.index') }}" class="nav-link flex items-center px-3 py-2 text-gray-300 hover:bg-green-700 hover:text-white rounded-xl">
                        <i class="fas fa-file-alt w-5"></i>
                        <span class="ml-2 text-sm">Reports</span>
                    </a>
                </nav>
                <div class="p-3 border-t border-green-900">
                    <div class="text-center text-gray-400 text-xs">
                        <p>ChicAura SCM</p>
                        <p class="text-xs mt-1">v2.1.0</p>
                    </div>
                </div>
            </div>
        </aside>
        <div class="flex flex-col flex-1 w-full">
            <!-- Top Navigation Bar -->
            <header class="header-gradient relative z-10 flex items-center justify-between h-16 border-b">
                <div class="flex items-center">
                    <img src="{{ $contact->profile_picture ? Storage::disk('public')->url($contact->profile_picture) : asset('images/default-avatar.svg') }}" alt="{{ $contact->name }}" class="w-12 h-12 rounded-full border-2 border-green-200">
                    <span class="online-indicator ml-2 {{ $contact->isOnline() ? 'bg-green-500' : 'bg-gray-400' }}"></span>
                    <div class="ml-4">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white">{{ $contact->name }}</h3>
                        <p class="text-sm text-gray-500 dark:text-gray-400">{{ ucfirst($contact->role) }}
                            <span class="ml-2 text-xs {{ $contact->isOnline() ? 'text-green-500' : 'text-gray-400' }}">
                                {{ $contact->isOnline() ? 'Online' : 'Offline' }}
                            </span>
                        </p>
                    </div>
                </div>
                <div class="flex items-center pr-4 space-x-3">
                    <x-wholesaler-notification-bell />
                    <button data-theme-toggle class="p-2 text-gray-500 hover:text-green-600 hover:bg-green-50 rounded-full transition-colors" title="Switch Theme">
                        <i class="fas fa-moon text-lg"></i>
                    </button>
                    <div class="relative">
                        <button class="flex items-center focus:outline-none bg-white rounded-full p-2 shadow-md hover:shadow-lg transition-shadow">
                            <span class="mr-2 text-gray-700 font-medium text-sm">{{ Auth::user()->name ?? 'Supplier User' }}</span>
                            <img class="w-7 h-7 rounded-full border-2 border-green-200 object-cover" 
                                 src="{{ Auth::user()->profile_picture ? Storage::disk('public')->url(Auth::user()->profile_picture) : asset('images/default-avatar.svg') }}" 
                                 alt="User Avatar">
                        </button>
                    </div>
                    <div class="flex items-center space-x-2">
                        <form method="POST" action="{{ route('logout') }}" class="inline">
                            @csrf
                            <button type="submit" class="p-2 text-gray-500 hover:text-red-600 hover:bg-red-50 rounded-full transition-colors" title="Logout">
                                <i class="fas fa-sign-out-alt text-lg"></i>
                            </button>
                        </form>
                    </div>
                </div>
            </header>
            <!-- Main Content -->
            <main class="flex-1 p-4 h-full">
                <div class="mb-6 relative">
                    <a href="{{ route('supplier.chat.index') }}" class="absolute top-0 right-0 inline-flex items-center px-4 py-2 bg-gray-200 dark:bg-gray-700 text-gray-800 dark:text-gray-200 rounded hover:bg-gray-300 dark:hover:bg-gray-600 transition text-sm font-semibold">
                        <i class="fas fa-arrow-left mr-2"></i> Back to Chat
                    </a>
                    <h2 class="text-2xl font-bold text-white mb-1">Chat with {{ $contact->name }}</h2>
                    <p class="text-gray-200 text-sm">{{ ucfirst($contact->role) }}</p>
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
                                    ? asset('images/supplier.jpg')
                                    : ($senderRole === 'manufacturer'
                                        ? asset('images/manufacturer.png')
                                        : asset('images/default-avatar.svg'));
                            @endphp
                            <div class="flex {{ $isOwnMessage ? 'justify-end' : 'justify-start' }}">
                                <div class="flex {{ $isOwnMessage ? 'flex-row-reverse' : 'flex-row' }} items-end space-x-3 max-w-xs lg:max-w-md">
                                    <img src="{{ $avatar }}" alt="{{ $senderName }}" class="w-8 h-8 rounded-full flex-shrink-0 border-2 border-green-200">
                                    <div class="message-bubble {{ $isOwnMessage ? 'own' : 'other' }}">
                                        <p class="text-sm">{{ $message->content }}</p>
                                        <p class="text-xs {{ $isOwnMessage ? 'text-green-100' : 'text-gray-500 dark:text-gray-400' }} mt-1">
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
                                       class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-green-500 focus:border-transparent dark:bg-gray-700 dark:text-white dark:placeholder-gray-400">
                            </div>
                            <button type="submit" 
                                    class="px-6 py-3 bg-green-600 text-white rounded-xl hover:bg-green-700 focus:ring-2 focus:ring-green-500 focus:ring-offset-2 transition-colors">
                                <i class="fas fa-paper-plane"></i>
                            </button>
                        </form>
                    </div>
                </div>
            </main>
        </div>
    </div>
    <script src="https://js.pusher.com/7.2/pusher.min.js"></script>
    @vite('resources/js/bootstrap.js')
    <script>
        // Mobile menu toggle
        const menuToggle = document.getElementById('menu-toggle');
        if (menuToggle) {
            menuToggle.addEventListener('click', function() {
                document.getElementById('sidebar').classList.toggle('open');
            });
        }
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
                    sender: { name: '{{ $user->name }}', role: 'supplier' },
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
                fetch('{{ route("supplier.chat.send") }}', {
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

            function loadMessages() {
                fetch(`{{ route('supplier.chat.messages', ['contactId' => $contact->id]) }}`)
                    .then(response => response.json())
                    .then(data => {
                        messagesContainer.innerHTML = '';
                        if (data.messages && data.messages.length > 0) {
                            data.messages.forEach(message => {
                                appendMessage(message);
                            });
                        } else {
                            messagesContainer.innerHTML = '<div class="text-center text-gray-500 dark:text-gray-400 py-8">No messages yet. Start the conversation!</div>';
                        }
                        scrollToBottom();
                    })
                    .catch(error => {
                        messagesContainer.innerHTML = '<div class="text-center text-red-500 py-8">Error loading messages</div>';
                    });
            }

            function appendMessage(message) {
                const isOwnMessage = message.sender_id == {{ $user->id }};
                const avatar = isOwnMessage
                    ? '{{ asset('images/supplier.jpg') }}'
                    : ('{{ $contact->role === 'manufacturer' ? asset('images/manufacturer.png') : asset('images/default-avatar.svg') }}');
                const messageHtml = `
                    <div class="flex ${isOwnMessage ? 'justify-end' : 'justify-start'}">
                        <div class="flex ${isOwnMessage ? 'flex-row-reverse' : 'flex-row'} items-end space-x-3 max-w-xs lg:max-w-md">
                            <img src="${avatar}" alt="${message.sender.name}" class="w-8 h-8 rounded-full flex-shrink-0 border-2 border-green-200">
                            <div class="message-bubble ${isOwnMessage ? 'own' : 'other'}">
                                <p class="text-sm">${message.content}</p>
                                <p class="text-xs ${isOwnMessage ? 'text-green-100' : 'text-gray-500 dark:text-gray-400'} mt-1">
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

            // Laravel Echo real-time listener
            if (window.Echo) {
                window.Echo.private('chat.user.' + {{ $user->id }})
                    .listen('.ChatMessageSent', (e) => {
                        // Only append if the message is from the current contact
                        if (e.sender_id == {{ $contact->id }}) {
                            appendMessage(e);
                            scrollToBottom();
                        }
                    });
            }
        });
    </script>
</body>
</html> 