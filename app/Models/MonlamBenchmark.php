<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MonlamBenchmark extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'monlam_benchmark';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<string>
     */
    protected $fillable = [
        'subject',
        'question_type',
        'question_text',
        'answer_option1',
        'answer_option2',
        'answer_option3',
        'answer_option4',
        'correct_answer',
        'explanation',
        'difficulty_level',
        'category',
        'tags',
        'is_active',
        'created_by',
        'updated_by'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'is_active' => 'boolean',
    ];
}
