<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Tenant;
use App\Models\TenantInvitation;
use App\Support\Teams\AcceptTenantInvitationAction;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class AcceptInvitationController extends Controller
{
    public function __invoke(Request $request, string $token, AcceptTenantInvitationAction $action): RedirectResponse
    {
        $invitation = TenantInvitation::query()->where('token', $token)->firstOrFail();
        $tenant = Tenant::query()->findOrFail($invitation->tenant_id);

        if (! $request->user()) {
            return redirect()->guest(route('login', absolute: false));
        }

        try {
            $action->execute($invitation, $request->user());
        } catch (AuthorizationException $exception) {
            abort(403, $exception->getMessage());
        }

        return redirect()->away(\App\Support\Tenancy\TenancyUrl::tenantUrl($tenant, '/projects'))
            ->with('status', 'Invitation accepted.');
    }
}
