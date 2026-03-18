@extends('layouts.admin')

@section('title', 'Create Agent')

@section('content')
    <div class="mb-6">
        <h1 class="text-2xl font-semibold">Create Agent</h1>
    </div>

    <div class="bg-white shadow sm:rounded-lg">
        @include('admin.agents.partials.form', ['statuses' => $statuses])
    </div>
@endsection
