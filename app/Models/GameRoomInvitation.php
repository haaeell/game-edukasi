<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class GameRoomInvitation extends Model
{
    use HasFactory;

    protected $fillable = [
        'game_room_id',
        'email',
        'token',
        'invited_by',
        'status',
        'expired_at',
        'accepted_at',
    ];

    protected function casts(): array
    {
        return [
            'expired_at' => 'datetime',
            'accepted_at' => 'datetime',
        ];
    }

    public function room(): BelongsTo
    {
        return $this->belongsTo(GameRoom::class, 'game_room_id');
    }

    public function inviter(): BelongsTo
    {
        return $this->belongsTo(User::class, 'invited_by');
    }
}
