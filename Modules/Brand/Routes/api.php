<?php

use Illuminate\Http\Request;
use Modules\Brand\Http\Controllers\v1\Panel\BrandController;

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

Route::prefix('v1/panel')->group(function () {
    Route::apiResource("/brands", BrandController::class);
});
