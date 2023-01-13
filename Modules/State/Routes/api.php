<?php

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Route;
use Modules\State\Http\Controllers\v1\App\CityController;
use Modules\State\Http\Controllers\v1\Panel\StateController;

Route::prefix('v1/application')->group(function () {
    Route::get("/states", [\Modules\State\Http\Controllers\v1\App\StateController::class, 'index']);
    Route::get("/states/{id}/cities", [\Modules\State\Http\Controllers\v1\App\StateController::class, 'cities']);
    // Route::get("/cities", Modules\State\Http\Controllers\v1\App\CityController::class);
});


Route::prefix('v1/panel')->group(function () {
    Route::apiResource("/states", StateController::class);
    Route::get("/states/{id}/cities", [StateController::class, 'cities']);
    Route::apiResource("/cities", CityController::class);
});