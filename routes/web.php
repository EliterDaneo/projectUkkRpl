<?php

use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\SupplierController;

Route::middleware('guest')->group(function () {
    Route::get('/', function () {
        return view('welcome');
    })->name('login');
    Route::post('/proses-login', [AuthController::class, 'login'])->name('proses-login');
});

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [AuthController::class, 'dashboard'])->name('dashboard');
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::resource('/category', CategoryController::class, ['except' => ['show']]);
    Route::resource('/supplier', SupplierController::class, ['except' => ['show']]);
    Route::resource('/product', ProductController::class, ['except' => ['show']]);
});
