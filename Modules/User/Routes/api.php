<?php

use Illuminate\Http\Request;
use Modules\User\Http\Controllers\v1\Panel\UserController;



Route::prefix('v1/application')->group(function () {
    Route::prefix('user')->group(function () {
        Route::get("init", [\Modules\User\Http\Controllers\v1\Application\AuthController::class, 'init']);
        Route::post("authenticate", [\Modules\User\Http\Controllers\v1\Application\AuthController::class, 'authenticate']);
        Route::post("login/otp", [\Modules\User\Http\Controllers\v1\Application\LoginController::class, 'otp']);
        Route::post("logout", [\Modules\User\Http\Controllers\v1\Application\AuthController::class, 'logout'])->middleware(['auth:api']);
    });
});


Route::prefix('v1/panel')->group(function () {
    Route::apiResource("/users", UserController::class);
});
