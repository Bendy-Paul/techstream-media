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
        Schema::create('job_applications', function (Blueprint $table) {
            $table->id();

            /*
            |--------------------------------------------------------------------------
            | Core Relationships
            |--------------------------------------------------------------------------
            */

            $table->foreignId('job_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->foreignId('user_id')
                ->constrained()
                ->cascadeOnDelete();

            // Denormalized for faster employer dashboard queries
            $table->foreignId('company_id')
                ->index();

            /*
            |--------------------------------------------------------------------------
            | Resume Snapshot (Immutable)
            |--------------------------------------------------------------------------
            */

            $table->foreignId('resume_id')
                ->nullable()
                ->constrained()
                ->nullOnDelete();

            // What employer actually saw at time of apply
            $table->json('resume_snapshot'); // Changed jsonb to json for compatibility

            /*
            |--------------------------------------------------------------------------
            | Smart Match System
            |--------------------------------------------------------------------------
            */

            // 0–100 match score
            $table->unsignedTinyInteger('match_score')
                ->nullable()
                ->index();

            /*
            |--------------------------------------------------------------------------
            | Application Data
            |--------------------------------------------------------------------------
            */

            $table->text('cover_letter')->nullable();

            $table->json('answers')->nullable(); // Changed jsonb to json

            /*
            |--------------------------------------------------------------------------
            | Status Lifecycle
            |--------------------------------------------------------------------------
            */

            $table->string('status')
                ->default('applied')
                ->index();

            $table->timestamp('applied_at')
                ->useCurrent()
                ->index();

            $table->timestamp('reviewed_at')->nullable();
            $table->timestamp('rejected_at')->nullable();
            $table->timestamp('hired_at')->nullable();

            /*
            |--------------------------------------------------------------------------
            | System
            |--------------------------------------------------------------------------
            */

            $table->timestamps();
            $table->softDeletes();

            /*
            |--------------------------------------------------------------------------
            | Performance Indexes
            |--------------------------------------------------------------------------
            */

            $table->unique(['job_id', 'user_id']);

            $table->index(['company_id', 'status']);
            $table->index(['company_id', 'applied_at']);
            $table->index(['company_id', 'match_score']);
            $table->index(['job_id', 'status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('job_applications');
    }
};
