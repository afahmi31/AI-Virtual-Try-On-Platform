<?php

use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\SellerManagementController;
use App\Http\Controllers\Auth\WebAuthController;
use App\Http\Controllers\Public\SellerPublicController;
use App\Http\Controllers\Public\TryOnPublicController;
use App\Http\Controllers\Seller\SellerDashboardController;
use App\Http\Controllers\Seller\SellerProductController;
use Illuminate\Support\Facades\Route;

Route::redirect('/', '/login');

Route::middleware('guest')->group(function (): void {
    Route::get('/login', [WebAuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [WebAuthController::class, 'login'])->name('login.submit');
});

Route::post('/logout', [WebAuthController::class, 'logout'])->middleware('auth')->name('logout');

Route::prefix('admin')->middleware(['auth', 'role:admin'])->group(function (): void {
    Route::get('/', [AdminDashboardController::class, 'index'])->name('admin.dashboard');
    Route::get('/sellers', [SellerManagementController::class, 'index'])->name('admin.sellers.index');
    Route::post('/sellers', [SellerManagementController::class, 'store'])->name('admin.sellers.store');
    Route::patch('/sellers/{sellerId}', [SellerManagementController::class, 'update'])->name('admin.sellers.update');
    Route::post('/sellers/{sellerId}/topup', [SellerManagementController::class, 'topup'])->name('admin.sellers.topup');
});

Route::prefix('dashboard')->middleware(['auth', 'role:seller'])->group(function (): void {
    Route::get('/', [SellerDashboardController::class, 'index'])->name('seller.dashboard');
    Route::get('/products', [SellerProductController::class, 'index'])->name('seller.products.index');
    Route::post('/products', [SellerProductController::class, 'store'])->name('seller.products.store');
    Route::patch('/products/{productId}', [SellerProductController::class, 'update'])->name('seller.products.update');
    Route::delete('/products/{productId}', [SellerProductController::class, 'destroy'])->name('seller.products.destroy');
    Route::post('/products/{productId}/images', [SellerProductController::class, 'addImage'])->name('seller.products.images.store');
});

Route::prefix('{seller_slug}/try-on')->middleware('throttle:tryon-public')->group(function (): void {
    Route::post('/sessions', [TryOnPublicController::class, 'store'])->name('public.tryon.sessions.store');
    Route::get('/sessions/{sessionId}', [TryOnPublicController::class, 'show'])->name('public.tryon.sessions.show');
});

Route::get('/{seller_slug}/{product_slug?}', [SellerPublicController::class, 'index'])
    ->where('seller_slug', '^(?!admin$|dashboard$|api$|login$|logout$)[A-Za-z0-9\-]+$')
    ->where('product_slug', '[A-Za-z0-9\-]+')
    ->name('public.seller.page');
