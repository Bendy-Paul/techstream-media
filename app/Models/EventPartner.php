<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EventPartner extends Model
{
    //

    protected $fillable = [
        'event_id',
        'company_id',
    ];

    public function event(){
        return $this->belongsTo(Event::class);
    }
}
