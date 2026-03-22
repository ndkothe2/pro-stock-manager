<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AppController;
use App\Http\Controllers\AuthController;


Route::get('/', function () {
    return redirect()->route('login');
});

Route::get('/login', [AppController::class, 'showLoginForm'])->name('login');
// turbo
Route::post('/login', [AppController::class, 'login']);

Route::get('/register', [AppController::class, 'showCustomerRegisterForm'])->name('register');
Route::post('/register', [AppController::class, 'registerCustomer']);

Route::middleware(['auth:web,customer'])->group(function () {
    Route::post('/logout', [AppController::class, 'logout'])->name('logout');
});

// Admin & Seller Portal Routes (Web Guard)
Route::middleware(['auth:web'])->group(function () {
    
    // ADMIN ONLY ROUTES
    Route::middleware(['role:admin'])->group(function () {
        Route::get('/admin/dashboard', [AppController::class, 'adminDashboard'])->name('admin.dashboard');
        Route::get('/admin/sellers', [AppController::class, 'manageSellers'])->name('admin.sellers');
        Route::get('/admin/analytics', [AppController::class, 'inventoryAnalytics'])->name('admin.analytics');
        Route::get('/admin/logs', [AppController::class, 'systemLogs'])->name('admin.logs');
        Route::post('/admin/logs/clear', [AppController::class, 'clearLogs'])->name('admin.logs.clear');
        Route::get('/admin/configurations', [AppController::class, 'configurations'])->name('admin.configurations');
        Route::post('/admin/configurations/update', [AppController::class, 'updateConfigurations'])->name('admin.configurations.update');
        Route::post('/admin/configurations/update-single', [AppController::class, 'updateSingleConfig'])->name('admin.configurations.updateSingle');
        Route::post('/admin/optimize', [AppController::class, 'optimizeDatabase'])->name('admin.optimize');
        
        Route::post('/seller-store', [AppController::class, 'sellerStore'])->name('seller.store');
        Route::get('/get-sellers-data', [AppController::class, 'getSellersData'])->name('seller.getAllSellers');
        Route::post('/toggle-seller-status', [AppController::class, 'toggleSellerStatus'])->name('seller.toggleStatus');
    });

    // SELLER ONLY ROUTES
    Route::middleware(['role:seller'])->group(function () {
        Route::get('/seller/dashboard', [AppController::class, 'getSellerDashboardStats'])->name('seller.dashboard');
        Route::get('/seller/add-product', [AppController::class, 'addProduct'])->name('products.add');
        Route::get('/products-list', [AppController::class, 'productList'])->name('products.list');
        Route::post('/products-store', [AppController::class, 'store'])->name('products.store');
        Route::get('/get-product-brands/{id}', [AppController::class, 'getProductBrands']);
        Route::post('/delete-product/{id}', [AppController::class, 'deleteProduct']);
        Route::get('/products/generate-pdf/{id}', [AppController::class, 'generateProductInventoryPdf'])->name('products.pdf');
    });

    // COMMON WEB ROUTES (If any)
    Route::get('/progress', [AppController::class, 'progress'])->name('progress');
    Route::get('/exams', [AppController::class, 'exams'])->name('exams');
    Route::get('/practice', [AppController::class, 'practice'])->name('practice');
    Route::get('/demo-report', [AppController::class, 'generateDummyProductInventoryPdf']);
});

// Customer Portal Routes (Customer Guard)
Route::middleware(['auth:customer'])->group(function () {
    Route::get('/customer/dashboard', [AppController::class, 'customerDashboard'])->name('customer.dashboard');
    Route::get('/customer/profile', [AppController::class, 'showCustomerProfile'])->name('customer.profile');
    Route::post('/customer/profile/update', [AppController::class, 'updateCustomerProfile'])->name('customer.profile.update');
    Route::get('/customer/wishlist', [AppController::class, 'showWishlist'])->name('customer.wishlist');

    // Cart Core Routes
    Route::get('/cart/fetch', [AppController::class, 'fetchCart'])->name('cart.fetch');
    Route::post('/cart/add', [AppController::class, 'addToCart'])->name('cart.add');
    Route::post('/cart/update', [AppController::class, 'updateCart'])->name('cart.update');
    Route::post('/cart/remove', [AppController::class, 'removeFromCart'])->name('cart.remove');
    
    // Wishlist Route
    Route::post('/wishlist/toggle', [AppController::class, 'toggleWishlist'])->name('wishlist.toggle');
});

Route::get('/dev/migrate', [AppController::class, 'runMigration']);

Route::get('/auth/google', [AuthController::class, 'redirectToGoogle']);
Route::get('/auth/google/callback', [AuthController::class, 'handleGoogleCallback']);