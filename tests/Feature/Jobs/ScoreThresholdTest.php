<?php

namespace Tests\Feature\Jobs;

use App\Jobs\ProcessJobRecommendations;
use App\Models\Company;
use App\Models\Job;
use App\Models\JobRecommendation;
use App\Models\Resume;
use App\Models\Stack;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ScoreThresholdTest extends TestCase
{
    use RefreshDatabase;

    public function test_recommendations_respect_score_threshold()
    {
        // 1. Setup Data
        $user = User::factory()->create();

        $phpStack = Stack::create(['name' => 'PHP']);
        $jsStack = Stack::create(['name' => 'JavaScript']);

        // Resume has PHP
        $resume = Resume::create([
            'user_id' => $user->id,
            'title' => 'My Resume',
            'summary' => 'Initial Summary',
            'is_default' => true,
            'visibility' => 'public',
        ]);
        $resume->skills()->create(['skills' => [$phpStack->id]]);

        $company = Company::create([
            'name' => 'Tech Corp',
            'slug' => 'tech-corp',
            'user_id' => $user->id,
            'status' => 'active',
        ]);

        // Job 1: Requires PHP (100% Match) -> Should be saved
        $jobHigh = Job::create([
            'title' => 'PHP Developer',
            'company_id' => $company->id,
            'description' => 'We need a PHP dev',
            'status' => 'active',
            'expires_at' => now()->addDays(30),
            'user_id' => $user->id,
            'slug' => 'php-dev',
            'location' => 'Remote',
            'is_remote' => true,
            'job_type' => 'Full-time',
            'application_type' => 'smart_apply',
            'summary' => 'Summary',
        ]);
        $jobHigh->tools()->attach($phpStack->id);

        // Job 2: Requires JavaScript (0% Match) -> Should NOT be saved
        $jobLow = Job::create([
            'title' => 'JS Developer',
            'company_id' => $company->id,
            'description' => 'We need a JS dev',
            'status' => 'active',
            'expires_at' => now()->addDays(30),
            'user_id' => $user->id,
            'slug' => 'js-dev',
            'location' => 'Remote',
            'is_remote' => true,
            'job_type' => 'Full-time',
            'application_type' => 'smart_apply',
            'summary' => 'Summary',
        ]);
        $jobLow->tools()->attach($jsStack->id);

        // 2. Dispatch Job
        // Note: The user updated the job to take an int ID, let's use that.
        ProcessJobRecommendations::dispatchSync($user->id);

        // 3. Assertions
        $this->assertDatabaseHas('job_recommendations', [
            'user_id' => $user->id,
            'job_id' => $jobHigh->id,
        ]); // Implicitly checks score >= 50 resulted in save

        $this->assertDatabaseMissing('job_recommendations', [
            'user_id' => $user->id,
            'job_id' => $jobLow->id,
        ]); // Checks score < 50 resulted in NO save
    }
}
