<?php

namespace App\Http\Livewire;

use Livewire\Component;

class TaskPriorityBadge extends Component
{
    public $priority;

    public function mount($priority)
    {
        $this->priority = $priority;
    }

    public function render()
    {
        return view('livewire.task-priority-badge');
    }
}
