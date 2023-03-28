<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Modules\Comment\Http\Controllers\v1\Panel\CommentController;
use Modules\Comment\Http\Controllers\v1\Panel\ScoreModelController;

Route::prefix('v1/application')->group(function () {
    Route::post("/comments/product/{id}", [\Modules\Comment\Http\Controllers\v1\App\CommentController::class, 'store']);
    Route::get("/comments/product/{id}", [\Modules\Comment\Http\Controllers\v1\App\CommentController::class, 'index']);
});

Route::prefix('v1/panel')->middleware(['auth.panel', 'auth:api'])->group(function () {
    // score models
    Route::apiResource("score/models", ScoreModelController::class);

    // comments
    Route::apiResource("comments", CommentController::class);
});
