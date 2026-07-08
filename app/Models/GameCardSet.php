<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class GameCardSet extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'status',
        'created_by',
    ];

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function cards(): HasMany
    {
        return $this->hasMany(GameCard::class)->orderBy('order_number');
    }

    public function rooms(): HasMany
    {
        return $this->hasMany(GameRoom::class);
    }
}
