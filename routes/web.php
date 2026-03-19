<?php

use Illuminate\Support\Facades\Route;

// Include the run failure routes if present. This avoids overwriting all
// application routes while ensuring the new endpoints are available.
if (file_exists(__DIR__ . '/run_failures.php')) {
    require __DIR__ . '/run_failures.php';
}

// A minimal root route to keep the file valid. Replace/extend this in the
// main application as needed.
Route::get('/', function () {
    return response('Application routes');
});
