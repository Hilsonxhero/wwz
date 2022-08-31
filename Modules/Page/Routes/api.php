<?php

use Illuminate\Http\Request;
use Modules\Page\Http\Controllers\v1\Panel\PageController;



Route::prefix('v1/panel')->group(function () {
    Route::apiResource('pages', PageController::class);
});
