<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class State extends Model
{
    //
    protected $fillable = ['name', 'country_id', 'slug', 'statecode'];

public function country(){
        return $this->belongsTo(Country::class, 'country_id');
    }
    public function cities(){
        return $this->hasMany(City::class, 'state_id');
    }

    public function companies()
{
    return $this->hasManyThrough(
        Company::class,
        City::class,
        'state_id',   // Foreign key on cities table
        'city_id',    // Foreign key on companies table
        'id',         // Local key on states table
        'id'          // Local key on cities table
    );
}
}
