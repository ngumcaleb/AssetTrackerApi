<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreActivityLogRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'type' => [
                'required',
                'string',
                'max:255',
                Rule::in([
                    'asset_created',
                    'asset_archived',
                    'asset_discarded',
                    'asset_restored',
                    'asset_deleted',
                    'checkout',
                    'return',
                    'scan',
                    'note',
                ]),
            ],
            'asset_id' => ['nullable', 'exists:assets,id'],
            'description' => ['required', 'string'],
            'metadata' => ['nullable', 'array'],
        ];
    }
}
