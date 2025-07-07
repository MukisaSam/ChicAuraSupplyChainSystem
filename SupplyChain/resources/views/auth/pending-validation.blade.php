@extends('layouts.app')

@section('content')
<div class="min-h-screen flex items-center justify-center relative">
    <!-- Overlay for contrast -->
    <div class="absolute inset-0 bg-black bg-opacity-40"></div>
    <div class="relative z-10 bg-white/90 dark:bg-gray-800/90 rounded-xl shadow-lg p-8 max-w-md w-full text-center">
        <div class="flex justify-center mb-4">
            <svg class="w-16 h-16 text-indigo-400 dark:text-indigo-300" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 11c0-1.657-1.343-3-3-3s-3 1.343-3 3 1.343 3 3 3 3-1.343 3-3zm0 0c0-1.657 1.343-3 3-3s3 1.343 3 3-1.343 3-3 3-3-1.343-3-3zm0 8v-2a4 4 0 00-4-4H5a2 2 0 00-2 2v2m14 0v-2a4 4 0 00-4-4h-1m5 6v-2a2 2 0 00-2-2h-1a4 4 0 00-4 4v2" />
            </svg>
        </div>
        <h1 class="text-2xl font-bold text-gray-900 dark:text-gray-900 mb-2">Account Pending Validation</h1>
        <p class="text-gray-600 dark:text-gray-300 mb-6">Please wait for an administrator to verify your account.<br>You will be notified once your account is active.</p>
        <a href="{{ route('login') }}" class="inline-block bg-indigo-500 hover:bg-indigo-600 text-white font-semibold px-6 py-2 rounded-lg shadow transition-colors">Back to Login</a>
    </div>
</div>
@endsection
