<?php

namespace Modules\Product\Http\Requests\App;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\Rule;
use Modules\Product\Enums\ProductAnnouncementType;

class ProductAnnouncementRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'type' => ['required', Rule::in([ProductAnnouncementType::Availability->value, ProductAnnouncementType::Promotion->value])],
            // 'product_variant_id' => ['required', 'exists:product_variants,id'],
            'via_sms' => ['required'],
            'via_email' => ['required'],
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
