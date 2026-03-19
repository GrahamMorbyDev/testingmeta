<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Activity;

class ActivityList extends Component
{
    use WithPagination;

    // Filterable fields
    public $user_id = '';
    public $action = '';
    public $date_from = '';
    public $date_to = '';
    public $perPage = 20;

    // Modal state for viewing metadata
    public $showModal = false;
    public $modalMetadata = null;

    protected $queryString = [
        'user_id' => ['except' => ''],
        'action' => ['except' => ''],
        'date_from' => ['except' => ''],
        'date_to' => ['except' => ''],
    ];

    protected $updatesQueryString = ['user_id', 'action', 'date_from', 'date_to'];

    // Reset pagination when filters update
    public function updating($name, $value)
    {
        if (in_array($name, ['user_id', 'action', 'date_from', 'date_to', 'perPage'])) {
            $this->resetPage();
        }
    }

    public function showMetadata($id)
    {
        $activity = Activity::find($id);
        if (! $activity) {
            $this->modalMetadata = null;
            $this->showModal = true;
            return;
        }

        $this->modalMetadata = $activity->metadata ? json_encode($activity->metadata, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) : null;
        $this->showModal = true;
    }

    public function closeModal()
    {
        $this->showModal = false;
        $this->modalMetadata = null;
    }

    public function render()
    {
        $query = Activity::with('user')->orderBy('created_at', 'desc');

        if ($this->user_id !== '') {
            $query->where('user_id', $this->user_id);
        }

        if ($this->action !== '') {
            $query->where('action', $this->action);
        }

        if (! empty($this->date_from)) {
            $query->whereDate('created_at', '>=', $this->date_from);
        }

        if (! empty($this->date_to)) {
            $query->whereDate('created_at', '<=', $this->date_to);
        }

        $activities = $query->paginate($this->perPage);

        return view('livewire.activity-list', [
            'activities' => $activities,
        ]);
    }
}
