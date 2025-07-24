<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Notifications\DatabaseNotification;

class NotificationController extends Controller
{
   public function index()
{
    $user = Auth::user(); // Default guard

    if ($user->role !== 'admin') {
        abort(403);
    }

    $notifications = $user->notifications()->latest()->paginate(20);

    return view('admin.notifications.index', compact('notifications'));
}


    public function markRead(DatabaseNotification $notification)
    {
        if ($notification->notifiable_id !== Auth::guard('admin')->id()) {
            abort(403);
        }

        $notification->markAsRead();
        return back();
    }

    public function markAllRead()
    {
        Auth::guard('admin')->user()->unreadNotifications->markAsRead();
        return back();
    }

    public function unread()
    {
        $notifications = Auth::guard('admin')->user()->unreadNotifications()->take(10)->get();
        return response()->json($notifications);
    }
}
