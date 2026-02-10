<?php

namespace App\Http\Requests\Api\V1\Buyer;

use Illuminate\Foundation\Http\FormRequest;

class CartItemStoreRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'product_variant_id' => ['required', 'integer', 'exists:product_variants,id'],
            'bundle_id' => ['nullable', 'integer', 'exists:product_bundles,id'],
            'qty' => ['required', 'integer', 'min:1'],
        ];
    }
}
