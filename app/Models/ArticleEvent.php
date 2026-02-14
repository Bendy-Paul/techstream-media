<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ArticleEvent extends Model
{
    //
    protected $table = 'article_events';

    protected $fillable = [
        'article_id',
        'event_id'
    ];

    public function article()
    {
        return $this->belongsToMany(Article::class, 'article_id');
    }
    public function event()
    {
        return $this->belongsToMany(Event::class, 'event_id');
    }
}
