<?php

namespace App\Services;

use App\Models\Item;
use App\Models\Customer;
use App\Models\CustomerOrder;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class RecommendationService
{
    private const ML_API_BASE_URL = 'http://localhost:8001';
    private const API_TOKEN = 'ml-api-chicaura-token';
    private const CACHE_DURATION = 1440; // 24 hours in minutes

    /**
     * Get recommendations for a customer
     */
    public function getRecommendations(int $customerId, int $limit = 8): array
    {
        $cacheKey = "customer_recommendations_{$customerId}";
        
        // Try to get from cache first
        $recommendations = Cache::get($cacheKey);
        
        if ($recommendations === null) {
            // Generate new recommendations
            $recommendations = $this->generateRecommendations($customerId, $limit);
            
            // Cache the recommendations
            Cache::put($cacheKey, $recommendations, $this->getCacheDuration());
        }
        
        return $recommendations;
    }
    
    /**
     * Generate fresh recommendations for a customer
     */
    private function generateRecommendations(int $customerId, int $limit): array
    {
        try {
            // Try ML API first
            $mlRecommendations = $this->getMLRecommendations($customerId, $limit);
            
            if (!empty($mlRecommendations)) {
                Log::info("ML recommendations generated for customer {$customerId}");
                return $mlRecommendations;
            }
        } catch (\Exception $e) {
            Log::warning("ML API failed for customer {$customerId}: " . $e->getMessage());
        }
        
        // Fallback to rule-based recommendations
        return $this->getFallbackRecommendations($customerId, $limit);
    }
    
    /**
     * Get recommendations from ML API
     */
    private function getMLRecommendations(int $customerId, int $limit): array
    {
        try {
            $response = Http::timeout(10)
                ->withHeaders([
                    'Authorization' => 'Bearer ' . self::API_TOKEN,
                    'Content-Type' => 'application/json'
                ])
                ->post(self::ML_API_BASE_URL . '/api/v1/recommendations', [
                    'customer_id' => $customerId,
                    'n_recommendations' => $limit,
                    'include_reasons' => true
                ]);
            
            if ($response->successful()) {
                $data = $response->json();
                return $this->formatMLRecommendations($data['recommendations'] ?? []);
            }
            
            Log::warning("ML API returned unsuccessful response: " . $response->status());
            return [];
            
        } catch (\Exception $e) {
            Log::error("ML API request failed: " . $e->getMessage());
            return [];
        }
    }
    
    /**
     * Format ML API recommendations
     */
    private function formatMLRecommendations(array $mlRecommendations): array
    {
        $recommendations = [];
        
        foreach ($mlRecommendations as $mlRec) {
            $item = Item::find($mlRec['item_id']);
            
            if ($item && $item->stock_quantity > 0) {
                $recommendations[] = [
                    'item' => $item,
                    'recommendation_score' => $mlRec['recommendation_score'] ?? 0,
                    'reasons' => $mlRec['reasons'] ?? ['Recommended for you'],
                    'source' => 'ml_api'
                ];
            }
        }
        
        return $recommendations;
    }
    
    /**
     * Get fallback recommendations when ML API is unavailable
     */
    private function getFallbackRecommendations(int $customerId, int $limit): array
    {
        Log::info("Generating fallback recommendations for customer {$customerId}");
        
        $customer = Customer::find($customerId);
        $recommendations = [];
        
        if ($customer) {
            // Check if customer has any orders first
            $hasOrders = $customer->customerOrders()->exists();
            
            if ($hasOrders) {
                // Strategy 1: Based on customer's purchase history
                $historyBasedRecs = $this->getHistoryBasedRecommendations($customer, $limit);
                $recommendations = array_merge($recommendations, $historyBasedRecs);
            } else {
                // Customer has no orders - use onboarding strategy
                $onboardingRecs = $this->getOnboardingRecommendations($customer, $limit);
                $recommendations = array_merge($recommendations, $onboardingRecs);
            }
            
            // Strategy 2: Based on customer preferences (for both new and existing customers)
            if (count($recommendations) < $limit) {
                $remaining = $limit - count($recommendations);
                $preferencesBasedRecs = $this->getPreferencesBasedRecommendations($customer, $remaining);
                $recommendations = array_merge($recommendations, $preferencesBasedRecs);
            }
            
            // Strategy 3: Demographic-based recommendations for new customers
            if (count($recommendations) < $limit && !$hasOrders) {
                $remaining = $limit - count($recommendations);
                $demographicRecs = $this->getDemographicBasedRecommendations($customer, $remaining);
                $recommendations = array_merge($recommendations, $demographicRecs);
            }
        }
        
        // Strategy 4: Popular items if still not enough
        if (count($recommendations) < $limit) {
            $remaining = $limit - count($recommendations);
            $popularRecs = $this->getPopularRecommendations($remaining);
            $recommendations = array_merge($recommendations, $popularRecs);
        }
        
        return array_slice($recommendations, 0, $limit);
    }
    
    /**
     * Get recommendations based on customer's purchase history
     */
    private function getHistoryBasedRecommendations(Customer $customer, int $limit): array
    {
        $recommendations = [];
        
        // Get customer's previous purchases
        $purchasedItems = $customer->customerOrders()
            ->with('customerOrderItems.item')
            ->where('status', '!=', 'cancelled')
            ->get()
            ->pluck('customerOrderItems')
            ->flatten()
            ->pluck('item')
            ->filter()
            ->unique('id');
        
        if ($purchasedItems->isEmpty()) {
            return [];
        }
        
        // Get categories and materials the customer has bought
        $purchasedCategories = $purchasedItems->pluck('category')->unique()->filter();
        $purchasedMaterials = $purchasedItems->pluck('material')->unique()->filter();
        $purchasedItemIds = $purchasedItems->pluck('id')->toArray();
        
        // Find similar items in the same categories/materials
        $similarItems = Item::where('type', 'finished_product')
            ->where('stock_quantity', '>', 0)
            ->whereNotIn('id', $purchasedItemIds)
            ->where(function ($query) use ($purchasedCategories, $purchasedMaterials) {
                if ($purchasedCategories->isNotEmpty()) {
                    $query->whereIn('category', $purchasedCategories->toArray());
                }
                if ($purchasedMaterials->isNotEmpty()) {
                    $query->orWhereIn('material', $purchasedMaterials->toArray());
                }
            })
            ->orderBy('created_at', 'desc')
            ->limit($limit)
            ->get();
        
        foreach ($similarItems as $item) {
            $reasons = [];
            
            if ($purchasedCategories->contains($item->category)) {
                $reasons[] = "You've bought {$item->category} items before";
            }
            if ($purchasedMaterials->contains($item->material)) {
                $reasons[] = "You like {$item->material} products";
            }
            
            $recommendations[] = [
                'item' => $item,
                'recommendation_score' => 0.8,
                'reasons' => $reasons ?: ['Based on your purchase history'],
                'source' => 'purchase_history'
            ];
        }
        
        return $recommendations;
    }
    
    /**
     * Get recommendations based on customer preferences
     */
    private function getPreferencesBasedRecommendations(Customer $customer, int $limit): array
    {
        $recommendations = [];
        $shoppingPreferences = $customer->shopping_preferences ?? [];
        
        if (empty($shoppingPreferences)) {
            return [];
        }
        
        // Map shopping preferences to categories/filters
        $categoryMapping = [
            'casual_wear' => ['t-shirts', 'jeans', 'sneakers', 'casual'],
            'formal_wear' => ['suits', 'dress_shirts', 'formal', 'blazers'],
            'sports_wear' => ['activewear', 'sports', 'athletic', 'gym'],
            'luxury_items' => [], // Handle by price range
            'eco_friendly' => ['organic', 'sustainable', 'eco'],
        ];
        
        $query = Item::where('type', 'finished_product')
            ->where('stock_quantity', '>', 0);
        
        // Apply preference-based filters
        $hasFilters = false;
        
        foreach ($shoppingPreferences as $preference) {
            if (isset($categoryMapping[$preference])) {
                $categories = $categoryMapping[$preference];
                if (!empty($categories)) {
                    $query->where(function ($q) use ($categories) {
                        foreach ($categories as $category) {
                            $q->orWhere('name', 'like', "%{$category}%")
                              ->orWhere('description', 'like', "%{$category}%")
                              ->orWhere('category', 'like', "%{$category}%");
                        }
                    });
                    $hasFilters = true;
                }
            }
        }
        
        // Handle price-based preferences
        if (in_array('luxury_items', $shoppingPreferences)) {
            $avgPrice = Item::where('type', 'finished_product')->avg('base_price') ?? 100;
            $query->where('base_price', '>', $avgPrice * 1.5);
            $hasFilters = true;
        } elseif (in_array('budget_friendly', $shoppingPreferences)) {
            $avgPrice = Item::where('type', 'finished_product')->avg('base_price') ?? 100;
            $query->where('base_price', '<=', $avgPrice * 0.8);
            $hasFilters = true;
        }
        
        if (!$hasFilters) {
            return [];
        }
        
        $items = $query->limit($limit)->get();
        
        foreach ($items as $item) {
            $reasons = ['Matches your preferences'];
            
            foreach ($shoppingPreferences as $preference) {
                $prefDisplay = ucwords(str_replace('_', ' ', $preference));
                if ($this->itemMatchesPreference($item, $preference)) {
                    $reasons[] = "Perfect for {$prefDisplay}";
                }
            }
            
            $recommendations[] = [
                'item' => $item,
                'recommendation_score' => 0.7,
                'reasons' => array_unique($reasons),
                'source' => 'preferences'
            ];
        }
        
        return $recommendations;
    }
    
    /**
     * Check if item matches a specific preference
     */
    private function itemMatchesPreference(Item $item, string $preference): bool
    {
        $text = strtolower($item->name . ' ' . $item->description . ' ' . $item->category);
        
        $keywords = [
            'casual_wear' => ['casual', 'everyday', 'comfortable'],
            'formal_wear' => ['formal', 'business', 'professional', 'elegant'],
            'sports_wear' => ['sport', 'athletic', 'active', 'gym', 'workout'],
            'eco_friendly' => ['eco', 'organic', 'sustainable', 'natural'],
            'luxury_items' => ['premium', 'luxury', 'designer', 'exclusive'],
        ];
        
        if (!isset($keywords[$preference])) {
            return false;
        }
        
        foreach ($keywords[$preference] as $keyword) {
            if (str_contains($text, $keyword)) {
                return true;
            }
        }
        
        return false;
    }
    
    /**
     * Get onboarding recommendations for new customers without orders
     */
    private function getOnboardingRecommendations(Customer $customer, int $limit): array
    {
        $recommendations = [];
        
        // Get trending/featured items for new customers
        $trendingItems = Item::where('type', 'finished_product')
            ->where('stock_quantity', '>', 0)
            ->orderBy('created_at', 'desc')
            ->limit($limit)
            ->get();
        
        foreach ($trendingItems as $item) {
            $recommendations[] = [
                'item' => $item,
                'recommendation_score' => 0.9, // High score for onboarding
                'reasons' => [
                    'Welcome to ChicAura!',
                    'New arrival perfect for getting started',
                    'Popular choice for new customers'
                ],
                'source' => 'onboarding'
            ];
        }
        
        return $recommendations;
    }
    
    /**
     * Get demographic-based recommendations for customers without orders
     */
    private function getDemographicBasedRecommendations(Customer $customer, int $limit): array
    {
        $recommendations = [];
        
        // Create targeted recommendations based on demographics
        $ageGroup = $customer->age_group;
        $gender = $customer->gender;
        $incomeLevel = $customer->income_bracket;
        
        $query = Item::where('type', 'finished_product')
            ->where('stock_quantity', '>', 0);
        
        // Age-based filtering
        $ageKeywords = [];
        switch ($ageGroup) {
            case '18-25':
                $ageKeywords = ['trendy', 'casual', 'youth', 'modern', 'street'];
                break;
            case '26-35':
                $ageKeywords = ['professional', 'contemporary', 'stylish', 'work'];
                break;
            case '36-45':
                $ageKeywords = ['classic', 'elegant', 'sophisticated', 'business'];
                break;
            case '46-55':
                $ageKeywords = ['timeless', 'quality', 'refined', 'mature'];
                break;
            case '55+':
                $ageKeywords = ['comfortable', 'classic', 'premium', 'traditional'];
                break;
        }
        
        // Gender-based filtering
        $genderKeywords = [];
        if ($gender === 'male') {
            $genderKeywords = ['men', 'male', 'masculine', 'guy'];
        } elseif ($gender === 'female') {
            $genderKeywords = ['women', 'female', 'feminine', 'lady'];
        }
        
        // Income-based price filtering
        $priceRange = $this->getPriceRangeForIncome($incomeLevel);
        if ($priceRange) {
            $query->whereBetween('base_price', $priceRange);
        }
        
        // Apply keyword filtering
        $allKeywords = array_merge($ageKeywords, $genderKeywords);
        if (!empty($allKeywords)) {
            $query->where(function($q) use ($allKeywords) {
                foreach ($allKeywords as $keyword) {
                    $q->orWhere('name', 'like', "%{$keyword}%")
                      ->orWhere('description', 'like', "%{$keyword}%");
                }
            });
        }
        
        $items = $query->limit($limit)->get();
        
        foreach ($items as $item) {
            $reasons = ['Perfect for your demographic'];
            
            if ($ageGroup) {
                $reasons[] = "Great for {$ageGroup} age group";
            }
            if ($gender && $gender !== 'prefer-not-to-say') {
                $reasons[] = "Popular with {$gender} customers";
            }
            if ($incomeLevel && $incomeLevel !== 'prefer-not-to-say') {
                $reasons[] = "Fits your budget preferences";
            }
            
            $recommendations[] = [
                'item' => $item,
                'recommendation_score' => 0.75,
                'reasons' => array_slice($reasons, 0, 3),
                'source' => 'demographic'
            ];
        }
        
        return $recommendations;
    }
    
    /**
     * Get price range based on income bracket
     */
    private function getPriceRangeForIncome(?string $incomeLevel): ?array
    {
        if (!$incomeLevel || $incomeLevel === 'prefer-not-to-say') {
            return null;
        }
        
        // Get average price to calculate ranges
        $avgPrice = Item::where('type', 'finished_product')->avg('base_price') ?? 50;
        
        switch ($incomeLevel) {
            case 'low':
                return [0, $avgPrice * 0.6];
            case 'middle-low':
                return [0, $avgPrice * 0.8];
            case 'middle':
                return [$avgPrice * 0.3, $avgPrice * 1.2];
            case 'middle-high':
                return [$avgPrice * 0.8, $avgPrice * 2.0];
            case 'high':
                return [$avgPrice * 1.2, null];
            default:
                return null;
        }
    }
    
    /**
     * Get popular items as fallback recommendations
     */
    private function getPopularRecommendations(int $limit): array
    {
        $recommendations = [];
        
        // Get most popular items based on recent orders
        $popularItems = Item::select('items.*')
            ->join('customer_order_items', 'items.id', '=', 'customer_order_items.item_id')
            ->join('customer_orders', 'customer_order_items.customer_order_id', '=', 'customer_orders.id')
            ->where('items.type', 'finished_product')
            ->where('items.stock_quantity', '>', 0)
            ->where('customer_orders.status', '!=', 'cancelled')
            ->where('customer_orders.created_at', '>=', now()->subDays(30))
            ->groupBy('items.id')
            ->orderByRaw('COUNT(customer_order_items.id) DESC')
            ->limit($limit)
            ->get();
        
        // If no recent popular items, get newest items
        if ($popularItems->isEmpty()) {
            $popularItems = Item::where('type', 'finished_product')
                ->where('stock_quantity', '>', 0)
                ->orderBy('created_at', 'desc')
                ->limit($limit)
                ->get();
        }
        
        foreach ($popularItems as $item) {
            $recommendations[] = [
                'item' => $item,
                'recommendation_score' => 0.5,
                'reasons' => ['Popular with other customers', 'Trending now'],
                'source' => 'popular'
            ];
        }
        
        return $recommendations;
    }
    
    /**
     * Clear recommendations cache for a customer
     */
    public function clearRecommendationsCache(int $customerId): void
    {
        $cacheKey = "customer_recommendations_{$customerId}";
        Cache::forget($cacheKey);
    }
    
    /**
     * Get cache duration (shorter for active customers)
     */
    private function getCacheDuration(): int
    {
        return self::CACHE_DURATION;
    }
    
    /**
     * Refresh recommendations in background (can be called from queue job)
     */
    public function refreshRecommendations(int $customerId, int $limit = 8): array
    {
        $recommendations = $this->generateRecommendations($customerId, $limit);
        
        $cacheKey = "customer_recommendations_{$customerId}";
        Cache::put($cacheKey, $recommendations, $this->getCacheDuration());
        
        return $recommendations;
    }
    
    /**
     * Get recommendation statistics for admin dashboard
     */
    public function getRecommendationStats(): array
    {
        $totalCustomers = Customer::count();
        $customersWithRecommendations = 0;
        
        // Count cached recommendations
        for ($i = 1; $i <= min($totalCustomers, 100); $i++) {
            if (Cache::has("customer_recommendations_{$i}")) {
                $customersWithRecommendations++;
            }
        }
        
        return [
            'total_customers' => $totalCustomers,
            'customers_with_recommendations' => $customersWithRecommendations,
            'cache_hit_rate' => $totalCustomers > 0 ? ($customersWithRecommendations / min($totalCustomers, 100)) * 100 : 0,
            'ml_api_status' => $this->checkMLApiStatus(),
        ];
    }
    
    /**
     * Check if ML API is available
     */
    private function checkMLApiStatus(): string
    {
        try {
            $response = Http::timeout(5)
                ->withHeaders(['Authorization' => 'Bearer ' . self::API_TOKEN])
                ->get(self::ML_API_BASE_URL . '/health');
            
            return $response->successful() ? 'online' : 'offline';
        } catch (\Exception $e) {
            return 'offline';
        }
    }
}