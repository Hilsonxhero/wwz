<?php

use Illuminate\Http\Request;
use Modules\Cart\Http\Controllers\v1\App\CartController;

Route::prefix('v1/application')->group(function () {
    Route::prefix('cart')->group(function () {
        Route::post('/', [CartController::class, 'get']);
        Route::post('add', [CartController::class, 'add']);
    });
});
