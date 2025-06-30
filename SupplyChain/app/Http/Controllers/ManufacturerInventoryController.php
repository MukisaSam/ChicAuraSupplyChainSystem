<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Item;
use App\Models\SupplyRequest;
use App\Models\SuppliedItem;
use App\Models\Supplier;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ManufacturerInventoryController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
        if ($user->role !== 'manufacturer') {
            abort(403, 'Access denied. Manufacturer privileges required.');
        }
        
        // Get inventory statistics
        $stats = $this->getInventoryStats();
        
        // Get recent activities
        $recentActivities = $this->getRecentActivities();
        
        // Get low stock items
        $lowStockItems = Item::where('stock_quantity', '<=', 10)
            ->where('is_active', true)
            ->orderBy('stock_quantity', 'asc')
            ->limit(5)
            ->get();
        
        // Get inventory items with pagination
        $items = Item::where('is_active', true)
            ->orderBy('created_at', 'desc')
            ->paginate(10);
        
        return view('manufacturer.inventory.index', compact('stats', 'recentActivities', 'lowStockItems', 'items'));
    }

    public function create()
    {
        return view('manufacturer.inventory.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'category' => 'required|string|max:255',
            'material' => 'required|string|max:255',
            'base_price' => 'required|numeric|min:0',
            'stock_quantity' => 'required|integer|min:0',
            'size_range' => 'nullable|string|max:255',
            'color_options' => 'nullable|string|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        $data = $request->all();
        
        // Handle image upload
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('inventory', 'public');
            $data['image_url'] = $imagePath;
        }

        Item::create($data);

        return redirect()->route('manufacturer.inventory')
            ->with('success', 'Item added to inventory successfully.');
    }

    public function edit(Item $item)
    {
        return view('manufacturer.inventory.edit', compact('item'));
    }

    public function update(Request $request, Item $item)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'category' => 'required|string|max:255',
            'material' => 'required|string|max:255',
            'base_price' => 'required|numeric|min:0',
            'stock_quantity' => 'required|integer|min:0',
            'size_range' => 'nullable|string|max:255',
            'color_options' => 'nullable|string|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        $data = $request->all();
        
        // Handle image upload
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('inventory', 'public');
            $data['image_url'] = $imagePath;
        }

        $item->update($data);

        return redirect()->route('manufacturer.inventory')
            ->with('success', 'Item updated successfully.');
    }

    public function destroy(Item $item)
    {
        $item->update(['is_active' => false]);
        
        return redirect()->route('manufacturer.inventory')
            ->with('success', 'Item removed from inventory successfully.');
    }

    public function updateStock(Request $request, Item $item)
    {
        $request->validate([
            'quantity' => 'required|integer',
            'operation' => 'required|in:add,subtract,set'
        ]);

        $quantity = $request->quantity;
        $operation = $request->operation;

        switch ($operation) {
            case 'add':
                $item->increment('stock_quantity', $quantity);
                break;
            case 'subtract':
                if ($item->stock_quantity >= $quantity) {
                    $item->decrement('stock_quantity', $quantity);
                } else {
                    return back()->with('error', 'Insufficient stock available.');
                }
                break;
            case 'set':
                $item->update(['stock_quantity' => $quantity]);
                break;
        }

        return back()->with('success', 'Stock updated successfully.');
    }

    public function analytics()
    {
        $user = Auth::user();
        
        if ($user->role !== 'manufacturer') {
            abort(403, 'Access denied. Manufacturer privileges required.');
        }

        // Get analytics data
        $analytics = $this->getInventoryAnalytics();
        
        return view('manufacturer.inventory.analytics', compact('analytics'));
    }

    public function getChartData()
    {
        // Get stock levels by category
        $stockByCategory = Item::select('category', DB::raw('SUM(stock_quantity) as total_stock'))
            ->where('is_active', true)
            ->groupBy('category')
            ->get();

        // Get stock movement over time (last 30 days)
        $stockMovement = $this->getStockMovementData();

        return response()->json([
            'stockByCategory' => $stockByCategory,
            'stockMovement' => $stockMovement
        ]);
    }

    private function getInventoryStats()
    {
        $totalItems = Item::where('is_active', true)->count();
        $totalStock = Item::where('is_active', true)->sum('stock_quantity');
        $lowStockItems = Item::where('stock_quantity', '<=', 10)->where('is_active', true)->count();
        $totalValue = Item::where('is_active', true)
            ->get()
            ->sum(function($item) {
                return $item->stock_quantity * $item->base_price;
            });

        return [
            'total_items' => $totalItems,
            'total_stock' => $totalStock,
            'low_stock_items' => $lowStockItems,
            'total_value' => number_format($totalValue, 2)
        ];
    }

    private function getRecentActivities()
    {
        $activities = [];

        // Get recent supply requests
        $recentSupplyRequests = SupplyRequest::with('item')
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        foreach ($recentSupplyRequests as $request) {
            $activities[] = [
                'description' => "Supply request for {$request->item->name} - {$request->quantity} units",
                'time' => $request->created_at ? $request->created_at->diffForHumans() : 'N/A',
                'icon' => 'fa-truck',
                'color' => 'text-blue-600'
            ];
        }

        // Get recent stock updates
        $recentItems = Item::where('is_active', true)
            ->orderBy('updated_at', 'desc')
            ->limit(5)
            ->get();

        foreach ($recentItems as $item) {
            $activities[] = [
                'description' => "Stock updated for {$item->name} - {$item->stock_quantity} units",
                'time' => $item->updated_at ? $item->updated_at->diffForHumans() : 'N/A',
                'icon' => 'fa-boxes',
                'color' => 'text-green-600'
            ];
        }

        // Sort by time and return top 10
        usort($activities, function($a, $b) {
            return strtotime($b['time']) - strtotime($a['time']);
        });

        return array_slice($activities, 0, 10);
    }

    private function getInventoryAnalytics()
    {
        // Category distribution
        $categoryDistribution = Item::select('category', DB::raw('COUNT(*) as count'))
            ->where('is_active', true)
            ->groupBy('category')
            ->get();

        // Stock value by category
        $stockValueByCategory = Item::select('category', 
            DB::raw('SUM(stock_quantity * base_price) as total_value'))
            ->where('is_active', true)
            ->groupBy('category')
            ->get();

        // Low stock items
        $lowStockItems = Item::where('stock_quantity', '<=', 10)
            ->where('is_active', true)
            ->orderBy('stock_quantity', 'asc')
            ->get();

        return [
            'categoryDistribution' => $categoryDistribution,
            'stockValueByCategory' => $stockValueByCategory,
            'lowStockItems' => $lowStockItems
        ];
    }

    private function getStockMovementData()
    {
        // This would typically come from a stock movement log table
        // For now, we'll generate sample data
        $data = [];
        for ($i = 29; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i);
            $data[] = [
                'date' => $date->format('Y-m-d'),
                'stock_in' => rand(10, 100),
                'stock_out' => rand(5, 50)
            ];
        }
        
        return $data;
    }
} 