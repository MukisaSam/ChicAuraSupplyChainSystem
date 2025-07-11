<main class="flex-1 p-4">
                <div class="mb-6">
                    <h2 class="text-2xl font-bold text-white mb-1">Chat with {{ $contact->name }}</h2>
                    <p class="text-gray-200 text-sm">{{ ucfirst($contact->role) }}</p>
                </div>
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6 flex flex-col" style="height: calc(100vh - 200px);">
                    <!-- Messages Container -->
                    <div id="messages-container" class="flex-1 overflow-y-auto p-2 space-y-4">
                        @foreach($messages as $message)
                            @php
                                $isOwnMessage = $message->sender_id == $user->id;
                                $senderRole = $message->sender->role ?? null;
                                $senderName = $message->sender->name ?? '';
                                $avatar = $isOwnMessage
                                    ? asset('images/default-avatar.svg')
                                    : ($senderRole === 'manufacturer'
                                        ? asset('images/manufacturer.png')
                                        : asset('images/default-avatar.svg'));
                            @endphp
                            <div class="flex {{ $isOwnMessage ? 'justify-end' : 'justify-start' }}">
                                <div class="flex {{ $isOwnMessage ? 'flex-row-reverse' : 'flex-row' }} items-end space-x-3 max-w-xs lg:max-w-md">
                                    <img src="{{ $avatar }}" alt="{{ $senderName }}" class="w-8 h-8 rounded-full flex-shrink-0 border-2 border-purple-200">
                                    <div class="message-bubble {{ $isOwnMessage ? 'own' : 'other' }}">
                                        <p class="text-sm">{{ $message->content }}</p>
                                        <p class="text-xs {{ $isOwnMessage ? 'text-purple-100' : 'text-gray-500 dark:text-gray-400' }} mt-1">
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
                                       class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-purple-500 focus:border-transparent dark:bg-gray-700 dark:text-white dark:placeholder-gray-400">
                            </div>
                            <button type="submit" 
                                    class="px-6 py-3 bg-purple-600 text-white rounded-xl hover:bg-purple-700 focus:ring-2 focus:ring-purple-500 focus:ring-offset-2 transition-colors">
                                <i class="fas fa-paper-plane"></i>
                            </button>
                        </form>
                    </div>
                </div>
            </main>
        </div>
    </div>
   