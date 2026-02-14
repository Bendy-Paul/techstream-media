<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Resume extends Model
{
    //
    protected $fillable = [
        'user_id',
        'title',
        'summary',
        'is_default',
        'visibility',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function experiences()
    {
        return $this->hasMany(ResumeExperience::class);
    }

    public function education()
    {
        return $this->hasMany(ResumeEducation::class);
    }

    public function skills()
    {
        return $this->hasMany(ResumeSkill::class);
    }

    public function versions()
    {
        return $this->hasMany(ResumeVersion::class);
    }
}
