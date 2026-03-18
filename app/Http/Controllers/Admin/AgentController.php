<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreAgentRequest;
use App\Http\Requests\Admin\UpdateAgentRequest;
use App\Models\Agent;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AgentController extends Controller
{
    /**
     * Display a listing of the agents.
     */
    public function index(Request $request): View
    {
        $perPage = (int) $request->get('per_page', 15);

        $agents = Agent::orderBy('name')->paginate($perPage);

        return view('admin.agents.index', compact('agents'));
    }

    /**
     * Show the form for creating a new agent.
     */
    public function create(): View
    {
        $statuses = Agent::statuses();

        return view('admin.agents.create', compact('statuses'));
    }

    /**
     * Store a newly created agent in storage.
     */
    public function store(StoreAgentRequest $request): RedirectResponse
    {
        $data = $request->validated();

        Agent::create($data);

        return redirect()->route('admin.agents.index')
            ->with('success', 'Agent created successfully.');
    }

    /**
     * Display the specified agent.
     */
    public function show(Agent $agent): View
    {
        return view('admin.agents.show', compact('agent'));
    }

    /**
     * Show the form for editing the specified agent.
     */
    public function edit(Agent $agent): View
    {
        $statuses = Agent::statuses();

        return view('admin.agents.edit', compact('agent', 'statuses'));
    }

    /**
     * Update the specified agent in storage.
     */
    public function update(UpdateAgentRequest $request, Agent $agent): RedirectResponse
    {
        $data = $request->validated();

        $agent->update($data);

        return redirect()->route('admin.agents.index')
            ->with('success', 'Agent updated successfully.');
    }

    /**
     * Remove the specified agent from storage.
     */
    public function destroy(Agent $agent): RedirectResponse
    {
        $agent->delete();

        return redirect()->route('admin.agents.index')
            ->with('success', 'Agent deleted successfully.');
    }
}
