@extends('layouts.auth')

@section('title', 'Reset password')

@section('content')
    <div class="shell fade-up">
        <div class="panel">
            <div class="brand">
                <div class="brand-mark">FB</div>
                <div>{{ config('app.name', 'Forgebase') }}</div>
            </div>

            <h1 class="title">Reset your password</h1>
            <p class="subtitle">
                Enter your email and we will send a secure reset link.
            </p>

            @if (session('status'))
                <div class="status">{{ session('status') }}</div>
            @endif

            @if ($errors->any())
                <div class="errors">
                    {{ $errors->first() }}
                </div>
            @endif

            <form method="POST" action="{{ route('password.email') }}">
                @csrf

                <div class="input-group">
                    <label for="email">Email</label>
                    <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus>
                </div>

                <div class="meta" style="margin-top: 22px;">
                    <button type="submit" class="btn btn-primary">Send reset link</button>
                    <a class="btn btn-outline" href="{{ route('login') }}">Back to sign in</a>
                </div>
            </form>
        </div>

        <div class="aside">
            <div class="aside-card">
                <p class="mono">Secure recovery</p>
                <p class="subtitle">Reset links expire quickly and are scoped to your account.</p>
            </div>
            <div class="aside-card">
                <p class="mono">Need access?</p>
                <p class="subtitle">Ask your workspace owner to resend your invite.</p>
            </div>
        </div>
    </div>
@endsection
