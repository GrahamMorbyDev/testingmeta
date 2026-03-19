<?php

namespace App\Jobs;

use App\Models\Run;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class RetryRunJob implements ShouldQueue
{
    use InteractsWithQueue, Queueable, SerializesModels;

    /** @var Run */
    public $run;

    public function __construct(Run $run)
    {
        $this->run = $run;
    }

    /**
     * Handle the job. The minimal implementation marks the run as 'running'
     * and logs an informational message. Integrations with the actual run
     * execution system should be added here in the host application.
     */
    public function handle()
    {
        try {
            $this->run->status = 'running';
            $this->run->save();

            // Placeholder: integrate with the system that actually executes runs.
            Log::info('RetryRunJob started for run id: ' . $this->run->getKey());

            // Simulate work or hand off to the real executor.
            // After execution completes the real executor should update the run
            // status to 'success' or 'failed' as appropriate.
        } catch (\Throwable $e) {
            Log::error('RetryRunJob failed to start for run id: ' . $this->run->getKey() . ' - ' . $e->getMessage());

            // Mark as failed to reflect that the retry couldn't be scheduled.
            $this->run->status = 'failed';
            $this->run->failure_reason = 'Failed to schedule retry: ' . $e->getMessage();
            $this->run->save();

            throw $e;
        }
    }
}
