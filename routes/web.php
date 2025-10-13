<?php

use App\Models\Category;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ShoppingController;
use App\Http\Controllers\SupplierController;

Route::middleware('guest')->group(function () {
    Route::get('/', function () {
        $categories = Category::all();
        return view('welcome', compact('categories'));
    })->name('login');
    Route::post('/proses-login', [AuthController::class, 'login'])->name('proses-login');
});

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [AuthController::class, 'dashboard'])->name('dashboard');
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::resource('/category', CategoryController::class, ['except' => ['show']]);
    Route::resource('/supplier', SupplierController::class, ['except' => ['show']]);
    Route::resource('/product', ProductController::class, ['except' => ['show']]);

    Route::get('/kasir', [ShoppingController::class, 'index'])->name('kasir.index');
    Route::get('/api/products/{category}', [ShoppingController::class, 'getProductsByCategory']);
    Route::post('/api/transaction/process', [ShoppingController::class, 'processTransaction'])->name('api.transaction.process');

    // Rute Laporan PDF
    Route::get('/kasir/report/pdf', [ShoppingController::class, 'generateReportPdf'])->name('kasir.report.pdf');
});
