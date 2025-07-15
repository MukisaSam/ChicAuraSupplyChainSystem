<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AdminSettingsController extends Controller
{
    public function index()
    {
        $settings = \App\Models\SystemSetting::firstOrFail();
        return view('admin.settings.index', compact('settings'));
    }

    public function update(Request $request)
    {
        $settings = \App\Models\SystemSetting::firstOrFail();
        $settings->update($request->only(['company_name', 'currency', 'order_timeout']));
        return redirect()->route('admin.settings.index')->with('success', 'Settings updated!');
    }
}
