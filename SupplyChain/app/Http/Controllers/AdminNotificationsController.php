<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Notification;
use Illuminate\Support\Facades\Auth;


class AdminNotificationsController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $notifications = $user->notifications()->latest()->take(50)->get();
        return view('admin.notifications.index', compact('notifications'));
    }
    public function unread()
    {
        $notifications = Auth::user()->unreadNotifications()->take(10)->get();
        return response()->json($notifications);
    }

    public function markAllRead()
    {
        Auth::user()->unreadNotifications->markAsRead();
        return response()->json(['success' => true]);
    }
}

