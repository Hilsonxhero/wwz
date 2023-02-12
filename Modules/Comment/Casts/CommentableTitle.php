<?php

namespace Modules\Comment\Casts;

use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use Modules\Category\Entities\Category;
use Modules\Product\Entities\Product;
use Modules\User\Entities\User;

class CommentableTitle implements CastsAttributes
{
    /**
     * Cast the given value.
     *
     * @param  \Illuminate\Database\Eloquent\Model  $model
     * @param  string  $key
     * @param  mixed  $value
     * @param  array  $attributes
     * @return mixed
     */
    public function get($model, string $key, $value, array $attributes)
    {
        switch ($model->commentable_type) {
            case Product::class:
                return $model->commentable->title_fa;
                break;

            default:
                return "default";
        };
    }

    /**
     * Prepare the given value for storage.
     *
     * @param  \Illuminate\Database\Eloquent\Model  $model
     * @param  string  $key
     * @param  mixed  $value
     * @param  array  $attributes
     * @return mixed
     */
    public function set($model, string $key, $value, array $attributes)
    {
        return $value;
    }
}
