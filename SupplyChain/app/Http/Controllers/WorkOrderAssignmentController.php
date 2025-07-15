<?php

namespace App\Http\Controllers;

use App\Models\WorkOrderAssignment;
use Illuminate\Http\Request;

class WorkOrderAssignmentController extends Controller
{
    public function index() { /* ... */ }
    public function create($workOrderId)
    {
        $workOrder = \App\Models\WorkOrder::findOrFail($workOrderId);
        $workforce = \App\Models\Workforce::all();
        return view('manufacturer.production.assign-workforce', compact('workOrder', 'workforce'));
    }
    public function store(Request $request, $workOrderId)
    {
        $validated = $request->validate([
            'workforce_id' => 'required|exists:workforces,id',
            'role' => 'nullable|string|max:255',
        ]);
        
        \App\Models\WorkOrderAssignment::create([
            'work_order_id' => $workOrderId,
            'workforce_id' => $validated['workforce_id'],
            'role' => $validated['role'] ?? null,
            'assigned_at' => now(),
        ]);
        $workOrder = \App\Models\WorkOrder::findOrFail($workOrderId);
        return redirect()->route('manufacturer.production.show', ['production' => $workOrder])
            ->with('success', 'Workforce member assigned successfully!');
    }
    public function show(WorkOrderAssignment $workOrderAssignment) { /* ... */ }
    public function edit(WorkOrderAssignment $workOrderAssignment) { /* ... */ }
    public function update(Request $request, WorkOrderAssignment $workOrderAssignment) { /* ... */ }
    public function destroy(WorkOrderAssignment $workOrderAssignment)
    {
        $workOrder = $workOrderAssignment->workOrder;
        try {
            $deleted = $workOrderAssignment->forceDelete(); // Use forceDelete to ensure hard delete
            if (!$deleted) {
                return redirect()->back()->with('error', 'Failed to delete assignment.');
            }
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error deleting assignment: ' . $e->getMessage());
        }
        if (!$workOrder) {
            return redirect()->route('manufacturer.production.index')
                ->with('error', 'Related work order not found. Assignment removed.');
        }
        return redirect()->route('manufacturer.production.show', ['production' => $workOrder])
            ->with('success', 'Assignment removed successfully!');
    }
} 