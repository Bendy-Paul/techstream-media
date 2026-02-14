<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JobTools extends Model
{
    //
    protected $fillable = [
        'job_id',
        'tool_id',
    ];

    public function job()
    {
        return $this->belongsTo(Job::class);
    }

    public function tool()
    {
        return $this->belongsTo(Tools::class);
    }
}
