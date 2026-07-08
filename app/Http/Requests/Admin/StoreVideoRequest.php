<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class StoreVideoRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->role === 'admin';
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'slug' => $this->slug ?: Str::slug((string) $this->title),
        ]);
    }

    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:255'],
            'slug' => ['required', 'string', 'max:255', Rule::unique('videos', 'slug')],
            'youtube_url' => ['required', 'url'],
            'thumbnail' => ['nullable', 'image', 'max:2048'],
            'description' => ['nullable', 'string'],
            'status' => ['required', Rule::in(['draft', 'published'])],
        ];
    }
}
