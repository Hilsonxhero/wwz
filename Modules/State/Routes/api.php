<?php

use Illuminate\Http\Request;
use Modules\State\Http\Controllers\v1\Panel\CityController;
use Modules\State\Http\Controllers\v1\Panel\StateController;

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
    Route::apiResource("/states", StateController::class);
    Route::get("/states/{id}/cities", [StateController::class, 'cities']);
    Route::apiResource("/cities", CityController::class);
});
