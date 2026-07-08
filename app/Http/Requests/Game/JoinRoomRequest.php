<?php

namespace App\Http\Requests\Game;

use Illuminate\Foundation\Http\FormRequest;

class JoinRoomRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'code' => strtoupper((string) $this->code),
        ]);
    }

    public function rules(): array
    {
        return [
            'code' => ['required', 'string', 'size:6', 'exists:game_rooms,code'],
            'guest_name' => ['nullable', 'string', 'max:255'],
            'email' => ['nullable', 'email', 'max:255'],
            'is_anonymous' => ['nullable', 'boolean'],
            'anonymous_name' => ['nullable', 'string', 'max:255'],
            'invitation_token' => ['nullable', 'string', 'max:255'],
        ];
    }
}
