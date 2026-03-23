<section>
    <div class="mb-5">
        <h2 class="text-base font-semibold text-white">Profile Information</h2>
        <p class="text-sm text-slate-400 mt-1">Update your account's profile information and email address.</p>
    </div>

    <form id="send-verification" method="post" action="{{ route('verification.send') }}">@csrf</form>

    <form method="post" action="{{ route('profile.update') }}" class="space-y-5">
        @csrf
        @method('patch')

        <div>
            <label for="name" class="label block mb-1.5 text-sm">Name</label>
            <input id="name" name="name" type="text" class="input-field w-full" value="{{ old('name', $user->name) }}" required autofocus autocomplete="name" />
            @error('name') <p class="error-msg mt-1 text-xs">{{ $message }}</p> @enderror
        </div>

        <div>
            <label for="email" class="label block mb-1.5 text-sm">Email</label>
            <input id="email" name="email" type="email" class="input-field w-full" value="{{ old('email', $user->email) }}" required autocomplete="username" />
            @error('email') <p class="error-msg mt-1 text-xs">{{ $message }}</p> @enderror

            @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                <div class="mt-2 text-sm text-slate-400">
                    {{ __('Your email address is unverified.') }}
                    <button form="send-verification" class="underline text-green-400 hover:text-green-300 ml-1">Re-send verification email.</button>
                    @if (session('status') === 'verification-link-sent')
                        <p class="mt-1 font-medium text-green-400">Verification link sent!</p>
                    @endif
                </div>
            @endif
        </div>

        <div class="flex items-center gap-4">
            <button type="submit" class="btn-green">Save</button>
            @if (session('status') === 'profile-updated')
                <p x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 2000)" class="text-sm text-green-400">Saved!</p>
            @endif
        </div>
    </form>
</section>
