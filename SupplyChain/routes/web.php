<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ManufacturerDashboardController;
use App\Http\Controllers\SupplierDashboardController;
use App\Http\Controllers\WholesalerDashboardController;
use App\Http\Controllers\AdminDashboardController;

Route::middleware('auth')->group(function () {
    // ... (manufacturer, supplier, wholesaler routes)

    // Administrator Dashboard Route
    Route::get('/admin/dashboard', [AdminDashboardController::class, 'index'])->name('admin.dashboard');

    // ... (profile routes)
});

// Route for the Manufacturer Dashboard
Route::get('/manufacturer/dashboard', [ManufacturerDashboardController::class, 'index'])->name('manufacturer.dashboard');

 // Supplier Dashboard Route
 Route::get('/supplier/dashboard', [SupplierDashboardController::class, 'index'])->name('supplier.dashboard');

  // Wholesaler Dashboard Route
  Route::get('/wholesaler/dashboard', [WholesalerDashboardController::class, 'index'])->name('wholesaler.dashboard');