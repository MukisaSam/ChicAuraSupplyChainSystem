<?php

namespace App\Http\Controllers;

use App\Models\DowntimeLog;
use Illuminate\Http\Request;

class DowntimeLogController extends Controller
{
    public function index() {
        $logs = \App\Models\DowntimeLog::latest()->paginate(10);
        return view('manufacturer.downtime-logs.index', compact('logs'));
    }
    public function create() {
        $workOrders = \App\Models\WorkOrder::all();
        return view('manufacturer.downtime-logs.create', compact('workOrders'));
    }
    public function store(Request $request) {
        $validated = $request->validate([
            'work_order_id' => 'required|exists:work_orders,id',
            'reason' => 'required|string|max:255',
            'start_time' => 'required|date',
            'end_time' => 'required|date|after_or_equal:start_time',
            'notes' => 'nullable|string',
        ]);
        \App\Models\DowntimeLog::create($validated);
        return redirect()->route('manufacturer.downtime-logs.index')->with('success', 'Downtime log created successfully!');
    }
    public function show(\App\Models\DowntimeLog $downtimeLog) {
        return view('manufacturer.downtime-logs.show', compact('downtimeLog'));
    }
    public function edit(\App\Models\DowntimeLog $downtimeLog) {
        return view('manufacturer.downtime-logs.edit', compact('downtimeLog'));
    }
    public function update(Request $request, \App\Models\DowntimeLog $downtimeLog) {
        $validated = $request->validate([
            'reason' => 'required|string|max:255',
            'start_time' => 'required|date',
            'end_time' => 'required|date|after_or_equal:start_time',
            'notes' => 'nullable|string',
        ]);
        $downtimeLog->update($validated);
        return redirect()->route('manufacturer.downtime-logs.index')->with('success', 'Downtime log updated successfully!');
    }
    public function destroy(\App\Models\DowntimeLog $downtimeLog) {
        $downtimeLog->delete();
        return redirect()->route('manufacturer.downtime-logs.index')->with('success', 'Downtime log deleted successfully!');
    }
} 