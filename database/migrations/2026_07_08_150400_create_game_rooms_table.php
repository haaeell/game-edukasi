<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('game_rooms', function (Blueprint $table) {
            $table->id();
            $table->string('code', 6)->unique();
            $table->foreignId('host_user_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('game_card_set_id')->constrained('game_card_sets')->cascadeOnDelete();
            $table->string('title');
            $table->enum('card_flow_type', ['manual', 'automatic'])->default('manual');
            $table->unsignedInteger('auto_next_seconds')->nullable();
            $table->boolean('allow_guest')->default(true);
            $table->enum('status', ['waiting', 'playing', 'finished'])->default('waiting');
            $table->foreignId('current_game_card_id')->nullable()->constrained('game_cards')->nullOnDelete();
            $table->unsignedInteger('current_card_order')->nullable();
            $table->timestamp('current_card_started_at')->nullable();
            $table->timestamp('started_at')->nullable();
            $table->timestamp('ended_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('game_rooms');
    }
};
