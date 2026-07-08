<?php

namespace App\Http\Requests\Admin;

use App\Models\Video;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class UpdateVideoRequest extends FormRequest
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
        /** @var Video $video */
        $video = $this->route('video');

        return [
            'title' => ['required', 'string', 'max:255'],
            'slug' => ['required', 'string', 'max:255', Rule::unique('videos', 'slug')->ignore($video?->id)],
            'youtube_url' => ['required', 'url'],
            'thumbnail' => ['nullable', 'image', 'max:2048'],
            'description' => ['nullable', 'string'],
            'status' => ['required', Rule::in(['draft', 'published'])],
        ];
    }
}
