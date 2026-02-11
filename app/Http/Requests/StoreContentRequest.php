<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Enums\ContentTier;
use Illuminate\Validation\Rules\Enum;

class StoreContentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // We'll handle authorization in controller/policy
    }

    public function rules(): array
    {
        return [
            'title' => 'required|string|max:255',
            'body' => 'required|string',
            'tier' => ['required', new Enum(ContentTier::class)],
        ];
    }
}
