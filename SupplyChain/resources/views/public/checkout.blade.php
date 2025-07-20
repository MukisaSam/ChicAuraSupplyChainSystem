@extends('layouts.public')

@section('title', 'Checkout')

@section('content')
<div class="container py-4">
    <h1 class="h2 mb-4"><i class="bi bi-credit-card"></i> Checkout</h1>

    @auth('customer')
    <div class="row">
        <div class="col-lg-8">
            <form method="POST" action="{{ route('payment.process') }}" id="checkout-form">
                @csrf
                
                <!-- Shipping Information -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0"><i class="bi bi-truck"></i> Shipping Information</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="shipping_name" class="form-label">Full Name *</label>
                                <input type="text" class="form-control @error('shipping_address.name') is-invalid @enderror" 
                                       id="shipping_name" name="shipping_address[name]" 
                                       value="{{ old('shipping_address.name', Auth::guard('customer')->user()->name) }}" required>
                                @error('shipping_address.name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="shipping_phone" class="form-label">Phone Number *</label>
                                <input type="tel" class="form-control @error('shipping_address.phone') is-invalid @enderror" 
                                       id="shipping_phone" name="shipping_address[phone]" 
                                       value="{{ old('shipping_address.phone', Auth::guard('customer')->user()->phone) }}" required>
                                @error('shipping_address.phone')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label for="shipping_address" class="form-label">Address *</label>
                            <textarea class="form-control @error('shipping_address.address') is-invalid @enderror" 
                                      id="shipping_address" name="shipping_address[address]" rows="2" required>{{ old('shipping_address.address', Auth::guard('customer')->user()->address) }}</textarea>
                            @error('shipping_address.address')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="row">
                            <div class="col-md-8 mb-3">
                                <label for="shipping_city" class="form-label">City *</label>
                                <input type="text" class="form-control @error('shipping_address.city') is-invalid @enderror" 
                                       id="shipping_city" name="shipping_address[city]" 
                                       value="{{ old('shipping_address.city', Auth::guard('customer')->user()->city) }}" required>
                                @error('shipping_address.city')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="shipping_postal" class="form-label">Postal Code</label>
                                <input type="text" class="form-control @error('shipping_address.postal_code') is-invalid @enderror" 
                                       id="shipping_postal" name="shipping_address[postal_code]" 
                                       value="{{ old('shipping_address.postal_code') }}">
                                @error('shipping_address.postal_code')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Billing Information -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0"><i class="bi bi-receipt"></i> Billing Information</h5>
                    </div>
                    <div class="card-body">
                        <div class="form-check mb-3">
                            <input class="form-check-input" type="checkbox" id="billing_same" 
                                   name="billing_same_as_shipping" value="1" checked onchange="toggleBillingFields()">
                            <label class="form-check-label" for="billing_same">
                                Billing address is the same as shipping address
                            </label>
                        </div>
                        
                        <div id="billing-fields" style="display: none;">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="billing_name" class="form-label">Full Name</label>
                                    <input type="text" class="form-control @error('billing_address.name') is-invalid @enderror" 
                                           id="billing_name" name="billing_address[name]" 
                                           value="{{ old('billing_address.name') }}">
                                    @error('billing_address.name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="billing_phone" class="form-label">Phone Number</label>
                                    <input type="tel" class="form-control @error('billing_address.phone') is-invalid @enderror" 
                                           id="billing_phone" name="billing_address[phone]" 
                                           value="{{ old('billing_address.phone') }}">
                                    @error('billing_address.phone')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="mb-3">
                                <label for="billing_address_field" class="form-label">Address</label>
                                <textarea class="form-control @error('billing_address.address') is-invalid @enderror" 
                                          id="billing_address_field" name="billing_address[address]" rows="2">{{ old('billing_address.address') }}</textarea>
                                @error('billing_address.address')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="row">
                                <div class="col-md-8 mb-3">
                                    <label for="billing_city" class="form-label">City</label>
                                    <input type="text" class="form-control @error('billing_address.city') is-invalid @enderror" 
                                           id="billing_city" name="billing_address[city]" 
                                           value="{{ old('billing_address.city') }}">
                                    @error('billing_address.city')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label for="billing_postal" class="form-label">Postal Code</label>
                                    <input type="text" class="form-control @error('billing_address.postal_code') is-invalid @enderror" 
                                           id="billing_postal" name="billing_address[postal_code]" 
                                           value="{{ old('billing_address.postal_code') }}">
                                    @error('billing_address.postal_code')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Payment Method -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0"><i class="bi bi-credit-card-2-front"></i> Payment Method</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-check mb-2">
                                    <input class="form-check-input" type="radio" name="payment_method" 
                                           id="credit_card" value="credit_card" checked>
                                    <label class="form-check-label" for="credit_card">
                                        <i class="bi bi-credit-card"></i> Credit Card
                                    </label>
                                </div>
                                <div class="form-check mb-2">
                                    <input class="form-check-input" type="radio" name="payment_method" 
                                           id="debit_card" value="debit_card">
                                    <label class="form-check-label" for="debit_card">
                                        <i class="bi bi-credit-card-2-back"></i> Debit Card
                                    </label>
                                </div>
                                <div class="form-check mb-2">
                                    <input class="form-check-input" type="radio" name="payment_method" 
                                           id="paypal" value="paypal">
                                    <label class="form-check-label" for="paypal">
                                        <i class="bi bi-paypal"></i> PayPal
                                    </label>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-check mb-2">
                                    <input class="form-check-input" type="radio" name="payment_method" 
                                           id="bank_transfer" value="bank_transfer">
                                    <label class="form-check-label" for="bank_transfer">
                                        <i class="bi bi-bank"></i> Bank Transfer
                                    </label>
                                </div>
                                <div class="form-check mb-2">
                                    <input class="form-check-input" type="radio" name="payment_method" 
                                           id="mobile_money" value="mobile_money">
                                    <label class="form-check-label" for="mobile_money">
                                        <i class="bi bi-phone"></i> Mobile Money
                                    </label>
                                </div>
                                <div class="form-check mb-2">
                                    <input class="form-check-input" type="radio" name="payment_method" 
                                           id="cash_on_delivery" value="cash_on_delivery">
                                    <label class="form-check-label" for="cash_on_delivery">
                                        <i class="bi bi-cash"></i> Cash on Delivery
                                    </label>
                                </div>
                            </div>
                        </div>
                        @error('payment_method')
                            <div class="text-danger small">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <!-- Order Notes -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0"><i class="bi bi-chat-left-text"></i> Order Notes (Optional)</h5>
                    </div>
                    <div class="card-body">
                        <textarea class="form-control" name="notes" rows="3" 
                                  placeholder="Any special instructions for your order...">{{ old('notes') }}</textarea>
                    </div>
                </div>
            </form>
        </div>

        <!-- Order Summary -->
        <div class="col-lg-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Order Summary</h5>
                </div>
                <div class="card-body">
                    @foreach($cartItems as $item)
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <div>
                            <h6 class="mb-0">{{ $item['product']->name }}</h6>
                            <small class="text-muted">
                                Qty: {{ $item['quantity'] }}
                                @if($item['size']) • Size: {{ $item['size'] }} @endif
                                @if($item['color']) • Color: {{ $item['color'] }} @endif
                            </small>
                        </div>
                        <span>UGX{{ number_format($item['total_price'], 2) }}</span>
                    </div>
                    @endforeach
                    
                    <hr>
                    
                    <div class="d-flex justify-content-between mb-2">
                        <span>Subtotal:</span>
                        <span>UGX{{ number_format($total, 2) }}</span>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <span>Shipping:</span>
                        <span class="text-success">Free</span>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <span>Tax (10%):</span>
                        <span>UGX{{ number_format($total * 0.1, 2) }}</span>
                    </div>
                    <hr>
                    <div class="d-flex justify-content-between fw-bold h5">
                        <span>Total:</span>
                        <span>UGX{{ number_format($total * 1.1, 2) }}</span>
                    </div>
                </div>
                <div class="card-footer">
                    <button type="submit" form="checkout-form" class="btn btn-primary w-100 btn-lg">
                        <i class="bi bi-lock"></i> Place Order
                    </button>
                    <div class="text-center mt-2">
                        <small class="text-muted">
                            <i class="bi bi-shield-check"></i> Your payment information is secure
                        </small>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @else
    <!-- Guest Checkout - Redirect to Registration -->
    <div class="text-center py-5">
        <i class="bi bi-person-plus display-4 text-muted mb-3"></i>
        <h4>Create an account to complete your purchase</h4>
        <p class="text-muted">We need some information to process your order and provide you with the best service.</p>
        <div class="d-flex justify-content-center gap-3">
            <a href="{{ route('customer.register') }}" class="btn btn-primary">
                <i class="bi bi-person-plus"></i> Create Account
            </a>
            <a href="{{ route('customer.login') }}" class="btn btn-outline-primary">
                <i class="bi bi-box-arrow-in-right"></i> Login
            </a>
        </div>
    </div>
    @endauth
</div>
@endsection

@push('scripts')
<script>
function toggleBillingFields() {
    const billingFields = document.getElementById('billing-fields');
    const billingSame = document.getElementById('billing_same');
    
    if (billingSame.checked) {
        billingFields.style.display = 'none';
        // Clear billing fields when hiding
        billingFields.querySelectorAll('input, textarea').forEach(field => {
            field.removeAttribute('required');
        });
    } else {
        billingFields.style.display = 'block';
        // Make billing fields required when showing
        billingFields.querySelectorAll('input[name$="[name]"], input[name$="[phone]"], textarea[name$="[address]"], input[name$="[city]"]').forEach(field => {
            field.setAttribute('required', 'required');
        });
    }
}

// Initialize billing fields state
document.addEventListener('DOMContentLoaded', function() {
    toggleBillingFields();

document.getElementById('checkout-form').addEventListener('submit', function(e) {
    const paymentMethod = document.querySelector('input[name="payment_method"]:checked').value;
    
    if (paymentMethod === 'cash_on_delivery') {
        return true; // Proceed with normal form submission
    }
    
    if (paymentMethod === 'credit_card' || paymentMethod === 'debit_card') {
        e.preventDefault(); // We'll handle with Stripe
        // Show loading state
        const submitBtn = this.querySelector('[type="submit"]');
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm" role="status"></span> Processing...';
        
        // Submit form via AJAX to get Stripe session
        fetch(this.action, {
            method: 'POST',
            body: new FormData(this),
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.redirect) {
                window.location = data.redirect;
            } else {
                showToast('danger', data.message || 'Payment processing failed');
                submitBtn.disabled = false;
                submitBtn.innerHTML = '<i class="bi bi-lock"></i> Place Order';
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showToast('danger', 'An error occurred during payment processing');
            submitBtn.disabled = false;
            submitBtn.innerHTML = '<i class="bi bi-lock"></i> Place Order';
        });
    } else {
        showToast('warning', 'This payment method is not yet implemented');
        e.preventDefault();
    }
});
</script>
@endpush