<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\AgentController;

/**
 * Admin routes - simple inclusion for agent CRUD.
 *
 * If your application groups admin routes differently (RouteServiceProvider, separate file, etc.)
 * move this route registration there. This file provides a minimal, conventional binding.
 */

Route::middleware(['web', 'auth'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {
        // Agent resource routes
        Route::resource('agents', AgentController::class);
    });

// You can add public/test/demo routes below if needed.
