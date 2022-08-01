<?php

use Illuminate\Http\Request;
use Modules\Product\Http\Controllers\Panel\FeatureController;
use Modules\Product\Http\Controllers\Panel\FeatureValueController;

Route::prefix('v1/panel')->group(function () {
    Route::resource("/features", FeatureController::class);
    Route::get("/features/{id}/values", [FeatureController::class, 'values']);
    Route::resource("/feature/values", FeatureValueController::class);
});
