<?php

namespace Database\Seeders;

use App\Models\GameCard;
use App\Models\GameCardSet;
use App\Models\User;
use Illuminate\Database\Seeder;

class GameSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::where('email', 'admin@example.com')->firstOrFail();

        $sets = [
            'Ice Breaking' => [
                'Kalau bisa punya superpower satu hari, mau pilih apa?',
                'Sebutkan aktivitas favorit yang bikin semangat belajar.',
                'Siapa tokoh yang paling menginspirasi kamu dan kenapa?',
            ],
            'Deep Talk' => [
                'Apa tantangan terbesar yang pernah kamu lewati saat belajar?',
                'Apa arti sukses versi kamu saat ini?',
                'Hal apa yang ingin kamu tingkatkan dari diri sendiri tahun ini?',
            ],
            'Edukasi' => [
                'Metode belajar apa yang paling cocok untukmu?',
                'Bagaimana cara membuat materi sulit jadi lebih mudah dipahami?',
                'Apa kebiasaan kecil yang berdampak besar pada hasil belajar?',
            ],
        ];

        foreach ($sets as $title => $questions) {
            $set = GameCardSet::updateOrCreate(
                ['title' => $title],
                [
                    'description' => 'Set kartu untuk '.$title,
                    'status' => 'active',
                    'created_by' => $admin->id,
                ]
            );

            foreach ($questions as $index => $question) {
                GameCard::updateOrCreate(
                    [
                        'game_card_set_id' => $set->id,
                        'order_number' => $index + 1,
                    ],
                    [
                        'title' => 'Kartu '.($index + 1),
                        'question' => $question,
                        'duration_seconds' => 60,
                        'status' => 'active',
                    ]
                );
            }
        }
    }
}
