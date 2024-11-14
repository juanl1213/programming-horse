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
        Schema::create('games', function (Blueprint $table) {
            $table->id('game_id')->unique();
            $table->timestamps();
/*             $table->unsignedBigInteger("game_id")->unique(); */
            $table->foreignId('user_id')->constrained('users');
            $table->string("language");
            $table->foreignId("topic_id");
            $table->string("game_state")->default('active');
            $table->string("game_status")->default('active');
            $table->string("game_winner")->default('none');

            //$table->foreign("user_name")->references("user_name")->on("users");
            //$table->foreign("topic_id")->references("topic_id")->on("topics");
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('games');
    }
};
