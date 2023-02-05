<?php

namespace Modules\Voucher\Http\Requests\Panel;

use Illuminate\Validation\Rule;
use Modules\User\Entities\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class VoucherableRequest extends FormRequest
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
                'user' => ['nullable', 'exists:users,id'],
                'category' => ['nullable', 'exists:categories,id'],
                'product' => ['nullable', 'exists:products,id'],
            ];
        }

        return [
            'user' => ['nullable', 'exists:users,id', Rule::unique('voucherables', 'voucherable_id')->where(function ($query) {
                return $query->where('voucherable_id', $this->user)
                    ->where('voucher_id', $this->voucher)->where('voucherable_type', "Modules\User\Entities\User");
            }),],
            'category' => ['nullable', 'exists:categories,id', Rule::unique('voucherables', 'voucherable_id')->where(function ($query) {
                return $query->where('voucherable_id', $this->category)
                    ->where('voucher_id', $this->voucher)->where('voucherable_type', "Modules\Category\Entities\Category");
            }),],
            'product' => ['nullable', 'exists:products,id', Rule::unique('voucherables', 'voucherable_id')->where(function ($query) {
                return $query->where('voucherable_id', $this->product)
                    ->where('voucher_id', $this->voucher)->where('voucherable_type', "Modules\Product\Entities\Product");
            }),],
            'voucher' => ['required', 'exists:vouchers,id'],
        ];
    }


    /**
     * Prepare the data for validation.
     *
     * @return void
     */
    // protected function prepareForValidation()
    // {
    //     $this->merge([
    //         'voucherable_user' => "Modules\User\Entities\User",
    //     ]);
    // }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'user.unique' => 'کاربر مورد نظر قبلا به این کد تخفیف اضافه شده است',
            'product.unique' => 'محصول مورد نظر قبلا به این کد تخفیف اضافه شده است',
            'category.unique' => 'دسته بندی مورد نظر قبلا به این کد تخفیف اضافه شده است',
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
