
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Customer Login - {{ config('app.name', 'ChicAura') }}</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.7.2/font/bootstrap-icons.css">
    
    <style>
        body {
            background: linear-gradient(135deg, #e0e7ff 0%, #f8fafc 100%);
            min-height: 100vh;
            margin: 0;
            padding: 0;
        }
        
        .login-outer-container {
            width: 100vw;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background: transparent;
            padding: 2rem 0;
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
            flex-direction: column;
        }
        
        .login-logo-img {
            max-width: 140px;
            width: 100%;
            height: auto;
            display: block;
        }
        
        .login-welcome {
            margin-top: 1.25rem;
            color: #475569;
            font-size: 1rem;
            text-align: center;
            max-width: 180px;
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
            text-align: center;
        }
        
        .login-subtitle {
            font-size: 1.125rem;
            color: #64748b;
            margin-bottom: 2rem;
            text-align: center;
        }
        
        .form-label {
            color: #1e293b !important;
            font-weight: 600;
            margin-bottom: 0.5rem;
            display: block;
            font-size: 0.9rem;
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
            font-size: 1rem;
        }
        
        .form-input::placeholder {
            color: #94a3b8 !important;
        }
        
        .form-input:focus {
            background: #fff !important;
            border-color: #2563eb !important;
            outline: none;
            color: #1e293b !important;
            box-shadow: 0 0 0 0.2rem rgba(37, 99, 235, 0.25);
        }
        
        .password-field {
            position: relative;
        }
        
        .password-toggle {
            position: absolute;
            top: 50%;
            right: 0.75rem;
            transform: translateY(-50%);
            background: none;
            border: none;
            padding: 0;
            cursor: pointer;
            color: #6b7280;
        }
        
        .remember-section {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1rem;
        }
        
        .form-check {
            display: flex;
            align-items: center;
        }
        
        .form-check-input {
            height: 1rem;
            width: 1rem;
            margin-right: 0.5rem;
            accent-color: #2563eb;
        }
        
        .form-check-label {
            color: #374151;
            font-size: 0.875rem;
        }
        
        .forgot-password {
            color: #2563eb;
            text-decoration: none;
            font-size: 0.875rem;
            font-weight: 500;
            transition: color 0.2s;
        }
        
        .forgot-password:hover {
            color: #1d4ed8;
            text-decoration: underline;
        }
        
        .btn-signin {
            width: 100%;
            display: flex;
            justify-content: center;
            padding: 0.75rem 1rem;
            border: none;
            border-radius: 0.5rem;
            box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
            font-size: 0.875rem;
            font-weight: 600;
            color: white;
            background-color: #4f46e5;
            transition: all 0.2s;
            margin-bottom: 1.5rem;
        }
        
        .btn-signin:hover {
            background-color: #4338ca;
            transform: scale(1.05);
            color: white;
        }
        
        .divider {
            position: relative;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 1rem;
        }
        
        .divider::before {
            content: '';
            width: 100%;
            height: 1px;
            background-color: #e5e7eb;
        }
        
        .divider-text {
            position: absolute;
            background: #fff;
            color: #64748b;
            padding: 0 0.5rem;
            font-size: 0.875rem;
        }
        
        .btn-create-account {
            background: transparent;
            color: #2563eb;
            border: 2px solid #2563eb;
            border-radius: 0.5rem;
            width: 100%;
            display: flex;
            justify-content: center;
            padding: 0.75rem 1rem;
            font-size: 0.875rem;
            font-weight: 600;
            text-decoration: none;
            transition: all 0.2s;
        }
        
        .btn-create-account:hover {
            background: #2563eb;
            color: white;
        }
        
        .error-message {
            color: #dc2626;
            font-size: 0.875rem;
            margin-top: 0.25rem;
            display: block;
        }
        
        .is-invalid {
            border-color: #dc2626 !important;
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
                margin: 1rem;
            }
            
            .login-title {
                font-size: 1.5rem;
            }
            
            .login-card-left, .login-card-right {
                padding: 1.25rem 0.5rem;
            }
        }
    </style>
</head>
<body>
    <div class="login-outer-container">
        <div class="login-card">
            <div class="login-card-left">
                <img src="{{ asset('images/logo.png') }}" alt="ChicAura Logo" class="login-logo-img">
                <div class="login-welcome">
                    Empowering seamless collaboration across the fashion supply chain.
                </div>
            </div>
            
            <div class="login-card-right">
                <div class="login-title">Welcome Back</div>
                <div class="login-subtitle">Sign in to your ChicAura account</div>
                
                <form method="POST" action="{{ route('customer.login.store') }}">
                    @csrf

                    <div>
                        <label for="email" class="form-label">Email Address</label>
                        <input type="email" class="form-input @error('email') is-invalid @enderror" 
                               id="email" name="email" value="{{ old('email') }}" 
                               placeholder="eg.john@gmail.com" required autofocus>
                        @error('email')
                            <span class="error-message" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>

                    <div>
                        <label for="password" class="form-label">Password</label>
                        <div class="password-field">
                            <input type="password" class="form-input @error('password') is-invalid @enderror" 
                                   id="password" name="password" placeholder="Enter your password" required 
                                   style="padding-right: 2.5rem;">
                            <button type="button" id="togglePassword" class="password-toggle" aria-label="Show password">
                                <svg id="eyeIcon" xmlns="http://www.w3.org/2000/svg" width="22" height="22" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                </svg>
                            </button>
                        </div>
                        @error('password')
                            <span class="error-message" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>

                    
                    <div class="remember-section">
                        <div class="form-check">
                            <input type="checkbox" class="form-check-input" id="remember" name="remember"{{ old('remember') ? 'checked' : '' }}>
                            <label class="form-check-label" for="remember">
                                {{ __('Remember Me') }}
                            </label>
                        </div>
                         @if (Route::has('password.request'))
                        <a href="{{ route('password.request') }}" class="forgot-password">{{ __('Forgot Password?') }}</a>
                         @endif
                    </div>

                    <button type="submit" class="btn-signin">
                        Sign In
                    </button>

                    <div class="divider">
                        <span class="divider-text">New to ChicAura?</span>
                    </div>
                    
                    <a href="{{ route('customer.register') }}" class="btn-create-account">
                        Create Account
                    </a>
                </form>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
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
</body>
</html>