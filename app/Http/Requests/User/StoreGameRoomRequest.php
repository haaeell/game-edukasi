<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

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
            'card_flow_type' => ['required', Rule::in(['manual', 'automatic'])],
            'auto_next_seconds' => ['nullable', 'integer', 'min:5'],
            'allow_guest' => ['nullable', 'boolean'],
            'host_is_player' => ['nullable', 'boolean'],
            'is_anonymous' => ['nullable', 'boolean'],
            'anonymous_name' => ['nullable', 'string', 'max:255'],
        ];
    }
}
