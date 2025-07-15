@extends('layouts.public')

@section('title', 'Shopping Cart')

@section('content')
<div class="container py-4">
    <h1 class="h2 mb-4"><i class="bi bi-cart3"></i> Shopping Cart</h1>

    @if(count($cartItems) > 0)
    <div class="row">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-body">
                    @foreach($cartItems as $item)
                    <div class="row align-items-center cart-item py-3 border-bottom" data-cart-key="{{ $item['id'] }}">
                        <div class="col-md-2">
                            @if($item['product']->image_url)
                                <img src="{{ $item['product']->image_url }}" class="img-fluid rounded" alt="{{ $item['product']->name }}">
                            @else
                                <div class="bg-light rounded d-flex align-items-center justify-content-center" style="height: 80px;">
                                    <i class="bi bi-image text-muted"></i>
                                </div>
                            @endif
                        </div>
                        <div class="col-md-4">
                            <h6 class="mb-1">{{ $item['product']->name }}</h6>
                            <small class="text-muted">{{ $item['product']->category }}</small>
                            @if($item['size'])
                                <br><small class="text-muted">Size: {{ $item['size'] }}</small>
                            @endif
                            @if($item['color'])
                                <br><small class="text-muted">Color: {{ $item['color'] }}</small>
                            @endif
                        </div>
                        <div class="col-md-2">
                            <span class="fw-bold">${{ number_format($item['unit_price'], 2) }}</span>
                        </div>
                        <div class="col-md-2">
                            <div class="input-group input-group-sm">
                                <button class="btn btn-outline-secondary" type="button" onclick="updateQuantity('{{ $item['id'] }}', {{ $item['quantity'] - 1 }})">-</button>
                                <input type="number" class="form-control text-center quantity-input" 
                                       value="{{ $item['quantity'] }}" min="1" 
                                       onchange="updateQuantity('{{ $item['id'] }}', this.value)">
                                <button class="btn btn-outline-secondary" type="button" onclick="updateQuantity('{{ $item['id'] }}', {{ $item['quantity'] + 1 }})">+</button>
                            </div>
                        </div>
                        <div class="col-md-1">
                            <span class="fw-bold item-total">${{ number_format($item['total_price'], 2) }}</span>
                        </div>
                        <div class="col-md-1">
                            <button class="btn btn-sm btn-outline-danger" onclick="removeFromCart('{{ $item['id'] }}')">
                                <i class="bi bi-trash"></i>
                            </button>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>

            <div class="mt-3">
                <a href="{{ route('public.products') }}" class="btn btn-outline-primary">
                    <i class="bi bi-arrow-left"></i> Continue Shopping
                </a>
                <button onclick="clearCart()" class="btn btn-outline-danger ms-2">
                    <i class="bi bi-trash"></i> Clear Cart
                </button>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Order Summary</h5>
                </div>
                <div class="card-body">
                    <div class="d-flex justify-content-between mb-2">
                        <span>Subtotal:</span>
                        <span id="cart-subtotal">${{ number_format($total, 2) }}</span>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <span>Shipping:</span>
                        <span class="text-success">Free</span>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <span>Tax:</span>
                        <span>${{ number_format($total * 0.1, 2) }}</span>
                    </div>
                    <hr>
                    <div class="d-flex justify-content-between fw-bold">
                        <span>Total:</span>
                        <span id="cart-total">${{ number_format($total * 1.1, 2) }}</span>
                    </div>
                </div>
                <div class="card-footer">
                    <a href="{{ route('cart.checkout') }}" class="btn btn-primary w-100 btn-lg">
                        <i class="bi bi-credit-card"></i> Proceed to Checkout
                    </a>
                </div>
            </div>

            <!-- Promo Code (Future Feature) -->
            <div class="card mt-3">
                <div class="card-body">
                    <h6>Have a promo code?</h6>
                    <div class="input-group">
                        <input type="text" class="form-control" placeholder="Enter promo code">
                        <button class="btn btn-outline-secondary" type="button">Apply</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @else
    <!-- Empty Cart -->
    <div class="text-center py-5">
        <i class="bi bi-cart-x display-4 text-muted mb-3"></i>
        <h4 class="text-muted">Your cart is empty</h4>
        <p class="text-muted">Add some products to get started!</p>
        <a href="{{ route('public.products') }}" class="btn btn-primary">
            <i class="bi bi-bag"></i> Start Shopping
        </a>
    </div>
    @endif
</div>
@endsection

@push('scripts')
<script>
function updateQuantity(cartKey, quantity) {
    if (quantity < 1) {
        removeFromCart(cartKey);
        return;
    }

    const formData = new FormData();
    formData.append('cart_key', cartKey);
    formData.append('quantity', quantity);
    formData.append('_token', document.querySelector('meta[name="csrf-token"]').getAttribute('content'));

    fetch('/cart/update', {
        method: 'POST',
        body: formData,
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Update item total
            const cartItem = document.querySelector(`[data-cart-key="${cartKey}"]`);
            cartItem.querySelector('.item-total').textContent = `$${data.item_total.toFixed(2)}`;
            
            // Update cart totals
            document.getElementById('cart-subtotal').textContent = `$${data.cart_total.toFixed(2)}`;
            document.getElementById('cart-total').textContent = `$${(data.cart_total * 1.1).toFixed(2)}`;
            
            // Update cart count
            updateCartCount();
            
            showAlert('success', data.message);
        } else {
            showAlert('danger', 'Failed to update cart');
        }
    })
    .catch(error => {
        console.error('Error updating cart:', error);
        showAlert('danger', 'Error updating cart');
    });
}

function removeFromCart(cartKey) {
    if (!confirm('Remove this item from cart?')) return;

    const formData = new FormData();
    formData.append('cart_key', cartKey);
    formData.append('_token', document.querySelector('meta[name="csrf-token"]').getAttribute('content'));

    fetch('/cart/remove', {
        method: 'POST',
        body: formData,
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Remove item from DOM
            const cartItem = document.querySelector(`[data-cart-key="${cartKey}"]`);
            cartItem.remove();
            
            // Update cart count
            updateCartCount();
            
            // Reload page if cart is empty
            if (data.cart_count === 0) {
                location.reload();
            }
            
            showAlert('success', data.message);
        } else {
            showAlert('danger', 'Failed to remove item');
        }
    })
    .catch(error => {
        console.error('Error removing from cart:', error);
        showAlert('danger', 'Error removing item from cart');
    });
}

function clearCart() {
    if (!confirm('Remove all items from cart?')) return;

    fetch('/cart/clear', {
        method: 'POST',
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            location.reload();
        } else {
            showAlert('danger', 'Failed to clear cart');
        }
    })
    .catch(error => {
        console.error('Error clearing cart:', error);
        showAlert('danger', 'Error clearing cart');
    });
}
</script>
@endpush