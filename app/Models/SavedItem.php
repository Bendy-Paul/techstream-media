<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

use App\Traits\RecordsActivity;

class SavedItem extends Model
{
    use RecordsActivity;
    protected $fillable = ['user_id', 'item_id', 'item_type'];

    /**
     * Get the user that saved the item.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the saved item (Event, Company, Job, etc.).
     */
    public function item(): MorphTo
    {
        return $this->morphTo();
    }
}
