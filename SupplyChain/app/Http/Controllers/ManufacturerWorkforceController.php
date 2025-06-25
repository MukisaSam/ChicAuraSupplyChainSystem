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
        $employees = Workforce::where('manufacturer_id', $user->id)->paginate(10);
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
            'job_role' => 'required|string|max:100',
            'status' => 'required|in:Active,Inactive,On Leave,Terminated',
            'hire_date' => 'nullable|date',
            'salary' => 'nullable|integer',
        ]);
        Workforce::create(array_merge($request->all(), ['manufacturer_id' => $user->id]));
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
            'job_role' => 'required|string|max:100',
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
} 