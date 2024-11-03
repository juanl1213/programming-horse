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
        Schema::create('study_guides', function (Blueprint $table) {
            $table->studyGuide_id(); 
            $table->timestamps(); 
            $table->foreignId("game_id")->unique();
            $table->string("user_name");
            $table->string("language"); 
            $table->foreignId("topic_id")->constrained()->onDelete('cascade'); // Foreign key referencing topics table
            $table->string("recommendations_written_1");
            $table->string("recommendations_written_2");
            $table->string("recommendations_written_3");
            $table->string("recommendations_video_1");
            $table->string("recommendations_video_2");
            $table->string("recommendations_video_3");
            $table->string("created_by")->default('system'); // created the study guide by system

            $table->foreign("user_name")->references("user_name")->on("users");
            $table->foreign("game_id")->references("game_id")->on("games");

            // Optional: Index for better performance
            $table->index('user_name');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('study_guides');
    }
};