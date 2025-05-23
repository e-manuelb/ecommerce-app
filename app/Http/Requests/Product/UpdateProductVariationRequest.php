<?php

namespace App\Http\Requests\Product;

use App\Models\ProductVariation;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateProductVariationRequest extends FormRequest
{
    public function rules(): array
    {
        /** @var ProductVariation $productVariation */
        $productVariation = ProductVariation::query()->where('uuid', $this->route('uuid'))->first();

        return [
            'name' => ['required', 'string', 'max:255'],
            'product_id' => ['required', 'integer', 'exists:products,id'],
            'sku' => ['required', 'string', 'max:255', Rule::unique('product_variations', 'sku')->ignore($productVariation->uuid, 'uuid')],
            'description' => ['required', 'string', 'max:255'],
            'price' => ['required', 'numeric'],
            'quantity' => ['required', 'numeric']
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'O campo nome é obrigatório.',
            'name.string' => 'O campo nome deve ser uma sequência de caracteres.',
            'name.max' => 'O campo nome não pode ter mais que 255 caracteres.',

            'product_id.required' => 'O campo ID do Produto é obrigatório',
            'product_id.integer' => 'O campo ID do Produto deve ser um número inteiro.',

            'sku.required' => 'O campo SKU é obrigatório.',
            'sku.string' => 'O campo SKU deve ser uma sequência de caracteres.',
            'sku.max' => 'O campo SKU não pode ter mais que 255 caracteres.',
            'sku.unique' => 'Este SKU já está em uso.',

            'description.required' => 'O campo descrição é obrigatório.',
            'description.string' => 'O campo descrição deve ser uma sequência de caracteres.',
            'description.max' => 'O campo descrição não pode ter mais que 255 caracteres.',

            'price.required' => 'O campo preço é obrigatório.',
            'price.numeric' => 'O campo preço deve ser um número.',

            'quantity.required' => 'O campo quantidade é obrigatório.',
            'quantity.numeric' => 'O campo quantidade deve ser um número.',
        ];
    }
}
