<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JobRecommendation extends Model
{
    //
    protected $fillable = [
        'user_id',
        'job_id',
        'score',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function job()
    {
        return $this->belongsTo(Job::class);
    }

    public function scopeForUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    public function scopeForJob($query, $jobId)
    {
        return $query->where('job_id', $jobId);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('score', 'desc');
    }
}
