<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Mail\TenantInvitationMail;
use App\Models\Tenant;
use App\Models\TenantInvitation;
use App\Support\Teams\CreateTenantInvitationAction;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class TenantInvitationController extends Controller
{
    public function store(Request $request, Tenant $tenant, CreateTenantInvitationAction $action): RedirectResponse
    {
        Gate::authorize('invite', $tenant);

        $attributes = $request->validate([
            'email' => ['required', 'email'],
            'role' => ['required', 'in:owner,admin,member'],
        ]);

        $action->execute($tenant, $attributes['email'], $attributes['role'], $request->user());

        return redirect()->route('workspaces.team', $tenant)
            ->with('status', 'Invitation sent.');
    }

    public function resend(Request $request, Tenant $tenant, TenantInvitation $invitation): RedirectResponse
    {
        Gate::authorize('resend', $tenant);

        $this->ensureInvitationBelongsToTenant($tenant, $invitation);

        if ($invitation->accepted_at !== null) {
            return redirect()->route('workspaces.team', $tenant)
                ->with('status', 'Invitation already accepted.');
        }

        $invitation->forceFill([
            'token' => Str::random(64),
            'expires_at' => now()->addDays(7),
        ])->save();

        Mail::to($invitation->email)->send(new TenantInvitationMail($tenant, $invitation, $request->user()));

        return redirect()->route('workspaces.team', $tenant)
            ->with('status', 'Invitation resent.');
    }

    public function destroy(Request $request, Tenant $tenant, TenantInvitation $invitation): RedirectResponse
    {
        Gate::authorize('revoke', $tenant);

        $this->ensureInvitationBelongsToTenant($tenant, $invitation);

        $invitation->delete();

        return redirect()->route('workspaces.team', $tenant)
            ->with('status', 'Invitation revoked.');
    }

    protected function ensureInvitationBelongsToTenant(Tenant $tenant, TenantInvitation $invitation): void
    {
        if ($invitation->tenant_id !== $tenant->id) {
            abort(404);
        }
    }
}
