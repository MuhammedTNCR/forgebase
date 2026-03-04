<?php

declare(strict_types=1);

namespace App\Support\Teams;

use App\Events\TenantInvitationAccepted;
use App\Models\TenantInvitation;
use App\Models\User;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Support\Facades\DB;

class AcceptTenantInvitationAction
{
    public function execute(TenantInvitation $invitation, User $user): TenantInvitation
    {
        if ($invitation->accepted_at !== null) {
            return $invitation;
        }

        if ($invitation->isExpired()) {
            throw new AuthorizationException('Invitation has expired.');
        }

        if (strtolower($user->email) !== strtolower($invitation->email)) {
            throw new AuthorizationException('This invitation is for a different email address.');
        }

        $tenantId = $invitation->tenant_id;

        $existing = $user->tenants()->whereKey($tenantId)->exists();

        if (! $existing) {
            $user->tenants()->attach($tenantId, ['role' => $invitation->role]);
        }

        $invitation->forceFill([
            'accepted_at' => now(),
            'accepted_by_user_id' => $user->id,
        ])->save();

        DB::afterCommit(function () use ($invitation, $user, $tenantId): void {
            event(new TenantInvitationAccepted($invitation, $user, $tenantId, [
                'invited_email' => $invitation->email,
                'role' => $invitation->role,
                'invitation_id' => $invitation->id,
            ]));
        });

        return $invitation;
    }
}
