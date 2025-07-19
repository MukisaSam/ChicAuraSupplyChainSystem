@extends('layouts.public')

@section('title', 'Home')

@section('content')
<!-- Hero Section -->
<section class="bg-primary text-white py-5">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-6">
                <h1 class="display-4 fw-bold mb-3">Welcome to ChicAura</h1>
                <p class="lead mb-4">Discover the latest fashion trends and timeless classics. From casual wear to luxury items, find everything you need to express your unique style.</p>
                <div class="d-flex gap-3 mb-4">
                    <a href="{{ route('public.products') }}" class="btn btn-light btn-lg">
                        <i class="bi bi-bag"></i> Shop Now
                    </a>
                    <a href="#featured" class="btn btn-outline-light btn-lg">
                        <i class="bi bi-arrow-down"></i> Explore
                    </a>
                </div>
            </div>
            <div class="col-lg-6">
                <img src="{{ asset('images/showroom.png') }}" alt="Fashion Collection" class="img-fluid rounded">
            </div>
        </div>
    </div>
</section>

<!-- Categories Section -->
<section class="py-5 bg-light">
    <div class="container">
        <div class="text-center mb-5">
            <h2 class="fw-bold">Shop by Category</h2>
            <p class="text-muted">Find exactly what you're looking for</p>
        </div>
        
        <div class="position-relative">
            <!-- Left Arrow -->
            <button class="btn btn-outline-primary categories-nav-btn categories-prev" id="categoriesPrev" type="button">
                <i class="bi bi-chevron-left"></i>
            </button>
            
            <!-- Categories Container -->
            <div class="categories-container" id="categoriesContainer">
                <div class="categories-wrapper d-flex">
                    @foreach($categories as $category)
                    <div class="category-item flex-shrink-0">
                        <a href="{{ route('public.products', ['category' => $category]) }}" class="text-decoration-none">
                            <div class="card h-100 border-0 shadow-sm category-card">
                                <div class="card-body text-center p-4">
                                    <i class="bi bi-tags display-6 text-primary mb-3"></i>
                                    <h6 class="card-title text-capitalize mb-2">{{ str_replace('_', ' ', $category) }}</h6>
                                    <p class="card-text text-muted small">Explore {{ $category }}</p>
                                </div>
                            </div>
                        </a>
                    </div>
                    @endforeach
                </div>
            </div>
            
            <!-- Right Arrow -->
            <button class="btn btn-outline-primary categories-nav-btn categories-next" id="categoriesNext" type="button">
                <i class="bi bi-chevron-right"></i>
            </button>
        </div>
    </div>
</section>

<!-- Featured Products Section -->
<section id="featured" class="py-5">
    <div class="container">
        <div class="text-center mb-5">
            <h2 class="fw-bold">Featured Products</h2>
            <p class="text-muted">Handpicked items just for you</p>
        </div>
        
        @if($featuredProducts->count() > 0)
        <div class="row g-4">
            @foreach($featuredProducts as $product)
            <div class="col-md-6 col-lg-3">
                <div class="card product-card h-100 border-0 shadow-sm" style="cursor: pointer;" onclick="window.location.href='{{ route('public.product.detail', $product->id) }}'">
                    @if($product->image_url)
                        <img src="{{ $product->image_url }}" class="card-img-top" alt="{{ $product->name }}" style="height: 250px; object-fit: cover;">
                    @else
                        <div class="card-img-top bg-light d-flex align-items-center justify-content-center" style="height: 250px;">
                            <i class="bi bi-image text-muted" style="font-size: 3rem;"></i>
                        </div>
                    @endif
                    
                    <div class="card-body d-flex flex-column">
                        <h6 class="card-title">{{ $product->name }}</h6>
                        <p class="card-text text-muted small flex-grow-1">{{ Str::limit($product->description, 80) }}</p>
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="h5 mb-0 text-primary">${{ number_format($product->base_price, 2) }}</span>
                            <a href="{{ route('public.product.detail', $product->id) }}" class="btn btn-outline-primary btn-sm" onclick="event.stopPropagation();">
                                <i class="bi bi-eye me-1"></i>View Details
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        
        <div class="text-center mt-5">
            <a href="{{ route('public.products') }}" class="btn btn-primary btn-lg">
                <i class="bi bi-grid"></i> View All Products
            </a>
        </div>
        @else
        <div class="text-center py-5">
            <i class="bi bi-box display-4 text-muted mb-3"></i>
            <h4 class="text-muted">No products available yet</h4>
            <p class="text-muted">Check back soon for our latest collection!</p>
        </div>
        @endif
    </div>
</section>

<!-- Features Section -->
<section class="py-5 bg-light">
    <div class="container">
        <div class="row g-4">
            <div class="col-md-6 col-lg-3">
                <div class="text-center">
                    <i class="bi bi-truck display-4 text-primary mb-3"></i>
                    <h5>Free Shipping</h5>
                    <p class="text-muted">Free shipping on orders over $50</p>
                </div>
            </div>
            <div class="col-md-6 col-lg-3">
                <div class="text-center">
                    <i class="bi bi-arrow-return-left display-4 text-primary mb-3"></i>
                    <h5>Easy Returns</h5>
                    <p class="text-muted">30-day return policy</p>
                </div>
            </div>
            <div class="col-md-6 col-lg-3">
                <div class="text-center">
                    <i class="bi bi-shield-check display-4 text-primary mb-3"></i>
                    <h5>Secure Payment</h5>
                    <p class="text-muted">Your payment information is safe</p>
                </div>
            </div>
            <div class="col-md-6 col-lg-3">
                <div class="text-center">
                    <i class="bi bi-headset display-4 text-primary mb-3"></i>
                    <h5>24/7 Support</h5>
                    <p class="text-muted">We're here to help anytime</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Customer Welcome Section (for logged in customers) -->
@auth('customer')
<section class="py-5 bg-light">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-8">
                <h3 class="fw-bold mb-3">
                    <i class="bi bi-person-circle text-primary"></i> 
                    Welcome back, {{ Auth::guard('customer')->user()->name }}!
                </h3>
                <p class="lead mb-0">Continue shopping and discover new arrivals tailored just for you.</p>
            </div>
            <div class="col-lg-4 text-lg-end">
                <div class="btn-group">
                    <a href="{{ route('customer.profile') }}" class="btn btn-outline-primary">
                        <i class="bi bi-person"></i> My Profile
                    </a>
                    <a href="{{ route('customer.orders') }}" class="btn btn-primary">
                        <i class="bi bi-bag"></i> My Orders
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>
@endauth

<!-- Call to Action for Business Partners -->
<section class="py-5 bg-primary text-white">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-8">
                <h3 class="fw-bold mb-3">Want to Join Our Business Network?</h3>
                <p class="lead mb-0">Connect with suppliers, manufacturers, and wholesalers. Grow your business with ChicAura's comprehensive supply chain platform.</p>
            </div>
            <div class="col-lg-4 text-lg-end">
                <a href="{{ route('welcome') }}" class="btn btn-light btn-lg">
                    <i class="bi bi-people"></i> Join Our Team
                </a>
            </div>
        </div>
    </div>
</section>
@endsection

@push('styles')
<style>
    .category-card {
        transition: transform 0.2s ease-in-out;
    }
    .category-card:hover {
        transform: translateY(-5px);
    }
    .hero-image {
        border-radius: 15px;
    }
    
    /* Horizontal Categories Scrolling Styles */
    .categories-container {
        overflow-x: auto;
        scroll-behavior: smooth;
        scrollbar-width: none; /* Firefox */
        -ms-overflow-style: none; /* IE and Edge */
        margin: 0 60px; /* Space for arrows */
        position: relative;
    }
    
    .categories-container::-webkit-scrollbar {
        display: none; /* Chrome, Safari, Opera */
    }
    
    .categories-wrapper {
        gap: 1rem;
        padding: 0.5rem 0;
    }
    
    .category-item {
        min-width: 220px;
        max-width: 220px;
    }
    
    .categories-nav-btn {
        position: absolute;
        top: 50%;
        transform: translateY(-50%);
        z-index: 10;
        width: 45px;
        height: 45px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        background: white;
        border: 2px solid #0d6efd;
        box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        transition: all 0.3s ease;
    }
    
    .categories-nav-btn:hover {
        background: #0d6efd;
        color: white;
        transform: translateY(-50%) scale(1.1);
        box-shadow: 0 4px 15px rgba(13, 110, 253, 0.3);
    }
    
    .categories-nav-btn:disabled {
        opacity: 0.3;
        cursor: not-allowed;
        transform: translateY(-50%);
    }
    
    .categories-nav-btn:disabled:hover {
        background: white;
        color: #0d6efd;
        transform: translateY(-50%);
        box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    }
    
    .categories-prev {
        left: 0;
    }
    
    .categories-next {
        right: 0;
    }
    
    /* Mobile Responsiveness */
    @media (max-width: 768px) {
        .categories-container {
            margin: 0 50px;
        }
        
        .categories-nav-btn {
            width: 40px;
            height: 40px;
            font-size: 0.9rem;
        }
        
        .category-item {
            min-width: 180px;
            max-width: 180px;
        }
        
        .category-card .card-body {
            padding: 1rem !important;
        }
        
        .category-card .display-6 {
            font-size: 2rem !important;
        }
    }
    
    @media (max-width: 576px) {
        .categories-container {
            margin: 0 45px;
        }
        
        .categories-nav-btn {
            width: 35px;
            height: 35px;
            font-size: 0.8rem;
        }
        
        .category-item {
            min-width: 160px;
            max-width: 160px;
        }
    }
    
    /* Hide arrows on very small screens */
    @media (max-width: 480px) {
        .categories-nav-btn {
            display: none;
        }
        
        .categories-container {
            margin: 0;
        }
    }
    
    /* Authentication Section Styles */
    .bg-gradient {
        position: relative;
    }
    .bg-gradient::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><defs><pattern id="grain" width="100" height="100" patternUnits="userSpaceOnUse"><circle cx="25" cy="25" r="1" fill="white" opacity="0.1"/><circle cx="75" cy="75" r="1" fill="white" opacity="0.1"/><circle cx="50" cy="10" r="0.5" fill="white" opacity="0.05"/></pattern></defs><rect width="100" height="100" fill="url(%23grain)"/></svg>');
        pointer-events: none;
    }
    
    .auth-card {
        backdrop-filter: blur(10px);
        border: 1px solid rgba(255,255,255,0.1);
    }
    
    .auth-card .card-header {
        border-bottom: 1px solid rgba(0,0,0,0.05);
    }
    
    .form-control:focus {
        border-color: #667eea;
        box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
    }
    
    .btn-primary {
        background: linear-gradient(45deg, #667eea, #764ba2);
        border: none;
        transition: all 0.3s ease;
    }
    
    .btn-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 15px rgba(102, 126, 234, 0.4);
    }
    
    .btn-success {
        background: linear-gradient(45deg, #56ab2f, #a8e6cf);
        border: none;
        transition: all 0.3s ease;
    }
    
    .btn-success:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 15px rgba(86, 171, 47, 0.4);
    }
    
    @media (max-width: 991.98px) {
        .auth-card .card-body {
            padding: 1.5rem !important;
        }
        .row.g-4 > .col-lg-6:first-child {
            margin-bottom: 1rem;
        }
    }
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Categories Horizontal Scroll Functionality
    const categoriesContainer = document.getElementById('categoriesContainer');
    const prevBtn = document.getElementById('categoriesPrev');
    const nextBtn = document.getElementById('categoriesNext');
    
    if (categoriesContainer && prevBtn && nextBtn) {
        const scrollAmount = 240; // Width of one category item + gap
        
        // Check scroll position and update button states
        function updateButtonStates() {
            const isAtStart = categoriesContainer.scrollLeft <= 0;
            const isAtEnd = categoriesContainer.scrollLeft >= 
                (categoriesContainer.scrollWidth - categoriesContainer.clientWidth - 5);
            
            prevBtn.disabled = isAtStart;
            nextBtn.disabled = isAtEnd;
        }
        
        // Initial button state
        updateButtonStates();
        
        // Previous button click
        prevBtn.addEventListener('click', function() {
            categoriesContainer.scrollBy({
                left: -scrollAmount,
                behavior: 'smooth'
            });
        });
        
        // Next button click
        nextBtn.addEventListener('click', function() {
            categoriesContainer.scrollBy({
                left: scrollAmount,
                behavior: 'smooth'
            });
        });
        
        // Update button states on scroll
        categoriesContainer.addEventListener('scroll', updateButtonStates);
        
        // Handle window resize
        window.addEventListener('resize', updateButtonStates);
        
        // Mouse wheel support for horizontal scrolling
        categoriesContainer.addEventListener('wheel', function(e) {
            if (e.deltaY !== 0) {
                e.preventDefault();
                this.scrollLeft += e.deltaY > 0 ? 50 : -50;
            }
        });
    }

    // Form validation and enhancement (existing code)
    const loginForm = document.getElementById('homeLoginForm');
    const registerForm = document.getElementById('homeRegisterForm');
    
    // Enhanced form submission with loading states
    if (loginForm) {
        loginForm.addEventListener('submit', function(e) {
            const submitBtn = this.querySelector('button[type="submit"]');
            const originalContent = submitBtn.innerHTML;
            
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<i class="bi bi-hourglass-split"></i> Logging in...';
            
            // Re-enable after a delay if form doesn't submit (for error cases)
            setTimeout(() => {
                submitBtn.disabled = false;
                submitBtn.innerHTML = originalContent;
            }, 5000);
        });
    }
    
    if (registerForm) {
        registerForm.addEventListener('submit', function(e) {
            const submitBtn = this.querySelector('button[type="submit"]');
            const originalContent = submitBtn.innerHTML;
            
            // Validate password confirmation
            const password = document.getElementById('home_reg_password').value;
            const passwordConfirm = document.getElementById('home_reg_password_confirmation').value;
            
            if (password !== passwordConfirm) {
                e.preventDefault();
                showAlert('danger', 'Passwords do not match');
                return;
            }
            
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<i class="bi bi-hourglass-split"></i> Creating Account...';
            
            // Re-enable after a delay if form doesn't submit
            setTimeout(() => {
                submitBtn.disabled = false;
                submitBtn.innerHTML = originalContent;
            }, 5000);
        });
        
        // Real-time password confirmation validation
        const passwordField = document.getElementById('home_reg_password');
        const confirmField = document.getElementById('home_reg_password_confirmation');
        
        function validatePasswords() {
            if (confirmField.value && passwordField.value !== confirmField.value) {
                confirmField.classList.add('is-invalid');
                confirmField.classList.remove('is-valid');
            } else if (confirmField.value) {
                confirmField.classList.remove('is-invalid');
                confirmField.classList.add('is-valid');
            }
        }
        
        if (passwordField && confirmField) {
            passwordField.addEventListener('input', validatePasswords);
            confirmField.addEventListener('input', validatePasswords);
        }
    }
    
    // Show alerts function
    function showAlert(type, message) {
        const alertHtml = `
            <div class="alert alert-${type} alert-dismissible fade show position-fixed" style="top: 70px; right: 20px; z-index: 1050; max-width: 400px;" role="alert">
                ${message}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        `;
        const alertContainer = document.createElement('div');
        alertContainer.innerHTML = alertHtml;
        document.body.appendChild(alertContainer.firstElementChild);
        
        // Auto remove after 5 seconds
        setTimeout(() => {
            const alert = document.querySelector('.alert');
            if (alert) {
                alert.remove();
            }
        }, 5000);
    }
    
    // Smooth scrolling to auth section when clicking from other parts
    const authLinks = document.querySelectorAll('a[href*="login"], a[href*="register"]');
    authLinks.forEach(link => {
        link.addEventListener('click', function(e) {
            const href = this.getAttribute('href');
            if (href.includes('customer.login') || href.includes('customer.register')) {
                // Check if we're on home page and auth section exists
                const authSection = document.querySelector('.bg-gradient');
                if (authSection && window.location.pathname === '/') {
                    e.preventDefault();
                    authSection.scrollIntoView({ 
                        behavior: 'smooth',
                        block: 'center'
                    });
                }
            }
        });
    });
});
</script>
@endpush