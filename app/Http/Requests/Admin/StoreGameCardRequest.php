<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreGameCardRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->role === 'admin';
    }

    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:255'],
            'question' => ['required', 'string'],
            'duration_seconds' => ['nullable', 'integer', 'min:1'],
            'status' => ['required', Rule::in(['active', 'inactive'])],
        ];
    }
}
