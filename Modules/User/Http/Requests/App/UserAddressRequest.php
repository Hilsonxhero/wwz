<?php

namespace Modules\User\Http\Requests\App;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class UserAddressRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        // if (request()->getMethod() == "PUT") {
        //     return [
        //         'cart_item_id' => ['required'],
        //         'quantity' => ['required'],

        //     ];
        // }

        return [
            'state_id' => ['required'],
            'city_id' => ['required'],
            'address' => ['required'],
            'postal_code' => ['required'],
            'telephone' => ['nullable'],
            'mobile' => ['nullable'],
            'is_default' => ['nullable'],
            'latitude' => ['nullable'],
            'longitude' => ['nullable'],
            'building_number' => ['required'],
            'unit' => ['required'],
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
