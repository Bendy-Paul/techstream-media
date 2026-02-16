<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use App\Traits\RecordsActivity;

class Company extends Model
{
    use RecordsActivity;
    //
    protected $fillable = [
        'name',
        'slug',
        'user_id',
        'description',
        'tagline',
        'website',
        'email',
        'phone',
        'address',
        'city_id',
        'category_id',
        'logo_url',
        'cover_image_url',
        'is_verified',
        'is_featured',
        'subscription_tier',
        'team_size',
        'starting_cost',
        // Add other fields as necessary
    ];

    public function city()
    {
        return $this->belongsTo(City::class);
    }

    public function branches()
{
    return $this->hasMany(Branch::class, 'company_id'); 
}
    public function stacks()
{
    return $this->belongsToMany(Tools::class, 'company_stacks', 'company_id', 'stack_id'); 
}

// If a company has ONE category (e.g. column 'category_id' in companies table)
public function categories()
{
    return $this->belongsToMany(Category::class, 'company_categories', 'company_id', 'category_id');
}

public function projects()
{
    return $this->hasMany(CompanyProject::class, 'company_id');
}

public function gallery()
    {
        return $this->hasMany(CompanyGallery::class, 'company_id');
    }

    public function reviews()
    {
        return $this->morphMany(Review::class, 'item');
    }

    public function savedBy()
    {
        return $this->morphMany(SavedItem::class, 'item');
    }

    public function jobs()
    {
        return $this->hasMany(Job::class, 'company_id');
    }
}
