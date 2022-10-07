<?php

use Illuminate\Http\Request;
use Modules\Shipment\Http\Controllers\v1\Panel\ShipmentController;
use Modules\Shipment\Http\Controllers\v1\Panel\ShipmentDateController;
use Modules\Shipment\Http\Controllers\v1\Panel\ShipmentTypeController;

Route::prefix('v1/panel')->group(function () {
    // Route::get('shipment', [ShipmentController::class, 'init']);
    Route::apiResource("/shipments", ShipmentController::class);
    Route::apiResource("/shipment/types", ShipmentTypeController::class);
    Route::apiResource("/shipment/types/{id}/dates", ShipmentDateController::class);
});
