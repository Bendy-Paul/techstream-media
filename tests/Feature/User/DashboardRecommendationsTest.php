<?php

namespace Tests\Feature\User;

use App\Models\Company;
use App\Models\Job;
use App\Models\JobRecommendation;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DashboardRecommendationsTest extends TestCase
{
    use RefreshDatabase;

    public function test_dashboard_displays_recommended_jobs()
    {
        // 1. Setup Data
        $user = User::factory()->create([
            'role' => 'user',
        ]);

        $company = Company::create([
            'name' => 'Awesome Corp',
            'slug' => 'awesome-corp',
            'user_id' => $user->id,
            'status' => 'active',
        ]);

        $job = Job::create([
            'title' => 'Top Recommended Job',
            'company_id' => $company->id,
            'description' => 'Great job',
            'status' => 'active',
            'expires_at' => now()->addDays(30),
            'user_id' => $user->id,
            'slug' => 'top-job',
            'location' => 'Remote',
            'is_remote' => true,
            'job_type' => 'Full-time',
            'salary_range' => '100k',
            'experience_level' => 'Mid',
            'education_level' => 'Bachelor',
            'application_type' => 'smart_apply',
            'summary' => 'Summary',
        ]);

        JobRecommendation::create([
            'user_id' => $user->id,
            'job_id' => $job->id,
            'score' => 95.00,
        ]);

        // 2. Act
        $response = $this->actingAs($user)->get(route('user.dashboard'));

        // 3. Assert
        $response->assertStatus(200);
        $response->assertSee('Top Recommended Job');
        $response->assertSee('Awesome Corp');
    }
}
