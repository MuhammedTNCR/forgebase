@extends('layouts.tenant-panel')

@section('title', 'Profile')
@section('heading', 'Profile')
@section('subheading', 'Manage your account settings.')

@section('content')
    <div class="panel" style="margin-bottom: 22px;">
        <h3 class="text-base font-semibold">Account settings</h3>
        <p class="muted" style="margin-top: 8px;">Manage your profile, security, and account preferences.</p>
    </div>

    <div class="grid">
        <div class="panel">
            @include('profile.partials.update-profile-information-form')
        </div>
        <div class="panel">
            @include('profile.partials.update-password-form')
        </div>
        <div class="panel">
            @include('profile.partials.delete-user-form')
        </div>
    </div>
@endsection
