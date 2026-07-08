<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class Article extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'slug',
        'thumbnail',
        'content',
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
                        <stop stop-color='#dbeafe'/>
                        <stop offset='1' stop-color='#bfdbfe'/>
                    </linearGradient>
                </defs>
                <rect width='800' height='480' rx='36' fill='url(#g)'/>
                <circle cx='180' cy='150' r='72' fill='rgba(255,255,255,.65)'/>
                <rect x='120' y='255' width='560' height='26' rx='13' fill='rgba(37,99,235,.18)'/>
                <rect x='120' y='305' width='430' height='20' rx='10' fill='rgba(37,99,235,.12)'/>
                <rect x='120' y='345' width='360' height='20' rx='10' fill='rgba(37,99,235,.1)'/>
                <path d='M150 150h60M150 120h120M150 180h120' stroke='#2563eb' stroke-width='18' stroke-linecap='round'/>
            </svg>"
        );
    }
}
