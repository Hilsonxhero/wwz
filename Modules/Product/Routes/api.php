<?php

use Illuminate\Http\Request;
use Modules\Product\Http\Controllers\v1\Panel\FeatureController;
use Modules\Product\Http\Controllers\v1\Panel\FeatureValueController;
use Modules\Product\Http\Controllers\v1\Panel\ProductController;

Route::prefix('v1/panel')->group(function () {
    Route::resource("/features", FeatureController::class);
    Route::resource("/products", ProductController::class);
    Route::get("/features/{id}/values", [FeatureController::class, 'values']);
    Route::resource("/feature/values", FeatureValueController::class);
});
