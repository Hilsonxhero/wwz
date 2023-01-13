<?php

use Illuminate\Http\Request;
use Modules\Warranty\Http\Controllers\v1\Panel\WarrantyController;

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
    Route::apiResource("/warranties", WarrantyController::class);
});