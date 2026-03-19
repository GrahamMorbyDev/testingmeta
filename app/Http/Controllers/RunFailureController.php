<?php

namespace App\Http\Controllers;

use App\Models\Run;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use App\Jobs\RetryRunJob;

class RunFailureController extends Controller
{
    /**
     * Return an analysis payload for a failed run.
     * If the run already has stored failure_reason/suggestions those will be
     * preferred and returned to keep the UI stable. If not, a lightweight
     * analysis is computed and persisted.
     */
    public function show(Run $run)
    {
        $analysis = $run->analyzeFailure();

        // Persist the analysis if the run didn't have stored values. This
        // keeps subsequent requests consistent and gives a simple caching
        // behavior for the frontend.
        $shouldPersist = empty($run->failure_reason) || empty($run->failure_suggestions);

        if ($shouldPersist && isset($analysis['reason'])) {
            $run->failure_reason = $analysis['reason'];
            $run->failure_suggestions = $analysis['suggestions'] ?? [];
            // only set last_failed_at if the status indicates failure
            if (strtolower($run->status ?? '') === 'failed' && empty($run->last_failed_at)) {
                $run->last_failed_at = now();
            }
            $run->save();
        }

        return response()->json([
            'run_id' => $run->getKey(),
            'status' => $run->status,
            'analysis' => $analysis,
        ]);
    }

    /**
     * Attempt a retry of the run.
     * The first implementation marks the run as pending and dispatches a
     * background job. The job system (worker) should pick this up and execute
     * the actual retry logic.
     */
    public function retry(Request $request, Run $run)
    {
        // Only allow retrying runs that are failed or errored.
        $state = strtolower($run->status ?? '');

        if ($state !== 'failed' && $state !== 'error') {
            return response()->json(['message' => 'Run is not in a retryable failed state.'], 422);
        }

        // Reset metadata that may prevent a fresh retry.
        $run->status = 'pending';
        $run->failure_reason = null;
        $run->failure_suggestions = null;
        $run->save();

        // Dispatch a job to perform the actual retry. Implementations can be
        // made richer later (parameters, dry-run options, etc.).
        RetryRunJob::dispatch($run);

        return response()->json(['message' => 'Retry scheduled.', 'run_id' => $run->getKey()]);
    }
}
