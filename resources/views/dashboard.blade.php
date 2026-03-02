@extends('layouts.tenant-panel')

@section('title', 'Dashboard')
@section('heading', 'Dashboard')
@section('subheading', 'Your central workspace overview.')

@section('content')
    <div class="panel" style="margin-bottom: 22px;">
        <h3 class="text-base font-semibold">Welcome back</h3>
        <p class="muted" style="margin-top: 8px;">
            Jump into a workspace to manage projects, activity logs, and team invitations.
        </p>
        <div class="nav" style="margin-top: 16px;">
            <a href="{{ route('workspaces.index') }}" class="btn btn-primary">View workspaces</a>
            <a href="{{ route('profile.edit') }}" class="btn btn-outline">Manage profile</a>
        </div>
    </div>

    <div class="grid">
        <div class="panel">
            <p class="section-title">Multi-tenant</p>
            <h4 class="text-base font-semibold">Workspace switching</h4>
            <p class="muted" style="margin-top: 8px;">Move between tenants quickly from the central workspace hub.</p>
        </div>
        <div class="panel">
            <p class="section-title">Activity</p>
            <h4 class="text-base font-semibold">Audit-ready actions</h4>
            <p class="muted" style="margin-top: 8px;">Track project changes, invite events, and team updates.</p>
        </div>
        <div class="panel">
            <p class="section-title">Teams</p>
            <h4 class="text-base font-semibold">Invite with confidence</h4>
            <p class="muted" style="margin-top: 8px;">Send signed email invites and manage pending approvals.</p>
        </div>
    </div>
@endsection
