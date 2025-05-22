<?php

namespace App\Http\Requests\general\coupon;

use Illuminate\Foundation\Http\FormRequest;

class CreateCouponRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'code' => ['required', 'unique:coupons,code', 'string', 'max:255'],
            'discount' => ['required', 'numeric', 'max:255'],
            'discount_type' => ['required', 'string', 'max:255'],
            'active' => ['sometimes', 'boolean'],
        ];
    }
}
