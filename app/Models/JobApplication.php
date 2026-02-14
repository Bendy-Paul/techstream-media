<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class JobApplication extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'job_id',
        'user_id',
        'company_id',
        'resume_id',
        'resume_snapshot',
        'match_score',
        'cover_letter',
        'answers',
        'status',
        'applied_at',
        'reviewed_at',
        'rejected_at',
        'hired_at',
    ];

    protected $casts = [
        'resume_snapshot' => 'json',
        'answers' => 'json',
        'applied_at' => 'datetime',
        'reviewed_at' => 'datetime',
        'rejected_at' => 'datetime',
        'hired_at' => 'datetime',
    ];

    public function job()
    {
        return $this->belongsTo(Job::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function resume()
    {
        return $this->belongsTo(Resume::class);
    }
}
