<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Modules\Order\Http\Controllers\v1\Panel\OrderController;

Route::prefix('v1/application')->group(function () {
});

Route::prefix('v1/panel')->group(function () {
    //orders
    Route::apiResource("/orders", OrderController::class);
});
