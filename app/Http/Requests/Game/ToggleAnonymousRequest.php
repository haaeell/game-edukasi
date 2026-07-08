<?php

namespace App\Http\Requests\Game;

use Illuminate\Foundation\Http\FormRequest;

class ToggleAnonymousRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'is_anonymous' => ['required', 'boolean'],
            'anonymous_name' => ['nullable', 'string', 'max:255'],
        ];
    }
}
