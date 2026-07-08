<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('game_room_messages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('game_room_id')->constrained('game_rooms')->cascadeOnDelete();
            $table->foreignId('game_room_participant_id')->constrained('game_room_participants')->cascadeOnDelete();
            $table->foreignId('game_card_id')->nullable()->constrained('game_cards')->nullOnDelete();
            $table->text('message');
            $table->enum('message_type', ['chat', 'system'])->default('chat');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('game_room_messages');
    }
};
