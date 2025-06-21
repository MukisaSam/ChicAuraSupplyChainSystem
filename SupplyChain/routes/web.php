<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\TypeController;
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

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    $user = Auth::user();
    
    switch ($user->role) {
        case 'admin':
            return redirect()->route('admin.dashboard');
        case 'supplier':
            return redirect()->route('supplier.dashboard');
        case 'manufacturer':
            return redirect()->route('manufacturer.dashboard');
        case 'wholesaler':
            return redirect()->route('wholesaler.dashboard');
        default:
            return view('dashboard');
    }
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    
    // User profile routes
    Route::get('/user-profile', [App\Http\Controllers\UserProfileController::class, 'edit'])->name('user.profile.edit');
    Route::post('/user-profile', [App\Http\Controllers\UserProfileController::class, 'update'])->name('user.profile.update');
    Route::post('/user-profile/password', [App\Http\Controllers\UserProfileController::class, 'updatePassword'])->name('user.profile.password');
    Route::get('/user-profile/picture', [App\Http\Controllers\UserProfileController::class, 'getProfilePicture'])->name('user.profile.picture');
});

require __DIR__.'/auth.php';

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

// Admin routes
Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/admin/dashboard', [App\Http\Controllers\AdminDashboardController::class, 'index'])->name('admin.dashboard');
     Route::get('/admin/users', [App\Http\Controllers\AdminUsersController::class, 'index'])->name('admin.users');
});

// Supplier routes
Route::middleware(['auth', 'role:supplier'])->prefix('supplier')->name('supplier.')->group(function () {
    Route::get('/dashboard', [App\Http\Controllers\SupplierController::class, 'dashboard'])->name('dashboard');
    Route::get('/analytics', [App\Http\Controllers\SupplierController::class, 'analytics'])->name('analytics');
    Route::get('/chat', [App\Http\Controllers\SupplierController::class, 'chat'])->name('chat');
    Route::get('/reports', [App\Http\Controllers\SupplierController::class, 'reports'])->name('reports');
    Route::get('/supply-requests/{supplyRequest}', [App\Http\Controllers\SupplierController::class, 'showSupplyRequest'])->name('supply-requests.show');
    Route::put('/supply-requests/{supplyRequest}', [App\Http\Controllers\SupplierController::class, 'updateSupplyRequest'])->name('supply-requests.update');
    Route::post('/supply-requests/{supplyRequest}/negotiate', [App\Http\Controllers\SupplierController::class, 'submitPriceNegotiation'])->name('supply-requests.negotiate');
    Route::get('/supplied-items/{suppliedItem}', [App\Http\Controllers\SupplierController::class, 'showSuppliedItem'])->name('supplied-items.show');
    Route::put('/supplied-items/{suppliedItem}', [App\Http\Controllers\SupplierController::class, 'updateSuppliedItem'])->name('supplied-items.update');
});

// Manufacturer routes
Route::middleware(['auth', 'role:manufacturer'])->prefix('manufacturer')->name('manufacturer.')->group(function () {

    //Dashboard
    Route::get('/dashboard', [App\Http\Controllers\ManufacturerDashboardController::class, 'index'])->name('dashboard');
    
    //Orders routes
    Route::get('/orders/analytics', [App\Http\Controllers\ManufacturerOrdersController::class, 'analytics'])->name('orders.analytics');
    Route::get('/orders', [App\Http\Controllers\ManufacturerOrdersController::class, 'index'])->name('orders');
    Route::get('/orders/{order}', [App\Http\Controllers\ManufacturerOrdersController::class, 'show'])->name('orders.show');
    Route::put('/orders/{order}/status', [App\Http\Controllers\ManufacturerOrdersController::class, 'updateStatus'])->name('orders.update-status');
    
    // Supply Request routes
    Route::get('/orders/supply-requests/create', [App\Http\Controllers\ManufacturerOrdersController::class, 'createSupplyRequest'])->name('orders.create-supply-request');
    Route::post('/orders/supply-requests', [App\Http\Controllers\ManufacturerOrdersController::class, 'storeSupplyRequest'])->name('orders.store-supply-request');
    Route::get('/orders/supply-requests/{supplyRequest}', [App\Http\Controllers\ManufacturerOrdersController::class, 'showSupplyRequest'])->name('orders.show-supply-request');
    Route::put('/orders/supply-requests/{supplyRequest}/status', [App\Http\Controllers\ManufacturerOrdersController::class, 'updateSupplyRequestStatus'])->name('orders.update-supply-request-status');
    
    // Orders Analytics
    
    //Analytics routes
    Route::get('/analytics', [App\Http\Controllers\ManufacturerAnalyticsController::class, 'index'])->name('analytics');
    Route::get('/analytics/chart-data', [App\Http\Controllers\ManufacturerAnalyticsController::class, 'getChartData'])->name('analytics.chart-data');
    Route::get('/analytics/supplier-report', [App\Http\Controllers\ManufacturerAnalyticsController::class, 'getSupplierReport'])->name('analytics.supplier-report');
    Route::get('/analytics/wholesaler-report', [App\Http\Controllers\ManufacturerAnalyticsController::class, 'getCustomerReport'])->name('analytics.wholesaler-report');

    //Inventory routes
    Route::get('/inventory', [App\Http\Controllers\ManufacturerInventoryController::class, 'index'])->name('inventory');
    Route::get('/inventory/create', [App\Http\Controllers\ManufacturerInventoryController::class, 'create'])->name('inventory.create');
    Route::post('/inventory', [App\Http\Controllers\ManufacturerInventoryController::class, 'store'])->name('inventory.store');
    Route::get('/inventory/{item}/edit', [App\Http\Controllers\ManufacturerInventoryController::class, 'edit'])->name('inventory.edit');
    Route::put('/inventory/{item}', [App\Http\Controllers\ManufacturerInventoryController::class, 'update'])->name('inventory.update');
    Route::delete('/inventory/{item}', [App\Http\Controllers\ManufacturerInventoryController::class, 'destroy'])->name('inventory.destroy');
    Route::post('/inventory/{item}/stock', [App\Http\Controllers\ManufacturerInventoryController::class, 'updateStock'])->name('inventory.update-stock');
    Route::get('/inventory/analytics', [App\Http\Controllers\ManufacturerInventoryController::class, 'analytics'])->name('inventory.analytics');
    Route::get('/inventory/chart-data', [App\Http\Controllers\ManufacturerInventoryController::class, 'getChartData'])->name('inventory.chart-data');

    //Wholesalers routes
    Route::get('/wholesalers', [App\Http\Controllers\ManufacturerWholesalersController::class, 'index'])->name('wholesalers');

    //Suppliers routes
    Route::get('/suppliers', [App\Http\Controllers\ManufacturerSuppliersController::class, 'index'])->name('suppliers');

    //Chat routes
    Route::get('/chat', [App\Http\Controllers\ManufacturerChatController::class, 'index'])->name('chat');
    Route::get('/chat/{contactId}', [App\Http\Controllers\ManufacturerChatController::class, 'show'])->name('chat.show');
    Route::post('/chat/send', [App\Http\Controllers\ManufacturerChatController::class, 'sendMessage'])->name('chat.send');
    Route::post('/chat/mark-read', [App\Http\Controllers\ManufacturerChatController::class, 'markAsRead'])->name('chat.mark-read');
    Route::get('/chat/unread-count', [App\Http\Controllers\ManufacturerChatController::class, 'getUnreadCount'])->name('chat.unread-count');
    Route::get('/chat/{contactId}/messages', [App\Http\Controllers\ManufacturerChatController::class, 'getRecentMessages'])->name('chat.messages');
    Route::get('/chat/unread-messages', [App\Http\Controllers\ManufacturerChatController::class, 'getUnreadMessages'])->name('chat.unread-messages');

    //Reports routes
    Route::get('/reports', [App\Http\Controllers\ManufacturerReportsController::class, 'index'])->name('reports');

    //Revenue routes
    Route::get('/revenue', [App\Http\Controllers\ManufacturerRevenueController::class, 'index'])->name('revenue');

});

// Wholesaler routes
Route::middleware(['auth', 'role:wholesaler'])->prefix('wholesaler')->name('wholesaler.')->group(function () {
    Route::get('/dashboard', [App\Http\Controllers\WholesalerDashboardController::class, 'index'])->name('dashboard');
    
    // Order routes
    Route::get('/orders', [App\Http\Controllers\WholesalerOrderController::class, 'index'])->name('orders.index');
    Route::get('/orders/create', [App\Http\Controllers\WholesalerOrderController::class, 'create'])->name('orders.create');
    Route::post('/orders', [App\Http\Controllers\WholesalerOrderController::class, 'store'])->name('orders.store');
    Route::get('/orders/{order}', [App\Http\Controllers\WholesalerOrderController::class, 'show'])->name('orders.show');
    Route::post('/orders/{order}/cancel', [App\Http\Controllers\WholesalerOrderController::class, 'cancel'])->name('orders.cancel');
    
    // Analytics routes
    Route::get('/analytics', [App\Http\Controllers\WholesalerAnalyticsController::class, 'index'])->name('analytics.index');
    
    // Chat routes
    Route::get('/chat', [App\Http\Controllers\WholesalerChatController::class, 'index'])->name('chat.index');
    Route::get('/chat/{contactId}', [App\Http\Controllers\WholesalerChatController::class, 'show'])->name('chat.show');
    Route::post('/chat/send', [App\Http\Controllers\WholesalerChatController::class, 'sendMessage'])->name('chat.send');
    Route::post('/chat/mark-read', [App\Http\Controllers\WholesalerChatController::class, 'markAsRead'])->name('chat.mark-read');
    Route::get('/chat/unread-count', [App\Http\Controllers\WholesalerChatController::class, 'getUnreadCount'])->name('chat.unread-count');
    Route::get('/chat/{contactId}/messages', [App\Http\Controllers\WholesalerChatController::class, 'getRecentMessages'])->name('chat.messages');

    // Reports routes
    Route::get('/reports', [App\Http\Controllers\WholesalerReportsController::class, 'index'])->name('reports.index');
    Route::get('/reports/sales', [App\Http\Controllers\WholesalerReportsController::class, 'salesReport'])->name('reports.sales');
    Route::get('/reports/orders', [App\Http\Controllers\WholesalerReportsController::class, 'orderReport'])->name('reports.orders');
    Route::get('/reports/export', [App\Http\Controllers\WholesalerReportsController::class, 'export'])->name('reports.export');
});

// Registration routes
Route::get('/register/new_user', [RegisteredUserController::class, 'createAdmin'])->name('register.admin');
Route::post('/register/new_user', [RegisteredUserController::class, 'storeUser'])->name('register.admin.store');

// Supplier registration routes
Route::get('/register/supplier', [RegisteredUserController::class, 'createSupplier'])->name('register.supplier');
Route::post('/register/supplier', [RegisteredUserController::class, 'storeSupplier'])->name('register.supplier.store');

// Manufacturer registration routes
Route::get('/register/manufacturer', [RegisteredUserController::class, 'createManufacturer'])->name('register.manufacturer');
Route::post('/register/manufacturer', [RegisteredUserController::class, 'storeManufacturer'])->name('register.manufacturer.store');

// Wholesaler registration routes
Route::get('/register/wholesaler', [RegisteredUserController::class, 'createWholesaler'])->name('register.wholesaler');
Route::post('/register/wholesaler', [RegisteredUserController::class, 'storeWholesaler'])->name('register.wholesaler.store');

