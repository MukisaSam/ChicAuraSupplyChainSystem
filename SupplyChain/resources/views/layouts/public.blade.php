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
    @vite(['resources/css/app.css', 'resources/js/app.js'])

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
            padding: 0.75rem 0;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        
        .navbar-brand {
            font-size: 1.75rem;
            font-weight: 700;
        }
        
        .navbar-toggler {
            border: none;
            padding: 0.25rem 0.5rem;
        }
        
        .navbar-toggler:focus {
            box-shadow: none;
        }
        
        /* Header Button Styles */
        .navbar-nav .nav-link {
            display: flex;
            align-items: center;
            padding: 0.5rem 1rem;
            transition: all 0.3s ease;
            font-weight: 500;
        }
        
        .navbar-nav .nav-link:hover {
            color: #0d6efd !important;
            transform: translateY(-1px);
        }
        
        .navbar-nav .btn {
            padding: 0.375rem 0.75rem;
            border-radius: 6px;
            font-weight: 500;
            transition: all 0.3s ease;
            font-size: 0.875rem;
            border-width: 1px;
            white-space: nowrap;
        }
        
        .navbar-nav .btn:hover {
            transform: translateY(-1px);
            box-shadow: 0 2px 8px rgba(0,0,0,0.15);
        }
        
        .navbar-nav .btn-outline-primary {
            border-color: #0d6efd;
            color: #0d6efd;
        }
        
        .navbar-nav .btn-outline-primary:hover {
            background-color: #0d6efd;
            border-color: #0d6efd;
            color: white;
        }
        
        .navbar-nav .btn-primary {
            background-color: #0d6efd;
            border-color: #0d6efd;
            color: white;
        }
        
        .navbar-nav .btn-primary:hover {
            background-color: #0b5ed7;
            border-color: #0b5ed7;
        }
        
        .navbar-nav .btn-secondary {
            background-color: #6c757d;
            border-color: #6c757d;
            color: white;
        }
        
        .navbar-nav .btn-secondary:hover {
            background-color: #5c636a;
            border-color: #5c636a;
        }
        
        /* Desktop right-aligned buttons */
        .navbar .d-lg-flex {
            margin-left: auto;
        }
        
        .navbar .d-lg-flex .nav-link {
            padding: 0.5rem 1rem;
            color: #6c757d;
            text-decoration: none;
            display: flex;
            align-items: center;
        }
        
        .navbar .d-lg-flex .nav-link:hover {
            color: #0d6efd;
        }
        
        .navbar .d-lg-flex .btn {
            padding: 0.375rem 0.75rem;
            font-size: 0.875rem;
            border-radius: 6px;
            font-weight: 500;
        }
        
        .navbar .d-lg-flex .btn:hover {
            transform: translateY(-1px);
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        
        
        /* Desktop button alignment */
        @media (min-width: 992px) {
            .navbar-nav {
                align-items: center;
            }
        }
        
        /* Mobile-first responsive design */
        @media (max-width: 991.98px) {
            .navbar {
                padding: 0.5rem 0;
            }
            
            .navbar-collapse {
                margin-top: 1rem;
                padding: 1rem;
                background: #f8f9fa;
                border-radius: 8px;
                border: 1px solid #dee2e6;
            }
            
            .navbar-nav {
                gap: 0.5rem;
            }
            
            .navbar-nav .nav-item {
                margin-bottom: 0.5rem;
            }
            
            .navbar-nav .nav-link {
                padding: 0.75rem 1rem;
                border-radius: 6px;
                text-align: center;
                background: white;
                border: 1px solid #dee2e6;
            }
            
            .navbar-nav .nav-link:hover {
                background-color: #e9ecef;
                transform: translateY(0);
            }
            
            .navbar-nav .btn {
                width: 100%;
                margin: 0.25rem 0;
                text-align: center;
                padding: 0.75rem 1rem;
                font-size: 0.95rem;
            }
            
            /* Mobile search form styling */
            .d-lg-none.mb-3 {
                background: white;
                padding: 1rem;
                border-radius: 6px;
                margin-bottom: 1rem !important;
                border: 1px solid #dee2e6;
            }
        }
        
        @media (max-width: 575.98px) {
            .navbar-brand {
                font-size: 1.5rem;
            }
            
            .navbar-collapse {
                margin: 0.75rem 0 0;
                padding: 1rem;
            }
            
            .navbar-nav .btn {
                font-size: 0.9rem;
                padding: 0.65rem 1rem;
            }
            
            .navbar-nav .nav-link {
                font-size: 0.9rem;
                padding: 0.65rem 1rem;
            }
        }
        
        .footer {
            background-color: #f8f9fa;
            margin-top: auto;
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
                <i class="bi bi-gem"></i> ChicAura
            </a>
        </div>

            <!-- Right-aligned buttons for desktop -->
            <div class="d-flex gap-3 mb-2 mt-2 ">
                <div>
                    <a class="nav-link position-relative" href="{{ route('public.cart') }}">
                        <i class="bi bi-cart3"></i>
                        <span class="cart-badge" id="cart-count">0</span>
                    </a>
                </div>

                @guest('customer')
                <div>
                    <a class="btn btn-outline-primary me-2" href="{{ route('customer.login') }}">
                        <i class="bi bi-box-arrow-in-right me-1"></i>Login
                    </a>
                </div>

                <div>
                    <a class="btn btn-outline-primary me-2" href="{{ route('customer.register') }}">
                        <i class="bi bi-person-plus me-1"></i>Sign Up
                    </a>
                </div>

                <div>
                    <a class="btn btn-outline-primary me-2" href="{{ route('welcome') }}">
                        <i class="bi bi-people"></i> Business Account
                    </a>
                </div>
                @endguest
    
    @auth('customer')
            <div>
                <a class="btn btn-outline-primary me-2" href="{{ route('customer.dashboard') }}">
                    <i class="bi bi-house"></i> Dashboard
                </a>
            </div>

            <div>
                <a class="btn btn-outline-primary me-2" href="{{ route('customer.profile') }}">
                    <i class="bi bi-person"></i> Profile
                </a>
            </div>

            <div>
                <a class="btn btn-outline-primary me-2" href="{{ route('customer.orders') }}">
                    <i class="bi bi-bag"></i> My Orders
                </a>
            </div>

            <div>
                <a class="btn btn-outline-primary me-2" href="{{ route('welcome') }}">
                    <i class="bi bi-people"></i> Business Account
                </a>
            </div>

            <form method="POST" action="{{ route('customer.logout') }}">
                    @csrf
                    <button type="submit" class="btn btn-outline-primary me-2">
                        <i class="bi bi-box-arrow-right"></i> Logout
                    </button>
            </form>
    @endauth
</div>

            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('public.home') ? 'active' : '' }}" href="{{ route('public.home') }}">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('public.products*') ? 'active' : '' }}" href="{{ route('public.products') }}">Products</a>
                    </li>
                </ul>
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
                        <i class="bi bi-gem"></i> ChicAura
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
                        <li><a href="{{ route('register.supplier') }}" class="text-muted text-decoration-none">Become a Supplier</a></li>
                        <li><a href="{{ route('register.wholesaler') }}" class="text-muted text-decoration-none">Become a Wholesaler</a></li>
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