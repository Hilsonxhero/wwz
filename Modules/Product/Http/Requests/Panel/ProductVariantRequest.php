<?php

namespace Modules\Product\Http\Requests\Panel;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class ProductVariantRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        if (request()->getMethod() == "PUT") {
            return [
                'warranty_id' => ['required'],
                'shipment_id' => ['required'],
                'price' => ['required'],
                'discount' => ['required'],
                'stock' => ['required'],
                'weight' => ['required'],
                'order_limit' => ['required'],
                'discount_expire_at' => ['nullable'],
            ];
        }

        return [
            'warranty_id' => ['required'],
            'shipment_id' => ['required'],
            'price' => ['required'],
            'discount' => ['required'],
            'stock' => ['required'],
            'weight' => ['required'],
            'order_limit' => ['required'],
            'discount_expire_at' => ['nullable'],
        ];
    }
    public function failedValidation($validator)
    {
        throw new HttpResponseException(response()->json([
            'success'   => false,
            'message'   => 'Validation errors',
            'data'      => $validator->errors()
        ], 422));
    }
}
