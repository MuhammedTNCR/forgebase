<section>
    <header>
        <h2 class="text-base font-semibold">{{ __('Update Password') }}</h2>
        <p class="muted" style="margin-top: 8px;">
            {{ __('Ensure your account is using a long, random password to stay secure.') }}
        </p>
    </header>

    <form method="post" action="{{ route('password.update') }}" class="form" style="margin-top: 16px;">
        @csrf
        @method('put')

        <div>
            <label for="update_password_current_password">{{ __('Current Password') }}</label>
            <input id="update_password_current_password" name="current_password" type="password" autocomplete="current-password">
            @if ($errors->updatePassword->has('current_password'))
                <p class="error">{{ $errors->updatePassword->first('current_password') }}</p>
            @endif
        </div>

        <div>
            <label for="update_password_password">{{ __('New Password') }}</label>
            <input id="update_password_password" name="password" type="password" autocomplete="new-password">
            @if ($errors->updatePassword->has('password'))
                <p class="error">{{ $errors->updatePassword->first('password') }}</p>
            @endif
        </div>

        <div>
            <label for="update_password_password_confirmation">{{ __('Confirm Password') }}</label>
            <input id="update_password_password_confirmation" name="password_confirmation" type="password" autocomplete="new-password">
            @if ($errors->updatePassword->has('password_confirmation'))
                <p class="error">{{ $errors->updatePassword->first('password_confirmation') }}</p>
            @endif
        </div>

        <div class="nav">
            <button type="submit" class="btn btn-primary">{{ __('Save') }}</button>
            @if (session('status') === 'password-updated')
                <span class="muted">{{ __('Saved.') }}</span>
            @endif
        </div>
    </form>
</section>
