<?php

use Illuminate\Http\Request;
use Modules\Cart\Http\Controllers\v1\App\CartController;

Route::prefix('v1/application')->group(function () {
    Route::apiResource('cart', CartController::class);
    // Route::prefix('cart')->group(function () {
    //     Route::get('/', [CartController::class, 'get']);
    //     Route::post('add', [CartController::class, 'add']);
    // });
});
