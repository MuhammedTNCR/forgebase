<?php

use App\Http\Controllers\ProjectController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('workspaces.welcome');
});

Route::middleware(['auth', 'feature:projects'])->group(function (): void {
    Route::resource('projects', ProjectController::class)->except('show');
});
