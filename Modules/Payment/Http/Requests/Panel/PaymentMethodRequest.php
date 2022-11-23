<?php

namespace Modules\Payment\Http\Requests\Panel;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Modules\Payment\Entities\PaymentMethod;

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
                'status' => ['required', Rule::in(PaymentMethod::$statuses)],
            ];
        }

        return [
            'title' => ['required'],
            'slug' => ['required'],
            'description' => ['required'],
            'type' => ['required'],
            'is_default' => ['required'],
            'status' => ['required', Rule::in(PaymentMethod::$statuses)],
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
