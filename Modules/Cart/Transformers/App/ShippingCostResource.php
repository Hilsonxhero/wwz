<?php

namespace Modules\Cart\Transformers\App;

use Modules\Cart\Facades\Cart;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Modules\Shipment\Transformers\ShipmentResource;
use Modules\User\Transformers\App\AddressResource;
use Modules\User\Transformers\App\UserAddressResource;
use Modules\User\Transformers\UserResource;

class ShippingCostResource extends JsonResource
{

    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'shipment' => new ShipmentResource($this),
            'cost' => round($this->shipping_cost)
        ];
    }
}
