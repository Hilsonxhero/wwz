<?php

use Illuminate\Http\Request;
use Modules\Cart\Http\Controllers\v1\App\CartController;
use Modules\Cart\Http\Controllers\v1\App\PaymentController;
use Modules\Cart\Http\Controllers\v1\App\ShippingController;

Route::prefix('v1/application')->group(function () {
    Route::apiResource('cart', CartController::class);
    Route::get('shipping', [ShippingController::class, 'init'])->middleware(['auth:api']);
    Route::post('shipping', [ShippingController::class, 'store'])->middleware(['auth:api']);
    Route::post('shipping/cost', [ShippingController::class, 'cost'])->middleware(['auth:api']);
});
