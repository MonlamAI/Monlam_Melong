<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EntryActivityLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'entry_id', 'action', 'word_delta', 'category', 'occurred_at'
    ];

    protected $casts = [
        'occurred_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function entry()
    {
        return $this->belongsTo(MonlamMelongFinetuning::class, 'entry_id');
    }
}
