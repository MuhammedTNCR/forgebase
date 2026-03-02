@extends('layouts.tenant-panel')

@section('title', 'Workspace')
@section('heading', 'Workspace Home')
@section('subheading')
    @php
        $tenantName = app(\App\Support\Tenancy\TenantContext::class)->get()?->name;
        $tenantId = app(\App\Support\Tenancy\TenantContext::class)->id();
        $role = auth()->user()?->tenants()->whereKey($tenantId)->first()?->pivot?->role;
        $roleLabel = match ($role) {
            'owner' => 'Owner',
            'admin' => 'Admin',
            default => 'Member',
        };
    @endphp
    {{ $tenantName ?? 'Workspace' }} · Role: <span class="badge">{{ $roleLabel }}</span>
@endsection

@section('content')
    @php
        $recentActivity = \App\Models\ActivityLog::query()
            ->where('tenant_id', $tenantId)
            ->latest()
            ->limit(10)
            ->get();
    @endphp

    <div class="panel" style="margin-bottom: 22px;">
        <h3 class="text-base font-semibold">Welcome to your workspace</h3>
        <p class="muted" style="margin-top: 8px;">
            Manage projects, review activity, and invite teammates from here.
        </p>
        <div class="nav" style="margin-top: 16px;">
            <a href="{{ route('projects.index', ['tenant' => request()->route('tenant')]) }}" class="btn btn-primary">Go to projects</a>
            @if ($tenantId)
                <a href="{{ route('workspaces.team', $tenantId) }}" class="btn btn-outline">Manage team</a>
            @endif
        </div>
    </div>

    <div class="panel">
        <p class="section-title">Recent activity</p>
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
