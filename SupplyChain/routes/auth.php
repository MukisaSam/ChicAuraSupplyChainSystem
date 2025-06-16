<?php

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\ConfirmablePasswordController;
use App\Http\Controllers\Auth\EmailVerificationNotificationController;
use App\Http\Controllers\Auth\EmailVerificationPromptController;
use App\Http\Controllers\Auth\NewPasswordController;
use App\Http\Controllers\Auth\PasswordController;
use App\Http\Controllers\Auth\PasswordResetLinkController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\Auth\VerifyEmailController;
use Illuminate\Support\Facades\Route;

Route::middleware('guest')->group(function () {
    // Role selection page
    Route::get('register', [RegisteredUserController::class, 'create'])
                ->name('register');

    // Admin registration
    Route::get('register/admin', [RegisteredUserController::class, 'createAdmin'])
                ->name('register.admin');
    Route::post('register/admin', [RegisteredUserController::class, 'storeAdmin'])
                ->name('register.admin.store');

    // Supplier registration
    Route::get('register/supplier', [RegisteredUserController::class, 'createSupplier'])
                ->name('register.supplier');
    Route::post('register/supplier', [RegisteredUserController::class, 'storeSupplier'])
                ->name('register.supplier.store');

    // Manufacturer registration
    Route::get('register/manufacturer', [RegisteredUserController::class, 'createManufacturer'])
                ->name('register.manufacturer');
    Route::post('register/manufacturer', [RegisteredUserController::class, 'storeManufacturer'])
                ->name('register.manufacturer.store');

    // Wholesaler registration
    Route::get('register/wholesaler', [RegisteredUserController::class, 'createWholesaler'])
                ->name('register.wholesaler');
    Route::post('register/wholesaler', [RegisteredUserController::class, 'storeWholesaler'])
                ->name('register.wholesaler.store');

    Route::get('login', [AuthenticatedSessionController::class, 'create'])
                ->name('login');

    Route::post('login', [AuthenticatedSessionController::class, 'store']);

    Route::get('forgot-password', [PasswordResetLinkController::class, 'create'])
                ->name('password.request');

    Route::post('forgot-password', [PasswordResetLinkController::class, 'store'])
                ->name('password.email');

    Route::get('reset-password/{token}', [NewPasswordController::class, 'create'])
                ->name('password.reset');

    Route::post('reset-password', [NewPasswordController::class, 'store'])
                ->name('password.store');
});

Route::middleware('auth')->group(function () {
    Route::get('verify-email', EmailVerificationPromptController::class)
                ->name('verification.notice');

    Route::get('verify-email/{id}/{hash}', VerifyEmailController::class)
                ->middleware(['signed', 'throttle:6,1'])
                ->name('verification.verify');

    Route::post('email/verification-notification', [EmailVerificationNotificationController::class, 'store'])
                ->middleware('throttle:6,1')
                ->name('verification.send');

    Route::get('confirm-password', [ConfirmablePasswordController::class, 'show'])
                ->name('password.confirm');

    Route::post('confirm-password', [ConfirmablePasswordController::class, 'store']);

    Route::put('password', [PasswordController::class, 'update'])->name('password.update');

    Route::post('logout', [AuthenticatedSessionController::class, 'destroy'])
                ->name('logout');
});
