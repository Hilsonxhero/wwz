<?php

namespace Modules\Setting\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class SettingBannerRequest extends FormRequest
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
                'banner' => ['nullable'],
                'mobile_banner' => ['nullable'],
                'url' => ['required', 'url'],
                'type' => ['required'],
                'status' => ['required'],
            ];
        }

        return [
            'title' => ['required'],
            'banner' => ['required'],
            'mobile_banner' => ['required'],
            'url' => ['required', 'url'],
            'type' => ['required'],
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
