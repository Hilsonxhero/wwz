<?php
namespace Modules\Article;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Modules\Article\Http\Controllers\v1\Panel\ArticleController;

Route::prefix('v1/panel')->group(function () {
    Route::apiResource("/articles", ArticleController::class);
});