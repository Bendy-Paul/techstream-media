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

class ProcessJobRecommendationsTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_generates_recommendations_for_user()
    {
        // 1. Setup Data
        $user = User::factory()->create();

        $stack = Stack::create(['name' => 'PHP']);

        // Create Resume with Skills
        $resume = Resume::create([
            'user_id' => $user->id,
            'title' => 'PHP Dev',
            'summary' => 'Experienced PHP developer',
            'is_default' => true,
            'visibility' => 'public',
        ]);

        $resume->skills()->create([
            'skills' => [$stack->id]
        ]);

        $company = Company::create([
            'name' => 'Tech Corp',
            'slug' => 'tech-corp',
            'user_id' => $user->id,
            'status' => 'active',
        ]);

        // Create Job with matching Tool (Stack)
        $job = Job::create([
            'title' => 'PHP Developer',
            'company_id' => $company->id,
            'description' => 'We need a PHP dev',
            'status' => 'active',
            'expires_at' => now()->addDays(30),
            'user_id' => $user->id,
            'slug' => 'php-developer',
            'location' => 'Remote',
            'is_remote' => true,
            'job_type' => 'Full-time',
            'salary_range' => '100k-120k',
            'experience_level' => 'Senior',
            'education_level' => 'Bachelor',
            'application_type' => 'smart_apply',
            'summary' => 'Short summary of the job',
        ]);

        // Attach tool to job
        $job->tools()->attach($stack->id);

        // 2. Dispatch Job
        ProcessJobRecommendations::dispatchSync($user);

        // 3. Assertions
        $this->assertDatabaseHas('job_recommendations', [
            'user_id' => $user->id,
            'job_id' => $job->id,
            'score' => 100,
        ]);
    }
}
