<?php

namespace App\Policies;

use App\Models\Project;
use App\Models\User;
use App\Support\Tenancy\TenantContext;

class ProjectPolicy
{
    public function viewAny(User $user): bool
    {
        return $this->roleFor($user) !== null;
    }

    public function view(User $user, Project $project): bool
    {
        return $this->viewAny($user);
    }

    public function create(User $user): bool
    {
        return $this->canManage($user);
    }

    public function manage(User $user): bool
    {
        return $this->canManage($user);
    }

    public function update(User $user, Project $project): bool
    {
        return $this->canManage($user);
    }

    public function delete(User $user, Project $project): bool
    {
        return $this->canManage($user);
    }

    protected function canManage(User $user): bool
    {
        return in_array($this->roleFor($user), ['owner', 'admin'], true);
    }

    protected function roleFor(User $user): ?string
    {
        $tenantId = app(TenantContext::class)->id();

        if ($tenantId === null) {
            return null;
        }

        $tenant = $user->tenants()->whereKey($tenantId)->first();

        return $tenant?->pivot?->role;
    }
}
