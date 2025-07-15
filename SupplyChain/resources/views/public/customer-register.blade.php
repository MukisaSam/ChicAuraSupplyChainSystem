@extends('layouts.public')

@section('title', 'Customer Registration')

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-md-8 col-lg-6">
            <div class="card shadow">
                <div class="card-header text-center">
                    <h4 class="mb-0"><i class="bi bi-person-plus"></i> Create Customer Account</h4>
                    <p class="text-muted mb-0">Join ChicAura to complete your purchase</p>
                </div>
                <div class="card-body p-4">
                    <form method="POST" action="{{ route('customer.register.store') }}">
                        @csrf

                        <!-- Basic Information -->
                        <div class="mb-4">
                            <h6 class="text-primary"><i class="bi bi-person"></i> Basic Information</h6>
                            <hr class="mt-2">
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="name" class="form-label">Full Name *</label>
                                <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                       id="name" name="name" value="{{ old('name') }}" required>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label for="email" class="form-label">Email Address *</label>
                                <input type="email" class="form-control @error('email') is-invalid @enderror" 
                                       id="email" name="email" value="{{ old('email') }}" required>
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="password" class="form-label">Password *</label>
                                <input type="password" class="form-control @error('password') is-invalid @enderror" 
                                       id="password" name="password" required>
                                @error('password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label for="password_confirmation" class="form-label">Confirm Password *</label>
                                <input type="password" class="form-control" 
                                       id="password_confirmation" name="password_confirmation" required>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="phone" class="form-label">Phone Number</label>
                                <input type="tel" class="form-control @error('phone') is-invalid @enderror" 
                                       id="phone" name="phone" value="{{ old('phone') }}">
                                @error('phone')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label for="city" class="form-label">City *</label>
                                <input type="text" class="form-control @error('city') is-invalid @enderror" 
                                       id="city" name="city" value="{{ old('city') }}" required>
                                @error('city')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="address" class="form-label">Address *</label>
                            <textarea class="form-control @error('address') is-invalid @enderror" 
                                      id="address" name="address" rows="2" required>{{ old('address') }}</textarea>
                            @error('address')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Demographics (for ML) -->
                        <div class="mb-4">
                            <h6 class="text-primary"><i class="bi bi-graph-up"></i> Help Us Serve You Better</h6>
                            <p class="text-muted small">This information helps us provide personalized recommendations</p>
                            <hr class="mt-2">
                        </div>

                        <div class="row mb-3">
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
                                    <div class="invalid-feedback">{{ $message }}</div>
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
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
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
                                    <div class="invalid-feedback">{{ $message }}</div>
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
                                    <div class="invalid-feedback">{{ $message }}</div>
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
                                <div class="text-danger small">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary btn-lg">
                                <i class="bi bi-person-check"></i> Create Account & Continue
                            </button>
                        </div>

                        <div class="text-center mt-3">
                            <p class="text-muted">
                                Already have an account? 
                                <a href="{{ route('customer.login') }}" class="text-primary text-decoration-none">Login here</a>
                            </p>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection