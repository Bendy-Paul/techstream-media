<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $table = 'categories';
    public $timestamps = false;

    protected $fillable = [
        'name',
        'slug',
        'parent_id',
        'type',
        'icon_class',
    ];

    public function children()
    {
        return $this->hasMany(Category::class, 'parent_id');
    }

    public function parent()
    {
        return $this->belongsTo(Category::class, 'parent_id');
    }

    public function companies()
    {
        return $this->belongsToMany(Company::class, 'company_categories', 'category_id', 'company_id');
    }

    public function getCompanies()
    {
        return $this->companies()->where('status', 'active')->get();
    }

    public function getFeaturedCompanies()
    {
        return $this->companies()
            ->where('status', 'active')
            ->where('is_featured', 1)
            ->withCount('projects') // Count projects for each company
            ->get();
    }

    public function events()
    {
        return $this->belongsToMany(Event::class, EventCategories::class, 'category_id', 'event_id');
    }
}
