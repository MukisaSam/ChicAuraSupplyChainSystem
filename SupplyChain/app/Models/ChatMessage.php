<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChatMessage extends Model
{
    use HasFactory;

    protected $fillable = [
        'sender_id',
        'receiver_id',
        'content',
        'message_type',
        'file_url',
        'is_read',
        'read_at',
    ];

    protected $casts = [
        'is_read' => 'boolean',
        'read_at' => 'datetime',
    ];

    /**
     * Get the sender of the message.
     */
    public function sender()
    {
        return $this->belongsTo(User::class, 'sender_id');
    }

    /**
     * Get the receiver of the message.
     */
    public function receiver()
    {
        return $this->belongsTo(User::class, 'receiver_id');
    }

    /**
     * Scope to get unread messages for a user.
     */
    public function scopeUnread($query, $userId)
    {
        return $query->where('receiver_id', $userId)
                    ->where('is_read', false);
    }

    /**
     * Scope to get conversation between two users.
     */
    public function scopeConversation($query, $user1Id, $user2Id)
    {
        return $query->where(function ($q) use ($user1Id, $user2Id) {
            $q->where('sender_id', $user1Id)
              ->where('receiver_id', $user2Id);
        })->orWhere(function ($q) use ($user1Id, $user2Id) {
            $q->where('sender_id', $user2Id)
              ->where('receiver_id', $user1Id);
        })->orderBy('created_at', 'asc');
    }

    /**
     * Mark message as read.
     */
    public function markAsRead()
    {
        $this->update([
            'is_read' => true,
            'read_at' => now(),
        ]);
    }

    /**
     * Get the conversation partner for a given user.
     */
    public function getConversationPartner($userId)
    {
        return $this->sender_id === $userId ? $this->receiver : $this->sender;
    }
}
