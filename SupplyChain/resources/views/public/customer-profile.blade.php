@extends('layouts.public')

@section('title', 'My Profile')

@section('content')
<div class="container py-5">
    <!-- Page Header -->
    <div class="mb-5">
        <h1 class="display-5 fw-bold text-primary mb-2">My Profile</h1>
        <p class="text-muted">Manage your account information and preferences</p>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="row">
        <!-- Profile Information -->
        <div class="col-lg-8">
            <div class="card shadow-sm border-0">
                <div class="card-body">
                    <h3 class="card-title h5 mb-4">
                        <i class="bi bi-person-circle text-primary me-2"></i>
                        Profile Information
                    </h3>

                    <form method="POST" action="{{ route('customer.profile.update') }}">
                        @csrf
                        @method('PUT')

                        <div class="row g-3">
                            <!-- Basic Information -->
                            <div class="col-md-6">
                                <label for="name" class="form-label">Full Name</label>
                                <input type="text" 
                                       class="form-control @error('name') is-invalid @enderror" 
                                       id="name" 
                                       name="name" 
                                       value="{{ old('name', $customer->name) }}" 
                                       required>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label for="email" class="form-label">Email Address</label>
                                <input type="email" 
                                       class="form-control @error('email') is-invalid @enderror" 
                                       id="email" 
                                       name="email" 
                                       value="{{ old('email', $customer->email) }}" 
                                       required>
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label for="phone" class="form-label">Phone Number</label>
                                <input type="tel" 
                                       class="form-control @error('phone') is-invalid @enderror" 
                                       id="phone" 
                                       name="phone" 
                                       value="{{ old('phone', $customer->phone) }}">
                                @error('phone')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label for="city" class="form-label">City</label>
                                <input type="text" 
                                       class="form-control @error('city') is-invalid @enderror" 
                                       id="city" 
                                       name="city" 
                                       value="{{ old('city', $customer->city) }}">
                                @error('city')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-12">
                                <label for="address" class="form-label">Address</label>
                                <textarea class="form-control @error('address') is-invalid @enderror" 
                                          id="address" 
                                          name="address" 
                                          rows="3">{{ old('address', $customer->address) }}</textarea>
                                @error('address')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Demographics Section -->
                            <!-- <div class="col-12">
                                <hr class="my-4">
                                <h4 class="h6 text-primary mb-3">
                                    <i class="bi bi-graph-up me-2"></i>
                                    Demographics & Preferences
                                </h4>
                                <p class="text-muted small mb-4">This information helps us provide better product recommendations</p>
                            </div> -->

                            <!-- <div class="col-md-6">
                                <label for="age_group" class="form-label">Age Group</label>
                                <select class="form-select @error('age_group') is-invalid @enderror" 
                                        id="age_group" 
                                        name="age_group">
                                    <option value="">Select Age Group</option>
                                    @foreach($ageGroups as $value => $label)
                                        <option value="{{ $value }}" 
                                                @if(old('age_group', $customer->age_group) == $value) selected @endif>
                                            {{ $label }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('age_group')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div> -->

                            <!-- <div class="col-md-6">
                                <label for="gender" class="form-label">Gender</label>
                                <select class="form-select @error('gender') is-invalid @enderror" 
                                        id="gender" 
                                        name="gender">
                                    <option value="">Select Gender</option>
                                    <option value="male" @if(old('gender', $customer->gender) == 'male') selected @endif>Male</option>
                                    <option value="female" @if(old('gender', $customer->gender) == 'female') selected @endif>Female</option>
                                    <option value="other" @if(old('gender', $customer->gender) == 'other') selected @endif>Other</option>
                                    <option value="prefer_not_to_say" @if(old('gender', $customer->gender) == 'prefer_not_to_say') selected @endif>Prefer not to say</option>
                                </select>
                                @error('gender')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div> -->

                            <!-- <div class="col-md-6">
                                <label for="income_bracket" class="form-label">Income Bracket</label>
                                <select class="form-select @error('income_bracket') is-invalid @enderror" 
                                        id="income_bracket" 
                                        name="income_bracket">
                                    <option value="">Select Income Bracket</option>
                                    @foreach($incomeBrackets as $value => $label)
                                        <option value="{{ $value }}" 
                                                @if(old('income_bracket', $customer->income_bracket) == $value) selected @endif>
                                            {{ $label }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('income_bracket')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div> -->

                            <!-- <div class="col-md-6">
                                <label for="purchase_frequency" class="form-label">Purchase Frequency</label>
                                <select class="form-select @error('purchase_frequency') is-invalid @enderror" 
                                        id="purchase_frequency" 
                                        name="purchase_frequency">
                                    <option value="">Select Purchase Frequency</option>
                                    @foreach($purchaseFrequencies as $value => $label)
                                        <option value="{{ $value }}" 
                                                @if(old('purchase_frequency', $customer->purchase_frequency) == $value) selected @endif>
                                            {{ $label }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('purchase_frequency')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div> -->

                            <!-- <div class="col-12">
                                <label class="form-label">Shopping Preferences</label>
                                <div class="row g-2">
                                    @foreach($shoppingPreferences as $value => $label)
                                        <div class="col-md-4">
                                            <div class="form-check">
                                                <input class="form-check-input" 
                                                       type="checkbox" 
                                                       id="pref_{{ $value }}" 
                                                       name="shopping_preferences[]" 
                                                       value="{{ $value }}"
                                                       @if(is_array(old('shopping_preferences', $customer->shopping_preferences ?? [])) && in_array($value, old('shopping_preferences', $customer->shopping_preferences ?? []))) checked @endif>
                                                <label class="form-check-label" for="pref_{{ $value }}">
                                                    {{ $label }}
                                                </label>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                                @error('shopping_preferences')
                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                            </div> -->

                            <div class="col-12">
                                <div class="d-flex justify-content-between align-items-center pt-3">
                                    <a href="{{ route('customer.dashboard') }}" class="btn btn-outline-secondary">
                                        <i class="bi bi-arrow-left me-1"></i> Back to Dashboard
                                    </a>
                                    <button type="submit" class="btn btn-primary">
                                        <i class="bi bi-check-circle me-1"></i> Update Profile
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Change Password -->
            <div class="card shadow-sm border-0 mt-4">
                <div class="card-body">
                    <h3 class="card-title h5 mb-4">
                        <i class="bi bi-shield-lock text-warning me-2"></i>
                        Change Password
                    </h3>

                    <form method="POST" action="{{ route('customer.password.update') }}">
                        @csrf
                        @method('PUT')

                        <div class="row g-3">
                            <div class="col-12">
                                <label for="current_password" class="form-label">Current Password</label>
                                <input type="password" 
                                       class="form-control @error('current_password') is-invalid @enderror" 
                                       id="current_password" 
                                       name="current_password" 
                                       required>
                                @error('current_password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-12">
                                <label for="password" class="form-label">New Password</label>
                                <input type="password" 
                                       class="form-control @error('password') is-invalid @enderror" 
                                       id="password" 
                                       name="password" 
                                       required>
                                @error('password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <div class="form-text">
                                    Password must be at least 8 characters long.
                                </div>
                            </div>

                            <div class="col-12">
                                <label for="password_confirmation" class="form-label">Confirm New Password</label>
                                <input type="password" 
                                       class="form-control" 
                                       id="password_confirmation" 
                                       name="password_confirmation" 
                                       required>
                            </div>

                            <div class="col-12">
                                <div class="d-grid">
                                    <button type="submit" class="btn btn-warning">
                                        <i class="bi bi-shield-check me-1"></i> Update Password
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Account Summary -->
        <div class="col-lg-4">
            <div class="card shadow-sm border-0">
                <div class="card-body">
                    <h3 class="card-title h5 mb-4">
                        <i class="bi bi-info-circle text-info me-2"></i>
                        Account Summary
                    </h3>

                    <div class="d-grid gap-3">
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="text-muted">Member Since</span>
                            <span class="fw-medium">{{ $customer->created_at->format('M Y') }}</span>
                        </div>

                        <div class="d-flex justify-content-between align-items-center">
                            <span class="text-muted">Account Status</span>
                            <span class="badge bg-success">Active</span>
                        </div>

                        <div class="d-flex justify-content-between align-items-center">
                            <span class="text-muted">Email Status</span>
                            <span class="badge {{ $customer->email_verified_at ? 'bg-success' : 'bg-warning' }}">
                                {{ $customer->email_verified_at ? 'Verified' : 'Unverified' }}
                            </span>
                        </div>

                        <div class="d-flex justify-content-between align-items-center">
                            <span class="text-muted">Last Updated</span>
                            <span class="fw-medium">{{ $customer->updated_at->format('M d, Y') }}</span>
                        </div>
                    </div>

                    <hr class="my-4">

                    <div class="d-grid gap-2">
                        <a href="{{ route('customer.orders') }}" class="btn btn-outline-primary btn-sm">
                            <i class="bi bi-bag-check me-1"></i> View My Orders
                        </a>
                        <a href="{{ route('public.products') }}" class="btn btn-outline-success btn-sm">
                            <i class="bi bi-shop me-1"></i> Continue Shopping
                        </a>
                    </div>
                </div>
            </div>

            <!-- Help Section -->
            <div class="card shadow-sm border-0 mt-4">
                <div class="card-body">
                    <h3 class="card-title h6 mb-3">
                        <i class="bi bi-question-circle text-warning me-2"></i>
                        Need Help?
                    </h3>
                    <p class="text-muted small mb-3">
                        Having trouble with your account? We're here to help!
                    </p>
                    <div class="d-grid">
                        <a href="#" class="btn btn-outline-warning btn-sm">
                            <i class="bi bi-chat-dots me-1"></i> Contact Support
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Auto-dismiss success alerts after 5 seconds
    const successAlert = document.querySelector('.alert-success');
    if (successAlert) {
        setTimeout(() => {
            successAlert.classList.remove('show');
            setTimeout(() => successAlert.remove(), 150);
        }, 5000);
    }
    
    console.log('Customer profile page loaded');
});
</script>
@endpush