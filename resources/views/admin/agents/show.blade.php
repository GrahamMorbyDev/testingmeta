@extends('layouts.admin')

@section('title', 'Agent Details')

@section('content')
    <div class="mb-6 flex items-center justify-between">
        <h1 class="text-2xl font-semibold">{{ $agent->name }}</h1>
        <div class="space-x-2">
            <a href="{{ route('admin.agents.edit', $agent) }}" class="px-3 py-2 bg-indigo-600 text-white rounded">Edit</a>
            <a href="{{ route('admin.agents.index') }}" class="px-3 py-2 bg-gray-200 rounded">Back</a>
        </div>
    </div>

    <div class="bg-white shadow sm:rounded-lg p-6">
        <dl class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-sm text-gray-700">
            <div>
                <dt class="font-medium text-gray-500">Name</dt>
                <dd class="mt-1">{{ $agent->name }}</dd>
            </div>
            <div>
                <dt class="font-medium text-gray-500">Email</dt>
                <dd class="mt-1">{{ $agent->email }}</dd>
            </div>
            <div>
                <dt class="font-medium text-gray-500">Phone</dt>
                <dd class="mt-1">{{ $agent->phone_number ?? '-' }}</dd>
            </div>
            <div>
                <dt class="font-medium text-gray-500">Company</dt>
                <dd class="mt-1">{{ $agent->company ?? '-' }}</dd>
            </div>
            <div>
                <dt class="font-medium text-gray-500">Status</dt>
                <dd class="mt-1">{{ ucfirst($agent->status) }}</dd>
            </div>
            <div>
                <dt class="font-medium text-gray-500">Created</dt>
                <dd class="mt-1 text-sm text-gray-500">{{ $agent->created_at->toDayDateTimeString() }}</dd>
            </div>
        </dl>
    </div>
@endsection
