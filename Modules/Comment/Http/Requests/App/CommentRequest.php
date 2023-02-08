<?php

namespace Modules\Comment\Http\Requests\App;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class CommentRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        if (request()->getMethod() == "PUT") {
        }

        return [
            'title' => ['required'],
            'content' => ['required'],
            'comment_id' => ['nullable'],
            'is_anonymous' => ['required'],
            'is_recommendation' => ['required'],
            'scores' => ['nullable', 'array'],
            'advantages' => ['nullable', 'array'],
            'disadvantages' => ['nullable', 'array']
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
