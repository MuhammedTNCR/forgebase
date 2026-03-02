@extends('layouts.auth')

@section('title', 'Create account')

@section('content')
    <div class="shell fade-up">
        <div class="panel">
            <div class="brand">
                <div class="brand-mark">FB</div>
                <div>{{ config('app.name', 'Forgebase') }}</div>
            </div>

            <h1 class="title">Create your account</h1>
            <p class="subtitle">Get access to multi-tenant workspaces in minutes.</p>

            @if ($errors->any())
                <div class="errors">
                    {{ $errors->first() }}
                </div>
            @endif

            <form method="POST" action="{{ route('register') }}">
                @csrf

                <div class="input-group">
                    <label for="name">Name</label>
                    <input id="name" type="text" name="name" value="{{ old('name') }}" required autofocus autocomplete="name">
                </div>

                <div class="input-group">
                    <label for="email">Email</label>
                    <input id="email" type="email" name="email" value="{{ old('email') }}" required autocomplete="username">
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
                    <button type="submit" class="btn btn-primary">Create account</button>
                    <a class="btn btn-outline" href="{{ route('login') }}">Sign in</a>
                </div>
            </form>
        </div>

        <div class="aside">
            <div class="aside-card">
                <p class="mono">Everything included</p>
                <p class="subtitle">Projects, audit logs, and team invites are ready when you are.</p>
            </div>
            <div class="aside-card">
                <p class="mono">Stay organized</p>
                <p class="subtitle">Switch tenants fast with centralized workspace control.</p>
            </div>
        </div>
    </div>
@endsection
