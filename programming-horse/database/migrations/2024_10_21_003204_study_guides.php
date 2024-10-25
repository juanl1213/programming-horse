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
            $table->string("user_name")->unique(); 
            $table->string("language"); 
            $table->foreignId("topic_id")->constrained()->onDelete('cascade'); // Foreign key referencing topics table
            $table->text("incorrect_answers"); 
            $table->text("recommendations"); // Recommendations for the user based on incorrect answers
            $table->string("created_by")->default('system'); // created the study guide by system

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
