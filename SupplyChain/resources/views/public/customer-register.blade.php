<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Customer Registration - {{ config('app.name', 'ChicAura') }}</title>
    
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
            font-family: 'Figtree', 'Open Sans', 'Helvetica Neue', sans-serif;
        }
        
        .register-outer-container {
            width: 100vw;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background: transparent;
            padding: 2rem 0;
        }
        
        .register-card {
            display: flex;
            flex-direction: row;
            background: #fff;
            box-shadow: 0 8px 32px 0 rgba(31, 38, 135, 0.15);
            border-radius: 1.5rem;
            overflow: hidden;
            max-width: 1000px;
            width: 100%;
            min-height: 500px;
        }
        
        .register-card-left {
            background: #f1f5f9;
            display: flex;
            align-items: center;
            justify-content: center;
            min-width: 280px;
            width: 30%;
            padding: 2rem 1.5rem;
            flex-direction: column;
        }
        
        .register-logo-img {
            max-width: 140px;
            width: 100%;
            height: auto;
            display: block;
        }
        
        .register-welcome {
            margin-top: 1.25rem;
            color: #475569;
            font-size: 1rem;
            text-align: center;
            max-width: 200px;
        }
        
        .register-card-right {
            flex: 1;
            padding: 2rem 2rem;
            display: flex;
            flex-direction: column;
            justify-content: flex-start;
            overflow-y: auto;
            max-height: 80vh;
        }
        
        .register-title {
            font-size: 2rem;
            font-weight: 700;
            color: #1e293b;
            margin-bottom: 0.5rem;
            text-align: center;
        }
        
        .register-subtitle {
            font-size: 1rem;
            color: #64748b;
            margin-bottom: 1.5rem;
            text-align: center;
        }
        
        .section-title {
            color: #2563eb;
            font-size: 1rem;
            font-weight: 600;
            margin-bottom: 0.5rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }
        
        .section-divider {
            border-color: #e5e7eb;
            margin-bottom: 1rem;
        }
        
        .form-label {
            color: #1e293b !important;
            font-weight: 600;
            margin-bottom: 0.5rem;
            display: block;
            font-size: 0.9rem;
        }
        
        .form-input, .form-select, .form-textarea {
            background: #f1f5f9 !important;
            border: 2px solid #e5e7eb !important;
            color: #1e293b !important;
            border-radius: 0.5rem;
            padding: 0.75rem 1rem;
            width: 100%;
            margin-bottom: 1rem;
            transition: border-color 0.2s;
            font-size: 0.9rem;
        }
        
        .form-input::placeholder, .form-select::placeholder, .form-textarea::placeholder {
            color: #94a3b8 !important;
        }
        
        .form-input:focus, .form-select:focus, .form-textarea:focus {
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
        
        .form-check {
            display: flex;
            align-items: center;
            margin-bottom: 0.5rem;
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
        
        .btn-register {
            width: 100%;
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 0.5rem;
            padding: 0.75rem 1rem;
            border: none;
            border-radius: 0.5rem;
            box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
            font-size: 1rem;
            font-weight: 600;
            color: white;
            background-color: #4f46e5;
            transition: all 0.2s;
            margin: 1.5rem 0 1rem 0;
        }
        
        .btn-register:hover {
            background-color: #4338ca;
            transform: scale(1.02);
            color: white;
        }
        
        .login-link {
            color: #2563eb;
            text-decoration: none;
            font-weight: 500;
            transition: color 0.2s;
        }
        
        .login-link:hover {
            color: #1d4ed8;
            text-decoration: underline;
        }
        
        .error-message {
            color: #dc2626;
            font-size: 0.875rem;
            margin-top: -0.75rem;
            margin-bottom: 0.5rem;
            display: block;
        }
        
        .is-invalid {
            border-color: #dc2626 !important;
        }
        
        .row {
            margin-bottom: 0.5rem;
        }
        
        .col-md-6 {
            padding-right: 0.5rem;
            padding-left: 0.5rem;
        }
        
        @media (max-width: 900px) {
            .register-card {
                max-width: 98vw;
            }
        }
        
        @media (max-width: 768px) {
            .register-card {
                flex-direction: column;
                min-height: unset;
            }
            
            .register-card-left, .register-card-right {
                width: 100%;
                min-width: unset;
                padding: 2rem 1rem;
            }
            
            .register-card-left {
                justify-content: center;
                border-bottom: 1px solid #e5e7eb;
            }
            
            .register-card-right {
                max-height: none;
            }
            
            .col-md-6 {
                padding-right: 0;
                padding-left: 0;
            }
        }
        
        @media (max-width: 500px) {
            .register-card {
                border-radius: 0.5rem;
                margin: 1rem;
            }
            
            .register-title {
                font-size: 1.5rem;
            }
            
            .register-card-left, .register-card-right {
                padding: 1.25rem 0.75rem;
            }
        }
    </style>
</head>
<body>
    <div class="register-outer-container">
        <div class="register-card">
            <div class="register-card-left">
                <img src="{{ asset('images/logo.png') }}" alt="ChicAura Logo" class="register-logo-img">
                <div class="register-welcome">
                    Join ChicAura and experience seamless fashion collaboration across our supply chain network.
                </div>
            </div>
            
            <div class="register-card-right">
                <div class="register-title">Create Account</div>
                <div class="register-subtitle">Join ChicAura to complete your purchase</div>
                
                <form method="POST" action="{{ route('customer.register.store') }}">
                    @csrf

                    <!-- Basic Information -->
                    <div class="mb-3">
                        <div class="section-title">
                            <i class="bi bi-person"></i> Basic Information
                        </div>
                        <hr class="section-divider">
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <label for="name" class="form-label">Full Name *</label>
                            <input type="text" class="form-input @error('name') is-invalid @enderror" 
                                   id="name" name="name" value="{{ old('name') }}" required>
                            @error('name')
                                <span class="error-message">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label for="email" class="form-label">Email Address *</label>
                            <input type="email" class="form-input @error('email') is-invalid @enderror" 
                                   id="email" name="email" value="{{ old('email') }}" required>
                            @error('email')
                                <span class="error-message">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <label for="password" class="form-label">Password *</label>
                            <div class="password-field">
                                <input type="password" class="form-input @error('password') is-invalid @enderror" 
                                       id="password" name="password" required style="padding-right: 2.5rem;">
                                <button type="button" id="togglePassword" class="password-toggle" aria-label="Show password">
                                    <svg id="eyeIcon" xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                    </svg>
                                </button>
                            </div>
                            @error('password')
                                <span class="error-message">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label for="password_confirmation" class="form-label">Confirm Password *</label>
                            <input type="password" class="form-input" 
                                   id="password_confirmation" name="password_confirmation" required>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <label for="phone" class="form-label">Phone Number</label>
                            <input type="tel" class="form-input @error('phone') is-invalid @enderror" 
                                   id="phone" name="phone" value="{{ old('phone') }}">
                            @error('phone')
                                <span class="error-message">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label for="city" class="form-label">City *</label>
                            <input type="text" class="form-input @error('city') is-invalid @enderror" 
                                   id="city" name="city" value="{{ old('city') }}" required>
                            @error('city')
                                <span class="error-message">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="address" class="form-label">Address *</label>
                        <textarea class="form-textarea @error('address') is-invalid @enderror" 
                                  id="address" name="address" rows="2" required>{{ old('address') }}</textarea>
                        @error('address')
                            <span class="error-message">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Demographics -->
                    <div class="mb-3">
                        <div class="section-title">
                            <i class="bi bi-graph-up"></i> Help Us Serve You Better (Optional)
                        </div>
                        <p class="text-muted small mb-2">This information helps us provide personalized recommendations</p>
                        <hr class="section-divider">
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <label for="age_group" class="form-label">Age Group</label>
                            <select class="form-select @error('age_group') is-invalid @enderror" 
                                    id="age_group" name="age_group">
                                <option value="">Select Age Group</option>
                                @foreach($ageGroups as $value => $label)
                                    <option value="{{ $value }}" {{ old('age_group') == $value ? 'selected' : '' }}>
                                        {{ $label }}
                                    </option>
                                @endforeach
                            </select>
                            @error('age_group')
                                <span class="error-message">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label for="gender" class="form-label">Gender</label>
                            <select class="form-select @error('gender') is-invalid @enderror" 
                                    id="gender" name="gender">
                                <option value="">Select Gender</option>
                                <option value="male" {{ old('gender') == 'male' ? 'selected' : '' }}>Male</option>
                                <option value="female" {{ old('gender') == 'female' ? 'selected' : '' }}>Female</option>
                                <option value="other" {{ old('gender') == 'other' ? 'selected' : '' }}>Other</option>
                                <option value="prefer-not-to-say" {{ old('gender') == 'prefer-not-to-say' ? 'selected' : '' }}>Prefer not to say</option>
                            </select>
                            @error('gender')
                                <span class="error-message">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <label for="income_bracket" class="form-label">Income Bracket</label>
                            <select class="form-select @error('income_bracket') is-invalid @enderror" 
                                    id="income_bracket" name="income_bracket">
                                <option value="">Select Income Bracket</option>
                                @foreach($incomeBrackets as $value => $label)
                                    <option value="{{ $value }}" {{ old('income_bracket') == $value ? 'selected' : '' }}>
                                        {{ $label }}
                                    </option>
                                @endforeach
                            </select>
                            @error('income_bracket')
                                <span class="error-message">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label for="purchase_frequency" class="form-label">How Often Do You Shop?</label>
                            <select class="form-select @error('purchase_frequency') is-invalid @enderror" 
                                    id="purchase_frequency" name="purchase_frequency">
                                <option value="">Select Frequency</option>
                                @foreach($purchaseFrequencies as $value => $label)
                                    <option value="{{ $value }}" {{ old('purchase_frequency') == $value ? 'selected' : '' }}>
                                        {{ $label }}
                                    </option>
                                @endforeach
                            </select>
                            @error('purchase_frequency')
                                <span class="error-message">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Shopping Preferences (Select all that apply)</label>
                        <div class="row">
                            @foreach($shoppingPreferences as $value => $label)
                            <div class="col-md-6">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" 
                                           name="shopping_preferences[]" value="{{ $value }}" 
                                           id="pref_{{ $value }}"
                                           {{ in_array($value, old('shopping_preferences', [])) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="pref_{{ $value }}">
                                        {{ $label }}
                                    </label>
                                </div>
                            </div>
                            @endforeach
                        </div>
                        @error('shopping_preferences')
                            <span class="error-message">{{ $message }}</span>
                        @enderror
                    </div>

                    <button type="submit" class="btn-register">
                        <i class="bi bi-person-check"></i>
                        Create Account & Continue
                    </button>

                    <div class="text-center">
                        <p class="text-muted mb-0">
                            Already have an account? 
                            <a href="{{ route('customer.login') }}" class="login-link">Login here</a>
                        </p>
                    </div>
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