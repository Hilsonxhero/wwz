<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Modules\Order\Http\Controllers\v1\Panel\OrderController;
use Modules\Order\Http\Controllers\v1\Panel\OrderShippingController;

Route::prefix('v1/application')->group(function () {
    Route::prefix('user')->middleware('auth:api')->group(function () {
        Route::get("orders", [\Modules\Order\Http\Controllers\v1\App\OrderController::class, 'index']);
        Route::get("orders/{id}", [\Modules\Order\Http\Controllers\v1\App\OrderController::class, 'show']);
        Route::get("orders/utils/tabs", [\Modules\Order\Http\Controllers\v1\App\OrderController::class, 'tabs']);
    });
});

Route::prefix('v1/panel')->middleware(['auth.panel', 'auth:api'])->group(function () {
    //orders
    Route::apiResource("/orders", OrderController::class);
    Route::apiResource("/order/shippings", OrderShippingController::class);
});
