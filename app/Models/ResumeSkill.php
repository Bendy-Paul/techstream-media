<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ResumeSkill extends Model
{
    protected $fillable = [
        'resume_id',
        'skills',
    ];

    protected $casts = [
        'skills' => 'array',
    ];

    public function resume()
    {
        return $this->belongsTo(Resume::class);
    }
}
