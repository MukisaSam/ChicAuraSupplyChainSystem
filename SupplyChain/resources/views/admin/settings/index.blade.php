@extends('layouts.admin-dashboard')

@section('title', 'System Settings')

@section('content')
<div class="flex-1 p-4">
    <div class="mb-4">
        <h2 class="text-2xl font-bold text-white mb-1">System Settings</h2>
        <p class="text-gray-200 text-sm">Manage your system configuration and preferences.</p>
    </div>
    <div class="card-gradient p-6 rounded-2xl shadow max-w-xl mx-auto">
        <form method="POST" action="{{ route('admin.settings.update') }}" class="space-y-6">
            @csrf
            <div>
                <label class="block font-medium text-gray-700">Company Name</label>
                <input type="text" name="company_name" value="{{ $settings->company_name }}" class="mt-1 w-full border rounded-lg p-2 focus:ring-2 focus:ring-blue-500">
            </div>
            <div>
                <label class="block font-medium text-gray-700">Default Currency</label>
                <input type="text" name="currency" value="{{ $settings->currency }}" class="mt-1 w-full border rounded-lg p-2 focus:ring-2 focus:ring-blue-500">
            </div>
            <div>
                <label class="block font-medium text-gray-700">Order Processing Timeout (Days)</label>
                <input type="number" name="order_timeout" value="{{ $settings->order_timeout }}" class="mt-1 w-full border rounded-lg p-2 focus:ring-2 focus:ring-blue-500">
            </div>
            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-xl shadow hover:bg-blue-700">Save Settings</button>
        </form>
    </div>
</div>
@endsection
