<?php

namespace App\Http\Controllers;

use App\Models\WorkOrder;
use App\Models\Workforce;
use App\Models\QualityCheck;
use Illuminate\Http\Request;

class QualityCheckController extends Controller
{
    public function index() {
        $qualityChecks = QualityCheck::with(['workOrder.product', 'checker'])->latest()->paginate(10);
        return view('manufacturer.quality.index', compact('qualityChecks'));
    }
    public function create() {
        $workOrders = WorkOrder::with('product')->get();
        $workforce = Workforce::all();
        return view('manufacturer.quality.create', compact('workOrders', 'workforce'));
    }
    public function store(Request $request) {
        $validated = $request->validate([
            'work_order_id' => 'required|exists:work_orders,id',
            'stage' => 'required|string',
            'result' => 'required|in:Pass,Fail,Rework',
            'checked_by' => 'required|exists:workforces,id',
            'checked_at' => 'required|date',
            'notes' => 'nullable|string',
        ]);
        QualityCheck::create($validated);
        return redirect()->route('manufacturer.quality.index')->with('success', 'Quality check created successfully!');
    }
    public function show(QualityCheck $qualityCheck) {
        $qualityCheck->load(['workOrder.product', 'checker']);
        return view('manufacturer.quality.show', compact('qualityCheck'));
    }
    public function edit(QualityCheck $qualityCheck) {
        $workOrders = WorkOrder::with('product')->get();
        $workforce = Workforce::all();
        return view('manufacturer.quality.edit', compact('qualityCheck', 'workOrders', 'workforce'));
    }
    public function update(Request $request, QualityCheck $qualityCheck) {
        $validated = $request->validate([
            'work_order_id' => 'required|exists:work_orders,id',
            'stage' => 'required|string',
            'result' => 'required|in:Pass,Fail,Rework',
            'checked_by' => 'required|exists:workforces,id',
            'checked_at' => 'required|date',
            'notes' => 'nullable|string',
        ]);
        $qualityCheck->update($validated);
        return redirect()->route('manufacturer.quality.index')->with('success', 'Quality check updated successfully!');
    }
    public function destroy(QualityCheck $qualityCheck) { /* ... */ }
} 