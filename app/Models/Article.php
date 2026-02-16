<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use App\Traits\RecordsActivity;

class Article extends Model
{
    use RecordsActivity;
    //
    protected $fillable = [
        'title',
        'author_id',
        'slug',
        'content',
        'featured_image_url',
        'is_featured',
        'status'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'author_id');
    }

    public function companies()
    {
        return $this->belongsToMany(Company::class, CompanyArticle::class, 'article_id', 'company_id');
    }


    public function categories()
    {
        return $this->belongsToMany(Category::class, ArticleCategory::class, 'article_id', 'category_id');
    }

    public function tags()
    {
        return $this->belongsToMany(
            Tag::class,
            'article_tags', // pivot table
            'article_id',  // foreign key on pivot for Article
            'tag_id'       // foreign key on pivot for Tag
        );
    }
    public function events()
    {
        return $this->belongsToMany(Event::class, ArticleEvent::class, 'article_id', 'event_id');
    }
}
