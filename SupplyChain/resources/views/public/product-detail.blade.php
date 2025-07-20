@extends('layouts.public')

@section('title', $product->name)

@section('content')
<div class="container py-4">
    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('public.home') }}">Home</a></li>
            <li class="breadcrumb-item"><a href="{{ route('public.products') }}">Products</a></li>
            <li class="breadcrumb-item"><a href="{{ route('public.products', ['category' => $product->category]) }}">{{ ucwords(str_replace('_', ' ', $product->category)) }}</a></li>
            <li class="breadcrumb-item active" aria-current="page">{{ $product->name }}</li>
        </ol>
    </nav>

    <div class="row">
        <!-- Product Images -->
        <div class="col-lg-6 mb-4">
            <div class="card border-0">
                @if($product->image_url)
                    <img src="{{ $product->image_url }}" class="card-img-top rounded" alt="{{ $product->name }}" style="height: 500px; object-fit: cover;">
                @else
                    <div class="card-img-top bg-light d-flex align-items-center justify-content-center rounded" style="height: 500px;">
                        <i class="bi bi-image text-muted" style="font-size: 6rem;"></i>
                    </div>
                @endif
            </div>
        </div>

        <!-- Product Information -->
        <div class="col-lg-6">
            <div class="sticky-top" style="top: 2rem;">
                <!-- Product Title and Category -->
                <div class="mb-3">
                    <span class="badge bg-primary mb-2">{{ ucwords(str_replace('_', ' ', $product->category)) }}</span>
                    <h1 class="h2 mb-3">{{ $product->name }}</h1>
                    <div class="d-flex align-items-center mb-3">
                        <span class="h3 text-primary mb-0 me-3">UGX {{ number_format($product->base_price, 2) }}</span>
                        @if($product->stock_quantity > 0)
                            <span class="badge bg-success">In Stock ({{ $product->stock_quantity }} available)</span>
                        @else
                            <span class="badge bg-danger">Out of Stock</span>
                        @endif
                    </div>
                </div>

                <!-- Product Description -->
                <div class="mb-4">
                    <h5>Description</h5>
                    <p class="text-muted">{{ $product->description }}</p>
                </div>

                <!-- Product Details -->
                <div class="mb-4">
                    <h5>Product Details</h5>
                    <div class="row g-3">
                        @if($product->material)
                        <div class="col-sm-6">
                            <div class="d-flex align-items-center">
                                <i class="bi bi-tag text-primary me-2"></i>
                                <div>
                                    <small class="text-muted d-block">Material</small>
                                    <span>{{ $product->material }}</span>
                                </div>
                            </div>
                        </div>
                        @endif
                        
                        @if($product->size_range)
                        <div class="col-sm-6">
                            <div class="d-flex align-items-center">
                                <i class="bi bi-rulers text-primary me-2"></i>
                                <div>
                                    <small class="text-muted d-block">Sizes Available</small>
                                    <span>{{ $product->size_range }}</span>
                                </div>
                            </div>
                        </div>
                        @endif
                        
                        @if($product->color_options)
                        <div class="col-sm-6">
                            <div class="d-flex align-items-center">
                                <i class="bi bi-palette text-primary me-2"></i>
                                <div>
                                    <small class="text-muted d-block">Colors Available</small>
                                    <span>{{ $product->color_options }}</span>
                                </div>
                            </div>
                        </div>
                        @endif
                        
                        <div class="col-sm-6">
                            <div class="d-flex align-items-center">
                                <i class="bi bi-box text-primary me-2"></i>
                                <div>
                                    <small class="text-muted d-block">Product Type</small>
                                    <span>{{ ucwords(str_replace('_', ' ', $product->type)) }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Add to Cart Section -->
                <div class="card bg-light border-0 p-4 mb-4">
                    @if($product->stock_quantity > 0)
                    <form id="addToCartForm">
                        <!-- Size Selection -->
                        @if($product->size_range)
                        <div class="mb-3">
                            <label for="size" class="form-label small">Size <span class="text-danger">*</span></label>
                            <select class="form-select" id="size" required>
                                <option value="">Select Size</option>
                                @php
                                    $sizes = explode(',', $product->size_range);
                                @endphp
                                @foreach($sizes as $size)
                                    <option value="{{ trim($size) }}">{{ trim($size) }}</option>
                                @endforeach
                            </select>
                        </div>
                        @endif
                        
                        <!-- Color Selection -->
                        @if($product->color_options)
                        <div class="mb-3">
                            <label for="color" class="form-label small">Color</label>
                            <select class="form-select" id="color">
                                <option value="">Select Color (Optional)</option>
                                @php
                                    $colors = explode(',', $product->color_options);
                                @endphp
                                @foreach($colors as $color)
                                    <option value="{{ trim($color) }}">{{ trim($color) }}</option>
                                @endforeach
                            </select>
                        </div>
                        @endif
                        
                        <div class="row g-3 align-items-end">
                            <div class="col-4">
                                <label for="quantity" class="form-label small">Quantity</label>
                                <input type="number" class="form-control" id="quantity" min="1" max="{{ $product->stock_quantity }}" value="1" required onchange="validateQuantity()" oninput="validateQuantity()">
                                <small class="text-muted" id="stock-info">{{ $product->stock_quantity }} in stock</small>
                            </div>
                            <div class="col-8">
                                <button type="button" onclick="addProductToCart()" class="btn btn-primary w-100 btn-lg" id="addToCartBtn">
                                    <i class="bi bi-cart-plus me-2"></i>Add to Cart
                                </button>
                            </div>
                        </div>
                    </form>
                    @else
                    <div class="text-center">
                        <button class="btn btn-secondary w-100 btn-lg" disabled>
                            <i class="bi bi-x-circle me-2"></i>Out of Stock
                        </button>
                        <small class="text-muted d-block mt-2">This item is currently unavailable</small>
                    </div>
                    @endif
                </div>

                <!-- Additional Actions -->
                <div class="d-grid gap-2 d-md-flex">
                    <a href="{{ route('public.products') }}" class="btn btn-outline-secondary">
                        <i class="bi bi-arrow-left me-1"></i>Back to Products
                    </a>
                    <a href="{{ route('public.products', ['category' => $product->category]) }}" class="btn btn-outline-primary">
                        <i class="bi bi-grid me-1"></i>More in {{ ucwords(str_replace('_', ' ', $product->category)) }}
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Related Products -->
    @if($relatedProducts->count() > 0)
    <div class="mt-5">
        <h3 class="mb-4">Related Products</h3>
        <div class="row g-4">
            @foreach($relatedProducts as $relatedProduct)
            <div class="col-md-6 col-lg-3">
                <div class="card product-card h-100 border-0 shadow-sm" style="cursor: pointer;" onclick="window.location.href='{{ route('public.product.detail', $relatedProduct->id) }}'">
                    @if($relatedProduct->image_url)
                        <img src="{{ $relatedProduct->image_url }}" class="card-img-top" alt="{{ $relatedProduct->name }}" style="height: 200px; object-fit: cover;">
                    @else
                        <div class="card-img-top bg-light d-flex align-items-center justify-content-center" style="height: 200px;">
                            <i class="bi bi-image text-muted" style="font-size: 2rem;"></i>
                        </div>
                    @endif
                    
                    <div class="card-body d-flex flex-column">
                        <h6 class="card-title">{{ $relatedProduct->name }}</h6>
                        <p class="card-text text-muted small flex-grow-1">{{ Str::limit($relatedProduct->description, 60) }}</p>
                        <div class="d-flex justify-content-between align-items-center mt-auto">
                            <span class="h6 mb-0 text-primary">UGX {{ number_format($relatedProduct->base_price, 2) }}</span>
                            <a href="{{ route('public.product.detail', $relatedProduct->id) }}" class="btn btn-outline-primary btn-sm" onclick="event.stopPropagation();">
                                <i class="bi bi-eye me-1"></i>View
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @endif
</div>
@endsection

@push('styles')
<style>
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
    
    @media (min-width: 992px) {
        .sticky-top {
            position: sticky !important;
        }
    }
</style>
@endpush

@push('scripts')
<script>
    function addProductToCart() {
        const productId = {{ $product->id }};
        const quantityInput = document.getElementById('quantity');
        const quantity = parseInt(quantityInput.value);
        const stockQuantity = {{ $product->stock_quantity }};
        const sizeSelect = document.getElementById('size');
        const colorSelect = document.getElementById('color');
        const button = document.getElementById('addToCartBtn');
        
        // Validate quantity
        if (quantity < 1) {
            quantityInput.classList.add('is-invalid');
            showAlert('danger', 'Quantity must be at least 1');
            quantityInput.focus();
            return;
        }
        
        if (quantity > stockQuantity) {
            quantityInput.classList.add('is-invalid');
            showAlert('danger', `Cannot add ${quantity} items. Only ${stockQuantity} in stock.`);
            quantityInput.focus();
            return;
        }
        
        // Remove invalid class if validation passes
        quantityInput.classList.remove('is-invalid');
        
        // Validate size selection if required
        @if($product->size_range)
        if (!sizeSelect.value) {
            // Highlight the size field
            sizeSelect.classList.add('is-invalid');
            sizeSelect.focus();
            
            // Show error message
            showAlert('danger', 'Please select a size before adding to cart');
            return;
        } else {
            sizeSelect.classList.remove('is-invalid');
        }
        @endif
        
        // Get selected values
        const size = sizeSelect ? sizeSelect.value : null;
        const color = colorSelect ? colorSelect.value : null;
        
        // Add loading state
        const originalContent = button.innerHTML;
        button.disabled = true;
        button.innerHTML = '<i class="bi bi-hourglass-split me-2"></i>Adding...';
        
        // Use the global addToCart function
        addToCart(productId, quantity, size, color);
        
        // Restore button state after a short delay
        setTimeout(() => {
            button.disabled = false;
            button.innerHTML = originalContent;
        }, 2000);
    }
    
    // Real-time quantity validation
    function validateQuantity() {
        const quantityInput = document.getElementById('quantity');
        const stockInfo = document.getElementById('stock-info');
        const addToCartBtn = document.getElementById('addToCartBtn');
        const quantity = parseInt(quantityInput.value);
        const stockQuantity = {{ $product->stock_quantity }};
        
        if (quantity > stockQuantity) {
            quantityInput.classList.add('is-invalid');
            stockInfo.innerHTML = `<span class="text-danger">Only ${stockQuantity} in stock</span>`;
            addToCartBtn.disabled = true;
        } else if (quantity < 1) {
            quantityInput.classList.add('is-invalid');
            stockInfo.innerHTML = `<span class="text-danger">Quantity must be at least 1</span>`;
            addToCartBtn.disabled = true;
        } else {
            quantityInput.classList.remove('is-invalid');
            stockInfo.innerHTML = `${stockQuantity} in stock`;
            addToCartBtn.disabled = false;
        }
    }
    
    // Simple add to cart for related products (without size/color validation)
    function addToCartSimple(productId, quantity = 1) {
        addToCart(productId, quantity);
    }
    
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
</script>
@endpush