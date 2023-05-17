<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Modules\Product\Http\Controllers\v1\App\ProductAnnouncementController;
use Modules\Product\Http\Controllers\v1\App\ProductQuestionController;
use Modules\Product\Http\Controllers\v1\App\ProductWishController;
use Modules\Product\Http\Controllers\v1\Panel\FeatureController;
use Modules\Product\Http\Controllers\v1\Panel\FeatureValueController;
use Modules\Product\Http\Controllers\v1\Panel\ProductController;
use Modules\Product\Http\Controllers\v1\Panel\ProductFeatureController;
use Modules\Product\Http\Controllers\v1\Panel\ProductGalleryController;
use Modules\Product\Http\Controllers\v1\Panel\ProductReviewController;
use Modules\Product\Http\Controllers\v1\Panel\ProductVariantController;
use Modules\Product\Http\Controllers\v1\Panel\RecommendationController;
use Modules\Product\Http\Controllers\v1\Panel\RecommendationProductController;
use Modules\Product\Http\Controllers\v1\Panel\VariantController;
use Modules\Product\Http\Controllers\v1\Panel\VariantGroupController;

Route::prefix('v1/application')->group(function () {

    // product page

    Route::get("/product/{id}", [Modules\Product\Http\Controllers\v1\App\ProductController::class, 'show']);

    // product price history

    Route::get("/product/{id}/price/history", [Modules\Product\Http\Controllers\v1\App\PriceHistoryController::class, 'show']);

    // product questions

    Route::post("/questions/product/{id}", [ProductQuestionController::class, 'store'])->middleware('auth:api');
    Route::get("/questions/product/{id}", [ProductQuestionController::class, 'index']);

    // wishes

    Route::post("/wishes/{id}", [ProductWishController::class, 'store'])->middleware('auth:api');
    Route::delete("/wishes/{id}", [ProductWishController::class, 'destroy'])->middleware('auth:api');

    // announcements

    Route::post("/announcements/{id}", [ProductAnnouncementController::class, 'store'])->middleware('auth:api');
    Route::delete("/announcements/{id}", [ProductAnnouncementController::class, 'destroy'])->middleware('auth:api');
});


Route::prefix('v1/panel')->middleware(['auth.panel', 'auth:api'])->group(function () {

    // products

    Route::apiResource("/products", ProductController::class);

    // questions

    Route::apiResource("/questions", \Modules\Product\Http\Controllers\v1\Panel\ProductQuestionController::class);

    // recommendation
    Route::apiResource("/recommendations", RecommendationController::class);

    // recommendations products
    Route::get("/recommendations/{id}/products", [RecommendationController::class, 'products']);

    // recommendations select
    Route::get("/recommendations/select/active", [RecommendationController::class, 'select']);

    // recommendation products
    Route::apiResource("/recommendation/products", RecommendationProductController::class);

    // products
    Route::apiResource("/product/incredibles", \Modules\Product\Http\Controllers\v1\Panel\IncredibleProductController::class);

    // product features
    Route::apiResource("/products/{id}/features", ProductFeatureController::class);

    // product variants
    Route::apiResource("/products/{id}/variants", ProductVariantController::class);

    // product gallery
    Route::apiResource("/products/{id}/gallery", ProductGalleryController::class);

    // product reviews
    Route::apiResource("/products/{id}/reviews", ProductReviewController::class);

    // product select
    Route::get("/product/select", [ProductController::class, 'select']);

    // product combinations
    Route::get("/product/{id}/combinations", [ProductController::class, 'combinations']);

    //variants
    Route::apiResource("/variants", VariantController::class);
    Route::apiResource("/variant/groups", VariantGroupController::class);
    Route::get("/variant/groups/list/active/{id}", [VariantGroupController::class, 'list']);
    Route::get("/variant/groups/{id}/values", [VariantGroupController::class, 'values']);
    //features
    Route::apiResource("/features", FeatureController::class);
    Route::get("/features/{id}/values", [FeatureController::class, 'values']);
    Route::get("/feature/select/{id?}", [FeatureController::class, 'select']);
    Route::apiResource("/feature/values", FeatureValueController::class);
});
