<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ResumeEducation extends Model
{
    protected $table = 'education';

    protected $fillable = [
        'resume_id',
        'institution',
        'degree',
        'field_of_study',
        'start_date',
        'end_date',
        'grade',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
    ];

    public function resume()
    {
        return $this->belongsTo(Resume::class);
    }
}
