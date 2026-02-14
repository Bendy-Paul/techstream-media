<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ArticleCategory extends Model
{
    //
    public function articles()
    {
        return $this->belongsTo(Article::class, 'category_id', 'id');
    }
}
