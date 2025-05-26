<?php

namespace App\Http\Requests\Order;

use Illuminate\Foundation\Http\FormRequest;

class CreateOrderRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'coupons' => ['sometimes', 'array'],
            'address.zip_code' => ['required', 'string'],
            'address.street' => ['required', 'string'],
            'address.number' => ['required', 'string'],
            'address.complement' => ['sometimes'],
            'address.neighborhood' => ['required', 'string'],
            'address.city' => ['required', 'string'],
            'address.state' => ['required', 'string'],
        ];
    }
}
