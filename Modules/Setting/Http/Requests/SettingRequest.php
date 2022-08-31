<?php

namespace Modules\Setting\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class SettingRequest extends FormRequest
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
                'site_name' => ['required'],
                'site_description' => ['required'],
                'email' => ['required'],
                'phone' => ['required'],
                'address' => ['required'],
                'logo' => ['nullable'],
                'copyright' => ['required'],
            ];
        }

        return [
            'site_name' => ['required'],
            'site_description' => ['required'],
            'email' => ['required'],
            'phone' => ['required'],
            'address' => ['required'],
            'logo' => ['nullable'],
            'copyright' => ['required'],
        ];

        return [];
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
