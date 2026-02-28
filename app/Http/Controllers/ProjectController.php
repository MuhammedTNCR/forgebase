<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use App\Models\Project;
use App\Support\Tenancy\TenantContext;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\View\View;

class ProjectController extends Controller
{
    public function index(string $tenant): View
    {
        Gate::authorize('viewAny', Project::class);

        $projects = Project::query()->latest()->get();
        $tenantId = app(TenantContext::class)->id();
        $recentActivity = ActivityLog::query()
            ->with('actor')
            ->where('tenant_id', $tenantId)
            ->latest()
            ->limit(20)
            ->get();
        $currentRole = auth()->user()?->tenants()->whereKey($tenantId)->first()?->pivot?->role;
        $currentRoleLabel = match ($currentRole) {
            'owner' => 'Owner',
            'admin' => 'Admin',
            default => 'Member',
        };

        return view('projects.index', compact('projects', 'recentActivity', 'currentRole', 'currentRoleLabel'));
    }

    public function create(string $tenant): View
    {
        Gate::authorize('create', Project::class);

        return view('projects.create');
    }

    public function store(Request $request, string $tenant): RedirectResponse
    {
        Gate::authorize('create', Project::class);

        $attributes = $request->validate([
            'name' => ['required', 'string', 'max:255'],
        ]);

        Project::query()->create($attributes);

        return redirect('/projects');
    }

    public function edit(string $tenant, string $project): View
    {
        Gate::authorize('manage', Project::class);

        $project = Project::query()->findOrFail($project);

        return view('projects.edit', compact('project'));
    }

    public function update(Request $request, string $tenant, string $project): RedirectResponse
    {
        Gate::authorize('manage', Project::class);

        $project = Project::query()->findOrFail($project);

        $attributes = $request->validate([
            'name' => ['required', 'string', 'max:255'],
        ]);

        $project->update($attributes);

        return redirect('/projects');
    }

    public function destroy(string $tenant, string $project): RedirectResponse
    {
        Gate::authorize('manage', Project::class);

        $project = Project::query()->findOrFail($project);

        $project->delete();

        return redirect('/projects');
    }
}
