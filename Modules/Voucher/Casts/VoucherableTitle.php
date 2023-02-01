<?php

namespace Modules\Voucher\Casts;

use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use Modules\Category\Entities\Category;
use Modules\Product\Entities\Product;
use Modules\User\Entities\User;

class VoucherableTitle implements CastsAttributes
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
        switch ($model->voucherable_type) {
            case User::class:
                return $model->voucherable->username;
                break;

            case Product::class:
                return $model->voucherable->title_fa;
                break;

            case Category::class:
                return $model->voucherable->title;
                break;
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
