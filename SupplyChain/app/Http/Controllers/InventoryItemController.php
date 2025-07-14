<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Warehouse;
use App\Models\InventoryItem;

class InventoryItemController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Warehouse $warehouse)
    {
        $items = $warehouse->inventoryItems()->paginate(10);
        return view('manufacturer.InventoryItems.index', compact('warehouse', 'items'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Warehouse $warehouse)
    {
        return view('manufacturer.InventoryItems.create', compact('warehouse'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, Warehouse $warehouse)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'sku' => 'nullable|string|max:100',
            'quantity' => 'required|integer|min:0',
            'min_stock' => 'nullable|integer|min:0',
            'max_stock' => 'nullable|integer|min:0',
            'batch_number' => 'nullable|string|max:100',
            'expiry_date' => 'nullable|date',
        ]);
        $validated['warehouse_id'] = $warehouse->id;
        InventoryItem::create($validated);
        return redirect()->route('warehouses.inventory-items.index', $warehouse)->with('success', 'Inventory item added successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Warehouse $warehouse, InventoryItem $inventory_item)
    {
        return view('manufacturer.InventoryItems.show', compact('warehouse', 'inventory_item'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Warehouse $warehouse, InventoryItem $inventory_item)
    {
        return view('manufacturer.InventoryItems.edit', compact('warehouse', 'inventory_item'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Warehouse $warehouse, InventoryItem $inventory_item)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'sku' => 'nullable|string|max:100',
            'quantity' => 'required|integer|min:0',
            'min_stock' => 'nullable|integer|min:0',
            'max_stock' => 'nullable|integer|min:0',
            'batch_number' => 'nullable|string|max:100',
            'expiry_date' => 'nullable|date',
        ]);
        $inventory_item->update($validated);
        return redirect()->route('warehouses.inventory-items.index', $warehouse)->with('success', 'Inventory item updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Warehouse $warehouse, InventoryItem $inventory_item)
    {
        $inventory_item->delete();
        return redirect()->route('warehouses.inventory-items.index', $warehouse)->with('success', 'Inventory item deleted successfully.');
    }
}
