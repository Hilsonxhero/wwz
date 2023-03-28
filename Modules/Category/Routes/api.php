<?php

use Illuminate\Http\Request;

use Modules\Category\Http\Controllers\v1\Panel\CategoryController;


Route::prefix('v1/panel')->middleware(['auth.panel', 'auth:api'])->group(function () {
    Route::apiResource("/categories", CategoryController::class);
    Route::get("/category/select", [CategoryController::class, 'select']);
    Route::apiResource("/category/banners", \Modules\Category\Http\Controllers\v1\Panel\CategoryBannerController::class);
});

Route::prefix('v1/application')->group(function () {
    Route::get("/categories", [\Modules\Category\Http\Controllers\v1\App\CategoryController::class, 'index']);
});
