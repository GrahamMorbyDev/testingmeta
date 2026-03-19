<?php /**
 * Activity index page
 * Integrates a Livewire component for listing / filtering activities.
 * Assumes the app layout provides Livewire styles/scripts (layouts.app is typical).
 */ ?>

@extends('layouts.app')

@section('title', 'Activity')

@section('content')
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white shadow-sm sm:rounded-lg p-6">
            <h1 class="text-2xl font-semibold text-gray-800 mb-4">System Activity</h1>

            {{-- Livewire component handles the listing, filtering, pagination and metadata modal --}}
            @livewire('activity-list')
        </div>
    </div>
@endsection
