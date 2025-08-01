<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\SettingsService;

class AdminSettingsController extends Controller
{
    protected $settings;

    public function __construct(SettingsService $settings)
    {
        $this->settings = $settings;
    }

    public function index()
    {
        $settings = $this->settings->all();
        return view('admin.settings.index', compact('settings'));
    }

    public function update(Request $request)
    {
        foreach ($request->except('_token') as $key => $value) {
            $this->settings->set($key, $value);
        }
        // Audit log for settings update
        if (\Illuminate\Support\Facades\Auth::check()) {
            \App\Models\AuditLog::create([
                'user_id' => \Illuminate\Support\Facades\Auth::id(),
                'action' => 'settings_update',
                'details' => 'Admin updated system settings.',
            ]);
        }
        return redirect()->back()->with('success', 'Settings updated!');
    }
}
