<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

use App\Traits\RecordsActivity;

class JobApplication extends Model
{
    use HasFactory, RecordsActivity;

    protected $fillable = [
        'job_id',
        'user_id',
        'resume_id',
        'match_score',
        'match_details',
        'resume_snapshot',
        'status',
        'cover_letter',
        'answers'
    ];

    protected $casts = [
        'match_details' => 'array',
        'resume_snapshot' => 'array',
        'answers' => 'array',
        'applied_at' => 'datetime',
        'match_score' => 'integer',
    ];

    public function job()
    {
        return $this->belongsTo(Job::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // public function resume()
    // {
    //     return $this->belongsTo(Resume::class)->withTrashed(); // In case resume is deleted later
    // }
}
