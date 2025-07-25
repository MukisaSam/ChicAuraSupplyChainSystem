<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\ChatMessage;
use App\Models\User;
use App\Models\Supplier;
use App\Models\Wholesaler;
use App\Models\Manufacturer;
use App\Notifications\ChatMessageNotification;

class ManufacturerChatController extends Controller
{
    /**
     * Display the chat interface with available contacts.
     */
    public function index()
    {
        $user = Auth::user();
        
        if ($user->role !== 'manufacturer') {
            abort(403, 'Access denied. Manufacturer privileges required.');
        }

        // Ensure at least one supplier, wholesaler, and admin exist for demo
        if (!User::where('role', 'supplier')->exists()) {
            User::create([
                'name' => 'Demo Supplier',
                'email' => 'supplier_demo@chicaura.com',
                'password' => bcrypt('password'),
                'role' => 'supplier',
                'is_verified' => true,
            ]);
        }
        if (!User::where('role', 'wholesaler')->exists()) {
            User::create([
                'name' => 'Demo Wholesaler',
                'email' => 'wholesaler_demo@chicaura.com',
                'password' => bcrypt('password'),
                'role' => 'wholesaler',
                'is_verified' => true,
            ]);
        }
        if (!User::where('role', 'admin')->exists()) {
            User::create([
                'name' => 'Demo Admin',
                'email' => 'admin_demo@chicaura.com',
                'password' => bcrypt('password'),
                'role' => 'admin',
                'is_verified' => true,
            ]);
        }

        // Get manufacturers and admin users as potential chat contacts
        $suppliers = User::where('role', 'supplier')
            ->with('supplier')
            ->get();
        $wholesalers = User::where('role', 'wholesaler')
            ->with('wholesaler')
            ->get();
        $admins = User::where('role', 'admin')->get();

        // Add online status to each contact
        foreach ($suppliers as $supplier) {
            $supplier->is_online = $supplier->isOnline();
        }
        foreach ($wholesalers as $wholesaler) {
            $wholesaler->is_online = $wholesaler->isOnline();
        }
        foreach ($admins as $admin) {
            $admin->is_online = $admin->isOnline();
        }
        
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

        return view('manufacturer.chat.index', compact(
            'user',
            'suppliers',
            'wholesalers',
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
        
        // Ensure manufacturer can only chat with suppliers or wholesalers
        if (!in_array($contact->role, ['supplier', 'wholesaler'])) {
            abort(403, 'Invalid chat contact.');
        }
        
        // Get manufacturers and admin users as potential chat contacts
        $suppliers = User::where('role', 'supplier')
            ->with('supplier')
            ->get();
        $wholesalers = User::where('role', 'wholesaler')
            ->with('wholesaler')
            ->get();
        $admins = User::where('role', 'admin')->get();

        // Add online status to each contact
        foreach ($suppliers as $supplier) {
            $supplier->is_online = $supplier->isOnline();
        }
        foreach ($wholesalers as $wholesaler) {
            $wholesaler->is_online = $wholesaler->isOnline();
        }
        foreach ($admins as $admin) {
            $admin->is_online = $admin->isOnline();
        }
        
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

        // Get conversation messages
        $messages = ChatMessage::conversation($user->id, $contact->id)
            ->with(['sender', 'receiver'])
            ->get();

        // Mark messages as read
        ChatMessage::where('sender_id', $contact->id)
            ->where('receiver_id', $user->id)
            ->where('is_read', false)
            ->update(['is_read' => true, 'read_at' => now()]);

        return view('manufacturer.chat.show', compact(
            'user', 
            'contact', 
            'messages',
            'suppliers',
            'wholesalers',
            'admins',
            'recentConversations',
            'unreadCounts',
        ));
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

        // Ensure manufacturer can only send messages to suppliers or wholesalers
        if (!in_array($receiver->role, ['supplier', 'wholesaler'])) {
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
        $receiver->notify(new \App\Notifications\ChatMessageNotification($message));

        // Load relationships for response
        $message->load(['sender', 'receiver']);

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => $message
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
     * Get unread message count for a user.
     */
    public function getUnreadCount(Request $request)
    {
        $user = Auth::user();
        $senderId = $request->get('sender_id');

        $count = ChatMessage::where('sender_id', $senderId)
            ->where('receiver_id', $user->id)
            ->where('is_read', false)
            ->count();

        return response()->json(['count' => $count]);
    }

    /**
     * Get recent messages for a conversation.
     */
    public function getRecentMessages($contactId)
    {
        $user = Auth::user();
        $contact = User::findOrFail($contactId);

        // Ensure manufacturer can only access conversations with suppliers or wholesalers
        if (!in_array($contact->role, ['supplier', 'wholesaler'])) {
            abort(403, 'Invalid chat contact.');
        }

        $messages = ChatMessage::conversation($user->id, $contact->id)
            ->with(['sender', 'receiver'])
            ->orderBy('created_at', 'desc')
            ->limit(50)
            ->get()
            ->reverse();

        return response()->json([
            'messages' => $messages
        ]);
    }

    /**
     * Get all unread messages for the current user.
     */
    public function getUnreadMessages()
    {
        $user = Auth::user();
        
        $unreadMessages = ChatMessage::unread($user->id)
            ->with(['sender', 'receiver'])
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json([
            'unread_messages' => $unreadMessages,
            'total_unread' => $unreadMessages->count()
        ]);
    }
}
