<?php

use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AddressController;

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

Route::middleware(['role:admin|pelanggan'])->group(function () {
    Route::get('/profile', [UserController::class, 'index'])->name('user');
    Route::put('/user/update', [UserController::class, 'update'])->name('user.update');
});

Route::prefix('admin')->middleware(['role:admin'])->group(function () {
    Route::get('/', [DashboardController::class, 'admin'])->name('admin.dashboard');
    Route::get('/orders', function () { return view('admin.orders'); })->name('admin.orders');
    Route::get('/manage-menu', function () { return view('admin.manage-menu'); })->name('admin.manage-menu');
    Route::get('/driver', function () { return view('admin.driver'); })->name('admin.driver');
    Route::get('/users', function () { return view('admin.users'); })->name('admin.users');
    Route::get('/reports', function () { return view('admin.reports'); })->name('admin.reports');
});


Route::prefix('user')->middleware(['role:pelanggan'])->group(function () {
    Route::post('/address', [AddressController::class, 'store'])->name('user.address.store');
    Route::put('/address/{id}', [AddressController::class, 'update'])->name('user.address.update');
    Route::delete('/address/{id}', [AddressController::class, 'destroy'])->name('user.address.destroy');
    Route::get('/address', [AddressController::class, 'index'])->name('user.address');
});



Route::middleware(['pelanggan_or_guest'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
});

Route::get('/ubah-password', function () {
    return view('change-password');
})->name('password.change');

Route::get('/checkout', function () {
    return view('checkout');
})->name('checkout');

Route::get('/favorite-menu', function () {
    return view('favorite-menu');
})->name('user.favorite');

Route::get('/history', function () {
    return view('history');
})->name('user.history');

Route::get('/admin/orders-detail/{code?}', function ($code = null) {
    return view('admin.orders-detail', ['code' => $code]);
});
