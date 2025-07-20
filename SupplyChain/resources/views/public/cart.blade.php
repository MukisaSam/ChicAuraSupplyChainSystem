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
                            <span class="fw-bold">UGX {{ number_format($item['unit_price'], 2) }}</span>
                        </div>
                        <div class="col-md-2">
                            <div class="input-group input-group-sm">
                                <button class="btn btn-outline-secondary decrease-btn" type="button" data-cart-key="{{ $item['id'] }}">-</button>
                                <input type="number" class="form-control text-center quantity-input" 
                                       value="{{ $item['quantity'] }}" min="1" max="{{ $item['product']->stock_quantity }}"
                                       data-cart-key="{{ $item['id'] }}" data-stock="{{ $item['product']->stock_quantity }}" 
                                       style="min-width: 45px;" readonly>
                                <button class="btn btn-outline-secondary increase-btn" type="button" 
                                        data-cart-key="{{ $item['id'] }}" data-stock="{{ $item['product']->stock_quantity }}">+</button>
                            </div>
                            <small class="text-muted d-block mt-1">{{ $item['product']->stock_quantity }} in stock</small>
                        </div>
                        <div class="col-md-1">
                            <span class="fw-bold item-total">UGX{{ number_format($item['total_price'], 2) }}</span>
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
                        <span id="cart-subtotal">UGX {{ number_format($total, 2) }}</span>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <span>Shipping:</span>
                        <span class="text-success">Free</span>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <span>Tax:</span>
                        <span id="cart-tax">UGX {{ number_format($total * 0.1, 2) }}</span>
                    </div>
                    <hr>
                    <div class="d-flex justify-content-between fw-bold">
                        <span>Total:</span>
                        <span id="cart-total">{{ number_format($total * 1.1, 2) }}</span>
                    </div>
                </div>
                <div class="card-footer">
                    <a href="{{ route('cart.checkout') }}" class="btn btn-primary w-100 btn-lg">
                        <i class="bi bi-credit-card"></i> Proceed to Checkout
                    </a>
                </div>
            </div>

            <!-- Promo Code (Future Feature) -->
            <!-- <div class="card mt-3">
                <div class="card-body">
                    <h6>Have a promo code?</h6>
                    <div class="input-group">
                        <input type="text" class="form-control" placeholder="Enter promo code">
                        <button class="btn btn-outline-secondary" type="button">Apply</button>
                    </div>
                </div>
            </div> -->
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
document.addEventListener('DOMContentLoaded', function() {
    // Add event listeners for quantity buttons
    document.querySelectorAll('.increase-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            const cartKey = this.getAttribute('data-cart-key');
            const stockQuantity = parseInt(this.getAttribute('data-stock'));
            const quantityInput = document.querySelector(`input.quantity-input[data-cart-key="${cartKey}"]`);
            const currentQuantity = parseInt(quantityInput.value);
            
            // Check if we can increase quantity
            if (currentQuantity >= stockQuantity) {
                showToast('warning', `Cannot add more items. Only ${stockQuantity} in stock.`, 3000);
                return;
            }
            
            updateQuantity(cartKey, currentQuantity + 1, this);
        });
    });

    document.querySelectorAll('.decrease-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            const cartKey = this.getAttribute('data-cart-key');
            const quantityInput = document.querySelector(`input.quantity-input[data-cart-key="${cartKey}"]`);
            const currentQuantity = parseInt(quantityInput.value);
            if (currentQuantity > 1) {
                updateQuantity(cartKey, currentQuantity - 1, this);
            } else {
                removeFromCart(cartKey);
            }
        });
    });
});

function updateQuantity(cartKey, quantity, buttonElement = null) {
    if (quantity < 1) {
        removeFromCart(cartKey);
        return;
    }

    // Show loading state
    if (buttonElement) {
        const originalText = buttonElement.innerHTML;
        buttonElement.innerHTML = '<span class="spinner-border spinner-border-sm" role="status"></span>';
        buttonElement.disabled = true;
    }

    // Optimistically update the UI
    const cartItem = document.querySelector(`[data-cart-key="${cartKey}"]`);
    const quantityInput = cartItem.querySelector('.quantity-input');
    const oldQuantity = parseInt(quantityInput.value);
    quantityInput.value = quantity;

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
            // Update item total with animation
            const itemTotalElement = cartItem.querySelector('.item-total');
            itemTotalElement.style.transition = 'color 0.3s ease';
            itemTotalElement.style.color = '#28a745';
            itemTotalElement.textContent = `$${data.item_total.toFixed(2)}`;
            
            // Update cart totals with animation
            updateCartTotals(data.cart_total);
            
            // Update cart count in header
            updateCartCount();
            
            // Update stock quantity if provided
            if (data.stock_quantity) {
                const increaseBtn = cartItem.querySelector('.increase-btn');
                const quantityInput = cartItem.querySelector('.quantity-input');
                increaseBtn.setAttribute('data-stock', data.stock_quantity);
                quantityInput.setAttribute('data-stock', data.stock_quantity);
                quantityInput.setAttribute('max', data.stock_quantity);
            }
            
            // Reset color after animation
            setTimeout(() => {
                itemTotalElement.style.color = '';
            }, 500);
            
            showToast('success', 'Cart updated successfully', 2000);
        } else {
            // Revert optimistic update
            quantityInput.value = oldQuantity;
            
            // Show specific error message with stock limit if available
            if (data.max_quantity) {
                showToast('warning', data.message, 4000);
                // Update the max quantity in case stock changed
                const increaseBtn = cartItem.querySelector('.increase-btn');
                const stockDisplay = cartItem.querySelector('small');
                increaseBtn.setAttribute('data-stock', data.max_quantity);
                quantityInput.setAttribute('data-stock', data.max_quantity);
                quantityInput.setAttribute('max', data.max_quantity);
                if (stockDisplay) {
                    stockDisplay.textContent = `${data.max_quantity} in stock`;
                }
            } else {
                showToast('danger', data.message || 'Failed to update cart');
            }
        }
    })
    .catch(error => {
        console.error('Error updating cart:', error);
        // Revert optimistic update
        quantityInput.value = oldQuantity;
        showToast('danger', 'Error updating cart');
    })
    .finally(() => {
        // Reset button state
        if (buttonElement) {
            const isIncrease = buttonElement.classList.contains('increase-btn');
            buttonElement.innerHTML = isIncrease ? '+' : '-';
            buttonElement.disabled = false;
        }
    });
}

function updateCartTotals(subtotal) {
    const subtotalElement = document.getElementById('cart-subtotal');
    const taxElement = document.getElementById('cart-tax');
    const totalElement = document.getElementById('cart-total');
    const tax = subtotal * 0.1;
    const total = subtotal + tax;

    // Add animation to all elements
    const elements = [subtotalElement, taxElement, totalElement];
    elements.forEach(element => {
        element.style.transition = 'all 0.3s ease';
        element.style.transform = 'scale(1.05)';
    });
    
    // Update values
    subtotalElement.textContent = `$${subtotal.toFixed(2)}`;
    taxElement.textContent = `$${tax.toFixed(2)}`;
    totalElement.textContent = `$${total.toFixed(2)}`;
    
    // Reset animation
    setTimeout(() => {
        elements.forEach(element => {
            element.style.transform = 'scale(1)';
        });
    }, 200);
}

function showToast(type, message, duration = 3000) {
    // Remove existing toasts
    const existingToasts = document.querySelectorAll('.toast-notification');
    existingToasts.forEach(toast => toast.remove());

    // Create toast element
    const toast = document.createElement('div');
    toast.className = `toast-notification alert alert-${type} position-fixed`;
    toast.style.cssText = `
        top: 20px;
        right: 20px;
        z-index: 9999;
        min-width: 300px;
        animation: slideInRight 0.3s ease-out;
    `;
    const getIcon = (type) => {
        switch(type) {
            case 'success': return 'check-circle';
            case 'warning': return 'exclamation-triangle';
            case 'danger': return 'x-circle';
            default: return 'info-circle';
        }
    };

    toast.innerHTML = `
        <div class="d-flex align-items-center">
            <i class="bi bi-${getIcon(type)} me-2"></i>
            <span>${message}</span>
            <button type="button" class="btn-close ms-auto" onclick="this.parentElement.parentElement.remove()"></button>
        </div>
    `;

    // Add CSS for animation
    if (!document.getElementById('toast-styles')) {
        const style = document.createElement('style');
        style.id = 'toast-styles';
        style.textContent = `
            @keyframes slideInRight {
                from { transform: translateX(100%); opacity: 0; }
                to { transform: translateX(0); opacity: 1; }
            }
            @keyframes slideOutRight {
                from { transform: translateX(0); opacity: 1; }
                to { transform: translateX(100%); opacity: 0; }
            }
        `;
        document.head.appendChild(style);
    }

    document.body.appendChild(toast);

    // Auto remove after duration
    setTimeout(() => {
        toast.style.animation = 'slideOutRight 0.3s ease-in';
        setTimeout(() => toast.remove(), 300);
    }, duration);
}

function removeFromCart(cartKey) {
    if (!confirm('Remove this item from cart?')) return;

    const cartItem = document.querySelector(`[data-cart-key="${cartKey}"]`);
    
    // Add removing animation
    cartItem.style.transition = 'all 0.3s ease';
    cartItem.style.opacity = '0.5';
    cartItem.style.transform = 'scale(0.95)';

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
            // Animate item removal
            cartItem.style.height = cartItem.offsetHeight + 'px';
            cartItem.style.overflow = 'hidden';
            
            setTimeout(() => {
                cartItem.style.height = '0';
                cartItem.style.marginTop = '0';
                cartItem.style.marginBottom = '0';
                cartItem.style.paddingTop = '0';
                cartItem.style.paddingBottom = '0';
                
                setTimeout(() => {
                    cartItem.remove();
                    
                    // Reload page if cart is empty
                    if (data.cart_count === 0) {
                        location.reload();
                    } else {
                        // Recalculate and update totals
                        recalculateCartTotals();
                    }
                }, 300);
            }, 100);
            
            // Update cart count
            updateCartCount();
            
            showToast('success', 'Item removed from cart');
        } else {
            // Reset animation
            cartItem.style.opacity = '1';
            cartItem.style.transform = 'scale(1)';
            showToast('danger', 'Failed to remove item');
        }
    })
    .catch(error => {
        console.error('Error removing from cart:', error);
        // Reset animation
        cartItem.style.opacity = '1';
        cartItem.style.transform = 'scale(1)';
        showToast('danger', 'Error removing item from cart');
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
            showToast('danger', 'Failed to clear cart');
        }
    })
    .catch(error => {
        console.error('Error clearing cart:', error);
        showToast('danger', 'Error clearing cart');
    });
}

function updateCartCount() {
    fetch('/cart/count', {
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => response.json())
    .then(data => {
        // Update cart count in navigation if element exists
        const cartCountElements = document.querySelectorAll('.cart-count, #cart-count');
        cartCountElements.forEach(element => {
            element.textContent = data.count;
            
            // Add bounce animation
            element.style.transition = 'transform 0.2s ease';
            element.style.transform = 'scale(1.2)';
            setTimeout(() => {
                element.style.transform = 'scale(1)';
            }, 200);
        });
    })
    .catch(error => {
        console.error('Error updating cart count:', error);
    });
}

function recalculateCartTotals() {
    let subtotal = 0;
    document.querySelectorAll('.cart-item').forEach(item => {
        const itemTotalText = item.querySelector('.item-total').textContent;
        const itemTotal = parseFloat(itemTotalText.replace('$', ''));
        subtotal += itemTotal;
    });
    
    updateCartTotals(subtotal);
}

// Legacy function for compatibility
function showAlert(type, message) {
    showToast(type, message);
}
</script>
@endpush