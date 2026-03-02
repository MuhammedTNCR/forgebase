@extends('layouts.tenant-panel')

@section('title', 'Team')
@section('heading', 'Team')
@section('subheading')
    Manage members for {{ $tenant->name }}
@endsection

@section('content')
    @php
        $roleLabel = static fn (?string $role): string => match ($role) {
            'owner' => 'Owner',
            'admin' => 'Admin',
            default => 'Member',
        };
    @endphp

    @can('invite', $tenant)
        <div class="panel" style="margin-bottom: 22px;">
            <p class="section-title">Invite someone</p>
            <form method="POST" action="{{ route('workspaces.invitations.store', $tenant) }}" class="form">
                @csrf
                <div>
                    <label for="invite-email">Email</label>
                    <input id="invite-email" name="email" type="email" required value="{{ old('email') }}">
                    @error('email')
                        <p class="error">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label for="invite-role">Role</label>
                    <select id="invite-role" name="role">
                        <option value="member" @selected(old('role') === 'member')>Member</option>
                        <option value="admin" @selected(old('role') === 'admin')>Admin</option>
                        <option value="owner" @selected(old('role') === 'owner')>Owner</option>
                    </select>
                    @error('role')
                        <p class="error">{{ $message }}</p>
                    @enderror
                </div>
                <button type="submit" class="btn btn-primary">Send invite</button>
            </form>
        </div>
    @endcan

    <div class="grid" style="margin-bottom: 22px;">
        <div class="panel">
            <p class="section-title">Members</p>
            <div class="list">
                @forelse ($members as $member)
                    <div class="row">
                        <div class="row-meta">
                            <div class="text-base font-semibold">{{ $member->name ?? $member->email }}</div>
                            <div class="muted">{{ $member->email }}</div>
                        </div>
                        <span class="badge">{{ $roleLabel($member->pivot->role ?? null) }}</span>
                    </div>
                @empty
                    <div class="row">
                        <p class="muted">No members yet.</p>
                    </div>
                @endforelse
            </div>
        </div>

        <div class="panel">
            <p class="section-title">Pending invitations</p>
            <div class="list">
                @forelse ($invitations as $invitation)
                    <div class="row">
                        <div class="row-meta">
                            <div class="text-base font-semibold">{{ $invitation->email }}</div>
                            <div class="muted">Role: {{ $roleLabel($invitation->role) }}</div>
                            @if ($invitation->expires_at)
                                <div class="muted">Expires {{ $invitation->expires_at->diffForHumans() }}</div>
                            @endif
                        </div>
                        @can('invite', $tenant)
                            <div class="nav">
                                <form method="POST" action="{{ route('workspaces.invitations.resend', [$tenant, $invitation]) }}">
                                    @csrf
                                    <button type="submit" class="btn btn-outline">Resend</button>
                                </form>
                                <form method="POST" action="{{ route('workspaces.invitations.destroy', [$tenant, $invitation]) }}">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger">Revoke</button>
                                </form>
                            </div>
                        @endcan
                    </div>
                @empty
                    <div class="row">
                        <p class="muted">No pending invitations.</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
@endsection
