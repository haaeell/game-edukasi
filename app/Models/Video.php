<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class Video extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'slug',
        'youtube_url',
        'youtube_embed_url',
        'thumbnail',
        'description',
        'status',
        'created_by',
    ];

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function getThumbnailUrlAttribute(): string
    {
        if ($this->thumbnail) {
            return Str::startsWith($this->thumbnail, ['http://', 'https://'])
                ? $this->thumbnail
                : Storage::disk('public')->url($this->thumbnail);
        }

        return "data:image/svg+xml;utf8,".rawurlencode(
            "<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 800 480'>
                <defs>
                    <linearGradient id='g' x1='0' x2='1' y1='0' y2='1'>
                        <stop stop-color='#fef3c7'/>
                        <stop offset='1' stop-color='#fde68a'/>
                    </linearGradient>
                </defs>
                <rect width='800' height='480' rx='36' fill='url(#g)'/>
                <circle cx='400' cy='205' r='96' fill='rgba(255,255,255,.68)'/>
                <path d='M375 160l78 45-78 45z' fill='#d97706'/>
                <rect x='160' y='355' width='480' height='24' rx='12' fill='rgba(180,83,9,.16)'/>
                <rect x='220' y='80' width='170' height='24' rx='12' fill='rgba(180,83,9,.12)'/>
            </svg>"
        );
    }
}
