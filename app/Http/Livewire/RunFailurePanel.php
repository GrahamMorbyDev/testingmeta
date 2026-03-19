<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Run;

/**
 * Livewire component that renders a failure-recovery panel for a Run.
 *
 * The component itself is intentionally lightweight and delegates the
 * analysis and retry actions to the backend endpoints introduced in the
 * previous step. The UI uses Alpine for client-side interactivity so the
 * component can be embedded into existing Blade pages with minimal fuss.
 */
class RunFailurePanel extends Component
{
    /** @var Run */
    public Run $run;

    public function mount(Run $run)
    {
        $this->run = $run;
    }

    public function render()
    {
        return view('livewire.run-failure-panel');
    }
}
