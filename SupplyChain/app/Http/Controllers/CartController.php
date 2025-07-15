<?php

namespace App\Http\Controllers;

use App\Models\Item;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class CartController extends Controller
{
    public function index()
    {
        $cart = Session::get('cart', []);
        $cartItems = [];
        $total = 0;

        foreach ($cart as $id => $item) {
            $product = Item::find($id);
            if ($product) {
                $cartItems[] = [
                    'id' => $id,
                    'product' => $product,
                    'quantity' => $item['quantity'],
                    'size' => $item['size'] ?? null,
                    'color' => $item['color'] ?? null,
                    'unit_price' => $product->base_price,
                    'total_price' => $product->base_price * $item['quantity']
                ];
                $total += $product->base_price * $item['quantity'];
            }
        }

        return view('public.cart', compact('cartItems', 'total'));
    }

    public function add(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:items,id',
            'quantity' => 'required|integer|min:1',
            'size' => 'nullable|string',
            'color' => 'nullable|string'
        ]);

        $product = Item::findOrFail($request->product_id);
        
        if (!$product->is_active || $product->type !== 'finished_product') {
            return response()->json([
                'success' => false,
                'message' => 'Product is not available'
            ], 400);
        }

        // Check stock quantity
        if ($request->quantity > $product->stock_quantity) {
            return response()->json([
                'success' => false,
                'message' => "Cannot add {$request->quantity} items. Only {$product->stock_quantity} in stock."
            ], 400);
        }

        if ($product->stock_quantity <= 0) {
            return response()->json([
                'success' => false,
                'message' => 'Product is out of stock'
            ], 400);
        }

        $cart = Session::get('cart', []);
        $productId = $request->product_id;
        
        // Create unique key for product with size and color variations
        $cartKey = $productId;
        if ($request->size || $request->color) {
            $cartKey = $productId . '_' . ($request->size ?? '') . '_' . ($request->color ?? '');
        }

        // Check if adding this quantity would exceed stock
        $existingQuantity = isset($cart[$cartKey]) ? $cart[$cartKey]['quantity'] : 0;
        $totalQuantity = $existingQuantity + $request->quantity;
        
        if ($totalQuantity > $product->stock_quantity) {
            $availableToAdd = $product->stock_quantity - $existingQuantity;
            if ($availableToAdd <= 0) {
                return response()->json([
                    'success' => false,
                    'message' => 'This item is already at maximum quantity in your cart'
                ], 400);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => "Can only add {$availableToAdd} more of this item to cart (you already have {$existingQuantity})"
                ], 400);
            }
        }

        if (isset($cart[$cartKey])) {
            $cart[$cartKey]['quantity'] += $request->quantity;
        } else {
            $cart[$cartKey] = [
                'product_id' => $productId,
                'quantity' => $request->quantity,
                'size' => $request->size,
                'color' => $request->color,
                'added_at' => now()
            ];
        }

        Session::put('cart', $cart);

        $cartCount = array_sum(array_column($cart, 'quantity'));

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Product added to cart successfully',
                'cart_count' => $cartCount
            ]);
        }

        return redirect()->back()->with('success', 'Product added to cart successfully');
    }

    public function update(Request $request)
    {
        $request->validate([
            'cart_key' => 'required|string',
            'quantity' => 'required|integer|min:1'
        ]);

        $cart = Session::get('cart', []);
        
        if (isset($cart[$request->cart_key])) {
            $cart[$request->cart_key]['quantity'] = $request->quantity;
            Session::put('cart', $cart);

            // Calculate new totals
            $cartItems = [];
            $total = 0;

            foreach ($cart as $id => $item) {
                $product = Item::find($item['product_id']);
                if ($product) {
                    $itemTotal = $product->base_price * $item['quantity'];
                    $total += $itemTotal;
                    
                    if ($id === $request->cart_key) {
                        $cartItems['updated_item'] = [
                            'cart_key' => $id,
                            'total_price' => $itemTotal
                        ];
                    }
                }
            }

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Cart updated successfully',
                    'item_total' => $cartItems['updated_item']['total_price'],
                    'cart_total' => $total,
                    'cart_count' => array_sum(array_column($cart, 'quantity'))
                ]);
            }
        }

        return redirect()->back()->with('success', 'Cart updated successfully');
    }

    public function remove(Request $request)
    {
        $request->validate([
            'cart_key' => 'required|string'
        ]);

        $cart = Session::get('cart', []);
        
        if (isset($cart[$request->cart_key])) {
            unset($cart[$request->cart_key]);
            Session::put('cart', $cart);

            $cartCount = array_sum(array_column($cart, 'quantity'));

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Item removed from cart',
                    'cart_count' => $cartCount
                ]);
            }
        }

        return redirect()->back()->with('success', 'Item removed from cart');
    }

    public function clear()
    {
        Session::forget('cart');

        if (request()->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Cart cleared successfully'
            ]);
        }

        return redirect()->back()->with('success', 'Cart cleared successfully');
    }

    public function getCartCount()
    {
        $cart = Session::get('cart', []);
        $count = array_sum(array_column($cart, 'quantity'));

        return response()->json(['count' => $count]);
    }

    public function checkout()
    {
        $cart = Session::get('cart', []);
        
        if (empty($cart)) {
            return redirect()->route('public.cart')->with('error', 'Your cart is empty');
        }

        $cartItems = [];
        $total = 0;

        foreach ($cart as $id => $item) {
            $product = Item::find($item['product_id']);
            if ($product) {
                $cartItems[] = [
                    'id' => $id,
                    'product' => $product,
                    'quantity' => $item['quantity'],
                    'size' => $item['size'] ?? null,
                    'color' => $item['color'] ?? null,
                    'unit_price' => $product->base_price,
                    'total_price' => $product->base_price * $item['quantity']
                ];
                $total += $product->base_price * $item['quantity'];
            }
        }

        return view('public.checkout', compact('cartItems', 'total'));
    }
}