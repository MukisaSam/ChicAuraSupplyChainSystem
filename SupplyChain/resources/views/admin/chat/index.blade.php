@extends('layouts.admin-dashboard')
@section('content')
                <div class="mb-6">
                    <h2 class="text-2xl font-bold text-white mb-1">Chat</h2>
    <p class="text-gray-200 text-sm">Communicate with any user in the system</p>
                </div>
                <div class="flex h-full gap-6">
                    <!-- Contacts Sidebar -->
                    <div class="w-80 card-gradient rounded-xl flex flex-col">
                        <div class="p-6 border-b border-gray-200 dark:border-gray-600">
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Contacts</h3>
                        </div>
                        <div class="flex-1 overflow-y-auto p-4 contacts-scroll" style="height: calc(100vh - 300px); max-height: 420px;">
            @foreach(['admin' => $admins, 'supplier' => $suppliers, 'manufacturer' => $manufacturers, 'wholesaler' => $wholesalers] as $role => $users)
                @if($users->count() > 0)
                            <div class="mb-6">
                    <h4 class="text-sm font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wide mb-3 px-2">{{ ucfirst($role) }}{{ $users->count() > 1 ? 's' : '' }}</h4>
                    @foreach($users as $userContact)
                    <a href="{{ route('admin.chat.show', ['contactId' => $userContact->id]) }}" class="contact-item flex items-center p-4 rounded-xl hover:bg-blue-100 dark:hover:bg-blue-900/20 transition" data-contact-name="{{ $userContact->name }}">
                                    <div class="relative">
                            <img src="{{ $userContact->profile_picture ? Storage::disk('public')->url($userContact->profile_picture) : asset('images/default-avatar.svg') }}" alt="{{ $userContact->name }}" class="w-12 h-12 rounded-full border-2 border-blue-200">
                                        <span class="online-indicator absolute -bottom-1 -right-1"></span>
                                    </div>
                                    <div class="ml-4 flex-1">
                            <h5 class="text-sm font-medium text-gray-900 dark:text-white">{{ $userContact->name }}</h5>
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
            @if($admins->count() == 0 && $suppliers->count() == 0 && $manufacturers->count() == 0 && $wholesalers->count() == 0)
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
                    <!-- Chat Area -->
                    <div class="flex-1 card-gradient rounded-xl flex flex-col min-h-0" id="chat-conversation">
                        <div id="chat-welcome" class="flex-1 flex items-center justify-center">
                            <div class="text-center">
                <div class="w-24 h-24 bg-gradient-to-br from-blue-100 to-blue-200 dark:from-blue-900 dark:to-blue-800 rounded-full flex items-center justify-center mx-auto mb-6">
                    <i class="fas fa-comments text-4xl text-blue-600 dark:text-blue-300"></i>
                                </div>
                                <h3 class="text-xl font-semibold text-gray-900 dark:text-white mb-2">Welcome to Chat</h3>
                                <p class="text-gray-600 dark:text-gray-400">Select a contact from the sidebar to start a conversation</p>
                            </div>
                        </div>
                    </div>
                </div>
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
    </script>
@endsection 