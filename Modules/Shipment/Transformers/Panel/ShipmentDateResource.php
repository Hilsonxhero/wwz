<?php

namespace Modules\Shipment\Transformers\Panel;

use Illuminate\Http\Resources\Json\JsonResource;

class ShipmentDateResource extends JsonResource
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
            // 'type' => new ShipmentResource($this->shipment),
            'type' => $this->shipment->shipment,
            'date' => formatGregorian($this->date, 'Y/m/d'),
            'day' => formatGregorian($this->date, 'd'),
            'weekday_name' => formatGregorian($this->date, '%A'),
            'is_holiday' => $this->is_holiday,
            'has_time_scope' => $this->has_time_scope,
            'intervals' =>  ShipmentIntervalResource::collection($this->intervals)
        ];
    }
}
