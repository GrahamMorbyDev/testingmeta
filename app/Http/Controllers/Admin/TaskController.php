<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreTaskRequest;
use App\Http\Requests\UpdateTaskRequest;
use App\Models\Agent;
use App\Models\Task;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class TaskController extends Controller
{
    public function index(Request $request): View
    {
        $tasks = Task::with('agent')->latest()->paginate(20);

        return view('admin.tasks.index', compact('tasks'));
    }

    public function create(): View
    {
        // Agents for dropdown selection
        $agents = Agent::orderBy('name')->pluck('name', 'id');

        return view('admin.tasks.create', compact('agents'));
    }

    public function store(StoreTaskRequest $request): RedirectResponse
    {
        Task::create($request->validated());

        return redirect()->route('admin.tasks.index')
            ->with('success', 'Task created successfully.');
    }

    public function show(Task $task): View
    {
        $task->load('agent');

        return view('admin.tasks.show', compact('task'));
    }

    public function edit(Task $task): View
    {
        $agents = Agent::orderBy('name')->pluck('name', 'id');

        return view('admin.tasks.edit', compact('task', 'agents'));
    }

    public function update(UpdateTaskRequest $request, Task $task): RedirectResponse
    {
        $task->update($request->validated());

        return redirect()->route('admin.tasks.index')
            ->with('success', 'Task updated successfully.');
    }

    public function destroy(Task $task): RedirectResponse
    {
        $task->delete();

        return redirect()->route('admin.tasks.index')
            ->with('success', 'Task deleted successfully.');
    }
}
