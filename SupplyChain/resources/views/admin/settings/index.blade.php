@extends('layouts.admin-dashboard')
@section('content')
<div class="flex-1 p-6">
    <div class="mb-6">
        <h2 class="text-2xl font-bold text-gray-900 mb-1">System Settings</h2>
        <p class="text-gray-500 text-sm">Manage your system configuration and preferences.</p>
    </div>
    <div class="max-w-xl mx-auto card-gradient p-8 rounded-2xl shadow">
        @if(session('success'))
            <div class="mb-4 p-3 rounded bg-green-100 text-green-800 border border-green-200">{{ session('success') }}</div>
        @endif
        @if ($errors->any())
            <div class="mb-4 p-3 rounded bg-red-100 text-red-800 border border-red-200">
                <ul class="list-disc pl-5">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        <form method="POST" action="{{ route('admin.settings.update') }}" class="space-y-6">
            @csrf
            <div>
                <label class="block font-medium text-gray-700 mb-1">Site Name</label>
                <input type="text" name="site_name" value="{{ $settings['site_name'] ?? '' }}" class="w-full border rounded-lg p-2 focus:ring-2 focus:ring-blue-500">
            </div>
            <div>
                <label class="block font-medium text-gray-700 mb-1">Support Email</label>
                <input type="email" name="support_email" value="{{ $settings['support_email'] ?? '' }}" class="w-full border rounded-lg p-2 focus:ring-2 focus:ring-blue-500">
            </div>
            <div>
                <label class="block font-medium text-gray-700 mb-1">Default Currency</label>
                <input type="text" name="currency" value="{{ $settings['currency'] ?? '' }}" class="w-full border rounded-lg p-2 focus:ring-2 focus:ring-blue-500">
            </div>
            <div>
                <label class="block font-medium text-gray-700 mb-1">Order Processing Timeout (Days)</label>
                <input type="number" name="order_timeout" value="{{ $settings['order_timeout'] ?? '' }}" class="w-full border rounded-lg p-2 focus:ring-2 focus:ring-blue-500">
            </div>
            <div>
                <label class="block font-medium text-gray-700 mb-1">Maintenance Mode</label>
                <select name="maintenance_mode" class="w-full border rounded-lg p-2 focus:ring-2 focus:ring-blue-500">
                    <option value="off" {{ (isset($settings['maintenance_mode']) && $settings['maintenance_mode'] == 'off') ? 'selected' : '' }}>Off</option>
                    <option value="on" {{ (isset($settings['maintenance_mode']) && $settings['maintenance_mode'] == 'on') ? 'selected' : '' }}>On</option>
                </select>
            </div>
            <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded-xl shadow hover:bg-blue-700 font-semibold">Save Settings</button>
        </form>
    </div>
</div>
@endsection
