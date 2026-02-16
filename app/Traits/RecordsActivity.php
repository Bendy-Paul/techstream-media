<?php

namespace App\Traits;

use App\Models\Activity;
use Illuminate\Support\Facades\Auth;

trait RecordsActivity
{
    /**
     * Boot the trait.
     */
    protected static function bootRecordsActivity()
    {
        if (auth()->guest()) return;

        foreach (static::getActivitiesToRecord() as $event) {
            static::$event(function ($model) use ($event) {
                $model->recordActivity($event);
            });
        }
    }

    /**
     * Get the events to record.
     *
     * @return array
     */
    protected static function getActivitiesToRecord()
    {
        return ['created', 'updated', 'deleted'];
    }

    /**
     * Record the activity.
     *
     * @param string $event
     */
    protected function recordActivity($event)
    {
        if (!auth()->check()) return;

        $type = $this->getActivityType($event);
        $user_id = auth()->id();
        $subject_id = $this->id;
        $subject_type = get_class($this);

        // Check for existing activity within last 30 minutes
        $existingActivity = Activity::where('user_id', $user_id)
            ->where('type', $type)
            ->where('subject_type', $subject_type)
            ->where('subject_id', $subject_id)
            ->where('updated_at', '>', now()->subMinutes(30))
            ->first();

        if ($existingActivity) {
            $existingActivity->touch();
            return;
        }

        Activity::create([
            'user_id' => $user_id,
            'type' => $type,
            'subject_id' => $subject_id,
            'subject_type' => $subject_type,
            'meta' => $this->getActivityMeta(),
        ]);
    }

    /**
     * Get the activity type name.
     *
     * @param string $event
     * @return string
     */
    protected function getActivityType($event)
    {
        $class = strtolower(class_basename($this));
        
        // Map specific events if needed
        if ($class === 'jobapplication' && $event === 'created') {
            return 'job_applied';
        }
        
        if ($class === 'saveditem' && $event === 'created') {
            // We might want to be more specific here if possible, 
            // e.g. job_saved, event_saved depending on the item_type
            if ($this->item_type === \App\Models\Job::class) {
                return 'job_saved';
            }
            if ($this->item_type === \App\Models\Event::class) {
                return 'event_saved';
            }
            if ($this->item_type === \App\Models\Company::class) {
                return 'company_saved';
            }
            return 'item_saved';
        }

        return "{$class}_{$event}";
    }

    /**
     * Get metadata for the activity.
     */
    /**
     * Get metadata for the activity.
     */
    protected function getActivityMeta()
    {
        if ($this instanceof \App\Models\SavedItem) {
            $item = $this->item;
            if ($item) {
                return [
                    'title' => $item->title ?? $item->name ?? 'Unknown Item',
                    'slug' => $item->slug ?? null,
                    'item_type' => class_basename($item),
                ];
            }
        }

        if ($this instanceof \App\Models\JobApplication) {
            $job = $this->job;
            if ($job) {
                return [
                    'job_title' => $job->title,
                    'job_slug' => $job->slug,
                    'company_name' => $job->company->name ?? 'Unknown Company',
                ];
            }
        }

        if ($this instanceof \App\Models\Job && $this->wasRecentlyCreated) {
             return [
                 'title' => $this->title,
                 'slug' => $this->slug,
             ];
        }

        return null;
    }
}
