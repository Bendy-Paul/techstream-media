<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use App\Traits\RecordsActivity;

class Event extends Model
{
    use RecordsActivity;
    //
    const STATUS_PUBLISHED = 'published';
    const STATUS_PENDING   = 'pending';
    const STATUS_DUPLICATE = 'duplicate';
    const STATUS_FLAGGED   = 'flagged';
    const STATUS_REJECTED  = 'rejected';
    const STATUS_ARCHIVED  = 'archived';

    protected $fillable = [
        'organizer_id',
        'event_status',
        'title',
        'slug',
        'description',
        'event_type',
        'location_name',
        'city_id',
        'is_virtual',
        'price',
        'ticket_url',
        'is_featured',
        'banner_image_url',
        'start_datetime',
        'end_datetime',
        'social_links',
        'community_links',
    ];

    public function categories()
    {
        return $this->belongsToMany(Category::class, EventCategories::class, 'event_id', 'category_id');
    }

    public function organizer()
    {
        return $this->belongsTo(Organizer::class);
    }

    public function coOrganizers()
    {
        return $this->belongsToMany(Company::class, 'event_organizers', 'event_id', 'company_id');
    }

    /**
     * Accessor for backward compatibility with views using $event->organizers.
     * Returns the collection of co-organizer companies.
     */
    public function getOrganizersAttribute()
    {
        return $this->coOrganizers;
    }

    public function partners()
    {
        return $this->belongsToMany(Company::class, EventPartner::class, 'event_id', 'company_id');
    }

    public function speakers()
    {
        return $this->hasMany(EventSpeaker::class);
    }

    public function galleries()
    {
        return $this->hasMany(EventGallery::class);
    }


    public function tags()
    {
        return $this->belongsToMany(Tag::class, 'event_tags', 'event_id', 'tag_id');
    }

    public function city()
    {
        return $this->belongsTo(City::class);
    }
    public function savedBy()
    {
        return $this->morphMany(SavedItem::class, 'item');
    }

    public function reviews()
    {
        return $this->morphMany(Review::class, 'item');
    }
}
