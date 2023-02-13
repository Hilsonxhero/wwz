<?php

namespace Modules\Product\Http\Requests\Panel;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class ProductGalleryRequest extends FormRequest
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
                'title' => ['required'],
                'product_id' => ['required', 'exists:products,id'],
                'media' => ['nullable'],
            ];
        }

        return [
            'title' => ['required'],
            'product_id' => ['required', 'exists:products,id'],
            'media' => ['required'],
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
