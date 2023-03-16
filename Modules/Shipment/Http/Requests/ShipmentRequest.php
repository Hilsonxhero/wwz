<?php

namespace Modules\Shipment\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class ShipmentRequest extends FormRequest
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
                'icon' => ['nullable'],
                'shipping_cost' => ['required'],
                'description' => ['required'],
                'delivery' => ['required'],
                'delivery_date' => ['required'],
            ];
        }

        return [
            'title' => ['required'],
            'icon' => ['nullable'],
            'shipping_cost' => ['required'],
            'description' => ['required'],
            'delivery' => ['required'],
            'delivery_date' => ['required'],
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
