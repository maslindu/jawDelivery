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
use App\Http\Controllers\Admin\ManageUserController;
use App\Http\Controllers\FavoriteController;
use App\Http\Controllers\DriverController;


Route::get('/', function () {
    return view('welcome');
});

Route::get('/login', function () {
    return view('login');
})->name('login');

Route::get('/register', function () {
    return view('register');
})->name('register');

Route::prefix('auth')->group(function () {
    Route::post('register', [AuthController::class, 'register'])->name('api.auth.register');
    Route::post('login', [AuthController::class, 'login'])->name('api.auth.login');
    Route::post('logout', [AuthController::class, 'logout'])->name('api.auth.logout');
});

Route::middleware(['role:admin|pelanggan|kurir'])->group(function () {
    Route::get('/profile', [UserController::class, 'index'])->name('user');
    Route::put('/user/update', [UserController::class, 'update'])->name('user.update');
});

Route::prefix('admin')->middleware(['role:admin'])->group(function () {
    Route::get('/', [DashboardController::class, 'admin'])->name('admin.dashboard');

    // Menu Management Routes
    Route::get('/manage-menu', [MenuController::class, 'adminIndex'])->name('admin.manage-menu');
    Route::get('/menu/create', [MenuController::class, 'create'])->name('admin.menu.create');
    Route::post('/menu', [MenuController::class, 'store'])->name('admin.menu.store');
    Route::get('/menu/{id}/edit', [MenuController::class, 'edit'])->name('admin.menu.edit');
    Route::put('/menu/{id}', [MenuController::class, 'update'])->name('admin.menu.update');
    Route::delete('/menu/{id}', [MenuController::class, 'destroy'])->name('admin.menu.destroy');

    // Category Management Routes
    Route::get('/add-category', [CategoryController::class, 'create'])->name('admin.category.create');
    Route::post('/category', [CategoryController::class, 'store'])->name('admin.category.store');
    Route::put('/category/{id}', [CategoryController::class, 'update'])->name('admin.category.update');
    Route::delete('/category/{id}', [CategoryController::class, 'destroy'])->name('admin.category.destroy');

    Route::get('/manage-driver', [DriverController::class, 'index'])->name('admin.manage-driver');
    Route::get('/financial-reports', function () { return view('admin.financial-reports'); })->name('admin.financial-reports');

    // Order Management Routes
    Route::get('/orders', [AdminOrderController::class, 'index'])->name('admin.orders');
    Route::post('/orders/{id}/status', [AdminOrderController::class, 'updateStatus'])->name('admin.orders.update-status');
    Route::get('/orders/{id}', [AdminOrderController::class, 'show'])->name('admin.orders.show');
    Route::get('/orders-detail/{id}', [AdminOrderController::class, 'detail'])->name('admin.orders.detail');
    Route::get('/orders/status-counts', [AdminOrderController::class, 'getStatusCounts'])->name('admin.orders.status-counts');
    Route::get('/orders/{id}/status', [AdminOrderController::class, 'getOrderStatus'])->name('admin.orders.get-status');

    // User Management Routes - PERBAIKAN DI SINI
    Route::get('/manage-users', [ManageUserController::class, 'index'])->name('admin.manage-users');
    Route::post('/manage-users', [ManageUserController::class, 'store'])->name('admin.users.store');
    Route::put('/manage-users/{id}', [ManageUserController::class, 'update'])->name('admin.users.update');
    Route::delete('/manage-users/{id}', [ManageUserController::class, 'destroy'])->name('admin.users.destroy');
    Route::get('/users/{id}', [ManageUserController::class, 'show'])->name('admin.users.show');
});

Route::prefix('user')->middleware(['role:pelanggan'])->group(function () {
    Route::post('/address', [AddressController::class, 'store'])->name('user.address.store');
    Route::put('/address/{id}', [AddressController::class, 'update'])->name('user.address.update');
    Route::delete('/address/{id}', [AddressController::class, 'destroy'])->name('user.address.destroy');
    Route::get('/address', [AddressController::class, 'index'])->name('user.address');
    Route::post('/cart/add-item', [CartController::class, 'create'])->name('user.addItem');
    Route::get('/ubah-password', function () {
        return view('change-password');
    })->name('password.change');
});

//routes kurir
// Driver Dashboard Routes
Route::prefix('driver')->middleware(['auth', 'role:kurir'])->group(function () {
    Route::get('/', [App\Http\Controllers\Driver\DriverDashboardController::class, 'index'])->name('driver.dashboard');
    Route::get('/profile', [App\Http\Controllers\Driver\DriverDashboardController::class, 'profile'])->name('driver.profile');
    Route::get('/ready-orders', [App\Http\Controllers\Driver\DriverDashboardController::class, 'readyOrders'])->name('driver.ready-orders');
    Route::get('/processing-orders', [App\Http\Controllers\Driver\DriverDashboardController::class, 'processingOrders'])->name('driver.processing-orders');
    Route::get('/delivery-history', [App\Http\Controllers\Driver\DriverDashboardController::class, 'deliveryHistory'])->name('driver.delivery-history');
});



Route::prefix('order')->middleware(['auth', 'role:pelanggan'])->group(function () {
    Route::post('/', [OrderController::class, 'store'])->name('order.store');
    Route::get('/{id}', [OrderController::class, 'show'])->name('order.show');
});

Route::get('/history', [OrderController::class, 'index'])->name('user.history');

Route::get('/checkout', [CheckoutController::class, 'index'])
    ->middleware('role:pelanggan')
    ->name('checkout');

Route::middleware(['pelanggan_or_guest'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
});

Route::patch('/cart/{id}/quantity', [CartController::class, 'updateQuantity'])->middleware('role:pelanggan');
Route::delete('/cart/{id}', [CartController::class, 'destroy'])->middleware('role:pelanggan');

Route::middleware(['auth', 'role:pelanggan'])->prefix('favorites')->name('favorite.')->group(function () {
    Route::get('/', [FavoriteController::class, 'index'])->name('index');
    Route::post('/', [FavoriteController::class, 'store'])->name('store');
    Route::delete('/', [FavoriteController::class, 'destroy'])->name('destroy');
});

Route::put('/driver/update', [DriverController::class, 'updateDriver'])->name('driver.update');



Route::get('/admin/orders-detail/{code?}', function ($code = null) {
    return view('admin.orders-detail', ['code' => $code]);
});

// Route untuk update status order
Route::patch('/admin/orders/{order}/status', [OrderController::class, 'updateStatus'])->name('orders.updateStatus');

// Admin routes
Route::middleware(['auth'])->prefix('admin')->group(function () {
    Route::get('/orders', [AdminOrderController::class, 'index'])->name('admin.orders');
    Route::post('/orders/{id}/status', [AdminOrderController::class, 'updateStatus'])->name('admin.orders.update-status');
    Route::get('/orders/{id}', [AdminOrderController::class, 'show'])->name('admin.orders.show');
    Route::get('/orders/status-counts', [AdminOrderController::class, 'getStatusCounts'])->name('admin.orders.status-counts');
    Route::get('/orders/{id}/status', [AdminOrderController::class, 'getOrderStatus'])->name('admin.orders.get-status');
});

// Customer routes
Route::middleware(['auth'])->group(function () {
    Route::get('/orders', [OrderController::class, 'index'])->name('orders.index');
    Route::get('/orders/{id}', [OrderController::class, 'show'])->name('orders.show');
    Route::get('/orders/{id}/status', [OrderController::class, 'getStatus'])->name('orders.get-status');
});

Route::middleware(['auth'])->group(function () {
    Route::resource('drivers', DriverController::class);
    Route::patch('drivers/{driver}/toggle-availability', [DriverController::class, 'toggleAvailability'])
        ->name('drivers.toggle-availability');
    Route::patch('drivers/{driver}/update-status', [DriverController::class, 'updateStatus'])
        ->name('drivers.update-status');
});

// API routes for drivers
Route::prefix('api')->group(function () {
    Route::get('drivers/available', [DriverController::class, 'getAvailableDrivers'])
        ->name('api.drivers.available');
});

Route::prefix('driver')->middleware(['role:kurir'])->group(function () {
    Route::get('/', [DashboardController::class, 'driver'])->name('driver.dashboard');
});
