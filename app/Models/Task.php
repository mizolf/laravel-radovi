<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Task extends Model
{
    protected $fillable = [
        'user_id',
        'title',
        'title_en',
        'description',
        'study_type',
    ];

    /**
     * Get the user (nastavnik) that created this task
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
