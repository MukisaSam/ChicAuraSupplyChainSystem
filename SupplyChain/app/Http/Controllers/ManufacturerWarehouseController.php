<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Warehouse;

class ManufacturerWarehouseController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        if ($user->role !== 'manufacturer') {
            abort(403, 'Access denied. Manufacturer privileges required.');
        }
        $warehouses = Warehouse::where('manufacturer_id', $user->manufacturer->id)->paginate(10);
        return view('manufacturer.Warehouse.index', compact('warehouses'));
    }

    public function create()
    {
        return view('manufacturer.Warehouse.create');
    }

    public function store(Request $request)
    {
        $user = Auth::user();
        $request->validate([
            'location' => 'required|string|max:255',
            'capacity' => 'nullable|integer',
        ]);
        Warehouse::create(array_merge($request->all(), ['manufacturer_id' => $user->manufacturer->id]));
        return redirect()->route('manufacturer.warehouse.index')->with('success', 'Warehouse added successfully.');
    }

    public function edit(Warehouse $warehouse)
    {
        $this->authorize('update', $warehouse);
        return view('manufacturer.Warehouse.edit', compact('warehouse'));
    }

    public function update(Request $request, Warehouse $warehouse)
    {
        $this->authorize('update', $warehouse);
        $request->validate([
            'location' => 'required|string|max:255',
            'capacity' => 'nullable|integer',
        ]);
        $warehouse->update($request->all());
        return redirect()->route('manufacturer.warehouse.index')->with('success', 'Warehouse updated successfully.');
    }

    public function destroy(Warehouse $warehouse)
    {
        $this->authorize('delete', $warehouse);
        $warehouse->delete();
        return redirect()->route('manufacturer.warehouse.index')->with('success', 'Warehouse deleted successfully.');
    }

    public function show(Warehouse $warehouse)
    {
        $this->authorize('view', $warehouse);
        return view('manufacturer.Warehouse.show', compact('warehouse'));
    }

    // Show form to assign staff to a warehouse
    public function showStaffAssignmentForm(Warehouse $warehouse)
    {
        $this->authorize('update', $warehouse);
        // Get all workforce not already assigned to this warehouse
        $assignedIds = $warehouse->workforce()->pluck('workforces.id');
        $availableWorkforce = \App\Models\Workforce::whereNotIn('id', $assignedIds)->get();
        return view('manufacturer.Warehouse.assign-staff', compact('warehouse', 'availableWorkforce'));
    }

    // Assign staff to a warehouse
    public function assignStaff(Request $request, Warehouse $warehouse)
    {
        $this->authorize('update', $warehouse);
        $request->validate([
            'workforce_id' => 'required|exists:workforces,id',
        ]);
        $warehouse->workforce()->attach($request->workforce_id);
        return redirect()->route('manufacturer.warehouse.show', $warehouse)->with('success', 'Staff assigned successfully.');
    }

    // Remove staff from a warehouse
    public function removeStaff(Warehouse $warehouse, \App\Models\Workforce $workforce)
    {
        $this->authorize('update', $warehouse);
        $warehouse->workforce()->detach($workforce->id);
        return redirect()->route('manufacturer.warehouse.show', $warehouse)->with('success', 'Staff removed successfully.');
    }
} 