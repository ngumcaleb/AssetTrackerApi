<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateAssetRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $assetId = $this->route('asset')?->id ?? $this->route('asset');

        return [
            'name' => ['sometimes', 'string', 'max:255'],
            'asset_tag' => [
                'sometimes',
                'string',
                'max:255',
                Rule::unique('assets', 'asset_tag')->ignore($assetId),
            ],
            'serial' => [
                'sometimes',
                'string',
                'max:255',
                Rule::unique('assets', 'serial')->ignore($assetId),
            ],
            'category_id' => ['sometimes', 'exists:categories,id'],
            'status' => ['sometimes', Rule::in(['active', 'archived', 'checked_out', 'discarded'])],
            'brand' => ['nullable', 'string', 'max:255'],
            'model' => ['nullable', 'string', 'max:255'],
            'purchase_date' => ['nullable', 'date'],
            'purchase_price' => ['nullable', 'numeric', 'min:0'],
            'supplier' => ['nullable', 'string', 'max:255'],
            'location' => ['nullable', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'condition' => ['nullable', 'string', 'max:255'],
            'archived_reason' => ['nullable', 'string', 'max:255'],
            'discarded_reason' => ['nullable', 'string', 'max:255'],
            'photo' => ['nullable', 'image', 'max:5120'],
        ];
    }
}
