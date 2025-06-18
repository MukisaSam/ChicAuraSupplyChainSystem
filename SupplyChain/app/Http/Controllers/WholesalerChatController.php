<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\ChatMessage;
use App\Models\User;
use App\Models\Wholesaler;
use App\Models\Manufacturer;

class WholesalerChatController extends Controller
{
    /**
     * Display the chat interface with available contacts.
     */
    public function index()
    {
        $user = Auth::user();
        $wholesaler = $user->wholesaler;
        
        if (!$wholesaler) {
            abort(403, 'Wholesaler profile not found.');
        }

        // Get manufacturers and admin users as potential chat contacts
        $manufacturers = User::where('role', 'manufacturer')
            ->with('manufacturer')
            ->get();
        
        $admins = User::where('role', 'admin')->get();
        
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

        return view('wholesaler.chat.index', compact(
            'user',
            'manufacturers',
            'admins',
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
        
        // Ensure wholesaler can only chat with manufacturers or admins
        if (!in_array($contact->role, ['manufacturer', 'admin'])) {
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

        return view('wholesaler.chat.show', compact('user', 'contact', 'messages'));
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

        // Ensure wholesaler can only send messages to manufacturers or admins
        if (!in_array($receiver->role, ['manufacturer', 'admin'])) {
            abort(403, 'Invalid message recipient.');
        }

        $message = ChatMessage::create([
            'sender_id' => $user->id,
            'receiver_id' => $receiver->id,
            'content' => $request->content,
            'message_type' => $request->message_type ?? 'text',
            'file_url' => $request->file_url,
        ]);

        // Load relationships for response
        $message->load(['sender', 'receiver']);

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => $message,
                'html' => view('wholesaler.chat.partials.message', compact('message', 'user'))->render()
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
            ->reverse();

        return response()->json(['messages' => $messages]);
    }
}
