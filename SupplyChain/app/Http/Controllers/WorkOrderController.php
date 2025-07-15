<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\WorkOrder;
use Illuminate\Http\Request;

class WorkOrderController extends Controller
{
    public function index() {
        $workOrders = WorkOrder::with('product')->latest()->paginate(10);
        return view('manufacturer.production.index', compact('workOrders'));
    }
    public function create() {
        $products = Item::all();
        return view('manufacturer.production.create', compact('products'));
    }
    public function store(Request $request) {
        $validated = $request->validate([
            'product_id' => 'required|exists:items,id',
            'quantity' => 'required|integer|min:1',
            'scheduled_start' => 'required|date',
            'scheduled_end' => 'required|date|after:scheduled_start',
            'notes' => 'nullable|string',
        ]);
        $workOrder = WorkOrder::create([
            'product_id' => $validated['product_id'],
            'quantity' => $validated['quantity'],
            'scheduled_start' => $validated['scheduled_start'],
            'scheduled_end' => $validated['scheduled_end'],
            'notes' => $validated['notes'] ?? null,
            'status' => 'Planned',
        ]);
        return redirect()->route('manufacturer.production.index')->with('success', 'Work order created successfully!');
    }
    public function show(WorkOrder $production) {
        // Force a fresh reload from the database to avoid stale relationships
        $production->refresh();
        $production->load([
            'product',
            'assignments.workforce',
            'qualityChecks.checker',
            'downtimeLogs',
            'productionCost',
        ]);
        return view('manufacturer.production.show', ['workOrder' => $production]);
    }
    public function edit(WorkOrder $production) {
        $products = Item::all();
        return view('manufacturer.production.edit', [
            'workOrder' => $production,
            'products' => $products
        ]);
    }
    public function update(Request $request, WorkOrder $production) {
        $validated = $request->validate([
            'product_id' => 'required|exists:items,id',
            'quantity' => 'required|integer|min:1',
            'scheduled_start' => 'required|date',
            'scheduled_end' => 'required|date|after:scheduled_start',
            'status' => 'required|in:Planned,InProgress,Completed,Cancelled',
            'notes' => 'nullable|string',
        ]);
        $production->update($validated);
        return redirect()->route('manufacturer.production.index')->with('success', 'Work order updated successfully!');
    }
    public function destroy(WorkOrder $workOrder) { /* ... */ }
} 