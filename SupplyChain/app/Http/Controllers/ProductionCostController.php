<?php

namespace App\Http\Controllers;

use App\Models\ProductionCost;
use Illuminate\Http\Request;

class ProductionCostController extends Controller
{
    public function index() {
        $costs = \App\Models\ProductionCost::latest()->paginate(10);
        return view('manufacturer.production-costs.index', compact('costs'));
    }
    public function create() {
        return view('manufacturer.production-costs.create');
    }
    public function store(Request $request) {
        $validated = $request->validate([
            'work_order_id' => 'required|exists:work_orders,id',
            'material_cost' => 'required|numeric|min:0',
            'labor_cost' => 'required|numeric|min:0',
            'overhead_cost' => 'required|numeric|min:0',
            'total_cost' => 'required|numeric|min:0',
            'notes' => 'nullable|string',
        ]);
        \App\Models\ProductionCost::create($validated);
        return redirect()->route('manufacturer.production-costs.index')->with('success', 'Production cost recorded successfully!');
    }
    public function show(\App\Models\ProductionCost $productionCost) {
        return view('manufacturer.production-costs.show', compact('productionCost'));
    }
    public function edit(\App\Models\ProductionCost $productionCost) {
        return view('manufacturer.production-costs.edit', compact('productionCost'));
    }
    public function update(Request $request, \App\Models\ProductionCost $productionCost) {
        $validated = $request->validate([
            'work_order_id' => 'required|exists:work_orders,id',
            'material_cost' => 'required|numeric|min:0',
            'labor_cost' => 'required|numeric|min:0',
            'overhead_cost' => 'required|numeric|min:0',
            'total_cost' => 'required|numeric|min:0',
            'notes' => 'nullable|string',
        ]);
        $productionCost->update($validated);
        return redirect()->route('manufacturer.production-costs.index')->with('success', 'Production cost updated successfully!');
    }
    public function destroy(\App\Models\ProductionCost $productionCost) {
        $productionCost->delete();
        return redirect()->route('manufacturer.production-costs.index')->with('success', 'Production cost deleted successfully!');
    }
} 