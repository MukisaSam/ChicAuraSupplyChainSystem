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
            --card-bg: #fff;
            --card-shadow: 0 8px 32px 0 rgba(31, 38, 135, 0.15);
        }

        body {
            min-height: 100vh;
            background: linear-gradient(120deg, #f5f6fa 0%, #e7e5df 60%, #ececec 100%);
            font-family: 'Figtree', 'Open Sans', 'Helvetica Neue', sans-serif;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .form-container {
            background: var(--card-bg);
            box-shadow: var(--card-shadow);
            border-radius: 1.25rem;
            padding: 2.5rem 2rem;
            max-width: 400px;
            margin: 2rem auto;
            border: 1px solid #e5e7eb;
        }

        .form-input {
            background: #f1f5f9 !important;
            border: 2px solid #e5e7eb !important;
            color: #1e293b !important;
            border-radius: 0.5rem;
            padding: 0.75rem 1rem;
            width: 100%;
            margin-bottom: 1rem;
            transition: border-color 0.2s;
        }

        .form-input::placeholder {
            color: #94a3b8 !important;
        }

        .form-input:focus {
            background: #fff !important;
            border-color: var(--primary-color) !important;
            outline: none;
            color: #1e293b !important;
        }

        .form-label {
            color: #1e293b !important;
            font-weight: 600;
            margin-bottom: 0.5rem;
            display: block;
        }

        .checkbox-label {
            color: #374151 !important;
        }

        .forgot-link {
            color: var(--primary-color) !important;
            text-decoration: none;
        }

        .forgot-link:hover {
            color: var(--primary-dark) !important;
            text-decoration: underline;
        }

        .divider-text {
            background: #fff !important;
            color: #64748b !important;
            padding: 0 0.5rem;
        }

        .register-btn {
            background: transparent !important;
            color: var(--primary-color) !important;
            border: 2px solid var(--primary-color) !important;
            border-radius: 0.5rem;
            transition: background 0.2s, color 0.2s;
        }

        .register-btn:hover {
            background: var(--primary-color) !important;
            color: white !important;
        }

        .brand-logo {
            display: flex;
            justify-content: center;
            align-items: center;
            margin-bottom: 1.5rem;
        }
        .brand-logo img {
            height: 72px;
            width: auto;
            filter: drop-shadow(0 2px 8px rgba(0,0,0,0.08));
        }
        .login-title {
            text-align: center;
            font-size: 2.25rem;
            font-weight: 700;
            color: #1e293b;
            margin-bottom: 0.5rem;
        }
        .login-subtitle {
            text-align: center;
            font-size: 1.125rem;
            color: #64748b;
            margin-bottom: 2rem;
        }
        @media (max-width: 500px) {
            .form-container {
                padding: 1.5rem 0.5rem;
            }
            .login-title {
                font-size: 1.5rem;
            }
        }
    </style>
</head>
<body>
    <div class="login-outer-container">
        <div class="login-card">
            <div class="login-card-left" style="display: flex; flex-direction: column; align-items: center; justify-content: center;">
                <img src="{{ asset('images/logo.png') }}" alt="ChicAura Logo" class="login-logo-img">
                <div class="login-welcome" style="margin-top: 1.25rem; color: #475569; font-size: 1rem; text-align: center; max-width: 180px;">
                    Empowering seamless collaboration across the fashion supply chain.
                </div>
            </div>
            <div class="login-card-right">
                <div class="login-title">Welcome Back</div>
                <div class="login-subtitle">Sign in to your ChicAura Business account</div>
                @if (session('status'))
                    <div class="mb-4 p-4 text-sm rounded-lg bg-blue-100 text-blue-700">
                        {{ session('status') }}
                    </div>
                @endif
                <form method="POST" action="{{ route('login') }}">
                    @csrf
                    <div>
                        <label for="email" class="form-label">{{ __('Email Address') }}</label>
                        <input id="email" type="email" class="form-input @error('email') border-red-500 @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus placeholder="eg.john@gmail.com">
                        @error('email')
                            <span class="text-red-400 text-sm mt-1 block" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                    <div>
                        <label for="password" class="form-label">{{ __('Password') }}</label>
                        <div style="position: relative;">
                            <input id="password" type="password" class="form-input @error('password') border-red-500 @enderror" name="password" required autocomplete="current-password" placeholder="Enter your password" style="padding-right: 2.5rem;">
                            <button type="button" id="togglePassword" aria-label="Show password" style="position: absolute; top: 50%; right: 0.75rem; transform: translateY(-50%); background: none; border: none; padding: 0; cursor: pointer;">
                                <svg id="eyeIcon" xmlns="http://www.w3.org/2000/svg" width="22" height="22" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                            </button>
                        </div>
                        @error('password')
                            <span class="text-red-400 text-sm mt-1 block" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                    <div class="flex items-center justify-between mb-4">
                        <div class="flex items-center">
                            <input class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                            <label class="checkbox-label ml-2 block text-sm" for="remember">
                                {{ __('Remember Me') }}
                            </label>
                        </div>
                        @if (Route::has('password.request'))
                            <div class="text-sm">
                                <a class="forgot-link font-medium transition duration-200" href="{{ route('password.request') }}">
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
                    <div class="relative flex items-center justify-center mb-4">
                        <div class="w-full border-t border-gray-200"></div>
                        <span class="divider-text absolute px-2 bg-white">New to ChicAura?</span>
                    </div>
                    <a href="{{ route('register') }}" class="register-btn w-full flex justify-center py-3 px-4 rounded-lg shadow-sm text-sm font-semibold focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition duration-200">
                        {{ __('Create Account') }}
                    </a>
                </div>
            </div>
        </div>
    </div>
    <style>
        body {
            min-height: 100vh;
            background: linear-gradient(135deg, #e0e7ff 0%, #f8fafc 100%);
            font-family: 'Figtree', 'Open Sans', 'Helvetica Neue', sans-serif;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .login-outer-container {
            width: 100vw;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background: transparent;
        }
        .login-card {
            display: flex;
            flex-direction: row;
            background: #fff;
            box-shadow: 0 8px 32px 0 rgba(31, 38, 135, 0.15);
            border-radius: 1.5rem;
            overflow: hidden;
            max-width: 800px;
            width: 100%;
            min-height: 420px;
        }
        .login-card-left {
            background: #f1f5f9;
            display: flex;
            align-items: center;
            justify-content: center;
            min-width: 240px;
            width: 35%;
            padding: 2rem 1.5rem;
        }
        .login-logo-img {
            max-width: 140px;
            width: 100%;
            height: auto;
            display: block;
        }
        .login-card-right {
            flex: 1;
            padding: 2.5rem 2rem;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }
        .login-title {
            font-size: 2.25rem;
            font-weight: 700;
            color: #1e293b;
            margin-bottom: 0.5rem;
        }
        .login-subtitle {
            font-size: 1.125rem;
            color: #64748b;
            margin-bottom: 2rem;
        }
        @media (max-width: 900px) {
            .login-card {
                max-width: 98vw;
            }
        }
        @media (max-width: 700px) {
            .login-card {
                flex-direction: column;
                min-height: unset;
            }
            .login-card-left, .login-card-right {
                width: 100%;
                min-width: unset;
                padding: 2rem 1rem;
            }
            .login-card-left {
                justify-content: center;
                border-bottom: 1px solid #e5e7eb;
            }
        }
        @media (max-width: 500px) {
            .login-card {
                border-radius: 0.5rem;
            }
            .login-title {
                font-size: 1.5rem;
            }
            .login-card-left, .login-card-right {
                padding: 1.25rem 0.5rem;
            }
        }
    </style>
</body>
</html>
<script>
    const togglePassword = document.getElementById('togglePassword');
    const passwordInput = document.getElementById('password');
    const eyeIcon = document.getElementById('eyeIcon');
    let passwordVisible = false;
    if (togglePassword && passwordInput && eyeIcon) {
        togglePassword.addEventListener('click', function () {
            passwordVisible = !passwordVisible;
            passwordInput.type = passwordVisible ? 'text' : 'password';
            eyeIcon.innerHTML = passwordVisible
                ? '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.542-7a9.956 9.956 0 012.293-3.95m3.362-2.568A9.956 9.956 0 0112 5c4.478 0 8.268 2.943 9.542 7a9.973 9.973 0 01-4.293 5.032M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3l18 18" />'
                : '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>';
        });
    }
</script>
