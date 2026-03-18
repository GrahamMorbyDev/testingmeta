<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Minimal Agent model to express relation to Task.
 * If an Agent model already exists in your application, merge the tasks() relation there instead of duplicating.
 */
class Agent extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function tasks()
    {
        return $this->hasMany(Task::class);
    }
}
