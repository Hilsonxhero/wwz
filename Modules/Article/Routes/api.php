<?php

namespace Modules\Article;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Modules\Article\Http\Controllers\v1\Panel\ArticleController;

Route::prefix('v1/application')->group(function () {
    Route::get("articles", [\Modules\Article\Http\Controllers\v1\App\ArticleController::class, 'index']);
    Route::get("articles/{slug}", [\Modules\Article\Http\Controllers\v1\App\ArticleController::class, 'show']);
});

Route::prefix('v1/panel')->group(function () {
    Route::apiResource("/articles", ArticleController::class);
});
