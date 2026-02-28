<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Support\Tenancy\TenantContext;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\View\View;

class ProjectController extends Controller
{
    public function index(): View
    {
        Gate::authorize('viewAny', Project::class);

        $projects = Project::query()->latest()->get();
        $tenantId = app(TenantContext::class)->id();
        $currentRole = auth()->user()?->tenants()->whereKey($tenantId)->first()?->pivot?->role;
        $currentRoleLabel = match ($currentRole) {
            'owner' => 'Owner',
            'admin' => 'Admin',
            default => 'Member',
        };

        return view('projects.index', compact('projects', 'currentRole', 'currentRoleLabel'));
    }

    public function create(): View
    {
        Gate::authorize('create', Project::class);

        return view('projects.create');
    }

    public function store(Request $request): RedirectResponse
    {
        Gate::authorize('create', Project::class);

        $attributes = $request->validate([
            'name' => ['required', 'string', 'max:255'],
        ]);

        Project::query()->create($attributes);

        return redirect('/projects');
    }

    public function edit(string $project): View
    {
        Gate::authorize('manage', Project::class);

        $project = Project::query()->findOrFail($project);

        return view('projects.edit', compact('project'));
    }

    public function update(Request $request, string $project): RedirectResponse
    {
        Gate::authorize('manage', Project::class);

        $project = Project::query()->findOrFail($project);

        $attributes = $request->validate([
            'name' => ['required', 'string', 'max:255'],
        ]);

        $project->update($attributes);

        return redirect('/projects');
    }

    public function destroy(string $project): RedirectResponse
    {
        Gate::authorize('manage', Project::class);

        $project = Project::query()->findOrFail($project);

        $project->delete();

        return redirect('/projects');
    }
}
