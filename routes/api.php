<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\V1\Admin\BrandController as AdminBrandController;
use App\Http\Controllers\Api\V1\Admin\BulkPricingRuleController as AdminBulkPricingRuleController;
use App\Http\Controllers\Api\V1\Admin\CategoryController as AdminCategoryController;
use App\Http\Controllers\Api\V1\Admin\OrderController as AdminOrderController;
use App\Http\Controllers\Api\V1\Admin\ProductBundleController as AdminProductBundleController;
use App\Http\Controllers\Api\V1\Admin\ProductController as AdminProductController;
use App\Http\Controllers\Api\V1\Admin\UserController as AdminUserController;
use App\Http\Controllers\Api\V1\Admin\VendorController as AdminVendorController;
use App\Http\Controllers\Api\V1\Buyer\BrandController as BuyerBrandController;
use App\Http\Controllers\Api\V1\Buyer\CartController as BuyerCartController;
use App\Http\Controllers\Api\V1\Buyer\CategoryController as BuyerCategoryController;
use App\Http\Controllers\Api\V1\Buyer\OrderController as BuyerOrderController;
use App\Http\Controllers\Api\V1\Buyer\ProductController as BuyerProductController;
use App\Http\Controllers\Api\V1\Buyer\ReviewController as BuyerReviewController;
use App\Http\Controllers\Api\V1\Buyer\VendorController as BuyerVendorController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->group(function () {
    Route::prefix('auth')->group(function () {
        Route::post('register', [AuthController::class, 'register']);
        Route::post('login', [AuthController::class, 'login']);

        Route::middleware('auth:sanctum')->group(function () {
            Route::get('user', [AuthController::class, 'user']);
            Route::post('logout', [AuthController::class, 'logout']);
        });
    });

    Route::prefix('admin')
        ->middleware(['auth:sanctum', 'role:admin'])
        ->group(function () {
            Route::get('dashboard', function () {
                return response()->json([
                    'message' => 'Admin access granted.',
                ]);
            });

            Route::apiResource('products', AdminProductController::class);
            Route::apiResource('categories', AdminCategoryController::class);
            Route::apiResource('brands', AdminBrandController::class);
            Route::apiResource('vendors', AdminVendorController::class);
            Route::apiResource('bundles', AdminProductBundleController::class);
            Route::apiResource('bulk-pricing-rules', AdminBulkPricingRuleController::class);
            Route::get('orders', [AdminOrderController::class, 'index']);
            Route::get('orders/{order}', [AdminOrderController::class, 'show']);
            Route::put('orders/{order}', [AdminOrderController::class, 'update']);
            Route::get('users', [AdminUserController::class, 'index']);
            Route::get('users/{user}', [AdminUserController::class, 'show']);
            Route::put('users/{user}', [AdminUserController::class, 'update']);
        });

    Route::get('products', [BuyerProductController::class, 'index']);
    Route::get('products/{product}', [BuyerProductController::class, 'show']);
    Route::get('categories', [BuyerCategoryController::class, 'index']);
    Route::get('brands', [BuyerBrandController::class, 'index']);
    Route::get('vendors', [BuyerVendorController::class, 'index']);

    Route::middleware('auth:sanctum')->group(function () {
        Route::get('cart', [BuyerCartController::class, 'show']);
        Route::post('cart/items', [BuyerCartController::class, 'storeItem']);
        Route::put('cart/items/{item}', [BuyerCartController::class, 'updateItem']);
        Route::delete('cart/items/{item}', [BuyerCartController::class, 'destroyItem']);
        Route::delete('cart', [BuyerCartController::class, 'clear']);

        Route::get('orders', [BuyerOrderController::class, 'index']);
        Route::post('orders', [BuyerOrderController::class, 'store']);
        Route::get('orders/{order}', [BuyerOrderController::class, 'show']);

        Route::get('reviews', [BuyerReviewController::class, 'index']);
        Route::post('reviews', [BuyerReviewController::class, 'store']);
        Route::put('reviews/{review}', [BuyerReviewController::class, 'update']);
        Route::delete('reviews/{review}', [BuyerReviewController::class, 'destroy']);
    });
});
