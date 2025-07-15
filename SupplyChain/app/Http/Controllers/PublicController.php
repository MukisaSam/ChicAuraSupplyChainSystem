<?php

namespace App\Http\Controllers;

use App\Models\Item;
use Illuminate\Http\Request;

class PublicController extends Controller
{
    public function home()
    {
        $featuredProducts = Item::where('is_active', true)
            ->where('type', 'finished_product')
            ->latest()
            ->take(8)
            ->get();

        $categories = Item::where('is_active', true)
            ->where('type', 'finished_product')
            ->distinct()
            ->pluck('category');

        return view('public.home', compact('featuredProducts', 'categories'));
    }

    public function products(Request $request)
    {
        $query = Item::where('is_active', true)
            ->where('type', 'finished_product');

        // Filter by category
        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }

        // Search by name or description
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        // Price range filter
        if ($request->filled('min_price')) {
            $query->where('base_price', '>=', $request->min_price);
        }
        if ($request->filled('max_price')) {
            $query->where('base_price', '<=', $request->max_price);
        }

        // Sort
        switch ($request->sort) {
            case 'price_low':
                $query->orderBy('base_price', 'asc');
                break;
            case 'price_high':
                $query->orderBy('base_price', 'desc');
                break;
            case 'name':
                $query->orderBy('name', 'asc');
                break;
            default:
                $query->latest();
                break;
        }

        $products = $query->paginate(12);

        $categories = Item::where('is_active', true)
            ->where('type', 'finished_product')
            ->distinct()
            ->pluck('category');

        return view('public.products', compact('products', 'categories'));
    }

    public function productDetail($id)
    {
        $product = Item::where('is_active', true)
            ->where('type', 'finished_product')
            ->findOrFail($id);

        $relatedProducts = Item::where('is_active', true)
            ->where('type', 'finished_product')
            ->where('category', $product->category)
            ->where('id', '!=', $product->id)
            ->take(4)
            ->get();

        return view('public.product-detail', compact('product', 'relatedProducts'));
    }

    public function search(Request $request)
    {
        $query = $request->get('q');
        
        if (empty($query)) {
            return redirect()->route('public.products');
        }

        $products = Item::where('is_active', true)
            ->where('type', 'finished_product')
            ->where(function($q) use ($query) {
                $q->where('name', 'like', "%{$query}%")
                  ->orWhere('description', 'like', "%{$query}%")
                  ->orWhere('category', 'like', "%{$query}%");
            })
            ->paginate(12);

        $categories = Item::where('is_active', true)
            ->where('type', 'finished_product')
            ->distinct()
            ->pluck('category');

        return view('public.products', compact('products', 'categories', 'query'));
    }
}