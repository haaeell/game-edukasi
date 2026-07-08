<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class GameRoomMessage extends Model
{
    use HasFactory;

    protected $fillable = [
        'game_room_id',
        'game_room_participant_id',
        'game_card_id',
        'message',
        'message_type',
    ];

    public function room(): BelongsTo
    {
        return $this->belongsTo(GameRoom::class, 'game_room_id');
    }

    public function participant(): BelongsTo
    {
        return $this->belongsTo(GameRoomParticipant::class, 'game_room_participant_id');
    }

    public function card(): BelongsTo
    {
        return $this->belongsTo(GameCard::class, 'game_card_id');
    }
}
