@extends('layouts.admin')

@section('title', 'Tasks')
@section('page_title', 'Tasks')

@section('content')
<div class="flex items-center justify-between mb-6">
    <h2 class="text-lg font-medium text-gray-700">Tasks</h2>
    <a href="{{ route('admin.tasks.create') }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 text-white rounded hover:bg-indigo-700">+ Create Task</a>
</div>

<div class="bg-white shadow-sm rounded overflow-hidden">
    <table class="min-w-full divide-y divide-gray-200">
        <thead class="bg-gray-50">
            <tr>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Title</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Agent</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Due</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Priority</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                <th class="px-6 py-3"></th>
            </tr>
        </thead>
        <tbody class="bg-white divide-y divide-gray-200">
            @forelse($tasks as $task)
                <tr>
                    <td class="px-6 py-4 whitespace-nowrap">{{ $task->title }}</td>
                    <td class="px-6 py-4 whitespace-nowrap">{{ optional($task->agent)->name ?? '—' }}</td>
                    <td class="px-6 py-4 whitespace-nowrap">{{ optional($task->due_date)->format('Y-m-d') ?? '—' }}</td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        {{-- Livewire badge component for priority --}}
                        @if (class_exists(\Livewire\Livewire::class))
                            <livewire:task-priority-badge :priority="$task->priority" :wire:key="'priority-'.$task->id" />
                        @else
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $task->priority === 'high' ? 'bg-red-100 text-red-800' : ($task->priority === 'medium' ? 'bg-yellow-100 text-yellow-800' : 'bg-green-100 text-green-800') }}">{{ ucfirst($task->priority) }}</span>
                        @endif
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">{{ str_replace('_', ' ', ucfirst($task->status)) }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                        <a href="{{ route('admin.tasks.show', $task) }}" class="text-indigo-600 hover:text-indigo-900 mr-3">View</a>
                        <a href="{{ route('admin.tasks.edit', $task) }}" class="text-yellow-600 hover:text-yellow-800 mr-3">Edit</a>

                        <div x-data class="inline">
                            <form x-ref="form" method="POST" action="{{ route('admin.tasks.destroy', $task) }}">
                                @csrf
                                @method('DELETE')
                                <button type="button" @click.prevent="if (confirm('Are you sure you want to delete this task?')) $refs.form.submit()" class="text-red-600 hover:text-red-800">Delete</button>
                            </form>
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="px-6 py-8 text-center text-gray-500">No tasks found.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

<div class="mt-4">
    {{ $tasks->links() }}
</div>
@endsection
