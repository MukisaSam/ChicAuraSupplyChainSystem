<?php

namespace App\Http\Controllers;

use App\Models\ProductionSchedule;
use Illuminate\Http\Request;

class ProductionScheduleController extends Controller
{
    public function index() {
        $schedules = \App\Models\ProductionSchedule::latest()->paginate(10);
        return view('manufacturer.production-schedules.index', compact('schedules'));
    }
    public function create() {
        return view('manufacturer.production-schedules.create');
    }
    public function store(Request $request) {
        $validated = $request->validate([
            'product_id' => 'required|exists:items,id',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'status' => 'required|in:planned,in_progress,completed',
        ]);
        \App\Models\ProductionSchedule::create($validated);
        return redirect()->route('manufacturer.production-schedules.index')->with('success', 'Production schedule created successfully!');
    }
    public function show(\App\Models\ProductionSchedule $productionSchedule) {
        return view('manufacturer.production-schedules.show', compact('productionSchedule'));
    }
    public function edit(\App\Models\ProductionSchedule $productionSchedule) {
        return view('manufacturer.production-schedules.edit', compact('productionSchedule'));
    }
    public function update(Request $request, \App\Models\ProductionSchedule $productionSchedule) {
        $validated = $request->validate([
            'product_id' => 'required|exists:items,id',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'status' => 'required|in:planned,in_progress,completed',
        ]);
        $productionSchedule->update($validated);
        return redirect()->route('manufacturer.production-schedules.index')->with('success', 'Production schedule updated successfully!');
    }
    public function destroy(\App\Models\ProductionSchedule $productionSchedule) {
        $productionSchedule->delete();
        return redirect()->route('manufacturer.production-schedules.index')->with('success', 'Production schedule deleted successfully!');
    }
} 