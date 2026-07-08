<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;

class StoreGameRoomRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->role === 'user';
    }

    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:255'],
            'game_card_set_id' => ['required', 'exists:game_card_sets,id'],
            'allow_guest' => ['nullable', 'boolean'],
            'host_is_player' => ['nullable', 'boolean'],
            'is_anonymous' => ['nullable', 'boolean'],
            'anonymous_name' => ['nullable', 'string', 'max:255'],
        ];
    }
}
