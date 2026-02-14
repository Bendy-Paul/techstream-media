<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CompanyProject extends Model
{
    //

    protected $fillable = [
        'company_id',
        'name',
        'description',
        // 'start_date',
        // 'end_date',
        // Add other fields as necessary
    ];
}
