<?php

use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AddressController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\MenuController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\Admin\AdminOrderController;
use App\Http\Controllers\Admin\AdminReportController;
use App\Http\Controllers\Admin\ManageUserController;
use App\Http\Controllers\FavoriteController;
use App\Http\Controllers\DriverController;

// Public Routes
Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::get('/login', function () {
    return view('login');
})->name('login');

Route::get('/register', function () {
    return view('register');
})->name('register');

// Authentication Routes
Route::prefix('auth')->group(function () {
    Route::post('register', [AuthController::class, 'register'])->name('api.auth.register');
    Route::post('login', [AuthController::class, 'login'])->name('api.auth.login');
    Route::post('logout', [AuthController::class, 'logout'])->name('api.auth.logout');
});

Route::middleware(['role:admin|pelanggan|kurir'])->group(function () {
    Route::get('/profile', [UserController::class, 'index'])->name('user');
    Route::put('/user/update', [UserController::class, 'update'])->name('user.update');
});

// ADMIN ROUTES
Route::prefix('admin')->middleware(['auth', 'role:admin'])->name('admin.')->group(function () {
    Route::get('/', [DashboardController::class, 'admin'])->name('dashboard');
    
    // Menu Management Routes
    Route::get('/manage-menu', [MenuController::class, 'adminIndex'])->name('manage-menu');
    Route::get('/menu/create', [MenuController::class, 'create'])->name('menu.create');
    Route::post('/menu', [MenuController::class, 'store'])->name('menu.store');
    Route::get('/menu/{id}/edit', [MenuController::class, 'edit'])->name('menu.edit');
    Route::put('/menu/{id}', [MenuController::class, 'update'])->name('menu.update');
    Route::delete('/menu/{id}', [MenuController::class, 'destroy'])->name('menu.destroy');

    // Category Management Routes
    Route::get('/add-category', [CategoryController::class, 'create'])->name('category.create');
    Route::post('/category', [CategoryController::class, 'store'])->name('category.store');
    Route::put('/category/{id}', [CategoryController::class, 'update'])->name('category.update');
    Route::delete('/category/{id}', [CategoryController::class, 'destroy'])->name('category.destroy');

    // Driver Management Routes
    Route::get('/manage-driver', [DriverController::class, 'index'])->name('manage-driver');
    Route::get('/drivers/create', [DriverController::class, 'create'])->name('drivers.create');
    Route::post('/drivers', [DriverController::class, 'store'])->name('drivers.store');
    Route::get('/drivers/{driver}', [DriverController::class, 'show'])->name('drivers.show');
    Route::get('/drivers/{driver}/edit', [DriverController::class, 'edit'])->name('drivers.edit');
    Route::put('/drivers/{driver}', [DriverController::class, 'update'])->name('drivers.update');
    Route::delete('/drivers/{driver}', [DriverController::class, 'destroy'])->name('drivers.destroy');
    Route::patch('/drivers/{driver}/toggle-availability', [DriverController::class, 'toggleAvailability'])->name('drivers.toggle-availability');
    Route::patch('/drivers/{driver}/update-status', [DriverController::class, 'updateStatus'])->name('drivers.update-status');
   
   
   Route::patch('/drivers/{driver}/update-status', [DriverController::class, 'updateStatus'])->name('drivers.update-status');

    // Order Management Routes
    Route::get('/orders', [AdminOrderController::class, 'index'])->name('orders');
    Route::get('/orders/{id}', [AdminOrderController::class, 'show'])->name('orders.show');
    Route::get('/orders-detail/{id}', [AdminOrderController::class, 'detail'])->name('orders.detail');
    Route::post('/orders/{id}/status', [AdminOrderController::class, 'updateStatus'])->name('orders.update-status');
    Route::get('/orders/status-counts', [AdminOrderController::class, 'getStatusCounts'])->name('orders.status-counts');
    Route::get('/orders/{id}/status', [AdminOrderController::class, 'getOrderStatus'])->name('orders.get-status');
    
    // Financial Reports Routes
    Route::get('/financial-reports', [AdminReportController::class, 'index'])->name('financial-reports');
    Route::get('/reports/export', [AdminReportController::class, 'export'])->name('reports.export');
    Route::get('/reports/revenue-data', [AdminReportController::class, 'getRevenueData'])->name('reports.revenue-data');

    // User Management Routes
    Route::get('/manage-users', [ManageUserController::class, 'index'])->name('manage-users');
    Route::post('/manage-users', [ManageUserController::class, 'store'])->name('users.store');
    Route::get('/users/{id}', [ManageUserController::class, 'show'])->name('users.show');
    Route::put('/manage-users/{id}', [ManageUserController::class, 'update'])->name('users.update');
    Route::delete('/manage-users/{id}', [ManageUserController::class, 'destroy'])->name('users.destroy');
});

// PELANGGAN ROUTES
Route::prefix('user')->middleware(['auth', 'role:pelanggan'])->name('user.')->group(function () {
    // Address Management
    Route::get('/address', [AddressController::class, 'index'])->name('address');
    Route::post('/address', [AddressController::class, 'store'])->name('address.store');
    Route::put('/address/{id}', [AddressController::class, 'update'])->name('address.update');
    Route::delete('/address/{id}', [AddressController::class, 'destroy'])->name('address.destroy');
    
    // Cart Management
    Route::post('/cart/add-item', [CartController::class, 'create'])->name('addItem');
    Route::patch('/cart/{id}/quantity', [CartController::class, 'updateQuantity'])->name('cart.update-quantity');
    Route::delete('/cart/{id}', [CartController::class, 'destroy'])->name('cart.destroy');
    
    // Password Change
    Route::get('/ubah-password', function () {
        return view('change-password');
    })->name('password.change');
    
    // Order History
    Route::get('/history', [OrderController::class, 'index'])->name('history');
});

// KURIR/DRIVER ROUTES - DIPERBAIKI
Route::prefix('driver')->middleware(['auth', 'role:kurir'])->name('driver.')->group(function () {
    Route::get('/', [App\Http\Controllers\Driver\DriverDashboardController::class, 'index'])->name('dashboard');
    Route::get('/dashboard', [App\Http\Controllers\Driver\DriverDashboardController::class, 'index'])->name('dashboard.alt');
    Route::get('/profile', [App\Http\Controllers\Driver\DriverDashboardController::class, 'profile'])->name('profile');
    
    // Order management routes
    Route::get('/ready-orders', [App\Http\Controllers\Driver\DriverOrderController::class, 'readyOrders'])->name('ready-orders');
    Route::get('/processing-orders', [App\Http\Controllers\Driver\DriverOrderController::class, 'processingOrders'])->name('processing-orders');
    Route::get('/delivery-history', [App\Http\Controllers\Driver\DriverOrderController::class, 'deliveryHistory'])->name('delivery-history');
    
    // Actions untuk driver
    Route::post('/take-order/{orderId}', [App\Http\Controllers\Driver\DriverOrderController::class, 'takeOrder'])->name('take-order');
    Route::post('/complete-delivery/{orderId}', [App\Http\Controllers\Driver\DriverOrderController::class, 'completeDelivery'])->name('complete-delivery');
    
    // Status management
    Route::patch('/toggle-availability', [App\Http\Controllers\Driver\DriverDashboardController::class, 'toggleAvailability'])->name('toggle-availability');
    Route::patch('/update-status', [App\Http\Controllers\Driver\DriverDashboardController::class, 'updateStatus'])->name('update-status');
    Route::get('/stats', [App\Http\Controllers\Driver\DriverDashboardController::class, 'getStats'])->name('stats');
});

// ORDER ROUTES
Route::middleware(['auth'])->group(function () {
    // Order creation and management
    Route::prefix('order')->middleware(['role:pelanggan'])->group(function () {
        Route::post('/', [OrderController::class, 'store'])->name('order.store');
        Route::get('/{id}', [OrderController::class, 'show'])->name('order.show');
        Route::get('/{id}/status', [OrderController::class, 'getStatus'])->name('orders.get-status');
    });
    
    // General order routes
    Route::get('/orders', [OrderController::class, 'index'])->name('orders.index');
    Route::get('/orders/{id}', [OrderController::class, 'show'])->name('orders.show');
});

// CHECKOUT ROUTES
Route::get('/checkout', [CheckoutController::class, 'index'])
    ->middleware(['auth', 'role:pelanggan'])
    ->name('checkout');

// DASHBOARD ROUTES
Route::middleware(['pelanggan_or_guest'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
});

// FAVORITES ROUTES
Route::middleware(['auth', 'role:pelanggan'])->prefix('favorites')->name('favorite.')->group(function () {
    Route::get('/', [FavoriteController::class, 'index'])->name('index');
    Route::post('/', [FavoriteController::class, 'store'])->name('store');
    Route::delete('/', [FavoriteController::class, 'destroy'])->name('destroy');
});

// Driver update route
Route::put('/driver/update', [DriverController::class, 'updateDriver'])->name('driver.update');

// API ROUTES
Route::prefix('api')->group(function () {
    Route::get('drivers/available', [DriverController::class, 'getAvailableDrivers'])
        ->name('api.drivers.available');
});

// Order status update route
Route::patch('/admin/orders/{order}/status', [OrderController::class, 'updateStatus'])->name('orders.updateStatus');

// Legacy route for orders detail
Route::get('/admin/orders-detail/{code?}', function ($code = null) {
    return view('admin.orders-detail', ['code' => $code]);
});
