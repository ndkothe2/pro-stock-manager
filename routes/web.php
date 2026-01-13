<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AppController;

Route::get('/', function () {
    return redirect()->route('login');
});

Route::get('/login', [AppController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AppController::class, 'login']);

Route::middleware('auth')->group(function () {
    Route::get('/admin/dashboard', [AppController::class, 'adminDashboard'])->name('admin.dashboard');
    Route::get('/seller/dashboard', [AppController::class, 'sellerDashboard'])->name('seller.dashboard');
    Route::post('/logout', [AppController::class, 'logout'])->name('logout');
    Route::get('/admin/sellers', [AppController::class, 'manageSellers'])->name('admin.sellers');
    Route::get('/progress', [AppController::class, 'progress'])->name('progress');
    Route::get('/exams', [AppController::class, 'exams'])->name('exams');
    Route::get('/practice', [AppController::class, 'practice'])->name('practice');
    Route::post('/seller-store', [AppController::class, 'sellerStore'])->name('seller.store');
    Route::get('/get-sellers-data', [AppController::class, 'getSellersData'])->name('seller.getAllSellers');
    Route::post('/toggle-seller-status', [AppController::class, 'toggleSellerStatus'])->name('seller.toggleStatus');
    Route::get('/admin/dashboard', [AppController::class, 'getDashboardStats'])->name('admin.dashboard');
    Route::get('/seller/add-product', [AppController::class, 'addProduct'])->name('products.add');
    Route::get('/seller/add-product', [AppController::class, 'addProduct'])->name('products.add');
    Route::get('/products-list', [AppController::class, 'productList'])->name('products.list');
    Route::post('/products-store', [AppController::class, 'store'])->name('products.store');
    Route::get('/get-product-brands/{id}', [AppController::class, 'getProductBrands']);
    Route::post('/delete-product/{id}', [AppController::class, 'deleteProduct']);
    Route::get('/products/generate-pdf/{id}', [AppController::class, 'generateProductInventoryPdf'])->name('products.pdf');
    Route::get('/seller/dashboard', [AppController::class, 'getSellerDashboardStats'])->name('seller.dashboard')->middleware('auth');
});