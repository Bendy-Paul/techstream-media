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
        Schema::create('activities', function (Blueprint $table) {
            $table->id();

            // Owner of the activity
            $table->foreignId('user_id')
                  ->constrained()
                  ->cascadeOnDelete();

            // Activity type (resume_updated, job_applied, job_saved)
            $table->string('type', 50)->index();

            // Polymorphic relation (Resume, JobApplication, SavedItem, etc.)
            $table->morphs('subject');

            // Optional small metadata
            $table->json('meta')->nullable();

            $table->timestamps();

            // Performance index for dashboard queries
            $table->index(['user_id', 'created_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('activities');
    }
};
