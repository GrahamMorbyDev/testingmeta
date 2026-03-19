<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ReadmeGeneration extends Model
{
    protected $table = 'readme_generations';

    protected $fillable = [
        'project_name',
        'description',
        'generated_readme',
        'status',
        'meta',
    ];

    protected $casts = [
        'meta' => 'array',
    ];
}
