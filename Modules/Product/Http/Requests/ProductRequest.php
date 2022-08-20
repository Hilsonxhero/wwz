<?php

namespace Modules\Product\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\Rule;
use Modules\Product\Entities\Product;

class ProductRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        if (request()->getMethod() == "PUT"){
            return [
                'title_fa' => ['required', 'min:4'],
                'title_en' => ['required', 'min:4'],
                'review' => ['required', 'min:4'],
                'image' => ['nullable'],
                'category_id' => ['required', 'exists:categories,id'],
                'brand_id' => ['required', 'exists:brands,id'],
                'status' => ['required', Rule::in(Product::$statuses)],
            ];
        }

        return [
            'title_fa' => ['required', 'min:4'],
            'title_en' => ['required', 'min:4'],
            'review' => ['required', 'min:4'],
            'image' => ['required'],
            'category_id' => ['required', 'exists:categories,id'],
            'brand_id' => ['required', 'exists:brands,id'],
            'status' => ['required', Rule::in(Product::$statuses)],
        ];
    }

    public function failedValidation( $validator)
    {
        throw new HttpResponseException(response()->json([
            'success'   => false,
            'message'   => 'Validation errors',
            'data'      => $validator->errors()
        ],422));
    }
}
