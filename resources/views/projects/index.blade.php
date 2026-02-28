@extends('layouts.tenant-panel')

@section('title', 'Projects')
@section('heading', 'Projects')
@section('subheading')
    Role:
    <span class="rounded bg-slate-200 px-2 py-0.5 text-xs font-semibold uppercase tracking-wide text-slate-700">{{ $currentRoleLabel }}</span>
@endsection

@section('content')
    @php
        $tenantParam = request()->route('tenant');
    @endphp

    <div class="mb-4 flex items-center justify-end">
        @can('create', App\Models\Project::class)
            <a href="{{ route('projects.create', ['tenant' => $tenantParam]) }}" class="rounded bg-slate-900 px-4 py-2 text-sm font-medium text-white hover:bg-slate-800">New Project</a>
        @endcan
    </div>

    @if ($currentRole === 'member')
        <div class="mb-4 rounded-md border border-amber-200 bg-amber-50 px-4 py-3 text-sm text-amber-800">
            You have read-only access in this workspace.
        </div>
    @endif

    <div class="overflow-hidden rounded-lg border border-slate-200 bg-white">
        @forelse ($projects as $project)
            <div class="flex items-center justify-between border-b border-slate-100 px-5 py-4 last:border-b-0">
                <div class="font-medium text-slate-900">{{ $project->name }}</div>
                <div class="flex items-center gap-4 text-sm">
                    @can('update', $project)
                        <a href="{{ route('projects.edit', ['tenant' => $tenantParam, 'project' => $project]) }}" class="text-slate-700 hover:text-slate-900">Edit</a>
                    @endcan
                    @can('delete', $project)
                        <form method="POST" action="{{ route('projects.destroy', ['tenant' => $tenantParam, 'project' => $project]) }}">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-600 hover:text-red-700">Delete</button>
                        </form>
                    @endcan
                </div>
            </div>
        @empty
            <div class="px-5 py-8 text-sm text-slate-600">No projects yet.</div>
        @endforelse
    </div>
@endsection
