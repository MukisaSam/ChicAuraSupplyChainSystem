<?php
namespace App\Services;

use App\Models\Setting;

class SettingsService
{
    public function get($key, $default = null)
    {
        return Setting::where('key', $key)->value('value') ?? $default;
    }

    public function set($key, $value)
    {
        return Setting::updateOrCreate(['key' => $key], ['value' => $value]);
    }

    public function all()
    {
        return Setting::pluck('value', 'key')->toArray();
    }
} 