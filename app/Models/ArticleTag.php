<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ArticleTag extends Model
{
    //
    public function articles()
    {
        return $this->belongsTo(Article::class, 'tag_id', 'id');
    }
}
