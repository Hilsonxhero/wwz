<?php

namespace Modules\Cart\Transformers\App;

use Illuminate\Http\Resources\Json\JsonResource;

class CartResource extends JsonResource
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
            'id' => $this->id,
            'items_count' => $this->items_count,
            'payable_price' => $this->payable_price,
            'rrp_price' => $this->rrp_price,
            'items_discount' => $this->items_discount,
            'total_discount' => $this->items_discount,
            // 'cart_items' =>  CartItemsResource::collection($this->items),
            'cart_items' =>  $this->items,
            'shipment_cost' => $this->shipment_cost,
            'shipment_discount' => $this->shipment_discount,
        ];
    }
}
