<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\TypeController;
use App\Http\Controllers\ManufacturerDashboardController;
use App\Http\Controllers\WholesalerDashboardController;
use App\Http\Controllers\AdminDashboardController;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/
Route::post('/register', [UserController::class, 'register']);
Route::post('/logout', [UserController::class, 'logout']);
Route::post('/login', [UserController::class, 'login']);

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
// Customer routes
Route::middleware(['auth', 'role:customer'])->group(function () {
    Route::get('/customer/dashboard', function () {
        return view('customer.dashboard');
    });
});

// Vendor routes
Route::middleware(['auth', 'role:vendor'])->group(function () {
    Route::get('/vendor/dashboard', function () {
        return view('vendor.dashboard');
    });
});

// Admin routes
Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/admin/dashboard', [AdminDashboardController::class, 'index'])->name('admin.dashboard');
});

// Supplier routes
Route::middleware(['auth', 'role:supplier'])->prefix('supplier')->name('supplier.')->group(function () {
    Route::get('/dashboard', [App\Http\Controllers\SupplierController::class, 'dashboard'])->name('dashboard');
    Route::get('/analytics', [App\Http\Controllers\SupplierController::class, 'analytics'])->name('analytics');
    Route::get('/supply-requests/{supplyRequest}', [App\Http\Controllers\SupplierController::class, 'showSupplyRequest'])->name('supply-requests.show');
    Route::put('/supply-requests/{supplyRequest}', [App\Http\Controllers\SupplierController::class, 'updateSupplyRequest'])->name('supply-requests.update');
    Route::post('/supply-requests/{supplyRequest}/negotiate', [App\Http\Controllers\SupplierController::class, 'submitPriceNegotiation'])->name('supply-requests.negotiate');
    Route::get('/supplied-items/{suppliedItem}', [App\Http\Controllers\SupplierController::class, 'showSuppliedItem'])->name('supplied-items.show');
    Route::put('/supplied-items/{suppliedItem}', [App\Http\Controllers\SupplierController::class, 'updateSuppliedItem'])->name('supplied-items.update');
});

// Admin registration routes
// Manufacturer routes
Route::middleware(['auth', 'role:manufacturer'])->prefix('manufacturer')->name('manufacturer.')->group(function () {
    Route::get('/dashboard', [ManufacturerDashboardController::class, 'index'])->name('dashboard');
});

// Wholesaler routes
Route::middleware(['auth', 'role:wholesaler'])->prefix('wholesaler')->name('wholesaler.')->group(function () {
    Route::get('/dashboard', [WholesalerDashboardController::class, 'index'])->name('dashboard');
});

Route::get('/register/admin', [RegisteredUserController::class, 'createAdmin'])->name('register.admin');
Route::post('/register/admin', [RegisteredUserController::class, 'storeAdmin'])->name('register.admin.store');

// Supplier registration routes
Route::get('/register/supplier', [RegisteredUserController::class, 'createSupplier'])->name('register.supplier');
Route::post('/register/supplier', [RegisteredUserController::class, 'storeSupplier'])->name('register.supplier.store');

// Manufacturer registration routes
Route::get('/register/manufacturer', [RegisteredUserController::class, 'createManufacturer'])->name('register.manufacturer');
Route::post('/register/manufacturer', [RegisteredUserController::class, 'storeManufacturer'])->name('register.manufacturer.store');

// Wholesaler registration routes
Route::get('/register/wholesaler', [RegisteredUserController::class, 'createWholesaler'])->name('register.wholesaler');
Route::post('/register/wholesaler', [RegisteredUserController::class, 'storeWholesaler'])->name('register.wholesaler.store');
