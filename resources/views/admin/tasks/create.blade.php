@extends('layouts.admin')

@section('title', 'Create Task')
@section('page_title', 'Create Task')

@section('content')
<div class="bg-white p-6 rounded shadow">
    <form action="{{ route('admin.tasks.store') }}" method="POST">
        @csrf

        @include('admin.tasks._form', ['task' => null, 'agents' => $agents])

        <div class="mt-4 flex items-center">
            <a href="{{ route('admin.tasks.index') }}" class="mr-3 text-sm text-gray-600">Cancel</a>
            <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded">Create Task</button>
        </div>
    </form>
</div>
@endsection
