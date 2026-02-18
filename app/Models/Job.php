<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Stack; // Added this line

use App\Traits\RecordsActivity;

class Job extends Model
{
    use RecordsActivity;
    //
    protected $fillable = [
        'title',
        'slug',
        'company_id',
        'description',
        'summary',
        'responsibilities',
        'requirements',
        'job_type',
        'experience_level',
        'education_level',
        'salary_range',
        'application_type',
        'apply_link',
        'location',  // For string location
        'city_id',   // For city relation
        'is_remote',
        'expires_at',
        'skills',
        'screening_questions',
        'status',
    ];

    protected $casts = [
        'is_remote' => 'boolean',
        'expires_at' => 'datetime',
        'screening_questions' => 'json',
    ];

    public function scopeActive($query)
    {
        return $query->where('status', 'active')
            ->where('expires_at', '>', now());
    }

    public function applications()
    {
        return $this->hasMany(JobApplication::class);
    }

    public function keywords()
    {
        return $this->belongsToMany(Keyword::class, 'job_keyword')
            ->withPivot('weight');
    }

    /**
     * Calculate match score with a resume
     */
    public function calculateMatchScore(Resume $resume): int
    {
        // 1. Get the User's Skill IDs
        // 1. Get the User's Skill IDs
        // Use property access to leverage eager loading if available
        $userSkillIds = $resume->skills->pluck('skills')->flatten()->unique()->toArray();

        // 2. Get Job Tool IDs (Hard Skills directly linked via 'tools' relationship)
        $jobToolIds = $this->tools()->pluck('stacks.id')->toArray();

        // 3. Get Job Keywords with weights
        $jobKeywords = $this->keywords()->get();

        // 4. Calculate Keyword Match
        $totalWeight = 0;
        $matchedWeight = 0;

        // Pre-fetch Stack IDs for all keywords to avoid N+1 queries
        $keywordNames = $jobKeywords->pluck('name')->toArray();
        $stackMap = \App\Models\Stack::whereIn('name', $keywordNames)
            ->pluck('id', 'name')
            ->mapWithKeys(fn($item, $key) => [strtolower($key) => $item]);

        foreach ($jobKeywords as $keyword) {
            $weight = $keyword->pivot->weight ?? 1;
            $totalWeight += $weight;

            $stackId = $stackMap->get(strtolower($keyword->name));

            if ($stackId && in_array($stackId, $userSkillIds)) {
                $matchedWeight += $weight;
            }
        }

        // 5. Tool Match
        $toolScore = 0;
        $hasTools = count($jobToolIds) > 0;

        if ($hasTools) {
            $matchedTools = count(array_intersect($jobToolIds, $userSkillIds));
            $toolScore = ($matchedTools / count($jobToolIds)) * 100;
        }

        $hasKeywords = $totalWeight > 0;
        $keywordScore = $hasKeywords ? ($matchedWeight / $totalWeight) * 100 : 100;

        // Edge cases
        if (!$hasTools && !$hasKeywords) {
            return 100; // No requirements → perfect match
        }

        if (!$hasTools) {
            return (int) round($keywordScore); // Only keywords count
        }

        if (!$hasKeywords) {
            return (int) round($toolScore); // Only tools count
        }

        // Weighted average when both exist (70% tools, 30% keywords)
        return (int) round(($toolScore * 0.7) + ($keywordScore * 0.3));
    }
    public function city()
    {
        return $this->belongsTo(City::class);
    }
    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function tools()
    {
        return $this->belongsToMany(Tools::class, 'job_tool', 'job_id', 'stack_id');
    }
    public function savedBy()
    {
        return $this->morphMany(SavedItem::class, 'item');
    }

    public function reviews()
    {
        return $this->morphMany(Review::class, 'item');
    }
}
