<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="{{ asset('js/theme-switcher.js') }}"></script>
    <style>
        /* Dark mode styles for main layout */
        .dark .bg-gray-100 {
            background-color: #1f2937;
        }
        
        .dark .bg-white {
            background-color: #374151;
        }
        
        .dark .text-gray-800 {
            color: #f3f4f6;
        }
        
        .dark .text-gray-600 {
            color: #d1d5db;
        }
        
        .dark .border-gray-200 {
            border-color: #4b5563;
        }
        
        .dark .shadow {
            box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.3);
        }
    </style>
</head>
<body class="font-sans antialiased">
    <div class="min-h-screen bg-gray-100 dark:bg-gray-900">
        @include('layouts.navigation')

        <!-- Page Heading -->
        @if (isset($header))
            <header class="bg-white dark:bg-gray-800 shadow">
                <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                    {{ $header }}
                </div>
            </header>
        @endif

        <!-- Page Content -->
        <main>
            {{ $slot ?? '' }}
        </main>
    </div>
    @stack('scripts')
</body>
</html>
