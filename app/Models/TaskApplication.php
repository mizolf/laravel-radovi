<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TaskApplication extends Model
{
    protected $fillable = [
        'task_id',
        'user_id',
        'status',
    ];

    /**
     * Get the task that this application is for
     */
    public function task(): BelongsTo
    {
        return $this->belongsTo(Task::class);
    }

    /**
     * Get the user (student) who applied
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
