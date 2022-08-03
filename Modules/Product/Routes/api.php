<?php

use Illuminate\Http\Request;
use Modules\Product\Http\Controllers\v1\Panel\FeatureController;
use Modules\Product\Http\Controllers\v1\Panel\FeatureValueController;
use Modules\Product\Http\Controllers\v1\Panel\ProductController;
use Modules\Product\Http\Controllers\v1\Panel\VariantController;
use Modules\Product\Http\Controllers\v1\Panel\VariantGroupController;

Route::prefix('v1/panel')->group(function () {
    Route::resource("/features", FeatureController::class);
    Route::resource("/products", ProductController::class);
    Route::resource("/variants", VariantController::class);
    Route::resource("/variant/groups", VariantGroupController::class);
    Route::get("/features/{id}/values", [FeatureController::class, 'values']);
    Route::get("/features/select/{id?}", [FeatureController::class, 'select']);
    Route::resource("/feature/values", FeatureValueController::class);
});
