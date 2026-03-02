<?php

declare(strict_types=1);

namespace App\Support\Teams;

use App\Mail\TenantInvitationMail;
use App\Models\Tenant;
use App\Models\TenantInvitation;
use App\Models\User;
use App\Support\Activity\ActivityLogger;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class CreateTenantInvitationAction
{
    public function execute(Tenant $tenant, string $email, string $role, ?User $inviter): TenantInvitation
    {
        $normalizedEmail = strtolower(trim($email));

        $existing = TenantInvitation::query()
            ->where('tenant_id', $tenant->id)
            ->where('email', $normalizedEmail)
            ->active()
            ->first();

        if ($existing) {
            $existing->forceFill([
                'token' => Str::random(64),
                'expires_at' => now()->addDays(7),
            ])->save();

            Mail::to($normalizedEmail)->send(new TenantInvitationMail($tenant, $existing, $inviter));

            app(ActivityLogger::class)->log('team.invited', null, [
                'invited_email' => $normalizedEmail,
                'role' => $existing->role,
                'invitation_id' => $existing->id,
            ], $inviter, $tenant->id);

            return $existing;
        }

        $invitation = TenantInvitation::query()->create([
            'tenant_id' => $tenant->id,
            'email' => $normalizedEmail,
            'role' => $role,
            'token' => Str::random(64),
            'invited_by_user_id' => $inviter?->id,
            'expires_at' => now()->addDays(7),
        ]);

        Mail::to($normalizedEmail)->send(new TenantInvitationMail($tenant, $invitation, $inviter));

        app(ActivityLogger::class)->log('team.invited', null, [
            'invited_email' => $normalizedEmail,
            'role' => $role,
            'invitation_id' => $invitation->id,
        ], $inviter, $tenant->id);

        return $invitation;
    }
}
