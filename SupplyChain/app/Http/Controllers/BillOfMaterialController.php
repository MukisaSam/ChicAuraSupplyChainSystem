<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\BillOfMaterial;
use App\Models\BillOfMaterialComponent;
use Illuminate\Http\Request;

class BillOfMaterialController extends Controller
{
    public function index() {
        $boms = BillOfMaterial::with(['product', 'components.rawItem'])->latest()->paginate(10);
        return view('manufacturer.bom.index', compact('boms'));
    }
    public function create() {
        $products = Item::where('category', 'finished_product')->get();
        $rawMaterials = Item::where('category', 'raw_material')->get();
        return view('manufacturer.bom.create', compact('products', 'rawMaterials'));
    }
    public function store(Request $request) {
        $validated = $request->validate([
            'product_id' => 'required|exists:items,id',
            'components' => 'required|array|min:1',
            'components.*' => 'required|exists:items,id',
            'quantities' => 'required|array|min:1',
            'quantities.*' => 'required|integer|min:1',
            'notes' => 'nullable|string',
        ]);
        $bom = BillOfMaterial::create([
            'product_id' => $validated['product_id'],
        ]);
        foreach ($validated['components'] as $i => $componentId) {
            BillOfMaterialComponent::create([
                'bom_id' => $bom->id,
                'raw_item_id' => $componentId,
                'quantity' => $validated['quantities'][$i],
            ]);
        }
        return redirect()->route('manufacturer.bom.index')->with('success', 'Bill of Materials created successfully!');
    }
    public function show(BillOfMaterial $billOfMaterial) {
        $billOfMaterial->load(['product', 'components.rawItem']);
        return view('manufacturer.bom.show', compact('billOfMaterial'));
    }
    public function edit(BillOfMaterial $billOfMaterial) {
        $products = Item::where('category', 'finished_product')->get();
        $rawMaterials = Item::where('category', 'raw_material')->get();
        $billOfMaterial->load('components');
        return view('manufacturer.bom.edit', compact('billOfMaterial', 'products', 'rawMaterials'));
    }
    public function update(Request $request, BillOfMaterial $billOfMaterial) { /* ... */ }
    public function destroy(BillOfMaterial $billOfMaterial) { /* ... */ }

    public function addComponent(Request $request, $bomId)
    {
        $request->validate([
            'raw_item_id' => 'required|exists:items,id',
            'quantity' => 'required|integer|min:1',
        ]);
        $bom = BillOfMaterial::findOrFail($bomId);
        // Prevent duplicate components for the same raw material
        if ($bom->components()->where('raw_item_id', $request->raw_item_id)->exists()) {
            return back()->withErrors(['raw_item_id' => 'This raw material is already a component of the BOM.']);
        }
        BillOfMaterialComponent::create([
            'bom_id' => $bom->id,
            'raw_item_id' => $request->raw_item_id,
            'quantity' => $request->quantity,
        ]);
        return redirect()->route('manufacturer.bom.show', $bom->id)->with('success', 'Component added successfully!');
    }

    public function updateComponent(Request $request, $bomId, $componentId)
    {
        $request->validate([
            'raw_item_id' => 'required|exists:items,id',
            'quantity' => 'required|integer|min:1',
        ]);
        $component = BillOfMaterialComponent::findOrFail($componentId);
        // Prevent duplicate components for the same raw material (except for this component)
        $exists = BillOfMaterialComponent::where('bom_id', $bomId)
            ->where('raw_item_id', $request->raw_item_id)
            ->where('id', '!=', $componentId)
            ->exists();
        if ($exists) {
            return back()->withErrors(['raw_item_id' => 'This raw material is already a component of the BOM.']);
        }
        $component->update([
            'raw_item_id' => $request->raw_item_id,
            'quantity' => $request->quantity,
        ]);
        return redirect()->route('manufacturer.bom.show', $bomId)->with('success', 'Component updated successfully!');
    }

    public function deleteComponent($bomId, $componentId)
    {
        $component = BillOfMaterialComponent::findOrFail($componentId);
        $component->delete();
        return redirect()->route('manufacturer.bom.show', $bomId)->with('success', 'Component removed successfully!');
    }
} 