<?php

namespace Modules\Payment\Http\Requests\Panel;

use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Enum;
use Modules\Payment\Entities\Gateway;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Modules\Payment\Enums\GatewayStatus;

class GatewayRequest extends FormRequest
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
                'slug' => ['required'],
                'config' => ['nullable'],
                'type' => ['required'],
                'is_default' => ['required'],
                'status' => ['required', new Enum(GatewayStatus::class)],
            ];
        }

        return [
            'title' => ['required'],
            'slug' => ['required'],
            'config' => ['nullable'],
            'type' => ['required'],
            'is_default' => ['required'],
            'status' => ['required', new Enum(GatewayStatus::class)],
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
