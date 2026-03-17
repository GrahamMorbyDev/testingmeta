<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'status',
    ];

    /**
     * Allowed statuses for a project.
     * Keep as a method so other parts of the app can reuse.
     */
    public static function statuses(): array
    {
        return ['pending', 'active', 'completed', 'archived'];
    }
}
