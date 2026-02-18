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

class ResumeUpdateRecommendationTest extends TestCase
{
    use RefreshDatabase;

    public function test_recommendations_update_after_resume_change()
    {
        // 1. Setup Data - User, Resume with 'PHP', Job with 'PHP'
        $user = User::factory()->create();

        $phpStack = Stack::create(['name' => 'PHP']);
        $jsStack = Stack::create(['name' => 'JavaScript']);

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

        $job = Job::create([
            'title' => 'PHP Developer',
            'company_id' => $company->id,
            'description' => 'We need a PHP dev',
            'status' => 'active',
            'expires_at' => now()->addDays(30),
            'user_id' => $user->id, // Usually job author
            'slug' => 'php-developer',
            'location' => 'Remote',
            'is_remote' => true,
            'job_type' => 'Full-time',
            'application_type' => 'smart_apply',
            'summary' => 'Summary',
        ]);
        $job->tools()->attach($phpStack->id);

        // 2. Initial Dispatch
        ProcessJobRecommendations::dispatchSync($user);

        // Verify initial match
        $this->assertDatabaseHas('job_recommendations', [
            'user_id' => $user->id,
            'job_id' => $job->id,
            'score' => 100,
        ]);

        // 3. Update Resume - Remove 'PHP', Add 'JavaScript'
        $resume->skills()->update(['skills' => [$jsStack->id]]);

        // Refresh resume relation
        $user->refresh();

        // 4. Dispatch again (Simulate ResumeController update)
        ProcessJobRecommendations::dispatchSync($user);

        // 5. Verify Score Updated (Should be 0 or very low)
        // Since tools match is 0 and keywords match (assuming none) is 0.

        // We expect the record to still exist but with score 0
        $this->assertDatabaseHas('job_recommendations', [
            'user_id' => $user->id,
            'job_id' => $job->id,
            'score' => 0,
        ]);
    }
}
