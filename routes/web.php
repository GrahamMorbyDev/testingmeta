<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ActivityController;

// Activity routes: listing and recording
Route::middleware(['auth'])->group(function () {
    Route::get('activities', [ActivityController::class, 'index'])->name('activities.index');
    Route::post('activities', [ActivityController::class, 'store'])->name('activities.store');
});
