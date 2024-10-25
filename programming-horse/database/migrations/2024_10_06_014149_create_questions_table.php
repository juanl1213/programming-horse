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
        Schema::create('questions', function (Blueprint $table) {
            $table->id("question_id")->unique();
            $table->timestamps();
            $table->string("language");
            $table->foreignId("topic_id");
            $table->string("question");
            $table->string("correct_answer");
            $table->string("incorrect_1");
            $table->string("incorrect_2");
            $table->string("incorrect_3");
            $table->boolean("validated")->nullable();

           // $table->foreign("topic_id")->reference("topic_id")->on("topics");
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('questions');
    }
};
