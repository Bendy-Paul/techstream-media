<?php

namespace App\Jobs;

use App\Models\User;
use App\Models\Job;
use App\Models\JobRecommendation;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;


class ProcessJobRecommendations implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $userId;

    public function __construct(int $userId)
    {
        $this->userId = $userId;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $user = User::find($this->userId);

        // Eager load skills to avoid N+1 during match calculation
        $userResume = $user->resumes()->with('skills')->where('is_default', 1)->first();

        if (!$userResume || $userResume->skills->isEmpty()) {
            return;
        }

        $jobs = Job::active()->get();

        foreach ($jobs as $job) {
            // Correctly call the method on the Job model
            $score = floor($job->calculateMatchScore($userResume));

            if ($score >= 50) {
                JobRecommendation::updateOrCreate(
                    ['user_id' => $user->id, 'job_id' => $job->id],
                    ['score' => $score]
                );
            } else {
                JobRecommendation::where([
                    'user_id' => $user->id,
                    'job_id' => $job->id
                ])->delete();
            }
        }
    }
}
