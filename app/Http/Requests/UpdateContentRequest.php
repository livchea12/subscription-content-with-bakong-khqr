<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Enums\ContentTier;
use Illuminate\Validation\Rules\Enum;

class UpdateContentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'title' => 'sometimes|string|max:255',
            'body' => 'sometimes|string',
            'tier' => ['sometimes', new Enum(ContentTier::class)],
        ];
    }
}
