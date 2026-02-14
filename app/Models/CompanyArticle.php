<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CompanyArticle extends Model
{
    //
    protected $table = 'article_companies';
    public function article()
    {
        return $this->belongsTo(Article::class, 'article_id');
    }

    public function company()
    {
        return $this->belongsTo(Company::class, 'company_id');
    }
}
