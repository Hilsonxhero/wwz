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
            // 'comments' => parent::toArray($request),
            'links' => $this->links,

        ];
    }
}
