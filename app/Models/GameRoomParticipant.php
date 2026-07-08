<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Storage;

class GameRoomParticipant extends Model
{
    use HasFactory;

    protected $fillable = [
        'game_room_id',
        'user_id',
        'guest_name',
        'email',
        'display_name',
        'participant_type',
        'is_anonymous',
        'anonymous_name',
        'is_host',
        'status',
        'joined_at',
        'left_at',
    ];

    protected function casts(): array
    {
        return [
            'is_anonymous' => 'boolean',
            'is_host' => 'boolean',
            'joined_at' => 'datetime',
            'left_at' => 'datetime',
        ];
    }

    public function room(): BelongsTo
    {
        return $this->belongsTo(GameRoom::class, 'game_room_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function messages(): HasMany
    {
        return $this->hasMany(GameRoomMessage::class);
    }

    public function getPublicNameAttribute(): string
    {
        return $this->display_name;
    }

    public function getPhotoUrlAttribute(): ?string
    {
        if ($this->is_anonymous || ! $this->user?->photo) {
            return null;
        }

        return Storage::disk('public')->url($this->user->photo);
    }
}
