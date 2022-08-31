<?php

namespace Modules\Page\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class PageRequest extends FormRequest
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
                'title_en' => ['required'],
                'content' => ['nullable'],
            ];
        }

        return [
            'title' => ['required'],
            'title_en' => ['required'],
            'content' => ['nullable'],
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
