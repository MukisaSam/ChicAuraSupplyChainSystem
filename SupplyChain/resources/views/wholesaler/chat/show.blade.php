<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chat - ChicAura SCM</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <script src="{{ asset('js/theme-switcher.js') }}"></script>
    <style>
        body {
            background: #f5f7fa;
            min-height: 100vh;
        }

        .card-gradient {
            background: linear-gradient(135deg, #ffffff 0%, #f8fafc 100%);
            border: 1px solid rgba(255, 255, 255, 0.2);
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
            backdrop-filter: blur(10px);
        }

        .dark body {
            background: linear-gradient(135deg, rgba(0, 0, 0, 0.8) 0%, rgba(0, 0, 0, 0.7) 100%),
            url('{{ asset(' images/wholesaler.jpg') }}');
        }

        .sidebar {
            transition: transform 0.3s ease-in-out;
            background: #1a237e;
            box-shadow: 4px 0 15px rgba(0, 0, 0, 0.08);
        }

        .sidebar .sidebar-logo-blend {
            background: #fff;
        }

        .dark .sidebar {
            background: linear-gradient(180deg, #0f172a 0%, #1e293b 100%);
        }

        .logo-container {
            background: #fff;
            border-radius: 12px;
            padding: 8px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.06);
        }

        .dark .logo-container {
            background: rgba(255, 255, 255, 0.9);
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
            background: #fff;
            box-shadow: 0 2px 20px rgba(0, 0, 0, 0.06);
        }

        .dark .header-gradient {
            background: linear-gradient(135deg, #1e293b 0%, #334155 100%);
            border-color: #475569;
        }

        .contact-item {
            transition: all 0.3s ease;
            cursor: pointer;
            border-radius: 12px;
            margin: 4px 0;
        }

        .contact-item:hover {
            background-color: rgba(139, 92, 246, 0.1);
            transform: translateX(5px);
        }

        .contact-item.active {
            background: linear-gradient(135deg, rgba(139, 92, 246, 0.2) 0%, rgba(168, 85, 247, 0.2) 100%);
            border: 1px solid rgba(139, 92, 246, 0.3);
        }

        .contacts-scroll {
            overflow-y: scroll;
            scrollbar-width: thin;
            scrollbar-color: #8b5cf6 #e5e7eb;
        }

        .contacts-scroll::-webkit-scrollbar {
            width: 6px;
        }

        .contacts-scroll::-webkit-scrollbar-track {
            background: #e5e7eb;
            border-radius: 3px;
        }

        .contacts-scroll::-webkit-scrollbar-thumb {
            background: #8b5cf6;
            border-radius: 3px;
        }

        .contacts-scroll::-webkit-scrollbar-thumb:hover {
            background: #7c3aed;
        }

        .messages-scroll {
            overflow-y: scroll;
            scrollbar-width: thin;
            scrollbar-color: #8b5cf6 #e5e7eb;
        }

        .messages-scroll::-webkit-scrollbar {
            width: 6px;
        }

        .messages-scroll::-webkit-scrollbar-track {
            background: #e5e7eb;
            border-radius: 3px;
        }

        .messages-scroll::-webkit-scrollbar-thumb {
            background: #8b5cf6;
            border-radius: 3px;
        }

        .messages-scroll::-webkit-scrollbar-thumb:hover {
            background: #7c3aed;
        }

        .dark .contacts-scroll::-webkit-scrollbar-track,
        .dark .messages-scroll::-webkit-scrollbar-track {
            background: #374151;
        }

        .dark .contacts-scroll::-webkit-scrollbar-thumb,
        .dark .messages-scroll::-webkit-scrollbar-thumb {
            background: #8b5cf6;
        }

        .dark .contacts-scroll::-webkit-scrollbar-thumb:hover,
        .dark .messages-scroll::-webkit-scrollbar-thumb:hover {
            background: #7c3aed;
        }

        .unread-badge {
            background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
            color: white;
            border-radius: 50%;
            padding: 2px 6px;
            font-size: 0.75rem;
            min-width: 18px;
            text-align: center;
            box-shadow: 0 2px 4px rgba(239, 68, 68, 0.3);
        }

        .online-indicator {
            width: 8px;
            height: 8px;
            background: #10b981;
            border-radius: 50%;
            display: inline-block;
            margin-right: 8px;
            box-shadow: 0 0 0 2px rgba(16, 185, 129, 0.2);
        }

        .message-bubble {
            max-width: 70%;
            border-radius: 18px;
            padding: 12px 16px;

            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        }

        .message-bubble.own {
            background: linear-gradient(135deg, #8b5cf6 0%, #a855f7 100%);
            color: white;
            margin-left: auto;
            border-bottom-right-radius: 6px;
        }

        .message-bubble.other {
            background: linear-gradient(135deg, #ffffff 0%, #f8fafc 100%);
            color: #1f2937;
            margin-right: auto;
            border-bottom-left-radius: 6px;
            border: 1px solid rgba(0, 0, 0, 0.1);
        }

        .dark .message-bubble.other {
            background: linear-gradient(135deg, #374151 0%, #4b5563 100%);
            color: #f1f5f9;
            border-color: #6b7280;
        }

        @media (max-width: 768px) {
            .sidebar {
                transform: translateX(-100%);
            }

            .sidebar.open {
                transform: translateX(0);
            }

            main {
                margin-left: 0 !important;
            }

            header {
                left: 0 !important;
            }
        }

        @media (max-width: 768px) {
            .sidebar {
                transform: translateX(-100%);
            }

            .sidebar.open {
                transform: translateX(0);
            }

            main {
                margin-left: 0 !important;
            }

            header {
                left: 0 !important;
            }
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
            background: linear-gradient(135deg, #8b5cf6 0%, #a855f7 100%);
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

<body class="font-sans antialiased overflow-hidden">
    <!-- Sidebar -->
    <aside id="sidebar" class="sidebar fixed top-0 left-0 h-full w-64 z-20">
        <div class="flex flex-col h-full">
            <div class="flex items-center justify-center h-16 border-b border-gray-600">
                <div class="sidebar-logo-blend w-full h-16 flex items-center justify-center p-0 m-0"
                    style="background:#fff;">
                    <img src="{{ asset('images/logo.png') }}" alt="ChicAura Logo"
                        class="w-full h-auto object-contain max-w-[160px] max-h-[48px]">
                </div>
            </div>
            <div class="px-4 py-4">
                <h3 class="text-white text-sm font-semibold mb-3 px-3">WHOLESALER PORTAL</h3>
            </div>
            <nav class="flex-1 px-4 py-2 space-y-1">
                <a href="{{ route('wholesaler.dashboard') }}"
                    class="nav-link flex items-center px-3 py-2 text-gray-300 hover:bg-gray-700 hover:text-white rounded-xl">
                    <i class="fas fa-home w-5"></i>
                    <span class="ml-2 text-sm">Home</span>
                </a>
                <a href="{{ route('wholesaler.orders.index') }}"
                    class="nav-link flex items-center px-3 py-2 text-gray-300 hover:bg-gray-700 hover:text-white rounded-xl">
                    <i class="fas fa-shopping-cart w-5"></i>
                    <span class="ml-2 text-sm">Orders</span>
                </a>
                <a href="{{ route('wholesaler.analytics.index') }}"
                    class="nav-link flex items-center px-3 py-2 text-gray-300 hover:bg-gray-700 hover:text-white rounded-xl">
                    <i class="fas fa-chart-line w-5"></i>
                    <span class="ml-2 text-sm">Analytics</span>
                </a>
                <a href="{{ route('wholesaler.chat.index') }}"
                    class="nav-link flex items-center px-3 py-2 text-white bg-gradient-to-r from-purple-600 to-purple-700 rounded-xl shadow-lg">
                    <i class="fas fa-comments w-5"></i>
                    <span class="ml-2 font-medium text-sm">Chat</span>
                    <span id="unread-count" class="unread-badge ml-auto" style="display: none;"></span>
                </a>
                <a href="{{ route('wholesaler.reports.index') }}"
                    class="nav-link flex items-center px-3 py-2 text-gray-300 hover:bg-gray-700 hover:text-white rounded-xl">
                    <i class="fas fa-file-invoice-dollar w-5"></i>
                    <span class="ml-2 text-sm">Reports</span>
                </a>
                <a href="{{ route('wholesaler.invoices.index') }}"
                    class="nav-link flex items-center px-3 py-2 text-gray-300 hover:bg-gray-700 hover:text-white rounded-xl"><i
                        class="fas fa-file-invoice w-5"></i><span class="ml-2 text-sm">Invoices</span></a>
            </nav>
            <div class="p-3 border-t border-gray-600">
                <div class="text-center text-gray-400 text-xs">
                    <p>ChicAura SCM</p>
                    <p class="text-xs mt-1">v2.1.0</p>
                </div>
            </div>
        </div>
    </aside>
    <!-- Top Navigation Bar -->
    <header class="header-gradient fixed top-0 left-64 right-0 h-16 z-10 flex items-center justify-between border-b">
        <div class="flex items-center">
            <button id="menu-toggle"
                class="md:hidden p-3 text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200">
                <i class="fas fa-bars text-lg"></i>
            </button>
            <div class="relative ml-3 hidden md:block">
                <span class="absolute inset-y-0 left-0 flex items-center pl-3">
                    <i class="fas fa-search text-gray-400"></i>
                </span>
                <input type="text" id="wholesalerUniversalSearch"
                    placeholder="Search orders, products, invoices, reports, chat..."
                    class="w-80 py-2 pl-10 pr-4 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-purple-500 focus:border-transparent text-sm dark:bg-gray-700 dark:text-white">
            </div>
        </div>
        <div class="flex items-center pr-4 space-x-3">
            <x-wholesaler-notification-bell />
            <button data-theme-toggle
                class="p-2 text-gray-500 hover:text-purple-600 hover:bg-purple-50 rounded-full transition-colors"
                title="Switch Theme">
                <i class="fas fa-moon text-lg"></i>
            </button>
            <div class="relative">
                <button
                    class="flex items-center focus:outline-none bg-white rounded-full p-2 shadow-md hover:shadow-lg transition-shadow">
                    <span class="mr-2 text-gray-700 font-medium text-sm">{{ Auth::user()->name ?? 'Wholesaler User'
                        }}</span>
                    <img class="w-7 h-7 rounded-full border-2 border-purple-200 object-cover"
                        src="{{ $user->profile_picture ? asset('storage/' . $user->profile_picture) : asset('images/default-avatar.svg') }}"
                        alt="User Avatar">
                </button>
            </div>
            <div class="flex items-center space-x-2">
                <form method="POST" action="{{ route('logout') }}" class="inline">
                    @csrf
                    <button type="submit"
                        class="p-2 text-gray-500 hover:text-red-600 hover:bg-red-50 rounded-full transition-colors"
                        title="Logout">
                        <i class="fas fa-sign-out-alt text-lg"></i>
                    </button>
                </form>
            </div>
        </div>
    </header>
    <!-- Main Content -->
    <main class="ml-64 mt-16 p-4" style="min-height: calc(100vh - 4rem);">

        <div class="flex h-full gap-6">
            <!-- Contacts Sidebar -->
            <div class="w-80 card-gradient rounded-xl flex flex-col">
                <div class="p-6 border-b border-gray-200 dark:border-gray-600">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Contacts</h3>
                </div>

                <div class="flex-1 overflow-y-auto p-4 contacts-scroll"
                    style="height: calc(100vh - 300px); max-height: 420px;">
                    <!-- Manufacturers -->
                    @if($manufacturers->count() > 0)
                    <div class="mb-6">
                        <h4
                            class="text-sm font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wide mb-3 px-2">
                            Manufacturers</h4>
                        @foreach($manufacturers as $manufacturer)
                        <div class="contact-item flex items-center p-4 rounded-xl"
                            data-contact-id="{{ $manufacturer->id }}" data-contact-name="{{ $manufacturer->name }}"
                            data-chat-url="{{ route('wholesaler.chat.show', ['contactId' => $manufacturer->id]) }}">
                            <div class="relative">
                                <img src="{{ $manufacturer->profile_picture ? asset('storage/' . $manufacturer->profile_picture) : asset('images/default-avatar.svg') }}" alt="{{ $manufacturer->name }}"
                                    class="w-12 h-12 rounded-full border-2 border-purple-200">
                                <span
                                    class="online-indicator absolute -bottom-1 -right-1 {{ $manufacturer->is_online ? 'bg-green-500' : 'bg-gray-400' }}"></span>
                            </div>
                            <div class="ml-4 flex-1">
                                <h5 class="text-sm font-medium text-gray-900 dark:text-white">{{ $manufacturer->name }}
                                </h5>
                                <p class="text-xs text-gray-500 dark:text-gray-400">Manufacturer</p>
                            </div>
                            @if(isset($unreadCounts[$manufacturer->id]) && $unreadCounts[$manufacturer->id] > 0)
                            <span class="unread-badge">{{ $unreadCounts[$manufacturer->id] }}</span>
                            @endif
                        </div>
                        @endforeach
                    </div>
                    @endif

                    <!-- Admins -->
                    @if($admins->count() > 0)
                    <div class="mb-6">
                        <h4
                            class="text-sm font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wide mb-3 px-2">
                            Support Team</h4>
                        @foreach($admins as $admin)
                        <div class="contact-item flex items-center p-4 rounded-xl" data-contact-id="{{ $admin->id }}"
                            data-contact-name="{{ $admin->name }}"
                            data-chat-url="{{ route('wholesaler.chat.show', ['contactId' => $admin->id]) }}">
                            <div class="relative">
                                <img src="{{ $admin->profile_picture ? asset('storage/' . $admin->profile_picture) : asset('images/default-avatar.svg') }}" alt="{{ $admin->name }}"
                                    class="w-12 h-12 rounded-full border-2 border-purple-200">
                                <span
                                    class="online-indicator absolute -bottom-1 -right-1 {{ $admin->is_online ? 'bg-green-500' : 'bg-gray-400' }}"></span>
                            </div>
                            <div class="ml-4 flex-1">
                                <h5 class="text-sm font-medium text-gray-900 dark:text-white">{{ $admin->name }}</h5>
                                <p class="text-xs text-gray-500 dark:text-gray-400">Support Team</p>
                            </div>
                            @if(isset($unreadCounts[$admin->id]) && $unreadCounts[$admin->id] > 0)
                            <span class="unread-badge">{{ $unreadCounts[$admin->id] }}</span>
                            @endif
                        </div>
                        @endforeach
                    </div>
                    @endif

                    <!-- No contacts message -->
                    @if($manufacturers->count() == 0 && $admins->count() == 0)
                    <div class="text-center py-12">
                        <div class="text-gray-500 dark:text-gray-400">
                            <i class="fas fa-users text-4xl mb-4"></i>
                            <p class="text-lg font-medium">No contacts available</p>
                            <p class="text-sm">Contact support to add manufacturers or admins</p>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
            <!-- Messages Container -->
            <div class="flex-1 card-gradient rounded-xl flex flex-col overflow-hidden">
                <div class="bg-gradient-to-r from-purple-600 to-purple-700 p-6">
                    <div class="flex items-center space-x-4">
                        <img src="{{ $contact->profile_picture ? asset('storage/' . $contact->profile_picture) : asset('images/default-avatar.svg') }}"
                            alt="{{ $contact->name }}" class="w-16 h-16 rounded-full border-none">
                        <div>
                            <h2 class="text-2xl font-bold text-white dark:text-white mb-1"> {{ $contact->name }}</h2>
                            <div class="flex items-center space-x-2">
                                <p class="text-white dark:text-white text-sm mb-0">{{ ucfirst($contact->role) }}</p>
                                <span
                                    class="ml-2 text-xs {{ $contact->isOnline() ? 'text-green-500' : 'text-gray-400' }}">
                                    <span
                                        class="inline-block w-2 h-2 rounded-full mr-1 align-middle {{ $contact->isOnline() ? 'bg-green-500' : 'bg-gray-400' }}"></span>
                                    {{ $contact->isOnline() ? 'Online' : 'Offline' }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="p-6">
                    <div id="messages-container" class="flex-1 overflow-y-auto p-2 space-y-4 h-[300px]">
                        @foreach($messages as $message)
                        @php
                        $isOwnMessage = $message->sender_id == $user->id;
                        $senderRole = $message->sender->role ?? null;
                        $senderName = $message->sender->name ?? '';
                        $avatar = $isOwnMessage
                                    ? ($user->profile_picture ? asset('storage/' . $user->profile_picture) : asset('images/default-avatar.svg'))
                                    : ($message->sender->profile_picture ? asset('storage/' . $message->sender->profile_picture) : asset('images/default-avatar.svg'));
                        @endphp
                        <div class="flex {{ $isOwnMessage ? 'justify-end' : 'justify-start' }}">
                            <div
                                class="flex {{ $isOwnMessage ? 'flex-row-reverse' : 'flex-row' }} items-end max-w-xs lg:max-w-md">
                                <img src="{{ $avatar }}" alt="{{ $senderName }}"
                                    class="w-8 h-8 rounded-full flex-shrink-0 border-2 border-purple-200">
                                <div class="message-bubble {{ $isOwnMessage ? 'own mr-3' : 'other ml-3' }}">
                                    <p class="text-sm">{{ $message->content }}</p>
                                    <p
                                        class="text-xs {{ $isOwnMessage ? 'text-purple-100' : 'text-gray-500 dark:text-gray-400' }} mt-1">
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
                                <input type="text" id="message-input" name="content" placeholder="Type your message..."
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
        </div>
    </main>
    <script src="https://js.pusher.com/7.2/pusher.min.js"></script>
    @vite('resources/js/bootstrap.js')
    <script>
        // Mobile menu toggle
        const menuToggle = document.getElementById('menu-toggle');
        if (menuToggle) {
            menuToggle.addEventListener('click', function () {
                document.getElementById('sidebar').classList.toggle('open');
            });
        }

        //Contact side
        document.addEventListener('DOMContentLoaded', function () {
            console.log('Chat page loaded');

            const contactItems = document.querySelectorAll('.contact-item');
            const messagesContainer = document.getElementById('messages-container');
            const contactName = document.getElementById('contact-name');
            const searchInput = document.getElementById('wholesalerUniversalSearch');

            // CSRF token for AJAX requests
            const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
            console.log('CSRF Token:', csrfToken);

            // Handle contact selection
            contactItems.forEach(item => {
                item.addEventListener('click', function () {
                    const chatUrl = this.dataset.chatUrl;
                    const senderId = this.dataset.contactId;
                    if (senderId) {
                        markMessagesAsRead(senderId);
                    }
                    if (chatUrl) {
                        setTimeout(() => { window.location.href = chatUrl; }, 150); // slight delay to allow AJAX
                    }
                });
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

            // Mark messages as read
            function markMessagesAsRead(senderId) {
                console.log('Marking messages as read from:', senderId);

                fetch('/wholesaler/chat/mark-read', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken
                    },
                    body: JSON.stringify({
                        sender_id: senderId
                    })
                })
                    .then(response => response.json())
                    .then(data => {
                        console.log('Mark as read response:', data);
                        if (data.success) {
                            // Remove unread badge
                            const contactItem = document.querySelector(`[data-contact-id="${senderId}"]`);
                            if (contactItem) {
                                const badge = contactItem.querySelector('.unread-badge');
                                if (badge) {
                                    badge.remove();
                                }
                            }
                        }
                    })
                    .catch(error => console.error('Error marking messages as read:', error));
            }


            // Scroll to bottom of messages
            function scrollToBottom() {
                messagesContainer.scrollTop = messagesContainer.scrollHeight;
            }

            // Real-time online status with Echo presence channel
            if (window.Echo) {
                window.Echo.join('chat-users')
                    .here((users) => {
                        updateOnlineIndicators(users);
                    })
                    .joining((user) => {
                        setOnlineIndicator(user.id, true);
                    })
                    .leaving((user) => {
                        setOnlineIndicator(user.id, false);
                    });
            }

            function updateOnlineIndicators(users) {
                // Set all to offline first
                document.querySelectorAll('.contact-item').forEach(item => {
                    const dot = item.querySelector('.online-indicator');
                    if (dot) dot.classList.remove('bg-green-500');
                    if (dot) dot.classList.add('bg-gray-400');
                });
                // Set online for users in the channel
                users.forEach(user => {
                    setOnlineIndicator(user.id, true);
                });
            }
            function setOnlineIndicator(userId, isOnline) {
                const item = document.querySelector(`.contact-item[data-contact-id="${userId}"]`);
                if (item) {
                    const dot = item.querySelector('.online-indicator');
                    if (dot) {
                        if (isOnline) {
                            dot.classList.remove('bg-gray-400');
                            dot.classList.add('bg-green-500');
                        } else {
                            dot.classList.remove('bg-green-500');
                            dot.classList.add('bg-gray-400');
                        }
                    }
                }
            }
        });

        document.addEventListener('DOMContentLoaded', function () {
            const searchInput = document.getElementById('wholesalerUniversalSearch');
            if (searchInput) {
                searchInput.addEventListener('input', function () {
                    const searchTerm = this.value.toLowerCase();
                    document.querySelectorAll('.contact-item').forEach(item => {
                        const name = item.dataset.contactName ? item.dataset.contactName.toLowerCase() : '';
                        item.style.display = name.includes(searchTerm) ? 'flex' : 'none';
                    });
                });
            }
        });

        //Message area
        document.addEventListener('DOMContentLoaded', function () {
            const messageForm = document.getElementById('message-form');
            const messageInput = document.getElementById('message-input');
            const messagesContainer = document.getElementById('messages-container');
            const receiverId = messageForm.querySelector('input[name="receiver_id"]').value;

            messageForm.addEventListener('submit', function (e) {
                e.preventDefault();
                const content = messageInput.value.trim();
                if (!content || !receiverId) {
                    return;
                }
                // Optimistically append the message immediately
                const tempMessage = {
                    sender_id: {{ $user-> id}},
                    sender: { name: '{{ $user->name }}' },
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
            fetch('{{ route("wholesaler.chat.send") }}', {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
                .then(response => response.json())
                .then(data => {
                    console.log('Send response:', data); // Debug log
                    if (!data.success) {
                        console.error('Failed to send message:', data);
                    }
                    // Do NOT call loadMessages() here!
                })
                .catch(error => {
                    console.error('Error sending message:', error);
                });
        }

        function loadMessages() {
            fetch(`{{ route('wholesaler.chat.messages', ['contactId' => $contact->id]) }}`)
                .then(response => response.json())
                .then(data => {
                    console.log('Loaded messages:', data.messages); // Debug log
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
                    console.error('Error loading messages:', error);
                    messagesContainer.innerHTML = '<div class="text-center text-red-500 py-8">Error loading messages</div>';
                });
        }

        function appendMessage(message) {
            const isOwnMessage = message.sender_id == {{ $user-> id }};
                          const avatar = isOwnMessage ? '{{ $user->profile_picture ? asset('storage/' . $user->profile_picture) : asset('images/default-avatar.svg') }}' :  
                                          '{{ $contact->profile_picture ? asset('storage/' . $contact->profile_picture) : asset('images/default-avatar.svg') }}';

            const messageHtml = `
                        <div class="flex ${isOwnMessage ? 'justify-end' : 'justify-start'}">
                            <div class="flex ${isOwnMessage ? 'flex-row-reverse' : 'flex-row'} items-end max-w-xs lg:max-w-md">
                                <img src="${avatar}" alt="${message.sender.name}" class="w-8 h-8 rounded-full flex-shrink-0 border-2 border-purple-200">
                                <div class="message-bubble ${isOwnMessage ? 'own mr-3' : 'other ml-3'}">
                                    <p class="text-sm">${message.content}</p>
                                    <p class="text-xs ${isOwnMessage ? 'text-purple-100' : 'text-gray-500 dark:text-gray-400'} mt-1">
                                        ${new Date(message.created_at).toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' })}
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
            window.Echo.private('chat.user.' + {{ $user-> id }}).listen('.ChatMessageSent', (e) => {
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