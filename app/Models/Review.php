<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    //
    protected $fillable = [
        'user_id', 
        'item_id', 
        'item_type', 
        'rating', 
        'comment', 
        'is_approved'
    ];

    public function item()
    {
        return $this->morphTo();
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function scopeApproved($query)
    {
        return $query->where('is_approved', true);
    }
}
