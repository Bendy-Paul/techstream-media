<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable implements MustVerifyEmail
{
    const ROLE_ADMIN   = 'admin';
    const ROLE_COMPANY = 'company';
    const ROLE_USER    = 'user';
    const ROLE_EVENT_ORGANIZER = 'event_organizer';
    
    public function sendEmailVerificationNotification()
    {
        $this->notify(new \App\Notifications\QueuedVerifyEmail);
    }

    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'phone_number',
        'role',
        'subscription_tier',
        'password',
        'is_subscribed_newsletter',
        'is_subscribed_job_board',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_subscribed_newsletter' => 'boolean',
            'is_subscribed_job_board' => 'boolean',
        ];
    }

    /**
     * Check if the user is a premium member.
     */
    public function isPremium(): bool
    {
        return $this->subscription_tier === 'premium';
    }

    /**
     * Get the resume limit based on subscription tier.
     */
    public function getResumeLimitAttribute(): int
    {
        return $this->isPremium() ? 5 : 1;
    }

    /**
     * Get the user's resumes.
     */
    public function resumes()
    {
        return $this->hasMany(Resume::class);
    }

    /**
     * Get the user's saved items.
     */
    public function savedItems()
    {
        return $this->hasMany(SavedItem::class);
    }

    /**
     * Check if the user has saved a specific item.
     */
    public function hasSaved($item)
    {
        return $this->savedItems()
            ->where('item_id', $item->id)
            ->where('item_type', get_class($item))
            ->exists();
    }

    /**
     * Get the user's reviews.
     */
    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    /**
     * Get the user's job applications.
     */
    public function applications()
    {
        return $this->hasMany(JobApplication::class);
    }

    /**
     * Get the user's activities.
     */
    public function activities()
    {
        return $this->hasMany(Activity::class);
    }
    /**
     * Get the user's organizer profiles.
     */
    public function organizers()
    {
        return $this->morphMany(Organizer::class, 'owner');
    }

    /**
     * Get the user's company profile.
     */
    public function company()
    {
        return $this->hasOne(Company::class);
    }
}
