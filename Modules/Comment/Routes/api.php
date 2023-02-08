<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Modules\Comment\Http\Controllers\v1\Panel\CommentController;
use Modules\Comment\Http\Controllers\v1\Panel\ScoreModelController;

Route::prefix('v1/application')->group(function () {
    Route::post("/comments/product/{id}", [\Modules\Comment\Http\Controllers\v1\App\CommentController::class, 'store']);
});

Route::prefix('v1/panel')->group(function () {
    // score models
    Route::apiResource("score/models", ScoreModelController::class);

    // comments
    Route::apiResource("comments", CommentController::class);
});
