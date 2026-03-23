<section>
    <div class="mb-5">
        <h2 class="text-base font-semibold text-white">Update Password</h2>
        <p class="text-sm text-slate-400 mt-1">Ensure your account is using a long, random password to stay secure.</p>
    </div>

    <form method="post" action="{{ route('password.update') }}" class="space-y-5">
        @csrf
        @method('put')

        <div>
            <label for="update_password_current_password" class="label block mb-1.5 text-sm">Current Password</label>
            <input id="update_password_current_password" name="current_password" type="password" class="input-field w-full" autocomplete="current-password" />
            @if($errors->updatePassword->get('current_password')) <p class="error-msg mt-1 text-xs">{{ $errors->updatePassword->first('current_password') }}</p> @endif
        </div>

        <div>
            <label for="update_password_password" class="label block mb-1.5 text-sm">New Password</label>
            <input id="update_password_password" name="password" type="password" class="input-field w-full" autocomplete="new-password" />
            @if($errors->updatePassword->get('password')) <p class="error-msg mt-1 text-xs">{{ $errors->updatePassword->first('password') }}</p> @endif
        </div>

        <div>
            <label for="update_password_password_confirmation" class="label block mb-1.5 text-sm">Confirm Password</label>
            <input id="update_password_password_confirmation" name="password_confirmation" type="password" class="input-field w-full" autocomplete="new-password" />
            @if($errors->updatePassword->get('password_confirmation')) <p class="error-msg mt-1 text-xs">{{ $errors->updatePassword->first('password_confirmation') }}</p> @endif
        </div>

        <div class="flex items-center gap-4">
            <button type="submit" class="btn-green">Save</button>
            @if (session('status') === 'password-updated')
                <p x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 2000)" class="text-sm text-green-400">Saved!</p>
            @endif
        </div>
    </form>
</section>
