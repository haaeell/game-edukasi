<?php

namespace App\Services;

class YouTubeService
{
    public function toEmbedUrl(string $url): ?string
    {
        $parsedUrl = parse_url($url);

        if (! is_array($parsedUrl) || ! isset($parsedUrl['host'])) {
            return null;
        }

        $host = $parsedUrl['host'];
        $videoId = null;

        if (str_contains($host, 'youtu.be')) {
            $videoId = ltrim($parsedUrl['path'] ?? '', '/');
        }

        if (str_contains($host, 'youtube.com')) {
            parse_str($parsedUrl['query'] ?? '', $query);
            $videoId = $query['v'] ?? null;
        }

        if (! $videoId) {
            return null;
        }

        return 'https://www.youtube.com/embed/'.$videoId;
    }
}
