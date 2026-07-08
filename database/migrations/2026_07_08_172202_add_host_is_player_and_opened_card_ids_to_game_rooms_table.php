<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('game_rooms', function (Blueprint $table) {
            $table->boolean('host_is_player')->default(true)->after('allow_guest');
            $table->json('opened_card_ids')->nullable()->after('current_card_order');
        });
    }

    public function down(): void
    {
        Schema::table('game_rooms', function (Blueprint $table) {
            $table->dropColumn(['host_is_player', 'opened_card_ids']);
        });
    }
};
