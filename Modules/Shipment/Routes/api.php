<?php

use Illuminate\Http\Request;
use Modules\Cart\Http\Controllers\v1\App\ShippingController;
use Modules\Shipment\Http\Controllers\v1\App\ShipmentAddressController;
use Modules\Shipment\Http\Controllers\v1\Panel\DeliveryTypeController;
use Modules\Shipment\Http\Controllers\v1\Panel\ShipmentController;
use Modules\Shipment\Http\Controllers\v1\Panel\ShipmentDateController;
use Modules\Shipment\Http\Controllers\v1\Panel\ShipmentIntervalController;
use Modules\Shipment\Http\Controllers\v1\Panel\ShipmentTypeController;


Route::prefix('v1/application')->group(function () {
    Route::prefix('shipment')->group(function () {
        Route::post("address/change", [ShipmentAddressController::class, 'change'])->middleware('auth:api');
    });
});
Route::prefix('v1/panel')->group(function () {

    Route::prefix('shipment')->group(function () {
        Route::apiResource("cities/{id}/dates", ShipmentDateController::class);
        Route::apiResource("dates/{id}/intervals", ShipmentIntervalController::class);
        Route::apiResource("types", ShipmentTypeController::class);
    });

    Route::apiResource("shipments", ShipmentController::class);
    Route::apiResource("delivery/types", DeliveryTypeController::class);
    Route::get("delivery/types/select/items", [DeliveryTypeController::class, 'select']);
});
