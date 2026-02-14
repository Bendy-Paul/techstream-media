<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tools extends Model
{
    //
    protected $table = 'stacks';


    protected $fillable = [
        'name',
        'category',
        'icon_class',
    ];
}
