<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class GameRoomFeedback extends Model
{
    use HasFactory;

    protected $table = 'game_room_feedbacks';

    protected $fillable = [
        'game_room_id',
        'game_room_participant_id',
        'participant_name',
        'message',
    ];

    public function room(): BelongsTo
    {
        return $this->belongsTo(GameRoom::class, 'game_room_id');
    }

    public function participant(): BelongsTo
    {
        return $this->belongsTo(GameRoomParticipant::class, 'game_room_participant_id');
    }
}
