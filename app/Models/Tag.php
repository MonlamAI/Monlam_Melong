<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\MonlamMelongFinetuning;

class Tag extends Model
{
    use HasFactory;
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'tibetan_name',
        'description',
    ];

    /**
     * The entries that belong to this tag
     */
    public function entries()
    {
        return $this->belongsToMany(MonlamMelongFinetuning::class, 'entry_tag', 'tag_id', 'entry_id');
    }
}
