<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     * Add 'notes' so it can be persisted via mass assignment where appropriate.
     * Adjust this list if your application uses $guarded instead.
     *
     * @var array
     */
    protected $fillable = [
        'user_id',
        'status',
        'total',
        'notes',
    ];

    /**
     * Optionally define casts or accessors for notes in the future.
     */
}
