<?php

namespace App\Http\Requests\General\Coupon;

use Illuminate\Foundation\Http\FormRequest;

class CreateCouponRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'code' => ['required', 'unique:coupons,code', 'string', 'max:255'],
            'discount' => ['required', 'numeric', 'max:255'],
            'discount_type' => ['required', 'string', 'max:255'],
            'active' => ['required', 'string'],
            'min_subtotal_to_apply' => ['required', 'numeric'],
            'expires_at' => ['required', 'date', 'after:today']
        ];
    }

    public function messages(): array
    {
        return [
            'code.required' => 'O campo código é obrigatório.',
            'code.unique' => 'Esse código de cupom já está em uso.',
            'code.string' => 'O campo código deve ser uma sequência de caracteres.',
            'code.max' => 'O campo código não pode ter mais que 255 caracteres.',

            'discount.required' => 'O campo desconto é obrigatório.',
            'discount.numeric' => 'O campo desconto deve ser um valor numérico.',
            'discount.max' => 'O campo desconto não pode ser maior que 255.',

            'discount_type.required' => 'O campo tipo de desconto é obrigatório.',
            'discount_type.string' => 'O campo tipo de desconto deve ser uma sequência de caracteres.',
            'discount_type.max' => 'O campo tipo de desconto não pode ter mais que 255 caracteres.',

            'active.required' => 'O campo ativo é obrigatório.',
            'active.string' => 'O campo ativo deve ser uma sequência de caracteres.',

            'min_subtotal_to_apply.required' => 'O campo subtotal mínimo para aplicar é obrigatório.',
            'min_subtotal_to_apply.numeric' => 'O campo subtotal mínimo para aplicar deve ser um valor numérico.',

            'expires_at.required' => 'O campo data de expiração é obrigatório.',
            'expires_at.date' => 'O campo data de expiração deve ser uma data válida.',
            'expires_at.after' => 'O campo data de expiração deve ser uma data posterior à hoje.',
        ];
    }
}
