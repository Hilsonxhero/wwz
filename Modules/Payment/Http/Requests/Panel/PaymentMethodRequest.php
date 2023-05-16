<?php

namespace Modules\Payment\Http\Requests\Panel;

use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Enum;
use Illuminate\Foundation\Http\FormRequest;
use Modules\Payment\Entities\PaymentMethod;
use Illuminate\Http\Exceptions\HttpResponseException;
use Modules\Payment\Enums\PaymentMethodStatus;

class PaymentMethodRequest extends FormRequest
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
                'description' => ['required'],
                'type' => ['required'],
                'is_default' => ['required'],
                'status' => ['required', new Enum(PaymentMethodStatus::class)],
            ];
        }

        return [
            'title' => ['required'],
            'slug' => ['required'],
            'description' => ['required'],
            'type' => ['required'],
            'is_default' => ['required'],
            'status' => ['required', new Enum(PaymentMethodStatus::class)],
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
