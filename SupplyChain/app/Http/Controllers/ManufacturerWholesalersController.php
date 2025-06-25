<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\SupplyRequest;

class ManufacturerWholesalersController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
        if ($user->role !== 'manufacturer') {
            abort(403, 'Access denied. Manufacturer privileges required.');
        }
        
        $wholesalers = \App\Models\Wholesaler::with('user')->get();
        return view('manufacturer.wholesalers.index', compact('wholesalers'));
    }
}
