<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Activity extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id',
        'action',
        'metadata',
        'ip',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'metadata' => 'array',
    ];

    /**
     * Relationship: activity belongs to a user (nullable).
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Record an activity in a consistent manner.
     *
     * Usage examples:
     *   Activity::record('project.created', ['project_id' => $id]);
     *   Activity::record('user.login', null, $user);
     *
     * @param  string  $action
     * @param  array|null  $metadata
     * @param  mixed  $user  (User model, user id, or null for current auth user)
     * @param  string|null  $ip
     * @return static
     */
    public static function record($action, $metadata = null, $user = null, $ip = null)
    {
        if ($user instanceof \Illuminate\Contracts\Auth\Authenticatable) {
            $userId = $user->getAuthIdentifier();
        } elseif (is_numeric($user)) {
            $userId = (int) $user;
        } elseif (auth()->check()) {
            $userId = auth()->id();
        } else {
            $userId = null;
        }

        $ip = $ip ?? (request()->hasSession() || request() ? request()->ip() : null);

        return static::create([
            'user_id' => $userId,
            'action' => (string) $action,
            'metadata' => $metadata ?: null,
            'ip' => $ip,
        ]);
    }
}
