<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        // Drop partial or old table if exists to ensure clean state
        Schema::dropIfExists('job_applications');

        Schema::create('job_applications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('job_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('resume_id')->constrained()->onDelete('cascade'); // Reference to original resume

            // Scoring & Logic
            $table->unsignedInteger('match_score')->default(0)->index(); 
            $table->json('match_details')->nullable(); // Stores matching ID overlaps
            
            // The Immutable Snapshot
            $table->json('resume_snapshot')->nullable(); // Captures skills, tools, and experience

            // Lifecycle
            $table->enum('status', ['applied', 'shortlisted', 'interviewing', 'rejected', 'hired', 'withdrawn'])
                  ->default('applied');

            $table->timestamps();
            
            // Prevent duplicate applications
            $table->unique(['job_id', 'user_id']); 
        });
    }

    public function down(): void {
        Schema::dropIfExists('job_applications');
    }
};
