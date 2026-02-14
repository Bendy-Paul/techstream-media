<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        /*
        |--------------------------------------------------------------------------
        | Resumes
        |--------------------------------------------------------------------------
        */
        Schema::create('resumes', function (Blueprint $table) {
            $table->id();

            $table->foreignId('user_id')
                  ->constrained()
                  ->cascadeOnDelete();

            $table->string('title')->default('My Resume');
            $table->text('summary')->nullable();

            $table->boolean('is_default')->default(true);
            $table->enum('visibility', ['private', 'public'])->default('private');

            $table->timestamps();

            $table->index(['user_id', 'is_default']);
        });

        /*
        |--------------------------------------------------------------------------
        | Experiences
        |--------------------------------------------------------------------------
        */
        Schema::create('experiences', function (Blueprint $table) {
            $table->id();

            $table->foreignId('resume_id')
                  ->constrained()
                  ->cascadeOnDelete();

            $table->string('company_name');
            $table->string('job_title');

            $table->enum('employment_type', [
                'full_time',
                'part_time',
                'contract',
                'internship',
                'freelance'
            ])->nullable();

            $table->date('start_date');
            $table->date('end_date')->nullable();
            $table->boolean('is_current')->default(false);

            $table->text('description')->nullable();

            $table->timestamps();

            $table->index('resume_id');
        });

        /*
        |--------------------------------------------------------------------------
        | Education
        |--------------------------------------------------------------------------
        */
        Schema::create('education', function (Blueprint $table) {
            $table->id();

            $table->foreignId('resume_id')
                  ->constrained()
                  ->cascadeOnDelete();

            $table->string('institution');
            $table->string('degree')->nullable();
            $table->string('field_of_study')->nullable();

            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();

            $table->string('grade')->nullable();

            $table->timestamps();

            $table->index('resume_id');
        });

        /*
        |--------------------------------------------------------------------------
        | Resume Skills (JSON)
        |--------------------------------------------------------------------------
        */
        Schema::create('resume_skills', function (Blueprint $table) {
            $table->id();

            $table->foreignId('resume_id')
                  ->constrained()
                  ->cascadeOnDelete();

            $table->json('skills');

            $table->timestamps();

            // One skills record per resume
            $table->unique('resume_id');
        });

        /*
        |--------------------------------------------------------------------------
        | Resume Versions
        |--------------------------------------------------------------------------
        */
        Schema::create('resume_versions', function (Blueprint $table) {
            $table->id();

            $table->foreignId('resume_id')
                  ->constrained()
                  ->cascadeOnDelete();

            // Snapshot of the entire resume at a point in time
            $table->json('data');

            $table->timestamps();

            $table->index('resume_id');
        });
    }

    public function down(): void
    {
        // Drop in reverse order to satisfy foreign keys
        Schema::dropIfExists('resume_versions');
        Schema::dropIfExists('resume_skills');
        Schema::dropIfExists('education');
        Schema::dropIfExists('experiences');
        Schema::dropIfExists('resumes');
    }
};
