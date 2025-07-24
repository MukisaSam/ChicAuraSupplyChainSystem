<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SupplierNotificationsController extends Controller
{
    // Show all notifications for the authenticated supplier
    public function index(Request $request)
    {
        $user = $request->user();
        $notifications = $user->notifications()->latest()->paginate(20);
        return view('supplier.notifications.index', compact('notifications'));
    }

    // Mark a notification as read
    public function markRead(Request $request, $notification)
    {
        $user = $request->user();
        $notif = $user->notifications()->findOrFail($notification);
        if (!$notif->read_at) {
            $notif->markAsRead();
        }
        return back();//->with('success', 'Notification marked as read.');
    }
}
