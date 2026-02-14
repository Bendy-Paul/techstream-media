<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JobKeyword extends Model
{
    protected $table = 'job_keyword';

    public $timestamps = false;

    protected $fillable = [
        'job_id',
        'keyword_id',
        'weight',
    ];

    public function job()
    {
        return $this->belongsTo(Job::class);
    }

    public function keyword()
    {
        return $this->belongsTo(Keyword::class);
    }
}
