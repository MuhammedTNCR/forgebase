<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Tenant;
use App\Models\TenantInvitation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\View\View;

class TeamController extends Controller
{
    public function index(Request $request, Tenant $tenant): View
    {
        Gate::authorize('view', $tenant);

        $members = $tenant->users()->orderBy('name')->get();
        $invitations = TenantInvitation::query()
            ->where('tenant_id', $tenant->id)
            ->whereNull('accepted_at')
            ->orderByDesc('created_at')
            ->get();

        return view('workspaces.team', [
            'tenant' => $tenant,
            'members' => $members,
            'invitations' => $invitations,
        ]);
    }
}
