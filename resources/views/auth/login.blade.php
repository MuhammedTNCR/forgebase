@extends('layouts.auth')

@section('title', 'Sign in')

@section('content')
    <div class="shell fade-up">
        <div class="panel">
            <div class="brand">
                <div class="brand-mark">FB</div>
                <div>{{ config('app.name', 'Forgebase') }}</div>
            </div>

            <h1 class="title">Welcome back</h1>
            <p class="subtitle">Sign in to manage your workspaces and recent activity.</p>

            @if (session('status'))
                <div class="status">{{ session('status') }}</div>
            @endif

            @if ($errors->any())
                <div class="errors">
                    {{ $errors->first() }}
                </div>
            @endif

            <form method="POST" action="{{ route('login') }}">
                @csrf

                <div class="input-group">
                    <label for="email">Email</label>
                    <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="username">
                </div>

                <div class="input-group">
                    <label for="password">Password</label>
                    <input id="password" type="password" name="password" required autocomplete="current-password">
                </div>

                <div class="meta">
                    <label>
                        <input type="checkbox" name="remember">
                        Remember me
                    </label>
                    @if (Route::has('password.request'))
                        <a href="{{ route('password.request') }}">Forgot your password?</a>
                    @endif
                </div>

                <div class="meta" style="margin-top: 22px;">
                    <button type="submit" class="btn btn-primary">Sign in</button>
                    <a class="btn btn-outline" href="{{ route('register') }}">Create account</a>
                </div>
            </form>
        </div>

        <div class="aside">
            <div class="aside-card">
                <p class="mono">Secure by default</p>
                <p class="subtitle">Tenant isolation, role-based access, and audit trails are ready out of the box.</p>
            </div>
            <div class="aside-card">
                <p class="mono">Quick access</p>
                <p class="subtitle">Accept team invites with signed links and jump straight into your tenant space.</p>
            </div>
        </div>
    </div>
@endsection
