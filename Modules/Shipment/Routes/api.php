<?php

use Illuminate\Http\Request;
use Modules\Shipment\Http\Controllers\v1\Panel\DeliveryTypeController;
use Modules\Shipment\Http\Controllers\v1\Panel\ShipmentController;
use Modules\Shipment\Http\Controllers\v1\Panel\ShipmentDateController;
use Modules\Shipment\Http\Controllers\v1\Panel\ShipmentTypeController;

Route::prefix('v1/panel')->group(function () {
    // Route::get('shipment', [ShipmentController::class, 'init']);
    Route::apiResource("/shipments", ShipmentController::class);
    Route::apiResource("/shipment/types", ShipmentTypeController::class);
    Route::apiResource("/delivery/types", DeliveryTypeController::class);
    Route::apiResource("/shipment/cities/{id}/dates", ShipmentDateController::class);
});
