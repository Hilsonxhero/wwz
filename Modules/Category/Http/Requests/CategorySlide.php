<?php

namespace Modules\Category\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\Validator;


class CategorySlide extends FormRequest
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
                'title' => ['required'],
                'banner' => ['nullable'],
                'url' => ['required'],
                'type' => ['required'],
                'status' => ['required'],
            ];
        }

        return [
            'title' => ['required'],
            'banner' => ['required'],
            'url' => ['required'],
            'type' => ['required'],
            'status' => ['required'],
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
