<?php

namespace Modules\Comment\Transformers\App;

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
            // 'category_id' => $this->category_id,
            'title' => $this->title,
            // 'category' => $this->category,
            // 'status' => $this->status,
            "average" => round($this->avg_value,)
            // "comments_count" => $this->comments_count,
        ];
    }
}
