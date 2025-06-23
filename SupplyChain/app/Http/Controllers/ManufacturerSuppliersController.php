<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\SupplyRequest;

class ManufacturerSuppliersController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
        if ($user->role !== 'manufacturer') {
            abort(403, 'Access denied. Manufacturer privileges required.');
        }
        
        $suppliers = \App\Models\Supplier::with('user')->get();
        $stats = [
            'raw_materials' => 150,
            'products' => 85,
            'suppliers' => $suppliers->count(),
            'revenue' => '125,000',
        ];
        $recentActivities = [];
        return view('manufacturer.suppliers.index', compact('suppliers', 'stats', 'recentActivities'));
    }
}
