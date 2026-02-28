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

    <div class="overflow-hidden rounded-lg border border-slate-200 bg-white">
        @forelse ($tenants as $tenant)
            <div class="flex items-center justify-between border-b border-slate-100 px-5 py-4 last:border-b-0">
                <div>
                    <p class="font-medium text-slate-900">{{ $tenant->name }}</p>
                    <p class="text-sm text-slate-600">
                        Role:
                        <span class="rounded bg-slate-200 px-2 py-0.5 text-xs font-semibold uppercase tracking-wide text-slate-700">{{ $roleLabel($tenant->pivot->role ?? null) }}</span>
                    </p>
                </div>
                <form method="POST" action="{{ route('workspaces.select', $tenant) }}">
                    @csrf
                    <button type="submit" class="rounded bg-slate-900 px-4 py-2 text-sm font-medium text-white hover:bg-slate-800">Open</button>
                </form>
            </div>
        @empty
            <div class="px-5 py-8 text-sm text-slate-600">No workspaces available for your account.</div>
        @endforelse
    </div>
@endsection
