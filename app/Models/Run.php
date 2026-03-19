<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Services\RunFailureAnalyzer;

class Run extends Model
{
    protected $table = 'runs';

    protected $fillable = [
        'status',
        'failure_reason',
        'failure_suggestions',
        'last_failed_at',
    ];

    protected $casts = [
        'failure_suggestions' => 'array',
        'last_failed_at' => 'datetime',
    ];

    /**
     * Return an analyzed failure payload for this run.
     * The analyzer is intentionally lightweight for the first iteration and
     * returns a reason and a list of actionable suggestions. The analyzer can
     * be replaced/extended later for richer diagnostics.
     *
     * @return array{reason:string, suggestions:array, confidence:float}
     */
    public function analyzeFailure(): array
    {
        return (new RunFailureAnalyzer())->analyze($this);
    }
}
