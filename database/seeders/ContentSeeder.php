<?php

namespace Database\Seeders;

use App\Models\Article;
use App\Models\User;
use App\Models\Video;
use App\Services\YouTubeService;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ContentSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::where('email', 'admin@example.com')->firstOrFail();
        $youtube = new YouTubeService();

        foreach ([
            'Tips Belajar Kolaboratif',
            'Cara Membuat Diskusi Kelas Lebih Aktif',
            'Ice Breaking untuk Sesi Pembelajaran',
        ] as $title) {
            Article::updateOrCreate(
                ['slug' => Str::slug($title)],
                [
                    'title' => $title,
                    'content' => 'Konten dummy untuk '.$title.'. Dokumen ini menjadi awal untuk artikel edukasi di aplikasi.',
                    'status' => 'published',
                    'created_by' => $admin->id,
                ]
            );
        }

        foreach ([
            ['title' => 'Belajar Efektif', 'url' => 'https://www.youtube.com/watch?v=dQw4w9WgXcQ'],
            ['title' => 'Diskusi Kelompok', 'url' => 'https://youtu.be/aqz-KE-bpKQ'],
            ['title' => 'Team Building Edukatif', 'url' => 'https://www.youtube.com/watch?v=ysz5S6PUM-U'],
        ] as $video) {
            Video::updateOrCreate(
                ['slug' => Str::slug($video['title'])],
                [
                    'title' => $video['title'],
                    'youtube_url' => $video['url'],
                    'youtube_embed_url' => $youtube->toEmbedUrl($video['url']) ?? $video['url'],
                    'description' => 'Video dummy untuk '.$video['title'],
                    'status' => 'published',
                    'created_by' => $admin->id,
                ]
            );
        }
    }
}
