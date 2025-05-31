<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/login', function () {
    return view('login');
});

Route::prefix('auth')->group(function () {
    Route::post('signup', [AuthController::class, 'signup'])->name('api.auth.signup');
    Route::post('login', [AuthController::class, 'login'])->name('api.auth.login');
});
