@extends('layouts.auth')

@section('title', 'Verify email')

@section('content')
    <div class="shell fade-up">
        <div class="panel">
            <div class="brand">
                <div class="brand-mark">FB</div>
                <div>{{ config('app.name', 'Forgebase') }}</div>
            </div>

            <h1 class="title">Verify your email</h1>
            <p class="subtitle">
                Before getting started, confirm your email address by clicking the link we sent you.
            </p>

            @if (session('status') === 'verification-link-sent')
                <div class="status">A new verification link has been sent to your email.</div>
            @endif

            <div class="meta" style="margin-top: 22px;">
                <form method="POST" action="{{ route('verification.send') }}">
                    @csrf
                    <button type="submit" class="btn btn-primary">Resend verification email</button>
                </form>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="btn btn-outline">Log out</button>
                </form>
            </div>
        </div>

        <div class="aside">
            <div class="aside-card">
                <p class="mono">Almost there</p>
                <p class="subtitle">Verify once to unlock your workspace dashboard.</p>
            </div>
            <div class="aside-card">
                <p class="mono">No email?</p>
                <p class="subtitle">Check spam or resend a fresh verification link.</p>
            </div>
        </div>
    </div>
@endsection
