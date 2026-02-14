<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class City extends Model
{
    //
    protected $table = 'cities';

    protected $fillable = [
        'name',
        'state_id',
        'city_code',
    ];

    public function country()
    {
        return $this->hasOneThrough(
            Country::class,
            State::class,
            'id',          // Local key on states table
            'id',          // Local key on countries table
            'state_id',    // Foreign key on cities table
            'country_id'   // Foreign key on states table
        );
    }

    public function state()
    {
        return $this->belongsTo(State::class);
    }

    public function companies()
    {
        return $this->hasMany(Company::class, 'city_id');
    }


}
