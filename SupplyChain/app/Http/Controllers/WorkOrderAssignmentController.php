<?php

namespace App\Http\Controllers;

use App\Models\WorkOrderAssignment;
use Illuminate\Http\Request;

class WorkOrderAssignmentController extends Controller
{
    public function index() { /* ... */ }
    public function create() { /* ... */ }
    public function store(Request $request) { /* ... */ }
    public function show(WorkOrderAssignment $workOrderAssignment) { /* ... */ }
    public function edit(WorkOrderAssignment $workOrderAssignment) { /* ... */ }
    public function update(Request $request, WorkOrderAssignment $workOrderAssignment) { /* ... */ }
    public function destroy(WorkOrderAssignment $workOrderAssignment) { /* ... */ }
} 