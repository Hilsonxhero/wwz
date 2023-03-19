<?php

use Illuminate\Http\Request;
use Modules\Web\Http\Controllers\App\LandingController;
use Modules\Web\Http\Controllers\v1\App\ProductPromotionController;
use Modules\Web\Http\Controllers\v1\Panel\InitController;

Route::prefix('v1/application')->group(function () {

    Route::get('promotion/products', [ProductPromotionController::class, 'index']);
    Route::get('init', [InitController::class, 'index']);
    Route::get('landing', [LandingController::class, 'index']);
    Route::get('recommendation', [\Modules\Web\Http\Controllers\v1\App\RecommendationController::class, 'index']);
    Route::get('search', [\Modules\Web\Http\Controllers\v1\App\SearchController::class, 'search']);
    Route::get('search/products', [\Modules\Web\Http\Controllers\v1\App\SearchController::class, 'products']);
});
