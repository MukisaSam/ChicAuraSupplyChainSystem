<?php

use App\Http\Controllers\ManufacturerDashboardController;
use Illuminate\Support\Facades\Route;

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

Route::get('/', function () {
    return view('welcome');
});

Route::middleware('auth')->group(function () {
    // Route for the Manufacturer Dashboard
    Route::get('/manufacturer/dashboard', [ManufacturerDashboardController::class, 'index'])->name('manufacturer.dashboard');
});

Route::get('/wholesaler/dashboard', function () {
    return view('wholesaler.dashboard');
})->name('wholesaler.dashboard');