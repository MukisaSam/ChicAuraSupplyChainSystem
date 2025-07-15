<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Notification;

class AdminNotificationsController extends Controller
{
    public function index()
    {
        $notifications = Notification::latest()->take(50)->get();
        return view('admin.notifications.index', compact('notifications'));
    }
}
