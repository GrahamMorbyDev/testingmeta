@extends('layouts.admin')

@section('title', 'Edit Agent')

@section('content')
    <div class="mb-6">
        <h1 class="text-2xl font-semibold">Edit Agent</h1>
    </div>

    <div class="bg-white shadow sm:rounded-lg">
        @include('admin.agents.partials.form', ['agent' => $agent, 'statuses' => $statuses])
    </div>
@endsection
