<div class="card shadow-sm border-0 h-100">
    <div class="card-body">
        <div class="d-flex justify-content-between align-items-start mb-4">
            <div>
                <h3 class="card-title h5 mb-2 d-flex align-items-center">
                    <i class="bi bi-star-fill text-warning me-2"></i>
                    Recommended for You
                </h3>
                <p class="text-muted small mb-0">Personalized picks based on your preferences</p>
            </div>
            <button id="refresh-recommendations" 
                    class="btn btn-outline-primary btn-sm d-flex align-items-center">
                <i class="bi bi-arrow-clockwise me-1"></i>
                Refresh
            </button>
        </div>

        <!-- Loading State -->
        <div id="recommendations-loading" class="d-none">
            <div class="row g-3">
                @for($i = 0; $i < 4; $i++)
                <div class="col-md-6 col-lg-3">
                    <div class="card">
                        <div class="card-img-top bg-light" style="height: 200px;">
                            <div class="placeholder-glow">
                                <span class="placeholder w-100 h-100"></span>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="placeholder-glow">
                                <span class="placeholder col-8"></span>
                                <span class="placeholder col-6"></span>
                                <span class="placeholder col-4"></span>
                            </div>
                        </div>
                    </div>
                </div>
                @endfor
            </div>
        </div>

        <!-- Recommendations Container -->
        <div id="recommendations-container">
            <!-- Recommendations will be loaded here via AJAX -->
        </div>

        <!-- Empty State -->
        <div id="recommendations-empty" class="d-none text-center py-5">
            <i class="bi bi-heart display-4 text-muted mb-3"></i>
            <h4 class="mb-3">No recommendations yet</h4>
            <p class="text-muted mb-4">Shop more products to get personalized recommendations!</p>
            <a href="{{ route('public.products') }}" 
               class="btn btn-primary">
                <i class="bi bi-search me-1"></i> Browse Products
            </a>
        </div>

        <!-- Error State -->
        <div id="recommendations-error" class="d-none text-center py-5">
            <i class="bi bi-exclamation-triangle display-4 text-danger mb-3"></i>
            <h4 class="mb-3">Unable to load recommendations</h4>
            <p class="text-muted mb-4">We're having trouble loading your recommendations right now.</p>
            <button onclick="loadRecommendations()" 
                    class="btn btn-primary">
                <i class="bi bi-arrow-clockwise me-1"></i> Try Again
            </button>
        </div>
    </div>
</div>

<!-- Recommendation Item Template -->
<template id="recommendation-item-template">
    <div class="col-md-6 col-lg-3">
        <div class="card recommendation-item h-100 product-card">
            <div class="position-relative">
                <img class="recommendation-image card-img-top" src="" alt="" style="height: 200px; object-fit: cover;">
                <div class="position-absolute top-0 end-0 p-2">
                    
                </div>
            </div>
            
            <div class="card-body">
                <h6 class="recommendation-name card-title text-truncate mb-2"></h6>
                <p class="recommendation-category text-muted small text-uppercase mb-2"></p>
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <span class="recommendation-price fw-bold text-primary h6 mb-0"></span>
                    <span class="recommendation-score badge "></span>
                </div>
                <div class="recommendation-reasons small text-muted mb-3"></div>
            </div>
        </div>
    </div>
</template>

@push('scripts')
<script>
class RecommendationManager {
    constructor() {
        this.apiUrl = '{{ route("customer.recommendations") }}';
        this.refreshUrl = '{{ route("customer.recommendations.refresh") }}';
        this.addToCartUrl = '{{ route("customer.recommendations.add-to-cart") }}';
        this.trackUrl = '{{ route("customer.recommendations.track") }}';
        this.csrfToken = '{{ csrf_token() }}';
        
        this.init();
    }
    
    init() {
        this.bindEvents();
        this.loadRecommendations();
    }
    
    bindEvents() {
        // Refresh button
        document.getElementById('refresh-recommendations')?.addEventListener('click', () => {
            this.refreshRecommendations();
        });
        
        // Delegation for dynamic buttons
        document.addEventListener('click', (e) => {
            if (e.target.closest('.add-to-cart-btn')) {
                e.preventDefault();
                const button = e.target.closest('.add-to-cart-btn');
                const itemId = button.dataset.itemId;
                this.addToCart(itemId, button);
            }
            
            if (e.target.closest('.quick-add-btn')) {
                e.preventDefault();
                const button = e.target.closest('.quick-add-btn');
                const itemId = button.dataset.itemId;
                this.addToCart(itemId, button);
            }
            
            if (e.target.closest('.recommendation-item')) {
                const item = e.target.closest('.recommendation-item');
                const itemId = item.querySelector('[data-item-id]')?.dataset.itemId;
                if (itemId) {
                    this.trackInteraction(itemId, 'click');
                }
            }
        });
    }
    
    async loadRecommendations() {
        this.showLoading();
        
        try {
            const response = await fetch(this.apiUrl, {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                }
            });
            
            const data = await response.json();
            
            if (data.success && data.recommendations.length > 0) {
                this.renderRecommendations(data.recommendations);
                this.hideAllStates();
                document.getElementById('recommendations-container').classList.remove('d-none');
            } else {
                this.showEmpty();
            }
        } catch (error) {
            console.error('Failed to load recommendations:', error);
            this.showError();
        }
    }
    
    async refreshRecommendations() {
        const refreshBtn = document.getElementById('refresh-recommendations');
        const originalText = refreshBtn.innerHTML;
        
        // Show loading state on button
        refreshBtn.innerHTML = `
            <svg class="animate-spin w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
            </svg>
            Refreshing...
        `;
        refreshBtn.disabled = true;
        
        try {
            const response = await fetch(this.refreshUrl, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': this.csrfToken,
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json',
                    'Content-Type': 'application/json'
                }
            });
            
            const data = await response.json();
            
            if (data.success && data.recommendations.length > 0) {
                this.renderRecommendations(data.recommendations);
                this.hideAllStates();
                document.getElementById('recommendations-container').classList.remove('d-none');
                this.showNotification('Recommendations refreshed!', 'success');
            } else {
                this.showEmpty();
            }
        } catch (error) {
            console.error('Failed to refresh recommendations:', error);
            this.showNotification('Failed to refresh recommendations', 'error');
        } finally {
            refreshBtn.innerHTML = originalText;
            refreshBtn.disabled = false;
        }
    }
    
    renderRecommendations(recommendations) {
        const container = document.getElementById('recommendations-container');
        const template = document.getElementById('recommendation-item-template');
        
        container.innerHTML = '';
        
        const grid = document.createElement('div');
        grid.className = 'row g-3';
        
        recommendations.slice(0, 8).forEach(rec => {
            const item = template.content.cloneNode(true);
            
            // Set image
            const img = item.querySelector('.recommendation-image');
            img.src = rec.image_url || 'https://via.placeholder.com/200x200?text=Product';
            img.alt = rec.name;
            
            // Set content
            item.querySelector('.recommendation-name').textContent = rec.name;
            item.querySelector('.recommendation-category').textContent = rec.category || 'Product';
            item.querySelector('.recommendation-price').textContent = `$${rec.base_price.toFixed(2)}`;
            
            // Set recommendation score
            const scoreElement = item.querySelector('.recommendation-score');
            if (rec.recommendation_score > 0) {
                scoreElement.textContent = `${(rec.recommendation_score * 100).toFixed(0)}% match`;
                scoreElement.classList.remove('d-none');
            } else {
                scoreElement.classList.add('d-none');
            }
            
            // Set reasons
            const reasonsContainer = item.querySelector('.recommendation-reasons');
            reasonsContainer.innerHTML = '';
            rec.reasons.slice(0, 2).forEach(reason => {
                const reasonDiv = document.createElement('div');
                reasonDiv.innerHTML = `<i class="bi bi-check-circle me-1"></i>${reason}`;
                reasonsContainer.appendChild(reasonDiv);
            });
            
            // Set data attributes
            item.querySelectorAll('[data-item-id]').forEach(el => {
                el.dataset.itemId = rec.id;
            });
            
            // Add click handler for product link
            const itemDiv = item.querySelector('.recommendation-item');
            itemDiv.style.cursor = 'pointer';
            itemDiv.addEventListener('click', (e) => {
                if (!e.target.closest('button')) {
                    window.location.href = rec.url;
                }
            });
            
            grid.appendChild(item);
        });
        
        container.appendChild(grid);
    }
    
    async addToCart(itemId, button) {
        const originalText = button.innerHTML;
        button.innerHTML = 'Adding...';
        button.disabled = true;
        
        try {
            const response = await fetch(this.addToCartUrl, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': this.csrfToken,
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json',
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    item_id: itemId,
                    quantity: 1
                })
            });
            
            const data = await response.json();
            
            if (data.success) {
                button.innerHTML = 'Added!';
                button.classList.remove('bg-blue-600', 'hover:bg-blue-700');
                button.classList.add('bg-green-600');
                
                // Update cart count if element exists
                this.updateCartCount(data.cart_count);
                
                // Track interaction
                this.trackInteraction(itemId, 'add_to_cart');
                
                this.showNotification('Item added to cart!', 'success');
                
                // Reset button after 2 seconds
                setTimeout(() => {
                    button.innerHTML = originalText;
                    button.classList.remove('bg-green-600');
                    button.classList.add('bg-blue-600', 'hover:bg-blue-700');
                    button.disabled = false;
                }, 2000);
            } else {
                throw new Error(data.message || 'Failed to add item to cart');
            }
        } catch (error) {
            console.error('Add to cart error:', error);
            button.innerHTML = originalText;
            button.disabled = false;
            this.showNotification(error.message || 'Failed to add item to cart', 'error');
        }
    }
    
    async trackInteraction(itemId, action) {
        try {
            await fetch(this.trackUrl, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': this.csrfToken,
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json',
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    item_id: itemId,
                    action: action,
                    recommendation_source: 'dashboard'
                })
            });
        } catch (error) {
            // Silent fail for tracking
            console.debug('Tracking failed:', error);
        }
    }
    
    updateCartCount(count) {
        const cartCountElements = document.querySelectorAll('.cart-count');
        cartCountElements.forEach(el => {
            el.textContent = count;
            if (count > 0) {
                el.classList.remove('d-none');
            }
        });
    }
    
    showLoading() {
        this.hideAllStates();
        document.getElementById('recommendations-loading').classList.remove('d-none');
    }
    
    showEmpty() {
        this.hideAllStates();
        document.getElementById('recommendations-empty').classList.remove('d-none');
    }
    
    showError() {
        this.hideAllStates();
        document.getElementById('recommendations-error').classList.remove('d-none');
    }
    
    hideAllStates() {
        document.getElementById('recommendations-loading').classList.add('d-none');
        document.getElementById('recommendations-container').classList.add('d-none');
        document.getElementById('recommendations-empty').classList.add('d-none');
        document.getElementById('recommendations-error').classList.add('d-none');
    }
    
    showNotification(message, type = 'info') {
        // Create a simple Bootstrap alert
        const alertClass = type === 'success' ? 'alert-success' : 
                          type === 'error' ? 'alert-danger' : 'alert-info';
        
        // Create or update notification container
        let container = document.getElementById('notification-container');
        if (!container) {
            container = document.createElement('div');
            container.id = 'notification-container';
            container.className = 'position-fixed top-0 end-0 p-3';
            container.style.zIndex = '1060';
            document.body.appendChild(container);
        }
        
        const alert = document.createElement('div');
        alert.className = `alert ${alertClass} alert-dismissible fade show`;
        alert.innerHTML = `
            <div class="d-flex align-items-center">
                <span>${message}</span>
                <button type="button" class="btn-close ms-auto" onclick="this.parentElement.parentElement.remove()"></button>
            </div>
        `;
        
        container.appendChild(alert);
        
        // Auto remove after 3 seconds
        setTimeout(() => {
            alert.classList.remove('show');
            setTimeout(() => {
                alert.remove();
            }, 150);
        }, 3000);
    }
}

// Initialize when DOM is ready
document.addEventListener('DOMContentLoaded', () => {
    new RecommendationManager();
});

// Global function for error retry
function loadRecommendations() {
    if (window.recommendationManager) {
        window.recommendationManager.loadRecommendations();
    }
}
</script>
@endpush