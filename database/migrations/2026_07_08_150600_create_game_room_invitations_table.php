<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('game_room_invitations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('game_room_id')->constrained('game_rooms')->cascadeOnDelete();
            $table->string('email');
            $table->string('token')->unique();
            $table->foreignId('invited_by')->constrained('users')->cascadeOnDelete();
            $table->enum('status', ['pending', 'accepted', 'expired'])->default('pending');
            $table->timestamp('expired_at');
            $table->timestamp('accepted_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('game_room_invitations');
    }
};
