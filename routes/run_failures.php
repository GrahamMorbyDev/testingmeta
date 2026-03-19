<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RunFailureController;

// Endpoints to support failure analysis and retrying runs.
Route::prefix('runs')->group(function () {
    Route::get('{run}/failure-analysis', [RunFailureController::class, 'show'])->name('runs.failure-analysis');
    Route::post('{run}/retry', [RunFailureController::class, 'retry'])->name('runs.retry');
});
