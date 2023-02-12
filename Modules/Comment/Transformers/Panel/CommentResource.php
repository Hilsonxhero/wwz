<?php

namespace Modules\Comment\Transformers\Panel;

use Illuminate\Http\Resources\Json\JsonResource;

class CommentResource extends JsonResource
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
            'content' => $this->body,
            'status' => $this->status,
            'like' => $this->like,
            'dislike' => $this->dislike,
            'report' => $this->report,
            'is_buyer' => $this->is_buyer,
            'is_recommendation' => $this->is_recommendation,
            'is_anonymous' => $this->is_anonymous,
            'advantages' => $this->advantages,
            'disadvantages' => $this->disadvantages,
            'username' => $this->user->username,
            'commentable_title' => $this->commentable_title,
            'created_at' => formatGregorian($this->created_at, '%A, %d %B'),

        ];
    }
}
