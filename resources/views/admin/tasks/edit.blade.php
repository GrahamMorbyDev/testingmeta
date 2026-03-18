@extends('layouts.admin')

@section('title', 'Edit Task')
@section('page_title', 'Edit Task')

@section('content')
<div class="bg-white p-6 rounded shadow">
    <form action="{{ route('admin.tasks.update', $task) }}" method="POST">
        @csrf
        @method('PUT')

        @include('admin.tasks._form', ['task' => $task, 'agents' => $agents])

        <div class="mt-4 flex items-center">
            <a href="{{ route('admin.tasks.index') }}" class="mr-3 text-sm text-gray-600">Cancel</a>
            <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded">Update Task</button>
        </div>
    </form>
</div>
@endsection
