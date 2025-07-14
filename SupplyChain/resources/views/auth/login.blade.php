<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('login') }}</title>

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
            background-image: url('/images/store.jpg');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            background-attachment: fixed;
        }

        .form-container {
            background: rgba(248, 250, 252, 0.95) !important;
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.3);
        }

        .form-input {
            background: rgba(255, 255, 255, 0.9) !important;
            border: 2px solid rgba(203, 213, 225, 0.5) !important;
            color: #1e293b !important;
            backdrop-filter: blur(5px);
        }

        .form-input::placeholder {
            color: #64748b !important;
        }

        .form-input:focus {
            background: rgba(255, 255, 255, 1) !important;
            border-color: var(--primary-color) !important;
            color: #1e293b !important;
        }

        .form-label {
            color: #1e293b !important;
            font-weight: 600;
        }

        .checkbox-label {
            color: #374151 !important;
        }

        .forgot-link {
            color: var(--primary-color) !important;
        }

        .forgot-link:hover {
            color: var(--primary-dark) !important;
        }

        .divider-text {
            background: rgba(248, 250, 252, 0.95) !important;
            color: #374151 !important;
        }

        .register-btn {
            background: transparent !important;
            color: var(--primary-color) !important;
            border: 2px solid var(--primary-color) !important;
        }

        .register-btn:hover {
            background: var(--primary-color) !important;
            color: white !important;
        }
    </style>
</head>
<body class="bg-cover bg-center bg-fixed" style="background-image: linear-gradient(135deg, rgba(0,0,0,0.7) 0%, rgba(0,0,0,0.4) 100%), url('{{ asset('images/store.jpg') }}');">
    <div class="min-h-screen flex flex-col justify-center py-12 sm:px-6 lg:px-8">
        <div class="sm:mx-auto sm:w-full sm:max-w-md">
            <div class="flex justify-center mb-8">
                <div style="background: linear-gradient(rgba(255,255,255,0.7), rgba(255,255,255,0.7)), url('/images/silk.jpeg') center center/cover no-repeat; padding: 0.75rem; border-radius: 1rem; box-shadow: 0 2px 8px rgba(0,0,0,0.08); display: inline-block;">
                    <img src="{{ asset('images/CA-WORD.png') }}" alt="ChicAura Logo" class="h-24 w-auto drop-shadow-lg">
                </div>
            </div>
            <h2 class="mt-6 text-center text-4xl font-bold text-white drop-shadow-lg">
                Welcome Back
            </h2>
            <p class="mt-2 text-center text-lg text-gray-200">
                Sign in to your ChicAura account
            </p>
        </div>

        <div class="mt-8 sm:mx-auto sm:w-full sm:max-w-md">
            <div class="form-container py-8 px-4 shadow-2xl sm:rounded-xl sm:px-10">
                @if (session('status'))
                    <div class="mb-4 p-4 text-sm rounded-lg bg-blue-100 text-blue-700">
                        {{ session('status') }}
                    </div>
                @endif

                <form method="POST" action="{{ route('login') }}" class="space-y-6">
                    @csrf

                    <div>
                        <label for="email" class="form-label block text-sm font-semibold mb-2">{{ __('Email Address') }}</label>
                        <input id="email" type="email" class="form-input w-full px-4 py-3 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition duration-200 @error('email') border-red-500 @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus placeholder="Enter your email">
                        @error('email')
                            <span class="text-red-300 text-sm mt-1 block" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>

                    <div>
                        <label for="password" class="form-label block text-sm font-semibold mb-2">{{ __('Password') }}</label>
                        <input id="password" type="password" class="form-input w-full px-4 py-3 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition duration-200 @error('password') border-red-500 @enderror" name="password" required autocomplete="current-password" placeholder="Enter your password">
                        @error('password')
                            <span class="text-red-300 text-sm mt-1 block" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>

                    <div class="flex items-center justify-between">
                        <div class="flex items-center">
                            <input class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                            <label class="checkbox-label ml-2 block text-sm" for="remember">
                                {{ __('Remember Me') }}
                            </label>
                        </div>

                        @if (Route::has('password.request'))
                            <div class="text-sm">
                                <a class="forgot-link font-medium hover:text-indigo-500 transition duration-200" href="{{ route('password.request') }}">
                                    {{ __('Forgot Password?') }}
                                </a>
                            </div>
                        @endif
                    </div>

                    <div>
                        <button type="submit" class="w-full flex justify-center py-3 px-4 border border-transparent rounded-lg shadow-sm text-sm font-semibold text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition duration-200 transform hover:scale-105">
                            {{ __('Sign In') }}
                        </button>
                    </div>
                </form>

                <div class="mt-6">
                    <div class="relative">
                        <div class="absolute inset-0 flex items-center">
                            <div class="w-full border-t border-gray-300"></div>
                        </div>
                        <div class="relative flex justify-center text-sm">
                            <span class="divider-text px-2">New to ChicAura?</span>
                        </div>
                    </div>

                    <div class="mt-6">
                        <a href="{{ route('register') }}" class="register-btn w-full flex justify-center py-3 px-4 rounded-lg shadow-sm text-sm font-semibold focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition duration-200">
                            {{ __('Create Account') }}
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
