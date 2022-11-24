<?php

namespace Modules\Shipment\Transformers\App;

use Illuminate\Http\Resources\Json\JsonResource;

class ShipmentIntervalResource extends JsonResource
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
            'date' => formatGregorian($this->shipment_date->date, '%A, %d %B'),
            'start_at' => $this->start_at,
            'end_at' => $this->end_at,
        ];
    }
}
