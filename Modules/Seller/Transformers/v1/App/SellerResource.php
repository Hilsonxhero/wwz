<?php

namespace Modules\Seller\Transformers\v1\App;

use Illuminate\Http\Resources\Json\JsonResource;

class SellerResource extends JsonResource
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
            'title' => $this->title,
            'code' => $this->code,
            'registration_date' => formatGregorian($this->created_at, '%A, %d %B'),
        ];
    }
}
