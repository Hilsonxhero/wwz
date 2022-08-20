<?php

use Illuminate\Http\Request;
use Modules\User\Http\Controllers\v1\Panel\UserController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::prefix('v1/application')->group(function () {
    Route::prefix('/user')->group(function () {
        Route::get("/authenticate", [\Modules\User\Http\Controllers\v1\Application\AuthController::class,'authenticate']);
        Route::post("/login/otp", [\Modules\User\Http\Controllers\v1\Application\LoginController::class,'otp']);
    });
});


Route::prefix('v1/panel')->group(function () {
    Route::apiResource("/users", UserController::class);
});
