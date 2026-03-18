<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Agent extends Model
{
    use HasFactory;

    // Fillable attributes for mass assignment
    protected $fillable = [
        'name',
        'email',
        'phone_number',
        'company',
        'status',
    ];

    // Default attributes
    protected $attributes = [
        'status' => 'pending',
    ];

    // Optionally cast fields
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    // Allowed status options
    public const STATUS_ACTIVE = 'active';
    public const STATUS_INACTIVE = 'inactive';
    public const STATUS_PENDING = 'pending';

    public static function statuses(): array
    {
        return [
            self::STATUS_ACTIVE,
            self::STATUS_INACTIVE,
            self::STATUS_PENDING,
        ];
    }
}
