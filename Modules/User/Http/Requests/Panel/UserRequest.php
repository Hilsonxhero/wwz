<?php

namespace Modules\User\Http\Requests\Panel;

use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Enum;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Modules\User\Enums\UserStatus;

class UserRequest extends FormRequest
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
                'username' => ['required', 'min:3'],
                'email' => ['nullable', 'email', 'min:3', Rule::unique('users', 'email')->ignore(request()->id)],
                'phone' => ['required', 'min:3', Rule::unique('users', 'phone')->ignore(request()->id)],
                'national_identity_number' => ['nullable'],
                'password' => ['nullable', 'confirmed'],
                'status' => ['required', new Enum(UserStatus::class)],
            ];
        }

        return [
            'username' => ['required', 'min:3'],
            'email' => ['required', 'email', 'min:3', 'unique:users,email'],
            'phone' => ['required', 'min:3', 'unique:users,phone'],
            'national_identity_number' => ['required'],
            'password' => ['required', 'confirmed'],
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
