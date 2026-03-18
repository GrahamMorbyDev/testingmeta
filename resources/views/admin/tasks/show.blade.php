@extends('layouts.admin')

@section('title', 'Task Details')
@section('page_title', 'Task Details')

@section('content')
<div class="bg-white p-6 rounded shadow">
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div>
            <h3 class="text-lg font-medium">{{ $task->title }}</h3>
            <p class="mt-2 text-sm text-gray-600">{{ $task->description ?? 'No description provided.' }}</p>

            <dl class="mt-4 text-sm text-gray-700">
                <div class="flex justify-between py-2 border-t">
                    <dt class="font-medium">Agent</dt>
                    <dd>{{ optional($task->agent)->name ?? '—' }}</dd>
                </div>
                <div class="flex justify-between py-2 border-t">
                    <dt class="font-medium">Due Date</dt>
                    <dd>{{ optional($task->due_date)->format('Y-m-d') ?? '—' }}</dd>
                </div>
                <div class="flex justify-between py-2 border-t">
                    <dt class="font-medium">Priority</dt>
                    <dd>
                        @if (class_exists(\Livewire\Livewire::class))
                            <livewire:task-priority-badge :priority="$task->priority" :wire:key="'show-priority-'.$task->id" />
                        @else
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $task->priority === 'high' ? 'bg-red-100 text-red-800' : ($task->priority === 'medium' ? 'bg-yellow-100 text-yellow-800' : 'bg-green-100 text-green-800') }}">{{ ucfirst($task->priority) }}</span>
                        @endif
                    </dd>
                </div>
                <div class="flex justify-between py-2 border-t">
                    <dt class="font-medium">Status</dt>
                    <dd>{{ str_replace('_', ' ', ucfirst($task->status)) }}</dd>
                </div>
            </dl>
        </div>

        <div class="flex flex-col gap-3">
            <a href="{{ route('admin.tasks.edit', $task) }}" class="px-4 py-2 bg-yellow-600 text-white rounded text-center">Edit</a>

            <div x-data>
                <form x-ref="form" method="POST" action="{{ route('admin.tasks.destroy', $task) }}">
                    @csrf
                    @method('DELETE')
                    <button type="button" @click.prevent="if (confirm('Delete this task?')) $refs.form.submit()" class="px-4 py-2 bg-red-600 text-white rounded">Delete</button>
                </form>
            </div>

            <a href="{{ route('admin.tasks.index') }}" class="px-4 py-2 bg-gray-200 text-gray-800 rounded text-center">Back to list</a>
        </div>
    </div>
</div>
@endsection
