<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AuditLogsController extends Controller
{
    public function index()
    {
        $logs = \App\Models\AuditLog::with('user')->latest()->take(100)->get();
        return view('admin.audit-logs.index', compact('logs'));
    }
}
