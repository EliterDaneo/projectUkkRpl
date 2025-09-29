<?php

use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\SupplierController;

Route::middleware('guest')->group(function(){
    Route::get('/', function () {
        return view('welcome');
    });
    Route::post('/login', [AuthController::class, 'login'])->name('login');
});

Route::middleware('auth')->group(function(){
    Route::resource('/category', CategoryController::class, ['except' => ['show']]);
    Route::resource('/supplier', SupplierController::class, ['except' => ['show']]);
    Route::resource('/product', ProductController::class, ['except' => ['show']]);
});


