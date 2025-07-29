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
        'feedback'
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
}
