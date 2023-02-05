<?php

use Illuminate\Http\Request;
use Modules\Cart\Http\Controllers\v1\App\CartController;
use Modules\Cart\Http\Controllers\v1\App\PaymentController;
use Modules\Cart\Http\Controllers\v1\App\ShippingController;

Route::prefix('v1/application')->middleware(['auth:api'])->group(function () {
    Route::apiResource('cart', CartController::class);
    Route::get('shipping', [ShippingController::class, 'init']);
    Route::post('shipping', [ShippingController::class, 'store']);
    Route::post('shipping/cost', [ShippingController::class, 'cost']);
});
