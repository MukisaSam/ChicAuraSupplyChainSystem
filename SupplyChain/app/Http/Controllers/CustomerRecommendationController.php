<?php

namespace App\Http\Controllers;

use App\Services\RecommendationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\JsonResponse;

class CustomerRecommendationController extends Controller
{
    private RecommendationService $recommendationService;
    
    public function __construct(RecommendationService $recommendationService)
    {
        $this->recommendationService = $recommendationService;
        $this->middleware('auth:customer');
    }
    
    /**
     * Get recommendations for the authenticated customer
     */
    public function getRecommendations(Request $request): JsonResponse
    {
        try {
            $customer = Auth::guard('customer')->user();
            $limit = $request->get('limit', 8);
            
            $recommendations = $this->recommendationService->getRecommendations(
                $customer->id, 
                min($limit, 20) // Cap at 20 recommendations
            );
            
            return response()->json([
                'success' => true,
                'recommendations' => $this->formatRecommendationsForResponse($recommendations),
                'count' => count($recommendations),
                'customer_id' => $customer->id
            ]);
            
        } catch (\Exception $e) {
            \Log::error('Recommendations API error: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Unable to load recommendations at this time',
                'recommendations' => [],
                'count' => 0
            ], 500);
        }
    }
    
    /**
     * Refresh recommendations for the authenticated customer
     */
    public function refreshRecommendations(Request $request): JsonResponse
    {
        try {
            $customer = Auth::guard('customer')->user();
            $limit = $request->get('limit', 8);
            
            // Clear cache and generate fresh recommendations
            $this->recommendationService->clearRecommendationsCache($customer->id);
            $recommendations = $this->recommendationService->refreshRecommendations(
                $customer->id, 
                min($limit, 20)
            );
            
            return response()->json([
                'success' => true,
                'recommendations' => $this->formatRecommendationsForResponse($recommendations),
                'count' => count($recommendations),
                'refreshed' => true,
                'customer_id' => $customer->id
            ]);
            
        } catch (\Exception $e) {
            \Log::error('Recommendations refresh error: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Unable to refresh recommendations at this time',
                'recommendations' => [],
                'count' => 0
            ], 500);
        }
    }
    
    /**
     * Add recommended item to cart
     */
    public function addToCart(Request $request): JsonResponse
    {
        $request->validate([
            'item_id' => 'required|integer|exists:items,id',
            'quantity' => 'integer|min:1|max:10',
            'size' => 'nullable|string|max:50',
            'color' => 'nullable|string|max:50'
        ]);
        
        try {
            $customer = Auth::guard('customer')->user();
            
            // Use the existing cart functionality
            $cartController = new CartController();
            $cartRequest = new Request([
                'item_id' => $request->item_id,
                'quantity' => $request->quantity ?? 1,
                'size' => $request->size,
                'color' => $request->color,
                'from_recommendations' => true
            ]);
            
            $response = $cartController->add($cartRequest);
            $responseData = $response->getData(true);
            
            if ($responseData['success']) {
                // Track recommendation interaction (could be used for ML feedback)
                \Log::info("Customer {$customer->id} added recommended item {$request->item_id} to cart");
                
                return response()->json([
                    'success' => true,
                    'message' => 'Item added to cart successfully!',
                    'cart_count' => $responseData['cart_count'] ?? 0,
                    'item_id' => $request->item_id
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => $responseData['message'] ?? 'Failed to add item to cart'
                ], 400);
            }
            
        } catch (\Exception $e) {
            \Log::error('Add to cart from recommendations error: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Unable to add item to cart at this time'
            ], 500);
        }
    }
    
    /**
     * Track recommendation interaction (for ML feedback)
     */
    public function trackInteraction(Request $request): JsonResponse
    {
        $request->validate([
            'item_id' => 'required|integer|exists:items,id',
            'action' => 'required|string|in:view,click,add_to_cart,dismiss',
            'recommendation_source' => 'nullable|string'
        ]);
        
        try {
            $customer = Auth::guard('customer')->user();
            
            // Log the interaction for ML feedback
            \Log::info("Recommendation interaction", [
                'customer_id' => $customer->id,
                'item_id' => $request->item_id,
                'action' => $request->action,
                'source' => $request->recommendation_source,
                'timestamp' => now()->toISOString()
            ]);
            
            // Here you could store this in a dedicated tracking table
            // for more sophisticated ML feedback loops
            
            return response()->json([
                'success' => true,
                'message' => 'Interaction tracked'
            ]);
            
        } catch (\Exception $e) {
            \Log::error('Track recommendation interaction error: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Unable to track interaction'
            ], 500);
        }
    }
    
    /**
     * Get recommendation statistics for customer (optional admin feature)
     */
    public function getStats(): JsonResponse
    {
        try {
            $stats = $this->recommendationService->getRecommendationStats();
            
            return response()->json([
                'success' => true,
                'stats' => $stats
            ]);
            
        } catch (\Exception $e) {
            \Log::error('Recommendation stats error: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Unable to load statistics'
            ], 500);
        }
    }
    
    /**
     * Format recommendations for JSON response
     */
    private function formatRecommendationsForResponse(array $recommendations): array
    {
        return array_map(function ($rec) {
            $item = $rec['item'];
            
            return [
                'id' => $item->id,
                'name' => $item->name,
                'description' => $item->description ?? '',
                'category' => $item->category ?? '',
                'material' => $item->material ?? '',
                'base_price' => (float) $item->base_price,
                'stock_quantity' => $item->stock_quantity,
                'size_range' => $item->size_range ?? '',
                'color_options' => $item->color_options ?? '',
                'image_url' => $item->image_url ?? null,
                'recommendation_score' => $rec['recommendation_score'] ?? 0,
                'reasons' => $rec['reasons'] ?? [],
                'source' => $rec['source'] ?? 'unknown',
                'url' => route('public.product.detail', $item->id)
            ];
        }, $recommendations);
    }
}