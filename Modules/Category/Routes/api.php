<?php

use Illuminate\Http\Request;
use Modules\Category\Http\Controllers\Api\CategoryController;
use Modules\Category\Http\Controllers\CategoryConrollerController;

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



Route::prefix('v1')->group(function () {
    Route::resource("/categories", CategoryController::class);
});
