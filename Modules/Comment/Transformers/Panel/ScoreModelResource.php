<?php

namespace Modules\Comment\Transformers\Panel;

use Illuminate\Http\Resources\Json\JsonResource;

class ScoreModelResource extends JsonResource
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
            'category' => $this->category,
            'status' => $this->status,
        ];
    }
}
