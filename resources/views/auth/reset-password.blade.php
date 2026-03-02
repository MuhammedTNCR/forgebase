@extends('layouts.auth')

@section('title', 'Set new password')

@section('content')
    <div class="shell fade-up">
        <div class="panel">
            <div class="brand">
                <div class="brand-mark">FB</div>
                <div>{{ config('app.name', 'Forgebase') }}</div>
            </div>

            <h1 class="title">Set a new password</h1>
            <p class="subtitle">Choose a strong password to secure your workspace access.</p>

            @if ($errors->any())
                <div class="errors">
                    {{ $errors->first() }}
                </div>
            @endif

            <form method="POST" action="{{ route('password.store') }}">
                @csrf
                <input type="hidden" name="token" value="{{ $request->route('token') }}">

                <div class="input-group">
                    <label for="email">Email</label>
                    <input id="email" type="email" name="email" value="{{ old('email', $request->email) }}" required autofocus autocomplete="username">
                </div>

                <div class="input-group">
                    <label for="password">Password</label>
                    <input id="password" type="password" name="password" required autocomplete="new-password">
                </div>

                <div class="input-group">
                    <label for="password_confirmation">Confirm password</label>
                    <input id="password_confirmation" type="password" name="password_confirmation" required autocomplete="new-password">
                </div>

                <div class="meta" style="margin-top: 22px;">
                    <button type="submit" class="btn btn-primary">Reset password</button>
                    <a class="btn btn-outline" href="{{ route('login') }}">Back to sign in</a>
                </div>
            </form>
        </div>

        <div class="aside">
            <div class="aside-card">
                <p class="mono">Protected access</p>
                <p class="subtitle">Reset links are single-use and time-limited for safety.</p>
            </div>
            <div class="aside-card">
                <p class="mono">Need a hand?</p>
                <p class="subtitle">Reach out to your workspace owner if access issues continue.</p>
            </div>
        </div>
    </div>
@endsection
