@extends('layouts.tenant-panel')

@section('title', 'Projects')
@section('heading', 'Projects')
@section('subheading')
    Role:
    <span class="badge">{{ $currentRoleLabel }}</span>
@endsection

@section('content')
    <div class="panel" style="margin-bottom: 22px;">
        <div class="nav" style="justify-content: flex-end; margin-bottom: 12px;">
            @can('create', App\Models\Project::class)
                <a href="{{ route('projects.create', ['tenant' => request()->route('tenant')]) }}" class="btn btn-primary">New Project</a>
            @endcan
        </div>

        @if ($currentRole === 'member')
            <div class="status" style="border-color: rgba(246, 167, 35, 0.4); background: rgba(246, 167, 35, 0.12); color: var(--accent-strong);">
                You have read-only access in this workspace.
            </div>
        @endif

        <div class="list">
            @forelse ($projects as $project)
                <div class="row">
                    <div class="row-meta">
                        <div class="text-base font-semibold">{{ $project->name }}</div>
                    </div>
                    <div class="nav">
                        @can('update', $project)
                            <a href="{{ route('projects.edit', ['tenant' => request()->route('tenant'), 'project' => $project]) }}" class="btn btn-outline">Edit</a>
                        @endcan
                        @can('delete', $project)
                            <form method="POST" action="{{ route('projects.destroy', ['tenant' => request()->route('tenant'), 'project' => $project]) }}">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger">Delete</button>
                            </form>
                        @endcan
                    </div>
                </div>
            @empty
                <div class="row">
                    <p class="muted">No projects yet.</p>
                </div>
            @endforelse
        </div>
    </div>

    <div class="panel">
        <p class="section-title">Recent activity (last 20)</p>
        <div class="list">
            @forelse ($recentActivity as $entry)
                <div class="row">
                    <div class="row-meta">
                        <div class="text-base font-semibold">{{ $entry->actor?->name ?? 'System' }}</div>
                        <div class="muted">{{ $entry->action }}</div>
                    </div>
                    <div class="muted">{{ $entry->created_at?->diffForHumans() }}</div>
                </div>
            @empty
                <div class="row">
                    <p class="muted">No activity yet.</p>
                </div>
            @endforelse
        </div>
    </div>
@endsection
