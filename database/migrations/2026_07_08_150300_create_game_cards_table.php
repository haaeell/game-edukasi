<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('game_cards', function (Blueprint $table) {
            $table->id();
            $table->foreignId('game_card_set_id')->constrained('game_card_sets')->cascadeOnDelete();
            $table->string('title');
            $table->text('question');
            $table->unsignedInteger('order_number');
            $table->unsignedInteger('duration_seconds')->nullable();
            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->timestamps();

            $table->unique(['game_card_set_id', 'order_number']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('game_cards');
    }
};
