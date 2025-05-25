<?php

use App\Http\Controllers\General\CartController;
use App\Http\Controllers\General\CouponController;
use App\Http\Controllers\Order\OrderController;
use App\Http\Controllers\Product\ProductController;
use App\Http\Controllers\Product\ProductVariationController;
use App\Models\Product;
use Illuminate\Support\Facades\Route;

Route::prefix('coupons')->group(function () {
    Route::get('/', [CouponController::class, 'index'])->name('coupons.index');

    Route::get('/create', fn() => view('coupons.form'))->name('coupons.create');
    Route::post('/', [CouponController::class, 'store'])->name('coupons.store');

    Route::post('/validate', [CouponController::class, 'validate'])->name('coupons.validate');

    Route::delete('/{uuid}', [CouponController::class, 'destroy'])->name('coupons.destroy');
});

Route::prefix('products')->group(function () {
    Route::get('/', [ProductController::class, 'index'])->name('products.index');
    Route::get('/create', [ProductController::class, 'create'])->name('products.create');
    Route::get('/edit/{uuid}', [ProductController::class, 'edit'])->name('products.edit');
    Route::post('/', [ProductController::class, 'store'])->name('products.store');

    Route::get('/{uuid}', [ProductController::class, 'show'])->name('products.show');
    Route::put('/{uuid}', [ProductController::class, 'update'])->name('products.update');
    Route::delete('/{uuid}', [ProductController::class, 'destroy'])->name('products.destroy');
    Route::post('/add-to-cart/{uuid}', [ProductController::class, 'addToCart'])->name('products.add-to-cart');
});

Route::prefix('product-variations')->group(function () {
    Route::get('/', [ProductVariationController::class, 'index'])->name('product-variations.index');

    Route::get('/edit/{uuid}', [ProductVariationController::class, 'edit'])->name('product-variations.edit');
    Route::get('/create', [ProductVariationController::class, 'create'])->name('product-variations.create');
    Route::post('/', [ProductVariationController::class, 'store'])->name('product-variations.store');

    Route::get('/{uuid}', [ProductVariationController::class, 'show'])->name('product-variations.show');
    Route::put('/{uuid}', [ProductVariationController::class, 'update'])->name('product-variations.update');
    Route::delete('/{uuid}', [ProductVariationController::class, 'destroy'])->name('product-variations.destroy');
    Route::post('/add-to-cart/{uuid}', [ProductVariationController::class, 'addToCart'])->name('product-variations.add-to-cart');
});

Route::prefix('cart')->group(function () {
    Route::get('/', [CartController::class, 'index'])->name('cart.index');
    Route::delete('/{uuid}', [CartController::class, 'remove'])->name('cart.remove');
    Route::put('/', [CartController::class, 'update'])->name('cart.update');
});

Route::prefix('orders')->group(function () {
    Route::get('/confirm-order', [OrderController::class, 'confirmOrder'])->name('orders.confirm-order');
    Route::post('/', [OrderController::class, 'store'])->name('orders.store');
});
