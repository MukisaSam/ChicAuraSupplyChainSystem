<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ManufacturerDashboardController extends Controller
{
    public function index()
    {
        return view('manufacturer.dashboard');
    }
}
