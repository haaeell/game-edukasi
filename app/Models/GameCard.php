<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class GameCard extends Model
{
    use HasFactory;

    protected $fillable = [
        'game_card_set_id',
        'title',
        'question',
        'order_number',
        'duration_seconds',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'duration_seconds' => 'integer',
            'order_number' => 'integer',
        ];
    }

    public function set(): BelongsTo
    {
        return $this->belongsTo(GameCardSet::class, 'game_card_set_id');
    }

    public function roomMessages(): HasMany
    {
        return $this->hasMany(GameRoomMessage::class);
    }
}
