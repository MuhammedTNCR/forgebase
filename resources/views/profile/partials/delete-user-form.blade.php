<section>
    <header>
        <h2 class="text-base font-semibold">{{ __('Delete Account') }}</h2>
        <p class="muted" style="margin-top: 8px;">
            {{ __('Once your account is deleted, all of its resources and data will be permanently deleted. Before deleting your account, please download any data or information that you wish to retain.') }}
        </p>
    </header>

    <form method="post" action="{{ route('profile.destroy') }}" class="form" style="margin-top: 16px;">
        @csrf
        @method('delete')

        <div>
            <label for="password">{{ __('Confirm Password') }}</label>
            <input id="password" name="password" type="password" placeholder="{{ __('Password') }}">
            @if ($errors->userDeletion->has('password'))
                <p class="error">{{ $errors->userDeletion->first('password') }}</p>
            @endif
        </div>

        <div class="nav">
            <button type="submit" class="btn btn-danger">{{ __('Delete Account') }}</button>
        </div>
    </form>
</section>
