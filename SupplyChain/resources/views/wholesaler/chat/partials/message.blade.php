@php
    $isOwnMessage = $message->sender_id == $user->id;
@endphp

<div class="flex {{ $isOwnMessage ? 'justify-end' : 'justify-start' }}">
    <div class="flex {{ $isOwnMessage ? 'flex-row-reverse' : 'flex-row' }} items-end space-x-3 max-w-xs lg:max-w-md">
        <img src="{{ $isOwnMessage ? asset('images/default-avatar.svg') : ($message->sender->role === 'manufacturer' ? asset('images/manufacturer.png') : asset('images/default-avatar.svg')) }}" 
             alt="{{ $message->sender->name }}" class="w-8 h-8 rounded-full flex-shrink-0 border-2 border-purple-200">
        <div class="message-bubble {{ $isOwnMessage ? 'own' : 'other' }}">
            <p class="text-sm">{{ $message->content }}</p>
            <p class="text-xs {{ $isOwnMessage ? 'text-purple-100' : 'text-gray-500 dark:text-gray-400' }} mt-1">
                {{ $message->created_at->format('H:i') }}
            </p>
        </div>
    </div>
</div> 