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
        Schema::create('rounds', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->unsignedBigInteger("round_num");
            $table->unsignedBigInteger("game_id")->unqiue();
            $table->unsignedBigInteger("question_id");
            $table->string("answer_selected");
            $table->string("round_winner");
            $table->string("is_correct");

            $table->foreign("game_id")->references("game_id")->on("games");
            $table->foreign("question_id")->references("question_id")->on("questions");
    });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rounds');
    }
};
