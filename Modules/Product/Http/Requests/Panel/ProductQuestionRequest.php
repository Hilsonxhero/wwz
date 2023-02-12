<?php

namespace Modules\Product\Http\Requests\Panel;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class ProductQuestionRequest extends FormRequest
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
                'content' => ['required'],
                'product_question_id' => ['nullable', 'exists:product_questions,id'],
            ];
        }

        return [
            'content' => ['required'],
            'product_question_id' => ['nullable', 'exists:product_questions,id'],
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
