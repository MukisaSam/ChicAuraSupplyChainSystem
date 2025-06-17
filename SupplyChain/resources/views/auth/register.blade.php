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
    <style>
        :root {
            --primary-color: #2563eb;
            --primary-dark: #1d4ed8;
            --text-light: #ffffff;
        }

        body {
            background-image: url('/images/showroom.png');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            background-attachment: fixed;
        }

        .form-container {
            background: rgba(255, 255, 255, 0.1) !important;
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }

        .role-btn {
            background: rgba(255, 255, 255, 0.1) !important;
            border: 2px solid rgba(255, 255, 255, 0.3) !important;
            color: var(--text-light) !important;
            backdrop-filter: blur(5px);
        }

        .role-btn:hover {
            background: rgba(255, 255, 255, 0.2) !important;
            border-color: var(--primary-color) !important;
        }

        .role-btn.admin {
            border-color: rgba(99, 102, 241, 0.5) !important;
        }

        .role-btn.admin:hover {
            border-color: #6366f1 !important;
            background: rgba(99, 102, 241, 0.2) !important;
        }

        .role-btn.supplier {
            border-color: rgba(34, 197, 94, 0.5) !important;
        }

        .role-btn.supplier:hover {
            border-color: #22c55e !important;
            background: rgba(34, 197, 94, 0.2) !important;
        }

        .role-btn.manufacturer {
            border-color: rgba(59, 130, 246, 0.5) !important;
        }

        .role-btn.manufacturer:hover {
            border-color: #3b82f6 !important;
            background: rgba(59, 130, 246, 0.2) !important;
        }

        .role-btn.wholesaler {
            border-color: rgba(168, 85, 247, 0.5) !important;
        }

        .role-btn.wholesaler:hover {
            border-color: #a855f7 !important;
            background: rgba(168, 85, 247, 0.2) !important;
        }

        .divider-text {
            background: rgba(255, 255, 255, 0.1) !important;
            color: var(--text-light) !important;
            text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.5);
        }

        .login-btn {
            background: transparent !important;
            color: var(--text-light) !important;
            border: 2px solid rgba(255, 255, 255, 0.3) !important;
        }

        .login-btn:hover {
            background: rgba(255, 255, 255, 0.1) !important;
            border-color: var(--primary-color) !important;
        }
    </style>
</head>
<body class="bg-cover bg-center bg-fixed" style="background-image: linear-gradient(135deg, rgba(0,0,0,0.6) 0%, rgba(0,0,0,0.3) 100%), url('{{ asset('images/showroom.png') }}');">
    <div class="min-h-screen flex flex-col justify-center py-12 sm:px-6 lg:px-8">
        <div class="sm:mx-auto sm:w-full sm:max-w-md">
            <div class="flex justify-center mb-8">
                <img src="{{ asset('images/logo.png') }}" alt="ChicAura Logo" class="h-24 w-auto drop-shadow-lg">
            </div>
            <h2 class="mt-6 text-center text-4xl font-bold text-white drop-shadow-lg">
                Join ChicAura
            </h2>
            <p class="mt-2 text-center text-lg text-gray-200">
                Choose your role in the fashion supply chain
            </p>
        </div>

        <div class="mt-8 sm:mx-auto sm:w-full sm:max-w-md">
            <div class="form-container py-8 px-4 shadow-2xl sm:rounded-xl sm:px-10">
                <div class="space-y-4">
                    <a href="{{ route('register.admin') }}" class="role-btn admin w-full flex justify-center py-3 px-4 rounded-lg shadow-sm text-sm font-semibold focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition duration-200 transform hover:scale-105">
                        <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"></path>
                        </svg>
                        Register as Admin
                    </a>

                    <a href="{{ route('register.supplier') }}" class="role-btn supplier w-full flex justify-center py-3 px-4 rounded-lg shadow-sm text-sm font-semibold focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition duration-200 transform hover:scale-105">
                        <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M3 4a1 1 0 011-1h12a1 1 0 011 1v2a1 1 0 01-1 1H4a1 1 0 01-1-1V4zM3 10a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H4a1 1 0 01-1-1v-6zM14 9a1 1 0 00-1 1v6a1 1 0 001 1h2a1 1 0 001-1v-6a1 1 0 00-1-1h-2z"></path>
                        </svg>
                        Register as Supplier
                    </a>

                    <a href="{{ route('register.manufacturer') }}" class="role-btn manufacturer w-full flex justify-center py-3 px-4 rounded-lg shadow-sm text-sm font-semibold focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition duration-200 transform hover:scale-105">
                        <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M6 6V5a3 3 0 013-3h2a3 3 0 013 3v1h2a2 2 0 012 2v3.57A22.952 22.952 0 0110 13a22.95 22.95 0 01-8-1.43V8a2 2 0 012-2h2zm2-1a1 1 0 011-1h2a1 1 0 011 1v1H8V5zm1 5a1 1 0 011-1h.01a1 1 0 110 2H10a1 1 0 01-1-1z" clip-rule="evenodd"></path>
                        </svg>
                        Register as Manufacturer
                    </a>

                    <a href="{{ route('register.wholesaler') }}" class="role-btn wholesaler w-full flex justify-center py-3 px-4 rounded-lg shadow-sm text-sm font-semibold focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-purple-500 transition duration-200 transform hover:scale-105">
                        <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M3 4a1 1 0 011-1h12a1 1 0 011 1v2a1 1 0 01-1 1H4a1 1 0 01-1-1V4zM3 10a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H4a1 1 0 01-1-1v-6zM14 9a1 1 0 00-1 1v6a1 1 0 001 1h2a1 1 0 001-1v-6a1 1 0 00-1-1h-2z"></path>
                        </svg>
                        Register as Wholesaler
                    </a>
                </div>

                <div class="mt-8">
                    <div class="relative">
                        <div class="absolute inset-0 flex items-center">
                            <div class="w-full border-t border-gray-300"></div>
                        </div>
                        <div class="relative flex justify-center text-sm">
                            <span class="divider-text px-2">
                                Already have an account?
                            </span>
                        </div>
                    </div>

                    <div class="mt-6">
                        <a href="{{ route('login') }}" class="login-btn w-full flex justify-center py-3 px-4 rounded-lg shadow-sm text-sm font-semibold focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition duration-200">
                            Sign in to your account
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
