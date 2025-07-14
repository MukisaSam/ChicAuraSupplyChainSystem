<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Workforce;

class ManufacturerWorkforceController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        if ($user->role !== 'manufacturer') {
            abort(403, 'Access denied. Manufacturer privileges required.');
        }
        $employees = Workforce::where('manufacturer_id', $user->manufacturer->id)->paginate(10);
        return view('manufacturer.Workforce.index', compact('employees'));
    }

    public function create()
    {
        return view('manufacturer.Workforce.create');
    }

    public function store(Request $request)
    {
        $user = Auth::user();
        $request->validate([
            'fullname' => 'required|string|max:255',
            'email' => 'required|email|unique:workforces,email',
            'contact_info' => 'nullable|string|max:255',
            'address' => 'nullable|string|max:255',
            // Remove job_role from validation, as it will be set automatically
            //'job_role' => 'required|string|max:100',
            'status' => 'required|in:Active,Inactive,On Leave,Terminated',
            'hire_date' => 'nullable|date',
            'salary' => 'nullable|integer',
        ]);
        // Assign job_role randomly
        $roles = ['Operator', 'Warehouse Staff'];
        $randomRole = $roles[array_rand($roles)];
        $data = $request->all();
        $data['job_role'] = $randomRole;
        $data['manufacturer_id'] = $user->manufacturer->id;
        Workforce::create($data);
        return redirect()->route('manufacturer.workforce.index')->with('success', 'Employee added successfully.');
    }

    public function edit(Workforce $workforce)
    {
        $this->authorize('update', $workforce);
        return view('manufacturer.Workforce.edit', compact('workforce'));
    }

    public function update(Request $request, Workforce $workforce)
    {
        $this->authorize('update', $workforce);
        $request->validate([
            'fullname' => 'required|string|max:255',
            'email' => 'required|email|unique:workforces,email,' . $workforce->id,
            'contact_info' => 'nullable|string|max:255',
            'address' => 'nullable|string|max:255',
            // Remove job_role from validation, allow any string if present
            //'job_role' => 'required|string|max:100',
            'status' => 'required|in:Active,Inactive,On Leave,Terminated',
            'hire_date' => 'nullable|date',
            'salary' => 'nullable|integer',
        ]);
        $workforce->update($request->all());
        return redirect()->route('manufacturer.workforce.index')->with('success', 'Employee updated successfully.');
    }

    public function destroy(Workforce $workforce)
    {
        $this->authorize('delete', $workforce);
        $workforce->delete();
        return redirect()->route('manufacturer.workforce.index')->with('success', 'Employee deleted successfully.');
    }

    public function show(Workforce $workforce)
    {
        $this->authorize('view', $workforce);
        return view('manufacturer.Workforce.show', compact('workforce'));
    }
} 