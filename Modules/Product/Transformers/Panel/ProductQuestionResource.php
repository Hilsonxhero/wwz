<?php

namespace Modules\Product\Transformers\Panel;

use Illuminate\Http\Resources\Json\JsonResource;

class ProductQuestionResource extends JsonResource
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
            'content' => $this->content,
            'product' => $this->product->title_fa,
            'status' => $this->status,
            'question' => $this->question,
            'replies' => ProductQuestionResource::collection($this->replies),
            'like' => $this->like,
            'dislike' => $this->dislike,
            'report' => $this->report,
            'is_buyer' => $this->is_buyer,
            'is_anonymous' => $this->is_anonymous,
            'username' => $this->user->username,
            'created_at' => formatGregorian($this->created_at, '%A, %d %B'),
        ];
    }
}
