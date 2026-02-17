<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Organizer extends Model
{
    use HasFactory;

    protected $fillable = [
        'owner_id',
        'owner_type',
        'name',
        'slug',
        'email',
        'phone',
        'website',
        'description',
        'logo_path',
    ];

    /**
     * Get the owning entity (User or Company).
     */
    public function owner()
    {
        return $this->morphTo();
    }

    /**
     * Get the events for the organizer.
     */
    public function events()
    {
        return $this->hasMany(Event::class);
    }
}
