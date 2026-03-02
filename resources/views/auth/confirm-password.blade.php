@extends('layouts.auth')

@section('title', 'Confirm password')

@section('content')
    <div class="shell fade-up">
        <div class="panel">
            <div class="brand">
                <div class="brand-mark">FB</div>
                <div>{{ config('app.name', 'Forgebase') }}</div>
            </div>

            <h1 class="title">Confirm your password</h1>
            <p class="subtitle">This is a secure area. Please confirm your password to continue.</p>

            @if ($errors->any())
                <div class="errors">
                    {{ $errors->first() }}
                </div>
            @endif

            <form method="POST" action="{{ route('password.confirm') }}">
                @csrf

                <div class="input-group">
                    <label for="password">Password</label>
                    <input id="password" type="password" name="password" required autocomplete="current-password">
                </div>

                <div class="meta" style="margin-top: 22px;">
                    <button type="submit" class="btn btn-primary">Confirm</button>
                    <a class="btn btn-outline" href="{{ route('login') }}">Back to sign in</a>
                </div>
            </form>
        </div>

        <div class="aside">
            <div class="aside-card">
                <p class="mono">Protected action</p>
                <p class="subtitle">We confirm sensitive changes with a quick password check.</p>
            </div>
            <div class="aside-card">
                <p class="mono">Security first</p>
                <p class="subtitle">Keep your account locked down with strong passwords.</p>
            </div>
        </div>
    </div>
@endsection
