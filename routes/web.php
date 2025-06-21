<?php

use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;

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

Route::middleware(['role:admin'])->group(function () {
    Route::get('/admin', [DashboardController::class, 'admin'])->name('admin.dashboard');


});

Route::middleware(['role:pelanggan'])->group(function () {
});

Route::middleware(['pelanggan_or_guest'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
});
