<?php

namespace App\Http\Controllers;

use App\Models\BillOfMaterialComponent;
use Illuminate\Http\Request;

class BillOfMaterialComponentController extends Controller
{
    public function index() { /* ... */ }
    public function create() { /* ... */ }
    public function store(Request $request) { /* ... */ }
    public function show(BillOfMaterialComponent $billOfMaterialComponent) { /* ... */ }
    public function edit(BillOfMaterialComponent $billOfMaterialComponent) { /* ... */ }
    public function update(Request $request, BillOfMaterialComponent $billOfMaterialComponent) { /* ... */ }
    public function destroy(BillOfMaterialComponent $billOfMaterialComponent) { /* ... */ }
} 