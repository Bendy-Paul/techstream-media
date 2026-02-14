<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ResumeExperience extends Model
{
    protected $table = 'experiences';

    protected $fillable = [
        'resume_id',
        'company_name',
        'job_title',
        'employment_type',
        'start_date',
        'end_date',
        'is_current',
        'description',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'is_current' => 'boolean',
    ];

    public function resume()
    {
        return $this->belongsTo(Resume::class);
    }
}
