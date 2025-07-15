<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\{
    Auth\RegisteredUserController,
    DashboardController,
    ProfileController,
    TypeController,
    UserController,
    SupplierChatController,
    InvoiceController,
    PublicController,
    CartController,
    CustomerController,
    CustomerOrderController
};

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Public Storefront Routes
Route::get('/', [PublicController::class, 'home'])->name('public.home');
Route::get('/products', [PublicController::class, 'products'])->name('public.products');
Route::get('/products/{id}', [PublicController::class, 'productDetail'])->name('public.product.detail');
Route::get('/search', [PublicController::class, 'search'])->name('public.search');

// Shopping Cart Routes
Route::prefix('cart')->name('cart.')->group(function () {
    Route::get('/', [CartController::class, 'index'])->name('index');
    Route::post('/add', [CartController::class, 'add'])->name('add');
    Route::post('/update', [CartController::class, 'update'])->name('update');
    Route::post('/remove', [CartController::class, 'remove'])->name('remove');
    Route::post('/clear', [CartController::class, 'clear'])->name('clear');
    Route::get('/count', [CartController::class, 'getCartCount'])->name('count');
    Route::get('/checkout', [CartController::class, 'checkout'])->name('checkout');
});

// Customer Authentication Routes
Route::prefix('customer')->name('customer.')->group(function () {
    // Guest routes
    Route::middleware('guest:customer')->group(function () {
        Route::get('/register', [CustomerController::class, 'showRegistrationForm'])->name('register');
        Route::post('/register', [CustomerController::class, 'register'])->name('register.store');
        Route::get('/login', [CustomerController::class, 'showLoginForm'])->name('login');
        Route::post('/login', [CustomerController::class, 'login'])->name('login.store');
    });
    
    // Authenticated customer routes
    Route::middleware('auth:customer')->group(function () {
        Route::post('/logout', [CustomerController::class, 'logout'])->name('logout');
        Route::get('/profile', [CustomerController::class, 'profile'])->name('profile');
        Route::post('/profile', [CustomerController::class, 'updateProfile'])->name('profile.update');
        Route::get('/orders', [CustomerController::class, 'orders'])->name('orders');
        Route::get('/orders/{id}', [CustomerController::class, 'orderDetail'])->name('order.detail');
        
        // Order management
        Route::post('/order', [CustomerOrderController::class, 'store'])->name('order.store');
        Route::get('/order/{id}/confirmation', [CustomerOrderController::class, 'confirmation'])->name('order.confirmation');
        Route::post('/order/{id}/cancel', [CustomerOrderController::class, 'cancel'])->name('order.cancel');
        Route::post('/order/{id}/reorder', [CustomerOrderController::class, 'reorder'])->name('order.reorder');
        Route::get('/order/{id}/track', [CustomerOrderController::class, 'track'])->name('order.track');
    });
});

// Public cart access (for checkout redirect)
Route::get('/cart', [CartController::class, 'index'])->name('public.cart');

// Welcome route
Route::get('/welcome', fn() => view('welcome'))->name('welcome');

// Authentication and Registration
Route::post('/register', [UserController::class, 'register']);
Route::post('/logout', [UserController::class, 'logout']);

// Role-specific registration
Route::prefix('register')->group(function () {
    Route::get('/new_user', [RegisteredUserController::class, 'createAdmin'])->name('register.admin');
    Route::post('/new_user', [RegisteredUserController::class, 'storeUser'])->name('register.admin.store');

    Route::post('/user', [RegisteredUserController::class, 'newUser'])->name('register.newUser');

    Route::get('/supplier', [RegisteredUserController::class, 'createSupplier'])->name('register.supplier');
    Route::post('/supplier', [RegisteredUserController::class, 'storeSupplier'])->name('register.supplier.store');

    Route::get('/manufacturer', [RegisteredUserController::class, 'createManufacturer'])->name('register.manufacturer');
    Route::post('/manufacturer', [RegisteredUserController::class, 'storeManufacturer'])->name('register.manufacturer.store');

    Route::get('/wholesaler', [RegisteredUserController::class, 'createWholesaler'])->name('register.wholesaler');
    Route::post('/wholesaler', [RegisteredUserController::class, 'storeWholesaler'])->name('register.wholesaler.store');
});

// Pending validation
Route::get('/pending-validation', fn() => view('auth.pending-validation'))->name('pending.validation');

// Dashboard redirection based on role and validation
Route::get('/dashboard', function () {
    $user = Auth::user();

    if ($user->is_verified === 'pending') {
        return redirect()->route('pending.validation');
    }

    return match ($user->role) {
        'admin' => redirect()->route('admin.dashboard'),
        'supplier' => redirect()->route('supplier.dashboard'),
        'manufacturer' => redirect()->route('manufacturer.dashboard'),
        'wholesaler' => redirect()->route('wholesaler.dashboard'),
        default => view('dashboard')
    };
})->middleware(['auth', 'verified'])->name('dashboard');

// Profile
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('/user-profile', [\App\Http\Controllers\UserProfileController::class, 'edit'])->name('user.profile.edit');
    Route::post('/user-profile', [\App\Http\Controllers\UserProfileController::class, 'update'])->name('user.profile.update');
    Route::post('/user-profile/password', [\App\Http\Controllers\UserProfileController::class, 'updatePassword'])->name('user.profile.password');
    Route::get('/user-profile/picture', [\App\Http\Controllers\UserProfileController::class, 'getProfilePicture'])->name('user.profile.picture');
});

// Admin
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [\App\Http\Controllers\AdminDashboardController::class, 'index'])->name('dashboard');

    //User Managemnet
    Route::get('/users', [App\Http\Controllers\AdminUsersController::class, 'index'])->name('users');
    Route::get('/users/add', fn() => view('admin.UsersManagement.addUser'))->name('users.add.view');
    Route::get('/users/edit', fn() => view('admin.UsersManagement.editUser'))->name('users.edit.view');
    Route::post('/users', [App\Http\Controllers\AdminUsersController::class, 'addUserView'])->name('users.addview');
    Route::post('/users/edit', [App\Http\Controllers\AdminUsersController::class, 'editUserView'])->name('users.editview');
    Route::post('/users/add', [App\Http\Controllers\AdminUsersController::class, 'addUser'])->name('users.add');
    Route::post('/users/remove', [App\Http\Controllers\AdminUsersController::class, 'removeUser'])->name('users.remove');
    Route::post('/users/update', [App\Http\Controllers\AdminUsersController::class, 'updateUser'])->name('users.update');
    
    // User Management Routes
    /*
    Route::prefix('users')->name('users.')->group(function () {
        Route::get('/table', [App\Http\Controllers\AdminUsersController::class, 'ajaxIndex'])->name('table');
        Route::get('/list', [App\Http\Controllers\AdminUsersController::class, 'getUsers'])->name('list');
        Route::get('/{user}', [App\Http\Controllers\AdminUsersController::class, 'show'])->name('show');
        Route::post('/', [App\Http\Controllers\AdminUsersController::class, 'store'])->name('store');
        Route::put('/{user}', [App\Http\Controllers\AdminUsersController::class, 'update'])->name('update');
        Route::delete('/{user}', [App\Http\Controllers\AdminUsersController::class, 'destroy'])->name('destroy');
    });
    */

    // User Roles Routes
    Route::prefix('user-roles')->name('user-roles.')->group(function () {
        Route::get('/ajax', [App\Http\Controllers\Admin\UserRoleController::class, 'ajaxIndex'])->name('ajax');
        Route::post('/{user}', [App\Http\Controllers\Admin\UserRoleController::class, 'update'])->name('update');
    });

    Route::get('/users/table', [App\Http\Controllers\AdminUsersController::class, 'ajaxIndex'])->name('users.table');

    Route::get('/analytics/user-registrations', [App\Http\Controllers\AdminDashboardController::class, 'userRegistrationsAnalytics'])->name('analytics.user-registrations');

    Route::get('/analytics', [App\Http\Controllers\Admin\AnalyticsController::class, 'index'])->name('admin.analytics');
});

Route::middleware(['auth', 'can:manage-users'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('user-roles', [App\Http\Controllers\Admin\UserRoleController::class, 'index'])->name('user-roles.index');
    Route::post('user-roles/{user}', [App\Http\Controllers\Admin\UserRoleController::class, 'update'])->name('user-roles.update');
});
Route::post('admin/user-roles/{user}/ajax', [App\Http\Controllers\Admin\UserRoleController::class, 'ajaxUpdate'])->name('admin.user-roles.ajax-update');
Route::post('admin/users/ajax', [App\Http\Controllers\AdminUsersController::class, 'ajaxStore'])->name('admin.users.ajax-store');
Route::put('admin/users/{user}/ajax', [App\Http\Controllers\AdminUsersController::class, 'ajaxUpdate'])->name('admin.users.ajax-update');
Route::delete('admin/users/{user}/ajax', [App\Http\Controllers\AdminUsersController::class, 'ajaxDestroy'])->name('admin.users.ajax-destroy');

Route::get('/admin/analytics/orders', [App\Http\Controllers\AdminDashboardController::class, 'ordersOverTime']);

// Supplier Routes
Route::middleware(['auth', 'role:supplier'])
    ->prefix('supplier')
    ->name('supplier.')
    ->group(function () {
        // Supplier dashboard etc.
        Route::get('/dashboard', [\App\Http\Controllers\SupplierController::class, 'dashboard'])->name('dashboard');
        Route::get('/supply-requests', [\App\Http\Controllers\SupplierController::class, 'supply-requests'])->name('supply-requests.index');
        Route::get('/analytics', [\App\Http\Controllers\SupplierController::class, 'analytics'])->name('analytics.index');
        Route::get('/chat', [\App\Http\Controllers\SupplierController::class, 'chat'])->name('chat.index');
        Route::get('/reports', [\App\Http\Controllers\SupplierController::class, 'reports'])->name('reports.index');

        // Supply Requests
        Route::resource('supply-requests', \App\Http\Controllers\SupplierController::class)->only(['update']);
        Route::post('supply-requests', [\App\Http\Controllers\SupplierController::class, 'store'])->name('supply-requests.store');
        Route::delete('supply-requests/{supplyRequest}', [\App\Http\Controllers\SupplierController::class, 'destroy'])->name('supply-requests.destroy');
        Route::post('supply-requests/{supplyRequest}/negotiate', [\App\Http\Controllers\SupplierController::class, 'submitPriceNegotiation'])->name('supply-requests.negotiate');
        Route::post('supply-requests/{supplyRequest}/status', [\App\Http\Controllers\SupplierController::class, 'ajaxUpdateSupplyRequestStatus'])->name('supply-requests.ajax-update-status');
        Route::get('supply-requests', [\App\Http\Controllers\SupplierController::class, 'supplyRequestsIndex'])->name('supply-requests.index');
        Route::get('supply-requests/{supplyRequest}', [\App\Http\Controllers\SupplierController::class, 'showSupplyRequest'])->name('supply-requests.show');

        // Supplied Items
        Route::get('supplied-items/{suppliedItem}', [\App\Http\Controllers\SupplierController::class, 'showSuppliedItem'])->name('supplied-items.show');
        Route::put('supplied-items/{suppliedItem}', [\App\Http\Controllers\SupplierController::class, 'updateSuppliedItem'])->name('supplied-items.update');
        Route::get('supplied-items', [\App\Http\Controllers\SupplierController::class, 'suppliedItems'])->name('supplied-items.index');

        // Supplier Chat Routes
        Route::prefix('chat')->name('chat.')->group(function () {
            Route::get('/', [\App\Http\Controllers\SupplierChatController::class, 'index'])->name('index');
            Route::get('/{contactId}', [\App\Http\Controllers\SupplierChatController::class, 'show'])->name('show');
            Route::post('/send', [\App\Http\Controllers\SupplierChatController::class, 'sendMessage'])->name('send');
            Route::post('/mark-read', [\App\Http\Controllers\SupplierChatController::class, 'markAsRead'])->name('mark-read');
            Route::get('/unread-count', [\App\Http\Controllers\SupplierChatController::class, 'getUnreadCount'])->name('unread-count');
            Route::get('/{contactId}/messages', [\App\Http\Controllers\SupplierChatController::class, 'getRecentMessages'])->name('messages');
        });
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

    //Analytics routes
    Route::get('/analytics', [App\Http\Controllers\ManufacturerAnalyticsController::class, 'index'])->name('analytics');
    Route::get('/analytics/chart-data', [App\Http\Controllers\ManufacturerAnalyticsController::class, 'getChartData'])->name('analytics.chart-data');
    Route::get('/analytics/supplier-report', [App\Http\Controllers\ManufacturerAnalyticsController::class, 'getSupplierReport'])->name('analytics.supplier-report');
    Route::get('/analytics/wholesaler-report', [App\Http\Controllers\ManufacturerAnalyticsController::class, 'getCustomerReport'])->name('analytics.wholesaler-report');
    
    // Forecast API routes
    Route::get('/analytics/forecast/options', [App\Http\Controllers\ManufacturerAnalyticsController::class, 'getForecastOptions'])->name('analytics.forecast.options');
    Route::post('/analytics/forecast/generate', [App\Http\Controllers\ManufacturerAnalyticsController::class, 'generateForecast'])->name('analytics.forecast.generate');

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
    Route::get('/reports/sales', [App\Http\Controllers\ManufacturerReportsController::class, 'sales'])->name('reports.sales');
    Route::get('/reports/inventory', [App\Http\Controllers\ManufacturerReportsController::class, 'inventory'])->name('reports.inventory');
    Route::get('/reports/suppliers', [App\Http\Controllers\ManufacturerReportsController::class, 'suppliers'])->name('reports.suppliers');
    Route::get('/reports/fulfillment', [App\Http\Controllers\ManufacturerReportsController::class, 'fulfillment'])->name('reports.fulfillment');
    Route::get('/reports/export/{type}', [App\Http\Controllers\ManufacturerReportsController::class, 'export'])->name('reports.export');
    
    // Chart data routes
    Route::get('/reports/chart/sales', [App\Http\Controllers\ManufacturerReportsController::class, 'getSalesChartData'])->name('reports.chart.sales');
    Route::get('/reports/chart/inventory', [App\Http\Controllers\ManufacturerReportsController::class, 'getInventoryChartData'])->name('reports.chart.inventory');
    Route::get('/reports/chart/suppliers', [App\Http\Controllers\ManufacturerReportsController::class, 'getSupplierChartData'])->name('reports.chart.suppliers');

    //Revenue routes
    Route::get('/revenue', [App\Http\Controllers\ManufacturerRevenueController::class, 'index'])->name('revenue');

    // Workforce routes
    Route::resource('workforce', App\Http\Controllers\ManufacturerWorkforceController::class)
        ->names('workforce');

    // Warehouse routes
    Route::resource('warehouse', App\Http\Controllers\ManufacturerWarehouseController::class)
        ->names('warehouse');

    // Mark notifications as read
    Route::post('/notifications/mark-as-read', [App\Http\Controllers\ManufacturerDashboardController::class, 'markNotificationsAsRead'])->name('notifications.markAsRead');

    // Manage partners (suppliers & wholesalers)
    Route::get('/partners', [App\Http\Controllers\ManufacturerDashboardController::class, 'managePartners'])->name('partners.manage');
    //Production routes
    Route::resource('production', App\Http\Controllers\WorkOrderController::class);
    Route::resource('bom', App\Http\Controllers\BillOfMaterialController::class)
        ->parameters(['bom' => 'billOfMaterial']);
    Route::resource('quality',App\Http\Controllers\QualityCheckController::class);

    Route::post('bom/{billOfMaterial}/add-component', [App\Http\Controllers\BillOfMaterialController::class, 'addComponent'])->name('manufacturer.bom.add-component');
    Route::put('bom/{billOfMaterial}/update-component/{component}', [App\Http\Controllers\BillOfMaterialController::class, 'updateComponent'])->name('manufacturer.bom.update-component');
});
    //manage supplyrequests
    Route::resource('supply-requests', \App\Http\Controllers\SupplyRequestController::class);

    // Production Management Routes
Route::get('manufacturer/work-orders', [App\Http\Controllers\WorkOrderController::class, 'index'])->name('manufacturer.work-orders.index');
Route::get('manufacturer/bom', [App\Http\Controllers\BillOfMaterialController::class, 'index'])->name('manufacturer.bom.index');
Route::get('manufacturer/production-schedules', [App\Http\Controllers\ProductionScheduleController::class, 'index'])->name('manufacturer.production-schedules.index');
Route::get('manufacturer/quality-checks', [App\Http\Controllers\QualityCheckController::class, 'index'])->name('manufacturer.quality-checks.index');
Route::get('manufacturer/downtime-logs', [App\Http\Controllers\DowntimeLogController::class, 'index'])->name('manufacturer.downtime-logs.index');
Route::get('manufacturer/production-costs', [App\Http\Controllers\ProductionCostController::class, 'index'])->name('manufacturer.production-costs.index');

//Production schedules
Route::get('manufacturer/production-schedules/create', [App\Http\Controllers\ProductionScheduleController::class, 'create'])->name('manufacturer.production-schedules.create');
Route::post('manufacturer/production-schedules', [App\Http\Controllers\ProductionScheduleController::class, 'store'])->name('manufacturer.production-schedules.store');
Route::get('manufacturer/production-schedules/{productionSchedule}', [App\Http\Controllers\ProductionScheduleController::class, 'show'])->name('manufacturer.production-schedules.show');
Route::get('manufacturer/production-schedules/{productionSchedule}/edit', [App\Http\Controllers\ProductionScheduleController::class, 'edit'])->name('manufacturer.production-schedules.edit');
Route::put('manufacturer/production-schedules/{productionSchedule}', [App\Http\Controllers\ProductionScheduleController::class, 'update'])->name('manufacturer.production-schedules.update');
Route::delete('manufacturer/production-schedules/{productionSchedule}', [App\Http\Controllers\ProductionScheduleController::class, 'destroy'])->name('manufacturer.production-schedules.destroy');

// Downtime Logs 
Route::get('manufacturer/downtime-logs/create', [App\Http\Controllers\DowntimeLogController::class, 'create'])->name('manufacturer.downtime-logs.create');
Route::post('manufacturer/downtime-logs', [App\Http\Controllers\DowntimeLogController::class, 'store'])->name('manufacturer.downtime-logs.store');
Route::get('manufacturer/downtime-logs/{downtimeLog}', [App\Http\Controllers\DowntimeLogController::class, 'show'])->name('manufacturer.downtime-logs.show');
Route::get('manufacturer/downtime-logs/{downtimeLog}/edit', [App\Http\Controllers\DowntimeLogController::class, 'edit'])->name('manufacturer.downtime-logs.edit');
Route::put('manufacturer/downtime-logs/{downtimeLog}', [App\Http\Controllers\DowntimeLogController::class, 'update'])->name('manufacturer.downtime-logs.update');
Route::delete('manufacturer/downtime-logs/{downtimeLog}', [App\Http\Controllers\DowntimeLogController::class, 'destroy'])->name('manufacturer.downtime-logs.destroy');

// Production Costs 
Route::get('manufacturer/production-costs/create', [App\Http\Controllers\ProductionCostController::class, 'create'])->name('manufacturer.production-costs.create');
Route::post('manufacturer/production-costs', [App\Http\Controllers\ProductionCostController::class, 'store'])->name('manufacturer.production-costs.store');
Route::get('manufacturer/production-costs/{productionCost}', [App\Http\Controllers\ProductionCostController::class, 'show'])->name('manufacturer.production-costs.show');
Route::get('manufacturer/production-costs/{productionCost}/edit', [App\Http\Controllers\ProductionCostController::class, 'edit'])->name('manufacturer.production-costs.edit');
Route::put('manufacturer/production-costs/{productionCost}', [App\Http\Controllers\ProductionCostController::class, 'update'])->name('manufacturer.production-costs.update');
Route::delete('manufacturer/production-costs/{productionCost}', [App\Http\Controllers\ProductionCostController::class, 'destroy'])->name('manufacturer.production-costs.destroy');


// Wholesaler
Route::middleware(['auth', 'role:wholesaler'])->prefix('wholesaler')->name('wholesaler.')->group(function () {
    Route::get('/dashboard', [\App\Http\Controllers\WholesalerDashboardController::class, 'index'])->name('dashboard');
    Route::resource('orders', \App\Http\Controllers\WholesalerOrderController::class)->only(['index', 'create', 'store', 'show']);
    Route::post('orders/{order}/cancel', [\App\Http\Controllers\WholesalerOrderController::class, 'cancel'])->name('orders.cancel');

    Route::get('analytics', [\App\Http\Controllers\WholesalerAnalyticsController::class, 'index'])->name('analytics.index');

    Route::prefix('chat')->group(function () {
        Route::get('/', [\App\Http\Controllers\WholesalerChatController::class, 'index'])->name('chat.index');
        Route::get('/{contactId}', [\App\Http\Controllers\WholesalerChatController::class, 'show'])->name('chat.show');
        Route::post('/send', [\App\Http\Controllers\WholesalerChatController::class, 'sendMessage'])->name('chat.send');
        Route::post('/mark-read', [\App\Http\Controllers\WholesalerChatController::class, 'markAsRead'])->name('chat.mark-read');
        Route::get('/unread-count', [\App\Http\Controllers\WholesalerChatController::class, 'getUnreadCount'])->name('chat.unread-count');
        Route::get('/{contactId}/messages', [\App\Http\Controllers\WholesalerChatController::class, 'getRecentMessages'])->name('chat.messages');
    });

    Route::prefix('reports')->group(function () {
        Route::get('/', [\App\Http\Controllers\WholesalerReportsController::class, 'index'])->name('reports.index');
        Route::get('/sales', [\App\Http\Controllers\WholesalerReportsController::class, 'salesReport'])->name('reports.sales');
        Route::get('/orders', [\App\Http\Controllers\WholesalerReportsController::class, 'orderReport'])->name('reports.orders');
        Route::get('/export', [\App\Http\Controllers\WholesalerReportsController::class, 'export'])->name('reports.export');
    });

    Route::middleware(['auth', 'role:wholesaler'])->group(function () {
        Route::get('/invoices', [InvoiceController::class, 'index'])->name('invoices.index');
        Route::get('/invoices/{id}', [InvoiceController::class, 'show'])->name('invoices.show');
    });
});

// Auth scaffolding
require __DIR__.'/auth.php';

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');


// Wholesaler notifications
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/wholesaler/notifications', [App\Http\Controllers\UserController::class, 'wholesalerNotifications']);
    Route::post('/wholesaler/notifications/mark-all-read', [App\Http\Controllers\UserController::class, 'markAllWholesalerNotificationsRead']);
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

// Validation Page
Route::get('/register/validation', function (){
    return view('auth.validation');
})->name('register.validation');

Route::get('admin/users/ajax', [App\Http\Controllers\AdminUsersController::class, 'ajaxIndex'])->name('admin.users.ajax');
Route::get('admin/users/{user}/ajax', [App\Http\Controllers\AdminUsersController::class, 'ajaxShow'])->name('admin.users.ajax-show');
Route::post('admin/users/ajax', [App\Http\Controllers\AdminUsersController::class, 'ajaxStore'])->name('admin.users.ajax-store');
Route::put('admin/users/{user}/ajax', [App\Http\Controllers\AdminUsersController::class, 'ajaxUpdate'])->name('admin.users.ajax-update');
Route::delete('admin/users/{user}/ajax', [App\Http\Controllers\AdminUsersController::class, 'ajaxDestroy'])->name('admin.users.ajax-destroy');

// BoM routes outside manufacturer group with simple names
Route::post('manufacturer/bom/{billOfMaterial}/add-component', [App\Http\Controllers\BillOfMaterialController::class, 'addComponent'])->name('test.add-component');
Route::put('manufacturer/bom/{billOfMaterial}/update-component/{component}', [App\Http\Controllers\BillOfMaterialController::class, 'updateComponent'])->name('test.update-component');
Route::delete('manufacturer/bom/{billOfMaterial}/delete-component/{component}', [App\Http\Controllers\BillOfMaterialController::class, 'deleteComponent'])->name('test.delete-component');

// Inventory Items nested under Warehouses
Route::resource('warehouses.inventory-items', App\Http\Controllers\InventoryItemController::class);

// Staff assignment routes for warehouses
Route::get('warehouse/{warehouse}/assign-staff', [App\Http\Controllers\ManufacturerWarehouseController::class, 'showStaffAssignmentForm'])->name('manufacturer.warehouse.assign-staff');
Route::post('warehouse/{warehouse}/assign-staff', [App\Http\Controllers\ManufacturerWarehouseController::class, 'assignStaff'])->name('manufacturer.warehouse.assign-staff.post');
Route::delete('warehouse/{warehouse}/remove-staff/{workforce}', [App\Http\Controllers\ManufacturerWarehouseController::class, 'removeStaff'])->name('manufacturer.warehouse.remove-staff');

Route::get('manufacturer/production/{workOrder}/assign-workforce', [App\Http\Controllers\WorkOrderAssignmentController::class, 'create'])->name('manufacturer.production.assign-workforce');
Route::post('manufacturer/production/{workOrder}/assign-workforce', [App\Http\Controllers\WorkOrderAssignmentController::class, 'store'])->name('manufacturer.production.assign-workforce.store');
Route::delete('manufacturer/production/assignment/{workOrderAssignment}', [App\Http\Controllers\WorkOrderAssignmentController::class, 'destroy'])->name('manufacturer.production.assignment.destroy');




