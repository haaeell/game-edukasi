<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('game_room_participants', function (Blueprint $table) {
            $table->id();
            $table->foreignId('game_room_id')->constrained('game_rooms')->cascadeOnDelete();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('guest_name')->nullable();
            $table->string('email')->nullable();
            $table->string('display_name');
            $table->enum('participant_type', ['registered', 'guest']);
            $table->boolean('is_anonymous')->default(false);
            $table->string('anonymous_name')->nullable();
            $table->boolean('is_host')->default(false);
            $table->enum('status', ['active', 'left'])->default('active');
            $table->timestamp('joined_at');
            $table->timestamp('left_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('game_room_participants');
    }
};
