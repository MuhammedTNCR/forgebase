<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\Tenant;
use App\Models\User;

class TeamPolicy
{
    public function view(User $user, Tenant $tenant): bool
    {
        return $this->roleFor($user, $tenant) !== null;
    }

    public function invite(User $user, Tenant $tenant): bool
    {
        return in_array($this->roleFor($user, $tenant), ['owner', 'admin'], true);
    }

    public function revoke(User $user, Tenant $tenant): bool
    {
        return $this->invite($user, $tenant);
    }

    public function resend(User $user, Tenant $tenant): bool
    {
        return $this->invite($user, $tenant);
    }

    protected function roleFor(User $user, Tenant $tenant): ?string
    {
        $membership = $user->tenants()->whereKey($tenant->id)->first();

        return $membership?->pivot?->role;
    }
}
