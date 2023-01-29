<?php

namespace Modules\Product\Http\Requests\Panel;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class RecommendationProductRequest extends FormRequest
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
                'recommendation_id' => ['required', 'exists:recommendations,id'],
                'product_id' => ['required', 'exists:products,id'],
            ];
        }

        return [
            'recommendation_id' => ['required', 'exists:recommendations,id'],
            'product_id' => ['required', 'exists:products,id'],
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
