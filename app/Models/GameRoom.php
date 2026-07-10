<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class GameRoom extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'host_user_id',
        'game_card_set_id',
        'title',
        'card_flow_type',
        'auto_next_seconds',
        'allow_guest',
        'host_is_player',
        'status',
        'current_game_card_id',
        'current_target_participant_id',
        'next_turn_participant_id',
        'current_card_order',
        'opened_card_ids',
        'current_card_started_at',
        'started_at',
        'ended_at',
    ];

    protected function casts(): array
    {
        return [
            'allow_guest' => 'boolean',
            'host_is_player' => 'boolean',
            'auto_next_seconds' => 'integer',
            'current_game_card_id' => 'integer',
            'current_target_participant_id' => 'integer',
            'next_turn_participant_id' => 'integer',
            'current_card_order' => 'integer',
            'opened_card_ids' => 'array',
            'current_card_started_at' => 'datetime',
            'started_at' => 'datetime',
            'ended_at' => 'datetime',
        ];
    }

    public function host(): BelongsTo
    {
        return $this->belongsTo(User::class, 'host_user_id');
    }

    public function cardSet(): BelongsTo
    {
        return $this->belongsTo(GameCardSet::class, 'game_card_set_id');
    }

    public function currentCard(): BelongsTo
    {
        return $this->belongsTo(GameCard::class, 'current_game_card_id');
    }

    public function currentTargetParticipant(): BelongsTo
    {
        return $this->belongsTo(GameRoomParticipant::class, 'current_target_participant_id');
    }

    public function nextTurnParticipant(): BelongsTo
    {
        return $this->belongsTo(GameRoomParticipant::class, 'next_turn_participant_id');
    }

    public function participants(): HasMany
    {
        return $this->hasMany(GameRoomParticipant::class);
    }

    public function invitations(): HasMany
    {
        return $this->hasMany(GameRoomInvitation::class);
    }

    public function messages(): HasMany
    {
        return $this->hasMany(GameRoomMessage::class);
    }

    public function feedbacks(): HasMany
    {
        return $this->hasMany(GameRoomFeedback::class);
    }

    public function activeParticipants(): HasMany
    {
        return $this->participants()->where('status', 'active');
    }
}
