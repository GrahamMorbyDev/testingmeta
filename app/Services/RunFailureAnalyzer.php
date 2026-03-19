<?php

namespace App\Services;

use App\Models\Run;

/**
 * Service responsible for converting a failed run into a human-friendly
 * explanation and set of actionable suggestions.
 *
 * The initial implementation is intentionally conservative: it prefers stored
 * analysis if present, otherwise applies simple heuristics and a helpful
 * generic fallback. This keeps the feature useful immediately while allowing
 * later extension (log analysis, integration with diagnostics, etc.).
 */
class RunFailureAnalyzer
{
    /**
     * Analyze a run and produce a simple structured response.
     *
     * Keys:
     *  - reason: short explanation of what failed
     *  - suggestions: array of actionable next steps
     *  - confidence: 0.0-1.0 indicating how confident this analysis is
     */
    public function analyze(Run $run): array
    {
        // If the run already has stored analysis, return that first.
        if (!empty($run->failure_reason)) {
            return [
                'reason' => $run->failure_reason,
                'suggestions' => $run->failure_suggestions ?? [],
                'confidence' => 0.9,
            ];
        }

        // If the run status isn't 'failed', provide a neutral response.
        if (strtolower($run->status ?? '') !== 'failed') {
            return [
                'reason' => 'Run has not failed.',
                'suggestions' => ['No action required.'],
                'confidence' => 0.0,
            ];
        }

        // Basic heuristic attempts. If your runs include fields like exit_code,
        // logs, or error_type, extend this block to inspect them.
        $suggestions = [];
        $reason = 'The run failed during execution.';
        $confidence = 0.4;

        // Attempt to use common fields if they exist on the model.
        if (isset($run->exit_code) && $run->exit_code !== null) {
            $code = (int) $run->exit_code;

            if ($code === 137) {
                $reason = 'The run was terminated (possibly out of memory).';
                $suggestions = [
                    'Increase memory or resource limits for the job.',
                    'Try splitting the job into smaller steps.',
                    'Review recent changes that may use more memory than expected.',
                ];
                $confidence = 0.8;
            } elseif ($code === 0) {
                $reason = 'Exit code 0 reported but run marked failed — check system logs.';
                $suggestions = ['Inspect the run logs for non-zero errors.'];
                $confidence = 0.5;
            } else {
                $reason = "Process exited with code {$code}.";
                $suggestions = [
                    'Inspect the run logs to find the failing step or error message.',
                    'Check dependencies and environment configuration.',
                ];
                $confidence = 0.6;
            }
        } else {
            // Generic helpful suggestions
            $reason = 'Execution failed; logs or diagnostics were not available for automated analysis.';
            $suggestions = [
                'Open the run logs to identify the failing step or error message.',
                'Check configuration (credentials, environment variables, resource quotas).',
                'Try re-running the job after addressing obvious issues.',
                'If the problem persists, collect logs and contact support with the run ID.',
            ];
            $confidence = 0.4;
        }

        return [
            'reason' => $reason,
            'suggestions' => $suggestions,
            'confidence' => $confidence,
        ];
    }
}
