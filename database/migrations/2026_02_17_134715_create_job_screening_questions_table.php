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
    Schema::create('job_screening_questions', function (Blueprint $table) {
        $table->id();
        // NullOnDelete is good for history, but Cascade is often better for cleanup
        $table->foreignId('job_id')->constrained('jobs')->cascadeOnDelete(); 
        $table->string('question');
        $table->enum('type', ['text', 'boolean', 'multiple_choice', 'file']);
        $table->json('options')->nullable()->comment('Only for multiple_choice type');
        $table->string('correct_answer')->nullable()->comment('For auto-grading/filtering');
        $table->boolean('is_required')->default(true);
        $table->unsignedInteger('order')->default(0); // For UI sequencing
        $table->timestamps();
        
        $table->index(['job_id', 'order']); 
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('job_screening_questions');
    }
};
