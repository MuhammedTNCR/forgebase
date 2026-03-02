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

    @if (session('status'))
        <div class="mb-4 rounded-md border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-800">
            {{ session('status') }}
        </div>
    @endif

    @can('invite', $tenant)
        <div class="mb-6 overflow-hidden rounded-lg border border-slate-200 bg-white">
            <div class="border-b border-slate-100 px-5 py-4">
                <h2 class="text-sm font-semibold uppercase tracking-wide text-slate-600">Invite someone</h2>
            </div>
            <form method="POST" action="{{ route('workspaces.invitations.store', $tenant) }}" class="space-y-4 px-5 py-4">
                @csrf
                <div>
                    <label class="text-sm font-medium text-slate-700" for="invite-email">Email</label>
                    <input id="invite-email" name="email" type="email" required class="mt-2 w-full rounded border border-slate-200 px-3 py-2 text-sm" value="{{ old('email') }}">
                    @error('email')
                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label class="text-sm font-medium text-slate-700" for="invite-role">Role</label>
                    <select id="invite-role" name="role" class="mt-2 w-full rounded border border-slate-200 px-3 py-2 text-sm">
                        <option value="member" @selected(old('role') === 'member')>Member</option>
                        <option value="admin" @selected(old('role') === 'admin')>Admin</option>
                        <option value="owner" @selected(old('role') === 'owner')>Owner</option>
                    </select>
                    @error('role')
                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                <button type="submit" class="rounded bg-slate-900 px-4 py-2 text-sm font-medium text-white hover:bg-slate-800">Send invite</button>
            </form>
        </div>
    @endcan

    <div class="mb-6 overflow-hidden rounded-lg border border-slate-200 bg-white">
        <div class="border-b border-slate-100 px-5 py-4">
            <h2 class="text-sm font-semibold uppercase tracking-wide text-slate-600">Members</h2>
        </div>
        @forelse ($members as $member)
            <div class="flex items-center justify-between border-b border-slate-100 px-5 py-4 last:border-b-0">
                <div>
                    <p class="font-medium text-slate-900">{{ $member->name ?? $member->email }}</p>
                    <p class="text-sm text-slate-600">{{ $member->email }}</p>
                </div>
                <span class="rounded bg-slate-200 px-2 py-0.5 text-xs font-semibold uppercase tracking-wide text-slate-700">
                    {{ $roleLabel($member->pivot->role ?? null) }}
                </span>
            </div>
        @empty
            <div class="px-5 py-8 text-sm text-slate-600">No members yet.</div>
        @endforelse
    </div>

    <div class="overflow-hidden rounded-lg border border-slate-200 bg-white">
        <div class="border-b border-slate-100 px-5 py-4">
            <h2 class="text-sm font-semibold uppercase tracking-wide text-slate-600">Pending invitations</h2>
        </div>
        @forelse ($invitations as $invitation)
            <div class="flex flex-col gap-3 border-b border-slate-100 px-5 py-4 last:border-b-0 sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <p class="font-medium text-slate-900">{{ $invitation->email }}</p>
                    <p class="text-sm text-slate-600">Role: {{ $roleLabel($invitation->role) }}</p>
                    @if ($invitation->expires_at)
                        <p class="text-xs text-slate-500">Expires {{ $invitation->expires_at->diffForHumans() }}</p>
                    @endif
                </div>
                @can('invite', $tenant)
                    <div class="flex items-center gap-3 text-sm">
                        <form method="POST" action="{{ route('workspaces.invitations.resend', [$tenant, $invitation]) }}">
                            @csrf
                            <button type="submit" class="text-slate-700 hover:text-slate-900">Resend</button>
                        </form>
                        <form method="POST" action="{{ route('workspaces.invitations.destroy', [$tenant, $invitation]) }}">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-600 hover:text-red-700">Revoke</button>
                        </form>
                    </div>
                @endcan
            </div>
        @empty
            <div class="px-5 py-8 text-sm text-slate-600">No pending invitations.</div>
        @endforelse
    </div>
@endsection
