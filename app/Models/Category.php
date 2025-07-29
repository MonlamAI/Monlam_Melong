<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\MonlamMelongFinetuning;

class Category extends Model
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
        'is_predefined',
        'description',
    ];

    /**
     * Get the entries for this category
     */
    public function entries()
    {
        return $this->hasMany(MonlamMelongFinetuning::class, 'category_id');
    }
}
