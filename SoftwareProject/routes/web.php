<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('auth.login');
})->name('user_login');

Route::get('/welcome', function () {
    return view('welcome');
});

//admin dashboard
Route::middleware(['auth', 'verified','admin'])->group(function () {
    Route::get('/admin/dashboard', function () {
        return view('admin.dashboard');
    })->name('admin.dashboard');
    
    Route::view('admin/analytics','admin.analytics')->name('admin.analytics');
    Route::view('admin/inventory','admin.inventory')->name('admin.inventory');
    Route::view('admin/suppliers','admin.suppliers')->name('admin.suppliers');
    Route::view('admin/users','admin.users')->name('admin.users');
    Route::view('admin/orders','admin.orders')->name('admin.orders');
    Route::view('admin/reports','admin.reports')->name('admin.reports');

});
//supplier dashboard

Route::middleware(['auth', 'verified','supplier'])->group(function () {
    Route::get('/supplier/dashboard', function () {
        return view('supplier.dashboard');
    })->name('supplier.dashboard');

    Route::view('supplier/analytics','supplier.analytics')->name('supplier.analytics');

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
