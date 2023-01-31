<?php

namespace Modules\Voucher\Http\Requests\Panel;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class VoucherRequest extends FormRequest
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
                'code' => ['required'],
                'value' => ['required'],
                'minimum_spend' => ['required'],
                'maximum_spend' => ['required'],
                'usage_limit_per_voucher' => ['required'],
                'usage_limit_per_customer' => ['required'],
                'is_percent' => ['nullable'],
                'is_active' => ['nullable'],
                'start_date' => ['required'],
                'end_date' => ['required'],
            ];
        }

        return [
            'code' => ['required'],
            'value' => ['required'],
            'minimum_spend' => ['required'],
            'maximum_spend' => ['required'],
            'usage_limit_per_voucher' => ['required'],
            'usage_limit_per_customer' => ['required'],
            'is_percent' => ['nullable'],
            'is_active' => ['nullable'],
            'start_date' => ['required'],
            'end_date' => ['required'],
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
