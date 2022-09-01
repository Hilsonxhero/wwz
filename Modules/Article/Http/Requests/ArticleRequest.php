<?php

namespace Modules\Article\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class ArticleRequest extends FormRequest
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
                'category_id' => ['required'],
                'image' => ['nullable'],
                'content' => ['required'],
                'description' => ['required'],
                'status' => ['required'],
            ];
        }

        return [
            'title' => ['required'],
            'category_id' => ['required'],
            'image' => ['required'],
            'content' => ['required'],
            'description' => ['required'],
            'status' => ['required'],
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
