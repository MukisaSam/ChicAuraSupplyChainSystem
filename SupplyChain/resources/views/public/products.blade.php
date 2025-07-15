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
            <p class="text-muted">{{ $products->total() }} product(s) found</p>
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
            <div class="d-flex justify-content-center mt-5">
                {{ $products->withQueryString()->links() }}
            </div>

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
</style>
@endpush