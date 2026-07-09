<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('game_rooms', function (Blueprint $table) {
            $table->foreignId('next_turn_participant_id')
                ->nullable()
                ->after('current_target_participant_id')
                ->constrained('game_room_participants')
                ->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('game_rooms', function (Blueprint $table) {
            $table->dropConstrainedForeignId('next_turn_participant_id');
        });
    }
};
