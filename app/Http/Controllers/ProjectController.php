<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\Tenant;
use App\Models\ActivityLog;
use App\Support\Tenancy\TenantContext;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\DB;
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

        $tenantId = app(TenantContext::class)->id()
            ?? Tenant::query()->where('slug', $tenant)->value('id');

        $project = Project::query()->whereKey($project)
            ->where('tenant_id', $tenantId)
            ->firstOrFail();

        return view('projects.edit', compact('project'));
    }

    public function update(Request $request, string $tenant, string $project): RedirectResponse
    {
        Gate::authorize('manage', Project::class);

        $tenantId = app(TenantContext::class)->id()
            ?? Tenant::query()->where('slug', $tenant)->value('id');

        $project = Project::query()->whereKey($project)
            ->where('tenant_id', $tenantId)
            ->firstOrFail();

        $attributes = $request->validate([
            'name' => ['required', 'string', 'max:255'],
        ]);

        $project->update($attributes);

        return redirect('/projects');
    }

    public function destroy(Request $request, string $tenant, string $project): RedirectResponse
    {
        Gate::authorize('manage', Project::class);

        $tenantId = app(TenantContext::class)->id()
            ?? Tenant::query()->where('slug', $tenant)->value('id');

        $row = DB::table('projects')
            ->where('id', $project)
            ->where('tenant_id', $tenantId)
            ->first();

        if (! $row) {
            abort(404);
        }

        $projectModel = (new Project())->newFromBuilder((array) $row);

        DB::table('projects')
            ->where('id', $project)
            ->where('tenant_id', $tenantId)
            ->delete();

        DB::afterCommit(function () use ($projectModel): void {
            event(new \App\Events\ProjectDeleted($projectModel, auth()->user(), $projectModel->tenant_id));
        });

        return redirect('/projects');
    }
}
