<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\WorkspaceController;
use Illuminate\Support\Facades\Route;

$centralDomain = config('forgebase.central_subdomain').'.'.config('forgebase.root_domain');

Route::domain($centralDomain)->group(function (): void {
    Route::get('/', function () {
        return view('welcome');
    });

    Route::get('/dashboard', function () {
        return view('dashboard');
    })->middleware(['auth', 'verified'])->name('dashboard');

    Route::middleware('auth')->group(function (): void {
        Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
        Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
        Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    });

    Route::middleware('auth')->group(function (): void {
        Route::get('/workspaces', [WorkspaceController::class, 'index'])->name('workspaces.index');
        Route::post('/workspaces/{tenant}/select', [WorkspaceController::class, 'select'])->name('workspaces.select');
    });

    require __DIR__.'/auth.php';
});
