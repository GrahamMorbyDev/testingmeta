@extends('layouts.admin')

@section('title', 'Agents')

@section('content')
    <div class="flex items-center justify-between mb-6">
        <h1 class="text-2xl font-semibold">Agents</h1>
        <a href="{{ route('admin.agents.create') }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 text-white rounded hover:bg-indigo-700">Create Agent</a>
    </div>

    <div class="bg-white shadow sm:rounded-lg p-4">
        @livewire('admin.agents-index')
    </div>
@endsection
