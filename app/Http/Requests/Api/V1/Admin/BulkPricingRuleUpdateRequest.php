<?php

namespace App\Http\Requests\Api\V1\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class BulkPricingRuleUpdateRequest extends FormRequest
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
        $ruleId = $this->route('bulk_pricing_rule')?->id;

        return [
            'product_variant_id' => ['required', 'integer', 'exists:product_variants,id'],
            'min_qty' => [
                'required',
                'integer',
                'min:1',
                Rule::unique('bulk_pricing_rules', 'min_qty')
                    ->where(fn ($query) => $query->where('product_variant_id', $this->input('product_variant_id')))
                    ->ignore($ruleId),
            ],
            'price_per_unit' => ['required', 'numeric', 'min:0'],
        ];
    }
}
