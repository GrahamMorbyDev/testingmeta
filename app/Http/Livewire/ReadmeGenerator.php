<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Http;

class ReadmeGenerator extends Component
{
    public $project_name;
    public $description;
    public $generated_readme;
    public $status = 'idle';
    public $error;

    protected $rules = [
        'description' => 'required|string|min:10',
        'project_name' => 'nullable|string',
    ];

    public function generate()
    {
        $this->validate();

        $this->status = 'pending';
        $this->generated_readme = null;
        $this->error = null;

        try {
            // Call the backend controller endpoint as defined in the contract
            $response = Http::post(url('/readme-generate'), [
                'project_name' => $this->project_name,
                'description' => $this->description,
            ]);

            if ($response->successful()) {
                $data = $response->json();

                // Backend returns 'generated_readme' and 'status' per contract
                $this->generated_readme = $data['generated_readme'] ?? ($data['generatedReadme'] ?? null);
                $this->status = $data['status'] ?? 'completed';
                $this->emit('readmeGenerated', $data['id'] ?? null);
            } else {
                $this->status = 'failed';
                $this->error = $response->json('message') ?? 'Failed to generate README';
            }
        } catch (\Throwable $e) {
            $this->status = 'failed';
            $this->error = $e->getMessage();
        }
    }

    public function render()
    {
        return view('livewire.readme-generator');
    }
}
