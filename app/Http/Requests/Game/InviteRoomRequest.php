<?php

namespace App\Http\Requests\Game;

use Illuminate\Foundation\Http\FormRequest;

class InviteRoomRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() !== null;
    }

    public function rules(): array
    {
        return [
            'email' => ['required', 'email', 'max:255'],
        ];
    }
}
