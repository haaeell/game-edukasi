<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('game_rooms', function (Blueprint $table) {
            $table->foreignId('current_target_participant_id')
                ->nullable()
                ->after('current_game_card_id')
                ->constrained('game_room_participants')
                ->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('game_rooms', function (Blueprint $table) {
            $table->dropConstrainedForeignId('current_target_participant_id');
        });
    }
};
