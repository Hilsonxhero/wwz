<?php

use Illuminate\Http\Request;
use Modules\User\Http\Controllers\v1\Application\PersonalInfoController;
use Modules\User\Http\Controllers\v1\Application\Profile\UserAddressController;
use Modules\User\Http\Controllers\v1\Application\UpdateProfileController;
use Modules\User\Http\Controllers\v1\Panel\UserController;



Route::prefix('v1/application')->group(function () {

    Route::prefix('user')->group(function () {
        Route::get("init", [\Modules\User\Http\Controllers\v1\Application\AuthController::class, 'init']);
        Route::post("authenticate", [\Modules\User\Http\Controllers\v1\Application\AuthController::class, 'authenticate']);
        Route::post("login/otp", [\Modules\User\Http\Controllers\v1\Application\LoginController::class, 'otp']);
        Route::post("logout", [\Modules\User\Http\Controllers\v1\Application\AuthController::class, 'logout'])->middleware(['auth:api']);

        Route::prefix('profile')->middleware(['auth:api'])->group(function () {

            Route::prefix('update')->group(function () {
                Route::post('username', [UpdateProfileController::class, 'username']);
                Route::post('email', [UpdateProfileController::class, 'email']);
                Route::post('password', [UpdateProfileController::class, 'password']);
                Route::post('mobile/request', [UpdateProfileController::class, 'mobileRequest']);
                Route::post('mobile/verify', [UpdateProfileController::class, 'mobileVerify']);
            });

            Route::apiResource('addresses', UserAddressController::class);

            Route::get('personal-info', PersonalInfoController::class);
        });
    });
});


Route::prefix('v1/panel')->group(function () {
    Route::apiResource("/users", UserController::class);
});
