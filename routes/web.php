<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ReadmeController;

/*
|--------------------------------------------------------------------------
| Readme Generator Routes
|--------------------------------------------------------------------------
|
| Routes for the One-Click README Generator backend endpoints.
|
*/

Route::post('/readme-generate', [ReadmeController::class, 'generate']);
Route::get('/readme/{id}', [ReadmeController::class, 'show']);
