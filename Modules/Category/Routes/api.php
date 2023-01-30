<?php

use Illuminate\Http\Request;
use Modules\Category\Http\Controllers\v1\Panel\CategoryController;
use Modules\Category\Http\Controllers\v1\Panel\CategorySlideController;

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

// Route::middleware('auth:api')->get('/category', function (Request $request) {
//     return $request->user();
// });

Route::prefix('v1/panel')->group(function () {
    Route::apiResource("/categories", CategoryController::class);
    Route::get("/category/select", [CategoryController::class, 'select']);
    Route::apiResource("/category/slides", CategorySlideController::class);
    Route::apiResource("/category/banners", \Modules\Category\Http\Controllers\v1\Panel\CategoryBannerController::class);
});
