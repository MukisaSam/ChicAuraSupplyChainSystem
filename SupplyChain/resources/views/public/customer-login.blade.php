@extends('layouts.public')

@section('title', 'Customer Login')

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-md-6 col-lg-4">
            <div class="card shadow">
                <div class="card-header text-center">
                    <h4 class="mb-0"><i class="bi bi-person"></i> Customer Login</h4>
                    <p class="text-muted mb-0">Welcome back!</p>
                </div>
                <div class="card-body p-4">
                    <form method="POST" action="{{ route('customer.login.store') }}">
                        @csrf

                        <div class="mb-3">
                            <label for="email" class="form-label">Email Address</label>
                            <input type="email" class="form-control @error('email') is-invalid @enderror" 
                                   id="email" name="email" value="{{ old('email') }}" required autofocus>
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="password" class="form-label">Password</label>
                            <input type="password" class="form-control @error('password') is-invalid @enderror" 
                                   id="password" name="password" required>
                            @error('password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3 form-check">
                            <input type="checkbox" class="form-check-input" id="remember" name="remember">
                            <label class="form-check-label" for="remember">
                                Remember me
                            </label>
                        </div>

                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary btn-lg">
                                <i class="bi bi-box-arrow-in-right"></i> Login
                            </button>
                        </div>

                        <div class="text-center mt-4">
                            <p class="text-muted">
                                Don't have an account? 
                                <a href="{{ route('customer.register') }}" class="text-primary text-decoration-none">Register here</a>
                            </p>
                            
                            <hr>
                            
                            <p class="text-muted">
                                Want to join our business network?
                                <br>
                                <a href="{{ route('login') }}" class="btn btn-outline-primary btn-sm mt-2">
                                    <i class="bi bi-people"></i> Business Login
                                </a>
                            </p>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection