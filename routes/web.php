<?php

use App\Http\Controllers\AcceptInvitationController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TeamController;
use App\Http\Controllers\TenantInvitationController;
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

    Route::get('/invitations/accept/{token}', AcceptInvitationController::class)
        ->middleware('signed')
        ->name('invitations.accept');

    Route::middleware('auth')->group(function (): void {
        Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
        Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
        Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    });

    Route::middleware('auth')->group(function (): void {
        Route::get('/workspaces', [WorkspaceController::class, 'index'])->name('workspaces.index');
        Route::post('/workspaces/{tenant}/select', [WorkspaceController::class, 'select'])->name('workspaces.select');
        Route::get('/workspaces/{tenant}/team', [TeamController::class, 'index'])
            ->middleware('feature:team_invites')
            ->name('workspaces.team');
        Route::post('/workspaces/{tenant}/invitations', [TenantInvitationController::class, 'store'])
            ->middleware('feature:team_invites')
            ->name('workspaces.invitations.store');
        Route::post('/workspaces/{tenant}/invitations/{invitation}/resend', [TenantInvitationController::class, 'resend'])
            ->middleware('feature:team_invites')
            ->name('workspaces.invitations.resend');
        Route::delete('/workspaces/{tenant}/invitations/{invitation}', [TenantInvitationController::class, 'destroy'])
            ->middleware('feature:team_invites')
            ->name('workspaces.invitations.destroy');
    });

    require __DIR__.'/auth.php';
});
