<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EventGallery extends Model
{
    //
    protected $fillable = [
        'event_id',
        'image_path',
    ];

    public function events(){
      return  $this->belongsTo(Event::class);
    }
}
