<?php

namespace App\Http\Livewire\Admin;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Agent;

class AgentsIndex extends Component
{
    use WithPagination;

    public $search = '';
    public $perPage = 15;
    public $confirmingDeleteId = null;

    protected $queryString = ['search', 'perPage'];

    protected $listeners = ['refreshAgents' => '$refresh'];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingPerPage()
    {
        $this->resetPage();
    }

    public function confirmDelete($id)
    {
        $this->confirmingDeleteId = $id;
    }

    public function cancelDelete()
    {
        $this->confirmingDeleteId = null;
    }

    public function deleteAgent($id)
    {
        $agent = Agent::find($id);

        if (!$agent) {
            session()->flash('success', 'Agent not found.');
            $this->confirmingDeleteId = null;
            return;
        }

        $agent->delete();

        session()->flash('success', 'Agent deleted successfully.');
        $this->confirmingDeleteId = null;
        $this->emit('refreshAgents');
    }

    public function render()
    {
        $query = Agent::query();

        if ($this->search) {
            $query->where(fn($q) => $q->where('name', 'like', "%{$this->search}%")
                    ->orWhere('email', 'like', "%{$this->search}%")
                    ->orWhere('company', 'like', "%{$this->search}%")
            );
        }

        $agents = $query->orderBy('name')->paginate($this->perPage);

        return view('livewire.admin.agents-index', ['agents' => $agents]);
    }
}
