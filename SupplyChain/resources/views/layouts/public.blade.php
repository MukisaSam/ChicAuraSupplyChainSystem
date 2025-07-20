<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'ChicAura') }} - @yield('title', 'Fashion Store')</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css" rel="stylesheet">

    <!-- Scripts -->
    <!-- @vite(['resources/css/app.css', 'resources/js/app.js']) -->

    <style>
        .navbar-brand {
            font-weight: 700;
            font-size: 1.5rem;
        }
        .cart-badge {
            background-color: #dc3545;
            color: white;
            border-radius: 50%;
            padding: 2px 6px;
            font-size: 0.75rem;
            position: absolute;
            top: -8px;
            right: -8px;
        }
        .product-card {
            transition: all 0.3s ease-in-out;
        }
        .product-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 8px 25px rgba(0,0,0,0.15) !important;
        }

        .product-card:hover .card-title {
            color: #0d6efd;
        }

        .product-card:hover .btn {
            transform: scale(1.05);
        }

        .product-card .btn {
            transition: transform 0.2s ease-in-out;
        }

        /* Enhanced Navbar Styles */
        .navbar {
            padding: 1rem 0;
            box-shadow: 0 2px 15px rgba(0,0,0,0.08);
            background: #ffffff !important;
            border-bottom: 1px solid rgba(0,0,0,0.05);
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            z-index: 1030;
            width: 100%;
        }

        /* Add top padding to body to account for fixed navbar */
        body {
            display: flex;
            flex-direction: column;
            min-height: 100vh;
            padding-top: 80px; /* Adjust based on your navbar height */
        }
        
        main {
            flex: 1;
        }

        /* Mobile adjustments */
        @media (max-width: 575.98px) {
            body {
                padding-top: 70px; /* Smaller padding for mobile */
            }
            
            .navbar {
                padding: 0.5rem 0;
            }
            
            .navbar-brand {
                font-size: 1.25rem;
            }
            
            .navbar-brand img {
                max-width: 80px !important;
            }
            
            .btn {
                font-size: 0.75rem;
                padding: 0.375rem 0.75rem;
            }
            
            .dropdown-menu {
                min-width: 160px;
            }
            
            .navbar-nav {
                padding-top: 1rem;
            }
            
            .nav-item {
                margin-bottom: 0.5rem;
            }
        }

        @media (min-width: 576px) and (max-width: 767.98px) {
            body {
                padding-top: 75px;
            }
            
            .navbar {
                padding: 0.75rem 0;
            }
            
            .navbar-brand {
                font-size: 1.5rem;
            }
            
            .navbar-brand img {
                max-width: 90px !important;
            }
            
            .btn {
                font-size: 0.8rem;
                padding: 0.4rem 0.8rem;
            }
        }

        @media (min-width: 768px) and (max-width: 991.98px) {
            body {
                padding-top: 80px;
            }
            
            .navbar-brand {
                font-size: 1.6rem;
            }
            
            .btn {
                font-size: 0.85rem;
                padding: 0.45rem 0.9rem;
            }
        }

        @media (min-width: 992px) {
            body {
                padding-top: 85px;
            }
            
            .navbar-nav {
                align-items: center;
            }
            
            .nav-item {
                margin-left: 0.5rem;
            }
        }

        @media (min-width: 1200px) {
            body {
                padding-top: 90px;
            }
            
            .btn {
                padding: 0.5rem 1rem;
            }
        }

        /* Navigation Links */
        .navbar-nav .nav-link {
            padding: 0.5rem 1rem;
            transition: all 0.3s ease;
            font-weight: 500;
        }

        .navbar-nav .nav-link:hover {
            color: #0d6efd !important;
        }

        /* Dropdown Styling */
        .dropdown-menu {
            border: 1px solid rgba(0,0,0,0.1);
            border-radius: 8px;
            padding: 0.5rem 0;
            margin-top: 0.5rem;
            min-width: 200px;
            box-shadow: 0 0.5rem 1rem rgba(0,0,0,0.15);
        }

        .dropdown-item {
            padding: 0.75rem 1rem;
            font-size: 0.875rem;
            transition: all 0.2s ease;
        }

        .dropdown-item:hover {
            background-color: #f8f9fa;
            color: #0d6efd;
        }

        .dropdown-item.text-danger:hover {
            background-color: #f8d7da;
            color: #dc3545;
        }

        /* Cart Badge Styling */
        .cart-badge {
            background: linear-gradient(135deg, #dc3545, #c82333);
            color: white;
            border-radius: 50%;
            padding: 2px 6px;
            font-size: 0.75rem;
            font-weight: 600;
            position: absolute;
            top: -8px;
            right: -8px;
            min-width: 18px;
            text-align: center;
            box-shadow: 0 2px 4px rgba(220,53,69,0.3);
        }

        /* Vertical Divider */
        .vr {
            width: 1px;
            background-color: rgba(0,0,0,0.1);
            height: 24px;
        }

        /* Mobile-first Responsive Design */
        @media (max-width: 575.98px) {
            .navbar {
                padding: 0.5rem 0;
            }
            
            .navbar-brand {
                font-size: 1.25rem;
            }
            
            .navbar-brand img {
                max-width: 80px !important;
            }
            
            .btn {
                font-size: 0.75rem;
                padding: 0.375rem 0.75rem;
            }
            
            .dropdown-menu {
                min-width: 160px;
            }
            
            .navbar-nav {
                padding-top: 1rem;
            }
            
            .nav-item {
                margin-bottom: 0.5rem;
            }
        }

        @media (min-width: 576px) and (max-width: 767.98px) {
            .navbar {
                padding: 0.75rem 0;
            }
            
            .navbar-brand {
                font-size: 1.5rem;
            }
            
            .navbar-brand img {
                max-width: 90px !important;
            }
            
            .btn {
                font-size: 0.8rem;
                padding: 0.4rem 0.8rem;
            }
        }

        @media (min-width: 768px) and (max-width: 991.98px) {
            .navbar-brand {
                font-size: 1.6rem;
            }
            
            .btn {
                font-size: 0.85rem;
                padding: 0.45rem 0.9rem;
            }
        }

        @media (min-width: 992px) {
            .navbar-nav {
                align-items: center;
            }
            
            .nav-item {
                margin-left: 0.5rem;
            }
        }

        @media (min-width: 1200px) {
            .btn {
                padding: 0.5rem 1rem;
            }
        }

        /* Collapsible Navigation Styling */
        .navbar-collapse {
            border-top: 1px solid rgba(0,0,0,0.1);
            margin-top: 1rem;
            padding-top: 1rem;
        }

        @media (min-width: 992px) {
            .navbar-collapse {
                border-top: none;
                margin-top: 0;
                padding-top: 0;
            }
        }

        /* Professional spacing and alignment */
        .d-flex.gap-2 > * {
            display: flex;
            align-items: center;
        }

        /* Ensure proper mobile menu behavior */
        .navbar-nav .dropdown-menu {
            position: static !important;
            transform: none !important;
            border: none;
            box-shadow: none;
            background-color: #f8f9fa;
            margin: 0.5rem 0;
        }

        @media (min-width: 992px) {
            .navbar-nav .dropdown-menu {
                position: absolute !important;
                background-color: white;
                border: 1px solid rgba(0,0,0,0.1);
                box-shadow: 0 0.5rem 1rem rgba(0,0,0,0.15);
                margin: 0.5rem 0 0 0;
            }
        }

        body {
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }
        main {
            flex: 1;
        }
    </style>

    @stack('styles')
</head>

<body class="font-sans antialiased">
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm">
        <div class="container">
            <a class="navbar-brand text-primary" href="{{ route('public.home') }}">
                <img src="{{ asset('images/logo.png') }}" alt="ChicAura" class="img-fluid" style="max-width: 100px;"/>
            </a>

            <!-- Mobile toggle button -->
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <!-- Collapsible navigation content -->
            <div class="collapse navbar-collapse" id="navbarNav">
                <div class="navbar-nav ms-auto">
                    <!-- Shopping Cart -->
                    <div class="nav-item position-relative me-2">
                        <a class="btn btn-outline-secondary d-flex align-items-center" href="{{ route('public.cart') }}">
                            <i class="bi bi-cart3 me-1"></i>
                            <span class="d-none d-sm-inline">Cart</span>
                            <span class="cart-badge" id="cart-count">0</span>
                        </a>
                    </div>

                    @guest('customer')
                    <!-- Guest Navigation -->
                    <div class="nav-item dropdown d-lg-none">
                        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="bi bi-person-circle me-1"></i>Account
                        </a>
                        <ul class="dropdown-menu">
                            <li>
                                <a class="dropdown-item d-flex align-items-center" href="{{ route('customer.login') }}">
                                    <i class="bi bi-box-arrow-in-right me-2"></i>Sign In
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item d-flex align-items-center" href="{{ route('customer.register') }}">
                                    <i class="bi bi-person-plus me-2"></i>Sign Up
                                </a>
                            </li>
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <a class="dropdown-item d-flex align-items-center" href="{{ route('welcome') }}">
                                    <i class="bi bi-briefcase me-2"></i>Business Portal
                                </a>
                            </li>
                        </ul>
                    </div>

                    <!-- Desktop Guest Navigation -->
                    <div class="d-none d-lg-flex align-items-center gap-2">
                        <a class="btn btn-outline-primary d-flex align-items-center" href="{{ route('customer.login') }}">
                            <i class="bi bi-box-arrow-in-right me-1"></i>
                            <span class="d-none d-xl-inline">Sign In</span>
                        </a>
                        
                        <a class="btn btn-primary d-flex align-items-center" href="{{ route('customer.register') }}">
                            <i class="bi bi-person-plus me-1"></i>
                            <span class="d-none d-xl-inline">Sign Up</span>
                        </a>
                        
                        <div class="vr mx-2"></div>
                        
                        <a class="btn btn-outline-dark d-flex align-items-center" href="{{ route('welcome') }}">
                            <i class="bi bi-briefcase me-1"></i>
                            <span class="d-none d-xl-inline">Business Portal</span>
                        </a>
                    </div>
                    @endguest

                    @auth('customer')
                    <!-- Authenticated Customer Navigation -->
                    <div class="nav-item dropdown">
                        <button class="btn btn-outline-secondary dropdown-toggle d-flex align-items-center" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="bi bi-person-circle me-1"></i>
                            <span class="d-none d-sm-inline">{{ Auth::guard('customer')->user()->name ?? 'Account' }}</span>
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end shadow">
                            <li>
                                <a class="dropdown-item d-flex align-items-center" href="{{ route('customer.dashboard') }}">
                                    <i class="bi bi-house me-2"></i>Home
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item d-flex align-items-center" href="{{ route('customer.orders') }}">
                                    <i class="bi bi-bag me-2"></i>My Orders
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item d-flex align-items-center" href="{{ route('customer.profile') }}">
                                    <i class="bi bi-person me-2"></i>Profile Settings
                                </a>
                            </li>
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <a class="dropdown-item d-flex align-items-center" href="{{ route('welcome') }}">
                                    <i class="bi bi-briefcase me-2"></i>Business Portal
                                </a>
                            </li>
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <form method="POST" action="{{ route('customer.logout') }}" class="d-inline">
                                    @csrf
                                    <button type="submit" class="dropdown-item d-flex align-items-center text-danger">
                                        <i class="bi bi-box-arrow-right me-2"></i>Sign Out
                                    </button>
                                </form>
                            </li>
                        </ul>
                    </div>
                    @endauth
                </div>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main>
        <!-- Flash Messages -->
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show m-0" role="alert">
                <div class="container">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show m-0" role="alert">
                <div class="container">
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            </div>
        @endif

        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="footer py-5">
        <div class="container">
            <div class="row">
                <div class="col-lg-4 mb-4">
                    <h5 class="text-primary">
                        <a class="navbar-brand text-primary" href="{{ route('public.home') }}">
                <img src="{{ asset('images/logo.png') }}" alt="ChicAura" class="img-fluid" style="max-width: 80px;"/>
            </a>
                    </h5>
                    <p class="text-muted">Your premier destination for fashion and style. Connecting suppliers, manufacturers, and customers in one seamless platform.</p>
                </div>
                <div class="col-lg-2 mb-4">

                </div>
                <div class="col-lg-2 mb-4">
                    <h6>Customer Care</h6>
                    <ul class="list-unstyled">
                        <li><a href="#" class="text-muted text-decoration-none">Contact Us</a></li>
                        <li><a href="#" class="text-muted text-decoration-none">Shipping Info</a></li>
                        <li><a href="#" class="text-muted text-decoration-none">Returns</a></li>
                    </ul>
                </div>
                <div class="col-lg-2 mb-4">
                    <h6>Business</h6>
                    <ul class="list-unstyled">
                        <li><a href="{{ route('register') }}" class="text-muted text-decoration-none">Be our Supplier</a></li>
                        <li><a href="{{ route('register') }}" class="text-muted text-decoration-none">Be our Wholesaler</a></li>
                    </ul>
                </div>
                <div class="col-lg-2 mb-4">
                    <h6>Connect</h6>
                    <ul class="list-unstyled">
                        <li><a href="#" class="text-muted text-decoration-none">
                            <i class="bi bi-facebook"></i> Facebook
                        </a></li>
                        <li><a href="#" class="text-muted text-decoration-none">
                            <i class="bi bi-instagram"></i> Instagram
                        </a></li>
                        <li><a href="#" class="text-muted text-decoration-none">
                            <i class="bi bi-twitter"></i> Twitter
                        </a></li>
                    </ul>
                </div>
            </div>
            <hr>
            <div class="row">
                <div class="col-md-6">
                    <p class="text-muted mb-0">&copy; {{ date('Y') }} ChicAura. All rights reserved.</p>
                </div>
                <div class="col-md-6 text-md-end">
                    <p class="text-muted mb-0">
                        <a href="#" class="text-muted text-decoration-none">Privacy Policy</a> |
                        <a href="#" class="text-muted text-decoration-none">Terms of Service</a>
                    </p>
                </div>
            </div>
        </div>
    </footer>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Custom Scripts -->
    <script>
        // Update cart count on page load
        document.addEventListener('DOMContentLoaded', function() {
            updateCartCount();
        });

        function updateCartCount() {
            fetch('/cart/count')
                .then(response => response.json())
                .then(data => {
                    document.getElementById('cart-count').textContent = data.count;
                })
                .catch(error => console.error('Error updating cart count:', error));
        }

        // Add to cart function
        function addToCart(productId, quantity = 1, size = null, color = null) {
            const formData = new FormData();
            formData.append('product_id', productId);
            formData.append('quantity', quantity);
            if (size) formData.append('size', size);
            if (color) formData.append('color', color);
            formData.append('_token', document.querySelector('meta[name="csrf-token"]').getAttribute('content'));

            fetch('/cart/add', {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    updateCartCount();
                    showAlert('success', data.message);
                } else {
                    showAlert('danger', data.message);
                }
            })
            .catch(error => {
                console.error('Error adding to cart:', error);
                showAlert('danger', 'Error adding item to cart');
            });
        }

        function showAlert(type, message) {
            const alertHtml = `
                <div class="alert alert-${type} alert-dismissible fade show" role="alert">
                    ${message}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            `;
            const alertContainer = document.createElement('div');
            alertContainer.innerHTML = alertHtml;
            document.body.insertBefore(alertContainer.firstElementChild, document.body.firstChild);
        }
    </script>

    @stack('scripts')
</body>
</html>
