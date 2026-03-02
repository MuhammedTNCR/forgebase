@extends('layouts.tenant-panel')

@section('title', 'Workspaces')
@section('heading', 'Workspaces')
@section('subheading', 'Select a workspace to continue.')

@section('content')
    @php
        $roleLabel = static fn (?string $role): string => match ($role) {
            'owner' => 'Owner',
            'admin' => 'Admin',
            default => 'Member',
        };
    @endphp

    <div class="panel">
        <p class="section-title">Your workspaces</p>
        <div class="list">
            @forelse ($tenants as $tenant)
                <div class="row">
                    <div class="row-meta">
                        <div class="text-base font-semibold">{{ $tenant->name }}</div>
                        <div class="muted">
                            Role:
                            <span class="badge">{{ $roleLabel($tenant->pivot->role ?? null) }}</span>
                        </div>
                    </div>
                    <div class="nav">
                        <a href="{{ route('workspaces.team', $tenant) }}" class="btn btn-outline">Team</a>
                        <form method="POST" action="{{ route('workspaces.select', $tenant) }}">
                            @csrf
                            <button type="submit" class="btn btn-primary">Open</button>
                        </form>
                    </div>
                </div>
            @empty
                <div class="row">
                    <p class="muted">No workspaces available for your account.</p>
                </div>
            @endforelse
        </div>
    </div>
@endsection
