@extends('layouts.public')

@section('title', 'Products')

@section('content')
<div class="container py-4">
    <!-- Page Header -->
    <div class="row mb-4">
        <div class="col-12">
            <h1 class="h2 mb-3">
                @if(request('search') || request('q'))
                    Search Results
                    @if(request('q'))
                        for "{{ request('q') }}"
                    @endif
                @elseif(request('category'))
                    {{ ucwords(str_replace('_', ' ', request('category'))) }}
                @else
                    All Products
                @endif
            </h1>
            <p class="text-muted mb-0">{{ $products->total() }} product(s) found</p>
        </div>
    </div>

    <form class=" mb-2" action="{{ route('public.search') }}" method="GET">
                    <div class="input-group">
                        <input class="form-control" type="search" name="q" placeholder="Search products..." 
                               value="{{ request('q') }}">
                        <button class="btn btn-outline-primary" type="submit">
                            <i class="bi bi-search"></i>
                        </button>
                    </div>
    </form>

    <div class="row">
        <!-- Filters Sidebar -->
        <div class="col-lg-3 mb-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0"><i class="bi bi-funnel"></i> Filters</h5>
                </div>
                <div class="card-body">
                    <form method="GET" action="{{ route('public.products') }}">
                        <!-- Search -->
                        @if(request('q'))
                            <input type="hidden" name="q" value="{{ request('q') }}">
                        @endif
                        
                        <!-- Category Filter -->
                        <div class="mb-4">
                            <h6>Category</h6>
                            @foreach($categories as $category)
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="category" 
                                       value="{{ $category }}" id="cat_{{ $loop->index }}"
                                       {{ request('category') == $category ? 'checked' : '' }}>
                                <label class="form-check-label" for="cat_{{ $loop->index }}">
                                    {{ ucwords(str_replace('_', ' ', $category)) }}
                                </label>
                            </div>
                            @endforeach
                            @if(request('category'))
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="category" 
                                       value="" id="cat_all">
                                <label class="form-check-label" for="cat_all">
                                    All Categories
                                </label>
                            </div>
                            @endif
                        </div>

                        <!-- Price Range -->
                        <div class="mb-4">
                            <h6>Price Range</h6>
                            <div class="row g-2">
                                <div class="col-6">
                                    <input type="number" class="form-control form-control-sm" 
                                           name="min_price" placeholder="Min" 
                                           value="{{ request('min_price') }}">
                                </div>
                                <div class="col-6">
                                    <input type="number" class="form-control form-control-sm" 
                                           name="max_price" placeholder="Max" 
                                           value="{{ request('max_price') }}">
                                </div>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-primary btn-sm w-100">
                            <i class="bi bi-search"></i> Apply Filters
                        </button>
                        
                        @if(request()->hasAny(['category', 'min_price', 'max_price']))
                        <a href="{{ route('public.products') }}" class="btn btn-outline-secondary btn-sm w-100 mt-2">
                            <i class="bi bi-x-circle"></i> Clear Filters
                        </a>
                        @endif
                    </form>
                </div>
            </div>
        </div>

        <!-- Products Grid -->
        <div class="col-lg-9">
            <!-- Sort Options -->
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    Showing {{ $products->firstItem() ?? 0 }} - {{ $products->lastItem() ?? 0 }} of {{ $products->total() }} products
                </div>
                <form method="GET" action="{{ route('public.products') }}" class="d-flex align-items-center">
                    <!-- Preserve existing filters -->
                    @foreach(request()->except(['sort', 'page']) as $key => $value)
                        <input type="hidden" name="{{ $key }}" value="{{ $value }}">
                    @endforeach
                    
                    <label class="me-2">Sort by:</label>
                    <select name="sort" class="form-select form-select-sm" style="width: auto;" onchange="this.form.submit()">
                        <option value="">Latest</option>
                        <option value="name" {{ request('sort') == 'name' ? 'selected' : '' }}>Name A-Z</option>
                        <option value="price_low" {{ request('sort') == 'price_low' ? 'selected' : '' }}>Price: Low to High</option>
                        <option value="price_high" {{ request('sort') == 'price_high' ? 'selected' : '' }}>Price: High to Low</option>
                    </select>
                </form>
            </div>

            @if($products->count() > 0)
            <!-- Products Grid -->
            <div class="row g-4">
                @foreach($products as $product)
                <div class="col-md-6 col-xl-4">
                    <div class="card product-card h-100 border-0 shadow-sm" style="cursor: pointer;" onclick="window.location.href='{{ route('public.product.detail', $product->id) }}'">
                        @if($product->image_url)
                            <img src="{{ $product->image_url }}" class="card-img-top" alt="{{ $product->name }}" style="height: 250px; object-fit: cover;">
                        @else
                            <div class="card-img-top bg-light d-flex align-items-center justify-content-center" style="height: 250px;">
                                <i class="bi bi-image text-muted" style="font-size: 3rem;"></i>
                            </div>
                        @endif
                        
                        <div class="card-body d-flex flex-column">
                            <span class="badge bg-secondary mb-2 align-self-start">{{ ucwords(str_replace('_', ' ', $product->category)) }}</span>
                            <h6 class="card-title">{{ $product->name }}</h6>
                            <p class="card-text text-muted small flex-grow-1">{{ Str::limit($product->description, 80) }}</p>
                            
                            <!-- Product Details -->
                            <div class="mb-3">
                                @if($product->color_options)
                                <small class="text-muted d-block">
                                    <i class="bi bi-palette"></i> Colors: {{ $product->color_options }}
                                </small>
                                @endif
                                @if($product->size_range)
                                <small class="text-muted d-block">
                                    <i class="bi bi-rulers"></i> Sizes: {{ $product->size_range }}
                                </small>
                                @endif
                                @if($product->material)
                                <small class="text-muted d-block">
                                    <i class="bi bi-tag"></i> Material: {{ $product->material }}
                                </small>
                                @endif
                            </div>
                            
                            <div class="d-flex justify-content-between align-items-center mt-auto">
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

            <!-- Pagination -->
            @if($products->hasPages())
            <div class="d-flex justify-content-center mt-5">
                <nav aria-label="Products pagination">
                    <ul class="pagination pagination-lg">
                        {{-- Previous Page Link --}}
                        @if ($products->onFirstPage())
                            <li class="page-item disabled">
                                <span class="page-link">
                                    <i class="bi bi-chevron-left"></i>
                                    <span class="d-none d-sm-inline ms-1">Previous</span>
                                </span>
                            </li>
                        @else
                            <li class="page-item">
                                <a class="page-link" href="{{ $products->appends(request()->query())->previousPageUrl() }}">
                                    <i class="bi bi-chevron-left"></i>
                                    <span class="d-none d-sm-inline ms-1">Previous</span>
                                </a>
                            </li>
                        @endif

                        {{-- Pagination Elements --}}
                        @foreach ($products->appends(request()->query())->getUrlRange(1, $products->lastPage()) as $page => $url)
                            @if ($page == $products->currentPage())
                                <li class="page-item active">
                                    <span class="page-link">{{ $page }}</span>
                                </li>
                            @else
                                <li class="page-item">
                                    <a class="page-link" href="{{ $url }}">{{ $page }}</a>
                                </li>
                            @endif
                        @endforeach

                        {{-- Next Page Link --}}
                        @if ($products->hasMorePages())
                            <li class="page-item">
                                <a class="page-link" href="{{ $products->appends(request()->query())->nextPageUrl() }}">
                                    <span class="d-none d-sm-inline me-1">Next</span>
                                    <i class="bi bi-chevron-right"></i>
                                </a>
                            </li>
                        @else
                            <li class="page-item disabled">
                                <span class="page-link">
                                    <span class="d-none d-sm-inline me-1">Next</span>
                                    <i class="bi bi-chevron-right"></i>
                                </span>
                            </li>
                        @endif
                    </ul>
                </nav>
            </div>

            <!-- Pagination Info -->
            <div class="text-center mt-3">
                <small class="text-muted">
                    Showing {{ $products->firstItem() }} to {{ $products->lastItem() }} of {{ $products->total() }} results
                    (Page {{ $products->currentPage() }} of {{ $products->lastPage() }})
                </small>
            </div>
            @endif

            @else
            <!-- No Products Found -->
            <div class="text-center py-5">
                <i class="bi bi-search display-4 text-muted mb-3"></i>
                <h4 class="text-muted">No products found</h4>
                <p class="text-muted">
                    @if(request()->hasAny(['search', 'q', 'category', 'min_price', 'max_price']))
                        Try adjusting your search criteria or 
                        <a href="{{ route('public.products') }}">browse all products</a>
                    @else
                        Check back soon for our latest collection!
                    @endif
                </p>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .product-card {
        transition: transform 0.2s ease-in-out;
    }
    .product-card:hover {
        transform: translateY(-5px);
    }
    
    /* Professional Pagination Styles */
    .pagination {
        margin-bottom: 0;
    }
    
    .pagination .page-link {
        border: 1px solid #dee2e6;
        color: #0d6efd;
        padding: 0.75rem 1rem;
        margin: 0 2px;
        border-radius: 6px;
        font-weight: 500;
        transition: all 0.3s ease;
        text-decoration: none;
    }
    
    .pagination .page-link:hover {
        background-color: #e9ecef;
        border-color: #adb5bd;
        color: #0a58ca;
        transform: translateY(-1px);
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }
    
    .pagination .page-item.active .page-link {
        background-color: #0d6efd;
        border-color: #0d6efd;
        color: white;
        transform: none;
        box-shadow: 0 2px 8px rgba(13, 110, 253, 0.3);
    }
    
    .pagination .page-item.disabled .page-link {
        color: #6c757d;
        background-color: #f8f9fa;
        border-color: #dee2e6;
        cursor: not-allowed;
    }
    
    .pagination .page-item.disabled .page-link:hover {
        transform: none;
        box-shadow: none;
    }
    
    /* Mobile Responsiveness for Pagination */
    @media (max-width: 576px) {
        .pagination {
            justify-content: center;
        }
        
        .pagination .page-link {
            padding: 0.5rem 0.75rem;
            font-size: 0.875rem;
            margin: 0 1px;
        }
        
        .pagination-lg .page-link {
            padding: 0.5rem 0.75rem;
            font-size: 0.875rem;
        }
    }
    
    @media (max-width: 480px) {
        .pagination .page-link {
            padding: 0.4rem 0.6rem;
            font-size: 0.8rem;
        }
        
        /* Hide page numbers on very small screens, keep only prev/next */
        .pagination .page-item:not(:first-child):not(:last-child) {
            display: none;
        }
        
        /* Show current page and adjacent pages */
        .pagination .page-item.active,
        .pagination .page-item.active + .page-item,
        .pagination .page-item.active - .page-item {
            display: block;
        }
    }
    
    /* Pagination info styling */
    .text-muted small {
        font-size: 0.875rem;
    }
    
    /* Smooth scroll to top after pagination click */
    .page-link {
        scroll-behavior: smooth;
    }
</style>
@endpush