<?php

use Illuminate\Http\Request;
use Modules\Shipment\Http\Controllers\v1\Panel\ShipmentController;

Route::prefix('v1/panel')->group(function () {
    Route::apiResource("/shipments", ShipmentController::class);
});
