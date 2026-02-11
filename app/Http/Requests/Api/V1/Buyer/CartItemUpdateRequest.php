<?php

namespace App\Http\Requests\Api\V1\Buyer;

use Illuminate\Foundation\Http\FormRequest;

class CartItemUpdateRequest extends FormRequest
{
    public function authorize(): bool
    {
        $user = $this->user();
        $item = $this->route('item');

        if (!$user || $user->role !== 'buyer' || !$item) {
            return false;
        }

        return (int) $item->cart?->user_id === (int) $user->id;
    }

    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'qty' => ['required', 'integer', 'min:1'],
        ];
    }
}
