<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\ChatMessage;
use App\Models\User;
use App\Notifications\ChatMessageNotification;

class SupplierChatController extends Controller
{
    /**
     * Display the chat interface with available contacts.
     */
    public function index()
    {
        $user = Auth::user();
        $admins = User::where('role', 'admin')->get();
        $manufacturers = User::where('role', 'manufacturer')->get();

        // Get recent conversations
        $recentConversations = ChatMessage::where('sender_id', $user->id)
            ->orWhere('receiver_id', $user->id)
            ->with(['sender', 'receiver'])
            ->orderBy('created_at', 'desc')
            ->get()
            ->groupBy(function ($message) use ($user) {
                return $message->sender_id === $user->id ? $message->receiver_id : $message->sender_id;
            })
            ->take(10);

        // Get unread message counts
        $unreadCounts = ChatMessage::unread($user->id)
            ->selectRaw('sender_id, COUNT(*) as count')
            ->groupBy('sender_id')
            ->pluck('count', 'sender_id');

        return view('supplier.chat.index', compact(
            'user',
            'admins',
            'manufacturers',
            // 'wholesalers',
            'recentConversations',
            'unreadCounts'
        ));
    }

    /**
     * Show conversation with a specific user.
     */
    public function show($contactId)
    {
        $user = Auth::user();
        $contact = User::findOrFail($contactId);

        // Ensure supplier can only chat with admins or manufacturers
        if (!in_array($contact->role, ['admin', 'manufacturer'])) {
            abort(403, 'Invalid chat contact.');
        }

        // Get conversation messages
        $messages = ChatMessage::conversation($user->id, $contact->id)
            ->with(['sender', 'receiver'])
            ->get();

        // Mark messages as read
        ChatMessage::where('sender_id', $contact->id)
            ->where('receiver_id', $user->id)
            ->where('is_read', false)
            ->update(['is_read' => true, 'read_at' => now()]);

        return view('supplier.chat.show', compact('user', 'contact', 'messages'));
    }

    /**
     * Send a new message.
     */
    public function sendMessage(Request $request)
    {
        $request->validate([
            'receiver_id' => 'required|exists:users,id',
            'content' => 'required|string|max:1000',
            'message_type' => 'nullable|in:text,file,image',
            'file_url' => 'nullable|string',
        ]);

        $user = Auth::user();
        $receiver = User::findOrFail($request->receiver_id);

        // Ensure supplier can only send messages to admins or manufacturers
        if (!in_array($receiver->role, ['admin', 'manufacturer'])) {
            abort(403, 'Invalid message recipient.');
        }

        $message = ChatMessage::create([
            'sender_id' => $user->id,
            'receiver_id' => $receiver->id,
            'content' => $request->content,
            'message_type' => $request->message_type ?? 'text',
            'file_url' => $request->file_url,
        ]);

        // Notify the receiver of the new chat message
        $receiver->notify(new ChatMessageNotification($message));

        // Load relationships for response
        $message->load(['sender', 'receiver']);

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => $message,
                'html' => view('supplier.chat.partials.message', compact('message', 'user'))->render()
            ]);
        }

        return redirect()->back()->with('success', 'Message sent successfully.');
    }

    /**
     * Mark messages as read.
     */
    public function markAsRead(Request $request)
    {
        $request->validate([
            'sender_id' => 'required|exists:users,id',
        ]);

        $user = Auth::user();
        $sender = User::findOrFail($request->sender_id);

        ChatMessage::where('sender_id', $sender->id)
            ->where('receiver_id', $user->id)
            ->where('is_read', false)
            ->update(['is_read' => true, 'read_at' => now()]);

        if ($request->ajax()) {
            return response()->json(['success' => true]);
        }

        return redirect()->back();
    }

    /**
     * Get unread message count for notifications.
     */
    public function getUnreadCount()
    {
        $user = Auth::user();
        $count = ChatMessage::unread($user->id)->count();

        return response()->json(['count' => $count]);
    }

    /**
     * Get recent messages for a conversation.
     */
    public function getRecentMessages($contactId)
    {
        $user = Auth::user();
        $contact = User::findOrFail($contactId);

        $messages = ChatMessage::conversation($user->id, $contact->id)
            ->with(['sender', 'receiver'])
            ->latest()
            ->take(50)
            ->get()
            ->reverse()
            ->map(function ($message) {
                $sender = $message->sender;
                $receiver = $message->receiver;
                return [
                    'id' => $message->id,
                    'sender_id' => $message->sender_id,
                    'receiver_id' => $message->receiver_id,
                    'content' => $message->content,
                    'created_at' => $message->created_at ? $message->created_at->toIso8601String() : null,
                    'sender' => [
                        'id' => $sender ? $sender->id : $message->sender_id,
                        'name' => $sender ? $sender->name : 'Unknown',
                        'role' => $sender ? $sender->role : '',
                    ],
                    'receiver' => [
                        'id' => $receiver ? $receiver->id : $message->receiver_id,
                        'name' => $receiver ? $receiver->name : 'Unknown',
                        'role' => $receiver ? $receiver->role : '',
                    ],
                ];
            });

        return response()->json(['messages' => $messages]);
    }
}
