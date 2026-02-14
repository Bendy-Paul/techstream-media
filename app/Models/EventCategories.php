<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EventCategories extends Model
{
    //
    protected $table = 'category_event';

    protected $fillable = [
        'category_id',
        'event_id',
    ];

    
}
