<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Category;
use App\Models\Tag;

class MonlamMelongFinetuning extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'monlam_melong_finetuning';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'question',
        'answer',
        'context',
        'reference',
        'category',
        'difficulty',
        'tags',
        'user_id',
        'status',
        'feedback',
        'status_updated_at',
        'status_edited_in_window'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'status_updated_at' => 'datetime',
    ];

    /**
     * Get the user that owns this entry.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the category that this entry belongs to.
     */
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * Get the tags associated with this entry.
     */
    public function tags()
    {
        return $this->belongsToMany(Tag::class, 'entry_tag', 'entry_id', 'tag_id');
    }

    /**
     * Check if the status can be changed within the 10-minute window.
     */
    public function canChangeStatus(): bool
    {
        // If status is not approved or rejected, allow changes
        if (!in_array($this->status, ['approved', 'rejected'])) {
            return true;
        }

        // If status has already been edited in the current window, don't allow changes
        if ($this->status_edited_in_window) {
            return false;
        }

        // For older entries without status_updated_at, use updated_at as fallback
        $statusChangeTime = $this->status_updated_at ?? $this->updated_at;
        
        // If no timestamp is available, allow changes (for very old entries)
        if (!$statusChangeTime) {
            return true;
        }

        // Check if 10 minutes have passed since status was last updated
        return $statusChangeTime->diffInMinutes(now()) < 10;
    }

    /**
     * Boot method to automatically update status_updated_at when status changes.
     */
    protected static function boot()
    {
        parent::boot();

        static::updating(function ($model) {
            // Check if status is being changed to approved or rejected
            if ($model->isDirty('status') && in_array($model->status, ['approved', 'rejected'])) {
                $model->status_updated_at = now();
            }
        });
    }
}
