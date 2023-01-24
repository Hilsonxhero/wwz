<?php

namespace Modules\Order\Transformers\Panel;

use Illuminate\Http\Resources\Json\JsonResource;

class OrderShippingResource extends JsonResource
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
            'order_items_cost' => $this->total_price,
            'order_items' => OrderShippingItemResource::collection($this->items),
        ];
    }
}
