<?php

declare(strict_types=1);

namespace App\Support\Teams;

use App\Events\TenantInvitationCreated;
use App\Mail\TenantInvitationMail;
use App\Models\Tenant;
use App\Models\TenantInvitation;
use App\Models\User;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\DB;
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

            DB::afterCommit(function () use ($existing, $inviter, $tenant, $normalizedEmail): void {
                event(new TenantInvitationCreated($existing, $inviter, $tenant->id, [
                    'invited_email' => $normalizedEmail,
                    'role' => $existing->role,
                    'invitation_id' => $existing->id,
                ]));
            });

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

        DB::afterCommit(function () use ($invitation, $inviter, $tenant, $normalizedEmail, $role): void {
            event(new TenantInvitationCreated($invitation, $inviter, $tenant->id, [
                'invited_email' => $normalizedEmail,
                'role' => $role,
                'invitation_id' => $invitation->id,
            ]));
        });

        return $invitation;
    }
}
