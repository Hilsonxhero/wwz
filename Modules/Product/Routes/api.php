<?php

use Illuminate\Http\Request;
use Modules\Product\Http\Controllers\v1\Panel\FeatureController;
use Modules\Product\Http\Controllers\v1\Panel\FeatureValueController;
use Modules\Product\Http\Controllers\v1\Panel\ProductController;
use Modules\Product\Http\Controllers\v1\Panel\ProductFeatureController;
use Modules\Product\Http\Controllers\v1\Panel\ProductVariantController;
use Modules\Product\Http\Controllers\v1\Panel\VariantController;
use Modules\Product\Http\Controllers\v1\Panel\VariantGroupController;

Route::prefix('v1/panel')->group(function () {
    //products
    Route::apiResource("/products", ProductController::class);
    // product features
    Route::apiResource("/products/{id}/features", ProductFeatureController::class);
    // product variants
    Route::apiResource("/products/{id}/variants", ProductVariantController::class);
    //variants
    Route::apiResource("/variants", VariantController::class);
    Route::apiResource("/variant/groups", VariantGroupController::class);
    Route::get("/variant/groups/list/active", [VariantGroupController::class, 'list']);
    Route::get("/variant/groups/{id}/values", [VariantGroupController::class, 'values']);
    //features
    Route::apiResource("/features", FeatureController::class);
    Route::get("/features/{id}/values", [FeatureController::class, 'values']);
    Route::get("/features/select/{id?}", [FeatureController::class, 'select']);
    Route::apiResource("/feature/values", FeatureValueController::class);
});
