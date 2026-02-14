<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Branch extends Model
{
    //
    protected $table = 'company_locations';
    public $timestamps = false;
    protected $fillable = [
        'company_id',
        'city_id',
        'address',
    ];

    public function city(){
        return $this->belongsTo(City::class, 'city_id', 'id');
    }
}
